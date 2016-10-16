//<?php
/**
 * ElementsInTree
 *
 * Get access to all Elements inside Manager sidebar
 *
 * @category    plugin
 * @version     1.1.1
 * @license     http://creativecommons.org/licenses/GPL/2.0/ GNU Public License (GPL v2)
 * @internal    @properties &useIcons=Use icons in tabs;list;yes,no;yes;desc;Icons available in MODX version 1.1.1 or newer
 * @internal    @events OnManagerTreePrerender,OnManagerTreeRender
 * @internal    @modx_category Manager and Admin
 * @internal    @installset base
 * @documentation Requirements: This plugin requires MODX Evolution 1.1.1 or later
 * @reportissues https://github.com/pmfx/ElementsInTree
 * @link        Original Github thread https://github.com/modxcms/evolution/issues/783
 * @author      Dmi3yy https://github.com/dmi3yy
 * @author      pmfx https://github.com/pmfx
 * @author      Nicola1971 https://github.com/Nicola1971
 * @lastupdate  16/10/2016
 */

$e = &$modx->Event;
if($e->name == 'OnManagerTreePrerender'){
	
		if ($useIcons=='yes') {
			$tabPadding = '10px';
		}
		else {
			$tabPadding = '9px';
		}
	
	$output = '
<style>
#treePane .tab-page ul {
  margin: 0;
  margin-bottom: 5px;
  padding: 0;
}

#treePane .tab-page ul li {
  list-style: none;
  padding-left: 10px;
}

#treePane .tab-page ul li li {
  list-style: none;
  padding-left: 5px;
  line-height: 1.6;
}

#treePane .tab-page ul li a {
  text-decoration: none;
}

#treePane .tab-page ul li a:hover {
  text-decoration: underline;
}

#treePane .tab {
  padding-left: 7px;
  padding-right: 7px;
}

#treePane .tab > span > .fa {
  margin-right: 2px;
  margin-left: 2px;
}

#treePane .tab.selected {
	padding-bottom: 6px;
}

#treePane .tab-row .tab span {
	font-size: 14px;
}

#tabDoc {
	 overflow: hidden;
}

#treePane .ext-ico {
	text-decoration:none!important;
	color:#97D19C!important;
}

#treePane ul > li > strong > a.catname
{
	color: #444;
}

#treePane .fade {
  opacity: 0;
  -webkit-transition: opacity .15s linear;
  -o-transition: opacity .15s linear;
  transition: opacity .15s linear;
}

#treePane .fade.in {
  opacity: 1;
}

#treePane .collapse {
  display: none;
}

#treePane .collapse.in {
  display: block;
}

#treePane tr.collapse.in {
  display: table-row;
}

#treePane tbody.collapse.in {
  display: table-row-group;
}

#treePane .collapsing {
  position: relative;
  height: 0;
  overflow: hidden;
  -webkit-transition-timing-function: ease;
       -o-transition-timing-function: ease;
          transition-timing-function: ease;
  -webkit-transition-duration: .35s;
       -o-transition-duration: .35s;
          transition-duration: .35s;
  -webkit-transition-property: height;
  -o-transition-property: height;
  transition-property: height;
}

#treePane .panel-title a{
	display: block;
	padding: 4px 0 4px 15px;
	color: #657587;
	font-weight: bold;
}

#treePane .panel-title > a::before {
	content: "\f107"; /* fa-angle-down */
  font-family: "FontAwesome";
  position: absolute;
  left: 15px;
}
#treePane .panel-title > a[aria-expanded="false"]::before {
  content: "\f105"; /* fa-angle-right */
}
#treePane .panel-title > a[aria-expanded="true"] {
	color: #657587;
}

#treePane li.eltree {
  margin-left: 5px;
	line-height: 1.4em;
}

#treePane li.eltree:before {
  font-family: FontAwesome;
  padding:0 5px 0 0;
  margin-right:2px;
  color: #657587;
}

#tabTemp li.eltree:before {content: "\f1ea";}
#tabCH   li.eltree:before {content: "\f009";}
#tabSN   li.eltree:before {content: "\f121";}
#tabTV   li.eltree:before {content: "\f022";}
#tabPL   li.eltree:before {content: "\f1e6";}
#tabMD   li.eltree:before {content: "\f085";}
</style>

<div class="tab-pane" id="treePane" style="border:0;">
<script type="text/javascript" src="media/script/tabpane.js"></script>
<script src="media/script/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="media/script/jquery.quicksearch.js"></script>
<script type="text/javascript">
treePane = new WebFXTabPane(document.getElementById( "treePane" ),true);
</script>
<div class="tab-page" id="tabDoc" style="padding-left:0; padding-right:0;">
<h2 class="tab">Site Tree</h2>
<script type="text/javascript">treePane.addTabPage( document.getElementById( "tabDoc" ) );</script>
';
	$e->output($output);
}

