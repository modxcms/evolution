<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_template')) {
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
$caption = mysql_escape_string($_POST['caption']);
$type = mysql_escape_string($_POST['type']);
$elements = mysql_escape_string($_POST['elements']);
$default_text = mysql_escape_string($_POST['default_text']);
$rank = isset ($_POST['rank']) ? mysql_escape_string($_POST['rank']) : 0;
$display = mysql_escape_string($_POST['display']);
$params = mysql_escape_string($_POST['params']);
$locked = $_POST['locked']=='on' ? 1 : 0 ;

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

if($caption =="") {
	$caption  = $name? $name: "Untitled variable";
}

switch ($_POST['mode']) {
    case '300':

		// invoke OnBeforeTVFormSave event
		$modx->invokeEvent("OnBeforeTVFormSave",
								array(
									"mode"	=> "new",
									"id"	=> $id
							));	      	
		// Add new TV
		$sql = "INSERT INTO $dbase.`".$table_prefix."site_tmplvars` (name, description, caption, type, elements, default_text, display,display_params, rank, locked, category) VALUES('".$name."', '".$description."', '".$caption."', '".$type."', '".$elements."', '".$default_text."', '".$display."', '".$params."', '".$rank."', '".$locked."', ".$categoryid.");";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! New variable not saved!";
		} else {	
			// get the id
			if(!$newid=mysql_insert_id()) {
				echo "Couldn't get last insert key!";
				exit;
			}			
			
			// save access permissions
			saveTemplateAccess();			
			saveDocumentAccessPermissons();

			// invoke OnTVFormSave event
			$modx->invokeEvent("OnTVFormSave",
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
				$a = ($_POST['stay']=='2') ? "301&id=$newid":"300";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=76&r=2";
				header($header);
			}
		}		
        break;
    case '301':	 	
		// invoke OnBeforeTVFormSave event
		$modx->invokeEvent("OnBeforeTVFormSave",
								array(
									"mode"	=> "upd",
									"id"	=> $id
							));	 

    	// update TV
		$sql = "UPDATE $dbase.`".$table_prefix."site_tmplvars` SET ";
        $sql .= "name='".$name."', ";
		$sql .= "description='".$description."', ";
        $sql .= "caption='".$caption."', ";
        $sql .= "type='".$type."', ";
        $sql .= "elements='".$elements."', ";
        $sql .= "default_text='".$default_text."', ";
        $sql .= "display='".$display."', "; 
        $sql .= "display_params='".$params."', ";         
        $sql .= "rank='".$rank."', ";
        $sql .= "locked='".$locked."', ";
        $sql .= "category=".$categoryid;
        $sql .= " WHERE id='".$id."';";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! Edited variable not saved!";
		} else {		

			// save access permissions
			saveTemplateAccess();			
			saveDocumentAccessPermissons();

			// invoke OnTVFormSave event
			$modx->invokeEvent("OnTVFormSave",
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
			// finished emptying cache - redirect	
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "301&id=$id":"300";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=76&r=2";
				header($header);
			}
		}				
		
        break;
    default:
	?>
	Erm... You supposed to be here now?
	<?php
}

function saveTemplateAccess() {	
	global $dbase,$table_prefix;
	global $id,$newid;

	if($newid) $id = $newid;
	$templates =  $_POST['template']; // get muli-templates based on S.BRENNAN mod
		
	// update template selections
	$tbl = "$dbase.`".$table_prefix."site_tmplvar_templates`";
	mysql_query("DELETE FROM $tbl WHERE tmplvarid = $id");
	for($i=0;$i<count($templates);$i++){
		mysql_query("INSERT INTO $tbl (tmplvarid,templateid) VALUES($id,".$templates[$i].");");
	}	
}

function saveDocumentAccessPermissons(){
	global $id,$newid;
	global $dbase,$table_prefix,$use_udperms;

	if($newid) $id = $newid;
	$docgroups = $_POST['docgroups'];

	// check for permission update access
	if($use_udperms==1) {
		// delete old permissions on the tv
		$sql = "DELETE FROM $dbase.`".$table_prefix."site_tmplvar_access` WHERE tmplvarid=$id;";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "An error occurred while attempting to delete previous template variable access permission entries.";
			exit;
		}	
		if(is_array($docgroups)) {
			foreach ($docgroups as $dgkey=>$value) {
				$sql = "INSERT INTO $dbase.`".$table_prefix."site_tmplvar_access` (tmplvarid,documentgroup) values($id,".stripslashes($value).")";
				$rs = mysql_query($sql);
				if(!$rs){
					echo "An error occured while attempting to save template variable acess permissions.";
					exit;
				}
			}
		}
	}
}

?>
