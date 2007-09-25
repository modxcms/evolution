<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
    if (!array_key_exists('mail_check_timeperiod',$modx->config) || !is_numeric($modx->config['mail_check_timeperiod'])) {
    	$modx->config['mail_check_timeperiod'] = 5;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html <?php echo $modx->config['manager_direction'] == 'rtl' ? 'dir="rtl"' : '';?> lang="<?php echo $modx->config['manager_lang_attribute'];?>" xml:lang="<?php echo $modx->config['manager_lang_attribute'];?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_charset; ?>" />
    <title>nav</title>
    <link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css" />
    <script type="text/javascript">var MODX_MEDIA_PATH = "<?php echo IN_MANAGER_MODE ? "media":"manager/media"; ?>";</script>
    <script src="media/script/mootools/mootools.js" type="text/javascript"></script>
    <script src="media/script/mootools/moodx.js" type="text/javascript"></script>
    <script type="text/javascript">
    // TREE FUNCTIONS - FRAME
    // These functions affect the tree frame and any items that may be pointing to the tree.
    var currentFrameState = 'open';
    var defaultFrameWidth = '<?php echo $modx->config['manager_direction']=='ltr' ? '260,*' : '*,260';?>';
    var userDefinedFrameWidth = '<?php echo $modx->config['manager_direction']=='ltr' ? '260,*' : '*,260';?>';

    var workText;
    var buildText;
    
    // Create the AJAX mail update object before requesting it
    var updateMailerAjx = new Ajax('index.php', {method:'post', postBody:'updateMsgCount=true', onComplete:showResponse});
    function updateMail(now) {
    	try {
    	  // if 'now' is set, runs immediate ajax request (avoids problem on initial loading where periodical waits for time period before making first request)
    	  if (now)
		  updateMailerAjx.request();
		  return false;
    	} catch(oException) {
		// Delay first run until we're ready...
		xx=updateMail.delay(1000,'',true);
    	}
    };

	function showResponse(request) {
		var counts = request.split(',');
		var elm = $('msgCounter');
          if (elm) elm.innerHTML ='(' + counts[0] + ' / ' + counts[1] + ')';
        var elm = $('newMail');
		  if (elm) elm.style.display = counts[0] >0 ? 'inline' :  'none';
	}
	
	window.addEvent('load', function() {
        updateMail(true); // First run update
	updateMail.periodical(<?php echo $modx->config['mail_check_timeperiod'] * 1000; ?>, '', true); // Periodical Updater
        if(top.__hideTree) {
            // display toc icon
            var elm = $('tocText');
            if(elm) elm.innerHTML = "<a href='#' onclick='defaultTreeFrame();'><img src='<?php echo $_style['show_tree']; ?>' alt='<?php echo $_lang['show_tree']; ?>' width='16' height='16' /></a>";
        }
    });

    function hideTreeFrame() {
        userDefinedFrameWidth = parent.document.getElementsByTagName("FRAMESET").item(1).cols;
        currentFrameState = 'closed';
        try {
            var elm = $('tocText');
            if(elm) elm.innerHTML = "<a href='#' onclick='defaultTreeFrame();'><img src='<?php echo $_style['show_tree']; ?>' alt='<?php echo $_lang['show_tree']; ?>' width='16' height='16' /></a>";
            parent.document.getElementsByTagName("FRAMESET").item(1).cols = '<?php echo ($modx->config['manager_direction'] == 'ltr' ? '0,*' : '*,0')?>';
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

    // GENERAL FUNCTIONS - Refresh
    // These functions are used for refreshing the tree or menu
    function reloadtree() {
        var elm = $('buildText');
        if (elm) {
            elm.innerHTML = "&nbsp;&nbsp;<img src='<?php echo $_style["icons_loading_doc_tree"]; ?>' width='16' height='16' />&nbsp;<?php echo $_lang['loading_doc_tree']; ?>";
            elm.style.display = 'block';
        }
        top.tree.saveFolderState(); // save folder state
        setTimeout('top.tree.restoreTree()',200);
    }

    function reloadmenu() {
    <?php if($manager_layout==0) { ?>
        var elm = $('buildText')
        if (elm) {
            elm.innerHTML = "&nbsp;&nbsp;<img src='<?php echo $_style["icons_working"]; ?>' width='16' height='16' />&nbsp;<?php echo $_lang['loading_menu']; ?>";
            elm.style.display = 'block';
        }
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

<div id="tocText" <?php echo $modx->config['manager_direction'] ? 'class="tocTextRTL"' : '' ?>></div>
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
                | <span id="newMail"><a href="index.php?a=10" title="<?php echo $_lang["you_got_mail"]; ?>" target="main"> <img src="<?php echo $_style['icons_mail']; ?>" width="16" height="16" /></a></span>
                <a onclick="this.blur();" href="index.php?a=10" target="main"><?php echo $_lang["messages"]; ?> <span id="msgCounter">( ? / ? )</span></a>
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
<?php

// Concatenate menu items based on permissions

// Site Menu
$sitemenu = '';
// home
$sitemenu .= '<li><a onclick="this.blur();" href="index.php?a=2" target="main">' . $_lang["home"] . '</a></li>';
// preview
$sitemenu .= '<li><a onclick="this.blur();" href="../" target="_blank">' . $_lang["preview"] . '</a></li>';
// clear-cache
$sitemenu .= '<li><a onclick="this.blur();" href="index.php?a=26" target="main">' . $_lang["refresh_site"] .'</a></li>';
// search
$sitemenu .= '<li><a onclick="this.blur();" href="index.php?a=71" target="main">' . $_lang['search'] .'</a></li>';
if ($modx->hasPermission('new_document')) { 
	// new-document
	$sitemenu .= '<li><a onclick="this.blur();" href="index.php?a=4" target="main">' . $_lang['add_document'] .'</a></li>';
	// new-weblink
	$sitemenu .= '<li><a onclick="this.blur();" href="index.php?a=72" target="main">' . $_lang['add_weblink'] .'</a></li>';
}

// Resources Menu
$resourcemenu = '';
// Resources
if($modx->hasPermission('new_template') || $modx->hasPermission('edit_template') || $modx->hasPermission('new_snippet') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('new_chunk') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('new_plugin') || $modx->hasPermission('edit_plugin')) {
	$resourcemenu .= '<li><a onclick="this.blur();" href="index.php?a=76" target="main">' . $_lang["resource_management"] . '</a></li>';
}
// Manage-Files
if($modx->hasPermission('file_manager')) {
	$resourcemenu .= '<li><a onclick="this.blur();" href="index.php?a=31" target="main">' . $_lang["manage_files"] .'</a></li>'."\n";
}
// Manage-Metatags
if($modx->hasPermission('manage_metatags')) { 
	$resourcemenu .= '<li><a onclick="this.blur();" href="index.php?a=81" target="main">' . $_lang["manage_metatags"] . '</a></li>'."\n";
}

// Modules Menu Items
$modulemenu = '';
// manage-modules
if($modx->hasPermission('new_module') || $modx->hasPermission('edit_module')) { 
	$modulemenu .= '<li><a onclick="this.blur();" href="index.php?a=106" target="main">' . $_lang["module_management"] . '</a></li>'."\n";
}
// Each module
if($modx->hasPermission('exec_module')) {
	if ($_SESSION['mgrRole'] != 1) {
		// Display only those modules the user can execute
		$rs = $modx->db->query('SELECT DISTINCT sm.id, sm.name, mg.member
		FROM '.$modx->getFullTableName('site_modules').' AS sm
		LEFT JOIN '.$modx->getFullTableName('site_module_access').' AS sma ON sma.module = sm.id
		LEFT JOIN '.$modx->getFullTableName('member_groups').' AS mg ON sma.usergroup = mg.user_group
		WHERE (mg.member IS NULL OR mg.member = '.$modx->getLoginUserID().') AND sm.disabled != 1');
	} else {
		// Admins get the entire list
		$rs = $modx->db->select('*',$modx->getFullTableName('site_modules'), 'disabled != 1');
	}
	while($content = $modx->db->getRow($rs)) {
		$modulemenu .= '<li><a onclick="this.blur();" href="index.php?a=112&amp;id='.$content['id'].'" target="main">'.$content['name'].'</a></li>'."\n";
	}
}

// Security menu items (users)
$securitymenu = '';
// manager-users
if($modx->hasPermission('edit_user')) {
	$securitymenu .= '<li><a onclick="this.blur();" href="index.php?a=75" target="main">' . $_lang["user_management_title"] . '</a></li>'."\n";
}
// web-users
if($modx->hasPermission('edit_web_user')) { 
	$securitymenu .= '<li><a onclick="this.blur();" href="index.php?a=99" target="main">' . $_lang["web_user_management_title"] . '</a></li>'."\n";
}
// roles
if($modx->hasPermission('edit_user')) {
	$securitymenu .= '<li><a onclick="this.blur();" href="index.php?a=86" target="main">' . $_lang["role_management_title"] . '</a></li>'."\n";
}
// manager-perms
if($modx->hasPermission('access_permissions')) {
	$securitymenu .= '<li><a onclick="this.blur();" href="index.php?a=40" target="main">' . $_lang["manager_permissions"] . '</a></li>'."\n";
}
// web-user-perms
if($modx->hasPermission('web_access_permissions')) {
	$securitymenu .= '<li><a onclick="this.blur();" href="index.php?a=91" target="main">' . $_lang["web_permissions"] . '</a></li>'."\n";
}

// Tools Menu
$toolsmenu = '';
// backup-mgr
if($modx->hasPermission('bk_manager')) {
	$toolsmenu .= '<li><a onclick="this.blur();" href="index.php?a=93" target="main">' . $_lang["bk_manager"] . '</a></li>'."\n";
}
// unlock-pages
if($modx->hasPermission('bk_manager')) {
	$toolsmenu .= '<li><a onclick="this.blur();" href="javascript:removeLocks();">' . $_lang["remove_locks"] .'</a></li>'."\n";
}
// import-html
if($modx->hasPermission('import_static')) {
	$toolsmenu .= '<li><a onclick="this.blur();" href="index.php?a=95" target="main">' . $_lang["import_site"] .'</a></li>';
}
// export-static-site
if($modx->hasPermission('export_static')) {
	$toolsmenu .= '<li><a onclick="this.blur();" href="index.php?a=83" target="main">' . $_lang["export_site"]. '</a></li>';
}
// configuration
if($modx->hasPermission('settings')) {
	$toolsmenu .= '<li><a onclick="this.blur();" href="index.php?a=17" target="main">' . $_lang["edit_settings"]. '</a></li>';
}

// Reports Menu
$reportsmenu = '';
// site-sched
$reportsmenu .= '<li><a onclick="this.blur();" href="index.php?a=70" target="main">' . $_lang["site_schedule"] . '</a></li>'."\n";
// eventlog
if($modx->hasPermission('view_eventlog')) {
	$reportsmenu .= '<li><a onclick="this.blur();" href="index.php?a=114" target="main">' . $_lang["eventlog_viewer"] . '</a></li>'."\n";
}
// manager-audit-trail
if($modx->hasPermission('logs')) {
	$reportsmenu .= '<li><a onclick="this.blur();" href="index.php?a=13" target="main">' . $_lang["view_logging"] . '</a></li>'."\n";
}
// system-info
if($modx->hasPermission('logs')) {
	$reportsmenu .= '<li><a onclick="this.blur();" href="index.php?a=53" target="main">' . $_lang["view_sysinfo"] . '</a></li>'."\n";
}

// Output Menus where there are items to show
if ($sitemenu) {
	echo '<li id="limenu3" class="active"><a href="#menu3" onclick="new NavToggle(this); return false;">' . $_lang["site"] . '</a>'."\n";
	echo '<ul class="subnav" id="menu3">' . $sitemenu . '</ul>'."\n";
	echo '</li>'."\n";
}
if ($resourcemenu) {
	echo '<li id="limenu5"><a href="#menu5" onclick="new NavToggle(this); return false;">' . $_lang["resources"] . '</a>'."\n";
	echo '<ul class="subnav" id="menu5">' . $resourcemenu . '</ul>'."\n";
	echo '</li>'."\n";
}
if ($modulemenu) {
	echo '<li id="limenu9"><a href="#menu9" onclick="new NavToggle(this); return false;">' . $_lang["modules"] . '</a>'."\n";
	echo '<ul class="subnav" id="menu9">' . $modulemenu . '</ul>'."\n";
	echo '</li>'."\n";
}
if ($securitymenu) {
	echo '<li id="limenu2"><a href="#menu2" onclick="new NavToggle(this); return false;">' . $_lang["users"] . '</a>'."\n";
	echo '<ul class="subnav" id="menu2">' . $securitymenu . '</ul>'."\n";
	echo '</li>'."\n";
}
if ($toolsmenu) {
	echo '<li id="limenu1-1"><a href="#menu1-1" onclick="new NavToggle(this); return false;">' . $_lang["tools"] . '</a>'."\n";
	echo '<ul class="subnav" id="menu1-1">' . $toolsmenu . '</ul>'."\n";
	echo '</li>'."\n";
}
if ($reportsmenu) {
	echo '<li id="limenu1-2"><a href="#menu1-2" onclick="new NavToggle(this); return false;">' . $_lang["reports"] . '</a>'."\n";
	echo '<ul class="subnav" id="menu1-2">' . $reportsmenu . '</ul>'."\n";
	echo '</li>'."\n";
}
?>
</ul>

</div></div>
</form>

<!-- can't find a better name :) should always be fixed -->
<div id="menuSplitter"></div>

</body>
</html>
