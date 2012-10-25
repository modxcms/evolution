<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

if(!$modx->hasPermission('new_module')) {	
	$e->setError(3);
	$e->dumpError();	
}
?>
<?php

$id=$_GET['id'];

// create globally unique identifiers (guid)
function createGUID(){
	srand((double)microtime()*1000000);
	$r = rand() ;
	$u = uniqid(getmypid() . $r . (double)microtime()*1000000,1);
	$m = md5 ($u);
	return $m;
}

// duplicate module
if (version_compare(mysql_get_server_info(),"4.0.14")>=0) {	
	$sql = "INSERT INTO ".$modx->getFullTableName("site_modules")." (name, description, disabled, category, wrap, icon, enable_resource, resourcefile, createdon, editedon, guid, enable_sharedparams, properties, modulecode) 
			SELECT CONCAT('Duplicate of ',name) AS 'name', description, disabled, category, wrap, icon, enable_resource, resourcefile, createdon, editedon, '".createGUID()."' as 'guid', enable_sharedparams, properties, modulecode 
			FROM ".$modx->getFullTableName("site_modules")." WHERE id=$id;";
	$rs = mysql_query($sql);
}
else {
	$sql = "SELECT CONCAT('Duplicate of ',name) AS 'name', description, disabled, category, wrap, icon, enable_resource, resourcefile, createdon, editedon, guid, enable_sharedparams, properties, modulecode 
			FROM ".$modx->getFullTableName("site_modules")." WHERE id=$id;";
	$rs = mysql_query($sql);
	if($rs) {
		$row = mysql_fetch_assoc($rs);
		$sql ="INSERT INTO ".$modx->getFullTableName("site_modules")." 
				(name, description, disabled, category, wrap, icon, enable_resource, resourcefile, createdon, editedon, guid, enable_sharedparams, properties, modulecode) VALUES 
				('".$modx->db->escape($row['name'])."', '".$modx->db->escape($row['description'])."', ".$row['disabled'].", '".$row['category']."', '".$row['wrap']."', '".$modx->db->escape($row['icon'])."', '".$modx->db->escape($row['enable_resource'])."', '".$modx->db->escape($row['resourcefile'])."', '".$row['createdon']."', '".$row['editedon']."', '".createGuid()."', '".$modx->db->escape($row['enable_sharedparams'])."', '".$modx->db->escape($row['properties'])."', '".$modx->db->escape($row['modulecode'])."');";
		$rs = mysql_query($sql);
	}	
}
if($rs) $newid = mysql_insert_id(); // get new id
else {
	echo "A database error occured while trying to duplicate module: <br /><br />".mysql_error();
	exit;
}


// duplicate module dependencies
if (version_compare(mysql_get_server_info(),"4.0.14")>=0) {	
	$sql = "INSERT INTO ".$modx->getFullTableName("site_module_depobj")." (module, resource, type)
			SELECT  '$newid', resource, type  
			FROM ".$modx->getFullTableName("site_module_depobj")." WHERE module=$id;";
	$rs = mysql_query($sql);
}
else {
	$sql = "SELECT $newid as 'newid', resource, type 
			FROM ".$modx->getFullTableName("site_module_depobj")." WHERE module=$id;";
	$ds = mysql_query($sql);
	if($ds) while($row = mysql_fetch_assoc($ds)) {
		$sql ="INSERT INTO ".$modx->getFullTableName("site_module_depobj")." 
				(module,resource,type) VALUES 
				('".$modx->db->escape($row['newid'])."', '".$modx->db->escape($row['resource'])."', '".$modx->db->escape($row['type'])."');";
		$rs = mysql_query($sql);
	}	
}
if(!$rs){
	echo "A database error occured while trying to duplicate module dependencies: <br /><br />".mysql_error();
	exit;
}

// duplicate module user group access
if (version_compare(mysql_get_server_info(),"4.0.14")>=0) {	
	$sql = "INSERT INTO ".$modx->getFullTableName("site_module_access")." (module, usergroup)
			SELECT  '$newid', usergroup  
			FROM ".$modx->getFullTableName("site_module_access")." WHERE module=$id;";
	$rs = mysql_query($sql);
}
else {
	$sql = "SELECT $newid as 'newid', usergroup 
			FROM ".$modx->getFullTableName("site_module_access")." WHERE module=$id;";
	$ds = mysql_query($sql);
	if($ds) while($row = mysql_fetch_assoc($ds)) {
		$sql ="INSERT INTO ".$modx->getFullTableName("site_module_access")." 
				(module,usergroup) VALUES 
				('".$modx->db->escape($row['newid'])."', '".$modx->db->escape($row['usergroup'])."');";
		$rs = mysql_query($sql);
	}	
}
if(!$rs){
	echo "A database error occured while trying to duplicate module user group access: <br /><br />".mysql_error();
	exit;
}

// finish duplicating - redirect to new module
$header="Location: index.php?r=2&a=108&id=$newid";
header($header);
?>
