<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

    // close the session as it is not used here
    // this should speed up frame loading, does it?
    //session_write_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_charset; ?>" />
    <title>nav</title>
    <link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css" />
    <script type="text/javascript">var MODX_MEDIA_PATH = "<?php echo IN_MANAGER_MODE ? "media":"manager/media"; ?>";</script>
    <script src="media/script/scriptaculous/prototype.js" type="text/javascript"></script>
    <script src="media/script/scriptaculous/scriptaculous.js" type="text/javascript"></script>
    <script type="text/javascript" src="media/script/error.js"></script>

    <script language="JavaScript" type="text/javascript">
    // TREE FUNCTIONS - FRAME
    // These functions affect the tree frame and any items that may be pointing to the tree.
    var currentFrameState = 'open';
    var defaultFrameWidth = '260,*';
    var userDefinedFrameWidth = '260,*';

    var workText;
    var buildText;

    Event.observe(window,'load',function() {
        if(top.__hideTree) {
            // display toc icon
            var elm = $('tocText');
            if(elm) elm.innerHTML = "<a href='javascript:defaultTreeFrame();'><img src='<?php echo $_style['show_tree']; ?>' alt='<?php echo $_lang['show_tree']; ?>' width='16' height='16' /><\/a>";
        }
    })

    function hideTreeFrame() {
        userDefinedFrameWidth = parent.document.getElementsByTagName("FRAMESET").item(1).cols;
        currentFrameState = 'closed';
        try {
            var elm = $('tocText');
            if(elm) elm.innerHTML = "<a href='javascript:defaultTreeFrame();'><img src='<?php echo $_style['show_tree']; ?>' alt='<?php echo $_lang['show_tree']; ?>' width='16' height='16' /><\/a>";
            parent.document.getElementsByTagName("FRAMESET").item(1).cols = '0,*';
            top.__hideTree = true;
        } catch(oException) {
            x=window.setTimeout('hideTreeFrame()', 1000);
        }
    }

    function defaultTreeFrame() {
        userDefinedFrameWidth = defaultFrameWidth;
        currentFrameState = 'open';
        try {
            var elm = $('tocText')
            if(elm)elm.innerHTML = "";
            parent.document.getElementsByTagName("FRAMESET").item(1).cols = defaultFrameWidth;
            top.__hideTree = false;
        } catch(oException) {
            z=window.setTimeout('defaultTreeFrame()', 1000);
        }
    }

    // TREE FUNCTIONS - Expand/ Collapse
    // These functions affect the expanded/collapsed state of the tree and any items that may be pointing to it
    function expandTree() {
        try {
            parent.tree.d.openAll();  // dtree
        } catch(oException) {
            zz=window.setTimeout('expandTree()', 1000);
        }
    }

    function collapseTree() {
        try {
            parent.tree.d.closeAll();  // dtree
        } catch(oException) {
            yy=window.setTimeout('collapseTree()', 1000);
        }
    }

    // GENERAL FUNCTIONS - Messages
    // These functions are used for the messaging system
    function updateMsgCount(nrnewmessages, nrtotalmessages, messagesallowed) {
//      messagestr = "( " + nrmessages +" / " + nrtotalmessages +" )";
//      try {
//          var elm = $('msgCounter@mainMenu'); to use mootools
//          /* message count disabled in opera, let's solve it when Opera 9.0 final will be released */
//      if(navigator.userAgent.toLowerCase().indexOf("opera")==-1) elm.innerHTML = messagestr;
//          elm = $('newMail');
//          if (elm) elm.style.display = (nrnewmessages>0) ? "inline":"none";
//      }
//      catch(oException) {
//          nrmessages = nrmessages; messagesallowed = messagesallowed;
//          xx=window.setTimeout('updateMsgCount(nrmessages,nrtotalmessages,messagesallowed)', 1000);
//      }
    }

    function startmsgcount(nr, nrtotal, allow){
//      nrmessages = nr; nrtotalmessages=nrtotal; messagesallowed = allow;
//      x=window.setTimeout('updateMsgCount(nrmessages,nrtotalmessages,messagesallowed)',1000);
    }

    // GENERAL FUNCTIONS - Refresh
    // These functions are used for refreshing the tree or menu
    function reloadtree() {
        var elm = $('buildText');
        if (elm) {
            elm.innerHTML = "&nbsp;&nbsp;<img src='<?php echo $_style["icons_loading_doc_tree"]; ?>' width='16' height='16' />&nbsp;<?php echo $_lang['loading_doc_tree']; ?>";
            elm.style.display = 'inline';
        }
        parent.tree.saveFolderState(); // save folder state
        parent.tree.restoreTree();
    }

    function reloadmenu() {
    <?php if($manager_layout==0) { ?>
        var elm = $('buildText')
        if (elm) elm.innerHTML = "&nbsp;&nbsp;<img src='<?php echo $_style["icons_working"]; ?>' width='16' height='16' />&nbsp;<?php echo $_lang['loading_menu']; ?>";
        parent.mainMenu.location.reload();
    <?php } ?>
    }

    function startrefresh(rFrame){
        if(rFrame==1){
            x=window.setTimeout('reloadtree()',500);
        }
        if(rFrame==2) {
            x=window.setTimeout('reloadmenu()',500);
        }
        if(rFrame==9) {
            x=window.setTimeout('reloadmenu()',500);
            y=window.setTimeout('reloadtree()',500);
        }
    }

    // GENERAL FUNCTIONS - Work
    // These functions are used for showing the user the system is working
    function work() {
        var elm = $('workText');
        if (elm) elm.innerHTML = "&nbsp;<img src='<?php echo $_style["icons_working"]; ?>' width='16' height='16' />&nbsp;<?php echo $_lang['working']; ?>";
        else w=window.setTimeout('work()', 50);
    }

    function stopWork() {
        var elm = $('workText');
        if (elm) elm.innerHTML = "";
        else  ww=window.setTimeout('stopWork()', 50);
    }

    // GENERAL FUNCTIONS - Remove locks
    // This function removes locks on documents, templates, parsers, and snippets
    function removeLocks() {
        if(confirm("<?php echo $_lang['confirm_remove_locks']; ?>")==true) {
            top.main.document.location.href="index.php?a=67";
        }
    }

    function showWin() {
        window.open('../');
    }

    function stopIt() {
        top.mainMenu.stopWork();
    }

    function openCredits() {
        parent.main.document.location.href = "index.php?a=18";
        xwwd = window.setTimeout('stopIt()', 2000);
    }

    function NavToggle(element) {
        // This gives the active tab its look
        var navid = document.getElementById('nav');
        var navs = navid.getElementsByTagName('li');
        var navsCount = navs.length;
        for(j = 0; j < navsCount; j++) {
            active = (navs[j].id == element.parentNode.id) ? "active" : "";
            navs[j].className = active;
        }

        // Don't use effect if Opera detected
        if(navigator.userAgent.toLowerCase().indexOf("opera")==-1){
            //Hide all content containers
            contents = document.getElementsByClassName('subnav');
            contentsCount = contents.length;
            for(var i = 0; i < contentsCount; i++) {
                contents[i].style.display = 'none';
            }

            //Extract content container id from href
            ele = element.getAttribute('href').replace(/^.*\#/,'');

            //Magic Happens
            new Effect.Appear(ele,{duration:0.15});        
        }
        // remove focus from top nav
        if(element) element.blur();
    }
</script>

<!--[if lt IE 7]>
    <style type="text/css">
      body { behavior: url(media/script/forIE/htcmime.php?file=csshover.htc) }
      img { behavior: url(media/script/forIE/htcmime.php?file=pngbehavior.htc); }
    </style>
<![endif]-->

</head>

<body id="topMenu">

<div id="tocText"></div>
<div id="topbar">
    <div id="topbar-container">
        <div id="statusbar">
            <span id="buildText"></span>
            <span id="workText"></span>
        </div>

        <div id="supplementalNav">
            &nbsp;<img src="<?php echo $_style['icons_user_current']; ?>" width="16" height="16" />
            <?php 
                echo ($modx->hasPermission('change_password'))? '<a onclick="this.blur();" href="index.php?a=28" target="main">'.$modx->getLoginUserName().'</a>': $modx->getLoginUserName();
            ?>
            <?php if($modx->hasPermission('messages')) { ?>
                | <span id="newMail"><a href="index.php?a=10" title="<?php echo $_lang["you_got_mail"]; ?>" target="main"><img src="<?php echo $_style['icons_mail']; ?>" width="16" height="16" /></a></span>
                <a onclick="this.blur();" href="index.php?a=10" target="main"><?php echo $_lang["messages"]; ?><!--<span id="msgCounter">( ? / ? )</span>--></a>
            <?php } ?> 
            <?php if($modx->hasPermission('help')) { ?>
                &nbsp;|&nbsp;<a href="index.php?a=9" target="main"><?php echo $_lang["help"]; ?></a>
            <?php } ?>
                &nbsp;|&nbsp;<a href="index.php?a=8" target="_top"><?php echo $_lang["logout"]; ?></a>
                &nbsp;|&nbsp;<span title="<?php echo $site_name ;?> - <?php echo $full_appname; ?>"><?php echo $version;?></span>&nbsp;
        </div>
        <!-- close #supplementalNav -->
    </div>
    
    <form action="index.php" style="margin:0;padding:0;position:absolute;top:1000px;">
        <input type="text" name="focusStealer" />
    </form>
</div> 


<form name="menuForm" action="l4mnu.php" class="clear">
<div id="Navcontainer">
<div id="divNav">

<ul id="nav">
<!-- Site -->
<li id="limenu3" class="active"><a href="#menu3" onclick="new NavToggle(this); return false;"><?php echo $_lang["site"]; ?></a>
<ul class="subnav" id="menu3">
<!--home--><li><a onclick="this.blur();" href="index.php?a=2" target="main"><?php echo $_lang["home"]; ?></a></li>
<!--preview--><li><a onclick="this.blur();" href="../" target="_blank"><?php echo $_lang["launch_site"]; ?></a></li>
<!--clear-cache--><li><a onclick="this.blur();" href="index.php?a=26" target="main"><?php echo $_lang["refresh_site"]; ?></a></li>
<!--search--><li><a onclick="this.blur();" href="index.php?a=71" target="main"><?php echo $_lang['search']; ?></a></li>
<!--new-document--><li><a onclick="this.blur();" href="index.php?a=4" target="main"><?php echo $_lang["add_document"]; ?></a></li>
<!--new-folder--><li><a onclick="this.blur();" href="index.php?a=85" target="main"><?php echo $_lang["add_folder"]; ?></a></li>
<!--new-weblink--><li><a onclick="this.blur();" href="index.php?a=72" target="main"><?php echo $_lang["add_weblink"]; ?></a></li>
</ul>
</li>

<!--Resources-->
<?php if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin')) { ?>
<li id="limenu5"><a onclick="new NavToggle(this);return false;" href="#menu5"><?php echo $_lang["resources"]; ?></a>
<ul class="subnav" id="menu5"><!-- break these out individually soon -->
<!--resources--><li><a onclick="this.blur();" href="index.php?a=76" target="main"><?php echo $_lang["resource_management"]; ?></a></li>
<?php if($modx->hasPermission('file_manager')) { ?>
<!--manage-files--><li><a onclick="this.blur();" href="index.php?a=31" target="main"><?php echo $_lang["manage_files"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('manage_metatags')) { ?>
<!--manage-metatags--><li><a onclick="this.blur();" href="index.php?a=81" target="main"><?php echo $_lang["manage_metatags"]; ?></a></li>
<?php } ?>
</ul>
</li>
<?php } ?>


<!-- Modules -->
<?php  if($modx->hasPermission('exec_module')) { ?>
<li id="limenu9"><a href="#menu9" onclick="new NavToggle(this); return false;"><?php echo $_lang["modules"]; ?></a>
<ul class="subnav" id="menu9">
<?php if($modx->hasPermission('new_module') || $modx->hasPermission('edit_module')) { ?>
<!--manage-modules--><li><a onclick="this.blur();" href="index.php?a=106" target="main"><?php echo $_lang["module_management"]; ?></a></li>
<?php } ?>
<?php
$list = '';  // initialize list variable
$rs = $modx->db->select('*',$modx->getFullTableName('site_modules'));  // get modules
while($content = $modx->db->getRow($rs)) {
$list .= '<li><a onclick="this.blur();" href="index.php?a=112&amp;id='.$content['id'].'" target="main">'.$content['name'].'</a></li>'."\n";
}
echo $list;
?>
</ul>
</li>
<?php } ?>


<!-- Security (users) -->
<?php if($modx->hasPermission('new_user') || $modx->hasPermission('edit_user') || $modx->hasPermission('new_role') || $modx->hasPermission('edit_role') || $modx->hasPermission('access_permissions')||$modx->hasPermission('new_web_user') || $modx->hasPermission('edit_web_user') || $modx->hasPermission('web_access_permissions')) { ?>
<li id="limenu2"><a href="#menu2" onclick="new NavToggle(this); return false;"><?php echo $_lang["users"]; ?></a>
<ul class="subnav" id="menu2">
<?php if($modx->hasPermission('new_user')||$modx->hasPermission('edit_user')) { ?>
<!--manager-users--><li><a onclick="this.blur();" href="index.php?a=75" target="main"><?php echo $_lang["user_management_title"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('new_web_user')||$modx->hasPermission('edit_web_user')) { ?>
<!--web-users--><li><a onclick="this.blur();" href="index.php?a=99" target="main"><?php echo $_lang["web_user_management_title"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('new_role')||$modx->hasPermission('edit_user')) { ?>
<!--roles--><li><a onclick="this.blur();" href="index.php?a=86" target="main"><?php echo $_lang["role_management_title"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('access_permissions')) { ?>
<!--manager-perms--><li><a onclick="this.blur();" href="index.php?a=40" target="main"><?php echo $_lang["manager_permissions"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('web_access_permissions')) { ?>
<!--web-user-perms--><li><a onclick="this.blur();" href="index.php?a=91" target="main"><?php echo $_lang["web_permissions"]; ?></a></li>
<?php } ?>
</ul>
</li>
<?php } ?>


<!-- Tools -->
<li id="limenu1-1"><a href="#menu1-1" onclick="new NavToggle(this); return false;"><?php echo $_lang["tools"]; ?></a>
<ul class="subnav" id="menu1-1">
<?php if($modx->hasPermission('bk_manager')) { ?>
<!--backup-mgr--><li><a onclick="this.blur();" href="index.php?a=93" target="main"><?php echo $_lang["bk_manager"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('settings')) { ?>
<!--unlock-pages--><li><a onclick="this.blur();" href="javascript:top.scripter.removeLocks();"><?php echo $_lang["remove_locks"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('new_document')) { ?>
<!--import-html--><li><a onclick="this.blur();" href="index.php?a=95" target="main"><?php echo $_lang["import_site"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('edit_document')) { ?>
<!--export-static-site--><li><a onclick="this.blur();" href="index.php?a=83" target="main"><?php echo $_lang["export_site"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('settings')) { ?>
<!--configuration--><li><a onclick="this.blur();" href="index.php?a=17" target="main"><?php echo $_lang["edit_settings"]; ?></a></li>
<?php } ?>
</ul>
</li>



<!-- Reports -->
<li id="limenu1-2"><a href="#menu1-2" onclick="new NavToggle(this); return false;"><?php echo $_lang["reports"]; ?></a>
<ul class="subnav" id="menu1-2">
<!--site-sched--><li><a onclick="this.blur();" href="index.php?a=70" target="main"><?php echo $_lang["site_schedule"]; ?></a></li>
<?php if($modx->hasPermission('view_eventlog')) { ?>
<!--manager-events--><li><a onclick="this.blur();" href="index.php?a=114" target="main"><?php echo $_lang["eventlog_viewer"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('logs')) { ?>
<!--manager-audit-trail--><li><a onclick="this.blur();" href="index.php?a=13" target="main"><?php echo $_lang["view_logging"]; ?></a></li>
<?php } ?>
<?php if($modx->hasPermission('settings')) { ?>
<!--system-info--><li><a onclick="this.blur();" href="index.php?a=53" target="main"><?php echo $_lang["view_sysinfo"]; ?></a></li>
<?php } ?>
</ul>
</li>

</ul>

</div></div>
</form>

</body>
</html>
