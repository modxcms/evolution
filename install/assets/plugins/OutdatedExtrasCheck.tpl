/**
 * OutdatedExtrasCheck
 *
 * Check for Outdated critical extras not compatible with EVO 1.4.0
 *
 * @category	plugin
 * @version     1.4.0 
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @package     evo
 * @author      Author: Nicola Lambathakis
 * @internal    @events OnManagerWelcomeHome
 * @internal    @properties &wdgVisibility=Show widget for:;menu;All,AdminOnly,AdminExcluded,ThisRoleOnly,ThisUserOnly;All &ThisRole=Run only for this role:;string;;;(role id) &ThisUser=Run only for this user:;string;;;(username) &DittoVersion=Min Ditto version:;string;2.1.3 &MtvVersion=Min multiTV version:;string;2.0.12
 * @internal    @modx_category Manager and Admin
 * @internal    @installset base
 * @internal    @disabled 0
 */

// get manager role check
$internalKey = $modx->getLoginUserID();
$sid = $modx->sid;
$role = $_SESSION['mgrRole'];
$user = $_SESSION['mgrShortname'];
// show widget only to Admin role 1
if(($role!=1) AND ($wdgVisibility == 'AdminOnly')) {}
// show widget to all manager users excluded Admin role 1
else if(($role==1) AND ($wdgVisibility == 'AdminExcluded')) {}
// show widget only to "this" role id
else if(($role!=$ThisRole) AND ($wdgVisibility == 'ThisRoleOnly')) {}
// show widget only to "this" username
else if(($user!=$ThisUser) AND ($wdgVisibility == 'ThisUserOnly')) {}
else {
// get plugin id and setting button
$result = $modx->db->select('id', $this->getFullTableName("site_plugins"), "name='{$modx->event->activePlugin}' AND disabled=0");
$pluginid = $modx->db->getValue($result);
if($modx->hasPermission('edit_plugin')) {
$button_pl_config = '<a data-toggle="tooltip" href="javascript:;" title="' . $_lang["settings_config"] . '" class="text-muted pull-right" onclick="parent.modx.popup({url:\''. MODX_MANAGER_URL.'?a=102&id='.$pluginid.'&tab=1\',title1:\'' . $_lang["settings_config"] . '\',icon:\'fa-cog\',iframe:\'iframe\',selector2:\'#tabConfig\',position:\'center center\',width:\'80%\',height:\'80%\',hide:0,hover:0,overlay:1,overlayclose:1})" ><i class="fa fa-cog fa-spin-hover" style="color:#FFFFFF;"></i> </a>';
}
$modx->setPlaceholder('button_pl_config', $button_pl_config);
//plugin lang
$_oec_lang = array();
$plugin_path = $modx->config['base_path'] . "assets/plugins/extrascheck/";
include($plugin_path . 'lang/english.php');
if (file_exists($plugin_path . 'lang/' . $modx->config['manager_language'] . '.php')) {
include($plugin_path . 'lang/' . $modx->config['manager_language'] . '.php');
}
//run the plugin
// get globals
global $modx,$_lang;
//function to extract snippet version from description <strong></strong> tags 
if (!function_exists('getver')) {
function getver($string, $tag)
{
$content ="/<$tag>(.*?)<\/$tag>/";
preg_match($content, $string, $text);
return $text[1];
	}
}
$e = &$modx->Event;
$EVOversion = $modx->config['settings_version'];
$output = '';
//get extras module id for the link
$modtable = $modx->getFullTableName('site_modules');
$getExtra = $modx->db->select( "id, name", $modtable, "name='Extras'" );
while( $row = $modx->db->getRow( $getExtra ) ) {
$ExtrasID = $row['id'];
}
//get site snippets table
$table = $modx->getFullTableName('site_snippets');
//check ditto
//get min version from config
$minDittoVersion = $DittoVersion;
//search the snippet by name
$CheckDitto = $modx->db->select( "id, name, description", $table, "name='Ditto'" );
if($CheckDitto != ''){
while( $row = $modx->db->getRow( $CheckDitto ) ) {
//extract snippet version from description <strong></strong> tags 
$curr_ditto_version = getver($row['description'],"strong");
//check snippet version and return an alert if outdated
if ($curr_ditto_version < $minDittoVersion){
$output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> '.$_lang["snippet"].' (version ' . $curr_ditto_version . ') '.$_oec_lang['isoutdated'].' <b>Evolution '.$EVOversion.'</b>. '.$_oec_lang['please_update'].' <b>' . $row['name'] . '</b> '.$_oec_lang["to_latest"].' ('.$_oec_lang['min _required'].' '.$minDittoVersion.') '.$_oec_lang['from'].' <a target="main" href="index.php?a=112&id='.$ExtrasID.'">'.$_oec_lang['extras_module'].'</a> '.$_oec_lang['or_move_to'].' <b>DocLister</b></div>';
		}
	}
} 
//end check ditto

//check Multitv
//get min version from config
$minMtvVersion = $MtvVersion;
//search the snippet by name
$CheckMtv = $modx->db->select( "id, name, description", $table, "name='multiTV'" );
if($CheckMtv != ''){
while( $row = $modx->db->getRow( $CheckMtv ) ) {
//extract snippet version from description <strong></strong> tags 
$curr_mtv_version = getver($row['description'],"strong");
//check snippet version and return an alert if outdated
if ($curr_mtv_version < $minMtvVersion){
$output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> '.$_lang["snippet"].' (version ' . $curr_mtv_version . ') '.$_oec_lang['isoutdated'].' <b>Evolution '.$EVOversion.'</b>. '.$_oec_lang['please_update'].' <b>' . $row['name'] . '</b> '.$_oec_lang["to_latest"].' ('.$_oec_lang['min _required'].' '.$minMtvVersion.') '.$_oec_lang['from'].' <a target="main" href="index.php?a=112&id='.$ExtrasID.'">'.$_oec_lang['extras_module'].'</a></div>';
		}
	}
} 
//end check Multitv
if($output != ''){
if($e->name == 'OnManagerWelcomeHome') {
$out = $output;
$wdgTitle = 'EVO '.$EVOversion.' - '.$_oec_lang['title'].'';
$widgets['xtraCheck'] = array(
				'menuindex' =>'0',
				'id' => 'xtraCheck'.$pluginid.'',
				'cols' => 'col-md-12',
                'headAttr' => 'style="background-color:#B60205; color:#FFFFFF;"',
				'bodyAttr' => 'style="background-color:#FFFFFF; color:#24292E;"',
				'icon' => 'fa-warning',
				'title' => ''.$wdgTitle.' '.$button_pl_config.'',
				'body' => '<div class="card-body">'.$out.'</div>',
				'hide' => '0'
			);	
            $e->output(serialize($widgets));
return;
		}
	}
}