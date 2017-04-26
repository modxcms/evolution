<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

class mgrResources {
	var $types = array();
	var $items = array();
	var $categories = array();
	var $itemsPerCategory = array();
	
	function __construct() {
		$this->setTypes();
		$this->queryItemsFromDB();
		$this->prepareCategoryArrays();
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
		$this->types['site_modules']      = array(
			'title'=>$_lang["manage_modules"],
			'actions'=>array('edit'=>array(108,'edit_module'), 'duplicate'=>array(111,'new_module'), 'remove'=>array(110,'delete_module')),
			'permissions'=>array('new_module','edit_module'),
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

	function prepareCategoryArrays() {
		foreach($this->items as $type=>$items) {
			foreach((array)$items as $item) {
				$catid = $item['catid'] ? $item['catid'] : 0;
				$this->categories[$catid] = $item['category'];
				
				$item['type'] = $type;
				$this->itemsPerCategory[$catid][] = $item;
			}
		}
		
		// Sort categories by name
		natcasesort($this->categories);
		
		// Now sort by name
		foreach($this->itemsPerCategory as $catid=>$items) {
			usort($this->itemsPerCategory[$catid], function ($a, $b) {
				return strcasecmp($a['name'], $b['name']);
			});
		}
	}
}
