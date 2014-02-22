<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_template')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = intval($_POST['id']);
$name = $modx->db->escape(trim($_POST['name']));				
$description = $modx->db->escape($_POST['description']);
$caption = $modx->db->escape($_POST['caption']);
$type = $modx->db->escape($_POST['type']);
$elements = $modx->db->escape($_POST['elements']);
$default_text = $modx->db->escape($_POST['default_text']);
$rank = isset ($_POST['rank']) ? $modx->db->escape($_POST['rank']) : 0;
$display = $modx->db->escape($_POST['display']);
$params = $modx->db->escape($_POST['params']);
$locked = $_POST['locked']=='on' ? 1 : 0 ;

//Kyle Jaebker - added category support
if (empty($_POST['newcategory']) && $_POST['categoryid'] > 0) {
    $categoryid = $modx->db->escape($_POST['categoryid']);
} elseif (empty($_POST['newcategory']) && $_POST['categoryid'] <= 0) {
    $categoryid = 0;
} else {
    include_once "categories.inc.php";
    $catCheck = checkCategory($modx->db->escape($_POST['newcategory']));
    if ($catCheck) {
        $categoryid = $catCheck;
    } else {
        $categoryid = newCategory($_POST['newcategory']);
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

		// disallow duplicate names for new tvs
		$sql = "SELECT COUNT(id) FROM {$dbase}.`{$table_prefix}site_tmplvars` WHERE name = '{$name}'";
		$rs = $modx->db->query($sql);
		$count = $modx->db->getValue($rs);
		if($count > 0) {
			$modx->manager->saveFormValues(300);
			$modx->webAlertAndQuit(sprintf($_lang['duplicate_name_found_general'], $_lang['tv'], $name), "index.php?a=300");
		}
        // disallow reserved names
        if(in_array($name, array('id', 'type', 'contentType', 'pagetitle', 'longtitle', 'description', 'alias', 'link_attributes', 'published', 'pub_date', 'unpub_date', 'parent', 'isfolder', 'introtext', 'content', 'richtext', 'template', 'menuindex', 'searchable', 'cacheable', 'createdby', 'createdon', 'editedby', 'editedon', 'deleted', 'deletedon', 'deletedby', 'publishedon', 'publishedby', 'menutitle', 'donthit', 'haskeywords', 'hasmetatags', 'privateweb', 'privatemgr', 'content_dispo', 'hidemenu','alias_visible'))) {
		$_POST['name'] = '';
		$modx->manager->saveFormValues(300);
		$modx->webAlertAndQuit(sprintf($_lang['reserved_name_warning'], $_lang['tv'], $name), "index.php?a=300");
        }

		// Add new TV
		$sql = "INSERT INTO $dbase.`".$table_prefix."site_tmplvars` (name, description, caption, type, elements, default_text, display,display_params, rank, locked, category) VALUES('".$name."', '".$description."', '".$caption."', '".$type."', '".$elements."', '".$default_text."', '".$display."', '".$params."', '".$rank."', '".$locked."', ".$categoryid.");";
		$modx->db->query($sql);
			// get the id
			if(!$newid=$modx->db->getInsertId()) {
				$modx->webAlertAndQuit("Couldn't get last insert key!");
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
								
		// Set the item name for logger
		$_SESSION['itemname'] = $caption;

			// empty cache
			$modx->clearCache('full');
			
			// finished emptying cache - redirect
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "301&id=$newid":"300";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=76&r=2";
				header($header);
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
		$modx->db->query($sql);

			// save access permissions
			saveTemplateAccess();			
			saveDocumentAccessPermissons();

			// invoke OnTVFormSave event
			$modx->invokeEvent("OnTVFormSave",
									array(
										"mode"	=> "upd",
										"id"	=> $id
								));	 

		// Set the item name for logger
		$_SESSION['itemname'] = $caption;

			// empty cache
			$modx->clearCache('full');

			// finished emptying cache - redirect	
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "301&id=$id":"300";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=76&r=2";
				header($header);
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
	global $modx;

	if($newid) $id = $newid;
	$templates =  $_POST['template']; // get muli-templates based on S.BRENNAN mod
		
	// update template selections
	$tbl = "$dbase.`".$table_prefix."site_tmplvar_templates`";
	
    $getRankArray = array();

    $getRank = $modx->db->select("templateid, rank", $tbl, "tmplvarid=$id");

    while( $row = $modx->db->getRow( $getRank ) ) {
    $getRankArray[$row['templateid']] = $row['rank'];
    }
   
	
	$modx->db->query("DELETE FROM $tbl WHERE tmplvarid = $id");
	for($i=0;$i<count($templates);$i++){
	    $setRank = ($getRankArray[$templates[$i]]) ? $getRankArray[$templates[$i]] : 0;
		$modx->db->query("INSERT INTO $tbl (tmplvarid,templateid,rank) VALUES($id,".$templates[$i].",$setRank);");
	}	
}

function saveDocumentAccessPermissons(){
	global $id,$newid;
	global $modx,$dbase,$table_prefix,$use_udperms;

	if($newid) $id = $newid;
	$docgroups = $_POST['docgroups'];

	// check for permission update access
	if($use_udperms==1) {
		// delete old permissions on the tv
		$sql = "DELETE FROM $dbase.`".$table_prefix."site_tmplvar_access` WHERE tmplvarid=$id;";
		$modx->db->query($sql);
		if(is_array($docgroups)) {
			foreach ($docgroups as $dgkey=>$value) {
				$sql = "INSERT INTO $dbase.`".$table_prefix."site_tmplvar_access` (tmplvarid,documentgroup) values($id,".stripslashes($value).")";
				$modx->db->query($sql);
			}
		}
	}
}
?>