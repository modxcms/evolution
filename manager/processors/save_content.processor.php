<?php
if (IN_MANAGER_MODE != "true")
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if (!$modx->hasPermission('save_document')) {
	$e->setError(3);
	$e->dumpError();
}

$id = is_numeric($_POST['id']) ? $_POST['id'] : "";
$introtext = mysql_escape_string($_POST['introtext']);
$content = mysql_escape_string($_POST['ta']);
$pagetitle = mysql_escape_string($_POST['pagetitle']); //replace apostrophes with ticks :(
$description = mysql_escape_string($_POST['description']);
$alias = mysql_escape_string($_POST['alias']);
$link_attributes = mysql_escape_string($_POST['link_attributes']);
$isfolder = $_POST['isfolder'];
$richtext = $_POST['richtext'];
$published = $_POST['published'];
$parent = $_POST['parent'] != '' ? $_POST['parent'] : 0;
$template = $_POST['template'];
$menuindex = $_POST['menuindex'];
if (empty ($menuindex))
	$menuindex = 0;
$searchable = $_POST['searchable'];
$cacheable = $_POST['cacheable'];
$syncsite = $_POST['syncsite'];
$pub_date = $_POST['pub_date'];
$unpub_date = $_POST['unpub_date'];
$document_groups = $_POST['docgroups'];
$type = $_POST['type'];
$keywords = $_POST['keywords'];
$metatags = $_POST['metatags'];
$contentType = mysql_escape_string($_POST['contentType']);
$contentdispo = intval($_POST['content_dispo']);
$longtitle = mysql_escape_string($_POST['longtitle']);
$variablesmodified = explode(",", $_POST['variablesmodified']);
$donthit = intval($_POST['donthit']);
$menutitle = mysql_escape_string($_POST['menutitle']);
$hidemenu = intval($_POST['hidemenu']);

if (trim($pagetitle == "")) {
	if ($type == "reference") {
		$pagetitle = $_lang['untitled_weblink'];
	} else {
		$pagetitle = $_lang['untitled_document'];
	}
}

$actionToTake = "new";
if ($_POST['mode'] == '73' || $_POST['mode'] == '27') {
	$actionToTake = "edit";
}

// friendly url alias checks
if ($friendly_urls) {
	// auto assign alias
	if (!$alias && $automatic_alias) {
		$alias = stripAlias(strtolower(trim($pagetitle)));
		// check if alias already exists. if yes then append $cnt to alias
		$cnt = $modx->db->getValue("SELECT count(*) FROM " . $modx->getFullTableName("site_content") . " WHERE id<>'$id' AND alias='$alias'");
		if ($cnt > 0)
			$alias .= $cnt;
	}

	// check for duplicate alias name if not allowed
	elseif ($alias && !$allow_duplicate_alias) {
		$alias = stripAlias($alias);
		if ($use_alias_path) {
			// Only check for duplicates on the same level if alias_path is on (netnoise 2006/08/14)
			$docid = $modx->db->getValue("SELECT id FROM " . $modx->getFullTableName("site_content") . " WHERE id<>'$id' AND alias='$alias' AND parent=$parent LIMIT 1");
		} else {
			$docid = $modx->db->getValue("SELECT id FROM " . $modx->getFullTableName("site_content") . " WHERE id<>'$id' AND alias='$alias' LIMIT 1");
		}
		if ($docid > 0) {
			if ($actionToTake == 'edit') {
				$modx->manager->saveFormValues(27);
				$url = "index.php?a=27&id=" . $id;
				include_once "header.inc.php";
				$modx->webAlert(sprintf($_lang["duplicate_alias_found"], $docid, $alias), $url);
				include_once "footer.inc.php";
				exit;
			} else {
				$modx->manager->saveFormValues(4);
				$url = "index.php?a=4";
				include_once "header.inc.php";
				$modx->webAlert(sprintf($_lang["duplicate_alias_found"], $docid, $alias), $url);
				include_once "footer.inc.php";
				exit;
			}
		}
	}
	// strip alias of special characters
	elseif ($alias) {
		$alias = stripAlias($alias);
	}
}
elseif ($alias) {
	$alias = stripAlias($alias);
}

