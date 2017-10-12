<?php

if (IN_MANAGER_MODE != "true") {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
header("X-XSS-Protection: 0");

$_SESSION['browser'] = (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 1') !== false) ? 'legacy_IE' : 'modern';

// invoke OnManagerPreFrameLoader
$modx->invokeEvent('OnManagerPreFrameLoader', array('action' => $action));

$mxla = $modx_lang_attribute ? $modx_lang_attribute : 'en';

if (!isset($modx->config['manager_menu_height'])) {
    $modx->config['manager_menu_height'] = 2.2; // rem
}

if (!isset($modx->config['manager_tree_width'])) {
    $modx->config['manager_tree_width'] = 20; // rem
}

if (isset($_SESSION['onLoginForwardToAction']) && is_int($_SESSION['onLoginForwardToAction'])) {
    $initMainframeAction = $_SESSION['onLoginForwardToAction'];
    unset($_SESSION['onLoginForwardToAction']);
} else {
    $initMainframeAction = 2; // welcome.static
}

if (!isset($_SESSION['tree_show_only_folders'])) {
    $_SESSION['tree_show_only_folders'] = 0;
}

$body_class = '';
$menu_height = $modx->config['manager_menu_height'];
$tree_width = $modx->config['manager_tree_width'];
$tree_min_width = 0;

if (isset($_COOKIE['MODX_widthSideBar'])) {
    $MODX_widthSideBar = $_COOKIE['MODX_widthSideBar'];
} else {
    $MODX_widthSideBar = $tree_width;
}

if (!$MODX_widthSideBar) {
    $body_class .= 'sidebar-closed';
}

if (isset($_COOKIE['MODX_themeColor'])) {
    $body_class .= ' ' . $_COOKIE['MODX_themeColor'];
}

if (isset($modx->pluginCache['ElementsInTree'])) {
    $body_class .= ' ElementsInTree';
}

$unlockTranslations = array(
    'msg' => $_lang["unlock_element_id_warning"],
    'type1' => $_lang["lock_element_type_1"],
    'type2' => $_lang["lock_element_type_2"],
    'type3' => $_lang["lock_element_type_3"],
    'type4' => $_lang["lock_element_type_4"],
    'type5' => $_lang["lock_element_type_5"],
    'type6' => $_lang["lock_element_type_6"],
    'type7' => $_lang["lock_element_type_7"],
    'type8' => $_lang["lock_element_type_8"]
);

foreach ($unlockTranslations as $key => $value) {
    $unlockTranslations[$key] = iconv($modx->config["modx_charset"], "utf-8", $value);
}

$user = $modx->getUserInfo($modx->getLoginUserID());
if ($user['which_browser'] == 'default') {
    $user['which_browser'] = $modx->config['which_browser'];
}
?>
<!DOCTYPE html>
<html <?= (isset($modx_textdir) && $modx_textdir ? 'dir="rtl" lang="' : 'lang="') . $mxla . '" xml:lang="' . $mxla . '"' ?>>
<head>
    <title><?= $site_name ?>- (EVO CMS Manager)</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= $modx_manager_charset ?>" />
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width" />
    <meta name="theme-color" content="#1d2023" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" type="text/css" href="media/style/<?= $modx->config['manager_theme'] ?>/css/page.css?v=<?= $modx->config['settings_version'] ?>" />
    <?php if ($modx->config['show_picker'] != "0") { ?>
    <link rel="stylesheet" href="media/style/common/spectrum/spectrum.css" />
    <?php } ?>
    <link rel="icon" type="image/ico" href="<?= $_style['favicon'] ?>" />
    <style>
        #tree { width: <?= $MODX_widthSideBar ?>rem }
        #main, #resizer { left: <?= $MODX_widthSideBar ?>rem }
        .ios #main { -webkit-overflow-scrolling: touch; overflow-y: scroll; }
    </style>
    <script type="text/javascript">
      if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        document.documentElement.className += ' ios'
      }
    </script>
    <script src="media/script/jquery/jquery.min.js" type="text/javascript"></script>
    <?php if ($modx->config['show_picker'] != "0") { ?>
    <script src="media/script/spectrum/spectrum.evo.min.js" type="text/javascript"></script>
    <script language="javascript">
    $(document).ready(function() {
    var bgColour = "#1d2023";
    if(localStorage.getItem("EvoMenuColour")){
      bgColour = localStorage.getItem("EvoMenuColour");
    }
    $("#mainMenu").css({backgroundColor:bgColour});
    $("form").on("submit", function(ev){
    ev.preventDefault();
    var newColour =$("#picker").spectrum("get");
    localStorage.setItem("EvoMenuColour",newColour);
    $("#colPicked").html(newColour);
    $("#mainMenu").css({backgroundColor:newColour});
    location.reload();
    });
    $("#picker").spectrum({
    flat: true,
    showInput: true,
    showAlpha: false,
    allowEmpty: true,
    color: '',
    showPaletteOnly: true,
    togglePaletteOnly: true,
    togglePaletteMoreText: '+',
    togglePaletteLessText: '-',
    preferredFormat: "hex3",
        palette:[
        ["#000","#1d2023","#333","#444","#555","#bbb","#f3f3f3","#fafafa","#fff"],
        ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47","#9d2661"]
        ],
        color: localStorage.getItem("EvoMenuColour")
        });
         //text color
            var txColour = " ";
            if(localStorage.getItem("EvoMenuTextColour")){
	       txColour = localStorage.getItem("EvoMenuTextColour");
            }
            $("#mainMenu .nav > li > a").css({color:txColour});
            $("#mainMenu .nav .label_searchid").css({color:txColour});
            $("form").on("submit", function(ev){
            ev.preventDefault();
            var newTColour =$("#textpicker").spectrum("get");
            localStorage.setItem("EvoMenuTextColour",newTColour);
            $("#colPicked2").html(newTColour);
            $("#mainMenu .nav > li > a").css({color:newTColour});
            $("#mainMenu .nav .label_searchid").css({color:newTColour});    
            location.reload();
            });
            $("#textpicker").spectrum({
            flat: true,
            showInput: true,
            showAlpha: false,
            allowEmpty: true,
            showPaletteOnly: true,
            togglePaletteOnly: true,
            togglePaletteMoreText: '+',
            togglePaletteLessText: '-',
            preferredFormat: "hex3",
            palette:[
        ["#000","#444","#666","#bbb","#ccc","#eee","#f3f3f3","#fafafa","#fff"]
        ],
            color: localStorage.getItem("EvoMenuTextColour")
            });
            var txHColour = " ";
            if(localStorage.getItem("EvoMenuTextHColour")){
	       txHColour = localStorage.getItem("EvoMenuTextHColour");
            }
           var txColour = "";
            if(localStorage.getItem("EvoMenuTextColour")){
	       txColour = localStorage.getItem("EvoMenuTextColour");
            }
            $("#mainMenu .nav > li:not(.active) > a").mouseover(function() {
            $(this).css({color:txHColour});
            }).mouseout(function() {
            $(this).css({color:txColour});
            });
            $("#mainMenu .nav .label_searchid").mouseover(function() {
            $(this).css({color:txHColour});
            }).mouseout(function() {
            $(this).css({color:txColour});
            });
            $("#mainMenu .nav > li.active > a").css({color:txHColour});
            $("form").on("submit", function(ev){
            ev.preventDefault();
            var newTColour =$("#textpicker").spectrum("get");
            localStorage.setItem("EvoMenuTextColour",newTColour);
            var newTHColour =$("#textHpicker").spectrum("get");
            localStorage.setItem("EvoMenuTextHColour",newTHColour);
            $("#colPicked3").html(newTHColour);
            $("#mainMenu .nav > li:not(.active) > a").mouseover(function() {
            $(this).css({color:newTHColour});
            }).mouseout(function() {
            $(this).css({color:newTColour});
            });
            $("#mainMenu .nav .label_searchid").mouseover(function() {
            $(this).css({color:newTHColour});
            }).mouseout(function() {
            $(this).css({color:newTColour});
            }); 
            $("#mainMenu .nav > li.active > a").css({color:newTHColour});
            location.reload();
            });
            $("#textHpicker").spectrum({
            flat: true,
            showInput: true,
            showAlpha: false,
            allowEmpty: true,
            showPaletteOnly: true,
            togglePaletteOnly: true,
            togglePaletteMoreText: '+',
            togglePaletteLessText: '-',
            preferredFormat: "hex3",
            palette:[
        ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fafafa","#fff"]
        ],
            color: localStorage.getItem("EvoMenuTextHColour")
            });
        });
    </script>
<script type="text/javascript">
function cleanLocalStorage(keys) {
keys = keys.split(',');
for (var i = 0; i < keys.length; i++) {
delete localStorage[keys[i]];
}
location.reload();
}
</script>
<?php } ?>
    <script type="text/javascript">
      // GLOBAL variable modx
      var modx = {
        MGR_DIR: '<?= MGR_DIR ?>',
        MODX_SITE_URL: '<?= MODX_SITE_URL ?>',
        MODX_MANAGER_URL: '<?= MODX_MANAGER_URL ?>',
        user: {
          role: <?= (int)$user['role'] ?>,
          username: '<?= $user['username'] ?>'
        },
        config: {
          mail_check_timeperiod: <?= $modx->config['mail_check_timeperiod'] ?>,
          menu_height: <?= (int)$menu_height ?>,
          tree_width: <?= (int)$tree_width ?>,
          tree_min_width: <?= (int)$tree_min_width ?>,
          session_timeout: <?= (int)$modx->config['session_timeout'] ?>,
          site_start: <?= (int)$modx->config['site_start'] ?>,
          tree_page_click: <?=(!empty($modx->config['tree_page_click']) ? (int)$modx->config['tree_page_click'] : 27) ?>,
          theme: '<?= $modx->config['manager_theme'] ?>',
          which_browser: '<?= $user['which_browser'] ?>',
          layout: <?= (int)$manager_layout ?>,
          textdir: '<?= $modx_textdir ?>',
          global_tabs: <?= $modx->config['global_tabs'] && $user['role'] == 1 ? 1 : 0 ?>
        },
        lang: {
          already_deleted: "<?= $_lang['already_deleted'] ?>",
          cm_unknown_error: "<?= $_lang['cm_unknown_error'] ?>",
          collapse_tree: "<?= $_lang['collapse_tree'] ?>",
          confirm_delete_resource: "<?= $_lang['confirm_delete_resource'] ?>",
          confirm_empty_trash: "<?= $_lang['confirm_empty_trash'] ?>",
          confirm_publish: "<?= $_lang['confirm_publish'] ?>",
          confirm_remove_locks: "<?= $_lang['confirm_remove_locks'] ?>",
          confirm_resource_duplicate: "<?= $_lang['confirm_resource_duplicate'] ?>",
          confirm_undelete: "<?= $_lang['confirm_undelete'] ?>",
          confirm_unpublish: "<?= $_lang['confirm_unpublish'] ?>",
          empty_recycle_bin: "<?= $_lang['empty_recycle_bin'] ?>",
          empty_recycle_bin_empty: "<?= $_lang['empty_recycle_bin_empty'] ?>",
          error_no_privileges: "<?= $_lang["error_no_privileges"] ?>",
          expand_tree: "<?= $_lang['expand_tree'] ?>",
          inbox: "<?= $_lang['inbox'] ?>",
          loading_doc_tree: "<?= $_lang['loading_doc_tree'] ?>",
          loading_menu: "<?= $_lang['loading_menu'] ?>",
          not_deleted: "<?= $_lang['not_deleted'] ?>",
          unable_set_link: "<?= $_lang['unable_set_link'] ?>",
          unable_set_parent: "<?= $_lang['unable_set_parent'] ?>",
          working: "<?= $_lang['working'] ?>"
        },
        style: {
          actions_file: '<?= addslashes($_style['actions_file']) ?>',
          actions_pencil: '<?= addslashes($_style['actions_pencil']) ?>',
          actions_plus: '<?= addslashes($_style['actions_plus']) ?>',
          actions_reply: '<?= addslashes($_style['actions_reply']) ?>',
          collapse_tree: '<?= addslashes($_style['collapse_tree']) ?>',
          email: '<?= addslashes($_style['email']) ?>',
          empty_recycle_bin: '<?= addslashes($_style['empty_recycle_bin']) ?>',
          empty_recycle_bin_empty: '<?= addslashes($_style['empty_recycle_bin_empty']) ?>',
          expand_tree: '<?= addslashes($_style['expand_tree']) ?>',
          icons_external_link: '<?= addslashes($_style['icons_external_link']) ?>',
          icons_working: '<?= addslashes($_style['tree_working']) ?>',
          tree_info: '<?= addslashes($_style['tree_info']) ?>',
          tree_folder: '<?= addslashes($_style['tree_folder_new']) ?>',
          tree_folder_secure: '<?= addslashes($_style['tree_folder_secure']) ?>',
          tree_folderopen: '<?= addslashes($_style['tree_folderopen_new']) ?>',
          tree_folderopen_secure: '<?= addslashes($_style['tree_folderopen_secure']) ?>',
          tree_minusnode: '<?= addslashes($_style["tree_minusnode"]) ?>',
          tree_plusnode: '<?= addslashes($_style['tree_plusnode']) ?>',
          tree_preview_resource: '<?= addslashes($_style['tree_preview_resource']) ?>'
        },
        permission: {
          assets_images: <?= $modx->hasPermission('assets_images') ? 1 : 0 ?>,
          delete_document: <?= $modx->hasPermission('delete_document') ? 1 : 0 ?>,
          edit_chunk: <?= $modx->hasPermission('edit_chunk') ? 1 : 0 ?>,
          edit_plugin: <?= $modx->hasPermission('edit_plugin') ? 1 : 0 ?>,
          edit_snippet: <?= $modx->hasPermission('edit_snippet') ? 1 : 0 ?>,
          edit_template: <?= $modx->hasPermission('edit_template') ? 1 : 0 ?>,
          messages: <?= $modx->hasPermission('messages') ? 1 : 0 ?>,
          new_document: <?= $modx->hasPermission('new_document') ? 1 : 0 ?>,
          publish_document: <?= $modx->hasPermission('publish_document') ? 1 : 0 ?>,
          dragndropdocintree: <?= ($modx->hasPermission('new_document') && $modx->hasPermission('edit_document') && $modx->hasPermission('save_document') ? 1 : 0) ?>

        },
        plugins: {
          ElementsInTree: <?= isset($modx->pluginCache['ElementsInTree']) ? 1 : 0 ?>,
          EVOmodal: <?= isset($modx->pluginCache['EVO.modal']) ? 1 : 0 ?>
        },
        extend: function(a, b) {
          for (var c in a) {
            a[c] = b[c];
          }
        },
        extended: function(a) {
          for (var b in a) {
            this[b] = a[b];
          }
          delete a[b]
        },
        openedArray: [],
        lockedElementsTranslation: <?= json_encode($unlockTranslations, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE) . "\n" ?>
      };
      <?php
      $opened = array_filter(array_map('intval', explode('|', $_SESSION['openedArray'])));
      echo (empty($opened) ? '' : 'modx.openedArray[' . implode("] = 1;\n		modx.openedArray[", $opened) . '] = 1;') . "\n";
      ?>
    </script>
    <script src="media/style/<?= $modx->config['manager_theme'] ?>/js/modx.js?v=<?= $modx->config['settings_version'] ?>"></script>
    <?php
    // invoke OnManagerTopPrerender event
    $evtOut = $modx->invokeEvent('OnManagerTopPrerender', $_REQUEST);
    if (is_array($evtOut)) {
        echo implode("\n", $evtOut);
    }
    ?>
</head>
<body class="<?= $body_class ?>">
<input type="hidden" name="sessToken" id="sessTokenInput" value="<?= md5(session_id()) ?>" />
<div id="frameset">
    <div id="mainMenu" class="dropdown">
        <div class="container">
            <div class="row">
                <div class="cell">
                    <?php include('mainmenu.php') ?>
                </div>
                <div class="cell">
                    <ul id="settings" class="nav">
                        <li id="searchform">
                            <form action="index.php?a=71" method="post" target="main">
                                <input type="hidden" value="Search" name="submitok" />
                                <label for="searchid" class="label_searchid">
                                    <i class="fa fa-search"></i>
                                </label>
                                <input type="text" id="searchid" name="searchid" size="25" />
                                <div class="mask"></div>
                            </form>
                        </li>
                        <li>
                            <a href="../" target="_blank" title="<?= $_lang['preview'] ?>">
                                <i class="fa fa-desktop"></i>
                            </a>
                        </li>
                        <?php if ($modx->hasPermission('settings') || $modx->hasPermission('view_eventlog') || $modx->hasPermission('logs') || $modx->hasPermission('help')) { ?>
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle" onclick="return false;"><i class="fa fa-sliders"></i></a>
                                <ul class="dropdown-menu">
                                    <?php if ($modx->hasPermission('settings')) { ?>
                                        <li>
                                            <a href="index.php?a=17" target="main">
                                                <i class="fa fa-cog fw"></i><?= $_lang['edit_settings'] ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($modx->hasPermission('view_eventlog')) { ?>
                                        <li>
                                            <a href="index.php?a=70" target="main">
                                                <i class="fa fa-calendar"></i><?= $_lang['site_schedule'] ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($modx->hasPermission('view_eventlog')) { ?>
                                        <li>
                                            <a href="index.php?a=114" target="main">
                                                <i class="fa fa-exclamation-triangle"></i><?= $_lang['eventlog_viewer'] ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($modx->hasPermission('logs')) { ?>
                                        <li>
                                            <a href="index.php?a=13" target="main">
                                                <i class="fa fa-user-secret"></i><?= $_lang['view_logging'] ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="index.php?a=53" target="main">
                                                <i class="fa fa-info-circle"></i><?= $_lang['view_sysinfo'] ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($modx->hasPermission('help')) { ?>
                                        <li>
                                            <a href="index.php?a=9" target="main">
                                                <i class="fa fa-question-circle"></i><?= $_lang['help'] ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($modx->config['show_picker'] != "0") { ?>
                        <li class="dropdown menupicker">
                          <a href="javascript:;" class="dropdown-toggle" onclick="return false;"><i class="fa fa-paint-brush"></i></a>
                            <ul class="dropdown-menu">
                             <li class="item-group"><a style="cursor:default;" href="javascript:;"><i class="fa fa-paint-brush"></i> Background Color <span id="colPicked"></span></a>
                            <a title="<?= $_lang['reset'] ?>" href="javascript:;" onclick="cleanLocalStorage('EvoMenuColour')" class="resetcolor btn btn-secondary"><i class="fa fa-refresh"></i></a></li>
                              <li><form method="" action="">
                                <input type="text" value='#1d2023' id="picker" name="EvoMenuColour"/><br/>
                                </form>                               
                              </li>
                              <li class="item-group"><a style="cursor:default;" href="javascript:;"><i class="fa fa-paint-brush"></i> Text Color<span id="colPicked2"></span></a>
                            <a title="<?= $_lang['reset'] ?>" href="javascript:;" onclick="cleanLocalStorage('EvoMenuTextColour')" class="resetcolor btn btn-secondary"><i class="fa fa-refresh"></i></a></li>
                              <li><form method="" action="">
                                <input type="text" value='#bbb' id="textpicker" name="EvoMenuTextColour"/> <br/>               
                                </form>
                            <li class="item-group"><a style="cursor:default;" href="javascript:;"><i class="fa fa-paint-brush"></i> Text Hover<span id="colPicked2"></span></a>
                            <a title="<?= $_lang['reset'] ?>" href="javascript:;" onclick="cleanLocalStorage('EvoMenuTextHColour')" class="resetcolor btn btn-secondary"><i class="fa fa-refresh"></i></a></li>
                              <li><form method="" action="">
                                <input type="text" value='#f3f3f3' id="textHpicker" name="EvoMenuTextHColour"/> <br/>               <input type="reset" onclick="cleanLocalStorage('EvoMenuColour,EvoMenuTextHColour,EvoMenuTextColour')" class="btn btn-secondary" value="<?= $_lang['reset'] ?>" style="margin-left:2.2rem;">
                                <input type="submit" class="btn btn-success" value="<?= $_lang['submit'] ?>" style="margin-left:2.5rem;">
                                </form>
                          </ul>
                        </li>
                        <?php } ?>
                        <li class="dropdown account">
                            <a href="javascript:;" class="dropdown-toggle" onclick="return false;">
                                <span class="username"><?= $user['username'] ?></span>
                                <?php if ($user['photo']) { ?>
                                    <span class="icon photo" style="background-image: url(<?= MODX_SITE_URL . $user['photo'] ?>);"></span>
                                <?php } else { ?>
                                    <span class="icon"><i class="fa fa-user-circle"></i></span>
                                <?php } ?>
                                <i id="msgCounter"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($modx->hasPermission('messages')): ?>
                                    <li id="newMail"></li>
                                <?php endif; ?>
                                <?php if ($modx->hasPermission('change_password')) { ?>
                                    <li>
                                        <a onclick="" href="index.php?a=28" target="main">
                                            <i class="fa fa-lock"></i><?= $_lang['change_password'] ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li>
                                    <a href="index.php?a=8">
                                        <i class="fa fa-sign-out"></i><?= $_lang['logout'] ?>
                                    </a>
                                </li>
                                <?php
                                $style = $modx->config['settings_version'] != $modx->getVersionData('version') ? 'style="color:#ffff8a;"' : '';
                                $version = 'Evolution';
                                ?>
                                <?php
                                echo sprintf('<li><span class="dropdown-item" title="%s &ndash; %s" %s>' . $version . ' %s</span></li>', $site_name, $modx->getVersionData('full_appname'), $style, $modx->config['settings_version']);
                                ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="tree">
        <?php include('tree.php') ?>
    </div>
    <div id="main">
        <?php if($modx->config['global_tabs'] && $user['role'] == 1): ?>
        <div class="tab-row-container evo-tab-row">
            <div class="tab-row"><h2 id="evo-tab-home" class="tab selected" data-target="evo-tab-page-home"><i class="fa fa-home"></i></h2></div>
        </div>
        <div id="evo-tab-page-home" class="evo-tab-page show">
            <iframe id="mainframe" src="index.php?a=<?= $initMainframeAction ?>" scrolling="auto" frameborder="0" onload="modx.main.onload(event)"></iframe>
        </div>
        <?php else: ?>
            <iframe id="mainframe" name="main" src="index.php?a=<?= $initMainframeAction ?>" scrolling="auto" frameborder="0" onload="modx.main.onload(event)"></iframe>
        <?php endif; ?>
        <div id="mainloader"></div>
    </div>
    <div id="resizer"></div>
    <div id="searchresult"></div>

    <div id="floater" class="dropdown">
        <?php
        $sortParams = array(
            'tree_sortby',
            'tree_sortdir',
            'tree_nodename'
        );
        foreach ($sortParams as $param) {
            if (isset($_REQUEST[$param])) {
                $modx->manager->saveLastUserSetting($param, $_REQUEST[$param]);
                $_SESSION[$param] = $_REQUEST[$param];
            } else if (!isset($_SESSION[$param])) {
                $_SESSION[$param] = $modx->manager->getLastUserSetting($param);
            }
        }
        ?>
        <form name="sortFrm" id="sortFrm">
            <div class="form-group">
                <input type="hidden" name="dt" value="<?= htmlspecialchars($_REQUEST['dt']) ?>" />
                <label><?= $_lang["sort_tree"] ?></label>
                <select name="sortby" class="form-control">
                    <option value="isfolder" <?= $_SESSION['tree_sortby'] == 'isfolder' ? "selected='selected'" : "" ?>><?= $_lang['folder'] ?></option>
                    <option value="pagetitle" <?= $_SESSION['tree_sortby'] == 'pagetitle' ? "selected='selected'" : "" ?>><?= $_lang['pagetitle'] ?></option>
                    <option value="longtitle" <?= $_SESSION['tree_sortby'] == 'longtitle' ? "selected='selected'" : "" ?>><?= $_lang['long_title'] ?></option>
                    <option value="id" <?= $_SESSION['tree_sortby'] == 'id' ? "selected='selected'" : "" ?>><?= $_lang['id'] ?></option>
                    <option value="menuindex" <?= $_SESSION['tree_sortby'] == 'menuindex' ? "selected='selected'" : "" ?>><?= $_lang['resource_opt_menu_index'] ?></option>
                    <option value="createdon" <?= $_SESSION['tree_sortby'] == 'createdon' ? "selected='selected'" : "" ?>><?= $_lang['createdon'] ?></option>
                    <option value="editedon" <?= $_SESSION['tree_sortby'] == 'editedon' ? "selected='selected'" : "" ?>><?= $_lang['editedon'] ?></option>
                    <option value="publishedon" <?= $_SESSION['tree_sortby'] == 'publishedon' ? "selected='selected'" : "" ?>><?= $_lang['page_data_publishdate'] ?></option>
                    <option value="alias" <?= $_SESSION['tree_sortby'] == 'alias' ? "selected='selected'" : "" ?>><?= $_lang['page_data_alias'] ?></option>
                </select>
            </div>
            <div class="form-group">
                <select name="sortdir" class="form-control">
                    <option value="DESC" <?= $_SESSION['tree_sortdir'] == 'DESC' ? "selected='selected'" : "" ?>><?= $_lang['sort_desc'] ?></option>
                    <option value="ASC" <?= $_SESSION['tree_sortdir'] == 'ASC' ? "selected='selected'" : "" ?>><?= $_lang['sort_asc'] ?></option>
                </select>
            </div>
            <div class="form-group">
                <label><?= $_lang["setting_resource_tree_node_name"] ?></label>
                <select name="nodename" class="form-control">
                    <option value="default" <?= $_SESSION['tree_nodename'] == 'default' ? "selected='selected'" : "" ?>><?= trim($_lang['default'], ':') ?></option>
                    <option value="pagetitle" <?= $_SESSION['tree_nodename'] == 'pagetitle' ? "selected='selected'" : "" ?>><?= $_lang['pagetitle'] ?></option>
                    <option value="longtitle" <?= $_SESSION['tree_nodename'] == 'longtitle' ? "selected='selected'" : "" ?>><?= $_lang['long_title'] ?></option>
                    <option value="menutitle" <?= $_SESSION['tree_nodename'] == 'menutitle' ? "selected='selected'" : "" ?>><?= $_lang['resource_opt_menu_title'] ?></option>
                    <option value="alias" <?= $_SESSION['tree_nodename'] == 'alias' ? "selected='selected'" : "" ?>><?= $_lang['alias'] ?></option>
                    <option value="createdon" <?= $_SESSION['tree_nodename'] == 'createdon' ? "selected='selected'" : "" ?>><?= $_lang['createdon'] ?></option>
                    <option value="editedon" <?= $_SESSION['tree_nodename'] == 'editedon' ? "selected='selected'" : "" ?>><?= $_lang['editedon'] ?></option>
                    <option value="publishedon" <?= $_SESSION['tree_nodename'] == 'publishedon' ? "selected='selected'" : "" ?>><?= $_lang['page_data_publishdate'] ?></option>
                </select>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="showonlyfolders" value="<?= ($_SESSION['tree_show_only_folders'] ? 1 : '') ?>" onclick="this.value = (this.value ? '' : 1);" <?= ($_SESSION['tree_show_only_folders'] ? '' : ' checked="checked"') ?> /> <?= $_lang['view_child_resources_in_container'] ?></label>
            </div>
            <div class="text-center">
                <a href="javascript:;" class="btn btn-primary" onclick="modx.tree.updateTree();modx.tree.showSorter(event);" title="<?= $_lang['sort_tree'] ?>"><?= $_lang['sort_tree'] ?></a>
            </div>
        </form>
    </div>

    <!-- Contextual Menu Popup Code -->
    <div id="mx_contextmenu" class="dropdown" onselectstart="return false;">
        <div id="nameHolder">&nbsp;</div>
        <?php
        constructLink(3, $_style["ctx_new_document"], $_lang["create_resource_here"], $modx->hasPermission('new_document')); // new Resource
        constructLink(2, $_style["ctx_edit_document"], $_lang["edit_resource"], $modx->hasPermission('edit_document')); // edit
        constructLink(5, $_style["ctx_move_document"], $_lang["move_resource"], $modx->hasPermission('save_document')); // move
        constructLink(7, $_style["ctx_resource_duplicate"], $_lang["resource_duplicate"], $modx->hasPermission('new_document')); // duplicate
        constructLink(11, $_style["ctx_sort_menuindex"], $_lang["sort_menuindex"], !!($modx->hasPermission('edit_document') && $modx->hasPermission('save_document'))); // sort menu index
        ?>
        <div class="seperator"></div>
        <?php
        constructLink(9, $_style["ctx_publish_document"], $_lang["publish_resource"], $modx->hasPermission('publish_document')); // publish
        constructLink(10, $_style["ctx_unpublish_resource"], $_lang["unpublish_resource"], $modx->hasPermission('publish_document')); // unpublish
        constructLink(4, $_style["ctx_delete"], $_lang["delete_resource"], $modx->hasPermission('delete_document')); // delete
        constructLink(8, $_style["ctx_undelete_resource"], $_lang["undelete_resource"], $modx->hasPermission('delete_document')); // undelete
        ?>
        <div class="seperator"></div>
        <?php
        constructLink(6, $_style["ctx_weblink"], $_lang["create_weblink_here"], $modx->hasPermission('new_document')); // new Weblink
        ?>
        <div class="seperator"></div>
        <?php
        constructLink(1, $_style["ctx_resource_overview"], $_lang["resource_overview"], $modx->hasPermission('view_document')); // view
        constructLink(12, $_style["ctx_preview_resource"], $_lang["preview_resource"], 1); // preview
        ?>

    </div>

    <?php
    function constructLink($action, $img, $text, $allowed)
    {
        if ($allowed == 1) {
            echo sprintf('<div class="menuLink" id="item%s" onclick="modx.tree.menuHandler(%s);">', $action, $action);
            echo sprintf('<i class="%s"></i> %s</div>', $img, $text);
        }
    }

    ?>

    <script type="text/javascript">
        <?php if($modx->hasPermission('edit_template') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('edit_plugin')) { ?>

        if (document.getElementById('treeMenu')) {

          document.getElementById('treeMenu_openelements').onclick = function(e) {
            e.preventDefault();
            if (modx.config.global_tabs && !e.shiftKey) {
              modx.tabs({url: '<?= MODX_MANAGER_URL ?>index.php?a=76', title: '<?= $_lang["elements"] ?>'});
            } else {
              var randomNum = '<?= $_lang["elements"] ?>';
              if (e.shiftKey) {
                randomNum += ' #' + Math.floor((Math.random() * 999999) + 1);
              }
              modx.openWindow({
                url: '<?= MODX_MANAGER_URL ?>index.php?a=76',
                title: randomNum
              })
            }
          };
            <?php } ?>
            <?php if($use_browser && $modx->hasPermission('assets_images')) { ?>

          document.getElementById('treeMenu_openimages').onclick = function(e) {
            e.preventDefault();
            if (modx.config.global_tabs && !e.shiftKey) {
              modx.tabs({url: '<?= MODX_MANAGER_URL ?>media/browser/<?= $which_browser ?>/browse.php?filemanager=media/browser/<?= $which_browser ?>/browse.php&type=images', title: '<?= $_lang["images_management"] ?>'});
            } else {
              var randomNum = '<?= $_lang["files_files"] ?>';
              if (e.shiftKey) {
                randomNum += ' #' + Math.floor((Math.random() * 999999) + 1);
              }
              modx.openWindow({
                url: '<?= MODX_MANAGER_URL ?>media/browser/<?= $which_browser ?>/browse.php?&type=images',
                title: randomNum
              })
            }
          };
            <?php } ?>
            <?php if($use_browser && $modx->hasPermission('assets_files')) { ?>

          document.getElementById('treeMenu_openfiles').onclick = function(e) {
            e.preventDefault();
            if (modx.config.global_tabs && !e.shiftKey) {
              modx.tabs({url: '<?= MODX_MANAGER_URL ?>media/browser/<?= $which_browser ?>/browse.php?filemanager=media/browser/<?= $which_browser ?>/browse.php&type=files', title: '<?= $_lang["files_files"] ?>'});
            } else {
              var randomNum = '<?= $_lang["files_files"] ?>';
              if (e.shiftKey) {
                randomNum += ' #' + Math.floor((Math.random() * 999999) + 1);
              }
              modx.openWindow({
                url: '<?= MODX_MANAGER_URL ?>media/browser/<?= $which_browser ?>/browse.php?&type=files',
                title: randomNum
              })
            }
          };
            <?php } ?>

        }

    </script>

    <?php
    // invoke OnManagerFrameLoader
    $modx->invokeEvent('OnManagerFrameLoader', array('action' => $action));
    ?>

</div>

</body>
</html>
