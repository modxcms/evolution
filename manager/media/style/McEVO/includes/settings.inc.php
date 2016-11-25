<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

// get the settings from the database.
$settings = array();
if ($modx && count($modx->config)>0) $settings = $modx->config;
else{
	$rs = $modx->db->select('setting_name, setting_value', $modx->getFullTableName('system_settings'));
	while ($row = $modx->db->getRow($rs)) {
		$settings[$row['setting_name']] = $row['setting_value'];
	}
}

extract($settings, EXTR_OVERWRITE);
// add for backwards compatibility - garryn FS#104
$etomite_charset = & $modx_manager_charset;

// setup default site id - new installation should generate a unique id for the site.
if(!isset($site_id)) $site_id = "MzGeQ2faT4Dw06+U49x3";


?>