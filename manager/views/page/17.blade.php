@extends('manager::template.page')
@section('content')
    <?php
    if (!$modx->hasPermission('settings')) {
        $modx->webAlertAndQuit(ManagerTheme::getLexicon('error_no_privileges'));
    }

    // check to see the edit settings page isn't locked
    $rs = $modx->getDatabase()->select('username', $modx->getDatabase()->getFullTableName('active_users'), "action=17 AND internalKey!='" . $modx->getLoginUserID() . "'");
    if ($username = $modx->getDatabase()->getValue($rs)) {
        $modx->webAlertAndQuit(sprintf(ManagerTheme::getLexicon('lock_settings_msg'), $username));
    }
    // end check for lock

    // reload system settings from the database.
    // this will prevent user-defined settings from being saved as system setting
    $settings = include EVO_CORE_PATH . 'factory/settings.php';
    $rs = $modx->getDatabase()->select('setting_name, setting_value', $modx->getDatabase()->getFullTableName('system_settings'));
    while ($row = $modx->getDatabase()->getRow($rs)) {
        $settings[$row['setting_name']] = $row['setting_value'];
    }
    $settings['filemanager_path'] = preg_replace('@^' . preg_quote(MODX_BASE_PATH) . '@', '[(base_path)]', $settings['filemanager_path']);
    $settings['rb_base_dir'] = preg_replace('@^' . preg_quote(MODX_BASE_PATH) . '@', '[(base_path)]', $settings['rb_base_dir']);

    foreach ($modx->config as $k => $v) {
        $settings[$k] = get_by_key($modx->config, $k);
    }

    // load languages and keys
    $lang_keys_select = [];
    $dir = dir(MODX_MANAGER_PATH . 'includes/lang');
    while ($file = $dir->read()) {
        if (strpos($file, '.inc.php') > 0) {
            $endpos = strpos($file, '.');
            $languagename = substr($file, 0, $endpos);
            $lang_keys_select[$languagename] = $languagename;
        }
    }
    $dir->close();

    // load templates
    $rs = $modx->getDatabase()->select('t.templatename, t.id, c.category', $modx->getDatabase()->getFullTableName('site_templates') . " AS t
                LEFT JOIN " . $modx->getDatabase()->getFullTableName('categories') . " AS c ON t.category = c.id", "", 'c.category, t.templatename ASC');

    $templates = [];
    $currentCategory = '';
    $oldTmpId = 0;
    $oldTmpName = '';
    $i = 0;
    while ($row = $modx->getDatabase()->getRow($rs)) {
        $thisCategory = $row['category'];
        if ($thisCategory == null) {
            $thisCategory = ManagerTheme::getLexicon('no_category');
        }
        if ($thisCategory != $currentCategory) {
            $templates[$i] = [
                'optgroup' => [
                    'name' => $thisCategory,
                    'options' => []
                ]
            ];
        } else {
            $i++;
        }
        if ($row['id'] == get_by_key($modx->config, 'default_template')) {
            $oldTmpId = $row['id'];
            $oldTmpName = $row['templatename'];
        }
        $templates[$i]['optgroup']['options'][] = [
            'text' => $row['templatename'],
            'value' => $row['id']
        ];
        $currentCategory = $thisCategory;
    }

    $phxEnabled = 0;
    $res = EvolutionCMS\Models\SitePlugin::whereRaw('plugincode LIKE "%phx.parser.class.inc.php%OnParseDocument();%" AND disabled != 1')->get()->pluck('id')->toArray();
    if (!empty($res)) {
        $phxEnabled = 1;
    }

    $serverTimes = [];
    for ($i = -24; $i < 25; $i++) {
        $seconds = $i * 60 * 60;
        $serverTimes[] = [
            'value' => $seconds,
            'text' => $i
        ];
    }

    $themes = [];
    $dir = dir("media/style/");
    while ($file = $dir->read()) {
        if ($file != "." && $file != ".." && is_dir("media/style/$file") && substr($file, 0, 1) != '.') {
            if ($file === 'common') {
                continue;
            }
            $themes[$file] = $file;
        }
    }
    $dir->close();

    $file_browsers = [];
    foreach (glob("media/browser/*", GLOB_ONLYDIR) as $dir) {
        $dir = str_replace('\\', '/', $dir);
        $file_browsers[] = substr($dir, strrpos($dir, '/') + 1);
    }

    $pwd_hash = [
        'BLOWFISH_Y' => [
            'value' => 'BLOWFISH_Y',
            'text' => 'CRYPT_BLOWFISH_Y (salt &amp; stretch)',
            'disabled' => $modx->getManagerApi()->checkHashAlgorithm('BLOWFISH_Y') ? 0 : 1
        ],
        'BLOWFISH_A' => [
            'value' => 'BLOWFISH_A',
            'text' => 'CRYPT_BLOWFISH_A (salt &amp; stretch)',
            'disabled' => $modx->getManagerApi()->checkHashAlgorithm('BLOWFISH_A') ? 0 : 1
        ],
        'SHA512' => [
            'value' => 'SHA512',
            'text' => 'CRYPT_SHA512 (salt &amp; stretch)',
            'disabled' => $modx->getManagerApi()->checkHashAlgorithm('SHA512') ? 0 : 1
        ],
        'SHA256' => [
            'value' => 'SHA256',
            'text' => 'CRYPT_SHA256 (salt &amp; stretch)',
            'disabled' => $modx->getManagerApi()->checkHashAlgorithm('SHA256') ? 0 : 1
        ],
        'MD5' => [
            'value' => 'MD5',
            'text' => 'CRYPT_MD5 (salt &amp; stretch)',
            'disabled' => $modx->getManagerApi()->checkHashAlgorithm('MD5') ? 0 : 1
        ],
        'UNCRYPT' => [
            'value' => 'UNCRYPT',
            'text' => 'UNCRYPT(32 chars salt + SHA-1 hash)',
            'disabled' => $modx->getManagerApi()->checkHashAlgorithm('UNCRYPT') ? 0 : 1
        ],
    ];

    $gdAvailable = extension_loaded('gd');
    if (!$gdAvailable) {
        $settings['use_captcha'] = 0;
    }


    $displayStyle = ($_SESSION['browser'] === 'modern') ? 'table-row' : 'block';
    ?>
    @push('scripts.top')
        <script type="text/javascript">
          var displayStyle = '{{ $displayStyle }}';
          var lang_chg = '{{ ManagerTheme::getLexicon('confirm_setting_language_change') }}';
          var actions = {
            save: function() {
              documentDirty = false;
              document.settings.submit();
            },
            cancel: function() {
              documentDirty = false;
              document.location.href = 'index.php?a=2';
            }
          };
        </script>
        <script type="text/javascript" src="actions/mutate_settings/functions.js"></script>
    @endpush
    <form name="settings" action="index.php?a=30" method="post">
        <!-- this field is used to check site settings have been entered/ updated after install or upgrade -->
        <input type="hidden" name="site_id" value="{{ get_by_key($modx->config, 'site_id') }}" />
        <input type="hidden" name="settings_version" value="{{ $modx->getVersionData('version') }}" />
        <h1>
            <i class="fa fa-sliders fw"></i>{{ ManagerTheme::getLexicon('settings_title') }}
        </h1>
        @include('manager::partials.actionButtons', ['save' => '', 'cancel' => ''])
        @if(!get_by_key($modx->config, 'settings_version') || get_by_key($modx->config, 'settings_version') != $modx->getVersionData('version'))
            <div class="container">
                <p class="alert alert-warning">{!! ManagerTheme::getLexicon('settings_after_install') !!}</p>
            </div>
        @endif
        <div class="tab-pane" id="settingsPane">
            <script type="text/javascript">
              tpSettings = new WebFXTabPane(document.getElementById('settingsPane'), {{ get_by_key($modx->config, 'remember_last_tab') ? 1 : 0 }});
            </script>
            @include('manager::page.settings.general')
            @include('manager::page.settings.friendly_urls')
            @include('manager::page.settings.interface')
            @include('manager::page.settings.security')
            @include('manager::page.settings.file_manager')
            @include('manager::page.settings.file_browser')
            @include('manager::page.settings.mail_templates')
        </div>
    </form>
    @push('scripts.bot')
        <script>
          (function($) {
            $('input:radio').change(function() {
              documentDirty = true;
            });
            $('#furlRowOn').change(function() {
              $('.furlRow').fadeIn();
            });
            $('#furlRowOff').change(function() {
              $('.furlRow').fadeOut();
            });
            $('#udPermsOn').change(function() {
              $('.udPerms').slideDown();
            });
            $('#udPermsOff').change(function() {
              $('.udPerms').slideUp();
            });
            $('#editorRowOn').change(function() {
              $('.editorRow').slideDown();
            });
            $('#editorRowOff').change(function() {
              $('.editorRow').slideUp();
            });
            $('#rbRowOn').change(function() {
              $('.rbRow').fadeIn();
            });
            $('#rbRowOff').change(function() {
              $('.rbRow').fadeOut();
            });
            $('#useSmtp').change(function() {
              $('.smtpRow').fadeIn();
            });
            $('#useMail').change(function() {
              $('.smtpRow').fadeOut();
            });
            $('#captchaOn').change(function() {
              $('.captchaRow').fadeIn();
            });
            $('#captchaOff').change(function() {
              $('.captchaRow').fadeOut();
            });
          })(jQuery);
        </script>

        <script type="text/javascript">
          var lastImageCtrl;
          var lastFileCtrl;

          function OpenServerBrowser(url, width, height)
          {
            var iLeft = (screen.width - width) / 2;
            var iTop = (screen.height - height) / 2;

            var sOptions = 'toolbar=no,status=no,resizable=yes,dependent=yes';
            sOptions += ',width=' + width;
            sOptions += ',height=' + height;
            sOptions += ',left=' + iLeft;
            sOptions += ',top=' + iTop;

            var oWindow = window.open(url, 'FCKBrowseWindow', sOptions);
          }

          function BrowseServer(ctrl)
          {
            lastImageCtrl = ctrl;
            var w = screen.width * 0.7;
            var h = screen.height * 0.7;
            OpenServerBrowser('{{ MODX_MANAGER_URL }}media/browser/{{ $modx->getConfig('which_browser') }}/browser.php?Type=images', w, h);
          }

          function BrowseFileServer(ctrl)
          {
            lastFileCtrl = ctrl;
            var w = screen.width * 0.7;
            var h = screen.height * 0.7;
            OpenServerBrowser('{{ MODX_MANAGER_URL }}media/browser/{{ $modx->getConfig('which_browser') }}/browser.php?Type=files', w, h);
          }

          function SetUrlChange(el)
          {
            if ('createEvent' in document) {
              var evt = document.createEvent('HTMLEvents');
              evt.initEvent('change', false, true);
              el.dispatchEvent(evt);
            } else {
              el.fireEvent('onchange');
            }
          }

          function SetUrl(url, width, height, alt)
          {
            if (lastFileCtrl) {
              var c = document.getElementById(lastFileCtrl);
              if (c && c.value != url) {
                c.value = url;
                SetUrlChange(c);
              }
              lastFileCtrl = '';
            } else if (lastImageCtrl) {
              var c = document.getElementById(lastImageCtrl);
              if (c && c.value != url) {
                c.value = url;
                SetUrlChange(c);
              }
              lastImageCtrl = '';
            } else {
              return;
            }
          }
        </script>
        @if(isset($_GET['tab']) && is_numeric($_GET['tab']))
            <script type="text/javascript">tpSettings.setSelectedIndex({{ $_GET['tab'] }});</script>
        @endif
    @endpush
@endsection
