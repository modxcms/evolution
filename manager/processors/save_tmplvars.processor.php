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
    $categoryid = intval($_POST['categoryid']);
} elseif (empty($_POST['newcategory']) && $_POST['categoryid'] <= 0) {
    $categoryid = 0;
} else {
    include_once(MODX_MANAGER_PATH.'includes/categories.inc.php');
    $categoryid = checkCategory($_POST['newcategory']);
    if (!$categoryid) {
        $categoryid = newCategory($_POST['newcategory']);
    }
}

if($caption =="") {
	$caption  = $name? $name: "Untitled variable";
}

// get table names
$tbl_site_tmplvars = $modx->getFullTableName('site_tmplvars');

switch ($_POST['mode']) {
    case '300':

		// invoke OnBeforeTVFormSave event
		$modx->invokeEvent("OnBeforeTVFormSave",
								array(
									"mode"	=> "new",
									"id"	=> $id
							));	      	

		// disallow duplicate names for new tvs
		$rs = $modx->db->select('COUNT(*)', $tbl_site_tmplvars, "name='{$name}'");
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
		$newid = $modx->db->insert(
			array(
				'name'           => $name,
				'description'    => $description,
				'caption'        => $caption,
				'type'           => $type,
				'elements'       => $elements,
				'default_text'   => $default_text,
				'display'        => $display,
				'display_params' => $params,
				'rank'           => $rank,
				'locked'         => $locked,
				'category'       => $categoryid,
			), $tbl_site_tmplvars);
			
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

		// disallow duplicate names for tvs
		$rs = $modx->db->select('COUNT(*)', $tbl_site_tmplvars, "name='{$name}' AND id!='{$id}'");
		if($modx->db->getValue($rs) > 0) {
			$modx->manager->saveFormValues(300);
			$modx->webAlertAndQuit(sprintf($_lang['duplicate_name_found_general'], $_lang['tv'], $name), "index.php?a=301&id={$id}");
		}
		// disallow reserved names
		if(in_array($name, array('id', 'type', 'contentType', 'pagetitle', 'longtitle', 'description', 'alias', 'link_attributes', 'published', 'pub_date', 'unpub_date', 'parent', 'isfolder', 'introtext', 'content', 'richtext', 'template', 'menuindex', 'searchable', 'cacheable', 'createdby', 'createdon', 'editedby', 'editedon', 'deleted', 'deletedon', 'deletedby', 'publishedon', 'publishedby', 'menutitle', 'donthit', 'haskeywords', 'hasmetatags', 'privateweb', 'privatemgr', 'content_dispo', 'hidemenu','alias_visible'))) {
			$modx->manager->saveFormValues(300);
			$modx->webAlertAndQuit(sprintf($_lang['reserved_name_warning'], $_lang['tv'], $name), "index.php?a=301&id={$id}");
		}

    	// update TV
		$modx->db->update(
			array(
				'name'           => $name,
				'description'    => $description,
				'caption'        => $caption,
				'type'           => $type,
				'elements'       => $elements,
				'default_text'   => $default_text,
				'display'        => $display, 
				'display_params' => $params,         
				'rank'           => $rank,
				'locked'         => $locked,
				'category'       => $categoryid,
			), $tbl_site_tmplvars, "id='{$id}'");

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
		$modx->webAlertAndQuit("No operation set in request.");
}

function saveTemplateAccess() {	
	global $id,$newid;
	global $modx;

	if($newid) $id = $newid;
	$templates =  $_POST['template']; // get muli-templates based on S.BRENNAN mod
		
	// update template selections
	$tbl_site_tmplvar_templates = $modx->getFullTableName('site_tmplvar_templates');
	
    $getRankArray = array();

    $getRank = $modx->db->select("templateid, rank", $tbl_site_tmplvar_templates, "tmplvarid='{$id}'");

    while( $row = $modx->db->getRow( $getRank ) ) {
    $getRankArray[$row['templateid']] = $row['rank'];
    }
   
	
	$modx->db->delete($tbl_site_tmplvar_templates, "tmplvarid = '{$id}'");
	for($i=0;$i<count($templates);$i++){
	    $setRank = ($getRankArray[$templates[$i]]) ? $getRankArray[$templates[$i]] : 0;
	    $modx->db->insert(
	        array(
	            'tmplvarid'  => $id,
	            'templateid' => $templates[$i],
	            'rank'       => $setRank,
	        ), $tbl_site_tmplvar_templates);
	}	
}

function saveDocumentAccessPermissons(){
	global $id,$newid;
	global $modx,$use_udperms;

	$tbl_site_tmplvar_templates = $modx->getFullTableName('site_tmplvar_access');

	if($newid) $id = $newid;
	$docgroups = $_POST['docgroups'];

	// check for permission update access
	if($use_udperms==1) {
		// delete old permissions on the tv
		$modx->db->delete($tbl_site_tmplvar_templates, "tmplvarid='{$id}'");
		if(is_array($docgroups)) {
			foreach ($docgroups as $value) {
				$modx->db->insert(
					array(
						'tmplvarid'  => $id,
						'documentgroup' => stripslashes($value),
					), $tbl_site_tmplvar_templates);
			}
		}
	}
}
?>