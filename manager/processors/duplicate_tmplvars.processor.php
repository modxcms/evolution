<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('edit_template')) {
	$e->setError(3);
	$e->dumpError();
}
?>
<?php

$id=$_GET['id'];

// duplicate TV
if (version_compare(mysql_get_server_info(),"4.0.14")>=0) {
	$sql = "INSERT INTO $dbase.`".$table_prefix."site_tmplvars` (type, name, caption, description, default_text, elements, rank, display, display_params)
			SELECT type, name, CONCAT('Duplicate of ',caption) AS 'caption', description, default_text, elements, rank, display, display_params
			FROM $dbase.`".$table_prefix."site_tmplvars` WHERE id=$id;";
	$rs = mysql_query($sql);
}
else {
	$sql = "SELECT type, name, CONCAT('Duplicate of ',caption) AS 'caption', description, default_text, elements, rank, display, display_params
			FROM $dbase.`".$table_prefix."site_tmplvars` WHERE id=$id;";
	$rs = mysql_query($sql);
	if($rs) {
		$row = mysql_fetch_assoc($rs);
		$sql = "INSERT INTO $dbase.`".$table_prefix."site_tmplvars`
				(type, name, caption, description, default_text, elements, rank, display, display_params) VALUES
				('".$row['type']."', '".mysql_escape_string($row['name'])."', '".mysql_escape_string($row['caption'])."', '".mysql_escape_string($row['description'])."', '".mysql_escape_string($row['default_text'])."', '".mysql_escape_string($row['elements'])."', '".$row['rank']."', '".$row['display']."', '".mysql_escape_string($row['display_params'])."');";
		$rs = mysql_query($sql);
	}
}
if($rs) $newid = mysql_insert_id(); // get new id
else {
	echo "A database error occured while trying to duplicate TV: <br /><br />".mysql_error();
	exit;
}


// duplicate TV Template Access Permissions
if (version_compare(mysql_get_server_info(),"4.0.14")>=0) {
	$sql = "INSERT INTO $dbase.`".$table_prefix."site_tmplvar_templates` (tmplvarid, templateid)
			SELECT $newid, templateid
			FROM $dbase.`".$table_prefix."site_tmplvar_templates` WHERE tmplvarid=$id;";
	$rs = mysql_query($sql);
}
else {
	$sql = "SELECT $newid as 'newid', templateid
			FROM $dbase.`".$table_prefix."site_tmplvar_templates` WHERE tmplvarid=$id;";
	$ds = mysql_query($sql);
	if($ds) while($row = mysql_fetch_assoc($ds)) {
		$sql = "INSERT INTO $dbase.`".$table_prefix."site_tmplvar_templates`
				(tmplvarid, templateid) VALUES
				('".$row['newid']."', '".$row['templateid']."');";
		$rs = mysql_query($sql);
	}
}
if (!$rs) {
	echo "A database error occured while trying to duplicate TV template access: <br /><br />".mysql_error();
	exit;
}


// duplicate TV Access Permissions
if (version_compare(mysql_get_server_info(),"4.0.14")>=0) {
	$sql = "INSERT INTO $dbase.`".$table_prefix."site_tmplvar_access` (tmplvarid, documentgroup)
			SELECT $newid, documentgroup
			FROM $dbase.`".$table_prefix."site_tmplvar_access` WHERE tmplvarid=$id;";
	$rs = mysql_query($sql);
}
else {
	$sql = "SELECT $newid as 'newid', documentgroup
			FROM $dbase.`".$table_prefix."site_tmplvar_access` WHERE tmplvarid=$id;";
	$ds = mysql_query($sql);
	while($row = mysql_fetch_assoc($ds)) {
		$sql = "INSERT INTO $dbase.`".$table_prefix."site_tmplvar_access`
				(tmplvarid, documentgroup) VALUES
				('".$row['newid']."', '".$row['documentgroup']."');";
		$rs = mysql_query($sql);
	}
}
if (!$rs) {
	echo "A database error occured while trying to duplicate TV Acess Permissions: <br /><br />".mysql_error();
	exit;
}

// finish duplicating - redirect to new variable
$header="Location: index.php?r=2&a=301&id=$newid";
header($header);

?>
