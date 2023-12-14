<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(dirname(__FILE__) . '/classes/EgStickerClass.php');

class EgSticker extends Module
{
    protected $domain;

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
    }

    public function install()
    {
        if (!parent::install() ||
            !$this->registerHook('actionProductAdd') ||
            !$this->registerHook('actionProductDelete') ||
            !$this->registerHook('actionProductUpdate') ||
            !$this->registerHook('displayAdminProductsExtra')
        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $id_product = (int)$params['id_product'];

        // Get the list of stickers
        $stickersList = $this->getStickersList();

        // Get the path to the module's image directory
        $imgPath = $this->_path . 'views/img/';

        // Assign the stickers list and image path to Smarty variables
        $this->context->smarty->assign(array(
            'stickerslist' => $stickersList,
            'imgPath' => $imgPath,
        ));

        // Return the HTML content
        return $this->display(__FILE__, 'views/templates/admin/admin_products_extra.tpl');
    }

    protected function getStickersList()
    {
        // Adjust the path based on your module's structure
        $stickerDir = _PS_MODULE_DIR_ . $this->name . '/views/img/';

        // Get the list of sticker files in the img directory
        $stickerFiles = scandir($stickerDir);

        $stickersList = array();
        foreach ($stickerFiles as $fileName) {
            if ($fileName !== '.' && $fileName !== '..') {
                $stickersList[] = array(
                    'img' => $fileName,
                );
            }
        }

        return $stickersList;
    }


    public function getContent()
    {
        if (Tools::isSubmit('submitUploadSticker')) {
            $this->processUploadSticker();
        }

        if (Tools::isSubmit('deleteegsticker_img')) {
            $this->context->controller->postProcess();
        }

        return $this->renderUploadSticker() . $this->renderListStickers();
    }

    protected function processUploadSticker()
    {
        $uploadedFile = $_FILES['STICKER_IMG'];

        if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
            $tempFilePath = $uploadedFile['tmp_name'];
            $destinationDir = _PS_MODULE_DIR_ . $this->name . '/views/img/';

            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0775, true);
            }

            $destinationPath = $destinationDir . basename($uploadedFile['name']);

            if (move_uploaded_file($tempFilePath, $destinationPath)) {
                $this->context->controller->confirmations[] = $this->l('Sticker image uploaded successfully.');
            } else {
                $this->context->controller->errors[] = $this->l('Error uploading sticker image.');
            }
        } else {
            $this->context->controller->errors[] = $this->l('Error uploading sticker image. Please check the file and try again.');
        }
    }

    protected function renderUploadSticker()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('UPLOAD STICKER IMAGE'),
                    'icon' => 'icon-upload'
                ),
                'input' => array(
                    array(
                        'type' => 'file',
                        'label' => $this->l('Upload new Sticker image'),
                        'name' => 'STICKER_IMG',
                        'desc' => $this->l('Upload a sticker image (transparent PNG)'),
                        'lang' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Upload'),
                    'icon' => 'process-icon-upload'
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitUploadSticker';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        return $helper->generateForm(array($fields_form));
    }

    protected function renderListStickers()
    {
        // Check if deletion action is triggered
        if (Tools::isSubmit('delete' . $this->name . '_list')) {
            $idToDelete = Tools::getValue('img');
            $this->processDeleteSticker($idToDelete);
        }

        $stickerFiles = scandir(_PS_MODULE_DIR_ . $this->name . '/views/img/');

        $data = array();
        foreach ($stickerFiles as $fileName) {
            if ($fileName !== '.' && $fileName !== '..') {
                $data[] = array(
                    'img' => $fileName,
                    'picture' => '<img src="' . $this->_path . 'views/img/' . $fileName . '" style="max-width: 100px; max-height: 100px;" />',
                );
            }
        }

        $fields_list = array(
            'img' => array(
                'title' => $this->l('File Name'),
                'align' => 'left',
                'width' => 200,
                'search' => true,
            ),
            'picture' => array(
                'title' => $this->l('Image'),
                'float' => true,
                'align' => 'left',
                'width' => 200,
                'search' => false,
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->no_link = true;
        $helper->actions = array('delete');
        $helper->identifier = 'img';
        $helper->title = $this->l('Stickers List');
        $helper->table = $this->name . '_list';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;

        return $helper->generateList($data, $fields_list);
    }

    protected function processDeleteSticker($fileName)
    {
        $filePath = _PS_MODULE_DIR_ . $this->name . '/views/img/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
            $this->context->controller->confirmations[] = $this->l('Sticker image deleted successfully.');
        } else {
            $this->context->controller->errors[] = $this->l('Error deleting sticker image. File not found.');
        }
    }

}
