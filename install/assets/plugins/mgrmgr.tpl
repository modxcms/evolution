//<?php
/**
 * ManagerManager
 *
 * Customize the MODX Manager to offer bespoke admin functions for end users.
 *
 * @category plugin
 * @version 	0.6.1b
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties &remove_deprecated_tv_types_pref=Remove deprecated TV types;list;yes,no;yes &config_chunk=Configuration Chunk;text;mm_rules
 * @internal @events OnDocFormRender,OnDocFormPrerender,OnBeforeDocFormSave,OnDocFormSave,OnDocDuplicate,OnPluginFormRender,OnTVFormRender
 * @internal @modx_category Manager and Admin
 * @internal @installset base
 * @internal @legacy_names Image TV Preview, Show Image TVs
 */

include($modx->config['base_path'].'assets/plugins/managermanager/mm.inc.php');
