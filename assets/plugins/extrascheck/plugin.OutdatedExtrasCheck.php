<?php
if (!defined('MODX_BASE_PATH')) {
    die('What are you doing? Get out of here!');
}

// get manager role check
$internalKey = $modx->getLoginUserID();
$sid = $modx->sid;
$role = (int)$_SESSION['mgrRole'];
$user = $_SESSION['mgrShortname'];

switch (true)
{
    case ($role !== 1 && $wdgVisibility === 'AdminOnly'):
        // show widget only to Admin role 1
        break;
    case ($role === 1 && $wdgVisibility === 'AdminExcluded'):
        // show widget to all manager users excluded Admin role 1
        break;
    case ($role != $ThisRole && $wdgVisibility === 'ThisRoleOnly'):
        // show widget only to "this" role id
        break;
    case ($user != $ThisUser && $wdgVisibility === 'ThisUserOnly'):
        // show widget only to "this" username
        break;
    default: // get plugin id and setting button
        //run the plugin
        //function to extract snippet version from description <strong></strong> tags
        if (!function_exists('getver')) {
            function getver($string, $tag)
            {
                $content = "/<$tag>(.*?)<\/$tag>/";
                preg_match($content, $string, $text);

                return $text[1];
            }
        }

        $pluginId = $modx->db->getValue($modx->db->select(
            'id',
            $this->getFullTableName('site_plugins'),
            "name='{$modx->event->activePlugin}' AND disabled=0"
        ));
        if ($modx->hasPermission('edit_plugin')) {
            $button_pl_config = '<a data-toggle="tooltip" href="javascript:;" title="' . $_lang["settings_config"] . '" class="text-muted pull-right" onclick="parent.modx.popup({url:\'' . MODX_MANAGER_URL . '?a=102&id=' . $pluginId . '&tab=1\',title1:\'' . $_lang["settings_config"] . '\',icon:\'fa-cog\',iframe:\'iframe\',selector2:\'#tabConfig\',position:\'center center\',width:\'80%\',height:\'80%\',hide:0,hover:0,overlay:1,overlayclose:1})" ><i class="fa fa-cog fa-spin-hover" style="color:#FFFFFF;"></i> </a>';
        }
        $modx->setPlaceholder('button_pl_config', $button_pl_config);

        //plugin lang
        $_oec_lang = array();
        $plugin_path = __DIR__;
        include $plugin_path . 'lang/english.php';
        if (file_exists($plugin_path . 'lang/' . $modx->getConfig('manager_language') . '.php')) {
            include $plugin_path . 'lang/' . $modx->getConfig('manager_language') . '.php';
        }

        $e = &$modx->Event;
        $EVOversion = $modx->getConfig('settings_version');
        $output = '';
        //get extras module id for the link
        $ExtrasID = $modx->db->getValue(
            $modx->db->select('id', $modx->getFullTableName('site_modules'), "name='Extras'")
        );

        //check outdated files
        //ajax index
        $indexajax = MODX_BASE_PATH . 'index-ajax.php';
        if (file_exists($indexajax)) {
            $output .= '<div class="widget-wrapper alert alert-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>index-ajax.php</b> ' . $_oec_lang['not_used'] . ' <b>Evolution ' . $EVOversion . '</b>.  ' . $_oec_lang['if_dont_use'] . ', ' . $_oec_lang['please_delete'] . '.</div>';
        }

        //check outdated default manager themes
        $oldthemes = explode(',', $badthemes);
        foreach ($oldthemes as $oldtheme) {
            if (file_exists('media/style/' . $oldtheme)) {
                $output .= '<div class="widget-wrapper alert alert-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $oldtheme . '</b> ' . $_lang["manager_theme"] . ',  ' . $_oec_lang['isoutdated'] . ' ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>.   ' . $_oec_lang['please_delete'] . ' ' . $_oec_lang['from_folder'] . ' ' . MODX_MANAGER_PATH . 'media/style/.</div>';
            }
        }

        //check outdated modx rss news feed
        $url_security = $modx->getConfig('rss_url_security');
        if ($url_security == 'http://feeds.feedburner.com/modxsecurity') {
            $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $_lang["settings_config"] . ' > ' . $_lang["rss_url_security_title"] . '</b> (http://feeds.feedburner.com/modxsecurity) ' . $_oec_lang['outdated'] . '. ' . $_oec_lang['please_download_and_install'] . ' <b>UpdateEvoRss</b>  ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a> </div>';
        }

        $url_news = $modx->getConfig('rss_url_news');
        if ($url_news == 'http://feeds.feedburner.com/modx-announce') {
            $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $_lang["settings_config"] . ' > ' . $_lang["rss_url_news_title"] . ' </b>(http://feeds.feedburner.com/modx-announce) ' . $_oec_lang['outdated'] . '. ' . $_oec_lang['please_download_and_install'] . ' <b>UpdateEvoRss</b>  ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a> </div>';
        }

        //get site snippets table
        $table = $modx->getFullTableName('site_snippets');
        //check ditto
        //get min version from config
        $minDittoVersion = $DittoVersion;
        //search the snippet by name
        $CheckDitto = $modx->db->select("id, name, description", $table, "name='Ditto'");
        if ($CheckDitto != '') {
            while ($row = $modx->db->getRow($CheckDitto)) {
                //extract snippet version from description <strong></strong> tags
                $curr_ditto_version = getver($row['description'], "strong");
                //check snippet version and return an alert if outdated
                if (version_compare($curr_ditto_version, $minDittoVersion, 'lt')) {
                    $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> ' . $_lang["snippet"] . ' (version ' . $curr_ditto_version . ') ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>. ' . $_oec_lang['please_update'] . ' <b>' . $row['name'] . '</b> ' . $_oec_lang["to_latest"] . ' (' . $_oec_lang['min _required'] . ' ' . $minDittoVersion . ') ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a> ' . $_oec_lang['or_move_to'] . ' <b>DocLister</b></div>';
                }
            }
        }
        //end check ditto

        //check eform
        //get min version from config
        $minEformVersion = $EformVersion;
        //search the snippet by name
        $CheckEform = $modx->db->select("id, name, description", $table, "name='eForm'");
        if ($CheckEform != '') {
            while ($row = $modx->db->getRow($CheckEform)) {
                //extract snippet version from description <strong></strong> tags
                $curr_Eform_version = getver($row['description'], "strong");
                //check snippet version and return an alert if outdated
                if (version_compare($curr_Eform_version, $minEformVersion, 'lt')) {
                    $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> ' . $_lang["snippet"] . ' (version ' . $curr_Eform_version . ') ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>. ' . $_oec_lang['please_update'] . ' <b>' . $row['name'] . '</b> ' . $_oec_lang["to_latest"] . ' (' . $_oec_lang['min _required'] . ' ' . $minEformVersion . ') ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a> ' . $_oec_lang['or_move_to'] . ' <b>FormLister</b></div>';
                }
            }
        }
        //end check eform

        //check AjaxSearch
        //get min version from config
        $minAjaxSearchVersion = $AjaxSearchVersion;
        //search the snippet by name
        $CheckAjaxSearch = $modx->db->select("id, name, description", $table, "name='AjaxSearch'");
        if ($CheckAjaxSearch != '') {
            while ($row = $modx->db->getRow($CheckAjaxSearch)) {
                //extract snippet version from description <strong></strong> tags
                $curr_AjaxSearch_version = getver($row['description'], "strong");
                //check snippet version and return an alert if outdated
                if (version_compare($curr_AjaxSearch_version, $minAjaxSearchVersion, 'lt')) {
                    $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> ' . $_lang["snippet"] . ' (version ' . $curr_AjaxSearch_version . ') ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>. ' . $_oec_lang['please_update'] . ' <b>' . $row['name'] . '</b> ' . $_oec_lang["to_latest"] . ' (' . $_oec_lang['min _required'] . ' ' . $minAjaxSearchVersion . ') ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a>.</div>';
                }
            }
        }
        //end check AjaxSearch

        //check Wayfinder
        //get min version from config
        $minWayfinderVersion = $WayfinderVersion;
        //search the snippet by name
        $CheckWayfinder = $modx->db->select("id, name, description", $table, "name='Wayfinder'");
        if ($CheckWayfinder != '') {
            while ($row = $modx->db->getRow($CheckWayfinder)) {
                //extract snippet version from description <strong></strong> tags
                $curr_Wayfinder_version = getver($row['description'], "strong");
                //check snippet version and return an alert if outdated
                if (version_compare($curr_Wayfinder_version, $minWayfinderVersion, 'lt')) {
                    $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> ' . $_lang["snippet"] . ' (version ' . $curr_Wayfinder_version . ') ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>. ' . $_oec_lang['please_update'] . ' <b>' . $row['name'] . '</b> ' . $_oec_lang["to_latest"] . ' (' . $_oec_lang['min _required'] . ' ' . $minWayfinderVersion . ') ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a>.</div>';
                }
            }
        }
        //end check Wayfinder

        //check WebLogin
        //get min version from config
        $minWebLoginVersion = $WebLoginVersion;
        //search the snippet by name
        $CheckWebLogin = $modx->db->select("id, name, description", $table, "name='WebLogin'");
        if ($CheckWebLogin != '') {
            while ($row = $modx->db->getRow($CheckWebLogin)) {
                //extract snippet version from description <strong></strong> tags
                $curr_WebLogin_version = getver($row['description'], "strong");
                //check snippet version and return an alert if outdated
                if (version_compare($curr_WebLogin_version, $minWebLoginVersion, 'lt')) {
                    $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> ' . $_lang["snippet"] . ' (version ' . $curr_WebLogin_version . ') ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>. ' . $_oec_lang['please_update'] . ' <b>' . $row['name'] . '</b> ' . $_oec_lang["to_latest"] . ' (' . $_oec_lang['min _required'] . ' ' . $minWebLoginVersion . ') ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a> ' . $_oec_lang['or_move_to'] . ' <b>FormLister</b></div>';
                }
            }
        }
        //end check WebLogin

        //check WebChangePwd
        //get min version from config
        $minWebChangePwdVersion = $WebChangePwdVersion;
        //search the snippet by name
        $CheckWebChangePwd = $modx->db->select("id, name, description", $table, "name='WebChangePwd'");
        if ($CheckWebLogin != '') {
            while ($row = $modx->db->getRow($CheckWebChangePwd)) {
                //extract snippet version from description <strong></strong> tags
                $curr_WebChangePwd_version = getver($row['description'], "strong");
                //check snippet version and return an alert if outdated
                if (version_compare($curr_WebChangePwd_version, $minWebChangePwdVersion, 'lt')) {
                    $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> ' . $_lang["snippet"] . ' (version ' . $curr_WebChangePwd_version . ') ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>. ' . $_oec_lang['please_update'] . ' <b>' . $row['name'] . '</b> ' . $_oec_lang["to_latest"] . ' (' . $_oec_lang['min _required'] . ' ' . $minWebChangePwdVersion . ') ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a> ' . $_oec_lang['or_move_to'] . ' <b>FormLister</b></div>';
                }
            }
        }
        //end check WebChangePwd

        //check WebSignup
        //get min version from config
        $minWebSignupVersion = $WebSignupVersion;
        //search the snippet by name
        $CheckWebSignup = $modx->db->select("id, name, description", $table, "name='WebSignup'");
        if ($CheckWebSignup != '') {
            while ($row = $modx->db->getRow($CheckWebSignup)) {
                //extract snippet version from description <strong></strong> tags
                $curr_WebSignup_version = getver($row['description'], "strong");
                //check snippet version and return an alert if outdated
                if (version_compare($curr_WebSignup_version, $minWebSignupVersion, 'lt')) {
                    $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> ' . $_lang["snippet"] . ' (version ' . $curr_WebSignup_version . ') ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>. ' . $_oec_lang['please_update'] . ' <b>' . $row['name'] . '</b> ' . $_oec_lang["to_latest"] . ' (' . $_oec_lang['min _required'] . ' ' . $minWebSignupVersion . ') ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a> ' . $_oec_lang['or_move_to'] . ' <b>FormLister</b></div>';
                }
            }
        }
        //end check WebSignup

        //check Breadcrumbs
        //get min version from config
        $minBreadcrumbsVersion = $BreadcrumbsVersion;
        //search the snippet by name
        $CheckBreadcrumbs = $modx->db->select("id, name, description", $table, "name='Breadcrumbs'");
        if ($CheckBreadcrumbs != '') {
            while ($row = $modx->db->getRow($CheckBreadcrumbs)) {
                //extract snippet version from description <strong></strong> tags
                $curr_Breadcrumbs_version = getver($row['description'], "strong");
                //check snippet version and return an alert if outdated
                if (version_compare($curr_Breadcrumbs_version, $minBreadcrumbsVersion, 'lt')) {
                    $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> ' . $_lang["snippet"] . ' (version ' . $curr_Breadcrumbs_version . ') ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>. ' . $_oec_lang['please_update'] . ' <b>' . $row['name'] . '</b> ' . $_oec_lang["to_latest"] . ' (' . $_oec_lang['min _required'] . ' ' . $minBreadcrumbsVersion . ') ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a>.</div>';
                }
            }
        }
        //end check Breadcrumbs

        //check Reflect
        //get min version from config
        $minReflectVersion = $ReflectVersion;
        //search the snippet by name
        $CheckReflect = $modx->db->select("id, name, description", $table, "name='Reflect'");
        if ($CheckReflect != '') {
            while ($row = $modx->db->getRow($CheckReflect)) {
                //extract snippet version from description <strong></strong> tags
                $curr_Reflect_version = getver($row['description'], "strong");
                //check snippet version and return an alert if outdated
                if (version_compare($curr_Reflect_version, $minReflectVersion, 'lt')) {
                    $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> ' . $_lang["snippet"] . ' (version ' . $curr_Reflect_version . ') ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>. ' . $_oec_lang['please_update'] . ' <b>' . $row['name'] . '</b> ' . $_oec_lang["to_latest"] . ' (' . $_oec_lang['min _required'] . ' ' . $minReflectVersion . ') ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a>.</div>';
                }
            }
        }
        //end check Reflect

        //check Jot
        //get min version from config
        $minJotVersion = $JotVersion;
        //search the snippet by name
        $CheckJot = $modx->db->select("id, name, description", $table, "name='Jot'");
        if ($CheckJot != '') {
            while ($row = $modx->db->getRow($CheckJot)) {
                //extract snippet version from description <strong></strong> tags
                $curr_Jot_version = getver($row['description'], "strong");
                //check snippet version and return an alert if outdated
                if (version_compare($curr_Jot_version, $minJotVersion, 'lt')) {
                    $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> ' . $_lang["snippet"] . ' (version ' . $curr_Jot_version . ') ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>. ' . $_oec_lang['please_update'] . ' <b>' . $row['name'] . '</b> ' . $_oec_lang["to_latest"] . ' (' . $_oec_lang['min _required'] . ' ' . $minJotVersion . ') ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a>.</div>';
                }
            }
        }
        //end check Jot

        //check Multitv
        //get min version from config
        $minMtvVersion = $MtvVersion;
        //search the snippet by name
        $CheckMtv = $modx->db->select("id, name, description", $table, "name='multiTV'");
        if ($CheckMtv != '') {
            while ($row = $modx->db->getRow($CheckMtv)) {
                //extract snippet version from description <strong></strong> tags
                $curr_mtv_version = getver($row['description'], "strong");
                //check snippet version and return an alert if outdated
                if (version_compare($curr_mtv_version, $minMtvVersion, 'lt')) {
                    $output .= '<div class="widget-wrapper alert alert-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>' . $row['name'] . '</b> ' . $_lang["snippet"] . ' (version ' . $curr_mtv_version . ') ' . $_oec_lang['isoutdated'] . ' <b>Evolution ' . $EVOversion . '</b>. ' . $_oec_lang['please_update'] . ' <b>' . $row['name'] . '</b> ' . $_oec_lang["to_latest"] . ' (' . $_oec_lang['min _required'] . ' ' . $minMtvVersion . ') ' . $_oec_lang['from'] . ' <a target="main" href="index.php?a=112&id=' . $ExtrasID . '">' . $_oec_lang['extras_module'] . '</a></div>';
                }
            }
        }
        //end check Multitv

        if ($output != '') {
            if ($e->name == 'OnManagerWelcomeHome') {
                $out = $output;
                $wdgTitle = 'EVO ' . $EVOversion . ' - ' . $_oec_lang['title'] . '';
                $widgets['xtraCheck'] = array(
                    'menuindex' => '0',
                    'id'        => 'xtraCheck' . $pluginId . '',
                    'cols'      => 'col-12',
                    'headAttr'  => 'style="color:#E84B39;"',
                    'bodyAttr'  => '',
                    'icon'      => 'fa-warning',
                    'title'     => '' . $wdgTitle . ' ' . $button_pl_config . '',
                    'body'      => '<div class="card-body">' . $out . '</div>',
                    'hide'      => '0'
                );
                $e->output(serialize($widgets));

                return;
            }
        }
        break;
}
