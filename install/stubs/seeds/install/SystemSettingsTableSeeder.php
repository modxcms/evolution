<?php

namespace EvolutionCMS\Installer\Install;

use Illuminate\Database\Seeder;

class SystemSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('system_settings')->delete();

        \DB::table('system_settings')->insert([
            0  =>
                [
                    'setting_name'  => 'settings_version',
                    'setting_value' => '',
                ],
            2  =>
                [
                    'setting_name'  => 'server_offset_time',
                    'setting_value' => '0',
                ],
            4  =>
                [
                    'setting_name'  => 'modx_charset',
                    'setting_value' => 'UTF-8',
                ],
            5  =>
                [
                    'setting_name'  => 'site_name',
                    'setting_value' => 'My Evolution Site',
                ],
            6  =>
                [
                    'setting_name'  => 'site_start',
                    'setting_value' => '1',
                ],
            7  =>
                [
                    'setting_name'  => 'error_page',
                    'setting_value' => '1',
                ],
            8  =>
                [
                    'setting_name'  => 'unauthorized_page',
                    'setting_value' => '1',
                ],
            9  =>
                [
                    'setting_name'  => 'site_status',
                    'setting_value' => '1',
                ],
            11 =>
                [
                    'setting_name'  => 'default_template',
                    'setting_value' => '3',
                ],
            12 =>
                [
                    'setting_name'  => 'old_template',
                    'setting_value' => '',
                ],
            13 =>
                [
                    'setting_name'  => 'publish_default',
                    'setting_value' => '1',
                ],
            14 =>
                [
                    'setting_name'  => 'friendly_urls',
                    'setting_value' => '1',
                ],
            15 =>
                [
                    'setting_name'  => 'friendly_alias_urls',
                    'setting_value' => '1',
                ],
            16 =>
                [
                    'setting_name'  => 'use_alias_path',
                    'setting_value' => '1',
                ],
            17 =>
                [
                    'setting_name'  => 'cache_type',
                    'setting_value' => '2',
                ],
            18 =>
                [
                    'setting_name'  => 'failed_login_attempts',
                    'setting_value' => '3',
                ],
            19 =>
                [
                    'setting_name'  => 'blocked_minutes',
                    'setting_value' => '60',
                ],
            20 =>
                [
                    'setting_name'  => 'use_captcha',
                    'setting_value' => '0',
                ],
            22 =>
                [
                    'setting_name'  => 'use_editor',
                    'setting_value' => '1',
                ],
            23 =>
                [
                    'setting_name'  => 'use_browser',
                    'setting_value' => '1',
                ],
            25 =>
                [
                    'setting_name'  => 'fck_editor_toolbar',
                    'setting_value' => 'standard',
                ],
            26 =>
                [
                    'setting_name'  => 'fck_editor_autolang',
                    'setting_value' => '0',
                ],
            27 =>
                [
                    'setting_name'  => 'editor_css_path',
                    'setting_value' => '',
                ],
            28 =>
                [
                    'setting_name'  => 'editor_css_selectors',
                    'setting_value' => '',
                ],
            29 =>
                [
                    'setting_name'  => 'upload_maxsize',
                    'setting_value' => '10485760',
                ],
            30 =>
                [
                    'setting_name'  => 'manager_layout',
                    'setting_value' => '4',
                ],
            31 =>
                [
                    'setting_name'  => 'auto_menuindex',
                    'setting_value' => '1',
                ],
            32 =>
                [
                    'setting_name'  => 'session.cookie.lifetime',
                    'setting_value' => '604800',
                ],
            33 =>
                [
                    'setting_name'  => 'mail_check_timeperiod',
                    'setting_value' => '600',
                ],
            34 =>
                [
                    'setting_name'  => 'manager_direction',
                    'setting_value' => 'ltr',
                ],
            35 =>
                [
                    'setting_name'  => 'xhtml_urls',
                    'setting_value' => '0',
                ],
            36 =>
                [
                    'setting_name'  => 'automatic_alias',
                    'setting_value' => '1',
                ],
            37 =>
                [
                    'setting_name'  => 'datetime_format',
                    'setting_value' => 'dd-mm-YYYY',
                ],
            38 =>
                [
                    'setting_name'  => 'warning_visibility',
                    'setting_value' => '0',
                ],
            39 =>
                [
                    'setting_name'  => 'remember_last_tab',
                    'setting_value' => '1',
                ],
            40 =>
                [
                    'setting_name'  => 'enable_bindings',
                    'setting_value' => '1',
                ],
            41 =>
                [
                    'setting_name'  => 'seostrict',
                    'setting_value' => '1',
                ],
            42 =>
                [
                    'setting_name'  => 'number_of_results',
                    'setting_value' => '30',
                ],
            43 =>
                [
                    'setting_name'  => 'theme_refresher',
                    'setting_value' => '',
                ],
            44 =>
                [
                    'setting_name'  => 'show_picker',
                    'setting_value' => '0',
                ],
            45 =>
                [
                    'setting_name'  => 'show_newresource_btn',
                    'setting_value' => '0',
                ],
            46 =>
                [
                    'setting_name'  => 'show_fullscreen_btn',
                    'setting_value' => '0',
                ],
            47 =>
                [
                    'setting_name'  => 'email_sender_method',
                    'setting_value' => '1',
                ],
            48 =>
                [
                    'setting_name'  => 'smtp_autotls',
                    'setting_value' => '0',
                ],
        ]);


    }
}