$currentdate = time();

if (empty ($pub_date)) {
	$pub_date = 0;
} else {
	list ($d, $m, $Y, $H, $M, $S) = sscanf($pub_date, "%2d-%2d-%4d %2d:%2d:%2d");
	$pub_date = mktime($H, $M, $S, $m, $d, $Y);

	if ($pub_date < $currentdate) {
		$published = 1;
	}
	elseif ($pub_date > $currentdate) {
		$published = 0;
	}
}

if (empty ($unpub_date)) {
	$unpub_date = 0;
} else {
	list ($d, $m, $Y, $H, $M, $S) = sscanf($unpub_date, "%2d-%2d-%4d %2d:%2d:%2d");
	$unpub_date = mktime($H, $M, $S, $m, $d, $Y);
	if ($unpub_date < $currentdate) {
		$published = 0;
	}
}

// Modified by Raymond for TV - Orig Added by Apodigm - DocVars
// get document groups for current user
$tmplvars = array ();
if ($_SESSION['mgrDocgroups']) {
	$docgrp = implode(",", $_SESSION['mgrDocgroups']);
}
$sql = "SELECT DISTINCT tv.*, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
$sql .= "FROM $dbase.`" . $table_prefix . "site_tmplvars` tv ";
$sql .= "INNER JOIN $dbase.`" . $table_prefix . "site_tmplvar_templates` tvtpl ON tvtpl.tmplvarid = tv.id ";
$sql .= "LEFT JOIN $dbase.`" . $table_prefix . "site_tmplvar_contentvalues` tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = '$id' ";
$sql .= "LEFT JOIN $dbase.`" . $table_prefix . "site_tmplvar_access` tva ON tva.tmplvarid=tv.id  ";
$sql .= "WHERE tvtpl.templateid = '" . $template . "' AND (1='" . $_SESSION['mgrRole'] . "' OR ISNULL(tva.documentgroup)" . ((!$docgrp) ? "" : " OR tva.documentgroup IN ($docgrp)") . ") ORDER BY tv.rank;";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if ($limit > 0) {
	for ($i = 0; $i < $limit; $i++) {
		$tmplvar = "";
		$row = mysql_fetch_assoc($rs);
		if ($row['type'] == 'url') {
			$tmplvar = $_POST["tv" . $row['name']];
			if ($_POST["tv" . $row['name'] . '_prefix'] != '--') {
				$tmplvar = str_replace(array (
					"ftp://",
					"http://"
				), "", $tmplvar);
				$tmplvar = $_POST["tv" . $row['name'] . '_prefix'] . $tmplvar;
			}
		} else
			if ($row['type'] == 'file') {
				/* Modified by Timon for use with resource browser */
				$tmplvar = $_POST["tv" . $row['name']];
			} else {
				if (is_array($_POST["tv" . $row['name']])) {
					// handles checkboxes & multiple selects elements
					$feature_insert = array ();
					$lst = $_POST["tv" . $row['name']];
					while (list ($featureValue, $feature_item) = each($lst)) {
						$feature_insert[count($feature_insert)] = $feature_item;
					}
					$tmplvar = implode("||", $feature_insert);
				} else {
					$tmplvar = $_POST["tv" . $row['name']];
				}
			}
		// save value if it was mopdified
		if (in_array($row['name'], $variablesmodified)) {
			if (strlen($tmplvar) > 0 && $tmplvar != $row['default_text'])
				$tmplvars[$row['name']] = array (
					$row['id'],
					$tmplvar
				);
			else
				$tmplvars[$row['name']] = $row['id'];
		}
	}
}
//End Modification

// get the document, but only if it already exists (d'oh!)
if ($actionToTake != "new") {
	$sql = "SELECT * FROM $dbase.`" . $table_prefix . "site_content` WHERE $dbase.`" . $table_prefix . "site_content`.id = $id;";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if ($limit > 1) {
		$e->setError(6);
		$e->dumpError();
	}
	if ($limit < 1) {
		$e->setError(7);
		$e->dumpError();
	}
	$existingDocument = mysql_fetch_assoc($rs);
}

