<?php
/**
 * functions.php
 *
 * Copyright (c) 2003-2007 The XAMS development team <info@xams.org>
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: functions.php 1058 2007-02-27 10:05:37Z stefaweb $
 *
 * @package plugins
 * @subpackage xams_chpasswd
 */

function xams_chpasswd_options_do()
{
    global $optpage_blocks;
    
    /* Switch to plugin domain so messages get translated */
    bindtextdomain('xams_chpasswd', SM_PATH . 'plugins/xams_chpasswd/locale');
    textdomain('xams_chpasswd');

    $optpage_blocks[] = array(
        'name' => _("Password changing"),
        'url' => SM_PATH . 'plugins/xams_chpasswd/options.php',
        'desc' => _("Here you can change the login password for your mail-account."),
        'js' => false
    );

    /* Switch to SquirrelMail domain so messages get translated */
    bindtextdomain('squirrelmail', SM_PATH . 'locale');
    textdomain('squirrelmail');
}

?>
