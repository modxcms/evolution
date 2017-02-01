<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

function renderViewSwitchButtons($cssId) {
	global $_lang;
	return '
			<li><a href="#" class="switchform-btn" data-target="switchForm_'.$cssId.'">'.$_lang['view_options'].'</a></li>
			<form id="switchForm_'.$cssId.'" class="switchForm" data-target="'.$cssId.'" style="display:none">
				<label><input type="checkbox" name="cb_buttons" value="buttons"> Buttons</label>
				<label><input type="checkbox" name="cb_description" value="description"> Description</label>
				<label><input type="checkbox" name="cb_icons" value="icons"> Icons</label>
				<br/>
				<label><input type="radio" name="view" value="list"> List</label>
				<label><input type="radio" name="view" value="inline"> Inline</label>
				<label><input type="radio" name="view" value="flex"> Flex</label>
				<label><input type="number" placeholder="Columns" name="columns" class="columns" value="3"></label>
				<br/>
				<label>Font-Size <input type="number" placeholder="" name="fontsize" class="fontsize" value="10"></label>
				<hr/>
				<label><input type="checkbox" class="cb_all" name="cb_all" value="all"> All Tabs</label>
				<a href="#" class="btn_reset"> Reset</a>
			</form>';
}

function createResourceList($resourceTable, $resources) {
	global $modx,$_lang,$_style,$modx_textdir;

	$items = isset($resources->items[$resourceTable]) ? $resources->items[$resourceTable] : false;
	$types = isset($resources->types[$resourceTable]) ? $resources->types[$resourceTable] : false;
	
	if(!$items) return $_lang['no_results'];
	if(!$types) return 'Error: Types missing';
	
	$output   = '<div class="clearfix"></div>';
	$output  .= '<div class="panel-group no-transition">';
	$output  .= '<div id="' . $resourceTable . '" class="resourceTable panel panel-default">';
	$preCat   = '';

	foreach($items as $row) {
		$row['category'] = stripslashes($row['category']);
		if ($preCat !== $row['category']) {
			$output .= $preCat != '' ? '</ul></div>' : '';
			$row['tab'] = $resourceTable;
			$row['categoryid'] = $row['catid'] != '' ? ' <small>(' . $row['catid'] . ')</small>' : '';
			$row['catid'] = $row['catid'] ? $row['catid'] : 0;
			$output .= $modx->parseText('
					<div class="panel-heading">
						<span class="panel-title">
							<a class="accordion-toggle" id="toggle[+tab+][+catid+]" href="#collapse[+tab+][+catid+]" data-cattype="[+tab+]" data-catid="[+catid+]" title="Click to toggle collapse. Shift+Click to toggle all.">
								<span class="category_name">
									<strong>[+category+][+categoryid+]</strong>
								</span>
							</a>
						</span>
					</div>
					<div id="collapse[+tab+][+catid+]" class="panel-collapse collapse in" aria-expanded="true">
						<ul class="elements">', $row);
		}

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
		
		// Placeholders
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
			'textdir'=>$modx_textdir ? '&rlm;' : '',
		);

		$template = '
					<li>
						<div class="rTable">
							<div class="rTableRow">
								[+lockedByUser+]
								<div class="mainCell elements_description">
									<span[+class+]>
										<a class="man_el_name" data-type="[+resourceTable+]" data-id="[+id+]" href="index.php?a=[+actionEdit+]&amp;id=[+id+]">
											[+name+] <small>([+id+])</small> <span class="elements_descr">[+caption+]</span>
										</a>[+textdir+]
									</span>
								</div>
								[+buttons+]
							</div>
						</div>
					</li>';

		$output .= $modx->parseText($template, $ph);

		$preCat = $row['category'];
	}

	$output .= '</ul></div>';
	$output .= '</div></div>';
	$output .= '<div class="clearfix"></div>';

	return $output;
}