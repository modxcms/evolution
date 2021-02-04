<!-- Friendly URL settings  -->
<div class="tab-page" id="tabPage3">
    <h2 class="tab">{{ ManagerTheme::getLexicon('settings_furls') }}</h2>
    <script type="text/javascript">tpSettings.addTabPage(document.getElementById('tabPage3'));</script>
    <div class="container container-body">

        @include('manager::form.radio', [
            'name' => 'friendly_urls',
            'label' => ManagerTheme::getLexicon('friendlyurls_title'),
            'small' => '[(friendly_urls)]',
            'value' => $settings['friendly_urls'],
            'options' => [
                1 => [
                    'text' => ManagerTheme::getLexicon('yes'),
                    'attributes' => 'id="furlRowOn"'
                ],
                0 => [
                    'text' => ManagerTheme::getLexicon('no'),
                    'attributes' => 'id="furlRowOff"'
                ],
            ],
            'comment' => ManagerTheme::getLexicon('friendlyurls_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.radio', [
            'name' => 'xhtml_urls',
            'label' => ManagerTheme::getLexicon('xhtml_urls_title'),
            'small' => '[(xhtml_urls)]',
            'value' => $settings['xhtml_urls'],
            'options' => [
                1 => [
                    'text' => ManagerTheme::getLexicon('yes'),
                    'attributes' => 'id="furlRowOn"'
                ],
                0 => [
                    'text' => ManagerTheme::getLexicon('no'),
                    'attributes' => 'id="furlRowOff"'
                ],
            ],
            'comment' => ManagerTheme::getLexicon('xhtml_urls_message')
        ])

        <div class="split my-1"></div>

        <div class="furlRow" @if(!$settings['friendly_urls']) style="display: none" @endif>

            @include('manager::form.input', [
                'name' => 'friendly_url_prefix',
                'label' => ManagerTheme::getLexicon('friendlyurlsprefix_title'),
                'small' => '[(friendly_url_prefix)]',
                'value' => $settings['friendly_url_prefix'],
                'attributes' => 'onchange="documentDirty=true;" maxlength="50"',
                'comment' => ManagerTheme::getLexicon('friendlyurlsprefix_message')
            ])

            <div class="split my-1"></div>

            @include('manager::form.input', [
                'name' => 'friendly_url_suffix',
                'label' => ManagerTheme::getLexicon('friendlyurlsuffix_title'),
                'small' => '[(friendly_url_suffix)]',
                'value' => $settings['friendly_url_suffix'],
                'attributes' => 'onchange="documentDirty=true;" maxlength="50"',
                'comment' => ManagerTheme::getLexicon('friendlyurlsuffix_message')
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'make_folders',
                'label' => ManagerTheme::getLexicon('make_folders_title'),
                'small' => '[(make_folders)]',
                'value' => $settings['make_folders'],
                'options' => [
                    1 => ManagerTheme::getLexicon('yes'),
                    0 => ManagerTheme::getLexicon('no'),
                ],
                'comment' => ManagerTheme::getLexicon('make_folders_message')
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'seostrict',
                'label' => ManagerTheme::getLexicon('seostrict_title'),
                'small' => '[(seostrict)]',
                'value' => $settings['seostrict'],
                'options' => [
                    1 => ManagerTheme::getLexicon('yes'),
                    0 => ManagerTheme::getLexicon('no'),
                ],
                'comment' => ManagerTheme::getLexicon('seostrict_message')
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'full_aliaslisting',
                'label' => ManagerTheme::getLexicon('full_aliaslisting_title'),
                'small' => '[(full_aliaslisting)]',
                'value' => $settings['full_aliaslisting'] ?? 0,
                'options' => [
                    1 => ManagerTheme::getLexicon('yes'),
                    0 => ManagerTheme::getLexicon('no'),
                ],
                'comment' => ManagerTheme::getLexicon('full_aliaslisting_title')
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'aliaslistingfolder',
                'label' => ManagerTheme::getLexicon('aliaslistingfolder_title'),
                'small' => '[(aliaslistingfolder)]',
                'value' => $settings['aliaslistingfolder'],
                'options' => [
                    1 => ManagerTheme::getLexicon('yes'),
                    0 => ManagerTheme::getLexicon('no'),
                ],
                'comment' => ManagerTheme::getLexicon('aliaslistingfolder_title')
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'friendly_alias_urls',
                'label' => ManagerTheme::getLexicon('friendly_alias_title'),
                'small' => '[(friendly_alias_urls)]',
                'value' => $settings['friendly_alias_urls'],
                'options' => [
                    1 => ManagerTheme::getLexicon('yes'),
                    0 => ManagerTheme::getLexicon('no'),
                ],
                'comment' => ManagerTheme::getLexicon('friendly_alias_message')
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'use_alias_path',
                'label' => ManagerTheme::getLexicon('use_alias_path_title'),
                'small' => '[(use_alias_path)]',
                'value' => $settings['use_alias_path'],
                'options' => [
                    1 => ManagerTheme::getLexicon('yes'),
                    0 => ManagerTheme::getLexicon('no'),
                ],
                'comment' => ManagerTheme::getLexicon('use_alias_path_message')
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'allow_duplicate_alias',
                'label' => ManagerTheme::getLexicon('duplicate_alias_title'),
                'small' => '[(allow_duplicate_alias)]',
                'value' => $settings['allow_duplicate_alias'],
                'options' => [
                    1 => ManagerTheme::getLexicon('yes'),
                    0 => ManagerTheme::getLexicon('no'),
                ],
                'comment' => ManagerTheme::getLexicon('duplicate_alias_message')
            ])

            <div class="split my-1"></div>

            @include('manager::form.radio', [
                'name' => 'automatic_alias',
                'label' => ManagerTheme::getLexicon('automatic_alias_title'),
                'small' => '[(automatic_alias)]',
                'value' => $settings['automatic_alias'],
                'options' => [
                    1 => ManagerTheme::getLexicon('yes'),
                    0 => ManagerTheme::getLexicon('no'),
                ],
                'comment' => ManagerTheme::getLexicon('automatic_alias_message')
            ])

            <div class="split my-1"></div>

            {!! get_by_key($tabEvents, 'OnFriendlyURLSettingsRender') !!}
        </div>
    </div>
</div>
