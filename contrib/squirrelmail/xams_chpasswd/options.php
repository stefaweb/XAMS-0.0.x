<?php
/**
 * options.php
 *
 * Copyright (c) 2003-2007 The XAMS development team <info@xams.org>
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: options.php 1058 2007-02-27 10:05:37Z stefaweb $
 *
 * @package plugins
 * @subpackage xams_chpasswd
 */

define('SM_PATH', '../../');

/* SquirrelMail required files. */
require_once(SM_PATH . 'include/validate.php');
include_once(SM_PATH . 'functions/i18n.php');
include_once(SM_PATH . 'functions/page_header.php');
include_once(SM_PATH . 'include/load_prefs.php');
include_once(SM_PATH . 'functions/html.php');
require_once(SM_PATH . 'functions/identity.php');
require_once(SM_PATH . 'plugins/xams_global/functions.php');
require_once(SM_PATH . 'functions/forms.php');

/* Switch to plugin domain so messages get translated */
bindtextdomain('xams_chpasswd', SM_PATH . 'plugins/xams_chpasswd/locale');
textdomain('xams_chpasswd');

/* Variables */
$error = array();

/* Functions */
function change_password($oldpwd, $newpwd)
{
    $username = $_SESSION['username'];

    /* Password set by SquirrelMail */
    $sm_password = OneTimePadDecrypt($_COOKIE['key'], $_SESSION['onetimepad']);

    if ($oldpwd != $sm_password)
        return 1;

    $db =& connect_db();
    $userid = get_userid($username);

    $ret = $db->query('
        UPDATE pm_users
        SET    password = ?
        WHERE  id = ?', array(md5($newpwd), $userid));

    if (DB::isError($ret))
        die($ret->getMessage());

    $db->disconnect();

    /* Set SquirrelMail password, too */
    $_SESSION['onetimepad'] = OneTimePadCreate(strlen($newpwd));
    $key = OneTimePadEncrypt($newpwd, $_SESSION['onetimepad']);
    setcookie('key', $key, 0, $_SESSION['base_uri']);

    return 0;
}

/* Redirect if user pressed cancel button */
if (gpost('cancel'))
{
    header('Location: ../../src/options.php?optmode=submit&plugin_xams_chpasswd=0');
    exit;
}

/* User pressed submit button...so lets see what we can do */
if (gpost('submit'))
{
    $oldpwd = trim(gpost('old_password'));
    $newpwd = trim(gpost('new_password'));
    $vrypwd = trim(gpost('vry_password'));

    if ($oldpwd == '')
        $error[] = _("You must type in your old password.");

    if ($newpwd == '')
        $error[] = _("You must type in a new password.");

    if (!count($error) && $oldpwd === $newpwd)
        $error[] = _("Your new password must be different than your old password.");

    if (!count($error) && $newpwd != $vrypwd)
        $error[] = _("Your new password does not match the verification password.");

    if (!count($error))
    {
        $ret = change_password($oldpwd, $newpwd);
        switch ($ret)
        {
            case 0:
                header('Location: ../../src/options.php?optmode=submit&plugin_xams_chpasswd=0');
                exit;
            case 1:
                $error[] = _("Your old password is not correct.");
                break;
            default:
                $error[] = _("Unknown error occurred.");
        }
    }
}

/* Start page building */

/* Switch to SquirrelMail domain so messages get translated */
bindtextdomain('squirrelmail', SM_PATH . 'locale');
textdomain('squirrelmail');

displayPageHeader($color, 'None');

/* Switch to plugin domain so messages get translated */
bindtextdomain('xams_chpasswd', SM_PATH . 'plugins/xams_chpasswd/locale');
textdomain('xams_chpasswd');

print '<table align="center" width="95%" border="0" cellpadding="1" cellspacing="0">' . "\n"
. '<tr>'
. '<td bgcolor="' . $color[0] . '" align="center">'
. '<b>' .  _("Change password for ") . $_SESSION['username'] . '</b>'
. '<table width="100%" border="0" cellpadding="8" cellspacing="0">' . "\n"
. '<tr>' . "\n"
. '<td bgcolor="' . $color[4] . '" align="center">';

    if (count($error))
    {
        print '<p id="error">';
        foreach ($error as $e) print '<br /><font color=' . $color[2] . '">' . $e . "</font><br />\n";
        print '</p>';
    }

print "\n" . '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">' . "\n"
. '<table>' . "\n" 
. '<tr>'
. '<td align="right">' . _("Old password") . '</td>' . "\n"
. '<td>'
. '<input type="password" name="old_password" value="" size="20" />'
. '</td>' 
. '</tr>' . "\n"
. '<tr>'
. '<td align="right">' . _("New password") . '</td>'
. '<td>'
. '<input type="password" name="new_password" value="" size="20" />'
. '</td>'
. '</tr>' . "\n"
. '<tr>'
. '<td align="right">' . _("Verify new password") . '</td>' . "\n"
. '<td>'
. '<input type="password" name="vry_password" value="" size="20" />'
. '</td>'
. '</tr>' . "\n"
. '<tr>'
. '<td colspan="2" align="left"><br />'
. '<input type="submit" name="submit" value="' . _("Submit") . '">' . "\n"
. '<input type="submit" name="cancel" value="' . _("Cancel") . '">' . "\n"
. '</td>' . "\n";

print '</tr></table></form></td></tr></table></p></body></html>' . "\n"; 

/* Switch to SquirrelMail domain so messages get translated */
bindtextdomain('squirrelmail', SM_PATH . 'locale');
textdomain('squirrelmail');

?>
