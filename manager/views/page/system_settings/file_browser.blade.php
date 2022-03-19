<!-- KCFinder settings -->
<div class="tab-page" id="tabPage8">
    <h2 class="tab">{{ __('global.settings_KC') }}</h2>
    <script type="text/javascript">tpSettings.addTabPage(document.getElementById('tabPage8'));</script>
    <div class="container container-body">

        @include('manager::form.radio', [
            'name' => 'use_browser',
            'label' => __('global.rb_title'),
            'small' => '[(use_browser)]',
            'value' => $settings['use_browser'],
            'options' => [
                1 => [
                    'text' => __('global.yes'),
                    'attributes' => 'id="rbRowOn"'
                ],
                0 => [
                    'text' => __('global.no'),
                    'attributes' => 'id="rbRowOff"'
                ]
            ],
            'comment' => (isset($disabledSettings['use_browser']) ? __('global.setting_from_file') . '<br>' : '') .
                __('global.rb_message'),
            'disabled' => $disabledSettings['use_browser'] ?? null
        ])

        <div class="split my-1"></div>

        <div class="rbRow" @if(!$settings['use_browser']) style="display: none;" @endif>
            @include('manager::form.select', [
                'name' => 'which_browser',
                'label' => __('global.which_browser_default_title'),
                'small' => '[(which_browser)]',
                'value' => $settings['which_browser'],
                'attributes' => 'onChange="documentDirty=true;" size="1"',
                'options' => $fileBrowsers,
                'as' => 'values',
                'comment' => (isset($disabledSettings['which_browser']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.which_browser_default_msg'),
                'disabled' => $disabledSettings['which_browser'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'rb_webuser',
                'label' => __('global.rb_webuser_title'),
                'small' => '[(rb_webuser)]',
                'value' => $settings['rb_webuser'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no')
                ],
                'comment' => (isset($disabledSettings['rb_webuser']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.rb_webuser_message'),
                'disabled' => $disabledSettings['rb_webuser'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.row', [
                'label' => __('global.rb_base_dir_title'),
                'small' => '[(rb_base_dir)]',
                'for' => 'rb_base_dir',
                'element' => __('global.default') . '
                    <span id="default_rb_base_dir">[(base_path)]assets/</span><br>
                    <div class="input-group">' .
                        view('manager::form.inputElement', [
                            'name' => 'rb_base_dir',
                            'value' => $settings['rb_base_dir'],
                            'attributes' => 'onchange="documentDirty=true;" maxlength="255"'
                        ]) .
                        '<div class="input-group-btn">' .
                            view('manager::form.inputElement', [
                                'type' => 'button',
                                'value' => __('global.reset'),
                                'attributes' => 'onclick="reset_path(\'rb_base_dir\');"'
                            ]) .
                        '</div>
                    </div>',
                'comment' => __('global.rb_base_dir_message')
            ])

            <div class="split my-1"></div>

            @include('manager::form.input', [
                'name' => 'rb_base_url',
                'label' => __('global.rb_base_url_title'),
                'small' => '[(rb_base_url)]',
                'value' => $settings['rb_base_url'],
                'attributes' => 'onchange="documentDirty=true;" maxlength="255"',
                'comment' => (isset($disabledSettings['rb_base_url']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.rb_base_url_message'),
                'disabled' => $disabledSettings['rb_base_url'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'clean_uploaded_filename',
                'label' => __('global.clean_uploaded_filename'),
                'small' => '[(clean_uploaded_filename)]',
                'value' => $settings['clean_uploaded_filename'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no')
                ],
                'comment' => (isset($disabledSettings['clean_uploaded_filename']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.clean_uploaded_filename_message'),
                'disabled' => $disabledSettings['clean_uploaded_filename'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'strip_image_paths',
                'label' => __('global.settings_strip_image_paths_title'),
                'small' => '[(strip_image_paths)]',
                'value' => $settings['strip_image_paths'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no')
                ],
                'comment' => (isset($disabledSettings['strip_image_paths']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.settings_strip_image_paths_message'),
                'disabled' => $disabledSettings['strip_image_paths'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.input', [
                'name' => 'maxImageWidth',
                'label' => __('global.maxImageWidth'),
                'small' => '[(maxImageWidth)]',
                'value' => $settings['maxImageWidth'],
                'attributes' => 'onchange="documentDirty=true;" maxlength="4"',
                'comment' => (isset($disabledSettings['maxImageWidth']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.maxImageWidth_message'),
                'disabled' => $disabledSettings['maxImageWidth'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.input', [
                'name' => 'maxImageHeight',
                'label' => __('global.maxImageHeight'),
                'small' => '[(maxImageHeight)]',
                'value' => $settings['maxImageHeight'],
                'attributes' => 'onchange="documentDirty=true;" maxlength="4"',
                'comment' => (isset($disabledSettings['maxImageHeight']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.maxImageHeight_message'),
                'disabled' => $disabledSettings['maxImageHeight'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'clientResize',
                'label' => __('global.clientResize'),
                'small' => '[(clientResize)]',
                'value' => $settings['clientResize'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no')
                ],
                'comment' => (isset($disabledSettings['clientResize']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.clientResize_message'),
                'disabled' => $disabledSettings['clientResize'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'noThumbnailsRecreation',
                'label' => __('global.noThumbnailsRecreation'),
                'small' => '[(noThumbnailsRecreation)]',
                'value' => $settings['noThumbnailsRecreation'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no')
                ],
                'comment' => (isset($disabledSettings['noThumbnailsRecreation']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.noThumbnailsRecreation_message'),
                'disabled' => $disabledSettings['noThumbnailsRecreation'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.input', [
                'name' => 'thumbWidth',
                'label' => __('global.thumbWidth'),
                'small' => '[(thumbWidth)]',
                'value' => $settings['thumbWidth'],
                'attributes' => 'onchange="documentDirty=true;" maxlength="4"',
                'comment' => (isset($disabledSettings['thumbWidth']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.thumbWidth_message'),
                'disabled' => $disabledSettings['thumbWidth'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.input', [
                'name' => 'thumbHeight',
                'label' => __('global.thumbHeight'),
                'small' => '[(thumbHeight)]',
                'value' => $settings['thumbHeight'],
                'attributes' => 'onchange="documentDirty=true;" maxlength="4"',
                'comment' => (isset($disabledSettings['thumbHeight']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.thumbHeight_message'),
                'disabled' => $disabledSettings['thumbHeight'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.input', [
                'name' => 'thumbsDir',
                'label' => __('global.thumbsDir'),
                'small' => '[(thumbsDir)]',
                'value' => $settings['thumbsDir'],
                'attributes' => 'onchange="documentDirty=true;" maxlength="255"',
                'comment' => (isset($disabledSettings['thumbsDir']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.thumbsDir_message'),
                'disabled' => $disabledSettings['thumbsDir'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.input', [
                'name' => 'jpegQuality',
                'label' => __('global.jpegQuality'),
                'small' => '[(jpegQuality)]',
                'value' => $settings['jpegQuality'],
                'attributes' => 'onchange="documentDirty=true;" maxlength="4"',
                'comment' => (isset($disabledSettings['jpegQuality']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.jpegQuality_message'),
                'disabled' => $disabledSettings['jpegQuality'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'denyZipDownload',
                'label' => __('global.denyZipDownload'),
                'small' => '[(denyZipDownload)]',
                'value' => $settings['denyZipDownload'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no')
                ],
                'comment' => (isset($disabledSettings['denyZipDownload']) ? __('global.setting_from_file') . '<br>' : ''),
                'disabled' => $disabledSettings['denyZipDownload'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'denyExtensionRename',
                'label' => __('global.denyExtensionRename'),
                'small' => '[(denyExtensionRename)]',
                'value' => $settings['denyExtensionRename'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no')
                ],
                'comment' => (isset($disabledSettings['denyExtensionRename']) ? __('global.setting_from_file') . '<br>' : ''),
                'disabled' => $disabledSettings['denyExtensionRename'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'showHiddenFiles',
                'label' => __('global.showHiddenFiles'),
                'small' => '[(showHiddenFiles)]',
                'value' => $settings['showHiddenFiles'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no')
                ],
                'comment' => (isset($disabledSettings['showHiddenFiles']) ? __('global.setting_from_file') . '<br>' : ''),
                'disabled' => $disabledSettings['showHiddenFiles'] ?? null
            ])

            <div class="split my-1"></div>
        </div>

        {!! get_by_key($tabEvents, 'OnMiscSettingsRender') !!}
    </div>
</div>
