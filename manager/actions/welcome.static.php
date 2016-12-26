<?php
if (IN_MANAGER_MODE != "true")
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

unset($_SESSION['itemname']); // clear this, because it's only set for logging purposes

if ($modx->hasPermission('settings') && (!isset($settings_version) || $settings_version != $modx->getVersionData('version'))) {
    // seems to be a new install - send the user to the configuration page
    echo '<script type="text/javascript">document.location.href="index.php?a=17";</script>';
    exit;
}

$script = <<<JS
        <script type="text/javascript">
        function hideConfigCheckWarning(key){
            var myAjax = new Ajax('index.php?a=118', {
                method: 'post',
                data: 'action=setsetting&key=_hide_configcheck_' + key + '&value=1'
            });
            myAjax.addEvent('onComplete', function(resp){
                fieldset = $(key + '_warning_wrapper').getParent().getParent();
                var sl = new Fx.Slide(fieldset);
                sl.slideOut();
            });
            myAjax.request();
        }

        </script>
JS;
$modx->regClientScript($script);

// set placeholders
$ph = array();
$ph['theme'] = $modx->config['manager_theme'];
$ph['home'] = $_lang["home"];
$ph['logo_slogan'] =  $_lang["logo_slogan"];
$ph['site_name'] =  $site_name;
$ph['welcome_title'] =  $_lang['welcome_title'];
$ph['resetgrid'] =  $_lang['reset'];
$ph['search'] =  $_lang['search'];

// setup message info
if ($modx->hasPermission('messages')) {
    include_once(MODX_MANAGER_PATH . 'includes/messageCount.inc.php');
    $_SESSION['nrtotalmessages'] = $nrtotalmessages;
    $_SESSION['nrnewmessages']   = $nrnewmessages;
    
    $msg = '<a href="index.php?a=10"><img src="' . $_style['icons_mail_large'] . '" /></a>
    <span style="color:#909090;font-size:15px;font-weight:bold">&nbsp;<a class="wm_messages_inbox_link" href="index.php?a=10">' . $_lang["inbox"] . '</a>' . ($_SESSION['nrnewmessages'] > 0 ? " (<span style='color:red'>" . $_SESSION['nrnewmessages'] . "</span>)" : "") . '</span><br />
    <span class="comment">' . sprintf($_lang["welcome_messages"], $_SESSION['nrtotalmessages'], "<span style='color:red;'>" . $_SESSION['nrnewmessages'] . "</span>") . '</span>';
    $ph['MessageInfo'] =  $msg;
}

// setup icons
function wrapIcon($i)
{
    return '<span class="wm_button" style="border:0">' . $i . '</span>';
}
// setup icons
if($modx->hasPermission('new_user')||$modx->hasPermission('edit_user')) {
	$icon = '<a class="hometblink" href="index.php?a=75"><i class="'.$_style['icons_security_large'].'" alt="'.$_lang['user_management_title'].'"> </i><br />'.$_lang['security'].'</a>';
	$ph['SecurityIcon'] = wrapIcon($icon);
}
if($modx->hasPermission('new_web_user')||$modx->hasPermission('edit_web_user')) {
	$icon = '<a class="hometblink" href="index.php?a=99"><i class="'.$_style['icons_webusers_large'].'" alt="'.$_lang['web_user_management_title'].'"> </i><br />'.$_lang['web_users'].'</a>';
	$ph['WebUserIcon'] = wrapIcon($icon);
}
if($modx->hasPermission('new_module') || $modx->hasPermission('edit_module')) {
	$icon = '<a class="hometblink" href="index.php?a=106"><i class="'.$_style['icons_modules_large'].'" alt="'.$_lang['manage_modules'].'"> </i><br />'.$_lang['modules'].'</a>';
	$ph['ModulesIcon'] = wrapIcon($icon);
}
if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('manage_metatags')) {
	$icon = '<a class="hometblink" href="index.php?a=76"><i class="'.$_style['icons_resources_large'].'" alt="'.$_lang['element_management'].'"> </i><br />'.$_lang['elements'].'</a>';
	$ph['ResourcesIcon'] = wrapIcon($icon);
}
if($modx->hasPermission('bk_manager')) {
	$icon = '<a class="hometblink" href="index.php?a=93"><i class="'.$_style['icons_backup_large'].'" alt="'.$_lang['bk_manager'].'"> </i><br />'.$_lang['backup'].'</a>';
	$ph['BackupIcon'] = wrapIcon($icon);
}
if ($modx->hasPermission('help')) {
    $icon = '<a class="hometblink" href="index.php?a=9"><i class="'.$_style['icons_help_large'].'" alt="'. $_lang['help'].'" /> </i><br />' . $_lang['help'] . '</a>';
    $ph['HelpIcon'] =  wrapIcon($icon);
}
// do some config checks
if (($modx->config['warning_visibility'] == 0 && $_SESSION['mgrRole'] == 1) || $modx->config['warning_visibility'] == 1) {
    include_once(MODX_MANAGER_PATH.'includes/config_check.inc.php');
    $ph['settings_config'] =  $_lang['settings_config'];
    $ph['configcheck_title'] =  $_lang['configcheck_title'];
    if ($config_check_results != $_lang['configcheck_ok']) {
        $ph['config_check_results'] =  $config_check_results;
        $ph['config_display'] =  'block';
    } else {
        $ph['config_display'] =  'none';
    }
} else {
    $ph['config_display'] =  'none';
}

