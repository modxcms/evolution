/**
 * Document Manager Module
 * 
 * Purpose: Allows for the bulk management of key document settings.
 * Author: Garry Nutting (Mark Kaplan - Menu Index functionalty, Luke Stokes - Document Permissions concept)
 * For: MODx CMS (www.modxcms.com)
 * Date:24/02/2006 Version: 1
 * 
 */

global $theme;
global $table;
global $_lang;
global $siteURL;

$basePath = $modx->config['base_path'];
$siteURL = $modx->config['site_url'];

//-- include language file
include_once $basePath.'assets/modules/docmanager/lang/lang.en.php';

//-- get theme
$tb_prefix = $modx->db->config['table_prefix'];
$theme = $modx->db->select('setting_value', $tb_prefix . 'system_settings', 'setting_name=\'manager_theme\'', '');
$theme = $modx->db->getRow($theme);
$theme = ($theme['setting_value'] <> '') ? '/' . $theme['setting_value'] : '';

//-- setup initial vars
$table = $modx->getFullTableName('site_content');
$output = '';
$error = '';

//-- include php files
include_once $basePath.'manager/includes/controls/datagrid.class.php';
include_once $basePath.'assets/modules/docmanager/includes/interaction.inc.php';
include_once $basePath.'assets/modules/docmanager/includes/process.inc.php';

//-- get POST vars
$tabAction = (isset ($_POST['tabAction'])) ? $_POST['tabAction'] : ''; // get action for active tab
$intType = (isset($_POST['opcode']) && $_POST['opcode'] == 'range') ? 'range' : 'tree'; // get interaction type

//-- Menu Index
if ($tabAction == 'sortMenu' || isset($_POST['sortableListsSubmitted'])) {
$id= isset($_POST['new_parent'])? $_POST['new_parent']: 0;
$actionkey = isset($_POST['actionkey'])? $_POST['actionkey']: 0;
if(isset($_POST['sortableListsSubmitted'])) {$actionkey =1;}

include_once $basePath.'assets/modules/docmanager/includes/SLLists.class.php';

}

//-- process POST actions if required
if ($tabAction == 'change_template') {
	$output .= changeTemplate($intType, $_POST['pids'], $_POST['newvalue']);
	return $output;
} elseif ($tabAction == 'pushDocGroup' || $tabAction == 'pullDocGroup' ) {
	$output.=changeDocGroups($intType, $_POST['pids'],$_POST['newvalue'],$tabAction);
	return $output;
} elseif ((isset($_POST['actionkey'])) && $tabAction == 'sortMenu' || isset($_POST['sortableListsSubmitted']) ) {
		$output .= ' <html><head>
									    <script type="text/javascript">
										function save() 
										{ 
										populateHiddenVars(); 
										if (document.getElementById("updated")) {new Effect.Fade(\'updated\', {duration:0});} 
										new Effect.Appear(\'updating\',{duration:0.5}); 
										setTimeout("document.sortableListForm.submit()",1000); 
										}
										</script>
									    <style type="text/css">
									    input {display:none;}
									    </style>
										';

	$output.= sortMenu($id);
	return $output;
} elseif ($tabAction == 'changeOther') {
	$output.= changeOther($intType, $_POST['pids']);
	return $output;
}

//-- render tabbed output
//--- HEAD
$output .= ' 
<html> 
		<head> 
		<link rel="stylesheet" type="text/css" href="media/style' . $theme . '/style.css?" /> 
		<link rel="stylesheet" type="text/css" href="media/style' . $theme . '/coolButtons2.css?" /> 
	    <link rel="stylesheet" type="text/css" href="media/style' . $theme . '/tabs.css?" /> 
		<script type="text/javascript" src="media/script/scriptaculous/prototype.js"></script> 
		<script type="text/javascript" src="media/script/scriptaculous/scriptaculous.js"></script> 
	    <script type="text/javascript" src="media/script/modx.js"></script> 
		<script type="text/javascript" src="media/script/cb2.js"></script> 
		<script type="text/javascript" src="media/script/tabpane.js"></script>  
        <script type="text/javascript" src="../assets/modules/docmanager/js/functions.js"></script>
        <script language="JavaScript" src="media/script/datefunctions.js"></script>
        <script type="text/javascript">var MODX_MEDIA_PATH = "media";</script>  
        <script type="text/javascript">
        function save()
		{
			document.newdocumentparent.submit();
		}	
	

parent.menu.ca = "move";

function setMoveValue(pId, pName) {
	if (pId==0 || checkParentChildRelation(pId, pName)) {
		document.newdocumentparent.new_parent.value=pId;
		document.getElementById(\'parentName\').innerHTML = "Parent: <b>" + pId + "</b> (" + pName + ")";
	}
}

// check if the selected parent is a child of this document
function checkParentChildRelation(pId, pName) {
	var sp;
	var id = document.newdocumentparent.id.value;
	var tdoc = parent.menu.document;
	var pn = (tdoc.getElementById) ? tdoc.getElementById("node"+pId) : tdoc.all["node"+pId];
	if (!pn) return;
		while (pn.p>0) {
			pn = (tdoc.getElementById) ? tdoc.getElementById("node"+pn.p) : tdoc.all["node"+pn.p];
			if (pn.id.substr(4)==id) {
				alert("Illegal Parent");
				return;
			}
		}
	
	return true;
}

		</script>';

$output.= buttonCSS();

$output.='
        </head>
        <body>

        <div class="subTitle" id="bttn"> 
				<span class="right"><img src="media/style' . $theme . '/images/_tx_.gif" width="1" height="5"><br />' . $_lang['module_title'] . '</span> 
				<div class="bttnheight"><a id="Button5" onclick="document.location.href=\'index.php?a=106\';">
					<img src="media/style' . $theme . '/images/icons/cancel.gif"> '.$_lang['cancel'].'</a>
				</div> 
				<div class="stay">   </div> 
	    </div> 
	';
	
		
//--- TABS
$output.= '<div class="sectionHeader"><img src=\'media/style' . $theme . '/images/misc/dot.gif\' alt="." />&nbsp;' . $_lang['action_title'] . '</div>
		   <div class="sectionBody"> 
	       <div class="tab-pane" id="docManagerPane"> 
	       <script type="text/javascript"> 
				tpResources = new WebFXTabPane( document.getElementById( "docManagerPane" ) ); 
	       </script>';

//--- template	       
$output.= '<div class="tab-page" id="tabTemplates">  
	    <h2 class="tab">' . $_lang["change_template"] . '</h2>  
	    <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabTemplates" ) );</script> 
		';

$output.=showTemplate();

$output.='</div>';

//--- document permissions	       
$output.= '<div class="tab-page" id="tabDocPermissions">  
	    <h2 class="tab">' . $_lang['doc_permissions']. '</h2>  
	    <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabDocPermissions" ) );</script> 
		';
	
$output.=showDocGroups();	
		
$output.='</div>';

//--- sort menu	       
$output.= '<div class="tab-page" id="tabSortMenu">  
	    <h2 class="tab">' . $_lang['sort_menu'] . '</h2>  
	    <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabSortMenu" ) );</script> 
		';

$output.= showSortMenu();

$output.='</div>';

//--- show Other    
$output.= '<div class="tab-page" id="tabOther">  
	    <h2 class="tab">' . $_lang['other'] . '</h2>  
	    <script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabOther" ) );</script> 
		';
	
$output.= showOther();
$output.= showAdjustDates();
$output.= showAdjustAuthors();
	
$output.='</div></div></div>';

$output.= showInteraction();

//-- send output
$output.='</body></html>';
return $output;