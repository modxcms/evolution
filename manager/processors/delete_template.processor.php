<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('delete_template')) {	
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id=intval($_GET['id']);

// delete the template, but first check it doesn't have any documents using it
$rs = $modx->db->select('id, pagetitle,introtext', $modx->getFullTableName('site_content'), "template='{$id}' AND deleted=0");
$limit = $modx->db->getRecordCount($rs);
if($limit>0) {
	echo "This template is in use. Please set the documents using the template to another template. Documents using this template:<br />";
	for ($i=0;$i<$limit;$i++) {
		$row = $modx->db->getRow($rs);
		echo $row['id']." - ".$row['pagetitle']."<br />\n";
	}	
	exit;
}

if($id==$default_template) {
	$modx->webAlertAndQuit("This template is set as the default template. Please choose a different default template in the MODX configuration before deleting this template.<br />");
}

// invoke OnBeforeTempFormDelete event
$modx->invokeEvent("OnBeforeTempFormDelete",
						array(
							"id"	=> $id
						));
						
// Set the item name for logger
$name = $modx->db->getValue($modx->db->select('templatename', $modx->getFullTableName('site_templates'), "id='{$id}'"));
$_SESSION['itemname'] = $name;

//ok, delete the document.
$modx->db->delete($modx->getFullTableName('site_templates'), "id='{$id}'");

	$modx->db->delete($modx->getFullTableName('site_tmplvar_templates'), "templateid='{$id}'");
			
	// invoke OnTempFormDelete event
	$modx->invokeEvent("OnTempFormDelete",
							array(
								"id"	=> $id
							));

	// empty cache
	$modx->clearCache('full');
	
	// finished emptying cache - redirect
	$header="Location: index.php?a=76&r=2";
	header($header);
?>