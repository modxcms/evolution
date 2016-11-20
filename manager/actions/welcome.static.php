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
$modx->setPlaceholder('theme', $modx->config['manager_theme']);
$modx->setPlaceholder('home', $_lang["home"]);
$modx->setPlaceholder('logo_slogan', $_lang["logo_slogan"]);
$modx->setPlaceholder('site_name', $site_name);
$modx->setPlaceholder('welcome_title', $_lang['welcome_title']);
$modx->setPlaceholder('resetgrid', $_lang['reset']);
$modx->setPlaceholder('search', $_lang['search']);

// setup message info
if ($modx->hasPermission('messages')) {
    include_once MODX_MANAGER_PATH . 'includes/messageCount.inc.php';
    $_SESSION['nrtotalmessages'] = $nrtotalmessages;
    $_SESSION['nrnewmessages']   = $nrnewmessages;
    
    $msg = '<a href="index.php?a=10"><img src="' . $_style['icons_mail_large'] . '" /></a>
    <span style="color:#909090;font-size:15px;font-weight:bold">&nbsp;<a class="wm_messages_inbox_link" href="index.php?a=10">' . $_lang["inbox"] . '</a>' . ($_SESSION['nrnewmessages'] > 0 ? " (<span style='color:red'>" . $_SESSION['nrnewmessages'] . "</span>)" : "") . '</span><br />
    <span class="comment">' . sprintf($_lang["welcome_messages"], $_SESSION['nrtotalmessages'], "<span style='color:red;'>" . $_SESSION['nrnewmessages'] . "</span>") . '</span>';
    $modx->setPlaceholder('MessageInfo', $msg);
}

// setup icons
function wrapIcon($i)
{
    return '<span class="wm_button" style="border:0">' . $i . '</span>';
}
// setup icons
if($modx->hasPermission('new_user')||$modx->hasPermission('edit_user')) {
	$icon = '<a class="hometblink" href="index.php?a=75"><i class="'.$_style['icons_security_large'].'" alt="'.$_lang['user_management_title'].'"> </i><br />'.$_lang['security'].'</a>';
	$modx->setPlaceholder('SecurityIcon',wrapIcon($icon));
}
if($modx->hasPermission('new_web_user')||$modx->hasPermission('edit_web_user')) {
	$icon = '<a class="hometblink" href="index.php?a=99"><i class="'.$_style['icons_webusers_large'].'" alt="'.$_lang['web_user_management_title'].'"> </i><br />'.$_lang['web_users'].'</a>';
	$modx->setPlaceholder('WebUserIcon',wrapIcon($icon));
}
if($modx->hasPermission('new_module') || $modx->hasPermission('edit_module')) {
	$icon = '<a class="hometblink" href="index.php?a=106"><i class="'.$_style['icons_modules_large'].'" alt="'.$_lang['manage_modules'].'"> </i><br />'.$_lang['modules'].'</a>';
	$modx->setPlaceholder('ModulesIcon',wrapIcon($icon));
}
if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('manage_metatags')) {
	$icon = '<a class="hometblink" href="index.php?a=76"><i class="'.$_style['icons_resources_large'].'" alt="'.$_lang['element_management'].'"> </i><br />'.$_lang['elements'].'</a>';
	$modx->setPlaceholder('ResourcesIcon',wrapIcon($icon));
}
if($modx->hasPermission('bk_manager')) {
	$icon = '<a class="hometblink" href="index.php?a=93"><i class="'.$_style['icons_backup_large'].'" alt="'.$_lang['bk_manager'].'"> </i><br />'.$_lang['backup'].'</a>';
	$modx->setPlaceholder('BackupIcon',wrapIcon($icon));
}
if ($modx->hasPermission('help')) {
    $icon = '<a class="hometblink" href="index.php?a=9"><i class="'.$_style['icons_help_large'].'" alt="'. $_lang['help'].'" /> </i><br />' . $_lang['help'] . '</a>';
    $modx->setPlaceholder('HelpIcon', wrapIcon($icon));
}
// do some config checks
if (($modx->config['warning_visibility'] == 0 && $_SESSION['mgrRole'] == 1) || $modx->config['warning_visibility'] == 1) {
    include_once "config_check.inc.php";
    $modx->setPlaceholder('settings_config', $_lang['settings_config']);
    $modx->setPlaceholder('configcheck_title', $_lang['configcheck_title']);
    if ($config_check_results != $_lang['configcheck_ok']) {
        $modx->setPlaceholder('config_check_results', $config_check_results);
        $modx->setPlaceholder('config_display', 'block');
    } else {
        $modx->setPlaceholder('config_display', 'none');
    }
} else {
    $modx->setPlaceholder('config_display', 'none');
}

