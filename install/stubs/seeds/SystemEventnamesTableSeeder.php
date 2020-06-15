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
                'id' => 1,
                'name' => 'OnDocPublished',
                'service' => 5,
                'groupname' => '',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'OnDocUnPublished',
                'service' => 5,
                'groupname' => '',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'OnWebPagePrerender',
                'service' => 5,
                'groupname' => '',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'OnWebLogin',
                'service' => 3,
                'groupname' => '',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'OnBeforeWebLogout',
                'service' => 3,
                'groupname' => '',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'OnWebLogout',
                'service' => 3,
                'groupname' => '',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'OnWebSaveUser',
                'service' => 3,
                'groupname' => '',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'OnWebDeleteUser',
                'service' => 3,
                'groupname' => '',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'OnWebChangePassword',
                'service' => 3,
                'groupname' => '',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'OnWebCreateGroup',
                'service' => 3,
                'groupname' => '',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'OnManagerLogin',
                'service' => 2,
                'groupname' => '',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'OnBeforeManagerLogout',
                'service' => 2,
                'groupname' => '',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'OnManagerLogout',
                'service' => 2,
                'groupname' => '',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'OnManagerSaveUser',
                'service' => 2,
                'groupname' => '',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'OnManagerDeleteUser',
                'service' => 2,
                'groupname' => '',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'OnManagerChangePassword',
                'service' => 2,
                'groupname' => '',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'OnManagerCreateGroup',
                'service' => 2,
                'groupname' => '',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'OnBeforeCacheUpdate',
                'service' => 4,
                'groupname' => '',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'OnCacheUpdate',
                'service' => 4,
                'groupname' => '',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'OnMakePageCacheKey',
                'service' => 4,
                'groupname' => '',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'OnLoadWebPageCache',
                'service' => 4,
                'groupname' => '',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'OnBeforeSaveWebPageCache',
                'service' => 4,
                'groupname' => '',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'OnChunkFormPrerender',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'OnChunkFormRender',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'OnBeforeChunkFormSave',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'OnChunkFormSave',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'OnBeforeChunkFormDelete',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'OnChunkFormDelete',
                'service' => 1,
                'groupname' => 'Chunks',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'OnDocFormPrerender',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'OnDocFormRender',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'OnBeforeDocFormSave',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'OnDocFormSave',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'OnBeforeDocFormDelete',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'OnDocFormDelete',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'OnDocFormUnDelete',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'onBeforeMoveDocument',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'onAfterMoveDocument',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'OnPluginFormPrerender',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'OnPluginFormRender',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'OnBeforePluginFormSave',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'OnPluginFormSave',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'OnBeforePluginFormDelete',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'OnPluginFormDelete',
                'service' => 1,
                'groupname' => 'Plugins',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'OnSnipFormPrerender',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'OnSnipFormRender',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'OnBeforeSnipFormSave',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'OnSnipFormSave',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'OnBeforeSnipFormDelete',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'OnSnipFormDelete',
                'service' => 1,
                'groupname' => 'Snippets',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'OnTempFormPrerender',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'OnTempFormRender',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'OnBeforeTempFormSave',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'OnTempFormSave',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'OnBeforeTempFormDelete',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'OnTempFormDelete',
                'service' => 1,
                'groupname' => 'Templates',
            ),
            55 => 
            array (
                'id' => 56,
                'name' => 'OnTVFormPrerender',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'OnTVFormRender',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'OnBeforeTVFormSave',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'OnTVFormSave',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'OnBeforeTVFormDelete',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'OnTVFormDelete',
                'service' => 1,
                'groupname' => 'Template Variables',
            ),
            61 => 
            array (
                'id' => 62,
                'name' => 'OnUserFormPrerender',
                'service' => 1,
                'groupname' => 'Users',
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'OnUserFormRender',
                'service' => 1,
                'groupname' => 'Users',
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'OnBeforeUserFormSave',
                'service' => 1,
                'groupname' => 'Users',
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'OnUserFormSave',
                'service' => 1,
                'groupname' => 'Users',
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'OnBeforeUserFormDelete',
                'service' => 1,
                'groupname' => 'Users',
            ),
            66 => 
            array (
                'id' => 67,
                'name' => 'OnUserFormDelete',
                'service' => 1,
                'groupname' => 'Users',
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'OnWUsrFormPrerender',
                'service' => 1,
                'groupname' => 'Web Users',
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'OnWUsrFormRender',
                'service' => 1,
                'groupname' => 'Web Users',
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'OnBeforeWUsrFormSave',
                'service' => 1,
                'groupname' => 'Web Users',
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'OnWUsrFormSave',
                'service' => 1,
                'groupname' => 'Web Users',
            ),
            71 => 
            array (
                'id' => 72,
                'name' => 'OnBeforeWUsrFormDelete',
                'service' => 1,
                'groupname' => 'Web Users',
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'OnWUsrFormDelete',
                'service' => 1,
                'groupname' => 'Web Users',
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'OnSiteRefresh',
                'service' => 1,
                'groupname' => '',
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'OnFileManagerUpload',
                'service' => 1,
                'groupname' => '',
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'OnModFormPrerender',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            76 => 
            array (
                'id' => 77,
                'name' => 'OnModFormRender',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'OnBeforeModFormDelete',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            78 => 
            array (
                'id' => 79,
                'name' => 'OnModFormDelete',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'OnBeforeModFormSave',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            80 => 
            array (
                'id' => 81,
                'name' => 'OnModFormSave',
                'service' => 1,
                'groupname' => 'Modules',
            ),
            81 => 
            array (
                'id' => 82,
                'name' => 'OnBeforeWebLogin',
                'service' => 3,
                'groupname' => '',
            ),
            82 => 
            array (
                'id' => 83,
                'name' => 'OnWebAuthentication',
                'service' => 3,
                'groupname' => '',
            ),
            83 => 
            array (
                'id' => 84,
                'name' => 'OnBeforeManagerLogin',
                'service' => 2,
                'groupname' => '',
            ),
            84 => 
            array (
                'id' => 85,
                'name' => 'OnManagerAuthentication',
                'service' => 2,
                'groupname' => '',
            ),
            85 => 
            array (
                'id' => 86,
                'name' => 'OnSiteSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            86 => 
            array (
                'id' => 87,
                'name' => 'OnFriendlyURLSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            87 => 
            array (
                'id' => 88,
                'name' => 'OnUserSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            88 => 
            array (
                'id' => 89,
                'name' => 'OnInterfaceSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            89 => 
            array (
                'id' => 90,
                'name' => 'OnSecuritySettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            90 => 
            array (
                'id' => 91,
                'name' => 'OnFileManagerSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            91 => 
            array (
                'id' => 92,
                'name' => 'OnMiscSettingsRender',
                'service' => 1,
                'groupname' => 'System Settings',
            ),
            92 => 
            array (
                'id' => 93,
                'name' => 'OnRichTextEditorRegister',
                'service' => 1,
                'groupname' => 'RichText Editor',
            ),
            93 => 
            array (
                'id' => 94,
                'name' => 'OnRichTextEditorInit',
                'service' => 1,
                'groupname' => 'RichText Editor',
            ),
            94 => 
            array (
                'id' => 95,
                'name' => 'OnManagerPageInit',
                'service' => 2,
                'groupname' => '',
            ),
            95 => 
            array (
                'id' => 96,
                'name' => 'OnWebPageInit',
                'service' => 5,
                'groupname' => '',
            ),
            96 => 
            array (
                'id' => 97,
                'name' => 'OnLoadDocumentObject',
                'service' => 5,
                'groupname' => '',
            ),
            97 => 
            array (
                'id' => 98,
                'name' => 'OnBeforeLoadDocumentObject',
                'service' => 5,
                'groupname' => '',
            ),
            98 => 
            array (
                'id' => 99,
                'name' => 'OnAfterLoadDocumentObject',
                'service' => 5,
                'groupname' => '',
            ),
            99 => 
            array (
                'id' => 100,
                'name' => 'OnLoadWebDocument',
                'service' => 5,
                'groupname' => '',
            ),
            100 => 
            array (
                'id' => 101,
                'name' => 'OnParseDocument',
                'service' => 5,
                'groupname' => '',
            ),
            101 => 
            array (
                'id' => 102,
                'name' => 'OnParseProperties',
                'service' => 5,
                'groupname' => '',
            ),
            102 => 
            array (
                'id' => 103,
                'name' => 'OnBeforeParseParams',
                'service' => 5,
                'groupname' => '',
            ),
            103 => 
            array (
                'id' => 104,
                'name' => 'OnManagerLoginFormRender',
                'service' => 2,
                'groupname' => '',
            ),
            104 => 
            array (
                'id' => 105,
                'name' => 'OnWebPageComplete',
                'service' => 5,
                'groupname' => '',
            ),
            105 => 
            array (
                'id' => 106,
                'name' => 'OnLogPageHit',
                'service' => 5,
                'groupname' => '',
            ),
            106 => 
            array (
                'id' => 107,
                'name' => 'OnBeforeManagerPageInit',
                'service' => 2,
                'groupname' => '',
            ),
            107 => 
            array (
                'id' => 108,
                'name' => 'OnBeforeEmptyTrash',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            108 => 
            array (
                'id' => 109,
                'name' => 'OnEmptyTrash',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            109 => 
            array (
                'id' => 110,
                'name' => 'OnManagerLoginFormPrerender',
                'service' => 2,
                'groupname' => '',
            ),
            110 => 
            array (
                'id' => 111,
                'name' => 'OnStripAlias',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            111 => 
            array (
                'id' => 112,
                'name' => 'OnMakeDocUrl',
                'service' => 5,
                'groupname' => '',
            ),
            112 => 
            array (
                'id' => 113,
                'name' => 'OnBeforeLoadExtension',
                'service' => 5,
                'groupname' => '',
            ),
            113 => 
            array (
                'id' => 114,
                'name' => 'OnCreateDocGroup',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            114 => 
            array (
                'id' => 115,
                'name' => 'OnManagerWelcomePrerender',
                'service' => 2,
                'groupname' => '',
            ),
            115 => 
            array (
                'id' => 116,
                'name' => 'OnManagerWelcomeHome',
                'service' => 2,
                'groupname' => '',
            ),
            116 => 
            array (
                'id' => 117,
                'name' => 'OnManagerWelcomeRender',
                'service' => 2,
                'groupname' => '',
            ),
            117 => 
            array (
                'id' => 118,
                'name' => 'OnBeforeDocDuplicate',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            118 => 
            array (
                'id' => 119,
                'name' => 'OnDocDuplicate',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            119 => 
            array (
                'id' => 120,
                'name' => 'OnManagerMainFrameHeaderHTMLBlock',
                'service' => 2,
                'groupname' => '',
            ),
            120 => 
            array (
                'id' => 121,
                'name' => 'OnManagerPreFrameLoader',
                'service' => 2,
                'groupname' => '',
            ),
            121 => 
            array (
                'id' => 122,
                'name' => 'OnManagerFrameLoader',
                'service' => 2,
                'groupname' => '',
            ),
            122 => 
            array (
                'id' => 123,
                'name' => 'OnManagerTreeInit',
                'service' => 2,
                'groupname' => '',
            ),
            123 => 
            array (
                'id' => 124,
                'name' => 'OnManagerTreePrerender',
                'service' => 2,
                'groupname' => '',
            ),
            124 => 
            array (
                'id' => 125,
                'name' => 'OnManagerTreeRender',
                'service' => 2,
                'groupname' => '',
            ),
            125 => 
            array (
                'id' => 126,
                'name' => 'OnManagerNodePrerender',
                'service' => 2,
                'groupname' => '',
            ),
            126 => 
            array (
                'id' => 127,
                'name' => 'OnManagerNodeRender',
                'service' => 2,
                'groupname' => '',
            ),
            127 => 
            array (
                'id' => 128,
                'name' => 'OnManagerMenuPrerender',
                'service' => 2,
                'groupname' => '',
            ),
            128 => 
            array (
                'id' => 129,
                'name' => 'OnManagerTopPrerender',
                'service' => 2,
                'groupname' => '',
            ),
            129 => 
            array (
                'id' => 130,
                'name' => 'OnDocFormTemplateRender',
                'service' => 1,
                'groupname' => 'Documents',
            ),
            130 => 
            array (
                'id' => 131,
                'name' => 'OnBeforeMinifyCss',
                'service' => 1,
                'groupname' => '',
            ),
            131 => 
            array (
                'id' => 132,
                'name' => 'OnPageUnauthorized',
                'service' => 1,
                'groupname' => '',
            ),
            132 => 
            array (
                'id' => 133,
                'name' => 'OnPageNotFound',
                'service' => 1,
                'groupname' => '',
            ),
            133 => 
            array (
                'id' => 134,
                'name' => 'OnFileBrowserUpload',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            134 => 
            array (
                'id' => 135,
                'name' => 'OnBeforeFileBrowserUpload',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            135 => 
            array (
                'id' => 136,
                'name' => 'OnFileBrowserDelete',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            136 => 
            array (
                'id' => 137,
                'name' => 'OnBeforeFileBrowserDelete',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            137 => 
            array (
                'id' => 138,
                'name' => 'OnFileBrowserInit',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            138 => 
            array (
                'id' => 139,
                'name' => 'OnFileBrowserMove',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            139 => 
            array (
                'id' => 140,
                'name' => 'OnBeforeFileBrowserMove',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            140 => 
            array (
                'id' => 141,
                'name' => 'OnFileBrowserCopy',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            141 => 
            array (
                'id' => 142,
                'name' => 'OnBeforeFileBrowserCopy',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            142 => 
            array (
                'id' => 143,
                'name' => 'OnBeforeFileBrowserRename',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            143 => 
            array (
                'id' => 144,
                'name' => 'OnFileBrowserRename',
                'service' => 1,
                'groupname' => 'File Browser Events',
            ),
            144 => 
            array (
                'id' => 145,
                'name' => 'OnLogEvent',
                'service' => 1,
                'groupname' => 'Log Event',
            ),
        ));
        
        
    }
}