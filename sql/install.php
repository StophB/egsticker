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

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eg_sticker` (
    `id_eg_sticker` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `image` varchar(255) NOT NULL,
    PRIMARY KEY (`id_eg_sticker`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eg_sticker_product` (
    `id_eg_sticker_product` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id_eg_sticker` int(10) unsigned NOT NULL,
    `id_product` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id_eg_sticker_product`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) === false) {
        die('Error executing query: ' . $query);
    }
}
