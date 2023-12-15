<?php
/**
 * 2023 (c) Egio digital
 *
 * MODULE EgCustomFields
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 */


$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'eg_sticker_product`';

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'eg_sticker`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