// include rss feeds for important forum topics
include_once "rss.inc.php";

// modx news
$modx->setPlaceholder('modx_news', $_lang["modx_news_tab"]);
$modx->setPlaceholder('modx_news_title', $_lang["modx_news_title"]);
$modx->setPlaceholder('modx_news_content', $feedData['modx_news_content']);

// security notices
$modx->setPlaceholder('modx_security_notices', $_lang["security_notices_tab"]);
$modx->setPlaceholder('modx_security_notices_title', $_lang["security_notices_title"]);
$modx->setPlaceholder('modx_security_notices_content', $feedData['modx_security_notices_content']);

// recent document info
$html  = '<div class="table-responsive">
<table class="table table-hover table-condensed">
<thead>
<tr>
<th style="width: 50px;">' . $_lang["id"] . '</th>
<th>' . $_lang["resource_title"] . '</th>
<th style="width: 140px;">' . $_lang['page_data_edited'] . '</th>
<th style="width: 180px;">' . $_lang["user"] . '</th>
<th style="width: 180px; text-align: right;">' . $_lang["mgrlog_action"] . '</th>
</tr>
</thead>';

$rs    = $modx->db->select('id, pagetitle, longtitle, introtext, alias, description, editedon, editedby, published, deleted, cacheable, template', $modx->getFullTableName('site_content'), "", 'editedon DESC', 10);

$limit = $modx->db->getRecordCount($rs);

if ($limit < 1) {
    $html .= '<tr><td>' . $_lang['no_activity_message'] . '</td></tr>';
} 

