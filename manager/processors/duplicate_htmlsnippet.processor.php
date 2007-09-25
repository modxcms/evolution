<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('new_snippet')) {
	$e->setError(3);
	$e->dumpError();
}
?>
<?php

$id=$_GET['id'];

// duplicate htmlsnippet
if (version_compare(mysql_get_server_info(),"4.0.14")>=0) {
	$sql = "INSERT INTO $dbase.`".$table_prefix."site_htmlsnippets` (name, description, snippet)
			SELECT CONCAT('Duplicate of ',name) AS 'name', description, snippet
			FROM $dbase.`".$table_prefix."site_htmlsnippets` WHERE id=$id;";
	$rs = mysql_query($sql);
}
else {
	$sql = "SELECT CONCAT('Duplicate of ',name) AS 'name', description, snippet
			FROM $dbase.`".$table_prefix."site_htmlsnippets` WHERE id=$id;";
	$rs = mysql_query($sql);
	if($rs) {
		$row = mysql_fetch_assoc($rs);
		$sql = "INSERT INTO $dbase.`".$table_prefix."site_htmlsnippets`
				(name, description, snippet) VALUES
				('".mysql_escape_string($row['name'])."', '".mysql_escape_string($row['description'])."','".mysql_escape_string($row['snippet'])."');";
		$rs = mysql_query($sql);
	}
}
if($rs) $newid = mysql_insert_id(); // get new id
else {
	echo "A database error occured while trying to duplicate variable: <br /><br />".mysql_error();
	exit;
}

// finish duplicating - redirect to new chunk
$header="Location: index.php?r=2&a=78&id=$newid";
header($header);

?>