// check to see if the user is allowed to save the document in the place he wants to save it in
if ($use_udperms == 1) {
	if ($existingDocument['parent'] != $parent) {
		include_once "./processors/user_documents_permissions.class.php";
		$udperms = new udperms();
		$udperms->user = $modx->getLoginUserID();
		$udperms->document = $parent;
		$udperms->role = $_SESSION['mgrRole'];

		if (!$udperms->checkPermissions()) {
			if ($actionToTake == 'edit') {
				$modx->manager->saveFormValues(27);
				$url = "index.php?a=27&id=" . $id;
				include_once "header.inc.php";
				$modx->webAlert(sprintf($_lang['access_permission_parent_denied'], $docid, $alias), $url);
				include_once "footer.inc.php";
				exit;
			} else {
				$modx->manager->saveFormValues(4);
				$url = "index.php?a=4";
				include_once "header.inc.php";
				$modx->webAlert(sprintf($_lang['access_permission_parent_denied'], $docid, $alias), $url);
				include_once "footer.inc.php";
				exit;
			}
		}
	}
}

switch ($actionToTake) {
	case 'new' :

		// invoke OnBeforeDocFormSave event
		$modx->invokeEvent("OnBeforeDocFormSave", array (
			"mode" => "new",
			"id" => $id
		));

		// Deny publishing if not permitted
		if (!$modx->hasPermission('publish_document')) {
			$pub_date = 0;
			$unpub_date = 0;
			$published = 0;
		}

		$publishedon = ($published ? time() : 0);
		$publishedby = ($published ? $modx->getLoginUserID() : 0);

		$sql = "INSERT INTO $dbase.`" . $table_prefix . "site_content` (introtext,content, pagetitle, longtitle, type, description, alias, link_attributes, isfolder, richtext, published, parent, template, menuindex, searchable, cacheable, createdby, createdon, editedby, editedon, publishedby, publishedon, pub_date, unpub_date, contentType, content_dispo, donthit, menutitle, hidemenu)
						VALUES('" . $introtext . "','" . $content . "', '" . $pagetitle . "', '" . $longtitle . "', '" . $type . "', '" . $description . "', '" . $alias . "', '" . $link_attributes . "', '" . $isfolder . "', '" . $richtext . "', '" . $published . "', '" . $parent . "', '" . $template . "', '" . $menuindex . "', '" . $searchable . "', '" . $cacheable . "', '" . $modx->getLoginUserID() . "', " . time() . ", '" . $modx->getLoginUserID() . "', " . time() . ", " . $publishedby . ", " . $publishedon . ", '$pub_date', '$unpub_date', '$contentType', '$contentdispo', '$donthit', '$menutitle', '$hidemenu')";

		$rs = mysql_query($sql);
		if (!$rs) {
			$modx->manager->saveFormValues(27);
			echo "An error occured while attempting to save the new document: " . mysql_error();
			exit;
		}

		if (!$key = mysql_insert_id()) {
			$modx->manager->saveFormValues(27);
			echo "Couldn't get last insert key!";
			exit;
		}

		// Modified by Raymond for TV - Orig Added by Apodigm for DocVars
		foreach ($tmplvars as $field => $value) {
			if (is_array($value)) {
				$tvId = $value[0];
				$tvVal = $value[1];
				$sql = "INSERT INTO $dbase.`" . $table_prefix . "site_tmplvar_contentvalues` (tmplvarid, contentid, value) VALUES('$tvId','$key', '" . mysql_escape_string($tvVal) . "')";
				$rs = mysql_query($sql);
			}
		}
		//End Modification

		/*******************************************************************************/
		// put the document in the document_groups it should be in
		// first, check that up_perms are switched on!
		if ($use_udperms == 1) {
			if (is_array($document_groups)) {
				foreach ($document_groups as $dgkey => $value) {
					$sql = "INSERT INTO $dbase.`" . $table_prefix . "document_groups` (document_group, document) values(" . stripslashes($value) . ", $key)";
					$rs = mysql_query($sql);
					if (!$rs) {
						$modx->manager->saveFormValues(27);
						echo "An error occured while attempting to add the document to a document_group.";
						exit;
					}
				}
			}
		}
		// end of document_groups stuff!
		/*******************************************************************************/

		/*******************************************************************************/
		if ($parent != 0) {
			$sql = "UPDATE $dbase.`" . $table_prefix . "site_content` SET isfolder=1 WHERE id=" . $_REQUEST['parent'] . ";";
			$rs = mysql_query($sql);
			if (!$rs) {
				echo "An error occured while attempting to change the document's parent to a folder.";
			}
		}
		// end of the parent stuff
		/*******************************************************************************/

		// Save META Keywords
		saveMETAKeywords($key);

		// invoke OnDocFormSave event
		$modx->invokeEvent("OnDocFormSave", array (
			"mode" => "new",
			"id" => $key
		));

		// secure web documents - flag as private 
		include $base_path . "manager/includes/secure_web_documents.inc.php";
		secureWebDocument($key);

		// secure manager documents - flag as private 
		include $base_path . "manager/includes/secure_mgr_documents.inc.php";
		secureMgrDocument($key);

		if ($syncsite == 1) {
			// empty cache
			include_once "cache_sync.class.processor.php";
			$sync = new synccache();
			$sync->setCachepath("../assets/cache/");
			$sync->setReport(false);
			$sync->emptyCache(); // first empty the cache		
		}

		// redirect/stay options						
		if ($_POST['stay'] != '') {
			// weblink
			if ($_POST['mode'] == "72")
				$a = ($_POST['stay'] == '2') ? "27&id=$key" : "72&pid=$parent";
			// document
			if ($_POST['mode'] == "4")
				$a = ($_POST['stay'] == '2') ? "27&id=$key" : "4&pid=$parent";
			$header = "Location: index.php?a=" . $a . "&r=1&stay=" . $_POST['stay'];
		} else {
			$header = "Location: index.php?r=1&id=$id&a=7&dv=1";
		}
		header($header);

		break;
	case 'edit' :

		// first, get the document's current parent.	
		$sql = "SELECT parent FROM $dbase.`" . $table_prefix . "site_content` WHERE id=" . $_REQUEST['id'] . ";";
		$rs = mysql_query($sql);
		if (!$rs) {
			$modx->manager->saveFormValues(27);
			echo "An error occured while attempting to find the document's current parent.";
			exit;
		}
		$row = mysql_fetch_assoc($rs);
		$oldparent = $row['parent'];
		// ok, we got the parent

		$doctype = $row['type'];

		if ($id == $site_start && $published == 0) {
			$modx->manager->saveFormValues(27);
			echo "Document is linked to site_start variable and cannot be unpublished!";
			exit;
		}
		if ($id == $site_start && ($pub_date != "0" || $unpub_date != "0")) {
			$modx->manager->saveFormValues(27);
			echo "Document is linked to site_start variable and cannot have publish or unpublish dates set!";
			exit;
		}
		if ($parent == $id) {
			$modx->manager->saveFormValues(27);
			echo "Document can not be it's own parent!";
			exit;
		}
		// check to see document is a folder.
		$sql = "SELECT count(*) FROM $dbase.`" . $table_prefix . "site_content` WHERE parent=" . $_REQUEST['id'] . ";";
		$rs = mysql_query($sql);
		if (!$rs) {
			$modx->manager->saveFormValues(27);
			echo "An error occured while attempting to find the document's children.";
			exit;
		}
		$row = mysql_fetch_assoc($rs);
		if ($row['count(*)'] > 0) {
			$isfolder = 1;
		}

		// Set publishedon and publishedby
		$was_published = $modx->db->getValue("SELECT published FROM `{$table_prefix}site_content` WHERE id = '{$id}';");

		// Keep original publish state, if change is not permitted
		if (!$modx->hasPermission('publish_document')) {
			$published = $was_published;
			$pub_date = 'pub_date';
			$unpub_date = 'unpub_date';
		}

		// If it was changed from unpublished to published
		if (!$was_published && $published) {
			$publishedon = time();
			$publishedby = $modx->getLoginUserID();
		}
		elseif ($was_published && !$published) {
			$publishedon = 0;
			$publishedby = 0;
		} else {
			$publishedon = 'publishedon';
			$publishedby = 'publishedby';
		}

		// invoke OnBeforeDocFormSave event
		$modx->invokeEvent("OnBeforeDocFormSave", array (
			"mode" => "upd",
			"id" => $id
		));

		// update the document
		$sql = "UPDATE $dbase.`" . $table_prefix . "site_content` SET introtext='$introtext', content='$content', pagetitle='$pagetitle', longtitle='$longtitle', type='$type', description='$description', alias='$alias', link_attributes='$link_attributes', 
				isfolder=$isfolder, richtext=$richtext, published=$published, pub_date=$pub_date, unpub_date=$unpub_date, parent=$parent, template=$template, menuindex='$menuindex',
				searchable=$searchable, cacheable=$cacheable, editedby=" . $modx->getLoginUserID() . ", editedon=" . time() . ", publishedon=$publishedon, publishedby=$publishedby, contentType='$contentType', content_dispo='$contentdispo', donthit='$donthit', menutitle='$menutitle', hidemenu='$hidemenu'  WHERE id=$id;";

		$rs = mysql_query($sql);
		if (!$rs) {
			echo "An error occured while attempting to save the edited document. The generated SQL is: <i> $sql </i>.";
		}

		// Modified by Raymond for TV - Orig Added by Apodigm - DocVars
		$sql = "SELECT tmplvarid FROM $dbase.`" . $table_prefix . "site_tmplvar_contentvalues` WHERE contentid=$id";
		$rs = mysql_query($sql);
		$tvIds = array ();
		while (list ($tvId) = mysql_fetch_row($rs)) {
			$tvIds[count($tvIds)] = $tvId;
		}
		foreach ($tmplvars as $field => $value) {
			if (!is_array($value)) {
				if (in_array($value, $tvIds)) {
					//delete unused variable 
					$sql = "DELETE FROM $dbase.`" . $table_prefix . "site_tmplvar_contentvalues` WHERE tmplvarid=$value AND contentid='$id';";
					$rs = mysql_query($sql);
				}
			} else {
				$tvId = $value[0];
				$tvVal = $value[1];
				if (in_array($tvId, $tvIds)) {
					//update the existing record
					$sql = "UPDATE $dbase.`" . $table_prefix . "site_tmplvar_contentvalues` SET value='" . mysql_escape_string($tvVal) . "' WHERE tmplvarid=$tvId AND contentid='$id';";
					$rs = mysql_query($sql);
				} else {
					//add a new record
					$sql = "INSERT INTO $dbase.`" . $table_prefix . "site_tmplvar_contentvalues` (tmplvarid, contentid,value) VALUES($tvId, '$id', '" . mysql_escape_string($tvVal) . "')";
					$rs = mysql_query($sql);
				}
			}
		}
		//End Modification

		/*******************************************************************************/
		// put the document in the document_groups it should be in
		// first, check that up_perms are switched on!
		if ($use_udperms == 1) {
			// delete old permissions on the document
			$sql = "DELETE FROM $dbase.`" . $table_prefix . "document_groups` WHERE document=$id;";
			$rs = mysql_query($sql);
			if (!$rs) {
				$modx->manager->saveFormValues(27);
				echo "An error occured while attempting to delete previous document_group entries.";
				exit;
			}
			if (is_array($document_groups)) {
				foreach ($document_groups as $dgkey => $value) {
					$sql = "INSERT INTO $dbase.`" . $table_prefix . "document_groups` (document_group, document) values(" . stripslashes($value) . ", $id)";
					$rs = mysql_query($sql);
					if (!$rs) {
						$modx->manager->saveFormValues(27);
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

		if ($parent != 0) {
			$sql = "UPDATE $dbase.`" . $table_prefix . "site_content` SET isfolder=1 WHERE id=" . $_REQUEST['parent'] . ";";
			$rs = mysql_query($sql);
			if (!$rs) {
				echo "An error occured while attempting to change the new parent to a folder.";
			}
		}

		// finished moving the document, now check to see if the old_parent should no longer be a folder.
		$sql = "SELECT count(*) FROM $dbase.`" . $table_prefix . "site_content` WHERE parent=$oldparent;";
		$rs = mysql_query($sql);
		if (!$rs) {
			echo "An error occured while attempting to find the old parents' children.";
		}
		$row = mysql_fetch_assoc($rs);
		$limit = $row['count(*)'];

		if ($limit == 0) {
			$sql = "UPDATE $dbase.`" . $table_prefix . "site_content` SET isfolder=0 WHERE id=$oldparent;";
			$rs = mysql_query($sql);
			if (!$rs) {
				echo "An error occured while attempting to change the old parent to a regular document.";
			}
		}

		// end of the parent stuff
		/*******************************************************************************/

		// Save META Keywords
		saveMETAKeywords($id);

		// invoke OnDocFormSave event
		$modx->invokeEvent("OnDocFormSave", array (
			"mode" => "upd",
			"id" => $id
		));

		// secure web documents - flag as private 
		include $base_path . "manager/includes/secure_web_documents.inc.php";
		secureWebDocument($id);

		// secure manager documents - flag as private 
		include $base_path . "manager/includes/secure_mgr_documents.inc.php";
		secureMgrDocument($id);

		if ($syncsite == 1) {
			// empty cache
			include_once "cache_sync.class.processor.php";
			$sync = new synccache();
			$sync->setCachepath("../assets/cache/");
			$sync->setReport(false);
			$sync->emptyCache(); // first empty the cache		
		}

		// Mod by Raymond
		if ($_POST['refresh_preview'] == '1')
			$header = "Location: ../index.php?id=$id&manprev=z";
		else {
			if ($_POST['stay'] != '') {
				$id = $_REQUEST['id'];
				if ($type == "reference") {
					// weblink		
					$a = ($_POST['stay'] == '2') ? "27&id=$id" : "72&pid=$parent";
				} else {
					// document		
					$a = ($_POST['stay'] == '2') ? "27&id=$id" : "4&pid=$parent";
				}
				$header = "Location: index.php?a=" . $a . "&r=1&stay=" . $_POST['stay'];
			} else {
				$header = "Location: index.php?r=1&id=$id&a=7&dv=1";
			}
		}
		header($header);
		break;
	default :
		header("Location: index.php?a=7");
		exit;
}

function stripAlias($alias) {
	global $modx;

	if (strtoupper($modx->config['modx_charset']) == 'UTF-8')
		$alias = utf8_decode($alias);
	$alias = strtr($alias, array (
		chr(196
	) => 'Ae', chr(214) => 'Oe', chr(220) => 'Ue', chr(228) => 'ae', chr(246) => 'oe', chr(252) => 'ue', chr(223) => 'ss'));

	$alias = strip_tags($alias);
	//$alias = strtolower($alias); 
	$alias = preg_replace('/&.+?;/', '', $alias); // kill entities 
	$alias = preg_replace('/[^\.%A-Za-z0-9 _-]/', '', $alias);
	$alias = preg_replace('/\s+/', '-', $alias);
	$alias = preg_replace('|-+|', '-', $alias);
	$alias = trim($alias, '-');
	return $alias;
}

// -- Save META Keywords --
function saveMETAKeywords($id) {
	global $modx;
	global $keywords;
	global $metatags;

	if ($modx->hasPermission('edit_doc_metatags')) {
		// keywords - remove old keywords first
		$tbl = $modx->getFullTableName("keyword_xref");
		$modx->db->delete($tbl, "content_id='$id'");
		for ($i = 0; $i < count($keywords); $i++) {
			$kwid = $keywords[$i];
			$flds = array (
				content_id => $id,
				keyword_id => $kwid
			);
			$modx->db->insert($flds, $tbl);
		}
		// meta tags - remove old tags first
		$tbl = $modx->getFullTableName("site_content_metatags");
		$modx->db->delete($tbl, "content_id='$id'");
		for ($i = 0; $i < count($metatags); $i++) {
			$kwid = $metatags[$i];
			$flds = array (
				content_id => $id,
				metatag_id => $kwid
			);
			$modx->db->insert($flds, $tbl);
		}
		$tbl = $modx->getFullTableName("site_content");
		$flds = array (
			haskeywords => (count($keywords
		) ? 1 : 0), hasmetatags => (count($metatags) ? 1 : 0));
		$modx->db->update($flds, $tbl, "id='$id'");
	}
}
?>
