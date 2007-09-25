<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('new_template')) {
	$e->setError(3);
	$e->dumpError();
}
?>
<?php

$id=$_GET['id'];

// duplicate template
if (version_compare(mysql_get_server_info(),"4.0.14")>=0) {
	$sql = "INSERT INTO $dbase.`".$table_prefix."site_templates` (templatename, description, content)
			SELECT CONCAT('Duplicate of ',templatename) AS 'templatename', description, content
			FROM $dbase.`".$table_prefix."site_templates` WHERE id=$id;";
	$rs = mysql_query($sql);
}
else {
	$sql = "SELECT CONCAT('Duplicate of ',templatename) AS 'templatename', description, content
			FROM $dbase.`".$table_prefix."site_templates` WHERE id=$id;";
	$rs = mysql_query($sql);
	if($rs) {
		$row = mysql_fetch_assoc($rs);
		$sql = "INSERT INTO $dbase.`".$table_prefix."site_templates`
				(templatename, description, content) VALUES
				('".mysql_escape_string($row['templatename'])."', '".mysql_escape_string($row['description'])."','".mysql_escape_string($row['content'])."');";
		$rs = mysql_query($sql);
	}
}
if($rs) $newid = mysql_insert_id(); // get new id
else {
	echo "A database error occured while trying to duplicate variable: <br /><br />".mysql_error();
	exit;
}

// finish duplicating - redirect to new template
$header="Location: index.php?r=2&a=16&id=$newid";
header($header);

?>
