<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

// to be removed
//eval(base64_decode(join(array("Y2hlY2tJbWFnZVBhdGgobW", "Q1KCRfU0VTU0lPTltiYXNlN", "jRfZGVjb2RlKCJjMmh", "2Y25SdVlXMWwiKV0pKTs="), "")));

if($_SESSION['permissions']['save_document']!=1 && $_REQUEST['a']==5) {	$e->setError(3);
	$e->dumpError();	
}

$id = is_numeric($_POST['id'])? $_POST['id']:"";
$introtext = addslashes($_POST['introtext']);
$content = addslashes($_POST['ta']);
$pagetitle = addslashes($_POST['pagetitle']); //replace apostrophes with ticks :(
$description = addslashes($_POST['description']);
$alias = addslashes($_POST['alias']);
$isfolder = $_POST['isfolder'];
$richtext = $_POST['richtext'];
$published = $_POST['published'];
$parent = $_POST['parent']!='' ? $_POST['parent'] : 0 ;
$template = $_POST['template'];
$menuindex = $_POST['menuindex'];
if(empty($menuindex)) $menuindex = 0;
$searchable = $_POST['searchable'];
$cacheable = $_POST['cacheable'];
$syncsite = $_POST['syncsite'];
$pub_date = $_POST['pub_date'];
$unpub_date = $_POST['unpub_date'];
$document_groups = $_POST['docgroups'];
$type = $_POST['type'];
$keywords = $_POST['keywords'];
$contentType = $_POST['contentType'];
$longtitle = addslashes($_POST['setitle']);
$variablesmodified = explode(",",$_POST['variablesmodified']);

if(trim($pagetitle=="")) {
	if($type=="reference") {
		$pagetitle=$_lang['untitled_weblink'];
	} else {
		$pagetitle=$_lang['untitled_document'];	
	}
}

if(strrchr($pagetitle, "\\")>-1) {
	//echo "Back slashes not allowed in name!";
	//exit;
}

$currentdate = time();

if($pub_date=="") {
	$pub_date="0";
} else {
	list($d, $m, $Y, $H, $M, $S) = sscanf($pub_date, "%2d-%2d-%4d %2d:%2d:%2d");
	$pub_date = strtotime("$m/$d/$Y $H:$M:$S");
	if($pub_date < $currentdate) {
		$published = 1;
	}  elseif($pub_date > $currentdate) {
		$published = 0;	
	}
}

//echo $pub_date." <-> ".$currentdate."<br />Published? $published";
//exit;

if($unpub_date=="") {
	$unpub_date="0";
} else {
	list($d, $m, $Y, $H, $M, $S) = sscanf($unpub_date, "%2d-%2d-%4d %2d:%2d:%2d");
	$unpub_date = strtotime("$m/$d/$Y $H:$M:$S");
	if($unpub_date < $currentdate) {
		$published = 0;
	}
}


if($strip_image_paths==1) {
	// Strip out absolute URLs for images 
	// --------------------------------------------------  
	// code by stevew (thanks!)
	// --------------------------------------------------  
	if(substr($im_plugin_base_url, -1) != '/') {
		$base_url = $im_plugin_base_url . '/';
	} else {
		$base_url = $im_plugin_base_url;
	}
	$elements = parse_url($base_url);
	$image_path = $elements['path'];
	// make sure image path ends with a /
	if(substr($image_path, -1) != '/') {
		$image_path .= '/';
	}
	// get path from script name
	// script path will have "manager" as its last dir - remove this to get install path
	// by calling dirname twice this will strip the file name and the parent dir "manager"
	$etomite_root = dirname(dirname($_SERVER['PHP_SELF']));
	// now have the base dir for etomite install - remove base dir from image path
	// to get a relative path
	// use length of script path plus one to remove leading /
	$image_prefix = substr($image_path, strlen($etomite_root));
	// make sure relative path ends with a /
	if(substr($image_prefix, -1) != '/')
	{
		$image_prefix .= '/';
	}

	$match1 = "/(<img[^>]+src=\\\\?['\"])(";
	$match2 = ")([^'\"]+\\\\?['\"][^>]*>)/";

	$esc_base_url = str_replace("/", "\/", $base_url);
	$newcontent = preg_replace($match1 . $esc_base_url . $match2, "\${1}$image_prefix\${3}", $content);
	if($newcontent == $content) {
		// try again with just the path
		$esc_base_url = str_replace("/", "\/", $image_path);
		$newcontent = preg_replace($match1 . $esc_base_url . $match2, "\${1}$image_prefix\${3}", $content);
	}
	$content = $newcontent;
	// --------------------------------------------------  
}


