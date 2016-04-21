<?php
/**
 * options.php
 *
 * Copyright (c) 2003-2007 The XAMS development team <info@xams.org>
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: options.php 1069 2007-03-06 17:46:19Z stefaweb $
 *
 * @package plugins
 * @subpackage xams_autoreply
 */

if (!defined('SM_PATH'))
   define('SM_PATH','../../');

/* SquirrelMail required files. */
require_once(SM_PATH . 'include/validate.php');
include_once(SM_PATH . 'functions/i18n.php');
include_once(SM_PATH . 'functions/page_header.php');
include_once(SM_PATH . 'include/load_prefs.php');
include_once(SM_PATH . 'functions/html.php');
require_once(SM_PATH . 'functions/identity.php');
require_once(SM_PATH . 'plugins/xams_global/functions.php');

/* Variables */
$username = $_SESSION['username'];
$error = array();

/* Switch to plugin domain so messages get translated */
bindtextdomain('xams_autoreply', SM_PATH . 'plugins/xams_autoreply/locale');
textdomain('xams_autoreply');

/* Functions */
function load_autoreply()
{
    global $username;

    $db =& connect_db();

    $userid = get_userid($username);

    $ret = $db->getRow('
        SELECT autoreply,
               autoreplysubject,
               autoreplytext
        FROM   pm_users
        WHERE  id = ?', array($userid));

    if (DB::isError($ret))
        die($ret->getMessage());

    $db->disconnect();

    return $ret;
}

function update_autoreply($autoreply_enabled, $autoreply_subject, $autoreply_message)
{
    global $username;

    $return = 1;

    $db =& connect_db();

    $userid = get_userid($username);

    if (!empty($userid))
    {
        $data = array($autoreply_enabled, $autoreply_subject, $autoreply_message, $userid);
        $ret = $db->query('
            UPDATE pm_users
            SET    autoreply = ?,
                   autoreplysubject = ?,
                   autoreplytext = ?
            WHERE  id = ?', $data);

        if (DB::isError($ret))
            die($ret->getMessage());

        $return = 0;
    }
    else
    {
        $return = 2;
    }

    $db->disconnect();
    return $return;
}

/* Redirect if user pressed cancel button */
if (isset($_POST['cancel']))
{
    header('Location: ../../src/options.php?optmode=submit&plugin_xams_autoreply=0');
    exit;
}

/* User pressed submit button...so lets see what we can do */
if (isset($_POST['submit']))
{
    list($autoreply_enabled, $autoreply_subject, $autoreply_message) =
        array(gpost('autoreply_enabled'), gpost('autoreply_subject'), gpost('autoreply_message'));

    if ($autoreply_enabled == 'true' && (empty($autoreply_subject) || empty($autoreply_message)))
        $error[] = _("If you enable autoreply you have to enter a subject and a message.");

    if (!count($error))
    {
        $ret = update_autoreply($autoreply_enabled, $autoreply_subject, $autoreply_message);
        if ($ret == 0)
        {
            header('Location: ../../src/options.php?optmode=submit&plugin_xams_autoreply=0');
            exit;
        }
        else $error[] = _("Unknown error occurred.");
    }
}
else
{
    list($autoreply_enabled, $autoreply_subject, $autoreply_message) = load_autoreply();
}

/* Start page building */

/* Switch to SquirrelMail domain so messages get translated */
bindtextdomain('squirrelmail', SM_PATH . 'locale');
textdomain('squirrelmail');

displayPageHeader($color, 'None');

/* Switch to plugin domain so messages get translated */
bindtextdomain('xams_autoreply', SM_PATH . 'plugins/xams_autoreply/locale');
textdomain('xams_autoreply');

print '<table align="center" width="95%" border="0" cellpadding="1" cellspacing="0">' . "\n"
. '<tr>'
. '<td bgcolor="' . $color[0] . '" align="center">'
. '<b>' . _("Change autoreply settings for ") . ' ' . $username . '</b>'
. '<table width="100%" border="0" cellpadding="8" cellspacing="0">' . "\n"
. '<tr>' . "\n"
. '<td bgcolor="' . $color[4] . '" align="center">';

    if (count($error))
    {
        print '<p id="error">';
        foreach ($error as $e) print '<br /><font color=' . $color[2] . '">' . $e . "</font><br />\n";
        print '</p>';
    }

print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">' . "\n"
. '<table>' . "\n" . '<tr>'
. '<td align="right">' . _("Autoreply enabled") . '</td>' . "\n"
. '<td><input type="checkbox" name="autoreply_enabled" value="true"' . "\n";

if ($autoreply_enabled == 'true') echo ' checked="checked"';

print '>' . _("Yes")
. '</td>'
. '</tr>' . "\n"
. '<tr>'
. '<td align="right">' . _("Subject")
. '</td>'
. '<td>'
. '<input type="text" name="autoreply_subject" value="' . $autoreply_subject . '" size="40"/>'
. '</td>'
. '</tr>' . "\n"
. '<tr>'
. '<td align="right">' . _("Message")
. '</td>' . "\n"
. '<td>'
. '<textarea name="autoreply_message" rows="8" cols="70" wrap="virtual">' . $autoreply_message . '</textarea>'
. '</td>'
. '</tr>' . "\n"
. '<tr>'
. '<td colspan="2"><br />'
. '<input type="submit" name="submit" value="' . _("Submit") . '">' . "\n"
. '<input type="submit" name="cancel" value="' . _("Cancel") . '">' . "\n"
. '</td>' . "\n";

print '</tr></table></form></td></tr></table></p></body></html>' . "\n"; 

/* Switch to SquirrelMail domain so messages get translated */
bindtextdomain('squirrelmail', SM_PATH . 'locale');
textdomain('squirrelmail');

?>
