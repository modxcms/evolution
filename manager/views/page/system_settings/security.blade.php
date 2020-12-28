<!-- Interface & editor settings -->
<div class="tab-page" id="tabPageSecurity">
    <h2 class="tab">{{ ManagerTheme::getLexicon('settings_security') }}</h2>
    <script type="text/javascript">tpSettings.addTabPage(document.getElementById('tabPageSecurity'));</script>
    <div class="container container-body">

        @include('manager::form.radio', [
            'name' => 'use_udperms',
            'label' => ManagerTheme::getLexicon('udperms_title'),
            'small' => '[(use_udperms)]',
            'value' => $settings['use_udperms'],
            'options' => [
                1 => [
                    'text' => ManagerTheme::getLexicon('yes'),
                    'attributes' => 'id="udPermsOn"'
                ],
                0 => [
                    'text' => ManagerTheme::getLexicon('no'),
                    'attributes' => 'id="udPermsOff"'
                ]
            ],
            'comment' => ManagerTheme::getLexicon('udperms_message')
        ])

        <div class="split my-1"></div>

        <div class="udPerms" @if(!$settings['use_udperms']) style="display: none;" @endif>
            @include('manager::form.radio', [
                'name' => 'udperms_allowroot',
                'label' => ManagerTheme::getLexicon('udperms_allowroot_title'),
                'small' => '[(udperms_allowroot)]',
                'value' => $settings['udperms_allowroot'],
                'options' => [
                    1 => ManagerTheme::getLexicon('yes'),
                    0 => ManagerTheme::getLexicon('no')
                ],
                'comment' => ManagerTheme::getLexicon('udperms_allowroot_message')
            ])

            <div class="split my-1"></div>
        </div>

        @include('manager::form.radio', [
            'name' => 'allow_eval',
            'label' => ManagerTheme::getLexicon('allow_eval_title'),
            'small' => '[(allow_eval)]',
            'value' => $settings['allow_eval'],
            'options' => [
                'with_scan' =>  ManagerTheme::getLexicon('allow_eval_with_scan'),
                'with_scan_at_post' => ManagerTheme::getLexicon('allow_eval_with_scan_at_post'),
                'everytime_eval' => ManagerTheme::getLexicon('allow_eval_everytime_eval'),
                'dont_eval' => ManagerTheme::getLexicon('allow_eval_dont_eval'),
            ],
            'comment' => ManagerTheme::getLexicon('allow_eval_msg')
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'safe_functions_at_eval',
            'label' => ManagerTheme::getLexicon('safe_functions_at_eval_title'),
            'small' => '[(safe_functions_at_eval)]',
            'value' => $settings['safe_functions_at_eval'],
            'attributes' => 'onchange="documentDirty=true;"',
            'comment' => ManagerTheme::getLexicon('safe_functions_at_eval_msg')
        ])

        <div class="split my-1"></div>

        @include('manager::form.textarea', [
            'name' => 'check_files_onlogin',
            'label' => ManagerTheme::getLexicon('check_files_onlogin_title'),
            'small' => '[(check_files_onlogin)]',
            'value' => $settings['check_files_onlogin']
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'validate_referer',
            'label' => ManagerTheme::getLexicon('validate_referer_title'),
            'small' => '[(validate_referer)]',
            'value' => $settings['validate_referer'],
            'options' => [
                1 =>  ManagerTheme::getLexicon('yes'),
                0 => ManagerTheme::getLexicon('no')
            ],
            'comment' => ManagerTheme::getLexicon('validate_referer_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'rss_url_security',
            'label' => ManagerTheme::getLexicon('rss_url_security_title'),
            'small' => '[(rss_url_security)]',
            'value' => $settings['rss_url_security'],
            'attributes' => 'onchange="documentDirty=true;" maxlength="350"',
            'comment' => ManagerTheme::getLexicon('rss_url_security_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'error_reporting',
            'label' => ManagerTheme::getLexicon('a17_error_reporting_title'),
            'small' => '[(error_reporting)]',
            'value' => $settings['error_reporting'],
            'options' => [
                0 =>  ManagerTheme::getLexicon('a17_error_reporting_opt0'),
                1 => ManagerTheme::getLexicon('a17_error_reporting_opt1'),
                2 => ManagerTheme::getLexicon('a17_error_reporting_opt2'),
                99 => ManagerTheme::getLexicon('a17_error_reporting_opt99'),
                199 => ManagerTheme::getLexicon('a17_error_reporting_opt199'),
            ],
            'comment' => ManagerTheme::getLexicon('a17_error_reporting_msg')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'send_errormail',
            'label' => ManagerTheme::getLexicon('mutate_settings.dynamic.php6'),
            'small' => '[(send_errormail)]',
            'value' => $settings['send_errormail'],
            'options' => [
                0 =>  ManagerTheme::getLexicon('mutate_settings.dynamic.php7'),
                3 => 'error',
                2 => 'error + warning',
                1 => 'error + warning + information',
            ],
            'comment' => str_replace('[+emailsender+]', $settings['emailsender'], ManagerTheme::getLexicon('mutate_settings.dynamic.php8'))
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'enable_bindings',
            'label' => ManagerTheme::getLexicon('enable_bindings_title'),
            'small' => '[(enable_bindings)]',
            'value' => $settings['enable_bindings'],
            'options' => [
                1 =>  ManagerTheme::getLexicon('yes'),
                0 =>  ManagerTheme::getLexicon('no'),
            ],
            'comment' => ManagerTheme::getLexicon('enable_bindings_message') . '<br><br>' . ManagerTheme::getLexicon('check_files_onlogin_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'failed_login_attempts',
            'label' => ManagerTheme::getLexicon('failed_login_title'),
            'small' => '[(failed_login_attempts)]',
            'value' => $settings['failed_login_attempts'],
            'attributes' => 'onchange="documentDirty=true;"',
            'comment' => ManagerTheme::getLexicon('failed_login_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'blocked_minutes',
            'label' => ManagerTheme::getLexicon('blocked_minutes_title'),
            'small' => '[(blocked_minutes)]',
            'value' => $settings['blocked_minutes'],
            'attributes' => 'onchange="documentDirty=true;"',
            'comment' => ManagerTheme::getLexicon('blocked_minutes_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'pwd_hash_algo',
            'label' => ManagerTheme::getLexicon('pwd_hash_algo_title'),
            'small' => '[(pwd_hash_algo)]',
            'value' => $settings['pwd_hash_algo'],
            'options' => $passwordsHash,
            'comment' => ManagerTheme::getLexicon('pwd_hash_algo_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'use_captcha',
            'label' => ManagerTheme::getLexicon('captcha_title'),
            'small' => '[(use_captcha)]',
            'value' => $settings['use_captcha'],
            'disabled' => !$gdAvailable,
            'options' => [
                1 =>  [
                    'text' => ManagerTheme::getLexicon('yes'),
                    'attributes' => 'id="captchaOn"',
                ],
                0 =>  [
                    'text' => ManagerTheme::getLexicon('no'),
                    'attributes' => 'id="captchaOff"'
                ]
            ],
            'comment' => ManagerTheme::getLexicon('captcha_message')
        ])

        <div class="split my-1"></div>

        <div class="captchaRow" @if(!$settings['use_captcha']) style="display: none;" @endif>
            @include('manager::form.textarea', [
                'name' => 'captcha_words',
                'label' => ManagerTheme::getLexicon('captcha_words_title') . '<br>' .
                    ManagerTheme::getLexicon('update_settings_from_language') .
                    ManagerTheme::view('form.selectElement', [
                        'name' => 'reload_captcha_words',
                        'id' => 'reload_captcha_words_select',
                        'class' => 'form-control-sm',
                        'attributes' => 'onchange="confirmLangChange(this, \'captcha_words_default\', \'captcha_words_input\');"',
                        'first' => [
                            'text' => ManagerTheme::getLexicon('language_title')
                        ],
                        'options' => $langKeys,
                        'as' => 'values',
                        'ucwords' => true
                    ]),
                'small' => '[(captcha_words)]',
                'value' => $settings['captcha_words'],
                'comment' => ManagerTheme::getLexicon('captcha_words_message') .
                    ManagerTheme::view('form.inputElement', [
                        'type' => 'hidden',
                        'name' => 'captcha_words_default',
                        'id' => 'captcha_words_default_hidden',
                        'value' => addslashes(ManagerTheme::getLexicon('captcha_words_default'))
                    ])
            ])

            <div class="split my-1"></div>
        </div>

        {!! get_by_key($tabEvents, 'OnSecuritySettingsRender') !!}
    </div>
</div>