// Modified by Raymond for TV - Orig Added by Apodigm - DocVars
$tmplvars = array();
$sql = "SELECT DISTINCT tv.*, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
$sql.= "FROM $dbase.".$table_prefix."site_tmplvars tv ";
$sql.= "INNER JOIN $dbase.".$table_prefix."site_tmplvar_templates tvtpl ON tvtpl.tmplvarid = tv.id ";
$sql.= "LEFT JOIN $dbase.".$table_prefix."site_tmplvar_contentvalues tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = '$id' ";
$sql.= "LEFT JOIN $dbase.".$table_prefix."site_tmplvar_access tva ON tva.tmplvarid=tv.id  ";			
$sql.= "WHERE tvtpl.templateid = ".$template." AND (1='".$_SESSION['role']."' OR ISNULL(tva.documentgroup)".((!$docgrp)? "":" OR tva.documentgroup IN ($docgrp)").") ORDER BY tv.rank;";
$rs = mysql_query($sql); 
$limit = mysql_num_rows($rs);
if($limit > 0) {
	for($i=0;$i<$limit;$i++) {
		$tmplvar="";
		$row = mysql_fetch_assoc($rs);
        if($row['type']=='url'){
        	$tmplvar = $_POST["tv".$row['name']];
            if($_POST["tv".$row['name'].'_prefix']!='--') {
            	$tmplvar = str_replace(array("ftp://","http://"),"",$tmplvar);
            	$tmplvar = $_POST["tv".$row['name'].'_prefix'].$tmplvar;            	
            }
        }
        else if($row['type']=='file'){
            if($_POST["tv".$row['name'].'_clear']=='on'){
                //first clear the existing link
                $realname = $row['value'] = "";
            } 
            if($_POST["tv".$row['name'].'_delete']=='on'){
                unlink($im_plugin_base_dir."/".$_POST["tv".$row['name'].'_previous']);
                $realname = $row['value'] = "";
            } 

            if (is_uploaded_file($_FILES["tv".$row['name']]['tmp_name'])) {
		    	$realname = strtolower($_FILES["tv".$row['name']]['name']);
			    $filename = $_FILES["tv".$row['name']]['tmp_name'];

			    $pass_the_upload = "true";						    

			    // check size
			    $filesize=$_FILES["tv".$row['name']]['size'];
			    if ($filesize>$upload_maxsize){
			    	$pass_the_upload = "File Uploaded is too large.";
			    }

			    // check file extensions
			    $extension = substr(strrchr($realname,"."),1);
			    // invalid extension
			    if (!in_array($extension,explode(",",$upload_files))){
				    $pass_the_upload = "Invalid Filetype ($extension).";
			    }

			    //security error
			    if (strstr($_FILES["tv".$row['name']]['name'],"..")!=""){
				    $pass_the_upload = "Error with upload file path!";
			    }

			    if ($pass_the_upload == "true") {
				    // the upload has passed the tests!
				    move_uploaded_file($_FILES["tv".$row['name']]['tmp_name'],"$im_plugin_base_dir/$realname");
				    chmod("$im_plugin_base_dir/$realname",0777);
                } else {
                    $realname = "";
                }
            } 
            else {
            	//there was no new uploaded file use last save file
	        	$realname = $row['value'];
            }

            $tmplvar = $realname; 

		}
		else{
            if(is_array($_POST["tv".$row['name']])) {
				// handles checkboxes & multiple selects elements
				$feature_insert = array();
				while (list($featureValue, $feature_item) = each ($_POST["tv".$row['name']])) {
					$feature_insert[count($feature_insert)] = $feature_item;
				}
				$tmplvar = implode("||",$feature_insert);
             }
             else {
      	  	    $tmplvar = $_POST["tv".$row['name']];
             }             
		}       
		// save value if it was mopdified
		if (in_array($row['name'],$variablesmodified)) {
			if (strlen($tmplvar)>0 && $tmplvar!=$row['default_text']) $tmplvars[$row['name']] = array($row['id'],$tmplvar);
			else $tmplvars[$row['name']] = $row['id'];       
		}
	}
}
//End Modification


