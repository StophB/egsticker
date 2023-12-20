<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(dirname(__FILE__).'/classes/EgStickerClass.php');
include_once(dirname(__FILE__).'/classes/EgStickerProductClass.php');


class EgSticker extends Module
{
    protected $domain;
    protected $imgPath;

    public function __construct()
    {
        $this->name = 'egsticker';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mustapha bousfina';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->domain = 'Modules.Egsticker.Egsticker';
        $this->displayName = $this->l('Module eg sticker');
        $this->description = $this->l('Module egio sticker');
        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller ce module?');
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_
        ];

        $this->imgPath = $this->_path . 'views/img/';
    }

    public function createTabs()
    {
        $idParent = (int) Tab::getIdFromClassName('AdminEgDigital');
        if (empty($idParent)) {
            $parent_tab = new Tab();
            $parent_tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                $parent_tab->name[$lang['id_lang']] = $this->trans('Modules EGIO', array(), $this->domain);
            }
            $parent_tab->class_name = 'AdminEgDigital';
            $parent_tab->id_parent = 0;
            $parent_tab->module = $this->name;
            $parent_tab->icon = 'library_books';
            $parent_tab->add();
        }

        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('EG sticker', array(), $this->domain);
        }
        $tab->class_name = 'AdminEgStickerGeneral';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminEgDigital');
        $tab->module = $this->name;
        $tab->icon = 'library_books';
        $tab->add();

        // Menage Module
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Config', array(), $this->domain);
        }
        $tab->class_name = 'AdminEgConfSticker';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminEgStickerGeneral');
        $tab->module = $this->name;
        $tab->add();

        // Menage Sticker
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Manage Sticker', array(), $this->domain);
        }
        $tab->class_name = 'AdminEgSticker';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminEgStickerGeneral');
        $tab->module = $this->name;
        $tab->add();

        return true;
    }

    /**
     * Remove Tabs module in Dashboard
     * @param $class_name string name Tab
     * @return bool
     * @throws
     * @throws
     */
    public function removeTabs($class_name)
    {
        if ($tab_id = (int)Tab::getIdFromClassName($class_name)) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        if (!parent::install() ||
            !$this->createTabs() ||
            !$this->registerHook('actionAdminProductsControllerSaveBefore') ||
            !$this->registerHook('displayAdminProductsExtra') ||
            !$this->registerHook('displayProductListReviews')

        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');
        $this->removeTabs('AdminEgConfSticker');
        $this->removeTabs('AdminEgStickerGeneral');
        $this->removeTabs('AdminEgSticker');
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function getContent()
    {

    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $stickers = EgStickerClass::getStickers();

        $this->context->smarty->assign(array(
            'stickers' => $stickers,
            'imgPath' => $this->imgPath,
        ));

        return $this->display(__FILE__, 'views/templates/admin/admin_products_extra.tpl');
    }

    public function hookActionAdminProductsControllerSaveBefore($params)
    {
        $productSticker = new EgStickerProductClass();
        $productSticker->id_eg_sticker = (int) Tools::getValue('sticker');
        $productSticker->id_product = (int) Tools::getValue('id_product');

        return $productSticker->save();
    }

    public function hookDisplayProductListReviews($params)
    {
        $product = $params["product"];

        $stickers = EgStickerClass::getStickers();

        $this->context->smarty->assign(array(
         //   'sticker' => $productSticker,
            'imgPath' => $this->imgPath,
        ));

        return $this->display(__FILE__, 'views/templates/hook/sticker.tpl');
    }




}
