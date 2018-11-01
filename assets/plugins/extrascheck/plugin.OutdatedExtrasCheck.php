<?php
if (!defined('MODX_BASE_PATH')) {
    die('What are you doing? Get out of here!');
}
/** @var DocumentParser $modx */

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

        if (!class_exists('CheckOutdated')) {
            class CheckOutdated
            {
                /** @var DocumentParser */
                protected $modx;
                protected $extrasId;

                public function __construct($modx)
                {
                    $this->modx = $modx;
                    $this->extrasId = $this->findExtrasId();
                }

                public function snippet($name, $minVersion, $message)
                {
                    $out = '';
                    //check ditto
                    //get min version from config
                    //search the snippet by name
                    $query = $this->modx->db->select(
                        '`id`, `name`, `description`',
                        $this->modx->getFullTableName('site_snippets'),
                        "`name`='" . $this->modx->db->escape($name). "'"
                    );
                    if ($query !== false) {
                        while ($row = $this->modx->db->getRow($query)) {
                            //extract snippet version from description <strong></strong> tags
                            $currentVersion = getver($row['description'], 'strong');
                            //check snippet version and return an alert if outdated
                            if (version_compare($currentVersion, $minVersion, 'lt')) {
                                $out .= $this->modx->parseChunk(
                                    $message,
                                    array(
                                        'name' => $row['name'],
                                        'minVersion' => $minVersion,
                                        'currentVersion' => $currentVersion,
                                        'ExtrasID' => $this->getExtrasId()
                                    ),
                                    '[+',
                                    '+]'
                                );
                            }
                        }
                    }

                    return $out;
                }

                public function getExtrasId()
                {
                    return $this->extrasId;
                }

                public function findExtrasId()
                {
                    return (int)$this->modx->db->getValue(
                        $this->modx->db->select('id', $this->modx->getFullTableName('site_modules'), "name='Extras'")
                    );
                }
            }
        }

        $checkOutdated = new CheckOutdated($modx);
        global $_lang;

        $query = $modx->db->query(
            'SELECT id FROM ' . $this->getFullTableName('site_plugins') . ' WHERE ' .
            "`name`='" . $modx->db->escape($modx->event->activePlugin) ."' AND disabled=0"
        );
        $pluginId = $modx->db->getValue($query);
        if ($modx->hasPermission('edit_plugin')) {
            $popup = array(
                'url' => MODX_MANAGER_URL . '?a=102&id=' . $pluginId . '&tab=1',
                'title1' => $_lang['settings_config'],
                'icon' => 'fa-cog',
                'iframe' => 'iframe',
                'selector2' => '#tabConfig',
                'position' => 'center',
                'width' => '80%',
                'height' => '80%',
                'hide' => 0,
                'hover' => 0,
                'overlay' => 1,
                'overlayclose' => 1
            );

            $button_pl_config = '<a ' .
                'data-toggle="tooltip" ' .
                'href="javascript:;" '.
                'title="' .$_lang["settings_config"] . '" ' .
                'class="text-muted pull-right" ' .
                'onclick="parent.modx.popup(' .
                    str_replace('"', "'", stripslashes(json_encode($popup))) .
                ')" ' .
            '>' .
                '<i class="fa fa-cog fa-spin-hover"></i> ' .
            '</a>';
        } else {
            $button_pl_config = '';
        }
        $modx->setPlaceholder('button_pl_config', $button_pl_config);

        //plugin lang
        $_oec_lang = array();
        $plugin_path = __DIR__;

        include $plugin_path . '/lang/english.php';
        if (file_exists($plugin_path . '/lang/' . $modx->getConfig('manager_language') . '.php')) {
            include $plugin_path . '/lang/' . $modx->getConfig('manager_language') . '.php';
        }

        $out = '';

        //check outdated files
        //ajax index
        $indexajax = MODX_BASE_PATH . 'index-ajax.php';
        if (file_exists($indexajax)) {
            $out .= '<div class="widget-wrapper alert alert-danger">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>index-ajax.php</b> ' . $_oec_lang['not_used'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b> ' . $_oec_lang['if_dont_use'] . ', ' .
                $_oec_lang['please_delete'] .
            '</div>';
        }

        //check outdated default manager themes
        if (!empty($badthemes)) {
            $oldthemes = explode(',', $badthemes);
            foreach ($oldthemes as $oldtheme) {
                if (file_exists('media/style/' . $oldtheme)) {
                    $out = $modx->parseChunk(
                        '@CODE: <div class="widget-wrapper alert alert-danger">' .
                            '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                            $_lang["manager_theme"] . ' <b>[+oldTheme+]</b> ' . $_oec_lang['isoutdated'] . ' ' .
                            ' <b>Evolution [+EvoVersion+]</b>. ' .
                            $_oec_lang['please_delete'] . ' ' . $_oec_lang['from_folder'] . ' [+path+]' .
                        '</div>',
                        array(
                            'oldTheme' => $oldtheme,
                            'EvoVersion' => $modx->getVersionData('version'),
                            'path' => MODX_MANAGER_PATH . 'media/style/'
                        ),
                        '[+',
                        '+]'
                    );
                }
            }
        }

        //check outdated modx rss news feed
        $url_security = $modx->getConfig('rss_url_security');
        if ($url_security === 'http://feeds.feedburner.com/modxsecurity') {
            $out .= $modx->parseChunk(
                '@CODE:<div class="widget-wrapper alert alert-warning">' .
                    '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                    '<b>' . $_lang['settings_config'] . ' > ' . $_lang['rss_url_security_title'] . '</b> ' .
                    '(http://feeds.feedburner.com/modxsecurity) ' . $_oec_lang['outdated'] . '. ' .
                    $_oec_lang['please_download_and_install'] . ' <b>UpdateEvoRss</b>  ' . $_oec_lang['from'] .
                    ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a>' .
                '</div>',
                array(
                    'ExtrasID' => $checkOutdated->getExtrasId(),
                    'oldUrl' => $url_security
                ),
                '[+',
                '+]'
            );
        }

        $url_news = $modx->getConfig('rss_url_news');
        if ($url_news === 'http://feeds.feedburner.com/modx-announce') {
            $out .= $modx->parseChunk(
                '@CODE:<div class="widget-wrapper alert alert-warning">' .
                    '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                    '<b>' . $_lang["settings_config"] . ' > ' . $_lang["rss_url_news_title"] . ' </b> ' .
                    '(http://feeds.feedburner.com/modx-announce) ' . $_oec_lang['outdated'] . '. ' .
                    $_oec_lang['please_download_and_install'] . ' <b>UpdateEvoRss</b>  ' . $_oec_lang['from'] .
                    ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a>' .
                '</div>',
                array(
                    'ExtrasID' => $checkOutdated->getExtrasId(),
                    'oldUrl' => $url_news
                ),
                '[+',
                '+]'
            );
        }

        $out .= $checkOutdated->snippet(
            'Ditto',
            $DittoVersion,
            '@CODE:<div class="widget-wrapper alert alert-warning">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>[+name+]</b> ' . $_lang['snippet'] . ' (version [+currentVersion+]) ' . $_oec_lang['isoutdated'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b>. ' . $_oec_lang['please_update'] .
                ' <b>[+name+]</b> ' . $_oec_lang['to_latest'] .
                ' (' . $_oec_lang['min _required'] . ' [+minVersion+]) ' . $_oec_lang['from'] .
                ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a> ' .
                $_oec_lang['or_move_to'] . ' <b>DocLister</b>' .
            '</div>'
        );

        $out .= $checkOutdated->snippet(
            'eForm',
            $EformVersion,
            '@CODE:<div class="widget-wrapper alert alert-warning">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>[+name+]</b> ' . $_lang["snippet"] . ' (version [+currentVersion+]) ' . $_oec_lang['isoutdated'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b>. ' . $_oec_lang['please_update'] .
                ' <b>[+name+]</b> ' . $_oec_lang["to_latest"] .
                ' (' . $_oec_lang['min _required'] . ' [+minVersion+]) ' . $_oec_lang['from'] .
                ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a> ' .
                $_oec_lang['or_move_to'] . ' <b>FormLister</b>' .
            '</div>'
        );

        $out .= $checkOutdated->snippet(
            'AjaxSearch',
            $AjaxSearchVersion,
            '@CODE:<div class="widget-wrapper alert alert-warning">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>[+name+]</b> ' . $_lang["snippet"] . ' (version [+currentVersion+]) ' . $_oec_lang['isoutdated'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b>. ' . $_oec_lang['please_update'] .
                ' <b>[+name+]</b> ' . $_oec_lang["to_latest"] .
                ' (' . $_oec_lang['min _required'] . ' [+minVersion+]) ' . $_oec_lang['from'] .
                ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a>' .
            '</div>'
        );

        $out .= $checkOutdated->snippet(
            'Wayfinder',
            $WayfinderVersion,
            '@CODE:<div class="widget-wrapper alert alert-warning">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>[+name+]</b> ' . $_lang["snippet"] . ' (version [+currentVersion+]) ' . $_oec_lang['isoutdated'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b>. ' . $_oec_lang['please_update'] .
                ' <b>[+name+]</b> ' . $_oec_lang["to_latest"] .
                ' (' . $_oec_lang['min _required'] . ' [+minVersion+]) ' . $_oec_lang['from'] .
                ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a>' .
            '</div>'
        );

        $out .= $checkOutdated->snippet(
            'WebLogin',
            $WebLoginVersion,
            '@CODE:<div class="widget-wrapper alert alert-warning">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>[+name+]</b> ' . $_lang["snippet"] . ' (version [+currentVersion+]) ' . $_oec_lang['isoutdated'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b>. ' . $_oec_lang['please_update'] .
                ' <b>[+name+]</b> ' . $_oec_lang["to_latest"] .
                ' (' . $_oec_lang['min _required'] . ' [+minVersion+]) ' . $_oec_lang['from'] .
                ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a> ' .
                $_oec_lang['or_move_to'] . ' <b>FormLister</b>' .
            '</div>'
        );

        $out .= $checkOutdated->snippet(
            'WebChangePwd',
            $WebChangePwdVersion,
            '@CODE:<div class="widget-wrapper alert alert-warning">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>[+name+]</b> ' . $_lang["snippet"] . ' (version [+currentVersion+]) ' . $_oec_lang['isoutdated'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b>. ' . $_oec_lang['please_update'] .
                ' <b>[+name+]</b> ' . $_oec_lang["to_latest"] .
                ' (' . $_oec_lang['min _required'] . ' [+minVersion+]) ' . $_oec_lang['from'] .
                ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a> ' .
                $_oec_lang['or_move_to'] . ' <b>FormLister</b>' .
            '</div>'
        );

        $out .= $checkOutdated->snippet(
            'WebSignup',
            $WebSignupVersion,
            '@CODE:<div class="widget-wrapper alert alert-warning">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>[+name+]</b> ' . $_lang["snippet"] . ' (version [+currentVersion+]) ' . $_oec_lang['isoutdated'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b>. ' . $_oec_lang['please_update'] .
                ' <b>[+name+]</b> ' . $_oec_lang["to_latest"] .
                ' (' . $_oec_lang['min _required'] . ' [+minVersion+]) ' . $_oec_lang['from'] .
                ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a> ' .
                $_oec_lang['or_move_to'] . ' <b>FormLister</b>' .
            '</div>'
        );

        $out .= $checkOutdated->snippet(
            'Breadcrumbs',
            $BreadcrumbsVersion,
            '@CODE:<div class="widget-wrapper alert alert-warning">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>[+name+]</b> ' . $_lang["snippet"] . ' (version [+currentVersion+]) ' . $_oec_lang['isoutdated'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b>. ' . $_oec_lang['please_update'] .
                ' <b>[+name+]</b> ' . $_oec_lang["to_latest"] .
                ' (' . $_oec_lang['min _required'] . ' [+minVersion+]) ' . $_oec_lang['from'] .
                ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a>' .
            '</div>'
        );

        $out .= $checkOutdated->snippet(
            'Reflect',
            $ReflectVersion,
            '@CODE:<div class="widget-wrapper alert alert-warning">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>[+name+]</b> ' . $_lang["snippet"] . ' (version [+currentVersion+]) ' . $_oec_lang['isoutdated'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b>. ' . $_oec_lang['please_update'] .
                ' <b>[+name+]</b> ' . $_oec_lang["to_latest"] .
                ' (' . $_oec_lang['min _required'] . ' [+minVersion+]) ' . $_oec_lang['from'] .
                ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a>' .
            '</div>'
        );

        $out .= $checkOutdated->snippet(
            'Jot',
            $JotVersion,
            '@CODE:<div class="widget-wrapper alert alert-warning">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>[+name+]</b> ' .$_lang["snippet"] . ' (version [+currentVersion+]) ' . $_oec_lang['isoutdated'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b>. ' . $_oec_lang['please_update'] .
                ' <b>[+name+]</b> ' . $_oec_lang["to_latest"] .
                ' (' . $_oec_lang['min _required'] . ' [+minVersion+]) ' . $_oec_lang['from'] .
                ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a>' .
            '</div>'
        );

        $out .= $checkOutdated->snippet(
            'multiTV',
            $MtvVersion,
            '@CODE:<div class="widget-wrapper alert alert-warning">' .
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
                '<b>[+name+]</b> ' . $_lang["snippet"] . ' (version [+currentVersion+]) ' . $_oec_lang['isoutdated'] .
                ' <b>Evolution ' . $modx->getVersionData('version') . '</b>. ' . $_oec_lang['please_update'] .
                ' <b>[+name+]</b> ' . $_oec_lang["to_latest"] .
                ' (' . $_oec_lang['min _required'] . ' [+minVersion+]) ' . $_oec_lang['from'] .
                ' <a target="main" href="index.php?a=112&id=[+ExtrasID+]">' . $_oec_lang['extras_module'] . '</a>' .
            '</div>'
        );

        if ($out !== '' && $modx->event->name === 'OnManagerWelcomeHome') {
            $wdgTitle = 'EVO ' . $modx->getVersionData('version') . ' - ' . $_oec_lang['title'] . '';
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
            $modx->event->setOutput(serialize($widgets));
        }
        break;
}
