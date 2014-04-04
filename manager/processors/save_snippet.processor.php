<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if (!$modx->hasPermission('save_snippet')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = intval($_POST['id']);
$snippet = trim($modx->db->escape($_POST['post']));
$name = $modx->db->escape(trim($_POST['name']));
$description = $modx->db->escape($_POST['description']);
$locked = $_POST['locked']=='on' ? 1 : 0 ;
// strip out PHP tags from snippets
if ( strncmp($snippet, "<?", 2) == 0 ) {
    $snippet = substr($snippet, 2);
    if ( strncmp( $snippet, "php", 3 ) == 0 ) $snippet = substr($snippet, 3);
    if ( substr($snippet, -2, 2) == '?>' ) $snippet = substr($snippet, 0, -2);
}
$properties = $modx->db->escape($_POST['properties']);
$moduleguid = $modx->db->escape($_POST['moduleguid']);
$sysevents = $_POST['sysevents'];

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

if($name=="") $name = "Untitled snippet";

switch ($_POST['mode']) {
    case '23': // Save new snippet

		// invoke OnBeforeSnipFormSave event
		$modx->invokeEvent("OnBeforeSnipFormSave",
								array(
									"mode"	=> "new",
									"id"	=> $id
								));

		// disallow duplicate names for new snippets
		$rs = $modx->db->select('COUNT(id)', $modx->getFullTableName('site_snippets'), "name='{$name}'");
		$count = $modx->db->getValue($rs);
		if($count > 0) {
			$modx->manager->saveFormValues(23);
			$modx->webAlertAndQuit(sprintf($_lang['duplicate_name_found_general'], $_lang['snippet'], $name), "index.php?a=23");
		}

		//do stuff to save the new doc
		$newid = $modx->db->insert(
			array(
				'name'    => $name,
				'description' => $description,
				'snippet' => $snippet,
				'moduleguid' => $moduleguid,
				'locked' => $locked,
				'properties' => $properties,
				'category' => $categoryid,
			), $modx->getFullTableName('site_snippets'));

			// invoke OnSnipFormSave event
			$modx->invokeEvent("OnSnipFormSave",
									array(
										"mode"	=> "new",
										"id"	=> $newid
									));

		// Set the item name for logger
		$_SESSION['itemname'] = $name;

			// empty cache
			$modx->clearCache('full');
			
			// finished emptying cache - redirect
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "22&id=$newid":"23";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=76&r=2";
				header($header);
			}
        break;
    case '22': // Save existing snippet
		// invoke OnBeforeSnipFormSave event
		$modx->invokeEvent("OnBeforeSnipFormSave",
								array(
									"mode"	=> "upd",
									"id"	=> $id
								));

		// disallow duplicate names for snippets
		$rs = $modx->db->select('COUNT(*)', $modx->getFullTableName('site_snippets'), "name='{$name}' AND id!='{$id}'");
		if ($modx->db->getValue($rs) > 0) {
			$modx->manager->saveFormValues(22);
			$modx->webAlertAndQuit(sprintf($_lang['duplicate_name_found_general'], $_lang['snippet'], $name), "index.php?a=22&id={$id}");
		}

		//do stuff to save the edited doc
		$modx->db->update(
			array(
				'name'        => $name,
				'description' => $description,
				'snippet'     => $snippet,
				'moduleguid'  => $moduleguid,
				'locked'      => $locked,
				'properties'  => $properties,
				'category'    => $categoryid,
			), $modx->getFullTableName('site_snippets'), "id='{$id}'");

			// invoke OnSnipFormSave event
			$modx->invokeEvent("OnSnipFormSave",
									array(
										"mode"	=> "upd",
										"id"	=> $id
									));

		// Set the item name for logger
		$_SESSION['itemname'] = $name;

			// empty cache
			$modx->clearCache('full');

			if($_POST['runsnippet']) run_snippet($snippet);
			// finished emptying cache - redirect	
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "22&id=$id":"23";
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
?>