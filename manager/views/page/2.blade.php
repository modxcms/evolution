@extends('manager::template.page')
@section('content')
    <?php /*include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/welcome.static.php");*/ ?>
    <?php
    unset($_SESSION['itemname']); // clear this, because it's only set for logging purposes

    if($modx->hasPermission('settings') && $modx->getConfig('settings_version') !== $modx->getVersionData('version')) {
        // seems to be a new install - send the user to the configuration page
        exit('<script type="text/javascript">document.location.href="index.php?a=17";</script>');
    }

    // set placeholders
    $ph = $_lang;

    $iconTpl = $modx->getChunk('manager#welcome\WrapIcon');
    // setup icons
    if($modx->hasPermission('new_user') || $modx->hasPermission('edit_user')) {
        $icon = '<i class="'. $_style['icon_user'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '" alt="[%user_management_title%]"> </i>[%user_management_title%]';
        $ph['SecurityIcon'] = sprintf($iconTpl,$icon, 75);
    }
    if($modx->hasPermission('new_user') || $modx->hasPermission('edit_user')) {
        $icon = '<i class="'. $_style['icon_web_user'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '" alt="[%web_user_management_title%]"> </i>[%web_user_management_title%]';
        $ph['WebUserIcon'] = sprintf($iconTpl,$icon, 99);
    }
    if($modx->hasPermission('new_module') || $modx->hasPermission('edit_module')) {
        $icon = '<i class="'. $_style['icon_modules'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '" alt="[%manage_modules%]"> </i>[%modules%]';
        $ph['ModulesIcon'] = sprintf($iconTpl,$icon, 106);
    }
    if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('manage_metatags')) {
        $icon = '<i class="'. $_style['icon_elements'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '" alt="[%element_management%]"> </i>[%elements%]';
        $ph['ResourcesIcon'] = sprintf($iconTpl,$icon, 76);
    }
    if($modx->hasPermission('bk_manager')) {
        $icon = '<i class="'. $_style['icon_database'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '" alt="[%bk_manager%]"> </i>[%backup%]';
        $ph['BackupIcon'] = sprintf($iconTpl,$icon, 93);
    }
    if($modx->hasPermission('help')) {
        $icon = '<i class="'. $_style['icon_question_circle'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '" alt="[%help%]" /> </i>[%help%]';
        $ph['HelpIcon'] = sprintf($iconTpl,$icon, 9);
    }

    if($modx->hasPermission('new_document')) {
        $icon = '<i class="'. $_style['icon_document'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>[%add_resource%]';
        $ph['ResourceIcon'] = sprintf($iconTpl,$icon, 4);
        $icon = '<i class="'. $_style['icon_chain'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>[%add_weblink%]';
        $ph['WeblinkIcon'] = sprintf($iconTpl,$icon, 72);
    }
    if($modx->hasPermission('assets_images')) {
        $icon = '<i class="'. $_style['icon_camera'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>[%images_management%]';
        $ph['ImagesIcon'] = sprintf($iconTpl,$icon, 72);
    }
    if($modx->hasPermission('assets_files')) {
        $icon = '<i class="'. $_style['icon_files'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>[%files_management%]';
        $ph['FilesIcon'] = sprintf($iconTpl,$icon, 72);
    }
    if($modx->hasPermission('change_password')) {
        $icon = '<i class="'. $_style['icon_lock'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>[%change_password%]';
        $ph['PasswordIcon'] = sprintf($iconTpl,$icon, 28);
    }
    $icon = '<i class="'. $_style['icon_logout'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>[%logout%]';
    $ph['LogoutIcon'] = sprintf($iconTpl,$icon, 8);

    // do some config checks
    if($modx->getConfig('warning_visibility') || $_SESSION['mgrRole'] == 1) {
        include_once(MODX_MANAGER_PATH . 'includes/config_check.inc.php');
        if($config_check_results != $_lang['configcheck_ok']) {
            $ph['config_check_results'] = $config_check_results;
            $ph['config_display'] = 'block';
        } else {
            $ph['config_display'] = 'none';
        }
    } else {
        $ph['config_display'] = 'none';
    }

    // Check logout-reminder
    if(isset($_SESSION['show_logout_reminder'])) {
        switch($_SESSION['show_logout_reminder']['type']) {
            case 'logout_reminder':
                $date = $modx->toDateFormat($_SESSION['show_logout_reminder']['lastHit'], 'dateOnly');
                $ph['logout_reminder_msg'] = str_replace('[+date+]', $date, $_lang['logout_reminder_msg']);
                break;
        }
        $ph['show_logout_reminder'] = 'block';
        unset($_SESSION['show_logout_reminder']);
    } else {
        $ph['show_logout_reminder'] = 'none';
    }

    // Check multiple sessions

    $ph['show_multiple_sessions'] = 'none';

    $ph['RecentInfo'] = $modx->getChunk('manager#welcome\RecentInfo');

    $tpl = '
<table class="table data">
	<tr>
		<td width="150">[%yourinfo_username%]</td>
		<td><b>[+username+]</b></td>
	</tr>
	<tr>
		<td>[%yourinfo_role%]</td>
		<td><b>[+role+]</b></td>
	</tr>
	<tr>
		<td>[%yourinfo_previous_login%]</td>
		<td><b>[+lastlogin+]</b></td>
	</tr>
	<tr>
		<td>[%yourinfo_total_logins%]</td>
		<td><b>[+logincount+]</b></td>
	</tr>
	<tr>
		<td>[%inbox%]</td>
		<td><a href="index.php?a=10" target="main"><b>[+msginfo+]</b></a></td>
	</tr>
</table>';

    $ph['UserInfo'] = $modx->parseText($tpl, array(
        'username' => $modx->getLoginUserName(),
        'role' => $_SESSION['mgrPermissions']['name'],
        'lastlogin' => $modx->toDateFormat($modx->timestamp($_SESSION['mgrLastlogin'])),
        'logincount' => $_SESSION['mgrLogincount'] + 1,
    ));

    $activeUsers = \EvolutionCMS\Models\ActiveUserSession::query()
        ->join('active_users', 'active_users.sid', '=', 'active_user_sessions.sid')
        ->where('active_users.action', '<>', 8)
        ->orderBy('username', 'ASC')
        ->orderBy('active_users.sid', 'ASC');
    if($activeUsers->count() < 1) {
        $html = '<p>[%no_active_users_found%]</p>';
    } else {
        $now = $modx->timestamp($_SERVER['REQUEST_TIME']);
        if (extension_loaded('intl')) {
            // https://www.php.net/manual/en/class.intldateformatter.php
            // https://www.php.net/manual/en/datetime.createfromformat.php
            $formatter = new IntlDateFormatter(
                evolutionCMS()->getConfig('manager_language'),
                IntlDateFormatter::MEDIUM,
                IntlDateFormatter::MEDIUM,
                null,
                null,
                "hh:mm:ss"
            );
            $ph['now'] = $formatter->format($now);
        } else {
            $ph['now'] = strftime('%H:%M:%S', $now);
        }
        $timetocheck = ($now - (60 * 20)); //+$server_offset_time;
        $html = '
	<div class="card-body">
		[%onlineusers_message%]
		<b>[+now+]</b>):
	</div>
	<div class="table-responsive">
	<table class="table data">
	<thead>
		<tr>
			<th>[%onlineusers_user%]</th>
			<th>ID</th>
			<th>[%onlineusers_ipaddress%]</th>
			<th>[%onlineusers_lasthit%]</th>
			<th>[%onlineusers_action%]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		</tr>
	</thead>
	<tbody>';

        $userList = array();
        $userCount = array();
        // Create userlist with session-count first before output
        foreach($activeUsers->get()->toArray() as $activeUser) {
            $userCount[$activeUser['internalKey']] = isset($userCount[$activeUser['internalKey']]) ? $userCount[$activeUser['internalKey']] + 1 : 1;

            $idle = $activeUser['lasthit'] < $timetocheck ? ' class="userIdle"' : '';
            $webicon = $activeUser['internalKey'] < 0 ? '<i class="[&icon_globe&]"></i>' : '';
            $ip = $activeUser['ip'] === '::1' ? '127.0.0.1' : $activeUser['ip'];
            $currentaction = EvolutionCMS\Legacy\LogHandler::getAction($activeUser['action'], $activeUser['id']);
            if (extension_loaded('intl')) {
                // https://www.php.net/manual/en/class.intldateformatter.php
                // https://www.php.net/manual/en/datetime.createfromformat.php
                $formatter = new IntlDateFormatter(
                    evolutionCMS()->getConfig('manager_language'),
                    IntlDateFormatter::MEDIUM,
                    IntlDateFormatter::MEDIUM,
                    null,
                    null,
                    "hh:mm:ss"
                );
                $lasthit = $formatter->format($modx->timestamp($activeUser['lasthit']));
            } else {
                $lasthit = strftime('%H:%M:%S', $modx->timestamp($activeUser['lasthit']));
            }
            $userList[] = array(
                $idle,
                '',
                $activeUser['username'],
                $webicon,
                abs($activeUser['internalKey']),
                $ip,
                $lasthit,
                $currentaction
            );
        }
        foreach($userList as $params) {
            $params[1] = $userCount[$params[4]] > 1 ? ' class="userMultipleSessions"' : '';
            $html .= "\n\t\t" . vsprintf('<tr%s><td><strong%s>%s</strong></td><td>%s%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $params);
        }

        $html .= '
	</tbody>
	</table>
</div>
';
    }
    $ph['OnlineInfo'] = $html;

    // include rss feeds for important forum topics
    // Here you can set the urls to retrieve the RSS from. Simply add a $urls line following the numbering progress in the square brakets.

    $urls['modx_news_content'] = $modx->getConfig('rss_url_news');
    $urls['modx_security_notices_content'] = $modx->getConfig('rss_url_security');

    // How many items per Feed?
    $itemsNumber = '3';

    $feedData = array();

    // create Feed
    foreach ($urls as $section => $url) {
        $output = '';
        $items = fetchCacheableRss($url, 'channel/item', function(SimpleXMLElement $entry){
            $props = [];
            foreach ($entry as $prop) {
                if (mb_strtolower($prop->getName()) === 'pubdate' && ($time = @strtotime($prop->__toString())) > 0) {
                    $props['date_timestamp'] = $time;
                    $props['pubdate'] = $prop->__toString();
                } else {
                    $props[$prop->getName()] = $prop->__toString();
                }
            }

            return $props;
        });
        if (empty($items)) {
            $feedData[$section] = 'Failed to retrieve ' . $url;
            continue;
        }
        $output .= '<ul>';

        $items = array_slice($items, 0, $itemsNumber);
        foreach ($items as $item) {
            $href = rel2abs($item['link'], 'https://github.com');
            $title = $item['title'];
            $pubdate = $item['pubdate'];
            $pubdate = $modx->toDateFormat(strtotime($pubdate));
            $description = strip_tags($item['description']);
            if (strlen($description) > 199) {
                $description = substr($description, 0, 200);
                $description .= '...<br />Read <a href="' . $href . '" target="_blank">more</a>.';
            }
            $output .= '<li><a href="' . $href . '" target="_blank">' . $title . '</a> - <b>' . $pubdate . '</b><br />' . $description . '</li>';
        }

        $output .= '</ul>';
        $feedData[$section] = $output;
    }
    $ph['modx_security_notices_content'] = $feedData['modx_security_notices_content'];
    $ph['modx_news_content'] = $feedData['modx_news_content'];

    $ph['theme'] = $modx->getConfig('manager_theme');
    $ph['site_name'] = $modx->getPhpCompat()->entities($modx->getConfig('site_name'));
    $ph['home'] = $_lang['home'];
    $ph['logo_slogan'] = $_lang['logo_slogan'];
    $ph['welcome_title'] = $_lang['welcome_title'];
    $ph['search'] = $_lang['search'];
    $ph['settings_config'] = $_lang['settings_config'];
    $ph['configcheck_title'] = $_lang['configcheck_title'];
    $ph['online'] = $_lang['online'];
    $ph['onlineusers_title'] = $_lang['onlineusers_title'];
    $ph['recent_docs'] = $_lang['recent_docs'];
    $ph['activity_title'] = $_lang['activity_title'];
    $ph['info'] = $_lang['info'];
    $ph['yourinfo_title'] = $_lang['yourinfo_title'];

    $ph['modx_security_notices'] = $_lang['security_notices_tab'];
    $ph['modx_security_notices_title'] = $_lang['security_notices_title'];
    $ph['modx_news'] = $_lang['modx_news_tab'];
    $ph['modx_news_title'] = $_lang['modx_news_title'];

    $modx->toPlaceholders($ph);

    $script = $modx->getChunk('manager#welcome\StartUpScript');
    $modx->regClientScript($script);

    // invoke event OnManagerWelcomePrerender
    $evtOut = $modx->invokeEvent('OnManagerWelcomePrerender');
    if(is_array($evtOut)) {
        $output = implode('', $evtOut);
        $ph['OnManagerWelcomePrerender'] = $output;
    }

    $widgets['welcome'] = array(
        'menuindex' => '10',
        'id' => 'welcome',
        'cols' => 'col-lg-6',
        'icon' => 'fa-home',
        'title' => '[%welcome_title%]',
        'body' => '
            <div class="wm_buttons card-body">' .
            ($modx->hasPermission("new_document") ? '
                <span class="wm_button">
                    <a target="main" href="index.php?a=4">
                        <i class="'. $_style['icon_document'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>
                        <span>[%add_resource%]</span>
                    </a>
                </span>
                <span class="wm_button">
                    <a target="main" href="index.php?a=72">
                        <i class="'. $_style['icon_chain'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>
                        <span>[%add_weblink%]</span>
                    </a>
                </span>
                ' : '') .
            ($modx->hasPermission("assets_images") ? '
                <span class="wm_button">
                    <a target="main" href="media/browser/mcpuk/browse.php?filemanager=media/browser/mcpuk/browse.php&type=images">
                        <i class="'. $_style['icon_camera'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>
                        <span>[%images_management%]</span>
                    </a>
                </span>
                ' : '') .
            ($modx->hasPermission("assets_files") ? '
                <span class="wm_button">
                    <a target="main" href="media/browser/mcpuk/browse.php?filemanager=media/browser/mcpuk/browse.php&type=files">
                        <i class="'. $_style['icon_files'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>
                        <span>[%files_management%]</span>
                    </a>
                </span>
                ' : '') .
            ($modx->hasPermission("bk_manager") ? '
                <span class="wm_button">
                    <a target="main" href="index.php?a=93">
                        <i class="'. $_style['icon_database'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>
                        <span>[%bk_manager%]</span>
                    </a>
                </span>
                ' : '') .
            ($modx->hasPermission("change_password") ? '
                <span class="wm_button">
                    <a target="main" href="index.php?a=28">
                        <i class="'. $_style['icon_lock'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>
                        <span>[%change_password%]</span>
                    </a>
                </span>
                ' : '') . '
                <span class="wm_button">
                    <a target="_top" href="index.php?a=8">
                        <i class="'. $_style['icon_logout'] . $_style['icon_size_2x'] . $_style['icon_size_fix'] . '"></i>
                        <span>[%logout%]</span>
                    </a>
                </span>
            </div>
            <div class="userprofiletable card-body">
                <table>
                    <tr>
                        <td width="150">[%yourinfo_username%]</td>
                        <td><b>' . $modx->getLoginUserName() . '</b></td>
                    </tr>
                    <tr>
                        <td>[%yourinfo_role%]</td>
                        <td><b>[[$_SESSION[\'mgrPermissions\'][\'name\'] ]]</b></td>
                    </tr>
                    <tr>
                        <td>[%yourinfo_previous_login%]</td>
                        <td><b>[[$_SESSION[\'mgrLastlogin\']:math(\'%s+[(server_offset_time)]\'):dateFormat]]</b></td>
                    </tr>
                    <tr>
                        <td>[%yourinfo_total_logins%]</td>
                        <td><b>[[$_SESSION[\'mgrLogincount\']:math(\'%s+1\')]]</b></td>
                    </tr>' .
            ($modx->hasPermission("change_password") ? '

                    ' : '') . '
                </table>
            </div>
		',
        'hide'=>'0'
    );
    $widgets['onlineinfo'] = array(
        'menuindex' => '20',
        'id' => 'onlineinfo',
        'cols' => 'col-lg-6',
        'icon' => 'fa-user',
        'title' => '[%onlineusers_title%]',
        'body' => '<div class="userstable">[+OnlineInfo+]</div>',
        'hide'=>'0'
    );
    $widgets['recentinfo'] = array(
        'menuindex' => '30',
        'id' => 'modxrecent_widget',
        'cols' => 'col-sm-12',
        'icon' => 'fa-pencil-square-o',
        'title' => '[%activity_title%]',
        'body' => '<div class="widget-stage">[+RecentInfo+]</div>',
        'hide'=>'0'
    );
    if ($modx->getConfig('rss_url_news')) {
        $widgets['news'] = array(
            'menuindex' => '40',
            'id' => 'news',
            'cols' => 'col-sm-6',
            'icon' => 'fa-rss',
            'title' => '[%modx_news_title%]',
            'body' => '<div style="max-height:200px;overflow-y: scroll;padding: 1rem .5rem">[+modx_news_content+]</div>',
            'hide'=>'0'
        );
    }
    if ($modx->getConfig('rss_url_security')) {
        $widgets['security'] = array(
            'menuindex' => '50',
            'id' => 'security',
            'cols' => 'col-sm-6',
            'icon' => 'fa-exclamation-triangle',
            'title' => '[%security_notices_title%]',
            'body' => '<div style="max-height:200px;overflow-y: scroll;padding: 1rem .5rem">[+modx_security_notices_content+]</div>',
            'hide'=>'0'
        );
    }

    // invoke OnManagerWelcomeHome event
    $sitewidgets = $modx->invokeEvent("OnManagerWelcomeHome", array('widgets' => $widgets));
    if(is_array($sitewidgets)) {
        $newwidgets = array();
        foreach($sitewidgets as $widget){
            $newwidgets = array_merge($newwidgets, unserialize($widget));
        }
        $widgets = (count($newwidgets) > 0) ? $newwidgets : $widgets;
    }

    usort($widgets, function ($a, $b) {
        return $a['menuindex'] - $b['menuindex'];
    });

    $tpl = $modx->getChunk('manager#welcome\Widget');
    $output = '';
    foreach($widgets as $widget) {
        if ((bool)get_by_key($widget, 'hide', false) !== true) {
            $output .= $modx->parseText($tpl, $widget);
        }
    }
    $ph['widgets'] = $output;
    ?>
    {!! ManagerTheme::makeTemplate('welcome', 'manager_welcome_tpl', $ph, false) !!}
@endsection