// Check logout-reminder
if(isset($_SESSION['show_logout_reminder'])) {
	switch($_SESSION['show_logout_reminder']['type']) {
		case 'logout_reminder':
			$ph['logout_reminder_msg'] = $modx->parseText($_lang["logout_reminder_msg"], array('date' => $modx->toDateFormat($_SESSION['show_logout_reminder']['lastHit'], 'dateOnly')));
			break;
	}
	$ph['show_logout_reminder'] = 'block';
	unset($_SESSION['show_logout_reminder']);
} else {
	$ph['show_logout_reminder'] = 'none';
}

// Check multiple sessions
$rs = $modx->db->select('count(*) AS count', $modx->getFullTableName('active_user_sessions'), "internalKey='{$_SESSION['mgrInternalKey']}'");
$count = $modx->db->getValue($rs);
if($count > 1) {
	$ph['multiple_sessions_msg'] = $modx->parseText($_lang["multiple_sessions_msg"], array('username' => $_SESSION['mgrShortname'], 'total'=>$count));
	$ph['show_multiple_sessions'] = 'block';
} else {
	$ph['show_multiple_sessions'] = 'none';
}

// include rss feeds for important forum topics
include_once(MODX_MANAGER_PATH.'includes/rss.inc.php');

// modx news
$ph['modx_news'] =  $_lang["modx_news_tab"];
$ph['modx_news_title'] =  $_lang["modx_news_title"];
$ph['modx_news_content'] =  $feedData['modx_news_content'];

// security notices
$ph['modx_security_notices'] =  $_lang["security_notices_tab"];
$ph['modx_security_notices_title'] =  $_lang["security_notices_title"];
$ph['modx_security_notices_content'] =  $feedData['modx_security_notices_content'];

$ph['RecentInfo'] =  getRecentInfo();
$ph['recent_docs'] =  $_lang['recent_docs'];
$ph['activity_title'] =  $_lang['activity_title'];

// user info
$ph['info'] =  $_lang['info'];
$ph['yourinfo_title'] =  $_lang['yourinfo_title'];
$html = '
    <table class="table table-hover table-condensed">
      <tr>
        <td width="150">' . $_lang["yourinfo_username"] . '</td>
        <td><b>' . $modx->getLoginUserName() . '</b></td>
      </tr>
      <tr>
        <td>' . $_lang["yourinfo_role"] . '</td>
        <td><b>' . $_SESSION['mgrPermissions']['name'] . '</b></td>
      </tr>
      <tr>
        <td>' . $_lang["yourinfo_previous_login"] . '</td>
        <td><b>' . $modx->toDateFormat($_SESSION['mgrLastlogin'] + $server_offset_time) . '</b></td>
      </tr>
      <tr>
        <td>' . $_lang["yourinfo_total_logins"] . '</td>
        <td><b>' . ($_SESSION['mgrLogincount'] + 1) . '</b></td>
      </tr>
      <tr>
        <td>' . $_lang["inbox"] . '</td>
        <td><a href="index.php?a=10"><b>' . sprintf($_lang["welcome_messages"], $_SESSION['nrtotalmessages'], "<span style='color:red;'>" . $_SESSION['nrnewmessages'] . "</span>") . '</b></a></td>
      </tr>
    </table>
