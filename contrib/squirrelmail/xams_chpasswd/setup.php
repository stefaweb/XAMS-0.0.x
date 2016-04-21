<?php
/**
 * setup.php
 *
 * Copyright (c) 2003-2007 The XAMS development team <info@xams.org>
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 * 
 * $Id: setup.php 1059 2007-02-27 11:54:59Z stefaweb $
 *
 * @package plugins
 * @subpackage xams_chpasswd
 */

function squirrelmail_plugin_init_xams_chpasswd()
{
    global $squirrelmail_plugin_hooks;
    $squirrelmail_plugin_hooks['optpage_register_block']['xams_chpasswd'] = 'xams_chpasswd_options';
}

function xams_chpasswd_options()
{
    include_once SM_PATH . 'plugins/xams_chpasswd/functions.php';
    xams_chpasswd_options_do();
}

/* Returns info about this plugin */
function xams_chpasswd_info()
{

   return array(
                  'english_name' => 'XAMS Change Account Password',
                  'version' => '0.0.3',
                  'required_sm_version' => '1.4.0', 
                  'summary' => 'Permit to change XAMS password account directly from SquirrelMail.',
                  'requires_configuration' => 0,
                  'requires_source_patch' => 0,
               );

}

/* Returns version info about this plugin */
function xams_chpasswd_version() 
{

   $info = xams_chpasswd_info();
   return $info['version'];

}

?>
