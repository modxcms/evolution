//<?php
/**
 * ElementsInTree
 *
 * Get access to all Elements and Modules inside Manager sidebar
 *
 * @category    plugin
 * @version     1.3.2
 * @license     http://creativecommons.org/licenses/GPL/2.0/ GNU Public License (GPL v2)
 * @internal    @properties &tabTreeTitle=Tree Tab Title;text;Site Tree;;Custom title of Site Tree tab. &useIcons=Use icons in tabs;list;yes,no;yes;;Icons available in MODX version 1.2 or newer. &treeButtonsInTab=Tree Buttons in tab;list;yes,no;yes;;Move Tree Buttons into Site Tree tab. &unifyFrames=Unify Frames;list;yes,no;yes;;Unify Tree and Main frame style. Right now supports MODxRE2 theme only.
 * @internal    @events OnManagerTreePrerender,OnManagerTreeRender,OnManagerMainFrameHeaderHTMLBlock,OnTempFormSave,OnTVFormSave,OnChunkFormSave,OnSnipFormSave,OnPluginFormSave,OnModFormSave,OnTempFormDelete,OnTVFormDelete,OnChunkFormDelete,OnSnipFormDelete,OnPluginFormDelete,OnModFormDelete
 * @internal    @modx_category Manager and Admin
 * @internal    @installset base
 * @documentation Requirements: This plugin requires MODX Evolution 1.2 or later
 * @reportissues https://github.com/pmfx/ElementsInTree
 * @link        Original Github thread https://github.com/modxcms/evolution/issues/783
 * @author      Dmi3yy https://github.com/dmi3yy
 * @author      pmfx https://github.com/pmfx
 * @author      Nicola1971 https://github.com/Nicola1971
 * @author      Deesen https://github.com/Deesen
 * @lastupdate  31/10/2016
 */

global $_lang;

$e = &$modx->Event;

if(!isset($_SESSION['elementsInTree'])) $_SESSION['elementsInTree'] = array();

// Set reloadTree = true for this events
if( in_array($e->name, array(
		'OnTempFormSave',
		'OnTVFormSave',
		'OnChunkFormSave',
		'OnSnipFormSave',
		'OnPluginFormSave',
		'OnModFormSave',

		'OnTempFormDelete',
		'OnTVFormDelete',
		'OnChunkFormDelete',
		'OnSnipFormDelete',
		'OnPluginFormDelete',
		'OnModFormDelete',

	)) || $_GET["r"] == 2) {
	$_SESSION['elementsInTree']['reloadTree'] = true;
}

// Trigger reloading tree for relevant actions when reloadTree = true
if ( $e->name == "OnManagerMainFrameHeaderHTMLBlock" ) {
	$relevantActions = array(16,19,23,300,301,77,78,22,101,102,108,76,106,107);
	if(in_array($_GET['a'],$relevantActions) && $_SESSION['elementsInTree']['reloadTree'] == true) {
		$_SESSION['elementsInTree']['reloadTree'] = false;
		$html  = "<!-- elementsInTree Start -->\n";
		$html .= "<script>";
		$html .= "jQuery(document).ready(function() {";
		$html .= "top.tree.location.reload();";
		$html .= "})\n";
		$html .= "</script>\n";
		$html .= "<!-- elementsInTree End -->\n";
		$e->output($html);
	};
}

