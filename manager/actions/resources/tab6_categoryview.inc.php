<?php if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly."); ?>

<!-- category view -->
<div class="tab-page" id="tabCategory">
	<h2 class="tab"><?php echo $_lang["element_categories"] ?></h2>
	<script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabCategory" ) );</script>

	<div id="category-info" style="display:none">
		<p class="element-edit-message"><?php echo $_lang['category_msg']; ?></p>
	</div>
	<ul class="actionButtons">
		<li>
			<form class="filterElements-form">
				<input class="form-control" type="text" placeholder="<?php echo $_lang['element_filter_msg']; ?>" id="categories_list_search">
			</form>
		</li>
		<li><a href="index.php?a=120"><?php echo $_lang['manage_categories']; ?></a></li>
		<li><a href="#" id="category-help"><?php echo $_lang['help']; ?></a></li>
		<!--- <label><input type="checkbox" id="catcheckHideButtons" value="buttons" checked="checked"> Buttons</label>-->
		<label><input type="checkbox" id="catcheckHideDescription" value="description" checked="checked"> Description</label>
		<form>
			<label><input type="radio" name="layout" class="catcheckinline" id="catcheckinlist" value="list" checked="checked"> List</label>
			<label><input type="radio" name="layout" class="catcheckinline" id="catcheckinline" value="inline"> Inline</label>
		</form>
	</ul>

	<div id="categories_list">
		<?php
		$displayInfo = array();
		$hasPermission = 0;
		if($modx->hasPermission('edit_plugin') || $modx->hasPermission('new_plugin')) {
			$displayInfo['plugin'] = array('table'=>'site_plugins','action'=>102,'name'=>$_lang['manage_plugins']);
			$hasPermission = 1;
		}
		if($modx->hasPermission('edit_snippet') || $modx->hasPermission('new_snippet')) {
			$displayInfo['snippet'] = array('table'=>'site_snippets','action'=>22,'name'=>$_lang['manage_snippets']);
			$hasPermission = 1;
		}
		if($modx->hasPermission('edit_chunk') || $modx->hasPermission('new_chunk')) {
			$displayInfo['htmlsnippet'] = array('table'=>'site_htmlsnippets','action'=>78,'name'=>$_lang['manage_htmlsnippets']);
			$hasPermission = 1;
		}
		if($modx->hasPermission('edit_template') || $modx->hasPermission('new_template')) {
			$displayInfo['templates'] = array('table'=>'site_templates','action'=>16,'name'=>$_lang['manage_templates']);
			$displayInfo['tmplvars'] = array('table'=>'site_tmplvars','action'=>301,'name'=>$_lang['tmplvars']);
			$hasPermission = 1;
		}
		if($modx->hasPermission('edit_module') || $modx->hasPermission('new_module')) {
			$displayInfo['modules'] = array('table'=>'site_modules','action'=>108,'name'=>$_lang['modules']);
			$hasPermission = 1;
		}

		//Category Delete permission check
		$delPerm = 0;
		if($modx->hasPermission('save_plugin') ||
			$modx->hasPermission('save_snippet') ||
			$modx->hasPermission('save_chunk') ||
			$modx->hasPermission('save_template') ||
			$modx->hasPermission('save_module')) {
			$delPerm = 1;
		}

		if($hasPermission) {
			$finalInfo = array();

			foreach ($displayInfo as $n => $v) {
				$nameField = ($v['table'] == 'site_templates')? 'templatename': 'name';
				$pluginsql = $v['table'] == 'site_plugins' ? $v['table'].'.disabled, ' : '';
				$rs = $modx->db->select(
					"{$pluginsql} {$nameField} as name, {$v['table']}.id, description, locked, IF(isnull(categories.category), '{$_lang['no_category']}',categories.category) as category, categories.id as catid",
					$modx->getFullTableName($v['table'])." AS {$v['table']}
                        LEFT JOIN ".$modx->getFullTableName('categories')." AS categories ON {$v['table']}.category = categories.id",
					"",
					"5,1"
				);
				while ($row = $modx->db->getRow($rs)) {
					$row['type'] = $v['name'];
					$row['action'] = $v['action'];
					if (empty($row['category'])) {$row['category'] = $_lang['no_category'];}
					$finalInfo[] = $row;
				}
			}

			foreach($finalInfo as $n => $v) {
				$category[$n] = $v['category'];
				$name[$n] = $v['name'];
			}

			$category_lowercase = array_map('strtolower', $category);
			$name_lowercase = array_map('strtolower', $name);
			array_multisort($category_lowercase, SORT_ASC, SORT_STRING, $name_lowercase, SORT_ASC, SORT_STRING, $finalInfo);

			echo '<ul>';
			$preCat = '';
			$insideUl = 0;
			foreach($finalInfo as $n => $v) {
				if ($preCat !== $v['category']) {
					echo $insideUl? '</ul>': '';
					if ($v['category'] == $_lang['no_category'] || !$delPerm) {
						echo '<li><span class="category_name"><strong>'.$v['category']. ($v['catid']!='' ? ' <small>('.$v['catid'].')</small>' : '') .'</strong></span><ul>';
					} else {
						echo '<li><span class="category_name"><strong>'.$v['category']. ($v['catid']!='' ? ' <small>('.$v['catid'].')</small>' : '') .' - <a class="btn btn-xs btn-default" onclick="return confirm(\''.$_lang["confirm_delete_category"].'\')" href="index.php?a=501&amp;catId='.$v['catid'].'"><i class="fa fa-trash"></i> '.$_lang['delete'].'</a></strong></span><ul>';
					}
					$insideUl = 1;
				}
				$class = array_key_exists('disabled',$v) && $v['disabled'] ? ' class="disabledPlugin"' : '';
				if ($v['id']) {
					?>
					<li class="el-<?php echo '' . $v['action'] . '';?>"><span<?php echo $class;?>><a href="index.php?id=<?php echo $v['id']. '&amp;a='.$v['action'];?>"><?php echo $v['name']; ?></a></span><?php echo ' (' . $v['type'] . ')'; echo !empty($v['description']) ? ' <span class="elements_descr">- '.$v['description'] : '</span>' ; ?><?php echo $v['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ; ?></li>
					<?php
				}
				$preCat = $v['category'];
			}
			echo $insideUl? '</ul>': '';
			?>
			<?php
		}
		?>
	</div>
	<script>
        initQuicksearch('categories_list_search', 'categories_list ul');
        initViews('cat', 'category');
	</script>
</div>