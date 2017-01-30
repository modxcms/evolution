<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

class mgrResources {
	var $types = array();
	var $items = array();
	
	function __construct() {
		$this->setTypes();
		$this->queryItemsFromDB();
	}

	function setTypes() {
		global $_lang;
		$this->types['site_templates']    = array(
			'title'=>$_lang["manage_templates"],
			'actions'=>array( 'edit'=>array(16,'edit_template'), 'duplicate'=>array(96,'new_template'), 'remove'=>array(21,'delete_template') ),
			'permissions'=>array('new_template','edit_template'),
			'name'=>'templatename'
		);
		$this->types['site_tmplvars']     = array(
			'title'=>$_lang["tmplvars"],
			'actions'=>array('edit'=>array(301,'edit_template'), 'duplicate'=>array(304,'edit_template'), 'remove'=>array(303,'edit_template')),
			'permissions'=>array('new_template','edit_template'),
		);
		$this->types['site_htmlsnippets'] = array(
			'title'=>$_lang["manage_htmlsnippets"],
			'actions'=>array('edit'=>array(78,'edit_chunk'), 'duplicate'=>array(97,'new_chunk'), 'remove'=>array(80,'delete_chunk')),
			'permissions'=>array('new_chunk','edit_chunk'),
		);
		$this->types['site_snippets']     = array(
			'title'=>$_lang["manage_snippets"],
			'actions'=>array('edit'=>array(22,'edit_snippet'), 'duplicate'=>array(98,'new_snippet'), 'remove'=>array(25,'delete_snippet')),
			'permissions'=>array('new_snippet','edit_snippet'),
		);
		$this->types['site_plugins']      = array(
			'title'=>$_lang["manage_plugins"],
			'actions'=>array('edit'=>array(102,'edit_plugin'), 'duplicate'=>array(105,'new_plugin'), 'remove'=>array(104,'delete_plugin')),
			'permissions'=>array('new_plugin','edit_plugin'),
		);
	}
	
	function queryItemsFromDB() {
		foreach($this->types as $resourceTable=>$type) {
			if($this->hasAnyPermissions($type['permissions'])) {
				$nameField = isset($type['name']) ? $type['name'] : 'name';
				$this->items[$resourceTable] = $this->queryResources($resourceTable, $nameField);
		   }
		 }
	}

	function hasAnyPermissions($permissions) {
		global $modx;
		
		foreach($permissions as $p) 
			if($modx->hasPermission($p)) return true;
		
		return false;
	}

	function createResourceList($resourceTable) {
		global $_lang;
		
		return !$this->items[$resourceTable] ? $_lang['no_results'] : self::render($resourceTable);
	}
		
	function queryResources($resourceTable, $nameField = 'name') {
		global $modx, $_lang;

		$pluginsql = $resourceTable == 'site_plugins' ? $resourceTable . '.disabled, ' : '';

		$tvsql  = '';
		$tvjoin = '';
		if ($resourceTable == 'site_tmplvars') {
			$tvsql    = 'site_tmplvars.caption, ';
			$tvjoin   = sprintf('LEFT JOIN %s AS stt ON site_tmplvars.id=stt.tmplvarid GROUP BY site_tmplvars.id,reltpl', $modx->getFullTableName('site_tmplvar_templates'));
			$sttfield = 'IF(stt.templateid,1,0) AS reltpl,';
		}
		else $sttfield = '';

		//$orderby = $resourceTable == 'site_plugins' ? '6,2' : '5,1';

		switch ($resourceTable) {
			case 'site_plugins':
				$orderby = '6,2';
				break;
			case 'site_tmplvars':
				$orderby = '7,3';
				break;
			case 'site_templates':
				$orderby = '6,1';
				break;
			default:
				$orderby = '5,1';
		}

		$selectableTemplates = $resourceTable == 'site_templates' ? "{$resourceTable}.selectable, " : "";

		$rs = $modx->db->select(
			"{$sttfield} {$pluginsql} {$tvsql} {$resourceTable}.{$nameField} as name, {$resourceTable}.id, {$resourceTable}.description, {$resourceTable}.locked, {$selectableTemplates}IF(isnull(categories.category),'{$_lang['no_category']}',categories.category) as category, categories.id as catid",
			$modx->getFullTableName($resourceTable) . " AS {$resourceTable}
	            LEFT JOIN " . $modx->getFullTableName('categories') . " AS categories ON {$resourceTable}.category = categories.id {$tvjoin}",
			"",
			$orderby
		);
		$limit = $modx->db->getRecordCount($rs);
		
		if($limit < 1) return false;
		
		$result = array();
		while ($row = $modx->db->getRow($rs)) {
			$result[] = $row;
		}
		return $result;
	}

