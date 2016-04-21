<?php
/**
 * functions.php
 *
 * Copyright (c) 2003-2007 The XAMS development team <info@xams.org>
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: functions.php 1059 2007-02-27 11:54:59Z stefaweb $
 *
 * @package plugins
 * @subpackage xams_autoreply
 */

function xams_autoreply_options_do()
{
    global $optpage_blocks;

    /* Switch to plugin domain so messages get translated */
    bindtextdomain('xams_autoreply', SM_PATH . 'plugins/xams_autoreply/locale');
    textdomain('xams_autoreply');

    $optpage_blocks[] = array(
        'name' => _("Autoreply update"),
        'url' => SM_PATH . 'plugins/xams_autoreply/options.php',
        'desc' => _("Here you can enable/disable the autoreply functionality and/or change the autoreply message for your mail account."),
        'js' => false
    );
   
    /* Switch to SquirrelMail domain so messages get translated */
    bindtextdomain('squirrelmail', SM_PATH . 'locale');
    textdomain('squirrelmail');
}

?>