if ( $modx->hasPermission('edit_template') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('exec_module') ) {
	if($e->name == 'OnManagerTreeRender'){
		
		if ($useIcons=='yes') {
			$tabLabel_template  = '<i class="fa fa-newspaper-o"></i>';
			$tabLabel_tv        = '<i class="fa fa-list-alt"></i>';
			$tabLabel_chunk     = '<i class="fa fa-th-large"></i>';
			$tabLabel_snippet   = '<i class="fa fa-code"></i>';
			$tabLabel_plugin    = '<i class="fa fa-plug"></i>';
			$tabLabel_module    = '<i class="fa fa-cogs"></i>';
			$tabLabel_refresh   = '<i class="fa fa-refresh"></i>';
		}
		else {
			$tabLabel_template  = 'TPL';
			$tabLabel_tv        = 'TV';
			$tabLabel_chunk     = 'CH';
			$tabLabel_snippet   = 'SN';
			$tabLabel_plugin    = 'PL';
			$tabLabel_module    = 'MD';
			$tabLabel_refresh   = 'Refresh';
		}
		
		//global $modx;
		$tablePre = $modx->db->config['dbase'] . '.`' . $modx->db->config['table_prefix'];
		function createResourceList($resourceTable,$action,$tablePre,$nameField = 'name') {
			global $modx, $_lang;
			$output  = '
				<form class="filterElements-form" style="margin-top: 0;">
				  <input class="form-control" type="text" placeholder="Type here to filter list" id="tree_'.$resourceTable.'_search">
				</form>';
			$output .= '<div class="panel-group"><div class="panel panel-default" id="tree_'.$resourceTable.'">';
			$pluginsql = $resourceTable == 'site_plugins' ? $tablePre.$resourceTable.'`.disabled, ' : '';
			$tvsql = $resourceTable == 'site_tmplvars' ? $tablePre.$resourceTable.'`.caption, ' : '';
			//$orderby = $resourceTable == 'site_plugins' ? '6,2' : '5,1';

			if ($resourceTable == 'site_plugins' || $resourceTable == 'site_tmplvars') {
				$orderby= '6,2';
			}
			else{
				$orderby= '5,1';
			}

			$sql = 'SELECT '.$pluginsql.$tvsql.$tablePre.$resourceTable.'`.'.$nameField.' as name, '.$tablePre.$resourceTable.'`.id, '.$tablePre.$resourceTable.'`.description, '.$tablePre.$resourceTable.'`.locked, if(isnull('.$tablePre.'categories`.category),\''.$_lang['no_category'].'\','.$tablePre.'categories`.category) as category, '.$tablePre.'categories`.id  as catid FROM '.$tablePre.$resourceTable.'` left join '.$tablePre.'categories` on '.$tablePre.$resourceTable.'`.category = '.$tablePre.'categories`.id ORDER BY '.$orderby;
			$rs = $modx->db->query($sql);
			$limit = $modx->db->getRecordCount($rs);
			if($limit<1){
				echo $_lang['no_results'];
			}
			$preCat = '';
			$insideUl = 0;
			for($i=0; $i<$limit; $i++) {
				$row = $modx->db->getRow($rs);
				$row['category'] = stripslashes($row['category']); //pixelchutes
				if ($preCat !== $row['category']) {
					$output .= $insideUl? '</div>': '';
					$output .= '<div class="panel-heading"><span class="panel-title"><a class="accordion-toggle" href="#collapse'.$resourceTable.$row['catid'].'" data-toggle="collapse" data-parent="#accordion"> '.$row['category'].'</a></span></div><div class="panel-collapse in '.$resourceTable.'"  id="collapse'.$resourceTable.$row['catid'].'"><ul>';
					$insideUl = 1;
				}
				if ($resourceTable == 'site_plugins') $class = $row['disabled'] ? ' class="disabledPlugin"' : '';
				$output .= '<li class="eltree"><span'.$class.'><a href="index.php?id='.$row['id'].'&amp;a='.$action.'" target="main"><span class="elementname">'.$row['name'].'</span><small> (' . $row['id'] . ')</small></a>
<a class="ext-ico" href="#" title="Open in new window" onclick="window.open(\'index.php?id='.$row['id'].'&a='.$action.'\',\'gener\',\'width=800,height=600,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')"> <small><i class="fa fa-external-link" aria-hidden="true"></i></small></a>'.($modx_textdir ? '&rlm;' : '').'</span>';
				/*
//@TODO: add description as title to link
if ($resourceTable == 'site_tmplvars') {
$output .= !empty($row['description']) ? ' - '.$row['caption'].' &nbsp; <small> ('.$row['description'].')</small>' : ' - '.$row['caption'];
}else{
$output .= !empty($row['description']) ? ' - '.$row['description'] : '' ;
}*/
				$output .= $row['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ;
				$output .= '</li>';
				$preCat = $row['category'];
			}
			$output .= $insideUl? '</ul></div></div>': '';
			$output .= '</div>';
			$output .= '
    
        <script>
          jQuery(\'#tree_'.$resourceTable.'_search\').quicksearch(\'#tree_'.$resourceTable.' ul li\', {
            selector: \'.elementname\'
          });
		  jQuery(\'#tree_'.$resourceTable.'_search\').on(\'focus\', function () {
    jQuery(\'.'.$resourceTable.'\').collapse(\'show\');
});
        </script>';
			return $output;
		}
		$temp = createResourceList('site_templates',16,$tablePre,'templatename');
		$tv = createResourceList('site_tmplvars',301,$tablePre);
		$chunk = createResourceList('site_htmlsnippets',78,$tablePre);
		$snippet = createResourceList('site_snippets',22,$tablePre);
		$plugin = createResourceList('site_plugins',102,$tablePre);
		$module = createResourceList('site_modules',112,$tablePre);

		if ( $modx->hasPermission('edit_template') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('exec_module') ) {
			$output = '</div>';
		}

		if ($modx->hasPermission('edit_template')) {
			$output .= '
	<div class="tab-page" id="tabTemp" style="padding-left:0; padding-right:0;">
	<h2 class="tab" title="Templates">'.$tabLabel_template.'</h2>
	<script type="text/javascript">treePane.addTabPage( document.getElementById( "tabTemp" ) );</script>
	'.$temp.'
	<br/>
	<ul class="actionButtons">
	<li><a href="index.php?a=19" target="main">New Template</a></li>
	<li><a href="javascript:location.reload();" title="Click here if element was added or deleted to refresh the list.">'.$tabLabel_refresh.'</a></li>
	</ul>
	</div>
	<div class="tab-page" id="tabTV" style="padding-left:0; padding-right:0;">
	<h2 class="tab" title="Template Variables">'.$tabLabel_tv.'</h2>
	<script type="text/javascript">treePane.addTabPage( document.getElementById( "tabTV" ) );</script>
	'.$tv.'
	<br/>
	<ul class="actionButtons">
	<li><a href="index.php?a=300" target="main">New TV</a></li>
	<li><a href="javascript:location.reload();" title="Click here if element was added or deleted to refresh the list.">'.$tabLabel_refresh.'</a></li>
	</ul>
	</div>
	';
		}

		if ($modx->hasPermission('edit_chunk')) {
			$output .= '
<div class="tab-page" id="tabCH" style="padding-left:0; padding-right:0;">
<h2 class="tab" title="Chunks">'.$tabLabel_chunk.'</h2>
<script type="text/javascript">treePane.addTabPage( document.getElementById( "tabCH" ) );</script>
'.$chunk.'
<br/>
<ul class="actionButtons">
<li><a href="index.php?a=77" target="main">New Chunk</a></li>
<li><a href="javascript:location.reload();" title="Click here if element was added or deleted to refresh the list.">'.$tabLabel_refresh.'</a></li>
</ul>
</div>
	';
		}

		if ($modx->hasPermission('edit_snippet')) {
			$output .= '
<div class="tab-page" id="tabSN" style="padding-left:0; padding-right:0;">
<h2 class="tab" title="Snippets">'.$tabLabel_snippet.'</h2>
<script type="text/javascript">treePane.addTabPage( document.getElementById( "tabSN" ) );</script>
'.$snippet.'
<br/>
<ul class="actionButtons">
<li><a href="index.php?a=23" target="main">New Snippet</a></li>
<li><a href="javascript:location.reload();" title="Click here if element was added or deleted to refresh the list.">'.$tabLabel_refresh.'</a></li>
</ul>
</div>
	';
		}

		if ($modx->hasPermission('edit_plugin')) {
			$output .= '
<div class="tab-page" id="tabPL" style="padding-left:0; padding-right:0;">
<h2 class="tab" title="Plugins">'.$tabLabel_plugin.'</h2>
<script type="text/javascript">treePane.addTabPage( document.getElementById( "tabPL" ) );</script>
'.$plugin.'
<br/>
<ul class="actionButtons">
<li><a href="index.php?a=101" target="main">New Plugin</a></li>
<li><a href="javascript:location.reload();" title="Click here if element was enabled/disabled/added/deleted to refresh the list.">'.$tabLabel_refresh.'</a></li>
</ul>
</div>
	';
		}
		
		if ($modx->hasPermission('exec_module')) {
			$output .= '
<div class="tab-page" id="tabMD" style="padding-left:0; padding-right:0;">
<h2 class="tab" title="Modules">'.$tabLabel_module.'</h2>
<script type="text/javascript">treePane.addTabPage( document.getElementById( "tabMD" ) );</script>
'.$module.'
<br/>
<ul class="actionButtons">
<li><a href="index.php?a=107" target="main">New Module</a></li>
<li><a href="javascript:location.reload();" title="Click here if element was enabled/disabled/added/deleted to refresh the list.">'.$tabLabel_refresh.'</a></li>
</ul>
</div>
	';
		}

        if ($modx->hasPermission('edit_template') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('exec_module') ) {
			$output .= '</div>';
			$e->output($output);
		}

	}
}