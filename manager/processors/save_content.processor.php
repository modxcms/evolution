<?php
if (IN_MANAGER_MODE != "true")
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if (!$modx->hasPermission('save_document')) {
	$e->setError(3);
	$e->dumpError();
}

// preprocess POST values
$id = is_numeric($_POST['id']) ? $_POST['id'] : '';
$introtext = $modx->db->escape($_POST['introtext']);
$content = $modx->db->escape($_POST['ta']);
$pagetitle = $modx->db->escape($_POST['pagetitle']);
$description = $modx->db->escape($_POST['description']);
$alias = $modx->db->escape($_POST['alias']);
$link_attributes = $modx->db->escape($_POST['link_attributes']);
$isfolder = $_POST['isfolder'];
$richtext = $_POST['richtext'];
$published = $_POST['published'];
$parent = $_POST['parent'] != '' ? $_POST['parent'] : 0;
$template = $_POST['template'];
$menuindex = !empty($_POST['menuindex']) ? $_POST['menuindex'] : 0;
$searchable = $_POST['searchable'];
$cacheable = $_POST['cacheable'];
$syncsite = $_POST['syncsite'];
$pub_date = $_POST['pub_date'];
$unpub_date = $_POST['unpub_date'];
$document_groups = (isset($_POST['chkalldocs']) && $_POST['chkalldocs'] == 'on') ? array() : $_POST['docgroups'];
$type = $_POST['type'];
$keywords = $_POST['keywords'];
$metatags = $_POST['metatags'];
$contentType = $modx->db->escape($_POST['contentType']);
$contentdispo = intval($_POST['content_dispo']);
$longtitle = $modx->db->escape($_POST['longtitle']);
$donthit = intval($_POST['donthit']);
$menutitle = $modx->db->escape($_POST['menutitle']);
$hidemenu = intval($_POST['hidemenu']);

if (trim($pagetitle == "")) {
	if ($type == "reference") {
		$pagetitle = $_lang['untitled_weblink'];
	} else {
		$pagetitle = $_lang['untitled_document'];
	}
}

// get table names
$tbl_document_groups            = $modx->getFullTableName('document_groups');
$tbl_documentgroup_names        = $modx->getFullTableName('documentgroup_names');
$tbl_keyword_xref               = $modx->getFullTableName('keyword_xref');
$tbl_site_content               = $modx->getFullTableName('site_content');
$tbl_site_content_metatags      = $modx->getFullTableName('site_content_metatags');
$tbl_site_tmplvar_access        = $modx->getFullTableName('site_tmplvar_access');
$tbl_site_tmplvar_contentvalues = $modx->getFullTableName('site_tmplvar_contentvalues');
$tbl_site_tmplvar_templates     = $modx->getFullTableName('site_tmplvar_templates');
$tbl_site_tmplvars              = $modx->getFullTableName('site_tmplvars');

$actionToTake = "new";
if ($_POST['mode'] == '73' || $_POST['mode'] == '27') {
	$actionToTake = "edit";
}

