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

class EgStickerClass extends ObjectModel
{
    public $id_eg_sticker;
    public $name;
    public $image;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'eg_sticker',
        'primary' => 'id_eg_sticker',
        'fields' => [
            'name' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName'],
            'image' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName'],
        ]
    ];

    public static function getStickers()
    {
        $query = new DbQuery();
        $query->select('eg.*');
        $query->from('eg_sticker', 'eg');

        return Db::getInstance()->getRow($query);
    }

    public static function showSticker($value)
    {
        $src = __PS_BASE_URI__. 'modules/egsticker/views/img/'.$value;
        return $value ? '<img src="'.$src.'" width="40" height="40px" class="img img-thumbnail"/>' : '-';
    }

}
