<?php
/**
 * functions.php
 *
 * Copyright (c) 2003-2004 The XAMS development team <info@xams.org>
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: functions.php 1055 2007-02-27 09:08:47Z stefaweb $
 *
 * @package plugins
 * @subpackage xams_global
 */

require 'config.php';
require 'DB.php';

function gpost($varname)
{
    return (isset($_POST[$varname])) ? $_POST[$varname] : null;
}

function &connect_db()
{
    static $db = null;

    if (!is_null($db))
        return $db;

    // DB connection
    $dsn = vsprintf('%s://%s:%s@%s/%s', $GLOBALS['DATABASE_SETTINGS']);
    $db = DB::connect($dsn);
    if (DB::isError($db))
        die($db->getMessage());

    return $db;
}

function get_userid($username)
{
    if (strpos($username, '@') === false)
    {
        $sql = 'SELECT     pm_users.id
                FROM       pm_sites
                INNER JOIN pm_domains
                ON         pm_sites.id = pm_domains.siteid
                INNER JOIN pm_users
                ON         pm_sites.id = pm_users.siteid
                WHERE      pm_users.uniquename = ?';
        $sql_params = array($username);
    }
    else
    {
        $sql = 'SELECT     pm_users.id
                FROM       pm_sites
                INNER JOIN pm_domains
                ON         pm_sites.id = pm_domains.siteid
                INNER JOIN pm_users
                ON         pm_sites.id = pm_users.siteid
                WHERE      pm_users.name = ?
                AND        pm_domains.name = ?';
        $sql_params = explode('@', $username);
    }

    $db =& connect_db();

    $ret = $db->getOne($sql, $sql_params, DB_FETCHMODE_ASSOC);
    if (DB::isError($ret))
        die($ret->getMessage());

    return $ret;
}



?>