// friendly url alias checks
if ($friendly_urls) {
	// auto assign alias
	if (!$alias && $automatic_alias) {
		$alias = strtolower(stripAlias(trim($pagetitle)));
		if(!$allow_duplicate_alias) {
			if ($modx->db->getValue("SELECT COUNT(id) FROM " . $tbl_site_content . " WHERE id<>'$id' AND alias='$alias'") != 0) {
				$cnt = 1;
				$tempAlias = $alias;
				while ($modx->db->getValue("SELECT COUNT(id) FROM " . $tbl_site_content . " WHERE id<>'$id' AND alias='$tempAlias'") != 0) {
					$tempAlias = $alias;
					$tempAlias .= $cnt;
					$cnt++;
				}
				$alias = $tempAlias;
			}
		}
	}

	// check for duplicate alias name if not allowed
	elseif ($alias && !$allow_duplicate_alias) {
		$alias = stripAlias($alias);
		if ($use_alias_path) {
			// only check for duplicates on the same level if alias_path is on
			$docid = $modx->db->getValue("SELECT id FROM " . $tbl_site_content . " WHERE id<>'$id' AND alias='$alias' AND parent=$parent LIMIT 1");
		} else {
			$docid = $modx->db->getValue("SELECT id FROM " . $tbl_site_content . " WHERE id<>'$id' AND alias='$alias' LIMIT 1");
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

// determine published status
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

// get document groups for current user
$tmplvars = array ();
if ($_SESSION['mgrDocgroups']) {
	$docgrp = implode(",", $_SESSION['mgrDocgroups']);
}

$sql = "SELECT DISTINCT tv.*, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
$sql .= "FROM $tbl_site_tmplvars AS tv ";
$sql .= "INNER JOIN $tbl_site_tmplvar_templates AS tvtpl ON tvtpl.tmplvarid = tv.id ";
$sql .= "LEFT JOIN $tbl_site_tmplvar_contentvalues AS tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = '$id' ";
$sql .= "LEFT JOIN $tbl_site_tmplvar_access tva ON tva.tmplvarid=tv.id  ";
$sql .= "WHERE tvtpl.templateid = '" . $template . "' AND (1='" . $_SESSION['mgrRole'] . "' OR ISNULL(tva.documentgroup)" . ((!$docgrp) ? "" : " OR tva.documentgroup IN ($docgrp)") . ") ORDER BY tv.rank;";
$rs = $modx->db->query($sql);
while ($row = $modx->db->getRow($rs)) {
	$additionalEncodings = array('-' => '%2D', '.' => '%2E', '_' => '%5F');
	$row['name'] = str_replace(array_keys($additionalEncodings), array_values($additionalEncodings), rawurlencode($row['name']));
	$tmplvar = '';
	switch ($row['type']) {
		case 'url':
			$tmplvar = $_POST["tv" . $row['name']];
			if ($_POST["tv" . $row['name'] . '_prefix'] != '--') {
				$tmplvar = str_replace(array (
					"ftp://",
					"http://"
				), "", $tmplvar);
				$tmplvar = $_POST["tv" . $row['name'] . '_prefix'] . $tmplvar;
			}
		break;
		case 'file':
			$tmplvar = $_POST["tv" . $row['name']];
		break;
		default:
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
		break;
	}
	// save value if it was modified
	if (strlen($tmplvar) > 0 && $tmplvar != $row['default_text']) {
		$tmplvars[$row['name']] = array (
			$row['id'],
			$tmplvar
		);
	} else {
		// Mark the variable for deletion
		$tmplvars[$row['name']] = $row['id'];
	}
}

// get the document, but only if it already exists
if ($actionToTake != "new") {
	$rs = $modx->db->select('*', $tbl_site_content, 'id='.$id);
	$limit = $modx->db->getRecordCount($rs);
	if ($limit > 1) {
		$e->setError(6);
		$e->dumpError();
	}
	if ($limit < 1) {
		$e->setError(7);
		$e->dumpError();
	}
	$existingDocument = $modx->db->getRow($rs);
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

		// deny publishing if not permitted
		if (!$modx->hasPermission('publish_document')) {
			$pub_date = 0;
			$unpub_date = 0;
			$published = 0;
		}

		$publishedon = ($published ? time() : 0);
		$publishedby = ($published ? $modx->getLoginUserID() : 0);

		$sql = "INSERT INTO $tbl_site_content (introtext,content, pagetitle, longtitle, type, description, alias, link_attributes, isfolder, richtext, published, parent, template, menuindex, searchable, cacheable, createdby, createdon, editedby, editedon, publishedby, publishedon, pub_date, unpub_date, contentType, content_dispo, donthit, menutitle, hidemenu)
						VALUES('" . $introtext . "','" . $content . "', '" . $pagetitle . "', '" . $longtitle . "', '" . $type . "', '" . $description . "', '" . $alias . "', '" . $link_attributes . "', '" . $isfolder . "', '" . $richtext . "', '" . $published . "', '" . $parent . "', '" . $template . "', '" . $menuindex . "', '" . $searchable . "', '" . $cacheable . "', '" . $modx->getLoginUserID() . "', " . time() . ", '" . $modx->getLoginUserID() . "', " . time() . ", " . $publishedby . ", " . $publishedon . ", '$pub_date', '$unpub_date', '$contentType', '$contentdispo', '$donthit', '$menutitle', '$hidemenu')";

		$rs = $modx->db->query($sql);
		if (!$rs) {
			$modx->manager->saveFormValues(27);
			echo "An error occured while attempting to save the new document: " . $modx->db->getLastError();
			exit;
		}

		if (!$key = $modx->db->getInsertId()) {
			$modx->manager->saveFormValues(27);
			echo "Couldn't get last insert key!";
			exit;
		}

		$tvChanges = array();
		foreach ($tmplvars as $field => $value) {
			if (is_array($value)) {
				$tvId = $value[0];
				$tvVal = $value[1];
				$tvChanges[] = array('tmplvarid' => $tvId, 'contentid' => $key, 'value' => $modx->db->escape($tvVal));
			}
		}
		if (!empty($tvChanges)) {
			foreach ($tvChanges as $tv) {
				$rs = $modx->db->insert($tv, $tbl_site_tmplvar_contentvalues);
			}
		}

		// document access permissions
		if ($use_udperms == 1 && is_array($document_groups)) {
			$new_groups = array();
			foreach ($document_groups as $value_pair) {
				// first, split the pair (this is a new document, so ignore the second value
				list($group) = explode(',', $value_pair); // @see manager/actions/mutate_content.dynamic.php @ line 1138 (permissions list)
				$new_groups[] = '('.(int)$group.','.$key.')';
			}
			$saved = true;
			if (!empty($new_groups)) {
				$sql = 'INSERT INTO '.$tbl_document_groups.' (document_group, document) VALUES '. implode(',', $new_groups);
				$saved = $modx->db->query($sql) ? $saved : false;
			}
			if (!$saved) {
				$modx->manager->saveFormValues(27);
				echo "An error occured while attempting to add the document to a document_group.";
				exit;
			}
		}

		// update parent folder status
		if ($parent != 0) {
			$fields = array('isfolder' => 1);
			$rs = $modx->db->update($fields, $tbl_site_content, 'id='.$_REQUEST['parent']);
			if (!$rs) {
				echo "An error occured while attempting to change the document's parent to a folder.";
			}
		}

		// save META Keywords
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
			$sync->emptyCache();
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

		// get the document's current parent
		$rs = $modx->db->select('parent', $tbl_site_content, 'id='.$_REQUEST['id']);
		if (!$rs) {
			$modx->manager->saveFormValues(27);
			echo "An error occured while attempting to find the document's current parent.";
			exit;
		}
		
		$row = $modx->db->getRow($rs);
		$oldparent = $row['parent'];
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
		// check to see document is a folder
		$rs = $modx->db->select('COUNT(id)', $tbl_site_content, 'parent='. $_REQUEST['id']);
		if (!$rs) {
			$modx->manager->saveFormValues(27);
			echo "An error occured while attempting to find the document's children.";
			exit;
		}
		$row = $modx->db->getRow($rs);
		if ($row['COUNT(id)'] > 0) {
			$isfolder = 1;
		}

		// set publishedon and publishedby
		$was_published = $modx->db->getValue("SELECT published FROM $tbl_site_content WHERE id='$id'");

		// keep original publish state, if change is not permitted
		if (!$modx->hasPermission('publish_document')) {
			$published = $was_published;
			$pub_date = 'pub_date';
			$unpub_date = 'unpub_date';
		}

		// if it was changed from unpublished to published
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
		$sql = "UPDATE $tbl_site_content SET introtext='$introtext', content='$content', pagetitle='$pagetitle', longtitle='$longtitle', type='$type', description='$description', alias='$alias', link_attributes='$link_attributes',
				isfolder=$isfolder, richtext=$richtext, published=$published, pub_date=$pub_date, unpub_date=$unpub_date, parent=$parent, template=$template, menuindex='$menuindex',
				searchable=$searchable, cacheable=$cacheable, editedby=" . $modx->getLoginUserID() . ", editedon=" . time() . ", publishedon=$publishedon, publishedby=$publishedby, contentType='$contentType', content_dispo='$contentdispo', donthit='$donthit', menutitle='$menutitle', hidemenu='$hidemenu'  WHERE id=$id;";

		$rs = $modx->db->query($sql);
		if (!$rs) {
			echo "An error occured while attempting to save the edited document. The generated SQL is: <i> $sql </i>.";
		}

		// update template variables
		$rs = $modx->db->select('id, tmplvarid', $tbl_site_tmplvar_contentvalues, 'contentid='. $id);
		$tvIds = array ();
		while ($row = $modx->db->getRow($rs)) {
			$tvIds[$row['tmplvarid']] = $row['id'];
		}
		$tvDeletions = array();
		$tvChanges = array();
		foreach ($tmplvars as $field => $value) {
			if (!is_array($value)) {
				if (isset($tvIds[$value])) $tvDeletions[] = $tvIds[$value];
			} else {
				$tvId = $value[0];
				$tvVal = $value[1];

				if (isset($tvIds[$tvId])) {
					$tvChanges[] = array(array('tmplvarid' => $tvId, 'contentid' => $id, 'value' => $modx->db->escape($tvVal)), array('id' => $tvIds[$tvId]));
				} else {
					$tvAdded[] = array('tmplvarid' => $tvId, 'contentid' => $id, 'value' => $modx->db->escape($tvVal));
				}
			}
		}

		if (!empty($tvDeletions)) {
			$rs = $modx->db->delete($tbl_site_tmplvar_contentvalues, 'id IN('.implode(',', $tvDeletions).')');
		}
			
		if (!empty($tvAdded)) {
			foreach ($tvAdded as $tv) {
				$rs = $modx->db->insert($tv, $tbl_site_tmplvar_contentvalues);
			}
		}
		
		if (!empty($tvChanges)) {
			foreach ($tvChanges as $tv) {
				$rs = $modx->db->update($tv[0], $tbl_site_tmplvar_contentvalues, 'id='.$tv[1]['id']);
			}
		}

		// set document permissions
		if ($use_udperms == 1 && is_array($document_groups)) {
			$new_groups = array();
			// process the new input
			foreach ($document_groups as $value_pair) {
				list($group, $link_id) = explode(',', $value_pair); // @see manager/actions/mutate_content.dynamic.php @ line 1138 (permissions list)
				$new_groups[$group] = $link_id;
			}

			// grab the current set of permissions on this document the user can access
			$isManager = $modx->hasPermission('access_permissions');
			$isWeb     = $modx->hasPermission('web_access_permissions');
			$sql = 'SELECT groups.id, groups.document_group FROM '.$tbl_document_groups.' AS groups '.
			       'LEFT JOIN '.$tbl_documentgroup_names.' AS dgn ON dgn.id = groups.document_group '.
			       'WHERE ((1='.(int)$isManager.' AND dgn.private_memgroup) '.
			       'OR    (1='.(int)$isWeb.' AND dgn.private_webgroup))'.
			       'AND groups.document = '.$id;
			$rs = $modx->db->query($sql);
			$old_groups = array();
			while ($row = $modx->db->getRow($rs)) $old_groups[$row['document_group']] = $row['id'];

			// update the permissions in the database
			$insertions = $deletions = array();
			foreach ($new_groups as $group => $link_id) {
				if (array_key_exists($group, $old_groups)) {
					unset($old_groups[$group]);
					continue;
				} elseif ($link_id == 'new') {
					$insertions[] = '('.(int)$group.','.$id.')';
				}
			}
			$saved = true;
			if (!empty($insertions)) {
				$sql_insert = 'INSERT INTO '.$tbl_document_groups.' (document_group, document) VALUES '.implode(',', $insertions);
				$saved = $modx->db->query($sql_insert) ? $saved : false;
			}
			if (!empty($old_groups)) {
				$sql_delete = 'DELETE FROM '.$tbl_document_groups.' WHERE id IN ('.implode(',', $old_groups).')';
				$saved = $modx->db->query($sql_delete) ? $saved : false;
			}
			// necessary to remove all permissions as document is public
			if ((isset($_POST['chkalldocs']) && $_POST['chkalldocs'] == 'on')) {
				$sql_delete = 'DELETE FROM '.$tbl_document_groups.' WHERE document='.$id;
				$saved = $modx->db->query($sql_delete) ? $saved : false;
			}
			if (!$saved) {
				$modx->manager->saveFormValues(27);
				echo "An error occured while saving document groups.";
				exit;
			}
		}

		// do the parent stuff
		if ($parent != 0) {
			$fields = array('isfolder' => 1);
			$rs = $modx->db->update($fields, $tbl_site_content, 'id='.$_REQUEST['parent']);
			if (!$rs) {
				echo "An error occured while attempting to change the new parent to a folder.";
			}
		}

		// finished moving the document, now check to see if the old_parent should no longer be a folder
		$rs = $modx->db->select('COUNT(id)', $tbl_site_content, 'parent='.$oldparent);
		if (!$rs) {
			echo "An error occured while attempting to find the old parents' children.";
		}
		$row = $modx->db->getRow($rs);
		$limit = $row['COUNT(id)'];

		if ($limit == 0) {
			$fields = array('isfolder' => 0);
			$rs = $modx->db->update($fields, $tbl_site_content, 'id='.$oldparent);
			if (!$rs) {
				echo "An error occured while attempting to change the old parent to a regular document.";
			}
		}

		// save META Keywords
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
			$sync->emptyCache();
		}
		
		if ($_POST['refresh_preview'] == '1')
			$header = "Location: ../index.php?id=$id&z=manprev";
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

/**
 * Swap named HTML entities with numeric entities.
 *
 * @see http://www.lazycat.org/software/html_entity_decode_full.phps
 */
function convert_entity($matches, $destroy= true) {
	static $table = array(
	    'quot' => '&#34;','amp' => '&#38;','lt' => '&#60;','gt' => '&#62;','OElig' => '&#338;','oelig' => '&#339;','Scaron' => '&#352;','scaron' => '&#353;',
	    'Yuml' => '&#376;','circ' => '&#710;','tilde' => '&#732;','ensp' => '&#8194;','emsp' => '&#8195;','thinsp' => '&#8201;','zwnj' => '&#8204;','zwj' => '&#8205;',
	    'lrm' => '&#8206;','rlm' => '&#8207;','ndash' => '&#8211;','mdash' => '&#8212;','lsquo' => '&#8216;','rsquo' => '&#8217;','sbquo' => '&#8218;','ldquo' => '&#8220;',
	    'rdquo' => '&#8221;','bdquo' => '&#8222;','dagger' => '&#8224;','Dagger' => '&#8225;','permil' => '&#8240;','lsaquo' => '&#8249;','rsaquo' => '&#8250;','euro' => '&#8364;',
	    'fnof' => '&#402;','Alpha' => '&#913;','Beta' => '&#914;','Gamma' => '&#915;','Delta' => '&#916;','Epsilon' => '&#917;','Zeta' => '&#918;','Eta' => '&#919;',
	    'Theta' => '&#920;','Iota' => '&#921;','Kappa' => '&#922;','Lambda' => '&#923;','Mu' => '&#924;','Nu' => '&#925;','Xi' => '&#926;','Omicron' => '&#927;',
	    'Pi' => '&#928;','Rho' => '&#929;','Sigma' => '&#931;','Tau' => '&#932;','Upsilon' => '&#933;','Phi' => '&#934;','Chi' => '&#935;','Psi' => '&#936;',
	    'Omega' => '&#937;','alpha' => '&#945;','beta' => '&#946;','gamma' => '&#947;','delta' => '&#948;','epsilon' => '&#949;','zeta' => '&#950;','eta' => '&#951;',
	    'theta' => '&#952;','iota' => '&#953;','kappa' => '&#954;','lambda' => '&#955;','mu' => '&#956;','nu' => '&#957;','xi' => '&#958;','omicron' => '&#959;',
	    'pi' => '&#960;','rho' => '&#961;','sigmaf' => '&#962;','sigma' => '&#963;','tau' => '&#964;','upsilon' => '&#965;','phi' => '&#966;','chi' => '&#967;',
	    'psi' => '&#968;','omega' => '&#969;','thetasym' => '&#977;','upsih' => '&#978;','piv' => '&#982;','bull' => '&#8226;','hellip' => '&#8230;','prime' => '&#8242;',
	    'Prime' => '&#8243;','oline' => '&#8254;','frasl' => '&#8260;','weierp' => '&#8472;','image' => '&#8465;','real' => '&#8476;','trade' => '&#8482;','alefsym' => '&#8501;',
	    'larr' => '&#8592;','uarr' => '&#8593;','rarr' => '&#8594;','darr' => '&#8595;','harr' => '&#8596;','crarr' => '&#8629;','lArr' => '&#8656;','uArr' => '&#8657;',
	    'rArr' => '&#8658;','dArr' => '&#8659;','hArr' => '&#8660;','forall' => '&#8704;','part' => '&#8706;','exist' => '&#8707;','empty' => '&#8709;','nabla' => '&#8711;',
	    'isin' => '&#8712;','notin' => '&#8713;','ni' => '&#8715;','prod' => '&#8719;','sum' => '&#8721;','minus' => '&#8722;','lowast' => '&#8727;','radic' => '&#8730;',
	    'prop' => '&#8733;','infin' => '&#8734;','ang' => '&#8736;','and' => '&#8743;','or' => '&#8744;','cap' => '&#8745;','cup' => '&#8746;','int' => '&#8747;',
	    'there4' => '&#8756;','sim' => '&#8764;','cong' => '&#8773;','asymp' => '&#8776;','ne' => '&#8800;','equiv' => '&#8801;','le' => '&#8804;','ge' => '&#8805;',
	    'sub' => '&#8834;','sup' => '&#8835;','nsub' => '&#8836;','sube' => '&#8838;','supe' => '&#8839;','oplus' => '&#8853;','otimes' => '&#8855;','perp' => '&#8869;',
	    'sdot' => '&#8901;','lceil' => '&#8968;','rceil' => '&#8969;','lfloor' => '&#8970;','rfloor' => '&#8971;','lang' => '&#9001;','rang' => '&#9002;','loz' => '&#9674;',
	    'spades' => '&#9824;','clubs' => '&#9827;','hearts' => '&#9829;','diams' => '&#9830;','nbsp' => '&#160;','iexcl' => '&#161;','cent' => '&#162;','pound' => '&#163;',
	    'curren' => '&#164;','yen' => '&#165;','brvbar' => '&#166;','sect' => '&#167;','uml' => '&#168;','copy' => '&#169;','ordf' => '&#170;','laquo' => '&#171;',
	    'not' => '&#172;','shy' => '&#173;','reg' => '&#174;','macr' => '&#175;','deg' => '&#176;','plusmn' => '&#177;','sup2' => '&#178;','sup3' => '&#179;',
	    'acute' => '&#180;','micro' => '&#181;','para' => '&#182;','middot' => '&#183;','cedil' => '&#184;','sup1' => '&#185;','ordm' => '&#186;','raquo' => '&#187;',
	    'frac14' => '&#188;','frac12' => '&#189;','frac34' => '&#190;','iquest' => '&#191;','Agrave' => '&#192;','Aacute' => '&#193;','Acirc' => '&#194;','Atilde' => '&#195;',
	    'Auml' => '&#196;','Aring' => '&#197;','AElig' => '&#198;','Ccedil' => '&#199;','Egrave' => '&#200;','Eacute' => '&#201;','Ecirc' => '&#202;','Euml' => '&#203;',
	    'Igrave' => '&#204;','Iacute' => '&#205;','Icirc' => '&#206;','Iuml' => '&#207;','ETH' => '&#208;','Ntilde' => '&#209;','Ograve' => '&#210;','Oacute' => '&#211;',
	    'Ocirc' => '&#212;','Otilde' => '&#213;','Ouml' => '&#214;','times' => '&#215;','Oslash' => '&#216;','Ugrave' => '&#217;','Uacute' => '&#218;','Ucirc' => '&#219;',
	    'Uuml' => '&#220;','Yacute' => '&#221;','THORN' => '&#222;','szlig' => '&#223;','agrave' => '&#224;','aacute' => '&#225;','acirc' => '&#226;','atilde' => '&#227;',
	    'auml' => '&#228;','aring' => '&#229;','aelig' => '&#230;','ccedil' => '&#231;','egrave' => '&#232;','eacute' => '&#233;','ecirc' => '&#234;','euml' => '&#235;',
	    'igrave' => '&#236;','iacute' => '&#237;','icirc' => '&#238;','iuml' => '&#239;','eth' => '&#240;','ntilde' => '&#241;','ograve' => '&#242;','oacute' => '&#243;',
	    'ocirc' => '&#244;','otilde' => '&#245;','ouml' => '&#246;','divide' => '&#247;','oslash' => '&#248;','ugrave' => '&#249;','uacute' => '&#250;','ucirc' => '&#251;',
	    'uuml' => '&#252;','yacute' => '&#253;','thorn' => '&#254;','yuml' => '&#255;',
	);
	if (isset($table[$matches[1]]))
	        return $table[$matches[1]];
	else    return $destroy ? '' : $matches[0];
}

/**
 * Format alias to be URL-safe
 *
 * @todo Make this function UTF-8 safe in PHP4? (see http://docs.php.net/manual/en/function.html-entity-decode.php#75153 )
 * @param string Alias to be formatted
 * @return string Safe alias
 */
function stripAlias($alias) {
	global $modx;

	// Convert all named HTML entities to numeric entities
	$alias = preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]{1,7});/', 'convert_entity', $alias);

	// Convert all numeric entities to their actual character
	$alias = preg_replace('/&#x([0-9a-f]{1,7});/ei', 'chr(hexdec("\\1"))', $alias);
	$alias = preg_replace('/&#([0-9]{1,7});/e', 'chr("\\1")', $alias);

	// Convert accented characters to their non-accented counterparts. Idea originally from Brett Florio (thanks!) ... expanded list from Textpattern (double-thanks!)
	static $replace_array = array('&' => 'and','\'' => '','À' => 'A','À' => 'A','Á' => 'A','Á' => 'A','Â' => 'A','Â' => 'A','Ã' => 'A','Ã' => 'A',
	    'Ä' => 'e','Ä' => 'A','Å' => 'A','Å' => 'A','Æ' => 'e','Æ' => 'E','Ā' => 'A','Ą' => 'A','Ă' => 'A','Ç' => 'C',
	    'Ç' => 'C','Ć' => 'C','Č' => 'C','Ĉ' => 'C','Ċ' => 'C','Ď' => 'D','Đ' => 'D','È' => 'E','È' => 'E','É' => 'E',
	    'É' => 'E','Ê' => 'E','Ê' => 'E','Ë' => 'E','Ë' => 'E','Ē' => 'E','Ę' => 'E','Ě' => 'E','Ĕ' => 'E','Ė' => 'E',
	    'Ĝ' => 'G','Ğ' => 'G','Ġ' => 'G','Ģ' => 'G','Ĥ' => 'H','Ħ' => 'H','Ì' => 'I','Ì' => 'I','Í' => 'I','Í' => 'I',
	    'Î' => 'I','Î' => 'I','Ï' => 'I','Ï' => 'I','Ī' => 'I','Ĩ' => 'I','Ĭ' => 'I','Į' => 'I','İ' => 'I','Ĳ' => 'J',
	    'Ĵ' => 'J','Ķ' => 'K','Ľ' => 'K','Ĺ' => 'K','Ļ' => 'K','Ŀ' => 'K','Ñ' => 'N','Ñ' => 'N','Ń' => 'N','Ň' => 'N',
	    'Ņ' => 'N','Ŋ' => 'N','Ò' => 'O','Ò' => 'O','Ó' => 'O','Ó' => 'O','Ô' => 'O','Ô' => 'O','Õ' => 'O','Õ' => 'O',
	    'Ö' => 'e','Ö' => 'e','Ø' => 'O','Ø' => 'O','Ō' => 'O','Ő' => 'O','Ŏ' => 'O','Œ' => 'E','Ŕ' => 'R','Ř' => 'R',
	    'Ŗ' => 'R','Ś' => 'S','Ş' => 'S','Ŝ' => 'S','Ș' => 'S','Ť' => 'T','Ţ' => 'T','Ŧ' => 'T','Ț' => 'T','Ù' => 'U',
	    'Ù' => 'U','Ú' => 'U','Ú' => 'U','Û' => 'U','Û' => 'U','Ü' => 'e','Ū' => 'U','Ü' => 'e','Ů' => 'U','Ű' => 'U',
	    'Ŭ' => 'U','Ũ' => 'U','Ų' => 'U','Ŵ' => 'W','Ŷ' => 'Y','Ÿ' => 'Y','Ź' => 'Z','Ż' => 'Z','à' => 'a','á' => 'a',
	    'â' => 'a','ã' => 'a','ä' => 'e','ä' => 'e','å' => 'a','ā' => 'a','ą' => 'a','ă' => 'a','å' => 'a','æ' => 'e',
	    'ç' => 'c','ć' => 'c','č' => 'c','ĉ' => 'c','ċ' => 'c','ď' => 'd','đ' => 'd','è' => 'e','é' => 'e','ê' => 'e',
	    'ë' => 'e','ē' => 'e','ę' => 'e','ě' => 'e','ĕ' => 'e','ė' => 'e','ƒ' => 'f','ĝ' => 'g','ğ' => 'g','ġ' => 'g',
	    'ģ' => 'g','ĥ' => 'h','ħ' => 'h','ì' => 'i','í' => 'i','î' => 'i','ï' => 'i','ī' => 'i','ĩ' => 'i','ĭ' => 'i',
	    'į' => 'i','ı' => 'i','ĳ' => 'j','ĵ' => 'j','ķ' => 'k','ĸ' => 'k','ł' => 'l','ľ' => 'l','ĺ' => 'l','ļ' => 'l',
	    'ŀ' => 'l','ñ' => 'n','ń' => 'n','ň' => 'n','ņ' => 'n','ŉ' => 'n','ŋ' => 'n','ò' => 'o','ó' => 'o','ô' => 'o',
	    'õ' => 'o','ö' => 'e','ö' => 'e','ø' => 'o','ō' => 'o','ő' => 'o','ŏ' => 'o','œ' => 'e','ŕ' => 'r','ř' => 'r',
	    'ŗ' => 'r','ù' => 'u','ú' => 'u','û' => 'u','ü' => 'e','ū' => 'u','ü' => 'e','ů' => 'u','ű' => 'u','ŭ' => 'u',
	    'ũ' => 'u','ų' => 'u','ŵ' => 'w','ÿ' => 'y','ŷ' => 'y','ż' => 'z','ź' => 'z','ß' => 's','ſ' => 's','Α' => 'A',
	    'Ά' => 'A','Β' => 'B','Γ' => 'G','Δ' => 'D','Ε' => 'E','Έ' => 'E','Ζ' => 'Z','Η' => 'I','Ή' => 'I','Θ' => 'TH',
	    'Ι' => 'I','Ί' => 'I','Ϊ' => 'I','Κ' => 'K','Λ' => 'L','Μ' => 'M','Ν' => 'N','Ξ' => 'KS','Ο' => 'O','Ό' => 'O',
	    'Π' => 'P','Ρ' => 'R','Σ' => 'S','Τ' => 'T','Υ' => 'Y','Ύ' => 'Y','Ϋ' => 'Y','Φ' => 'F','Χ' => 'X','Ψ' => 'PS',
	    'Ω' => 'O','Ώ' => 'O','α' => 'a','ά' => 'a','β' => 'b','γ' => 'g','δ' => 'd','ε' => 'e','έ' => 'e','ζ' => 'z',
	    'η' => 'i','ή' => 'i','θ' => 'th','ι' => 'i','ί' => 'i','ϊ' => 'i','ΐ' => 'i','κ' => 'k','λ' => 'l','μ' => 'm',
	    'ν' => 'n','ξ' => 'ks','ο' => 'o','ό' => 'o','π' => 'p','ρ' => 'r','σ' => 's','τ' => 't','υ' => 'y','ύ' => 'y',
	    'ϋ' => 'y','ΰ' => 'y','φ' => 'f','χ' => 'x','ψ' => 'ps','ω' => 'o','ώ' => 'o',
	);
	$alias = strtr($alias, $replace_array);

	$alias = strip_tags($alias); // strip HTML
	$alias = preg_replace('/[^\.%A-Za-z0-9 _-]/', '', $alias); // strip non-alphanumeric characters
	$alias = preg_replace('/\s+/', '-', $alias); // convert white-space to dash
	$alias = preg_replace('/-+/', '-', $alias);  // convert multiple dashes to one
	$alias = trim($alias, '-'); // trim excess
	return $alias;
}

// -- Save META Keywords --
function saveMETAKeywords($id) {
	global $modx, $keywords, $metatags,
	       $tbl_keyword_xref,
	       $tbl_site_content,
	       $tbl_site_content_metatags;

	if ($modx->hasPermission('edit_doc_metatags')) {
		// keywords - remove old keywords first
		$modx->db->delete($tbl_keyword_xref, "content_id=$id");
		for ($i = 0; $i < count($keywords); $i++) {
			$kwid = $keywords[$i];
			$flds = array (
				'content_id' => $id,
				'keyword_id' => $kwid
			);
			$modx->db->insert($flds, $tbl_keyword_xref);
		}
		// meta tags - remove old tags first
		$modx->db->delete($tbl_site_content_metatags, "content_id=$id");
		for ($i = 0; $i < count($metatags); $i++) {
			$kwid = $metatags[$i];
			$flds = array (
				'content_id' => $id,
				'metatag_id' => $kwid
			);
			$modx->db->insert($flds, $tbl_site_content_metatags);
		}
		$flds = array (
			'haskeywords' => (count($keywords) ? 1 : 0),
			'hasmetatags' => (count($metatags) ? 1 : 0)
		);
		$modx->db->update($flds, $tbl_site_content, "id=$id");
	}
}

?>