// Main elementsInTree-part
if ($e->name == 'OnManagerTreePrerender') {
	
	// use icons
	if ($useIcons == 'yes') {
		$tabPadding = '10px';
	}
	else {
		$tabPadding = '9px';
	}
	
	// unify frames
	if ($unifyFrames == 'yes') {
		$unifyFrames_css = '
			body,
			div.treeframebody {
				background-color: #f2f2f2 !important;
			}

			div.treeframebody {
				background-color: transparent !important;
				-webkit-box-shadow: none !important;
				box-shadow: none !important;
			}

			#treeMenu {
				background-color: transparent !important;
				border-bottom-color: transparent !important;
			}
	  ';
	}
	
	// tree buttons in tab
	if ($treeButtonsInTab == 'yes') {
       
        $treeButtonsInTab_js  = '
          jQuery("#treeMenu").detach().prependTo("#tabDoc");
          jQuery("#treeMenu").addClass("is-intab");
          parent.tree.resizeTree();
        ';
        
        $treeButtonsInTab_css = '
      #treeHolder {
        padding-top: 10px;
        padding-left: 10px;
      }
      
      #treeMenu {
        display: none;
        margin-left: 0;
        margin-bottom: 6px;
        background-color: transparent !important;
        border-bottom-width: 0;
      }

      #treeMenu.is-intab {
        display: table;
      }

      .treeButton,
      .treeButtonDisabled {
        padding: 2px 3px;
      }

      #tabDoc {
        padding-top: 11px !important;
        padding-left: 13px !important;
        padding-right: 13px !important;
      }
      
      #floater {
        width: 99%;
        top: 94px;
      }
	  ';
	}
	
	// start main output
	$output = '
		<style>
		#tabDoc {
			overflow: hidden;
		}
		
		#tabDoc::before {
			position: absolute;
			content: "";
			right: 0;
			top: 0;
			bottom: 0;
			width: 30px;
			background: -moz-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 90%, rgba(255,255,255,1) 100%);
			background: -webkit-linear-gradient(left, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 90%,rgba(255,255,255,1) 100%);
			background: linear-gradient(to right, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 90%,rgba(255,255,255,1) 100%);
		}

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

		#treePane.no-transition .collapsing {
			-webkit-transition: none;
			-o-transition: none;
    		transition: none;
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
		#treePane .panel-title > a.collapsed::before {
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
		
		.no-events { pointer-events: none; }
		
		'.$unifyFrames_css.'
		'.$treeButtonsInTab_css.'
		
		</style>

		<div class="tab-pane no-transition" id="treePane" style="border:0;">
		<script type="text/javascript" src="media/script/tabpane.js"></script>
		<script src="media/script/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="media/script/jquery.quicksearch.js"></script>
        <script>
            function initQuicksearch(inputId, listId) {
                jQuery("#"+inputId).quicksearch("#"+listId+" ul li", {
                    selector: ".elementname",
                    "show": function () { jQuery(this).removeClass("hide"); },
                    "hide": function () { jQuery(this).addClass("hide"); },
                    "bind":"keyup",
                    "onAfter": function() {
                        jQuery("#"+listId).find(".panel-collapse").each( function() {
                            var parentLI = jQuery(this);
                            var totalLI  = jQuery(this).find("li").length;
                            var hiddenLI = jQuery(this).find("li.hide").length;
                            if (hiddenLI == totalLI) { parentLI.prev(".panel-heading").addClass("hide"); }
                            else { parentLI.prev(".panel-heading").removeClass("hide"); }
                        });
                    }
                });
				jQuery(".filterElements-form").keydown(function (e) {
					if (e.keyCode == 13) {
						e.preventDefault();
					}
				});
            }
            
            var storageKey = "MODX_elementsInTreeParams";
            
            // localStorage reset :
            // localStorage.removeItem(storageKey);
            
			// Prepare remember collapsed categories function
	        var storage = localStorage.getItem(storageKey);
	        var elementsInTreeParams = {};
	        var searchFieldCache = {};

			try {
	            if(storage != null) {
	                try {
						elementsInTreeParams = JSON.parse( storage );
	                } catch(err) {
	                    console.log(err);
	                    elementsInTreeParams = { "cat_collapsed": {} };
	                }
	            } else {
                    elementsInTreeParams = { "cat_collapsed": {} };
                }
                
                // Remember collapsed categories functions
                function setRememberCollapsedCategories(obj=null) {
                    obj = obj == null ? elementsInTreeParams.cat_collapsed : obj;
					for (var type in obj) {
						if (!elementsInTreeParams.cat_collapsed.hasOwnProperty(type)) continue;
						for (var category in elementsInTreeParams.cat_collapsed[type]) {
							if (!elementsInTreeParams.cat_collapsed[type].hasOwnProperty(category)) continue;
							state = elementsInTreeParams.cat_collapsed[type][category];
							if(state == null) continue;
							var collapseItem = jQuery("#collapse" + type + category);
							var toggleItem = jQuery("#toggle" + type + category);
							if(state == 0) {
								// Collapsed
								collapseItem.collapse("hide");
								toggleItem.addClass("collapsed");
							} else {
								// Open
								collapseItem.collapse("show");
								toggleItem.removeClass("collapsed");
							} 
						}
					}
					// Avoid first category collapse-flicker on reload
					setTimeout(function() {
				       jQuery("#treePane").removeClass("no-transition");
					}, 50);
				}

                function setLastCollapsedCategory(type, id, state) {
	                  state = state != 1 ? 1 : 0;
	                  if(typeof elementsInTreeParams.cat_collapsed[type] == "undefined") elementsInTreeParams.cat_collapsed[type] = {};
	                  elementsInTreeParams.cat_collapsed[type][id] = state;
                }
				function writeElementsInTreeParamsToStorage() {
					var jsonString = JSON.stringify(elementsInTreeParams);
					localStorage.setItem(storageKey, jsonString );
				}
				
	            jQuery(document).ready(function() {

                jQuery(".filterElements-form").keydown(function (e) {
                    if(e.keyCode == 13) e.preventDefault();
                });
              
                '.$treeButtonsInTab_js.'
                
                // Shift-Mouseclick opens/collapsed all categories
                jQuery(".accordion-toggle").click(function(e) {
					      e.preventDefault();
					      var thisItemCollapsed = jQuery(this).hasClass("collapsed");
					      if (e.shiftKey) {
					          // Shift-key pressed
					          var toggleItems = jQuery(this).closest(".panel-group").find("> .panel .accordion-toggle");
					          var collapseItems = jQuery(this).closest(".panel-group").find("> .panel > .panel-collapse");
					          if(thisItemCollapsed) {
					            toggleItems.removeClass("collapsed");
					            collapseItems.collapse("show");
					          } else {
					            toggleItems.addClass("collapsed");
					            collapseItems.collapse("hide");
					          }
					          // Save states to localStorage
					          toggleItems.each(function() {
					            state = jQuery(this).hasClass("collapsed") ? 1 : 0;
					            setLastCollapsedCategory(jQuery(this).data("cattype"), jQuery(this).data("catid"), state);
					          });
					          writeElementsInTreeParamsToStorage();
					      } else {
					        jQuery(this).toggleClass("collapsed");
					        jQuery(jQuery(this).attr("href")).collapse("toggle");
					        // Save state to localStorage
					        state = thisItemCollapsed ? 0 : 1;
					        setLastCollapsedCategory(jQuery(this).data("cattype"), jQuery(this).data("catid"), state);
					        writeElementsInTreeParamsToStorage();
					      }
					});
					  
					setRememberCollapsedCategories();

	            });
	        } catch(err) {
	            alert("document.ready error: " + err);
	        }
        </script>
		<script type="text/javascript">
		treePane = new WebFXTabPane(document.getElementById( "treePane" ),true);
		</script>
		<div class="tab-page" id="tabDoc" style="padding-left:0; padding-right:0;">
		<h2 class="tab">'.$tabTreeTitle.'</h2>
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
		
		$tablePre = $modx->db->config['dbase'] . '.`' . $modx->db->config['table_prefix'];
		
		// createResourceList function
		
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
				return '';
			}
			
			$preCat = '';
			$insideUl = 0;
			
			for($i=0; $i<$limit; $i++) {
				$row = $modx->db->getRow($rs);
				$row['category'] = stripslashes($row['category']); //pixelchutes
				if ($preCat !== $row['category']) {
					$output .= $insideUl? '</div>': '';
					$row['catid'] = intval($row['catid']);
                    $output .= '<div class="panel-heading"><span class="panel-title"><a class="accordion-toggle" id="toggle'.$resourceTable.$row['catid'].'" href="#collapse'.$resourceTable.$row['catid'].'" data-cattype="'.$resourceTable.'" data-catid="'.$row['catid'].'" title="Click to toggle collapse. Shift+Click to toggle all."> '.$row['category'].'</a></span></div><div class="panel-collapse in '.$resourceTable.'"  id="collapse'.$resourceTable.$row['catid'].'"><ul>';
					$insideUl = 1;
				}
				if ($resourceTable == 'site_plugins') $class = $row['disabled'] ? ' class="disabledPlugin"' : '';
				$output .= '<li class="eltree"><span'.$class.'><a href="index.php?id='.$row['id'].'&amp;a='.$action.'" target="main"><span class="elementname">'.$row['name'].'</span><small> (' . $row['id'] . ')</small></a>
                  <a class="ext-ico" href="#" title="Open in new window" onclick="window.open(\'index.php?id='.$row['id'].'&a='.$action.'\',\'gener\',\'width=800,height=600,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')"> <small><i class="fa fa-external-link" aria-hidden="true"></i></small></a>'.($modx_textdir ? '&rlm;' : '').'</span>';
				
				$output .= $row['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ;
				$output .= '</li>';
				$preCat = $row['category'];
			}
			$output .= $insideUl? '</ul></div></div>': '';
			$output .= '</div>';
			$output .= '
    
        <script>
          jQuery(\'#collapse'.$resourceTable.$row['catid'].'\').collapse();
          initQuicksearch(\'tree_'.$resourceTable.'_search\', \'tree_'.$resourceTable.'\');
          jQuery(\'#tree_'.$resourceTable.'_search\').on(\'focus\', function () {
            searchFieldCache = elementsInTreeParams.cat_collapsed;
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("collapsed");
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').addClass("no-events");
            jQuery(\'.'.$resourceTable.'\').collapse(\'show\');
          }).on(\'blur\', function () {
            setRememberCollapsedCategories(searchFieldCache);
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("no-events");
          });
        </script>';
			return $output;
		}
		
		// end createResourceList function
		
		// createModulesList function
		
		function createModulesList($resourceTable,$action,$tablePre,$nameField = 'name') {
		
			global $modx, $_lang;
			
			$output  = '
				<form class="filterElements-form" style="margin-top: 0;">
				  <input class="form-control" type="text" placeholder="Type here to filter list" id="tree_'.$resourceTable.'_search">
				</form>';
				
			$output .= '<div class="panel-group"><div class="panel panel-default" id="tree_'.$resourceTable.'">';

			if ($_SESSION['mgrRole'] != 1) {
				$rs = $modx->db->query('SELECT sm.id, sm.name, sm.category, sm.disabled, cats.category AS catname, cats.id AS catid, mg.member
				FROM ' . $modx->getFullTableName('site_modules') . ' AS sm
				LEFT JOIN ' . $modx->getFullTableName('site_module_access') . ' AS sma ON sma.module = sm.id
				LEFT JOIN ' . $modx->getFullTableName('member_groups') . ' AS mg ON sma.usergroup = mg.user_group
				LEFT JOIN ' . $modx->getFullTableName('categories') . ' AS cats ON sm.category = cats.id
				WHERE (mg.member IS NULL OR mg.member = ' . $modx->getLoginUserID() . ') AND sm.disabled != 1 AND sm.locked != 1
				ORDER BY 5,1');
			} 
			
			else {
				$rs = $modx->db->query('SELECT sm.id, sm.name, sm.category, sm.disabled, cats.category AS catname, cats.id AS catid
				FROM ' . $modx->getFullTableName('site_modules') . ' AS sm
				LEFT JOIN ' . $modx->getFullTableName('categories') . ' AS cats ON sm.category = cats.id
				WHERE sm.disabled != 1
				ORDER BY 5,1');
			}
			
			$limit = $modx->db->getRecordCount($rs);
			
			if($limit<1){
                return '';
			}
			
			$preCat   = '';
			$insideUl = 0;
			
			for($i=0; $i<$limit; $i++) {
				$row = $modx->db->getRow($rs);
				if($row['catid'] > 0) {
					$row['catid'] = stripslashes($row['catid']);
				} else {
					$row['catname'] = $_lang["no_category"];
				}
				if ($preCat !== $row['category']) {
					$output .= $insideUl? '</div>': '';
					$row['catid'] = intval($row['catid']);
                    $output .= '<div class="panel-heading"><span class="panel-title"><a class="accordion-toggle" id="toggle'.$resourceTable.$row['catid'].'" href="#collapse'.$resourceTable.$row['catid'].'" data-cattype="'.$resourceTable.'" data-catid="'.$row['catid'].'" title="Click to toggle collapse. Shift+Click to toggle all."> '.$row['catname'].'</a></span></div><div class="panel-collapse in '.$resourceTable.'"  id="collapse'.$resourceTable.$row['category'].'"><ul>';
					$insideUl = 1;
				}
				$output .= '<li class="eltree"><span><a href="index.php?id='.$row['id'].'&amp;a='.$action.'" target="main"><span class="elementname">'.$row['name'].'</span><small> (' . $row['id'] . ')</small></a>
                  <a class="ext-ico" href="#" title="Open in new window" onclick="window.open(\'index.php?id='.$row['id'].'&a='.$action.'\',\'gener\',\'width=800,height=600,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')"> <small><i class="fa fa-external-link" aria-hidden="true"></i></small></a>'.($modx_textdir ? '&rlm;' : '').'</span>';
				$output .= $row['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ;
				$output .= '</li>';
				$preCat  = $row['category'];
			}
			$output .= $insideUl? '</ul></div></div>': '';
			$output .= '</div>';
			$output .= '
    
        <script>
          initQuicksearch(\'tree_'.$resourceTable.'_search\', \'tree_'.$resourceTable.'\');
          jQuery(\'#tree_'.$resourceTable.'_search\').on(\'focus\', function () {
            searchFieldCache = elementsInTreeParams.cat_collapsed;
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').addClass("no-events");
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("collapsed");
            jQuery(\'.'.$resourceTable.'\').collapse(\'show\');
          }).on(\'blur\', function () {
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("no-events");
            setRememberCollapsedCategories(searchFieldCache);
          });
        </script>';
			return $output;
		}
		
		// end createModulesList function
		
		$temp    = createResourceList('site_templates',16,$tablePre,'templatename');
		$tv      = createResourceList('site_tmplvars',301,$tablePre);
		$chunk   = createResourceList('site_htmlsnippets',78,$tablePre);
		$snippet = createResourceList('site_snippets',22,$tablePre);
		$plugin  = createResourceList('site_plugins',102,$tablePre);
		$module  = createModulesList('site_modules',112,$tablePre);

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
              <li><a href="index.php?a=19" target="main">'.$_lang['new_template'].'</a></li>
              <li><a href="javascript:location.reload();" title="Click here if element was added or deleted to refresh the list.">'.$tabLabel_refresh.'</a></li>
              </ul>
              </div>
              <div class="tab-page" id="tabTV" style="padding-left:0; padding-right:0;">
              <h2 class="tab" title="Template Variables">'.$tabLabel_tv.'</h2>
              <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabTV" ) );</script>
              '.$tv.'
              <br/>
              <ul class="actionButtons">
              <li><a href="index.php?a=300" target="main">'.$_lang['new_tmplvars'].'</a></li>
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
              <li><a href="index.php?a=77" target="main">'.$_lang['new_htmlsnippet'].'</a></li>
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
              <li><a href="index.php?a=23" target="main">'.$_lang['new_snippet'].'</a></li>
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
              <li><a href="index.php?a=101" target="main">'.$_lang['new_plugin'].'</a></li>
              <li><a href="javascript:location.reload();" title="Click here if element was enabled/disabled/added/deleted to refresh the list.">'.$tabLabel_refresh.'</a></li>
              </ul>
              </div>
	        ';
		}
		
		if ($modx->hasPermission('exec_module')) {
			
			$new_module_button = '';
      
			if ($modx->hasPermission('new_module')) {
				$new_module_button = '<li><a href="index.php?a=107" target="main">'.$_lang['new_module'].'</a></li>';
			}
			
			$output .= '
              <div class="tab-page" id="tabMD" style="padding-left:0; padding-right:0;">
              <h2 class="tab" title="Modules">'.$tabLabel_module.'</h2>
              <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabMD" ) );</script>
              '.$module.'
              <br/>
              <ul class="actionButtons">
              '.$new_module_button.'
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