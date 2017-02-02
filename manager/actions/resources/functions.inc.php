<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

function renderViewSwitchButtons($cssId) {
	global $modx,$_lang;
	
	$output = file_get_contents(MODX_MANAGER_PATH.'actions/resources/tpl_viewForm.tpl');
	$output = $modx->parseText($output, $_lang, '[%', '%]');
	return $modx->parseText($output, array(
		'cssId'=>$cssId
	));
}

function createResourceList($resourceTable, $resources) {
	global $modx,$_lang,$_style,$modx_textdir;

	$items = isset($resources->items[$resourceTable]) ? $resources->items[$resourceTable] : false;
	$types = isset($resources->types[$resourceTable]) ? $resources->types[$resourceTable] : false;
	
	if(!$items) return $_lang['no_results'];
	if(!$types) return 'Error: Types missing';
	
	$tpl = array(
		'panelGroup'    =>file_get_contents(MODX_MANAGER_PATH.'actions/resources/tpl_panelGroup.tpl'),
		'panelHeading'  =>file_get_contents(MODX_MANAGER_PATH.'actions/resources/tpl_panelHeading.tpl'),
		'panelCollapse' =>file_get_contents(MODX_MANAGER_PATH.'actions/resources/tpl_panelCollapse.tpl'),
		'elementsRow'   =>file_get_contents(MODX_MANAGER_PATH.'actions/resources/tpl_elementsRow.tpl'),
	);
	
	// Prepare elements- and categories-list
	$elements = array();
	$categories = array();
	foreach($items as $row) {
		
		$class = '';
		if ($resourceTable == 'site_templates') {
			$class           = $row['selectable'] ? '' : 'disabledPlugin';
			$lockElementType = 1;
		}
		if ($resourceTable == 'site_tmplvars') {
			$class           = $row['reltpl'] ? '' : 'disabledPlugin';
			$lockElementType = 2;
		}
		if ($resourceTable == 'site_htmlsnippets') {
			$lockElementType = 3;
		}
		if ($resourceTable == 'site_snippets') {
			$lockElementType = 4;
		}
		if ($resourceTable == 'site_plugins') {
			$class           = $row['disabled'] ? 'disabledPlugin' : '';
			$lockElementType = 5;
		}

		// Prepare displaying user-locks
		$lockedByUser = '';
		$rowLock      = $modx->elementIsLocked($lockElementType, $row['id'], true);
		if ($rowLock && $modx->hasPermission('display_locks')) {
			if ($rowLock['sid'] == $modx->sid) {
				$title        = $modx->parseText($_lang["lock_element_editing"], array('element_type' => $_lang["lock_element_type_" . $lockElementType], 'lasthit_df' => $rowLock['lasthit_df']));
				$lockedByUser = '<span title="' . $title . '" class="editResource" style="cursor:context-menu;"><img src="' . $_style['icons_preview_resource'] . '" /></span>&nbsp;';
			}
			else {
				$title = $modx->parseText($_lang["lock_element_locked_by"], array('element_type' => $_lang["lock_element_type_" . $lockElementType], 'username' => $rowLock['username'], 'lasthit_df' => $rowLock['lasthit_df']));
				if ($modx->hasPermission('remove_locks')) {
					$lockedByUser = '<a href="#" onclick="unlockElement(' . $lockElementType . ', ' . $row['id'] . ', this);return false;" title="' . $title . '" class="lockedResource"><img src="' . $_style['icons_secured'] . '" /></a>';
				}
				else {
					$lockedByUser = '<span title="' . $title . '" class="lockedResource" style="cursor:context-menu;"><img src="' . $_style['icons_secured'] . '" /></span>';
				}
			}
		}
		if($lockedByUser) $lockedByUser = '<div class="lockCell">'.$lockedByUser.'</div>';

		// Caption
		if ($resourceTable == 'site_tmplvars') {
			$caption = !empty($row['description']) ? ' ' . $row['caption'] . ' &nbsp; <small>(' . $row['description'] . ')</small>' : ' - ' . $row['caption'];
		}
		else {
			$caption = !empty($row['description']) ? ' ' . $row['description'] : '';
		}

		// Special marks
		$tplInfo = array();
		if ($row['locked']) $tplInfo[] = $_lang['locked'];
		if ($row['id'] == $modx->config['default_template'] && $resourceTable == 'site_templates') $tplInfo[] = $_lang['defaulttemplate_title'];
		$marks = !empty($tplInfo) ? ' <em>(' . join(', ', $tplInfo) . ')</em>' : '';

		/* row buttons */
		$buttons = '';
		if ($modx->hasPermission($types['actions']['edit'][1])) {
			$buttons .= '<li><a class="btn btn-xs btn-default" title="' . $_lang["edit_resource"] . '" href="index.php?a='.$types['actions']['edit'][0].'&amp;id=' . $row['id'] . '"><i class="fa fa-edit fa-fw"></i></a></li>';
		}
		if ($modx->hasPermission($types['actions']['duplicate'][1])) {
			$buttons .= '<li><a onclick="return confirm(\'' . $_lang["confirm_duplicate_record"] . '\')" class="btn btn-xs btn-default" title="' . $_lang["resource_duplicate"] . '" href="index.php?a='.$types['actions']['duplicate'][0].'&amp;id=' . $row['id'] . '"><i class="fa fa-clone fa-fw"></i></a></li>';
		}
		if ($modx->hasPermission($types['actions']['remove'][1])) {
			$buttons .= '<li><a onclick="return confirm(\'' . $_lang["confirm_delete_template"] . '\')" class="btn btn-xs btn-default" title="' . $_lang["delete_resource"] . '" href="index.php?a='.$types['actions']['remove'][0].'&amp;id=' . $row['id'] . '"><i class="fa fa-trash fa-fw"></i></a></li>';
		}
		$buttons = $buttons ? '<div class="btnCell"><ul class="elements_buttonbar">'.$buttons.'</ul></div>' : '';

		$catid = $row['catid'] ? $row['catid'] : 0;
		
		// Placeholders for elements-row
		$ph = array(
			'class'=>$class ? ' class="'.$class.'"' : '',
			'lockedByUser'=>$lockedByUser,
			'name'=>$row['name'],
			'caption'=>$caption,
			'buttons'=>$buttons,
			'marks'=>$marks,
			'id'=>$row['id'],
			'resourceTable'=>$resourceTable,
			'actionEdit'=>$types['actions']['edit'][0],
			'catid'=>$catid,
			'textdir'=>$modx_textdir ? '&rlm;' : '',
		);

		if(!isset($categories[$catid])) $categories[$catid] = array('name'=>stripslashes($row['category']));
		$elements[$catid][] = $ph;
	}
	
	// Now render categories / panel-collapse
	$panelGroup = '';
	foreach($elements as $catid=>$elList) {
		// Add panel-heading / category-collapse to output
		$panelGroup .= $modx->parseText($tpl['panelHeading'], array(
			'tab'        => $resourceTable,
			'category'   => $categories[$catid]['name'],
			'categoryid' => $catid != '' ? ' <small>(' . $catid . ')</small>' : '',
			'catid'      => $catid,
		));

		// Prepare content for panel-collapse
		$panelCollapse = '';
		foreach($elList as $el) {
			$panelCollapse .= $modx->parseText($tpl['elementsRow'], $el);
		}
		
		// Add panel-collapse with elements to output
		$panelGroup .= $modx->parseText($tpl['panelCollapse'], array(
			'tab'        => $resourceTable,
			'catid'      => $catid,
			'wrapper'    => $panelCollapse,
		));
	}

	return $modx->parseText($tpl['panelGroup'], array(
		'resourceTable'=>$resourceTable,
		'wrapper'=>$panelGroup
	));
}