else {
    while ($content = $modx->db->getRow($rs)) {
        $editedby  = $content['editedby'];
        $editedbyu = $modx->getUserInfo($editedby);
        $html .= '<tr><td data-toggle="collapse" data-target=".collapse' . $content['id'] . '">
      
        <span class="label label-info">' . $content['id'] . '</span></td><td>';
        if ($content['deleted'] == 1) {
            $html .= '<a class="deleted" ';
        } else if ($content['published'] == 0) {
            $html .= '<a class="unpublished" ';
        } else {
            $html .= '<a class="published" ';
        }
        $html .= 'title="' . $_lang["edit_resource"] . '" href="index.php?a=3&amp;id=' . $content['id'] . '">' . $content['pagetitle'] . '</a>' . '</td><td data-toggle="collapse" data-target=".collapse' . $content['id'] . '">' . $modx->toDateFormat($content['editedon'] + $server_offset_time) . '</td><td data-toggle="collapse" data-target=".collapse' . $content['id'] . '">' . $editedbyu['username'] . '</td>
        <td style="text-align: right;">';
        
        if ($modx->hasPermission('edit_document')) {
            $html .= '<a class="btn btn-xs btn-success" title="' . $_lang["edit_resource"] . '" href="index.php?a=27&amp;id=' . $content['id'] . '"><i class="fa fa-edit fa-fw"></i></a> ';
        }
        
        if ($content['deleted'] == 1) {
            $html .= '<a class="btn btn-xs btn-info disabled"  title="' . $_lang["preview_resource"] . '" target="_blank" href="../index.php?&amp;id=' . $content['id'] . '"><i class="fa fa-eye fa-fw"></i></a> ';
        } else {
            $html .= '<a class="btn btn-xs btn-info"  title="' . $_lang["preview_resource"] . '" target="_blank" href="../index.php?&amp;id=' . $content['id'] . '"><i class="fa fa-eye fa-fw"></i></a> ';
        }
        if ($modx->hasPermission('delete_document')) {
            if ($content['deleted'] == 0) {
                $html .= '<a class="btn btn-xs btn-danger"  title="' . $_lang["delete_resource"] . '" href="index.php?a=6&amp;id=' . $content['id'] . '"><i class="fa fa-trash fa-fw"></i></a> ';
            } else {
                $html .= '<a class="btn btn-xs btn-success"  title="' . $_lang["undelete_resource"] . '" href="index.php?a=63&amp;id=' . $content['id'] . '"><i class="fa fa-arrow-circle-o-up fa-fw"></i></a> ';
            }
        }
        if ($content['deleted'] == "1" AND $content['published'] == 0) {
            $html .= '<a class="btn btn-xs btn-primary disabled"  title="' . $_lang["publish_resource"] . '" href="index.php?a=61&amp;id=' . $content['id'] . '"><i class="fa fa-arrow-up fa-fw"></i></a> ';
        } else if ($content['deleted'] == "1" AND $content['published'] == 1) {
            $html .= '<a class="btn btn-xs btn-primary disabled"  title="' . $_lang["publish_resource"] . '" href="index.php?a=61&amp;id=' . $content['id'] . '"><i class="fa fa-arrow-down fa-fw"></i></a> ';
        } else if ($content['deleted'] == "0" AND $content['published'] == 0) {
            $html .= '<a class="btn btn-xs btn-primary"  title="' . $_lang["publish_resource"] . '" href="index.php?a=61&amp;id=' . $content['id'] . '"><i class="fa fa-arrow-up  fa-fw"></i></a> ';
        } else {
            $html .= '<a class="btn btn-xs btn-warning"  title="' . $_lang["unpublish_resource"] . '" href="index.php?a=62&amp;id=' . $content['id'] . '"><i class="fa fa-arrow-down  fa-fw"></i></a> ';
        }
        $html .= '<button class="btn btn-xs btn-default btn-expand btn-action" title="' . $_lang["resource_overview"] . '" data-toggle="collapse" data-target=".collapse' . $content['id'] . '"><i class="fa fa-info" aria-hidden="true"></i></button></td></tr><tr><td colspan="6" class="hiddenRow"><div class="resource-overview-accordian collapse collapse' . $content['id'] . '"><div class="overview-body small"><ul>        
<li><b>' . $_lang["long_title"] . '</b>: ';
        if ($content['longtitle'] == "") {
            $html .= '(<i>'.$_lang['not_set'].'</i>)';
        } else { 
            $html .= '' . $content['longtitle'] . '</li> ';
        }
        $html .= ' <li><b>' . $_lang["description"] . '</b>: ';        
        if ($content['description'] == "") {
            $html .= '(<i>'.$_lang['not_set'].'</i>)';
        } else {         
            $html .= '' . $content['description'] . '</li> ';
        }
        $html .= '<li><b>' . $_lang["resource_summary"] . '</b>: ';
        if ($content['introtext'] == "") {
            $html .= '(<i>'.$_lang['not_set'].'</i>)';
        } else {   
            $html .= '' . $content['introtext'] . '</li> ';
        }
        $html .= '<li><b>' . $_lang['type'] . '</b>: ';
        if ($content['type'] == 'reference') {
            $html .= '' . $_lang['weblink'] . '</li>';
        } else {
            $html .= '' . $_lang['resource'] . '</li>';
        }
        $html .= '<li><b>' . $_lang["resource_alias"] . '</b>: ';
        if ($content['alias'] == "") {
            $html .= '(<i>'.$_lang['not_set'].'</i>)';
        } else { 
           $html .= '' . $content['alias'] . '</li>';
        }
        $html .= '<li><b>' . $_lang['page_data_cacheable'] . '</b>: '; 
        if ($content['cacheable'] == 0) {
            $html .= '' . $_lang['no'] . '<br/>';
        } else {
            $html .= '' . $_lang['yes'] . '</li>';
        }
        $html .= '<li><b>' . $_lang['resource_opt_show_menu'] . '</b>: ';
        if ($content['hidemenu'] == 0) {
            $html .= '' . $_lang['no'] . '<br/>';
        } else {
            $html .= '' . $_lang['yes'] . '</li>';
        }
        // Get Template name
        $rst          = $modx->db->select('templatename', $modx->getFullTableName('site_templates'), 'id=' . $content['template'] .'');
        $templatename = $modx->db->getValue($rst);
        $html .= '<li><b>' . $_lang['page_data_template'] . '</b>: ' . $templatename . '</li>';
        $html .= '</ul></div></div></td></tr>';
        
    }
}