	function render($resourceTable) {
		global $modx,$_lang,$_style,$modx_textdir;
		
		$output   = '<ul id="' . $resourceTable . '" class="resourceTable">';
		$preCat   = '';
		$insideUl = 0;
		foreach($this->items[$resourceTable] as $row) {
			$row['category'] = stripslashes($row['category']);
			if ($preCat !== $row['category']) {
				$output .= $insideUl ? '</ul>' : '';
				$output .= '<li><span class="category_name"><strong>' . $row['category'] . ($row['catid'] != '' ? ' <small>(' . $row['catid'] . ')</small>' : '') . '</strong><span><ul>';
				$insideUl = 1;
			}

			if ($resourceTable == 'site_templates') {
				$class           = $row['selectable'] ? '' : ' class="disabledPlugin"';
				$lockElementType = 1;
			}
			if ($resourceTable == 'site_tmplvars') {
				$class           = $row['reltpl'] ? '' : ' class="disabledPlugin"';
				$lockElementType = 2;
			}
			if ($resourceTable == 'site_htmlsnippets') {
				$lockElementType = 3;
			}
			if ($resourceTable == 'site_snippets') {
				$lockElementType = 4;
			}
			if ($resourceTable == 'site_plugins') {
				$class           = $row['disabled'] ? ' class="disabledPlugin"' : '';
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

			$output .= '<li><div class="rTable"><div class="rTableRow"><div class="rTableCell elements_description"><span' . $class . '>' . $lockedByUser . '<a class="man_el_name" href="index.php?a='. $this->types[$resourceTable]['actions']['edit'][0] .'&amp;id=' . $row['id'] . '">' . $row['name'] . ' <small>(' . $row['id'] . ')</small></a>' . ($modx_textdir ? '&rlm;' : '') . '</span> <span class="elements_descr">';

			if ($resourceTable == 'site_tmplvars') {
				$output .= !empty($row['description']) ? ' ' . $row['caption'] . ' &nbsp; <small>(' . $row['description'] . ')</small>' : ' - ' . $row['caption'];
			}
			else {
				$output .= !empty($row['description']) ? ' ' . $row['description'] : '</span>';
			}

			$tplInfo = array();
			if ($row['locked']) $tplInfo[] = $_lang['locked'];
			if ($row['id'] == $modx->config['default_template'] && $resourceTable == 'site_templates') $tplInfo[] = $_lang['defaulttemplate_title'];
			$output .= !empty($tplInfo) ? ' <em>(' . join(', ', $tplInfo) . ')</em>' : '';
			
			/* row buttons */
			$output .= '</div><div class="rTableCell elements_buttonbar">';

			if ($modx->hasPermission($this->types[$resourceTable]['actions']['edit'][1])) {
				$output .= '<a class="btn btn-xs btn-default" title="' . $_lang["edit_resource"] . '" href="index.php?a='.$this->types[$resourceTable]['actions']['edit'][0].'&amp;id=' . $row['id'] . '"><i class="fa fa-edit fa-fw"></i></a> ';
			}
			if ($modx->hasPermission($this->types[$resourceTable]['actions']['duplicate'][1])) {
				$output .= '<a onclick="return confirm(\'' . $_lang["confirm_duplicate_record"] . '\')" class="btn btn-xs btn-default" title="' . $_lang["resource_duplicate"] . '" href="index.php?a='.$this->types[$resourceTable]['actions']['duplicate'][0].'&amp;id=' . $row['id'] . '"><i class="fa fa-clone fa-fw"></i></a> ';
			}
			if ($modx->hasPermission($this->types[$resourceTable]['actions']['remove'][1])) {
				$output .= '<a onclick="return confirm(\'' . $_lang["confirm_delete_template"] . '\')" class="btn btn-xs btn-default" title="' . $_lang["delete_resource"] . '" href="index.php?a='.$this->types[$resourceTable]['actions']['remove'][0].'&amp;id=' . $row['id'] . '"><i class="fa fa-trash fa-fw"></i></a> ';
			}
			
			$output .= '</div></div>';
			/* end row buttons */
			
			$output .= '</li>';

			$preCat = $row['category'];
		}
		$output .= $insideUl ? '</ul>' : '';
		$output .= '</ul>';
		
		return $output;
	}
}