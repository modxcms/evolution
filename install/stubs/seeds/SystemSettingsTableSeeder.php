<?php

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
        
        \DB::table('system_settings')->insert(array (
            0 => 
            array (
                'setting_name' => 'settings_version',
                'setting_value' => '',
            ),
            2 => 
            array (
                'setting_name' => 'server_offset_time',
                'setting_value' => '0',
            ),
            4 => 
            array (
                'setting_name' => 'modx_charset',
                'setting_value' => 'UTF-8',
            ),
            5 => 
            array (
                'setting_name' => 'site_name',
                'setting_value' => 'My Evolution Site',
            ),
            6 => 
            array (
                'setting_name' => 'site_start',
                'setting_value' => '1',
            ),
            7 => 
            array (
                'setting_name' => 'error_page',
                'setting_value' => '1',
            ),
            8 => 
            array (
                'setting_name' => 'unauthorized_page',
                'setting_value' => '1',
            ),
            9 => 
            array (
                'setting_name' => 'site_status',
                'setting_value' => '1',
            ),
            11 => 
            array (
                'setting_name' => 'default_template',
                'setting_value' => '3',
            ),
            12 => 
            array (
                'setting_name' => 'old_template',
                'setting_value' => '',
            ),
            13 => 
            array (
                'setting_name' => 'publish_default',
                'setting_value' => '1',
            ),
            14 => 
            array (
                'setting_name' => 'friendly_urls',
                'setting_value' => '1',
            ),
            15 => 
            array (
                'setting_name' => 'friendly_alias_urls',
                'setting_value' => '1',
            ),
            16 => 
            array (
                'setting_name' => 'use_alias_path',
                'setting_value' => '1',
            ),
            17 => 
            array (
                'setting_name' => 'cache_type',
                'setting_value' => '2',
            ),
            18 => 
            array (
                'setting_name' => 'failed_login_attempts',
                'setting_value' => '3',
            ),
            19 => 
            array (
                'setting_name' => 'blocked_minutes',
                'setting_value' => '60',
            ),
            20 => 
            array (
                'setting_name' => 'use_captcha',
                'setting_value' => '0',
            ),
            22 => 
            array (
                'setting_name' => 'use_editor',
                'setting_value' => '1',
            ),
            23 => 
            array (
                'setting_name' => 'use_browser',
                'setting_value' => '1',
            ),
            25 => 
            array (
                'setting_name' => 'fck_editor_toolbar',
                'setting_value' => 'standard',
            ),
            26 => 
            array (
                'setting_name' => 'fck_editor_autolang',
                'setting_value' => '0',
            ),
            27 => 
            array (
                'setting_name' => 'editor_css_path',
                'setting_value' => '',
            ),
            28 => 
            array (
                'setting_name' => 'editor_css_selectors',
                'setting_value' => '',
            ),
            29 => 
            array (
                'setting_name' => 'upload_maxsize',
                'setting_value' => '10485760',
            ),
            30 => 
            array (
                'setting_name' => 'manager_layout',
                'setting_value' => '4',
            ),
            31 => 
            array (
                'setting_name' => 'auto_menuindex',
                'setting_value' => '1',
            ),
            32 => 
            array (
                'setting_name' => 'session.cookie.lifetime',
                'setting_value' => '604800',
            ),
            33 => 
            array (
                'setting_name' => 'mail_check_timeperiod',
                'setting_value' => '600',
            ),
            34 => 
            array (
                'setting_name' => 'manager_direction',
                'setting_value' => 'ltr',
            ),
            35 => 
            array (
                'setting_name' => 'xhtml_urls',
                'setting_value' => '0',
            ),
            36 => 
            array (
                'setting_name' => 'automatic_alias',
                'setting_value' => '1',
            ),
            37 => 
            array (
                'setting_name' => 'datetime_format',
                'setting_value' => 'dd-mm-YYYY',
            ),
            38 => 
            array (
                'setting_name' => 'warning_visibility',
                'setting_value' => '0',
            ),
            39 => 
            array (
                'setting_name' => 'remember_last_tab',
                'setting_value' => '1',
            ),
            40 => 
            array (
                'setting_name' => 'enable_bindings',
                'setting_value' => '1',
            ),
            41 => 
            array (
                'setting_name' => 'seostrict',
                'setting_value' => '1',
            ),
            42 => 
            array (
                'setting_name' => 'number_of_results',
                'setting_value' => '30',
            ),
            43 => 
            array (
                'setting_name' => 'theme_refresher',
                'setting_value' => '',
            ),
            44 => 
            array (
                'setting_name' => 'show_picker',
                'setting_value' => '0',
            ),
            45 => 
            array (
                'setting_name' => 'show_newresource_btn',
                'setting_value' => '0',
            ),
            46 => 
            array (
                'setting_name' => 'show_fullscreen_btn',
                'setting_value' => '0',
            ),
            47 => 
            array (
                'setting_name' => 'email_sender_method',
                'setting_value' => '1',
            ),
            48 =>
            array (
                'setting_name' => 'smtp_autotls',
                'setting_value' => '0',
            ),
            49 =>
            array (
                'setting_name' => 'auto_template_logic',
                'setting_value' => '1',
            ),
        ));
        
        
    }
}