$html .= '</table></div>';
$modx->setPlaceholder('recent_docs', $_lang['recent_docs']);
$modx->setPlaceholder('activity_title', $_lang['activity_title']);
$modx->setPlaceholder('RecentInfo', $html);

// user info
$modx->setPlaceholder('info', $_lang['info']);
$modx->setPlaceholder('yourinfo_title', $_lang['yourinfo_title']);
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
$modx->setPlaceholder('UserInfo', $html);

// online users
$modx->setPlaceholder('online', $_lang['online']);
$modx->setPlaceholder('onlineusers_title', $_lang['onlineusers_title']);
$timetocheck = (time() - (60 * 20)); //+$server_offset_time;

include_once "actionlist.inc.php";

$rs    = $modx->db->select('*', $modx->getFullTableName('active_users'), "lasthit>'{$timetocheck}'", 'username ASC');
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
        $currentaction = getAction($activeusers['action'], $activeusers['id']);
        $webicon       = ($activeusers['internalKey'] < 0) ? "<img src='" . $_style["tree_globe"] . "' alt='Web user' />" : "";
      $html .= "<tr><td><strong>" . $activeusers['username'] . "</strong></td><td>$webicon&nbsp;" . abs($activeusers['internalKey']) . "</td><td>" . $activeusers['ip'] . "</td><td>" . strftime('%H:%M:%S', $activeusers['lasthit'] + $server_offset_time) . "</td><td>$currentaction</td></tr>";
    }
    $html .= '
                </tbody>
                </table>
                </div>
        ';
}
$modx->setPlaceholder('OnlineInfo', $html);

// invoke event OnManagerWelcomePrerender
$evtOut = $modx->invokeEvent('OnManagerWelcomePrerender');
if (is_array($evtOut)) {
    $output = implode("", $evtOut);
    $modx->setPlaceholder('OnManagerWelcomePrerender', $output);
}

// invoke event OnManagerWelcomeHome
$evtOut = $modx->invokeEvent('OnManagerWelcomeHome');
if (is_array($evtOut)) {
    $output = implode("", $evtOut);
    $modx->setPlaceholder('OnManagerWelcomeHome', $output);
}

// invoke event OnManagerWelcomeRender
$evtOut = $modx->invokeEvent('OnManagerWelcomeRender');
if (is_array($evtOut)) {
    $output = implode("", $evtOut);
    $modx->setPlaceholder('OnManagerWelcomeRender', $output);
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
$welcome_tpl = $modx->mergePlaceholderContent($welcome_tpl);
$welcome_tpl = preg_replace('~\[\+(.*?)\+\]~', '', $welcome_tpl); //cleanup
if ($js = $modx->getRegisteredClientScripts()) {
    $welcome_tpl .= $js;
}

echo $welcome_tpl;
