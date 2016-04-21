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
 * @subpackage xams_autoreply
 */

require_once SM_PATH . 'functions/global.php';

function squirrelmail_plugin_init_xams_autoreply()
{
    global $squirrelmail_plugin_hooks;
    $squirrelmail_plugin_hooks['optpage_register_block']['xams_autoreply'] = 'xams_autoreply_options';
}

function xams_autoreply_options()
{
    include_once SM_PATH . 'plugins/xams_autoreply/functions.php';
    xams_autoreply_options_do();
}


/**
  * Returns info about this plugin
  *
  */
function xams_autoreply_info()
{

   return array(
                  'english_name' => 'XAMS Auto Reply',
                  'version' => '0.0.2',
                  'required_sm_version' => '1.4.0', 
                  'summary' => 'Configure vacation message in XAMS.',
                  'requires_configuration' => 0,
                  'requires_source_patch' => 0,
               );

}


/**
  * Returns version info about this plugin
  *
  */
function xams_autoreply_version() 
{

   $info = xams_autoreply_info();
   return $info['version'];

}


?>
