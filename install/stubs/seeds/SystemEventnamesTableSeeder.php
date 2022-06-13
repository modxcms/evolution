<?php

use Illuminate\Database\Seeder;

class SystemEventnamesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('system_eventnames')->delete();
        
        \DB::table('system_eventnames')->insert(array (
            0 => 
            array (
                'name' => 'OnDocPublished',
                'service' => 5,
                'groupname' => '',
            ),
            1 => 
            array (
                'name' => 'OnDocUnPublished',
                'service' => 5,
                'groupname' => '',
            ),
            2 => 
            array (
                'name' => 'OnWebPagePrerender',
                'service' => 5,
                'groupname' => '',
            ),
            3 => 
            array (
                'name' => 'OnWebLogin',
                'service' => 3,
                'groupname' => '',
            ),
            4 => 
            array (
                'name' => 'OnBeforeWebLogout',
                'service' => 3,
                'groupname' => '',
            ),
            5 => 
            array (
                'name' => 'OnWebLogout',
                'service' => 3,
                'groupname' => '',
            ),
            6 => 
            array (
                'name' => 'OnWebSaveUser',
                'service' => 3,
                'groupname' => '',
            ),
            7 => 
            array (
                'name' => 'OnWebDeleteUser',
                'service' => 3,
                'groupname' => '',
            ),
            8 => 
            array (
                'name' => 'OnWebChangePassword',
                'service' => 3,
                'groupname' => '',
            ),
            9 => 
            array (
                'name' => 'OnWebCreateGroup',
                'service' => 3,
                'groupname' => '',
            ),
            10 => 
            array (
                'name' => 'OnManagerLogin',
                'service' => 2,
                'groupname' => '',
            ),
            11 => 
            array (
                'name' => 'OnBeforeManagerLogout',
                'service' => 2,
                'groupname' => '',
            ),
            12 => 
            array (
                'name' => 'OnManagerLogout',
                'service' => 2,
                'groupname' => '',
            ),
            13 => 
            array (
                'name' => 'OnManagerSaveUser',
                'service' => 2,
                'groupname' => '',
            ),
            14 => 
            array (
                'name' => 'OnManagerDeleteUser',
                'service' => 2,
                'groupname' => '',
            ),
            15 => 
            array (
                'name' => 'OnManagerChangePassword',
                'service' => 2,
                'groupname' => '',
            ),
            16 => 
            array (
                'name' => 'OnManagerCreateGroup',
                'service' => 2,
                'groupname' => '',
            ),
            17 => 
            array (
                'name' => 'OnBeforeCacheUpdate',
                'service' => 4,
                'groupname' => '',
            ),
            18 => 
            array (
                'name' => 'OnCacheUpdate',
                'service' => 4,
                'groupname' => '',
            ),
            19 => 
            array (
                'name' => 'OnMakePageCacheKey',
                'service' => 4,
                'groupname' => '',
            ),
            20 => 
            array (
                'name' => 'OnLoadWebPageCache',
                'service' => 4,
                'groupname' => '',
            ),
            21 => 
            array (
                'name' => 'OnBeforeSaveWebPageCache',
                'service' => 4,
                'groupname' => '',
            ),
            22 => 
            array (
                'name' => 'OnChunkFormPrerender',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            23 => 
            array (
                'name' => 'OnChunkFormRender',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            24 => 
            array (
                'name' => 'OnBeforeChunkFormSave',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            25 => 
            array (
                'name' => 'OnChunkFormSave',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            26 => 
            array (
                'name' => 'OnBeforeChunkFormDelete',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            27 => 
            array (
                'name' => 'OnChunkFormDelete',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            28 => 
            array (
                'name' => 'OnDocFormPrerender',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            29 => 
            array (
                'name' => 'OnDocFormRender',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            30 => 
            array (
                'name' => 'OnBeforeDocFormSave',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            31 => 
            array (
                'name' => 'OnDocFormSave',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            32 => 
            array (
                'name' => 'OnBeforeDocFormDelete',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            33 => 
            array (
                'name' => 'OnDocFormDelete',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            34 => 
            array (
                'name' => 'OnDocFormUnDelete',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            35 => 
            array (
                'name' => 'onBeforeMoveDocument',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            36 => 
            array (
                'name' => 'onAfterMoveDocument',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            37 => 
            array (
                'name' => 'OnPluginFormPrerender',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            38 => 
            array (
                'name' => 'OnPluginFormRender',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            39 => 
            array (
                'name' => 'OnBeforePluginFormSave',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            40 => 
            array (
                'name' => 'OnPluginFormSave',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            41 => 
            array (
                'name' => 'OnBeforePluginFormDelete',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            42 => 
            array (
                'name' => 'OnPluginFormDelete',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            43 => 
            array (
                'name' => 'OnSnipFormPrerender',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            44 => 
            array (
                'name' => 'OnSnipFormRender',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            45 => 
            array (
                'name' => 'OnBeforeSnipFormSave',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            46 => 
            array (
                'name' => 'OnSnipFormSave',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            47 => 
            array (
                'name' => 'OnBeforeSnipFormDelete',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            48 => 
            array (
                'name' => 'OnSnipFormDelete',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            49 => 
            array (
                'name' => 'OnTempFormPrerender',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            50 => 
            array (
                'name' => 'OnTempFormRender',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            51 => 
            array (
                'name' => 'OnBeforeTempFormSave',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            52 => 
            array (
                'name' => 'OnTempFormSave',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            53 => 
            array (
                'name' => 'OnBeforeTempFormDelete',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            54 => 
            array (
                'name' => 'OnTempFormDelete',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            55 => 
            array (
                'name' => 'OnTVFormPrerender',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            56 => 
            array (
                'name' => 'OnTVFormRender',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            57 => 
            array (
                'name' => 'OnBeforeTVFormSave',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            58 => 
            array (
                'name' => 'OnTVFormSave',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            59 => 
            array (
                'name' => 'OnBeforeTVFormDelete',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            60 => 
            array (
                'name' => 'OnTVFormDelete',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            61 => 
            array (
                'name' => 'OnUserFormPrerender',
                'service' => 1,
                'groupname' => 'Users',
            ),
            62 => 
            array (
                'name' => 'OnUserFormRender',
                'service' => 1,
                'groupname' => 'Users',
            ),
            63 => 
            array (
                'name' => 'OnBeforeUserSave',
                'service' => 1,
                'groupname' => 'Users',
            ),
            64 => 
            array (
                'name' => 'OnUserSave',
                'service' => 1,
                'groupname' => 'Users',
            ),
            65 => 
            array (
                'name' => 'OnBeforeUserDelete',
                'service' => 1,
                'groupname' => 'Users',
            ),
            66 => 
            array (
                'name' => 'OnUserDelete',
                'service' => 1,
                'groupname' => 'Users',
            ),
            73 => 
            array (
                'name' => 'OnSiteRefresh',
                'service' => 1,
                'groupname' => '',
            ),
            74 => 
            array (
                'name' => 'OnFileManagerUpload',
                'service' => 1,
                'groupname' => '',
            ),
            75 => 
            array (
                'name' => 'OnModFormPrerender',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            76 => 
            array (
                'name' => 'OnModFormRender',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            77 => 
            array (
                'name' => 'OnBeforeModFormDelete',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            78 => 
            array (
                'name' => 'OnModFormDelete',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            79 => 
            array (
                'name' => 'OnBeforeModFormSave',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            80 => 
            array (
                'name' => 'OnModFormSave',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            81 => 
            array (
                'name' => 'OnBeforeWebLogin',
                'service' => 3,
                'groupname' => '',
            ),
            82 => 
            array (
                'name' => 'OnWebAuthentication',
                'service' => 3,
                'groupname' => '',
            ),
            83 => 
            array (
                'name' => 'OnBeforeManagerLogin',
                'service' => 2,
                'groupname' => '',
            ),
            84 => 
            array (
                'name' => 'OnManagerAuthentication',
                'service' => 2,
                'groupname' => '',
            ),
            85 => 
            array (
                'name' => 'OnSiteSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            86 => 
            array (
                'name' => 'OnFriendlyURLSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            87 => 
            array (
                'name' => 'OnUserSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            88 => 
            array (
                'name' => 'OnInterfaceSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            89 => 
            array (
                'name' => 'OnSecuritySettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            90 => 
            array (
                'name' => 'OnFileManagerSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            91 => 
            array (
                'name' => 'OnMiscSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            92 => 
            array (
                'name' => 'OnRichTextEditorRegister',
                'service' => 1,
                'groupname' => 'RichText Editor',
            ),
            93 => 
            array (
                'name' => 'OnRichTextEditorInit',
                'service' => 1,
                'groupname' => 'RichText Editor',
            ),
            94 => 
            array (
                'name' => 'OnManagerPageInit',
                'service' => 2,
                'groupname' => '',
            ),
            95 => 
            array (
                'name' => 'OnWebPageInit',
                'service' => 5,
                'groupname' => '',
            ),
            96 => 
            array (
                'name' => 'OnLoadDocumentObject',
                'service' => 5,
                'groupname' => '',
            ),
            97 => 
            array (
                'name' => 'OnBeforeLoadDocumentObject',
                'service' => 5,
                'groupname' => '',
            ),
            98 => 
            array (
                'name' => 'OnAfterLoadDocumentObject',
                'service' => 5,
                'groupname' => '',
            ),
            99 => 
            array (
                'name' => 'OnLoadWebDocument',
                'service' => 5,
                'groupname' => '',
            ),
            100 => 
            array (
                'name' => 'OnParseDocument',
                'service' => 5,
                'groupname' => '',
            ),
            101 => 
            array (
                'name' => 'OnParseProperties',
                'service' => 5,
                'groupname' => '',
            ),
            102 => 
            array (
                'name' => 'OnBeforeParseParams',
                'service' => 5,
                'groupname' => '',
            ),
            103 => 
            array (
                'name' => 'OnManagerLoginFormRender',
                'service' => 2,
                'groupname' => '',
            ),
            104 => 
            array (
                'name' => 'OnWebPageComplete',
                'service' => 5,
                'groupname' => '',
            ),
            105 => 
            array (
                'name' => 'OnLogPageHit',
                'service' => 5,
                'groupname' => '',
            ),
            106 => 
            array (
                'name' => 'OnBeforeManagerPageInit',
                'service' => 2,
                'groupname' => '',
            ),
            107 => 
            array (
                'name' => 'OnBeforeEmptyTrash',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            108 => 
            array (
                'name' => 'OnEmptyTrash',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            109 => 
            array (
                'name' => 'OnManagerLoginFormPrerender',
                'service' => 2,
                'groupname' => '',
            ),
            110 => 
            array (
                'name' => 'OnStripAlias',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            111 => 
            array (
                'name' => 'OnMakeDocUrl',
                'service' => 5,
                'groupname' => '',
            ),
            112 => 
            array (
                'name' => 'OnBeforeLoadExtension',
                'service' => 5,
                'groupname' => '',
            ),
            113 => 
            array (
                'name' => 'OnCreateDocGroup',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            114 => 
            array (
                'name' => 'OnManagerWelcomePrerender',
                'service' => 2,
                'groupname' => '',
            ),
            115 => 
            array (
                'name' => 'OnManagerWelcomeHome',
                'service' => 2,
                'groupname' => '',
            ),
            116 => 
            array (
                'name' => 'OnManagerWelcomeRender',
                'service' => 2,
                'groupname' => '',
            ),
            117 => 
            array (
                'name' => 'OnBeforeDocDuplicate',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            118 => 
            array (
                'name' => 'OnDocDuplicate',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            119 => 
            array (
                'name' => 'OnManagerMainFrameHeaderHTMLBlock',
                'service' => 2,
                'groupname' => '',
            ),
            120 => 
            array (
                'name' => 'OnManagerPreFrameLoader',
                'service' => 2,
                'groupname' => '',
            ),
            121 => 
            array (
                'name' => 'OnManagerFrameLoader',
                'service' => 2,
                'groupname' => '',
            ),
            122 => 
            array (
                'name' => 'OnManagerTreeInit',
                'service' => 2,
                'groupname' => '',
            ),
            123 => 
            array (
                'name' => 'OnManagerTreePrerender',
                'service' => 2,
                'groupname' => '',
            ),
            124 => 
            array (
                'name' => 'OnManagerTreeRender',
                'service' => 2,
                'groupname' => '',
            ),
            125 => 
            array (
                'name' => 'OnManagerNodePrerender',
                'service' => 2,
                'groupname' => '',
            ),
            126 => 
            array (
                'name' => 'OnManagerNodeRender',
                'service' => 2,
                'groupname' => '',
            ),
            127 => 
            array (
                'name' => 'OnManagerMenuPrerender',
                'service' => 2,
                'groupname' => '',
            ),
            128 => 
            array (
                'name' => 'OnManagerTopPrerender',
                'service' => 2,
                'groupname' => '',
            ),
            129 => 
            array (
                'name' => 'OnDocFormTemplateRender',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            130 => 
            array (
                'name' => 'OnBeforeMinifyCss',
                'service' => 1,
                'groupname' => '',
            ),
            131 => 
            array (
                'name' => 'OnPageUnauthorized',
                'service' => 1,
                'groupname' => '',
            ),
            132 => 
            array (
                'name' => 'OnPageNotFound',
                'service' => 1,
                'groupname' => '',
            ),
            133 => 
            array (
                'name' => 'OnFileBrowserUpload',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            134 => 
            array (
                'name' => 'OnBeforeFileBrowserUpload',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            135 => 
            array (
                'name' => 'OnFileBrowserDelete',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            136 => 
            array (
                'name' => 'OnBeforeFileBrowserDelete',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            137 => 
            array (
                'name' => 'OnFileBrowserInit',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            138 => 
            array (
                'name' => 'OnFileBrowserMove',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            139 => 
            array (
                'name' => 'OnBeforeFileBrowserMove',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            140 => 
            array (
                'name' => 'OnFileBrowserCopy',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            141 => 
            array (
                'name' => 'OnBeforeFileBrowserCopy',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            142 => 
            array (
                'name' => 'OnBeforeFileBrowserRename',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            143 => 
            array (
                'name' => 'OnFileBrowserRename',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            144 => 
            array (
                'name' => 'OnLogEvent',
                'service' => 1,
                'groupname' => 'Log Event',
            ),
            145 => 
            array (
                'name' => 'OnLoadSettings',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
        ));
    }
}
