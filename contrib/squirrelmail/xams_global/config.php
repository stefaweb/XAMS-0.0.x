<?php
/**
 * config.php
 *
 * Copyright (c) 2004 The XAMS development team <info@xams.org>
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: config.php 1055 2007-02-27 09:08:47Z stefaweb $
 *
 * @package plugins
 * @subpackage xams_global
 */

define('_CONFIG_FILE', '/etc/squirrelmail/xams-webmail.conf');

function getini($var)
{
    return (isset($GLOBALS['ini']['WEBMAIL'][$var])) ? $GLOBALS['ini']['WEBMAIL'][$var] : null;
}

$ini = parse_ini_file(_CONFIG_FILE, true);
if (!$ini) die('Couldn\'t load '. _CONFIG_FILE);

$DATABASE_SETTINGS = array(
    'DBType' => getini('DBType'),
    'User'   => getini('DBUser'),
    'Pwd'    => getini('DBPass'),
    'Host'   => getini('DBHost'),
    'DB'     => getini('DB')
);

?>
