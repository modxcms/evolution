<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

function createResourceList($resourceTable,$action,$nameField = 'name') {
    global $modx, $_lang, $_style, $modx_textdir;
    
    $pluginsql = $resourceTable == 'site_plugins' ? $resourceTable.'.disabled, ' : '';
    
    $tvsql = '';
    $tvjoin = '';
    if($resourceTable == 'site_tmplvars') {
        $tvsql = 'site_tmplvars.caption, ';
        $tvjoin = sprintf('LEFT JOIN %s AS stt ON site_tmplvars.id=stt.tmplvarid GROUP BY site_tmplvars.id,reltpl', $modx->getFullTableName('site_tmplvar_templates'));
        $sttfield = 'IF(stt.templateid,1,0) AS reltpl,';
    }
    else $sttfield = '';
    
    //$orderby = $resourceTable == 'site_plugins' ? '6,2' : '5,1';

    switch($resourceTable) {
        case 'site_plugins':
            $orderby= '6,2'; break;
        case 'site_tmplvars':
            $orderby= '7,3'; break;
        case 'site_templates':
            $orderby= '6,1'; break;
        default:
            $orderby= '5,1';
    }

    $selectableTemplates = $resourceTable == 'site_templates' ? "{$resourceTable}.selectable, " : "";
    
    $rs = $modx->db->select(
        "{$sttfield} {$pluginsql} {$tvsql} {$resourceTable}.{$nameField} as name, {$resourceTable}.id, {$resourceTable}.description, {$resourceTable}.locked, {$selectableTemplates}IF(isnull(categories.category),'{$_lang['no_category']}',categories.category) as category, categories.id as catid",
        $modx->getFullTableName($resourceTable)." AS {$resourceTable}
            LEFT JOIN ".$modx->getFullTableName('categories')." AS categories ON {$resourceTable}.category = categories.id {$tvjoin}",
        "",
        $orderby
        );
    $limit = $modx->db->getRecordCount($rs);
    if($limit<1){
        echo $_lang['no_results'];
    } else {
    $output = '<ul id="'.$resourceTable.'">';
    $preCat = '';
    $insideUl = 0;
    while ($row = $modx->db->getRow($rs)) {
        $row['category'] = stripslashes($row['category']); //pixelchutes
        if ($preCat !== $row['category']) {
            $output .= $insideUl? '</ul>': '';
            $output .= '<li><strong>'.$row['category']. ($row['catid']!=''? ' <small>('.$row['catid'].')</small>' : '') .'</strong><ul>';
            $insideUl = 1;
        }

        if ($resourceTable == 'site_templates') {
            $class = $row['selectable'] ? '' : ' class="disabledPlugin"';
            $lockElementType = 1;
        }
        if ($resourceTable == 'site_tmplvars') {
            $class = $row['reltpl'] ? '' : ' class="disabledPlugin"';
            $lockElementType = 2;
        }
        if ($resourceTable == 'site_htmlsnippets') {
            $lockElementType = 3;
        }
        if ($resourceTable == 'site_snippets') {
            $lockElementType = 4;
        }
        if ($resourceTable == 'site_plugins') {
            $class = $row['disabled'] ? ' class="disabledPlugin"' : '';
            $lockElementType = 5;
        }        
        
        // Prepare displaying user-locks
        $lockedByUser = '';
        $rowLock = $modx->elementIsLocked($lockElementType, $row['id'], true);
        if($rowLock && $modx->hasPermission('display_locks')) {
            if($rowLock['sid'] == $modx->sid) {
                $title = $modx->parseText($_lang["lock_element_editing"], array('element_type'=>$_lang["lock_element_type_".$lockElementType],'lasthit_df'=>$rowLock['lasthit_df']));
                $lockedByUser = '<span title="'.$title.'" class="editResource" style="cursor:context-menu;"><img src="'.$_style['icons_preview_resource'].'" /></span>&nbsp;';
            } else {
                $title = $modx->parseText($_lang["lock_element_locked_by"], array('element_type'=>$_lang["lock_element_type_".$lockElementType], 'username'=>$rowLock['username'], 'lasthit_df'=>$rowLock['lasthit_df']));
                if($modx->hasPermission('remove_locks')) {
                    $lockedByUser = '<a href="#" onclick="unlockElement('.$lockElementType.', '.$row['id'].', this);return false;" title="'.$title.'" class="lockedResource"><img src="'.$_style['icons_secured'].'" /></a>';
                } else {
                    $lockedByUser = '<span title="'.$title.'" class="lockedResource" style="cursor:context-menu;"><img src="'.$_style['icons_secured'].'" /></span>';
                }
            }
        }
        
        $output .= '<li><span'.$class.'>'.$lockedByUser.'<a href="index.php?id='.$row['id'].'&amp;a='.$action.'">'.$row['name'].' <small>(' . $row['id'] . ')</small></a>'.($modx_textdir ? '&rlm;' : '').'</span>';
        
        if ($resourceTable == 'site_tmplvars') {
             $output .= !empty($row['description']) ? ' - '.$row['caption'].' &nbsp; <small>('.$row['description'].')</small>' : ' - '.$row['caption'];
        }else{
            $output .= !empty($row['description']) ? ' - '.$row['description'] : '' ;
        }

        $tplInfo  = array();
        if($row['locked']) $tplInfo[] = $_lang['locked'];
        if($row['id'] == $modx->config['default_template'] && $resourceTable == 'site_templates') $tplInfo[] = $_lang['defaulttemplate_title'];
        $output .= !empty($tplInfo) ? ' <em>('.join(', ', $tplInfo).')</em>' : '';

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
<script type="text/javascript" src="media/script/jquery.quicksearch.js"></script>
<script>
    function initQuicksearch(inputId, listId) {
        jQuery('#'+inputId).quicksearch('#'+listId+' ul li', {
            selector: 'a',
            'show': function () { jQuery(this).removeClass('hide'); },
            'hide': function () { jQuery(this).addClass('hide'); },
            'bind':'keyup',
            'onAfter': function() {
                jQuery('#'+listId).find('> li > ul').each( function() {
                    var parentLI = jQuery(this).closest('li');
                    var totalLI  = jQuery(this).children('li').length;
                    var hiddenLI = jQuery(this).children('li.hide').length;
                    if (hiddenLI == totalLI) { parentLI.addClass('hide'); }
                    else { parentLI.removeClass('hide'); }
                });
            }
        });
    }

    function unlockElement(type, id, domEl) {
    <?php
        // Prepare lang-strings
        $unlockTranslations = array('msg'=>$_lang["unlock_element_id_warning"],
                                    'type1'=>$_lang["lock_element_type_1"], 'type2'=>$_lang["lock_element_type_2"], 'type3'=>$_lang["lock_element_type_3"], 'type4'=>$_lang["lock_element_type_4"],
                                    'type5'=>$_lang["lock_element_type_5"], 'type6'=>$_lang["lock_element_type_6"], 'type7'=>$_lang["lock_element_type_7"], 'type8'=>$_lang["lock_element_type_8"]);
        foreach ($unlockTranslations as $key=>$value) $unlockTranslations[$key] = iconv($modx->config["modx_charset"], "utf-8", $value);
        ?>
        var trans = <?php echo json_encode($unlockTranslations); ?>;
        var msg = trans.msg.replace('[+id+]',id).replace('[+element_type+]',trans['type'+type]);
        if(confirm(msg)==true) {
            jQuery.get( 'index.php?a=67&type='+type+'&id='+id, function( data ) {
                if(data == 1) {
                    jQuery(domEl).fadeOut();
                }
                else alert( data );
            });
        }
    }
</script>

<h1 class="pagetitle">
  <span class="pagetitle-icon">
    <i class="fa fa-th"></i>
  </span>
  <span class="pagetitle-text">
    <?php echo $_lang['element_management']; ?>
  </span>
</h1>

<div class="sectionBody">
<div class="tab-pane" id="resourcesPane">

    <script type="text/javascript">
        tpResources = new WebFXTabPane( document.getElementById( "resourcesPane" ), true);
    </script>

<!-- Templates -->
<?php   if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template')) { ?>
    <div class="tab-page" id="tabTemplates">
        <h2 class="tab"><i class="fa fa-newspaper-o"></i> <?php echo $_lang["manage_templates"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabTemplates" ) );</script>
        <div id="template-info" style="display:none">
            <p class="element-edit-message"><?php echo $_lang['template_management_msg']; ?></p>
        </div>

        <ul class="actionButtons">
            <li>
              <form class="filterElements-form">
                <input class="form-control" type="text" placeholder="<?php echo $_lang['element_filter_msg']; ?>" id="site_templates_search">
              </form>
            </li>
            <li><a href="index.php?a=19"><?php echo $_lang['new_template']; ?></a></li>
            <li><a href="#" id="template-help"><?php echo $_lang['help']; ?></a></li>
        </ul>
        
        <?php echo createResourceList('site_templates',16,'templatename'); ?>
    
        <script>
          initQuicksearch('site_templates_search', 'site_templates');
          jQuery( "#template-help" ).click(function() {
             jQuery( '#template-info').toggle();
          });
        </script>
    </div>
<?php } ?>

<!-- Template variables -->
<?php   if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template')) { ?>
    <div class="tab-page" id="tabVariables">
        <h2 class="tab"><i class="fa fa-list-alt"></i> <?php echo $_lang["tmplvars"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabVariables" ) );</script>
        <!--//
            Modified By Raymond for Template Variables
            Added by Apodigm 09-06-2004- DocVars - web@apodigm.com
        -->
        <div id="tv-info" style="display:none">
            <p class="element-edit-message"><?php echo $_lang['tmplvars_management_msg']; ?></p>
        </div>
        
        <ul class="actionButtons">
            <li>
              <form class="filterElements-form">
                <input class="form-control" type="text" placeholder="<?php echo $_lang['element_filter_msg']; ?>" id="site_tmplvars_search">
              </form>
            </li>
            <li><a href="index.php?a=300"><?php echo $_lang['new_tmplvars']; ?></a></li>
            <li><a href="index.php?a=305"><?php echo $_lang['template_tv_edit']; ?></a></li>
            <li><a href="#" id="tv-help"><?php echo $_lang['help']; ?></a></li>
        </ul>
        
        <?php echo createResourceList('site_tmplvars',301); ?>
    
        <script>
          initQuicksearch('site_tmplvars_search', 'site_tmplvars');
          jQuery( "#tv-help" ).click(function() {
             jQuery( '#tv-info').toggle();
          });
        </script>
    </div>
<?php } ?>

<!-- chunks -->
<?php   if($modx->hasPermission('new_chunk') || $modx->hasPermission('edit_chunk')) { ?>
    <div class="tab-page" id="tabChunks">
        <h2 class="tab"><i class="fa fa-th-large"></i> <?php echo $_lang["manage_htmlsnippets"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabChunks" ) );</script>
        <div id="chunks-info" style="display:none">
            <p class="element-edit-message"><?php echo $_lang['htmlsnippet_management_msg']; ?></p>
        </div>

        <ul class="actionButtons">
            <li>
              <form class="filterElements-form">
                <input class="form-control" type="text" placeholder="<?php echo $_lang['element_filter_msg']; ?>" id="site_htmlsnippets_search">
              </form>
            </li>
            <li><a href="index.php?a=77"><?php echo $_lang['new_htmlsnippet']; ?></a></li>
            <li><a href="#" id="chunks-help"><?php echo $_lang['help']; ?></a></li>
        </ul>
        
        <?php echo createResourceList('site_htmlsnippets',78); ?>
    
        <script>
          initQuicksearch('site_htmlsnippets_search', 'site_htmlsnippets');
          jQuery( "#chunks-help" ).click(function() {
             jQuery( '#chunks-info').toggle();
          });
        </script>
    </div>
<?php } ?>

<!-- snippets -->
<?php   if($modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet')) { ?>
    <div class="tab-page" id="tabSnippets">
        <h2 class="tab"><i class="fa fa-code"></i> <?php echo $_lang["manage_snippets"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabSnippets" ) );</script>
        <div id="snippets-info" style="display:none">
            <p class="element-edit-message"><?php echo $_lang['snippet_management_msg']; ?></p>
        </div>

        <ul class="actionButtons">
            <li>
              <form class="filterElements-form">
                <input class="form-control" type="text" placeholder="<?php echo $_lang['element_filter_msg']; ?>" id="site_snippets_search">
              </form>
            </li>
            <li><a href="index.php?a=23"><?php echo $_lang['new_snippet']; ?></a></li>
            <li><a href="#" id="snippets-help"><?php echo $_lang['help']; ?></a></li>
        </ul>
        
        <?php echo createResourceList('site_snippets',22); ?>
    
        <script>
          initQuicksearch('site_snippets_search', 'site_snippets');
          jQuery( "#snippets-help" ).click(function() {
             jQuery( '#snippets-info').toggle();
          });
        </script>
    </div>
<?php } ?>

<!-- plugins -->
<?php   if($modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin')) { ?>
    <div class="tab-page" id="tabPlugins">
        <h2 class="tab"><i class="fa fa-plug"></i> <?php echo $_lang["manage_plugins"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabPlugins" ) );</script>
        <div id="plugins-info" style="display:none">
            <p class="element-edit-message"><?php echo $_lang['plugin_management_msg']; ?></p>
        </div>
    
        <ul class="actionButtons">
            <li>
              <form class="filterElements-form">
                <input class="form-control" type="text" placeholder="<?php echo $_lang['element_filter_msg']; ?>" id="site_plugins_search">
              </form>
            </li>
            <?php if($modx->hasPermission('new_plugin'))  { ?><li><a href="index.php?a=101"><?php echo $_lang['new_plugin']; ?></a></li><?php } ?>
            <?php if($modx->hasPermission('save_plugin')) { ?><li><a href="index.php?a=100"><?php echo $_lang['plugin_priority']; ?></a></li><?php } ?>
<?php   if($modx->hasPermission('delete_plugin') && $_SESSION['mgrRole'] == 1) {
            $tbl_site_plugins = $modx->getFullTableName('site_plugins');
            if ($modx->db->getRecordCount($modx->db->query("SELECT id FROM {$tbl_site_plugins} t1 WHERE disabled = 1 AND name IN (SELECT name FROM {$tbl_site_plugins} t2 WHERE t1.name = t2.name AND t1.id != t2.id)"))) { ?>
            <li><a href="index.php?a=119"><?php echo $_lang['purge_plugin']; ?></a></li>
<?php       }
        } ?>
            <li><a href="#" id="plugins-help"><?php echo $_lang['help']; ?></a></li>
        </ul>
        
        <?php echo createResourceList('site_plugins',102); ?>
    
        <script>
          initQuicksearch('site_plugins_search', 'site_plugins');
          jQuery( "#plugins-help" ).click(function() {
             jQuery( '#plugins-info').toggle();
          });
        </script>
    </div>
<?php } ?>

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
            <li><a href="#" id="category-help"><?php echo $_lang['help']; ?></a></li>
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
                        echo '<li><strong>'.$v['category']. ($v['catid']!='' ? ' <small>('.$v['catid'].')</small>' : '') .'</strong><ul>';
                    } else {
                        echo '<li><strong>'.$v['category']. ($v['catid']!='' ? ' <small>('.$v['catid'].')</small>' : '') .' - <a href="index.php?a=501&amp;catId='.$v['catid'].'">'.$_lang['delete'].'</a></strong><ul>';
                    }
                    $insideUl = 1;
                }
                $class = array_key_exists('disabled',$v) && $v['disabled'] ? ' class="disabledPlugin"' : '';
                if ($v['id']) {
        ?>
            <li class="el-<?php echo '' . $v['action'] . '';?>"><span<?php echo $class;?>><a href="index.php?id=<?php echo $v['id']. '&amp;a='.$v['action'];?>"><?php echo $v['name']; ?></a></span><?php echo ' (' . $v['type'] . ')'; echo !empty($v['description']) ? ' - '.$v['description'] : '' ; ?><?php echo $v['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ; ?></li>
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
            jQuery('.filterElements-form').keydown(function (e) {
            if (e.keyCode == 13) {
            e.preventDefault();
            }
          });
            jQuery( "#category-help" ).click(function() {
            jQuery( '#category-info').toggle();
            });
        </script>
    </div>
<?php
    if (is_numeric($_GET['tab'])) {
        echo '<script type="text/javascript"> tpResources.setSelectedIndex( '.$_GET['tab'].' );</script>';
    }
?>
</div>
</div>