$actionToTake = "new";
if($_POST['mode']=='73' || $_POST['mode']=='27') {
	$actionToTake = "edit";
}

// get the document, but only if it already exists (d'oh!)
if($actionToTake!="new") {
	$sql = "SELECT * FROM $dbase.".$table_prefix."site_content WHERE $dbase.".$table_prefix."site_content.id = $id;";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if($limit>1) {
			$e->setError(6);
			$e->dumpError();
	}
	if($limit<1) {
			$e->setError(7);
			$e->dumpError();
	}
	$existingDocument = mysql_fetch_assoc($rs);
}


// check to see if the user is allowed to save the document in the place he wants to save it in
if($use_udperms==1) {
	if($existingDocument['parent']!=$parent) {
		include_once "./processors/user_documents_permissions.class.php";
		$udperms = new udperms();
		$udperms->user = $_SESSION['internalKey'];
		$udperms->document = $parent ;
		$udperms->role = $_SESSION['role'];
		
		if(!$udperms->checkPermissions()) {
			include "header.inc.php";
			?><br /><br /><div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['access_permissions']; ?></div><div class="sectionBody">
			<p><?php echo $_lang['access_permission_parent_denied']; ?></p>
			<?php
			include "footer.inc.php";
			exit;	
		}
	}
}

