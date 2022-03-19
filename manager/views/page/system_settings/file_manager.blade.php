<!-- Miscellaneous settings -->
<div class="tab-page" id="tabPage7">
    <h2 class="tab">{{ __('global.settings_misc') }}</h2>
    <script type="text/javascript">tpSettings.addTabPage(document.getElementById('tabPage7'));</script>
    <div class="container container-body">

        @include('manager::form.row', [
            'label' => __('global.filemanager_path_title'),
            'small' => '[(filemanager_path)]',
            'for' => 'filemanager_path',
            'element' => __('global.default') . '
                <span id="default_filemanager_path">[(base_path)]</span><br>
                <div class="input-group">' .
                    view('manager::form.inputElement', [
                        'name' => 'filemanager_path',
                        'value' => $settings['filemanager_path'],
                        'attributes' => 'onChange="documentDirty=true;" maxlength="255"'
                    ]) .
                    '<div class="input-group-btn">' .
                        view('manager::form.inputElement', [
                            'type' => 'button',
                            'name' => 'reset_filemanager_path',
                            'value' => __('global.reset'),
                            'attributes' => 'onclick="reset_path(\'filemanager_path\');"'
                        ]) .
                    '</div>
                </div>',
            'comment' => __('global.filemanager_path_message')
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'upload_files',
            'label' => __('global.uploadable_files_title'),
            'small' => '[(upload_files)]',
            'value' => $settings['upload_files'],
            'attributes' => 'onchange="documentDirty=true;" maxlength="255"',
            'comment' => (isset($disabledSettings['upload_files']) ? __('global.setting_from_file') . '<br>' : '') .
                __('global.uploadable_files_message'),
            'disabled' => $disabledSettings['upload_files'] ?? null
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'upload_images',
            'label' => __('global.uploadable_images_title'),
            'small' => '[(upload_images)]',
            'value' => $settings['upload_images'],
            'attributes' => 'onchange="documentDirty=true;" maxlength="255"',
            'comment' => (isset($disabledSettings['upload_images']) ? __('global.setting_from_file') . '<br>' : '') .
                __('global.uploadable_images_message'),
            'disabled' => $disabledSettings['upload_images'] ?? null
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'upload_media',
            'label' => __('global.uploadable_media_title'),
            'small' => '[(upload_media)]',
            'value' => $settings['upload_media'],
            'attributes' => 'onchange="documentDirty=true;" maxlength="255"',
            'comment' => (isset($disabledSettings['upload_media']) ? __('global.setting_from_file') . '<br>' : '') .
                __('global.uploadable_media_message'),
            'disabled' => $disabledSettings['upload_media'] ?? null
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'upload_maxsize',
            'label' => __('global.upload_maxsize_title'),
            'small' => '[(upload_maxsize)]',
            'value' => $settings['upload_maxsize'],
            'attributes' => 'onchange="documentDirty=true;" maxlength="255"',
            'comment' => (isset($disabledSettings['upload_maxsize']) ? __('global.setting_from_file') . '<br>' : '') .
                __('global.upload_maxsize_message'),
            'disabled' => $disabledSettings['upload_maxsize'] ?? null
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'new_file_permissions',
            'label' => __('global.new_file_permissions_title'),
            'small' => '[(new_file_permissions)]',
            'value' => $settings['new_file_permissions'],
            'attributes' => 'onchange="documentDirty=true;" maxlength="4"',
            'comment' => (isset($disabledSettings['new_file_permissions']) ? __('global.setting_from_file') . '<br>' : '') .
                __('global.new_file_permissions_message'),
            'disabled' => $disabledSettings['new_file_permissions'] ?? null
        ])

        <div class="split my-1"></div>

        @include('manager::form.input', [
            'name' => 'new_folder_permissions',
            'label' => __('global.new_folder_permissions_title'),
            'small' => '[(new_folder_permissions)]',
            'value' => $settings['new_folder_permissions'],
            'attributes' => 'onchange="documentDirty=true;" maxlength="4"',
            'comment' => (isset($disabledSettings['new_folder_permissions']) ? __('global.setting_from_file') . '<br>' : '') .
                __('global.new_folder_permissions_message'),
            'disabled' => $disabledSettings['new_folder_permissions'] ?? null
        ])

        <div class="split my-1"></div>

        {!! get_by_key($tabEvents, 'OnFileManagerSettingsRender') !!}
    </div>
</div>

