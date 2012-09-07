//<?php
/**
 * ManagerManager
 * 
 * Customize the MODx Manager to offer bespoke admin functions for end users.
 *
 * @category 	plugin
 * @version 	clipper-0.3.12
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties &config_chunk=Configuration Chunk;text;mm_demo_rules; &remove_deprecated_tv_types_pref=Remove deprecated TV types;list;yes,no;yes &which_jquery=jQuery source;list;local (assets/js),remote (google code),manual url (specify below);local (assets/js) &js_src_type=jQuery URL override;text;
 * @internal	@events OnDocFormRender,OnDocFormPrerender,OnBeforeDocFormSave,OnPluginFormRender,OnTVFormRender
 * @internal	@modx_category Manager and Admin
 * @internal    @legacy_names Image TV Preview, Show Image TVs
 */

// You can put your ManagerManager rules EITHER in a chunk OR in an external file - whichever suits your development style the best

// To use an external file, put your rules in /assets/plugins/managermanager/mm_rules.inc.php 
// (you can rename default.mm_rules.inc.php and use it as an example)
// The chunk SHOULD have php opening tags at the beginning and end

// If you want to put your rules in a chunk (so you can edit them through the Manager),
// create the chunk, and enter its name in the configuration tab.
// The chunk should NOT have php tags at the beginning or end

// You don't need to change anything else from here onwards
//-------------------------------------------------------

// Run the main code
$asset_path = $modx->config['base_path'] . 'assets/plugins/managermanager/mm.inc.php';
include($asset_path);
