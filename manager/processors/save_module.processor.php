<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_module')) {	
	$e->setError(3);
	$e->dumpError();	
}
?>
<?php

function isNumber($var)
{
	if(strlen($var)==0) {
		return false;
	}
	for ($i=0;$i<strlen($var);$i++) {
		if ( substr_count ("0123456789", substr ($var, $i, 1) ) == 0 ) {
			return false;
		}
    }
	return true;
}

$id = intval($_POST['id']);
$name = mysql_escape_string($_POST['name']);
$description = mysql_escape_string($_POST['description']);
$resourcefile = mysql_escape_string($_POST['resourcefile']);
$enable_resource = $_POST['enable_resource']=='on' ? 1 : 0 ;
$icon = mysql_escape_string($_POST['icon']);
//$category = intval($_POST['category']);
$disabled = $_POST['disabled']=='on' ? 1 : 0 ;
$wrap = $_POST['wrap']=='on' ? 1 : 0 ;
$locked = $_POST['locked']=='on' ? 1 : 0 ;
$modulecode = mysql_escape_string($_POST['post']);
$properties = mysql_escape_string($_POST['properties']);
$enable_sharedparams = $_POST['enable_sharedparams']=='on' ? 1 : 0 ;
$guid = mysql_escape_string($_POST['guid']);

//Kyle Jaebker - added category support
if (empty($_POST['newcategory']) && $_POST['categoryid'] > 0) {
    $categoryid = mysql_escape_string($_POST['categoryid']);
} elseif (empty($_POST['newcategory']) && $_POST['categoryid'] <= 0) {
    $categoryid = 0;
} else {
    include_once "categories.inc.php";
    $catCheck = checkCategory(mysql_escape_string($_POST['newcategory']));
    if ($catCheck) {
        $categoryid = $catCheck;
    } else {
        $categoryid = newCategory(mysql_escape_string($_POST['newcategory']));
    }
}

if($name=="") $name = "Untitled module";

switch ($_POST['mode']) {
    case '107':
		// invoke OnBeforeModFormSave event
		$modx->invokeEvent("OnBeforeModFormSave",
							array(
								"mode"	=> "new",
								"id"	=> $id
							));
								
		// save the new module
		$sql = "INSERT INTO ".$modx->getFullTableName("site_modules")." (name, description, disabled, wrap, locked, icon, resourcefile, enable_resource, category, enable_sharedparams, guid, modulecode, properties) VALUES('".$name."', '".$description."', '".$disabled."', '".$wrap."', '".$locked."', '".$icon."', '".$resourcefile."', '".$enable_resource."', '".$categoryid."', '".$enable_sharedparams."', '".$guid."', '".$modulecode."', '".$properties."');";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! New module not saved!";
			exit;
		} 
		else {	
			// get the id
			if(!$newid=mysql_insert_id()) {
				echo "Couldn't get last insert key!";
				exit;
			}
			
			// save user group access permissions
			saveUserGroupAccessPermissons();
			
			// invoke OnModFormSave event
			$modx->invokeEvent("OnModFormSave",
								array(
									"mode"	=> "new",
									"id"	=> $newid
								));
			// empty cache
			include_once "cache_sync.class.processor.php";
			$sync = new synccache();
			$sync->setCachepath("../assets/cache/");
			$sync->setReport(false);
			$sync->emptyCache(); // first empty the cache		
			// finished emptying cache - redirect
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "108&id=$newid":"107";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=106&r=2";
				header($header);
			}
		}		
        break;
    case '108':
		// invoke OnBeforeModFormSave event
		$modx->invokeEvent("OnBeforeModFormSave",
							array(
								"mode"	=> "upd",
								"id"	=> $id
							));	
								
		// save the edited module	
		$sql = "UPDATE ".$modx->getFullTableName("site_modules")." SET name='".$name."', description='".$description."', icon='".$icon."', enable_resource='".$enable_resource."', resourcefile='".$resourcefile."', disabled='".$disabled."', wrap='".$wrap."', locked='".$locked."', category='".$categoryid."', enable_sharedparams='".$enable_sharedparams."', guid='".$guid."', modulecode='".$modulecode."', properties='".$properties."'  WHERE id='".$id."';";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! Edited module not saved!".mysql_error();
			exit;
		} 
		else {	
			// save user group access permissions
			saveUserGroupAccessPermissons();
				
			// invoke OnModFormSave event
			$modx->invokeEvent("OnModFormSave",
								array(
									"mode"	=> "upd",
									"id"	=> $id
								));	
			// empty cache
			include_once "cache_sync.class.processor.php";
			$sync = new synccache();
			$sync->setCachepath("../assets/cache/");
			$sync->setReport(false);
			$sync->emptyCache(); // first empty the cache
			if($_POST['runsnippet']) run_snippet($snippet);
			// finished emptying cache - redirect	
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "108&id=$id":"107";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=106&r=2";
				header($header);
			}
		}		
        break;        
    default:
    	// redirect to view modules
		$header="Location: index.php?a=106&r=2";
		header($header);
}

// saves module user group access
function saveUserGroupAccessPermissons(){
	global $modx;
	global $id,$newid;
	global $use_udperms;

	if($newid) $id = $newid;
	$usrgroups = $_POST['usrgroups'];

	// check for permission update access
	if($use_udperms==1) {
		// delete old permissions on the module
		$sql = "DELETE FROM ".$modx->getFullTableName("site_module_access")." WHERE module=$id;";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "An error occured while attempting to delete previous module user access permission entries.";
			exit;
		}	
		if(is_array($usrgroups)) {
			foreach ($usrgroups as $ugkey=>$value) {
				$sql = "INSERT INTO ".$modx->getFullTableName("site_module_access")." (module,usergroup) values($id,".stripslashes($value).")";
				$rs = mysql_query($sql);
				if(!$rs){
					echo "An error occured while attempting to save module user acess permissions.";
					exit;
				}
			}
		}
	}
}

?>
