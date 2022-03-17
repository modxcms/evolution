<!-- Interface & editor settings -->
<div class="tab-page" id="tabPage5">
    <h2 class="tab">{{ __('global.settings_ui') }}</h2>
    <script type="text/javascript">tpSettings.addTabPage(document.getElementById('tabPage5'));</script>
    <div class="container container-body">

        @include('manager::form.select', [
            'name' => 'manager_language',
            'label' => __('global.language_title'),
            'small' => '[(manager_language)]',
            'value' => $settings['manager_language'],
            'options' => $langKeys,
            'as' => 'values',
            'ucwords' => false,
            'str_to_upper' => true,
            'attributes' => 'onChange="documentDirty=true;" size="1"',
            'comment' => __('global.language_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.select', [
            'name' => 'modx_charset',
            'label' => __('global.charset_title'),
            'small' => '[(modx_charset)]',
            'value' => $settings['modx_charset'],
            'attributes' => 'onChange="documentDirty=true;" size="1"',
            'options' => include EVO_CORE_PATH . '/factory/charsets.php',
            'comment' => __('global.charset_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.select', [
            'name' => 'manager_theme',
            'label' => __('global.manager_theme'),
            'small' => '[(manager_theme)]',
            'value' => $settings['manager_theme'] ?? 'default',
            'attributes' => 'onChange="documentDirty=true; document.forms[\'settings\'].theme_refresher.value = Date.parse(new Date());" size="1"',
            'options' => $themes,
            'ucwords' => true,
            'comment' => '<input type="hidden" name="theme_refresher" value="" />'
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'manager_theme_mode',
            'label' => __('global.manager_theme_mode'),
            'small' => '[(manager_theme_mode)]',
            'value' => $settings['manager_theme_mode'],
            'options' => [
                1 => __('global.manager_theme_mode1'),
                2 => __('global.manager_theme_mode2'),
                3 => __('global.manager_theme_mode3'),
                4 => __('global.manager_theme_mode4')
            ],
            'comment' => __('global.manager_theme_mode_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.row', [
            'label' => __('global.login_logo_title'),
            'small' => '[(login_logo)]',
            'for' => 'login_logo',
            'element' => '
                <div class="col-md-8">
                    <div class="input-group">' .
                        view('manager::form.inputElement', [
                            'name' => 'login_logo',
                            'value' => $settings['login_logo'],
                            'attributes' => 'onChange="documentDirty=true;"'
                        ]) .
                        '<div class="input-group-btn">' .
                            view('manager::form.inputElement', [
                                'type' => 'button',
                                'value' => __('global.insert'),
                                'attributes' => 'onclick="BrowseServer(\'login_logo\')"'
                            ]) .
                        '</div>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <img name="login_logo" style="max-height: 48px" src="' .
                    ($settings['login_logo'] ? MODX_SITE_URL . $settings['login_logo'] : '') . '" />
                </div>',
            'comment' => __('global.login_logo_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.row', [
            'label' => __('global.login_bg_title'),
            'small' => '[(login_bg)]',
            'for' => 'login_bg',
            'element' => '
                <div class="col-md-8">
                    <div class="input-group">' .
                        view('manager::form.inputElement', [
                            'name' => 'login_bg',
                            'value' => $settings['login_bg'],
                            'attributes' => 'onChange="documentDirty=true;"'
                        ]) .
                        '<div class="input-group-btn">' .
                            view('manager::form.inputElement', [
                                'type' => 'button',
                                'value' => __('global.insert'),
                                'attributes' => 'onclick="BrowseServer(\'login_bg\')"'
                            ]) .
                        '</div>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <img name="login_bg" style="max-height: 48px" src="' .
                    ($settings['login_bg'] ? MODX_SITE_URL . $settings['login_bg'] : '') . '" />
                </div>',
            'comment' => __('global.login_bg_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'login_form_position',
            'label' => __('global.login_form_position_title'),
            'small' => '[(login_form_position)]',
            'value' => $settings['login_form_position'],
            'options' => [
                'left' => __('global.login_form_position_left'),
                'center' => __('global.login_form_position_center'),
                'right' => __('global.login_form_position_right')
            ]
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'login_form_style',
            'label' => __('global.login_form_style'),
            'small' => '[(login_form_style)]',
            'value' => $settings['login_form_style'],
            'options' => [
                'dark' => __('global.login_form_style_dark'),
                'light' => __('global.login_form_style_light')
            ]
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'manager_menu_position',
            'label' => __('global.manager_menu_position_title'),
            'small' => '[(manager_menu_position)]',
            'value' => $settings['manager_menu_position'],
            'options' => [
                'top' => __('global.manager_menu_position_top'),
                'left' => __('global.manager_menu_position_left')
            ]
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'show_picker',
            'label' => __('global.show_picker'),
            'small' => '[(show_picker)]',
            'value' => $settings['show_picker'],
            'options' => [
                1 => __('global.yes'),
                0 => __('global.no')
            ],
            'comment' => __('global.settings_show_picker_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'warning_visibility',
            'label' => __('global.warning_visibility'),
            'small' => '[(warning_visibility)]',
            'value' => $settings['warning_visibility'],
            'options' => [
                0 => __('global.administrators'),
                1 => __('global.everybody')
            ],
            'comment' => __('global.warning_visibility_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'tree_page_click',
            'label' => __('global.tree_page_click'),
            'small' => '[(tree_page_click)]',
            'value' => $settings['tree_page_click'],
            'options' => [
                27 => __('global.edit_resource'),
                3 => __('global.doc_data_title')
            ],
            'comment' => __('global.tree_page_click_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'use_breadcrumbs',
            'label' => __('global.use_breadcrumbs'),
            'small' => '[(use_breadcrumbs)]',
            'value' => $settings['use_breadcrumbs'],
            'options' => [
                1 => __('global.yes'),
                0 => __('global.no')
            ],
            'comment' => __('global.use_breadcrumbs_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'remember_last_tab',
            'label' => __('global.remember_last_tab'),
            'small' => '[(remember_last_tab)]',
            'value' => $settings['remember_last_tab'],
            'options' => [
                1 => __('global.yes'),
                0 => __('global.no')
            ],
            'comment' => __('global.remember_last_tab_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'global_tabs',
            'label' => __('global.use_global_tabs'),
            'small' => '[(global_tabs)]',
            'value' => $settings['global_tabs'],
            'options' => [
                1 => __('global.yes'),
                0 => __('global.no')
            ]
        ])

        <div class="split my-1"></div>

        @include('manager::form.select', [
            'name' => 'group_tvs',
            'label' => __('global.group_tvs'),
            'small' => '[(group_tvs)]',
            'value' => $settings['group_tvs'],
            'options' => explode(',', __('global.settings_group_tv_options')),
            'attributes' => 'onChange="documentDirty=true;" size="1"',
            'comment' => __('global.settings_group_tv_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'show_newresource_btn',
            'label' => __('global.show_newresource_btn'),
            'small' => '[(show_newresource_btn)]',
            'value' => $settings['show_newresource_btn'],
            'options' => [
                1 => __('global.yes'),
                0 => __('global.no')
            ],
            'comment' => __('global.show_newresource_btn_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'show_fullscreen_btn',
            'label' => __('global.show_fullscreen_btn'),
            'small' => '[(show_fullscreen_btn)]',
            'value' => $settings['show_fullscreen_btn'],
            'options' => [
                1 => __('global.yes'),
                0 => __('global.no')
            ],
            'comment' => __('global.show_fullscreen_btn_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.select', [
            'name' => 'resource_tree_node_name',
            'label' => __('global.setting_resource_tree_node_name'),
            'small' => '[(resource_tree_node_name)]',
            'value' => $settings['resource_tree_node_name'],
            'options' => [
                'pagetitle' => '[*pagetitle*]',
                'longtitle' => '[*longtitle*]',
                'menutitle' => '[*menutitle*]',
                'alias' => '[*alias*]',
                'createdon' => '[*createdon*]',
                'editedon' => '[*editedon*]',
                'publishedon' => '[*publishedon*]'
            ],
            'attributes' => 'onChange="documentDirty=true;" size="1"',
            'comment' => __('global.setting_resource_tree_node_name_desc') . '<br /><b>' . __('global.setting_resource_tree_node_name_desc_add') . '</b>'
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'session_timeout',
            'label' => __('global.session_timeout'),
            'small' => '[(session_timeout)]',
            'value' => $settings['session_timeout'],
            'comment' => __('global.session_timeout_msg')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'tree_show_protected',
            'label' => __('global.tree_show_protected'),
            'small' => '[(tree_show_protected)]',
            'value' => $settings['tree_show_protected'],
            'options' => [
                1 => __('global.yes'),
                0 => __('global.no')
            ],
            'comment' => __('global.tree_show_protected_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'datepicker_offset',
            'label' => __('global.datepicker_offset'),
            'small' => '[(datepicker_offset)]',
            'value' => $settings['datepicker_offset'],
            'attributes' => 'onChange="documentDirty=true;" maxlength="50"',
            'comment' => __('global.datepicker_offset_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.select', [
            'name' => 'datetime_format',
            'label' => __('global.datetime_format'),
            'small' => '[(datetime_format)]',
            'value' => $settings['datetime_format'],
            'attributes' => 'onChange="documentDirty=true;" size="1"',
            'options' =>  ['dd-mm-YYYY', 'mm/dd/YYYY', 'YYYY/mm/dd'],
            'as' => 'values',
            'comment' => __('global.datetime_format_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'number_of_logs',
            'label' => __('global.nologentries_title'),
            'small' => '[(number_of_logs)]',
            'value' => $settings['number_of_logs'],
            'attributes' => 'onChange="documentDirty=true;" maxlength="50"',
            'comment' => __('global.nologentries_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'mail_check_timeperiod',
            'label' => __('global.mail_check_timeperiod_title'),
            'small' => '[(mail_check_timeperiod)]',
            'value' => $settings['mail_check_timeperiod'],
            'attributes' => 'onChange="documentDirty=true;" maxlength="50"',
            'comment' => __('global.mail_check_timeperiod_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'number_of_messages',
            'label' => __('global.nomessages_title'),
            'small' => '[(number_of_messages)]',
            'value' => $settings['number_of_messages'],
            'attributes' => 'onChange="documentDirty=true;" maxlength="50"',
            'comment' => __('global.nomessages_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'number_of_results',
            'label' => __('global.noresults_title'),
            'small' => '[(number_of_results)]',
            'value' => $settings['number_of_results'],
            'attributes' => 'onChange="documentDirty=true;" maxlength="50"',
            'comment' => __('global.noresults_message')
        ])

        <div class="split my-1"></div>

        <?php
        // invoke OnRichTextEditorRegister event
        $evtOut = EvolutionCMS()->invokeEvent('OnRichTextEditorRegister');
        if (!is_array($evtOut)) {
            $evtOut = array();
            $use_editor = 0;
        }
        ?>

        <div @if(empty($evtOut)) style="display: none;" @endif>

            @include('manager::form.radio', [
                'name' => 'use_editor',
                'label' => __('global.use_editor_title'),
                'small' => '[(use_editor)]',
                'value' => $settings['use_editor'],
                'options' => [
                    1 => [
                        'text' => __('global.yes'),
                        'attributes' => 'id="editorRowOn"'
                    ],
                    0 => [
                        'text' => __('global.no'),
                        'attributes' => 'id="editorRowOff"'
                    ]
                ],
                'comment' => __('global.use_editor_message')
            ])

            <div class="split my-1"></div>

            <div class="editorRow" @if(empty($settings['use_editor'])) style="display: none;" @endif>

                @include('manager::form.select', [
                    'name' => 'which_editor',
                    'label' => __('global.which_editor_title'),
                    'small' => '[(which_editor)]',
                    'value' => $settings['which_editor'],
                    'attributes' => 'onChange="documentDirty=true;" size="1"',
                    'first' => [
                        'value' => 'none',
                        'text' => __('global.none')
                    ],
                    'options' => $evtOut,
                    'as' => 'values',
                    'comment' => __('global.which_editor_message')
                ])

                <div class="split my-1"></div>

                @include('manager::form.select', [
                    'name' => 'fe_editor_lang',
                    'label' => __('global.fe_editor_lang_title'),
                    'small' => '[(fe_editor_lang)]',
                    'value' => $settings['fe_editor_lang'],
                    'attributes' => 'onChange="documentDirty=true;" size="1"',
                    'first' => [
                        'text' => __('global.language_title')
                    ],
                    'options' => $langKeys,
                    'as' => 'values',
                    'ucwords' => true,
                    'comment' => __('global.fe_editor_lang_message')
                ])

                <div class="split my-1"></div>

                @include('manager::form.input', [
                    'name' => 'editor_css_path',
                    'label' => __('global.editor_css_path_title'),
                    'small' => '[(editor_css_path)]',
                    'value' => $settings['editor_css_path'],
                    'attributes' => 'onChange="documentDirty=true;" maxlength="255"',
                    'comment' => __('global.editor_css_path_message')
                ])

                <div class="split my-1"></div>
            </div>
        </div>

        {!! get_by_key($tabEvents, 'OnInterfaceSettingsRender') !!}
    </div>
</div>