';
$ph['UserInfo'] =  $html;

// online users
$ph['online'] =  $_lang['online'];
$ph['onlineusers_title'] =  $_lang['onlineusers_title'];
$timetocheck = (time() - (60 * 20)); //+$server_offset_time;

include_once(MODX_MANAGER_PATH.'includes/actionlist.inc.php');

$rs    = $modx->db->select('*, count(au.sid) AS count', $modx->getFullTableName('active_user_sessions')." us LEFT JOIN {$modx->getFullTableName('active_users')} au ON au.internalKey=us.internalKey GROUP BY au.sid HAVING au.action <> '8'", "", 'username ASC, au.sid ASC');
$limit = $modx->db->getRecordCount($rs);
if ($limit < 1) {
    $html = "<p>" . $_lang['no_active_users_found'] . "</p>";
} else {
    $html = $_lang["onlineusers_message"] . '<b>' . strftime('%H:%M:%S', time() + $server_offset_time) . '</b>):<br /><br />
                <div class="table-responsive">
                <table class="table table-hover table-condensed">
                  <thead>
                    <tr>
                      <th>' . $_lang["onlineusers_user"] . '</th>
                      <th>' . $_lang["onlineusers_userid"] . '</th>
                      <th>' . $_lang["onlineusers_ipaddress"] . '</th>
                      <th>' . $_lang["onlineusers_lasthit"] . '</th>
                      <th>' . $_lang["onlineusers_action"] . '</th>
                    </tr>
                  </thead>
                  <tbody>
        ';
    while ($activeusers = $modx->db->getRow($rs)) {
        $idle          = $activeusers['lasthit'] < $timetocheck ? ' class="userIdle"' : '';
        $multipleSessions = $activeusers['count'] > 1 ? ' class="userMultipleSessions"' : '';
        $webicon       = ($activeusers['internalKey'] < 0) ? sprintf('<img src="%s" alt="Web user" />',$_style["tree_globe"]) : '';
        $currentaction = getAction($activeusers['action'], $activeusers['id']);
        $params = array($idle, $multipleSessions, $activeusers['username'], $webicon, abs($activeusers['internalKey']), $activeusers['ip'], strftime('%H:%M:%S', $activeusers['lasthit'] + $server_offset_time),$currentaction);
        $html .= vsprintf('<tr%s><td><strong%s>%s</strong></td><td>%s&nbsp;%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $params);
    }
    $html .= '
                </tbody>
                </table>
                </div>
        ';
}
$ph['OnlineInfo'] =  $html;

$modx->toPlaceholders($ph);

// invoke event OnManagerWelcomePrerender
$evtOut = $modx->invokeEvent('OnManagerWelcomePrerender');
if (is_array($evtOut)) {
    $output = implode("", $evtOut);
    $ph['OnManagerWelcomePrerender'] =  $output;
}

// invoke event OnManagerWelcomeHome
$evtOut = $modx->invokeEvent('OnManagerWelcomeHome');
if (is_array($evtOut)) {
    $output = implode("", $evtOut);
    $ph['OnManagerWelcomeHome'] =  $output;
}

// invoke event OnManagerWelcomeRender
$evtOut = $modx->invokeEvent('OnManagerWelcomeRender');
if (is_array($evtOut)) {
    $output = implode("", $evtOut);
    $ph['OnManagerWelcomeRender'] =  $output;
}

// load template
if (!isset($modx->config['manager_welcome_tpl']) || empty($modx->config['manager_welcome_tpl'])) {
    $modx->config['manager_welcome_tpl'] = MODX_MANAGER_PATH . 'media/style/common/welcome.tpl';
}

$target = $modx->config['manager_welcome_tpl'];
$target = str_replace('[+base_path+]', MODX_BASE_PATH, $target);
$target = $modx->mergeSettingsContent($target);

if (substr($target, 0, 1) === '@') {
    if (substr($target, 0, 6) === '@CHUNK') {
        $target      = trim(substr($target, 7));
        $welcome_tpl = $modx->getChunk($target);
    } elseif (substr($target, 0, 5) === '@FILE') {
        $target      = trim(substr($target, 6));
        $welcome_tpl = file_get_contents($target);
    }
} else {
    $chunk = $modx->getChunk($target);
    if ($chunk !== false && !empty($chunk)) {
        $welcome_tpl = $chunk;
    } elseif (is_file(MODX_BASE_PATH . $target)) {
        $target      = MODX_BASE_PATH . $target;
        $welcome_tpl = file_get_contents($target);
    } elseif (is_file(MODX_MANAGER_PATH . 'media/style/' . $modx->config['manager_theme'] . '/welcome.tpl')) {
        $target      = MODX_MANAGER_PATH . 'media/style/' . $modx->config['manager_theme'] . '/welcome.tpl';
        $welcome_tpl = file_get_contents($target);
    } elseif (is_file(MODX_MANAGER_PATH . 'media/style/' . $modx->config['manager_theme'] . '/html/welcome.html')) { // ClipperCMS compatible
        $target      = MODX_MANAGER_PATH . 'media/style/' . $modx->config['manager_theme'] . '/html/welcome.html';
        $welcome_tpl = file_get_contents($target);
    } else {
        $target      = MODX_MANAGER_PATH . 'media/style/common/welcome.tpl';
        $welcome_tpl = file_get_contents($target);
    }
}

// merge placeholders
$welcome_tpl = $modx->mergeConditionalTagsContent($welcome_tpl);
$welcome_tpl = $modx->mergeSettingsContent($welcome_tpl);
$welcome_tpl = $modx->parseText($welcome_tpl,$ph);
if(strpos($welcome_tpl,'[+')!==false) {
    $modx->toPlaceholders($ph);
    $welcome_tpl = $modx->mergePlaceholderContent($welcome_tpl);
}
$welcome_tpl = $modx->parseText($welcome_tpl,$_lang, '[%','%]');
$welcome_tpl = $modx->parseText($welcome_tpl,$_style,'[&','&]');
$welcome_tpl = $modx->cleanUpMODXTags($welcome_tpl); //cleanup

if ($js = $modx->getRegisteredClientScripts()) {
    $welcome_tpl .= $js;
}

echo $welcome_tpl;



function getRecentInfo() { // recent document info
    global $modx, $_lang;
    
    $html  = '<div class="table-responsive">
    <table class="table table-hover table-condensed">
    <thead>
    <tr>
    <th style="width: 50px;">[%id%]</th>
    <th>[%resource_title%]</th>
    <th style="width: 140px;">[%page_data_edited%]</th>
    <th style="width: 180px;">[%user%]</th>
    <th style="width: 180px; text-align: right;">[%mgrlog_action%]</th>
    </tr>
    </thead>';
    
    $rs = $modx->db->select('*', '[+prefix+]site_content', '', 'editedon DESC', 10);
    
    if ($modx->db->getRecordCount($rs) < 1) {
        $html .= '<tr><td>[%no_activity_message%]</td></tr>';
    }
    else {
        while ($content = $modx->db->getRow($rs)) {
            
            $editedbyu = $modx->getUserInfo($content['editedby']);
            $content['username'] = $editedbyu['username'];
            
            $html .= '<tr><td data-toggle="collapse" data-target=".collapse[+id+]"><span class="label label-info">[+id+]</span></td><td>';
            
            $html .= '<a class=';
            if ($content['deleted'] == 1)       $html .= '"deleted"';
            elseif ($content['published'] == 0) $html .= '"unpublished"';
            else                                $html .= '"published"';
            
            $html .= ' title="[%edit_resource%]" href="index.php?a=3&amp;id=[+id+]">[+pagetitle+]</a></td><td data-toggle="collapse" data-target=".collapse[+id+]">[+editedon:math("%s+[(server_offset_time)]"):dateFormat+]</td><td data-toggle="collapse" data-target=".collapse[+id+]">[+username+]</td>
            <td style="text-align: right;">';
            
            if ($modx->hasPermission('edit_document')) {
                $html .= '<a class="btn btn-xs btn-success" title="[%edit_resource%]" href="index.php?a=27&amp;id=[+id+]"><i class="fa fa-edit fa-fw"></i></a> ';
            }
            
            if ($content['deleted'] == 1) {
                $html .= '<a class="btn btn-xs btn-info disabled"  title="[%preview_resource%]" target="_blank" href="../index.php?&amp;id=[+id+]"><i class="fa fa-eye fa-fw"></i></a> ';
            } else {
                $html .= '<a class="btn btn-xs btn-info"  title="[%preview_resource%]" target="_blank" href="../index.php?&amp;id=[+id+]"><i class="fa fa-eye fa-fw"></i></a> ';
            }
            
            if ($modx->hasPermission('delete_document')) {
                if ($content['deleted'] == 0) {
                    $html .= '<a onclick="return confirm(\'[%confirm_delete_record%]\')" class="btn btn-xs btn-danger"  title="[%delete_resource%]" href="index.php?a=6&amp;id=[+id+]"><i class="fa fa-trash fa-fw"></i></a> ';
                } else {
                    $html .= '<a onclick="return confirm(\'[%["confirm_undelete%]\')" class="btn btn-xs btn-success"  title="[%undelete_resource%]" href="index.php?a=63&amp;id=[+id+]"><i class="fa fa-arrow-circle-o-up fa-fw"></i></a> ';
                }
            }
            
            if ($content['deleted'] == 1 && $content['published'] == 0) {
                $html .= '<a class="btn btn-xs btn-primary disabled"  title="[%publish_resource%]" href="index.php?a=61&amp;id=[+id+]"><i class="fa fa-arrow-up fa-fw"></i></a> ';
            } elseif ($content['deleted'] == 1 && $content['published'] == 1) {
                $html .= '<a class="btn btn-xs btn-primary disabled"  title="[%publish_resource%]" href="index.php?a=61&amp;id=[+id+]"><i class="fa fa-arrow-down fa-fw"></i></a> ';
            } elseif ($content['deleted'] == 0 && $content['published'] == 0) {
                $html .= '<a class="btn btn-xs btn-primary"  title="[%publish_resource%]" href="index.php?a=61&amp;id=[+id+]"><i class="fa fa-arrow-up  fa-fw"></i></a> ';
            } else {
                $html .= '<a class="btn btn-xs btn-warning"  title="[%unpublish_resource%]" href="index.php?a=62&amp;id=[+id+]"><i class="fa fa-arrow-down  fa-fw"></i></a> ';
            }
            
            $html .= '<button class="btn btn-xs btn-default btn-expand btn-action" title="[%resource_overview%]" data-toggle="collapse" data-target=".collapse[+id+]"><i class="fa fa-info" aria-hidden="true"></i></button>';
            
            $html .='</td></tr><tr><td colspan="6" class="hiddenRow"><div class="resource-overview-accordian collapse collapse[+id+]"><div class="overview-body small"><ul>';
            
            $html .= ' <li><b>[%long_title%]</b>: ';
            $html .= ($content['longtitle'] != '')     ? '[+longtitle+]'    : '(<i>[%not_set%]</i>)';
            $html .= '</li>';
            
            $html .= ' <li><b>[%description%]</b>: ';
            $html .= ($content['description'] != '')   ? '[+description+] ' : '(<i>[%not_set%]</i>)';
            $html .= '</li>';
            
            $html .= '<li><b>[%resource_summary%]</b>: ';
            $html .= ($content['introtext'] != '')     ? '[+introtext+] '   : '(<i>[%not_set%]</i>)';
            $html .= '</li>';
            
            $html .= '<li><b>[%type%]</b>: ';
            $html .= ($content['type'] != 'reference') ? '[%resource%]'     : '[%weblink%]';
            $html .= '</li>';
            
            $html .= '<li><b>[%resource_alias%]</b>: ';
            $html .=  ($content['alias'] != '')        ? '[+alias+]'        : '(<i>[%not_set%]</i>)';
            $html .= '</li>';
            
            $html .= '<li><b>[%page_data_cacheable%]</b>: '; 
            $html .= ($content['cacheable'] == 0)      ? '[%yes%]'          : '[%no%]';
            $html .= '</li>';
            
            $html .= '<li><b>[%resource_opt_show_menu%]</b>: ';
            $html .= ($content['hidemenu'] == 0)       ? '[%yes%]'          : '[%no%]';
            $html .= '</li>';
            
            $html .= '<li><b>[%page_data_template%]</b>: [+template:templatename+]</li>';
            $html .= '</ul></div></div></td></tr>';
            $html = $modx->parseText($html,$content);
        }
    }
    
    $html .= '</table></div>';
    return $html;
}
