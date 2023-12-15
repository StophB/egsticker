<?php

class AdminEgStickerController extends ModuleAdminController
{
    protected $position_identifier = 'id_eg_sticker';
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'eg_sticker';
        $this->className = 'EgStickerClass';
        $this->identifier = 'id_eg_sticker';
        $this->toolbar_btn = null;
        $this->list_no_link = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );

        $this->fields_list = array(
            'id_eg_sticker' => array(
                'title' => $this->l('Id')
            ),
            'image' => array(
                'title' => $this->l('Image'),
                'type' => 'text',
                'callback' => 'showSticker',
                'callback_object' => 'EgStickerClass',
                'class' => 'fixed-width-xxl',
                'search' => false,
            ),
            'name' => array(
                'title' => $this->l('Title'),
                'filter_key' => 'b!title',
            ),
        );
    }

    /**
     * @see AdminController::initPageHeaderToolbar()
     */
    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_sticker'] = array(
                'href' => self::$currentIndex.'&addeg_sticker&token='.$this->token,
                'desc' => $this->l('Add new sticker'),
                'icon' => 'process-icon-new'
            );
        }
        parent::initPageHeaderToolbar();
    }

    /**
     * @param $item
     * @return array
     */
    protected function stUploadImage($item)
    {
        $result = array(
            'error' => array(),
            'image' => '',
        );
        $types = array('gif', 'jpg', 'jpeg', 'jpe', 'png', 'svg');
        if (isset($_FILES[$item]) && isset($_FILES[$item]['tmp_name']) && !empty($_FILES[$item]['tmp_name'])) {
            $name = str_replace(strrchr($_FILES[$item]['name'], '.'), '', $_FILES[$item]['name']);

            $imageSize = @getimagesize($_FILES[$item]['tmp_name']);
            if (!empty($imageSize) &&
                ImageManager::isCorrectImageFileExt($_FILES[$item]['name'], $types)) {
                $imageName = explode('.', $_FILES[$item]['name']);
                $imageExt = $imageName[1];
                $tempName = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                $coverImageName = $name .'-'.rand(0, 1000).'.'.$imageExt;
                if ($upload_error = ImageManager::validateUpload($_FILES[$item])) {
                    $result['error'][] = $upload_error;
                } elseif (!$tempName || !move_uploaded_file($_FILES[$item]['tmp_name'], $tempName)) {
                    $result['error'][] = $this->l('An error occurred during move image.');
                } else {
                    $destinationFile = _PS_MODULE_DIR_ . $this->module->name.'/views/img/'.$coverImageName;
                    if (!ImageManager::resize($tempName, $destinationFile, null, null, $imageExt)){
                        $result['error'][] = $this->l('An error occurred during the image upload.');
                    }
                }
                if (isset($tempName)) {
                    @unlink($tempName);
                }

                if (!count($result['error'])) {
                    $result['image'] = $coverImageName;
                    $result['width'] = $imageSize[0];
                    $result['height'] = $imageSize[1];
                }
                return $result;
            }
        } else {
            return $result;
        }
    }

    /**
     * AdminController::postProcess() override
     * @see AdminController::postProcess()
     */
    public function postProcess()
    {

        if ($this->action && $this->action == 'save') {
            foreach (Language::getLanguages(true) as $lang) {
                $image = $this->stUploadImage('image_'.$lang['id_lang']);
                if (isset($image['image']) && !empty($image['image'] )) {
                    $_POST['image_'.$lang['id_lang']]= $image['image'];
                }
            }
        }

        if (Tools::isSubmit('forcedeleteImage') || Tools::getValue('deleteImage')) {
            $champ = Tools::getValue('champ');
            $imgValue = Tools::getValue('image');
            EgStickerClass::updateEgStickerImag($champ, $imgValue);
            if (Tools::isSubmit('forcedeleteImage')) {
                Tools::redirectAdmin(self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminEgSticker'));
            }
        }

        return parent::postProcess();
    }

    /**
     * @see AdminController::initProcess()
     */
    public function initProcess()
    {
        $this->context->smarty->assign(array(
            'uri' => $this->module->getPathUri()
        ));
        parent::initProcess();
    }


    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Page'),
                'icon' => 'icon-folder-close'
            ),
            // custom template
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Name:'),
                    'name' => 'title',
                    'desc' => $this->l('Please enter a Name for the sticker.'),
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Image:'),
                    'name' => 'image',
                    'delete_url' => self::$currentIndex.'&'.$this->identifier .'='.$obj->id.'&token='.$this->token.'&champ=image&deleteImage=1',
                    'desc' => $this->l('Upload an image for your sticker.')
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        return parent::renderForm();
    }
}
