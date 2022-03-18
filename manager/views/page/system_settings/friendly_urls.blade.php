<!-- Friendly URL settings  -->
<div class="tab-page" id="tabPage3">
    <h2 class="tab">{{ __('global.settings_furls') }}</h2>
    <script type="text/javascript">tpSettings.addTabPage(document.getElementById('tabPage3'));</script>
    <div class="container container-body">

        @include('manager::form.radio', [
            'name' => 'friendly_urls',
            'label' => __('global.friendlyurls_title'),
            'small' => '[(friendly_urls)]',
            'value' => $settings['friendly_urls'],
            'options' => [
                1 => [
                    'text' => __('global.yes'),
                    'attributes' => 'id="furlRowOn"'
                ],
                0 => [
                    'text' => __('global.no'),
                    'attributes' => 'id="furlRowOff"'
                ],
            ],
            'comment' => (isset($disabledSettings['friendly_urls']) ? __('global.setting_from_file') . '<br>' : '') .
                __('global.friendlyurls_message'),
            'disabled' => $disabledSettings['friendly_urls'] ?? null
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'xhtml_urls',
            'label' => __('global.xhtml_urls_title'),
            'small' => '[(xhtml_urls)]',
            'value' => $settings['xhtml_urls'],
            'options' => [
                1 => [
                    'text' => __('global.yes'),
                    'attributes' => 'id="furlRowOn"'
                ],
                0 => [
                    'text' => __('global.no'),
                    'attributes' => 'id="furlRowOff"'
                ],
            ],
            'comment' => (isset($disabledSettings['xhtml_urls']) ? __('global.setting_from_file') . '<br>' : '') .
                __('global.xhtml_urls_message'),
            'disabled' => $disabledSettings['xhtml_urls'] ?? null
        ])

        <div class="split my-1"></div>

        <div class="furlRow" @if(!$settings['friendly_urls']) style="display: none" @endif>

            @include('manager::form.input', [
                'name' => 'friendly_url_prefix',
                'label' => __('global.friendlyurlsprefix_title'),
                'small' => '[(friendly_url_prefix)]',
                'value' => $settings['friendly_url_prefix'],
                'attributes' => 'onchange="documentDirty=true;" maxlength="50"',
                'comment' => (isset($disabledSettings['friendly_url_prefix']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.friendlyurlsprefix_message'),
                'disabled' => $disabledSettings['friendly_url_prefix'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.input', [
                'name' => 'friendly_url_suffix',
                'label' => __('global.friendlyurlsuffix_title'),
                'small' => '[(friendly_url_suffix)]',
                'value' => $settings['friendly_url_suffix'],
                'attributes' => 'onchange="documentDirty=true;" maxlength="50"',
                'comment' => (isset($disabledSettings['friendly_url_suffix']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.friendlyurlsuffix_message'),
                'disabled' => $disabledSettings['friendly_url_suffix'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'make_folders',
                'label' => __('global.make_folders_title'),
                'small' => '[(make_folders)]',
                'value' => $settings['make_folders'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no'),
                ],
                'comment' => (isset($disabledSettings['make_folders']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.make_folders_message'),
                'disabled' => $disabledSettings['make_folders'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'seostrict',
                'label' => __('global.seostrict_title'),
                'small' => '[(seostrict)]',
                'value' => $settings['seostrict'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no'),
                ],
                'comment' => (isset($disabledSettings['seostrict']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.seostrict_message'),
                'disabled' => $disabledSettings['seostrict'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'full_aliaslisting',
                'label' => __('global.full_aliaslisting_title'),
                'small' => '[(full_aliaslisting)]',
                'value' => $settings['full_aliaslisting'] ?? 0,
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no'),
                ],
                'comment' => (isset($disabledSettings['full_aliaslisting']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.full_aliaslisting_title'),
                'disabled' => $disabledSettings['full_aliaslisting'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'aliaslistingfolder',
                'label' => __('global.aliaslistingfolder_title'),
                'small' => '[(aliaslistingfolder)]',
                'value' => $settings['aliaslistingfolder'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no'),
                ],
                'comment' => (isset($disabledSettings['aliaslistingfolder']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.aliaslistingfolder_title'),
                'disabled' => $disabledSettings['aliaslistingfolder'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'friendly_alias_urls',
                'label' => __('global.friendly_alias_title'),
                'small' => '[(friendly_alias_urls)]',
                'value' => $settings['friendly_alias_urls'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no'),
                ],
                'comment' => (isset($disabledSettings['friendly_alias_urls']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.friendly_alias_message'),
                'disabled' => $disabledSettings['friendly_alias_urls'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'use_alias_path',
                'label' => __('global.use_alias_path_title'),
                'small' => '[(use_alias_path)]',
                'value' => $settings['use_alias_path'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no'),
                ],
                'comment' => (isset($disabledSettings['use_alias_path']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.use_alias_path_message'),
                'disabled' => $disabledSettings['use_alias_path'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'allow_duplicate_alias',
                'label' => __('global.duplicate_alias_title'),
                'small' => '[(allow_duplicate_alias)]',
                'value' => $settings['allow_duplicate_alias'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no'),
                ],
                'comment' => (isset($disabledSettings['allow_duplicate_alias']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.duplicate_alias_message'),
                'disabled' => $disabledSettings['allow_duplicate_alias'] ?? null
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'automatic_alias',
                'label' => __('global.automatic_alias_title'),
                'small' => '[(automatic_alias)]',
                'value' => $settings['automatic_alias'],
                'options' => [
                    1 => __('global.yes'),
                    0 => __('global.no'),
                ],
                'comment' => (isset($disabledSettings['automatic_alias']) ? __('global.setting_from_file') . '<br>' : '') .
                    __('global.automatic_alias_message'),
                'disabled' => $disabledSettings['automatic_alias'] ?? null
            ])

            <div class="split my-1"></div>

            {!! get_by_key($tabEvents, 'OnFriendlyURLSettingsRender') !!}
        </div>
    </div>
</div>