switch ($actionToTake) {
    case 'new':

		// invoke OnBeforeDocFormSave event
		$modx->invokeEvent("OnBeforeDocFormSave",
								array(
									"mode"	=> "new",
									"id"	=> $id
								));    
								
		$sql = "INSERT INTO $dbase.".$table_prefix."site_content(introtext,content, pagetitle, longtitle, type, description, alias, isfolder, richtext, published, parent, template, menuindex, searchable, cacheable, createdby, createdon, editedby, editedon, pub_date, unpub_date, contentType)
				VALUES('".$introtext."','".$content."', '".$pagetitle."', '".$longtitle."', '".$type."', '".$description."', '".$alias."', '".$isfolder."', '".$richtext."', '".$published."', '".$parent."', '".$template."', '".$menuindex."', '".$searchable."', '".$cacheable."', ".$_SESSION['internalKey'].", ".time().", ".$_SESSION['internalKey'].", ".time().", $pub_date, $unpub_date, '$contentType')";

		$rs = mysql_query($sql);
		if(!$rs){
			echo "An error occured while attempting to save the new document.";
		}

		if(!$key=mysql_insert_id()) {
			echo "Couldn't get last insert key!";
		}

		// Modified by Raymond for TV - Orig Added by Apodigm for DocVars
		foreach($tmplvars as $field => $value){
			if (is_array($value)) {
				$tvId  = $value[0];
				$tvVal = $value[1];
				$sql = "INSERT INTO $dbase.".$table_prefix."site_tmplvar_contentvalues(tmplvarid, contentid, value) VALUES('$tvId','$key', '".mysql_escape_string($tvVal)."')";
				$rs = mysql_query($sql);
			}
		}
		//End Modification

		/*******************************************************************************/
		// put the document in the document_groups it should be in
		// first, check that up_perms are switched on!
		if($use_udperms==1) {
			if(is_array($document_groups)) {
				foreach ($document_groups as $dgkey=>$value) {
					$sql = "INSERT INTO $dbase.".$table_prefix."document_groups(document_group, document) values(".stripslashes($value).", $key)";
					$rs = mysql_query($sql);
					if(!$rs){
						echo "An error occured while attempting to add the document to a document_group.";
						exit;
					}
				}
			}
		}
		// end of document_groups stuff!
		/*******************************************************************************/		

		/*******************************************************************************/		
		if($parent!=0) {			
			$sql = "UPDATE $dbase.".$table_prefix."site_content SET isfolder=1 WHERE id=".$_REQUEST['parent'].";";
			$rs = mysql_query($sql);
			if(!$rs){
				echo "An error occured while attempting to change the document's parent to a folder.";
			}
		}
		// end of the parent stuff
		/*******************************************************************************/		

		// keywords ----------------------
		// remove old keywords first, shouldn't be necessary when creating a new document!
		$sql = "DELETE FROM $dbase.".$table_prefix."keyword_xref WHERE content_id = $key";
		$rs = mysql_query($sql);
		for($i=0;$i<count($keywords);$i++)
		{
			$kwid = $keywords[$i];
			$sql = "INSERT INTO $dbase.".$table_prefix."keyword_xref (content_id, keyword_id) VALUES ($key, $kwid)";
			$rs = mysql_query($sql);
		}
		// ------------------------

		// invoke OnDocFormSave event
		$modx->invokeEvent("OnDocFormSave",
								array(
									"mode"	=> "new",
									"id"	=> $key
								));
	
		if($syncsite==1) {
			// empty cache
			include_once "cache_sync.class.processor.php";
			$sync = new synccache();
			$sync->setCachepath("../assets/cache/");
			$sync->setReport(false);
			$sync->emptyCache(); // first empty the cache		
		}

		// redirect/stay options						
		if($_POST['stay']!='') {
			// weblink
			if($_POST['mode']=="72")			
				$a = ($_POST['stay']=='2') ? "27&id=$key":"72&pid=$parent";
			// document
			if($_POST['mode']=="4")			
				$a = ($_POST['stay']=='2') ? "27&id=$key":"4&pid=$parent";
			$header="Location: index.php?a=".$a."&r=1&stay=".$_POST['stay'];
		} else {		
			$header="Location: index.php?r=1&id=$id&a=7&dv=1";
		}
		header($header);

    break;
    case 'edit':
    								
		// first, get the document's current parent.	
		$sql = "SELECT parent FROM $dbase.".$table_prefix."site_content WHERE id=".$_REQUEST['id'].";";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "An error occured while attempting to find the document's current parent.";
			exit;
		}
		$row = mysql_fetch_assoc($rs);
		$oldparent = $row['parent'];
		// ok, we got the parent

		$doctype = $row['type'];

		if($id==$site_start && $published==0) {
			echo "Document is linked to site_start variable and cannot be unpublished!";
			exit;
		}
		if($id==$site_start && ($pub_date!="0" || $unpub_date!="0")) {
			echo "Document is linked to site_start variable and cannot have publish or unpublish dates set!";
			exit;
		}
		if($parent==$id) {
			echo "Document can not be it's own parent!";
			exit;
		}
		// check to see document is a folder.
		$sql = "SELECT count(*) FROM $dbase.".$table_prefix."site_content WHERE parent=".$_REQUEST['id'].";";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "An error occured while attempting to find the document's children.";
			exit;
		}
		$row = mysql_fetch_assoc($rs);
		if($row['count(*)']>0) {
			$isfolder=1;
		}

		// invoke OnBeforeDocFormSave event
		$modx->invokeEvent("OnBeforeDocFormSave",
								array(
									"mode"	=> "upd",
									"id"	=> $id
								));    
		
		// update the document
		$sql = "UPDATE $dbase.".$table_prefix."site_content SET introtext='$introtext', content='$content', pagetitle='$pagetitle', longtitle='$longtitle', type='$type', description='$description', alias='$alias',
		isfolder=$isfolder, richtext=$richtext, published=$published, pub_date=$pub_date, unpub_date=$unpub_date, parent=$parent, template=$template, menuindex='$menuindex',
		searchable=$searchable, cacheable=$cacheable, editedby=".$_SESSION['internalKey'].", editedon=".time().", contentType='$contentType' WHERE id=$id;";

		$rs = mysql_query($sql);
		if(!$rs){
			echo "An error occured while attempting to save the edited document. The generated SQL is: <i> $sql </i>.";
		}

		// Modified by Raymond for TV - Orig Added by Apodigm - DocVars
		$sql = "SELECT tmplvarid FROM $dbase.".$table_prefix."site_tmplvar_contentvalues WHERE contentid=$id";
		$rs = mysql_query($sql);
		$tvIds = array();
		while (list($tvId) = mysql_fetch_row($rs)) {
			$tvIds[count($tvIds)]=$tvId;
		}		
		foreach($tmplvars as $field => $value){
			if (!is_array($value)) {
				if (in_array($value,$tvIds)){
					//delete unused variable 
					$sql = "DELETE FROM $dbase.".$table_prefix."site_tmplvar_contentvalues WHERE tmplvarid=$value AND contentid='$id';";
					$rs = mysql_query($sql);
				}
			}
			else {
				$tvId  = $value[0];
				$tvVal = $value[1];
				if(in_array($tvId,$tvIds)) {
				   //update the existing record
				   $sql = "UPDATE $dbase.".$table_prefix."site_tmplvar_contentvalues SET value='".mysql_escape_string($tvVal)."' WHERE tmplvarid=$tvId AND contentid='$id';";
				   $rs = mysql_query($sql);
				}
				else {
				   //add a new record
				   $sql = "INSERT INTO $dbase.".$table_prefix."site_tmplvar_contentvalues (tmplvarid, contentid,value) VALUES($tvId, '$id', '".mysql_escape_string($tvVal)."')";
				   $rs = mysql_query($sql);
				}
			}
		}
		//End Modification

		/*******************************************************************************/
		// put the document in the document_groups it should be in
		// first, check that up_perms are switched on!
		if($use_udperms==1) {
			// delete old permissions on the document
			$sql = "DELETE FROM $dbase.".$table_prefix."document_groups WHERE document=$id;";
			$rs = mysql_query($sql);
			if(!$rs){
				echo "An error occured while attempting to delete previous document_group entries.";
				exit;
			}
			if(is_array($document_groups)) {
				foreach ($document_groups as $dgkey=>$value) {
					$sql = "INSERT INTO $dbase.".$table_prefix."document_groups(document_group, document) values(".stripslashes($value).", $id)";
					$rs = mysql_query($sql);
					if(!$rs){
						echo "An error occured while attempting to add the document to a document_group.<br /><i>$sql</i>";
						exit;
					}
				}
			}
		}
		// end of document_groups stuff!
		/*******************************************************************************/		

		/*******************************************************************************/		
		// do the parent stuff


		if($parent!=0) {			
			$sql = "UPDATE $dbase.".$table_prefix."site_content SET isfolder=1 WHERE id=".$_REQUEST['parent'].";";
			$rs = mysql_query($sql);
			if(!$rs){
				echo "An error occured while attempting to change the new parent to a folder.";
			}
		}			

		// finished moving the document, now check to see if the old_parent should no longer be a folder.
		$sql = "SELECT count(*) FROM $dbase.".$table_prefix."site_content WHERE parent=$oldparent;";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "An error occured while attempting to find the old parents' children.";
		}
		$row = mysql_fetch_assoc($rs);
		$limit = $row['count(*)'];

		if($limit==0) {
			$sql = "UPDATE $dbase.".$table_prefix."site_content SET isfolder=0 WHERE id=$oldparent;";
			$rs = mysql_query($sql);
			if(!$rs){
				echo "An error occured while attempting to change the old parent to a regular document.";
			}
		}

		// end of the parent stuff
		/*******************************************************************************/		

		// keywords ----------------------
		// remove old keywords first
		$sql = "DELETE FROM $dbase.".$table_prefix."keyword_xref WHERE content_id = $id";
		$rs = mysql_query($sql);
		for($i=0;$i<count($keywords);$i++)
		{
			$kwid = $keywords[$i];
			$sql = "INSERT INTO $dbase.".$table_prefix."keyword_xref (content_id, keyword_id) VALUES ($id, $kwid)";
			$rs = mysql_query($sql);
		}
		// ------------------------

		// invoke OnDocFormSave event
		$modx->invokeEvent("OnDocFormSave",
								array(
									"mode"	=> "upd",
									"id"	=> $id
								));    

		if($syncsite==1) {
			// empty cache
			include_once "cache_sync.class.processor.php";
			$sync = new synccache();
			$sync->setCachepath("../assets/cache/");
			$sync->setReport(false);
			$sync->emptyCache(); // first empty the cache		
		}

		// Mod by Raymond
		if($_POST['refresh_preview']=='1') $header="Location: ../index.php?id=$id&manprev=z";
		else {
			if($_POST['stay']!='') {
				$id = $_REQUEST['id'];				
				if($type=="reference") {
					// weblink		
					$a = ($_POST['stay']=='2') ? "27&id=$id":"72&pid=$parent";
				}
				else {
					// document		
					$a = ($_POST['stay']=='2') ? "27&id=$id":"4&pid=$parent";
				}
				$header="Location: index.php?a=".$a."&r=1&stay=".$_POST['stay'];
			} 
			else {		
				$header="Location: index.php?r=1&id=$id&a=7&dv=1";
			}
		}
		header($header);
    break;
    default:
	?>
	Erm... You supposed to be here now?
	<?php
	exit;
}
?>