<?php
/**
 * 2023 (c) Egio digital
 *
 * MODULE EgSticker
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 */

class EgStickerProductClass extends ObjectModel
{
    public $id_eg_sticker_product;
    public $id_eg_sticker;
    public $id_product;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'eg_sticker_product',
        'primary' => 'id_eg_sticker_product',
        'fields' => [
            'id_eg_sticker' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
        ],
    ];
}
