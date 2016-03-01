<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

function createResourceList($resourceTable,$action,$nameField = 'name') {
    global $modx, $_lang, $modx_textdir;
    
    $pluginsql = $resourceTable == 'site_plugins' ? $resourceTable.'.disabled, ' : '';
    $tvsql = $resourceTable == 'site_tmplvars' ? $resourceTable.'.caption, ' : '';
    
    //$orderby = $resourceTable == 'site_plugins' ? '6,2' : '5,1';

    if ($resourceTable == 'site_plugins' || $resourceTable == 'site_tmplvars') {
        $orderby= '6,2';
    }else{
        $orderby= '5,1';
    }

    $selectableTemplates = $resourceTable == 'site_templates' ? "{$resourceTable}.selectable, " : "";
    
    $rs = $modx->db->select(
        "{$pluginsql} {$tvsql} {$resourceTable}.{$nameField} as name, {$resourceTable}.id, {$resourceTable}.description, {$resourceTable}.locked, {$selectableTemplates}IF(isnull(categories.category),'{$_lang['no_category']}',categories.category) as category",
        $modx->getFullTableName($resourceTable)." AS {$resourceTable}
            LEFT JOIN ".$modx->getFullTableName('categories')." AS categories ON {$resourceTable}.category = categories.id",
        "",
        $orderby
        );
	$limit = $modx->db->getRecordCount($rs);
    if($limit<1){
        echo $_lang['no_results'];
    } else {
    $output = '<ul>';
    $preCat = '';
    $insideUl = 0;
    while ($row = $modx->db->getRow($rs)) {
        $row['category'] = stripslashes($row['category']); //pixelchutes
        if ($preCat !== $row['category']) {
            $output .= $insideUl? '</ul>': '';
            $output .= '<li><strong>'.$row['category'].'</strong><ul>';
            $insideUl = 1;
        }

        if ($resourceTable == 'site_plugins') $class = $row['disabled'] ? ' class="disabledPlugin"' : '';
        if ($resourceTable == 'site_templates') $class = $row['selectable'] ? '' : ' class="disabledPlugin"';
        $output .= '<li><span'.$class.'><a href="index.php?id='.$row['id'].'&amp;a='.$action.'">'.$row['name'].' <small>(' . $row['id'] . ')</small></a>'.($modx_textdir ? '&rlm;' : '').'</span>';
        
        if ($resourceTable == 'site_tmplvars') {
             $output .= !empty($row['description']) ? ' - '.$row['caption'].' &nbsp; <small>  ('.$row['description'].')</small>' : ' - '.$row['caption'];
        }else{
            $output .= !empty($row['description']) ? ' - '.$row['description'] : '' ;
        }
        $output .= $row['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ;
        $output .= '</li>';

        $preCat = $row['category'];
    }
    $output .= $insideUl? '</ul>': '';
    $output .= '</ul>';
}
    return $output;
}

?>

<script type="text/javascript" src="media/script/tabpane.js"></script>

<h1><?php echo $_lang['element_management']; ?></h1>

<div class="sectionBody">
<div class="tab-pane" id="resourcesPane">

    <script type="text/javascript">
        tpResources = new WebFXTabPane( document.getElementById( "resourcesPane" ), true);
    </script>

<!-- Templates -->
<?php   if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template')) { ?>
    <div class="tab-page" id="tabTemplates">
        <h2 class="tab"><?php echo $_lang["manage_templates"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabTemplates" ) );</script>
        <p><?php echo $_lang['template_management_msg']; ?></p>

		<ul class="actionButtons">
            <li><a href="index.php?a=19"><?php echo $_lang['new_template']; ?></a></li>
        </ul>
        <?php echo createResourceList('site_templates',16,'templatename'); ?>
    </div>
<?php } ?>

<!-- Template variables -->
<?php   if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template')) { ?>
    <div class="tab-page" id="tabVariables">
        <h2 class="tab"><?php echo $_lang["tmplvars"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabVariables" ) );</script>
        <!--//
            Modified By Raymond for Template Variables
            Added by Apodigm 09-06-2004- DocVars - web@apodigm.com
        -->
        <p><?php echo $_lang['tmplvars_management_msg']; ?></p>
			<ul class="actionButtons">
                <li><a href="index.php?a=300"><?php echo $_lang['new_tmplvars']; ?></a></li>
            </ul>
            <?php echo createResourceList('site_tmplvars',301); ?>
    </div>
<?php } ?>

<!-- chunks -->
<?php   if($modx->hasPermission('new_chunk') || $modx->hasPermission('edit_chunk')) { ?>
    <div class="tab-page" id="tabChunks">
        <h2 class="tab"><?php echo $_lang["manage_htmlsnippets"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabChunks" ) );</script>
        <p><?php echo $_lang['htmlsnippet_management_msg']; ?></p>

		<ul class="actionButtons">
            <li><a href="index.php?a=77"><?php echo $_lang['new_htmlsnippet']; ?></a></li>
        </ul>
        <?php echo createResourceList('site_htmlsnippets',78); ?>
    </div>
<?php } ?>

<!-- snippets -->
<?php   if($modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet')) { ?>
    <div class="tab-page" id="tabSnippets">
        <h2 class="tab"><?php echo $_lang["manage_snippets"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabSnippets" ) );</script>
        <p><?php echo $_lang['snippet_management_msg']; ?></p>

		<ul class="actionButtons">
            <li><a href="index.php?a=23"><?php echo $_lang['new_snippet']; ?></a></li>
        </ul>
        <?php echo createResourceList('site_snippets',22); ?>
    </div>
<?php } ?>

<!-- plugins -->
<?php   if($modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin')) { ?>
    <div class="tab-page" id="tabPlugins">
        <h2 class="tab"><?php echo $_lang["manage_plugins"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabPlugins" ) );</script>
        <p><?php echo $_lang['plugin_management_msg']; ?></p>

		<ul class="actionButtons">
            <?php if($modx->hasPermission('new_plugin'))  { ?><li><a href="index.php?a=101"><?php echo $_lang['new_plugin']; ?></a></li><?php } ?>
            <?php if($modx->hasPermission('save_plugin')) { ?><li><a href="index.php?a=100"><?php echo $_lang['plugin_priority']; ?></a></li><?php } ?>
<?php   if($modx->hasPermission('delete_plugin') && $_SESSION['mgrRole'] == 1) {
            $tbl_site_plugins = $modx->getFullTableName('site_plugins');
            if ($modx->db->getRecordCount($modx->db->query("SELECT id FROM {$tbl_site_plugins} t1 WHERE disabled = 1 AND name IN (SELECT name FROM {$tbl_site_plugins} t2 WHERE t1.name = t2.name AND t1.id != t2.id)"))) { ?>
            <li><a href="index.php?a=119"><?php echo $_lang['purge_plugin']; ?></a></li>
<?php       }
        } ?>
        </ul>
        <?php echo createResourceList('site_plugins',102); ?>
    </div>
<?php } ?>

<!-- category view -->
    <div class="tab-page" id="tabCategory">
        <h2 class="tab"><?php echo $_lang["element_categories"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabCategory" ) );</script>
        <p><?php echo $_lang['category_msg']; ?></p>
        <br />
        <ul>
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
                    "{$pluginsql} {$nameField} as name, {$v['table']}.id, description, locked, categories.category, categories.id as catid",
                    $modx->getFullTableName($v['table'])." AS {$v['table']}
                        RIGHT JOIN ".$modx->getFullTableName('categories')." AS categories ON {$v['table']}.category = categories.id",
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
                        echo '<li><strong>'.$v['category'].'</strong><ul>';
                    } else {
                        echo '<li><strong>'.$v['category'].'</strong> (<a href="index.php?a=501&amp;catId='.$v['catid'].'">'.$_lang['delete'].'</a>)<ul>';
                    }
                    $insideUl = 1;
                }
                $class = array_key_exists('disabled',$v) && $v['disabled'] ? ' class="disabledPlugin"' : '';
                if ($v['id']) {
        ?>
            <li><span<?php echo $class;?>><a href="index.php?id=<?php echo $v['id']. '&amp;a='.$v['action'];?>"><?php echo $v['name']; ?></a></span><?php echo ' (' . $v['type'] . ')'; echo !empty($v['description']) ? ' - '.$v['description'] : '' ; ?><?php echo $v['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ; ?></li>
        <?php
                }
            $preCat = $v['category'];
            }
            echo $insideUl? '</ul>': '';
        ?>
        <?php
        }
        ?>
        </ul>
    </div>
<?php
    if (is_numeric($_GET['tab'])) {
        echo '<script type="text/javascript"> tpResources.setSelectedIndex( '.$_GET['tab'].' );</script>';
    }
?>
</div>
</div>
