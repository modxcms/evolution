//<?php
/**
 * ManagerManager
 * 
 * Customize the MODX Manager to offer bespoke admin functions for end users.
 * 
 * @category plugin
 * @version 0.6.1
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal @properties &remove_deprecated_tv_types_pref=Remove deprecated TV types;list;yes,no;yes &config_chunk=Configuration Chunk;text;mm_rules
 * @internal @events OnDocFormRender,OnDocFormPrerender,OnBeforeDocFormSave,OnDocFormSave,OnDocDuplicate,OnPluginFormRender,OnTVFormRender
 * @internal @modx_category Manager and Admin
 * @internal @installset base
 * @internal @legacy_names Image TV Preview, Show Image TVs
 */

// You can put your ManagerManager rules EITHER in a chunk OR in an external file - whichever suits your development style the best

// To use an external file, put your rules in /assets/plugins/managermanager/mm_rules.inc.php 
// (you can rename default.mm_rules.inc.php and use it as an example)
// The chunk SHOULD have php opening tags at the beginning and end

// If you want to put your rules in a chunk (so you can edit them through the Manager),
// create the chunk, and enter its name in the configuration tab.
// The chunk should NOT have php tags at the beginning or end.

// See also user-friendly module for editing ManagerManager configuration file ddMMEditor (http://code.divandesign.biz/modx/ddmmeditor).

// ManagerManager requires jQuery 1.9.1, which is located in /assets/plugins/managermanager/js/ folder.

// You don't need to change anything else from here onwards
//-------------------------------------------------------

// Run the main code
include($modx->config['base_path'].'assets/plugins/managermanager/mm.inc.php');