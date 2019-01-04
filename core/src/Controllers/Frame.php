<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Interfaces\ManagerTheme;
use EvolutionCMS\Interfaces\ManagerThemeInterface;

class Frame extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view;

    protected $frame;

    protected $sitemenu = [];

    public function __construct(ManagerThemeInterface $managerTheme, array $data = [])
    {
        parent::__construct($managerTheme, $data);

        $this->frame = $this->detectFrame();

        if ($this->frame > 9) {
            // this is to stop the debug thingy being attached to the framesets
            $this->managerTheme->getCore()->setConfig('enable_debug', false);
        }
        if (!empty($this->frame)) {
            $this->setView('frame.' . $this->frame);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkLocked(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function canView(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function process(): bool
    {
        header("X-XSS-Protection: 0");

        $this->initSession();

        // invoke OnManagerPreFrameLoader
        $this->managerTheme->getCore()->invokeEvent(
            'OnManagerPreFrameLoader',
            [
                'action' => $this->managerTheme->getActionId()
            ]
        );

        $body_class = '';
        $tree_width = $this->managerTheme->getCore()->getConfig('manager_tree_width');
        $this->parameters['tree_min_width'] = 0;

        if (isset($_COOKIE['MODX_widthSideBar'])) {
            $MODX_widthSideBar = $_COOKIE['MODX_widthSideBar'];
        } else {
            $MODX_widthSideBar = $tree_width;
        }
        $this->parameters['MODX_widthSideBar'] = $MODX_widthSideBar;

        if (!$MODX_widthSideBar) {
            $body_class .= 'sidebar-closed';
        }

        $theme_modes = ['', 'lightness', 'light', 'dark', 'darkness'];
        if (!empty($theme_modes[$_COOKIE['MODX_themeMode']])) {
            $body_class .= ' ' . $theme_modes[$_COOKIE['MODX_themeMode']];
        } elseif (!empty($theme_modes[$this->managerTheme->getCore()->getConfig('manager_theme_mode')])) {
            $body_class .= ' ' . $theme_modes[$this->managerTheme->getCore()->getConfig('manager_theme_mode')];
        }

        $navbar_position = $this->managerTheme->getCore()->getConfig('manager_menu_position');
        if ($navbar_position === 'left') {
            $body_class .= ' navbar-left navbar-left-icon-and-text';
        }

        if (isset($this->managerTheme->getCore()->pluginCache['ElementsInTree'])) {
            $body_class .= ' ElementsInTree';
        }

        $this->parameters['body_class'] = $body_class;

        $unlockTranslations = [
            'msg'   => $this->managerTheme->getLexicon('unlock_element_id_warning'),
            'type1' => $this->managerTheme->getLexicon('lock_element_type_1'),
            'type2' => $this->managerTheme->getLexicon('lock_element_type_2'),
            'type3' => $this->managerTheme->getLexicon('lock_element_type_3'),
            'type4' => $this->managerTheme->getLexicon('lock_element_type_4'),
            'type5' => $this->managerTheme->getLexicon('lock_element_type_5'),
            'type6' => $this->managerTheme->getLexicon('lock_element_type_6'),
            'type7' => $this->managerTheme->getLexicon('lock_element_type_7'),
            'type8' => $this->managerTheme->getLexicon('lock_element_type_8')
        ];

        foreach ($unlockTranslations as $key => $value) {
            $unlockTranslations[$key] = iconv(
                $this->managerTheme->getCore()->getConfig('modx_charset'),
                'utf-8',
                $value
            );
        }
        $this->parameters['unlockTranslations'] = $unlockTranslations;

        $user = $this->managerTheme->getCore()->getUserInfo($this->managerTheme->getCore()->getLoginUserID('mgr'));
        if ((isset($user['which_browser']) && $user['which_browser'] == 'default') || (!isset($user['which_browser']))) {
            $user['which_browser'] = $this->managerTheme->getCore()->getConfig('which_browser');
        }
        $this->parameters['user'] = $user;

        $this->registerCss();

        $flag = $user['role'] == 1 || $this->managerTheme->getCore()->hasAnyPermissions([
                'edit_template',
                'edit_chunk',
                'edit_snippet',
                'edit_plugin'
            ]);

        $this->managerTheme->getCore()->setConfig(
            'global_tabs',
            (int)($this->managerTheme->getCore()->getConfig('global_tabs') && $flag)
        );

        $this->makeMenu();

        return true;
    }

    protected function detectFrame(): string
    {
        return preg_replace(
            '/[^a-z0-9]/i',
            '',
            get_by_key($_REQUEST, 'f', get_by_key($this->data, 'frame'))
        );
    }

    protected function initSession(): void
    {
        $_SESSION['browser'] = (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 1') !== false) ? 'legacy_IE' : 'modern';

        if (isset($_SESSION['onLoginForwardToAction']) && is_int($_SESSION['onLoginForwardToAction'])) {
            $this->parameters['initMainframeAction'] = $_SESSION['onLoginForwardToAction'];
            unset($_SESSION['onLoginForwardToAction']);
        } else {
            $this->parameters['initMainframeAction'] = 2; // welcome.static
        }

        if (!isset($_SESSION['tree_show_only_folders'])) {
            $_SESSION['tree_show_only_folders'] = 0;
        }
    }

    protected function registerCss(): string
    {
        $this->parameters['css'] = $this->managerTheme->getThemeDir(false) . 'css/page.css?v=' . EVO_INSTALL_TIME;

        if ($this->managerTheme->getTheme() === 'default') {
            $themeDir = $this->managerTheme->getThemeDir();

            if (!file_exists($themeDir . 'css/styles.min.css') && is_writable($themeDir . 'css')) {
                require_once MODX_BASE_PATH . 'assets/lib/Formatter/CSSMinify.php';
                $minifier = new \Formatter\CSSMinify();
                $minifier->addFile(MODX_MANAGER_PATH . 'media/style/common/bootstrap/css/bootstrap.min.css');
                $minifier->addFile(MODX_MANAGER_PATH . 'media/style/common/font-awesome/css/font-awesome.min.css');
                $minifier->addFile($themeDir . 'css/fonts.css');
                $minifier->addFile($themeDir . 'css/forms.css');
                $minifier->addFile($themeDir . 'css/mainmenu.css');
                $minifier->addFile($themeDir . 'css/tree.css');
                $minifier->addFile($themeDir . 'css/custom.css');
                $minifier->addFile($themeDir . 'css/tabpane.css');
                $minifier->addFile($themeDir . 'css/contextmenu.css');
                $minifier->addFile($themeDir . 'css/index.css');
                $minifier->addFile($themeDir . 'css/main.css');
                file_put_contents($themeDir . 'css/styles.min.css', $minifier->minify());
            }
            if (file_exists($themeDir . 'css/styles.min.css')) {
                $this->parameters['css'] = $this->managerTheme->getThemeDir(false) . 'css/styles.min.css?v=' . EVO_INSTALL_TIME;
            }
        }

        return $this->parameters['css'];
    }

    protected function makeMenu()
    {
        $this->menuBars()
            ->menuSite()
            ->menuElementTypes()
            ->menuModules()
            ->menuUsers()
            ->menuTools()
            ->menuElements()
            ->menuFiles()
            ->menuCategories()
            ->menuNewModule()
            ->menuRunModules()
            ->menuUserManagment()
            ->menuWebUserManagment()
            ->menuRoleManagment()
            ->menuPermissions()
            ->menuWebPermissions()
            ->menuRefreshSite()
            ->menuSearch()
            ->menuBkManager()
            ->menuRemoveLocks()
            ->menuImportSite()
            ->menuExportSite();

        $menu = $this->managerTheme->getCore()->invokeEvent('OnManagerMenuPrerender', ['menu' => $this->sitemenu]);
        if (\is_array($menu)) {
            $newmenu = [];
            foreach ($menu as $item) {
                $data = unserialize($item, ['allowed_classes' => false]);
                if (\is_array($data)) {
                    $newmenu = array_merge($newmenu, $data);
                }
            }
            if (\count($newmenu) > 0) {
                $this->sitemenu = $newmenu;
            }
        }

        $this->parameters['menu'] = (new \EvolutionCMS\Support\Menu())
            ->Build(
                $this->sitemenu,
                [
                    'outerClass'      => 'nav',
                    'innerClass'      => 'dropdown-menu',
                    'parentClass'     => 'dropdown',
                    'parentLinkClass' => 'dropdown-toggle',
                    'parentLinkAttr'  => '',
                    'parentLinkIn'    => ''
                ],
                false
            );
    }

    protected function menuBars()
    {
        $this->sitemenu['bars'] = [
            'bars',
            'main',
            '<i class="fa fa-bars"></i>',
            'javascript:;',
            $this->managerTheme->getLexicon('home'),
            'modx.resizer.toggle(); return false;',
            ' return false;',
            '',
            0,
            10,
            ''
        ];

        return $this;
    }

    protected function menuSite()
    {
        $this->sitemenu['site'] = [
            'site',
            'main',
            '<i class="fa fa-tachometer"></i><span class="menu-item-text">' . $this->managerTheme->getLexicon('home') . '</span>',
            'index.php?a=2',
            $this->managerTheme->getLexicon('home'),
            '',
            '',
            'main',
            0,
            10,
            'active'
        ];

        return $this;
    }

    protected function menuElementTypes()
    {
        $flag = $this->managerTheme->getCore()->hasAnyPermissions(
            ['edit_template', 'edit_snippet', 'edit_chunk', 'edit_plugin', 'category_manager', 'file_manager']
        );

        if ($flag) {
            $this->sitemenu['elements'] = [
                'elements',
                'main',
                '<i class="fa fa-th"></i><span class="menu-item-text">' . $this->managerTheme->getLexicon('elements') . '</span>',
                'javascript:;',
                $this->managerTheme->getLexicon('elements'),
                ' return false;',
                '',
                '',
                0,
                20,
                ''
            ];
        }

        return $this;
    }

    protected function menuModules()
    {
        if ($this->managerTheme->getCore()->hasPermission('exec_module')) {
            $this->sitemenu['modules'] = [
                'modules',
                'main',
                '<i class="' . $this->managerTheme->getStyle('icons_modules') . '"></i><span class="menu-item-text">' . $this->managerTheme->getLexicon('modules') . '</span>',
                'javascript:;',
                $this->managerTheme->getLexicon('modules'),
                ' return false;',
                '',
                '',
                0,
                30,
                ''
            ];
        }

        return $this;
    }

    protected function menuUsers()
    {
        $flag = $this->managerTheme->getCore()->hasAnyPermissions(
            ['edit_user', 'edit_web_user', 'edit_role', 'access_permissions', 'web_access_permissions']
        );
        if ($flag) {
            $this->sitemenu['users'] = [
                'users',
                'main',
                '<i class="fa fa-users"></i><span class="menu-item-text">' . $this->managerTheme->getLexicon('users') . '</span>',
                'javascript:;',
                $this->managerTheme->getLexicon('users'),
                ' return false;',
                'edit_user',
                '',
                0,
                40,
                ''
            ];
        }

        return $this;
    }

    protected function menuTools()
    {
        $flag = $this->managerTheme->getCore()->hasAnyPermissions(
            ['empty_cache', 'bk_manager', 'remove_locks', 'import_static', 'export_static']
        );
        if ($flag) {
            $this->sitemenu['tools'] = [
                'tools',
                'main',
                '<i class="fa fa-wrench"></i><span class="menu-item-text">' . $this->managerTheme->getLexicon('tools') . '</span>',
                'javascript:;',
                $this->managerTheme->getLexicon('tools'),
                ' return false;',
                '',
                '',
                0,
                50,
                ''
            ];
        }

        return $this;
    }

    protected function menuElements()
    {
        $tab = $this->menuElementTemplates(0);
        $tab = $this->menuElementTv($tab);
        $tab = $this->menuElementChunks($tab);
        $tab = $this->menuElementSnippets($tab);
        $this->menuElementPlugins($tab);

        return $this;
    }

    protected function menuElementTemplates($tab)
    {
        if ($this->managerTheme->getCore()->hasPermission('edit_template')) {
            $this->sitemenu['element_templates'] = [
                'element_templates',
                'elements',
                '<i class="fa fa-newspaper-o"></i>' . $this->managerTheme->getLexicon('manage_templates') . '<i class="fa fa-angle-right toggle"></i>',
                'index.php?a=76&tab=' . $tab++,
                $this->managerTheme->getLexicon('manage_templates'),
                '',
                'new_template,edit_template',
                'main',
                0,
                10,
                'dropdown-toggle'
            ];
        }

        return $tab;
    }

    protected function menuElementTv($tab)
    {
        if ($this->managerTheme->getCore()->hasPermission('edit_template') && $this->managerTheme->getCore()->hasPermission('edit_snippet') && $this->managerTheme->getCore()->hasPermission('edit_chunk') && $this->managerTheme->getCore()->hasPermission('edit_plugin')) {
            $this->sitemenu['element_tplvars'] = [
                'element_tplvars',
                'elements',
                '<i class="fa fa-list-alt"></i>' . $this->managerTheme->getLexicon('tmplvars') . '<i class="fa fa-angle-right toggle"></i>',
                'index.php?a=76&tab=' . $tab++,
                $this->managerTheme->getLexicon('tmplvars'),
                '',
                'new_template,edit_template',
                'main',
                0,
                20,
                'dropdown-toggle'
            ];
        }

        return $tab;
    }

    protected function menuElementChunks($tab)
    {
        if ($this->managerTheme->getCore()->hasPermission('edit_chunk')) {
            $this->sitemenu['element_htmlsnippets'] = [
                'element_htmlsnippets',
                'elements',
                '<i class="fa fa-th-large"></i>' . $this->managerTheme->getLexicon('manage_htmlsnippets') . '<i class="fa fa-angle-right toggle"></i>',
                'index.php?a=76&tab=' . $tab++,
                $this->managerTheme->getLexicon('manage_htmlsnippets'),
                '',
                'new_chunk,edit_chunk',
                'main',
                0,
                30,
                'dropdown-toggle'
            ];
        }

        return $tab;
    }

    protected function menuElementSnippets($tab)
    {
        if ($this->managerTheme->getCore()->hasPermission('edit_snippet')) {
            $this->sitemenu['element_snippets'] = [
                'element_snippets',
                'elements',
                '<i class="fa fa-code"></i>' . $this->managerTheme->getLexicon('manage_snippets') . '<i class="fa fa-angle-right toggle"></i>',
                'index.php?a=76&tab=' . $tab++,
                $this->managerTheme->getLexicon('manage_snippets'),
                '',
                'new_snippet,edit_snippet',
                'main',
                0,
                40,
                'dropdown-toggle'
            ];
        }

        return $tab;
    }

    protected function menuElementPlugins($tab)
    {
        if ($this->managerTheme->getCore()->hasPermission('edit_plugin')) {
            $this->sitemenu['element_plugins'] = [
                'element_plugins',
                'elements',
                '<i class="fa fa-plug"></i>' . $this->managerTheme->getLexicon('manage_plugins') . '<i class="fa fa-angle-right toggle"></i>',
                'index.php?a=76&tab=' . $tab++,
                $this->managerTheme->getLexicon('manage_plugins'),
                '',
                'new_plugin,edit_plugin',
                'main',
                0,
                50,
                'dropdown-toggle'
            ];
        }

        return $tab;
    }

    function menuFiles()
    {
        if ($this->managerTheme->getCore()->hasPermission('file_manager')) {
            $this->sitemenu['manage_files'] = [
                'manage_files',
                'elements',
                '<i class="fa fa-folder-open-o"></i>' . $this->managerTheme->getLexicon('manage_files'),
                'index.php?a=31',
                $this->managerTheme->getLexicon('manage_files'),
                '',
                'file_manager',
                'main',
                0,
                80,
                ''
            ];
        }

        return $this;
    }

    protected function menuCategories()
    {
        if ($this->managerTheme->getCore()->hasPermission('category_manager')) {
            $this->sitemenu['manage_categories'] = [
                'manage_categories',
                'elements',
                '<i class="fa fa-object-group"></i>' . $this->managerTheme->getLexicon('manage_categories'),
                'index.php?a=120',
                $this->managerTheme->getLexicon('manage_categories'),
                '',
                'category_manager',
                'main',
                0,
                70,
                ''
            ];
        }

        return $this;
    }

    protected function menuNewModule()
    {
        $flag = $this->managerTheme->getCore()->hasAnyPermissions(
            ['new_module', 'edit_module', 'save_module']
        );

        if ($flag) {
            $this->sitemenu['new_module'] = [
                'new_module',
                'modules',
                '<i class="' . $this->managerTheme->getStyle('icons_modules') . '"></i>' . $this->managerTheme->getLexicon('module_management'),
                'index.php?a=106',
                $this->managerTheme->getLexicon('module_management'),
                '',
                'new_module,edit_module',
                'main',
                1,
                0,
                ''
            ];
        }

        return $this;
    }

    protected function menuRunModules()
    {
        if ($this->managerTheme->getCore()->hasPermission('exec_module')) {
            if ($_SESSION['mgrRole'] != 1 && $this->managerTheme->getCore()->getConfig('use_udperms') === true) {
                $rs = $this->managerTheme->getCore()->getDatabase()->query('SELECT DISTINCT sm.id, sm.name, sm.icon, mg.member
				FROM ' . $this->managerTheme->getCore()->getDatabase()->getFullTableName('site_modules') . ' AS sm
				LEFT JOIN ' . $this->managerTheme->getCore()->getDatabase()->getFullTableName('site_module_access') . ' AS sma ON sma.module = sm.id
				LEFT JOIN ' . $this->managerTheme->getCore()->getDatabase()->getFullTableName('member_groups') . ' AS mg ON sma.usergroup = mg.user_group
                WHERE (mg.member IS NULL OR mg.member = ' . $this->managerTheme->getCore()->getLoginUserID('mgr') . ') AND sm.disabled != 1 AND sm.locked != 1
                ORDER BY sm.name');
            } else {
                $rs = $this->managerTheme->getCore()->getDatabase()->select(
                    '*',
                    $this->managerTheme->getCore()->getDatabase()->getFullTableName('site_modules'),
                    'disabled != 1',
                    'name'
                );
            }
            if ($this->managerTheme->getCore()->getDatabase()->getRecordCount($rs)) {
                while ($row = $this->managerTheme->getCore()->getDatabase()->getRow($rs)) {
                    $this->sitemenu['module' . $row['id']] = [
                        'module' . $row['id'],
                        'modules',
                        ($row['icon'] != '' ? '<i class="' . $row['icon'] . '"></i>' : '<i class="' . $this->managerTheme->getStyle('icons_module') . '"></i>') . $row['name'],
                        'index.php?a=112&id=' . $row['id'],
                        $row['name'],
                        '',
                        '',
                        'main',
                        0,
                        1,
                        ''
                    ];
                }
            }
        }

        return $this;
    }

    protected function menuUserManagment()
    {
        if ($this->managerTheme->getCore()->hasPermission('edit_user')) {
            $this->sitemenu['user_management_title'] = [
                'user_management_title',
                'users',
                '<i class="fa fa-user-circle-o"></i>' . $this->managerTheme->getLexicon('user_management_title') . '<i class="fa fa-angle-right toggle"></i>',
                'index.php?a=75',
                $this->managerTheme->getLexicon('user_management_title'),
                '',
                'edit_user',
                'main',
                0,
                10,
                'dropdown-toggle'
            ];
        }

        return $this;
    }

    protected function menuWebUserManagment()
    {
        if ($this->managerTheme->getCore()->hasPermission('edit_web_user')) {
            $this->sitemenu['web_user_management_title'] = [
                'web_user_management_title',
                'users',
                '<i class="fa fa-user"></i>' . $this->managerTheme->getLexicon('web_user_management_title') . '<i class="fa fa-angle-right toggle"></i>',
                'index.php?a=99',
                $this->managerTheme->getLexicon('web_user_management_title'),
                '',
                'edit_web_user',
                'main',
                0,
                20,
                'dropdown-toggle'
            ];
        }

        return $this;
    }

    protected function menuRoleManagment()
    {
        if ($this->managerTheme->getCore()->hasPermission('edit_role')) {
            $this->sitemenu['role_management_title'] = [
                'role_management_title',
                'users',
                '<i class="fa fa-legal"></i>' . $this->managerTheme->getLexicon('role_management_title'),
                'index.php?a=86',
                $this->managerTheme->getLexicon('role_management_title'),
                '',
                'new_role,edit_role,delete_role',
                'main',
                0,
                30,
                ''
            ];
        }

        return $this;
    }

    protected function menuPermissions()
    {
        if ($this->managerTheme->getCore()->hasPermission('access_permissions')) {
            $this->sitemenu['manager_permissions'] = [
                'manager_permissions',
                'users',
                '<i class="fa fa-male"></i>' . $this->managerTheme->getLexicon('manager_permissions'),
                'index.php?a=40',
                $this->managerTheme->getLexicon('manager_permissions'),
                '',
                'access_permissions',
                'main',
                0,
                40,
                ''
            ];
        }

        return $this;
    }

    protected function menuWebPermissions()
    {
        if ($this->managerTheme->getCore()->hasPermission('web_access_permissions')) {
            $this->sitemenu['web_permissions'] = [
                'web_permissions',
                'users',
                '<i class="fa fa-universal-access"></i>' . $this->managerTheme->getLexicon('web_permissions'),
                'index.php?a=91',
                $this->managerTheme->getLexicon('web_permissions'),
                '',
                'web_access_permissions',
                'main',
                0,
                50,
                ''
            ];
        }

        return $this;
    }

    protected function menuRefreshSite()
    {
        $this->sitemenu['refresh_site'] = [
            'refresh_site',
            'tools',
            '<i class="fa fa-recycle"></i>' . $this->managerTheme->getLexicon('refresh_site'),
            'index.php?a=26',
            $this->managerTheme->getLexicon('refresh_site'),
            '',
            '',
            'main',
            0,
            5,
            'item-group',
            [
                'refresh_site_in_window' => [
                    'a',
                    // tag
                    'javascript:;',
                    // href
                    'btn btn-secondary',
                    // class or btn-success
                    'modx.popup({url:\'index.php?a=26\', title:\'' . $this->managerTheme->getLexicon('refresh_site') . '\', icon: \'fa-recycle\', iframe: \'ajax\', selector: \'.tab-page>.container\', position: \'right top\', width: \'auto\', maxheight: \'50%\', wrap: \'body\' })',
                    // onclick
                    $this->managerTheme->getLexicon('refresh_site'),
                    // title
                    '<i class="fa fa-recycle"></i>'
                    // innerHTML
                ]
            ]
        ];

        return $this;
    }

    protected function menuSearch()
    {
        $this->sitemenu['search'] = [
            'search',
            'tools',
            '<i class="fa fa-search"></i>' . $this->managerTheme->getLexicon('search'),
            'index.php?a=71',
            $this->managerTheme->getLexicon('search'),
            '',
            '',
            'main',
            1,
            9,
            ''
        ];

        return $this;
    }

    protected function menuBkManager()
    {
        if ($this->managerTheme->getCore()->hasPermission('bk_manager')) {
            $this->sitemenu['bk_manager'] = [
                'bk_manager',
                'tools',
                '<i class="fa fa-database"></i>' . $this->managerTheme->getLexicon('bk_manager'),
                'index.php?a=93',
                $this->managerTheme->getLexicon('bk_manager'),
                '',
                'bk_manager',
                'main',
                0,
                10,
                ''
            ];
        }

        return $this;
    }

    protected function menuRemoveLocks()
    {
        if ($this->managerTheme->getCore()->hasPermission('remove_locks')) {
            $this->sitemenu['remove_locks'] = [
                'remove_locks',
                'tools',
                '<i class="fa fa-hourglass"></i>' . $this->managerTheme->getLexicon('remove_locks'),
                'javascript:modx.removeLocks();',
                $this->managerTheme->getLexicon('remove_locks'),
                '',
                'remove_locks',
                '',
                0,
                20,
                ''
            ];
        }

        return $this;
    }

    protected function menuImportSite()
    {
        if ($this->managerTheme->getCore()->hasPermission('import_static')) {
            $this->sitemenu['import_site'] = [
                'import_site',
                'tools',
                '<i class="fa fa-upload"></i>' . $this->managerTheme->getLexicon('import_site'),
                'index.php?a=95',
                $this->managerTheme->getLexicon('import_site'),
                '',
                'import_static',
                'main',
                0,
                30,
                ''
            ];
        }

        return $this;
    }

    protected function menuExportSite()
    {
        if ($this->managerTheme->getCore()->hasPermission('export_static')) {
            $this->sitemenu['export_site'] = [
                'export_site',
                'tools',
                '<i class="fa fa-download"></i>' . $this->managerTheme->getLexicon('export_site'),
                'index.php?a=83',
                $this->managerTheme->getLexicon('export_site'),
                '',
                'export_static',
                'main',
                1,
                40,
                ''
            ];
        }

        return $this;
    }
}
