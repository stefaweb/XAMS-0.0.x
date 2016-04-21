<?php

/**
 * XAMS Password Driver
 *
 * Driver for passwords stored in XAMS database
 *
 * @version 0.1.1
 * @author Michael Kefeder <mike@weird-birds.org>
 *
 * Modified by Stephane Leclerc <sleclerc@actionweb.fr>
 *
 * Based on sql.php by Aleksander 'A.L.E.C' Machniak <alec@alec.pl>
 *
 */
define('_XAMS_CONFIG_FILE', '/etc/xams/xams.conf');

function password_save($curpass, $passwd)
{
    $rcmail = rcmail::get_instance();

    $ini = parse_ini_file(_XAMS_CONFIG_FILE, true);
    if (!$ini) die('Couldn\'t load '. _XAMS_CONFIG_FILE);

    $dsn = array();
    $dsn['new_link'] = true;
    $dsn['phptype'] = 'mysql';
    $dsn['hostspec'] = $ini['GUI']['DBHost'];
    $dsn['database'] = $ini['GUI']['DB'];
    $dsn['username'] = $ini['GUI']['DBUser'];
    $dsn['password'] = $ini['GUI']['DBPass'];


    $db = new rcube_mdb2($dsn, '', FALSE);
    $db->set_debug((bool)$rcmail->config->get('sql_debug'));
    $db->db_connect('w');

    if ($err = $db->is_error())
    {
        return PASSWORD_ERROR;
    }

    $user_info = explode('@', $_SESSION['username']);

    if (count($user_info) == 2) 
    {
        $user = $user_info[0];
        $domain = $user_info[1];
    }
    else
    {
        return PASSWORD_ERROR;
    } 

    $sql = "UPDATE _users SET password = ";
    $sql = str_replace('%h', $db->quote($_SESSION['imap_host'],'text'), $sql);
    $sql = str_replace('%p', $db->quote($passwd,'text'), $sql);

    # TODO: make it work for unique users
    $types = array('text');
    $sql = 'SELECT siteid FROM pm_domains WHERE name = %domainname';
    $sql = str_replace('%domainname', $db->quote($domain,'text'), $sql);
    $res = $db->query($sql);
    if (!$db->is_error())
    { 
        $values = $db->fetch_array($res);
    }
    else
    {
        return PASSWORD_ERROR;
    }

    if (count($values) < 1)
    {
        return PASSWORD_ERROR;
    }

    $siteid = $values[0];
    
    $sql = 'UPDATE pm_users SET password = :newpass WHERE siteid = :siteid AND name = :username';
    $sql = str_replace(':siteid', $db->quote($siteid,'integer'), $sql);
    $sql = str_replace(':newpass', $db->quote(md5($passwd),'text'), $sql);
    $sql = str_replace(':username', $db->quote($user,'text'), $sql);
    $res = $db->query($sql);

    if (!$db->is_error())
    {
            return PASSWORD_SUCCESS;
    }
    return PASSWORD_ERROR;
}

?>
