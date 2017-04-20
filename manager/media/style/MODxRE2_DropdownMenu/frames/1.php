<?php

if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
header("X-XSS-Protection: 0");
$_SESSION['browser'] = (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 1') !== false) ? 'legacy_IE' : 'modern';

$mxla = $modx_lang_attribute ? $modx_lang_attribute : 'en';

if(!isset($modx->config['manager_menu_height'])) {
	$modx->config['manager_menu_height'] = '48';
}
if(!isset($modx->config['manager_tree_width'])) {
	$modx->config['manager_tree_width'] = '320';
}
$modx->invokeEvent('OnManagerPreFrameLoader', array('action' => $action));

if(isset($_SESSION['onLoginForwardToAction']) && is_int($_SESSION['onLoginForwardToAction'])) {
	$initMainframeAction = $_SESSION['onLoginForwardToAction'];
	unset($_SESSION['onLoginForwardToAction']);
} else {
	$initMainframeAction = 2; // welcome.static
}

$bodyClass = '';

if(isset($_COOKIE['MODX_positionSideBar'])) {
	$MODX_positionSideBar = $_COOKIE['MODX_positionSideBar'];
} else {
	$MODX_positionSideBar = $modx->config['manager_tree_width'];
}

if(!$MODX_positionSideBar) {
	$bodyClass .= 'sidebar-closed';
}

?>
<!DOCTYPE html>
<html <?php echo (isset($modx_textdir) && $modx_textdir ? 'dir="rtl" lang="' : 'lang="') . $mxla . '" xml:lang="' . $mxla . '"'; ?>>
<head>
	<title><?php echo $site_name ?>- (MODX CMS Manager)</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_manager_charset ?>" />
	<link rel="stylesheet" type="text/css" href="media/style/common/font-awesome/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="media/style/<?php echo $modx->config['manager_theme']; ?>/style.css" />
	<style>
		#tree { width: <?php echo $MODX_positionSideBar ?>px }
		#main, #resizer { left: <?php echo $MODX_positionSideBar ?>px }
	</style>
</head>
<body>
<div id="frameset" class="<?php echo $bodyClass ?>">
    <div id="resizer">
        <a id="hideMenu" onclick="mainMenu.toggleTreeFrame();">
			<i class="fa fa-angle-left"></i>
		</a>
    </div>	
	<div id="mainMenu">
		<iframe name="mainMenu" src="index.php?a=1&amp;f=menu" scrolling="no" frameborder="0"></iframe>
	</div>
	<div id="tree">
		<iframe name="tree" src="index.php?a=1&amp;f=tree" scrolling="no" frameborder="0"></iframe>
	</div>
	<div id="main">
		<iframe name="main" id="mainframe" src="index.php?a=<?php echo $initMainframeAction; ?>" scrolling="auto" frameborder="0" onload="if (mainMenu.stopWork()) mainMenu.stopWork(); scrollWork();"></iframe>
	</div>
	<div class="dropdown"></div>
	<div id="searchresult"></div>
	<script type="text/javascript">

		var reloadmenu = function() {
			top.mainMenu.reloadmenu()
		};

		mainMenu.reloadtree = function() {
			top.mainMenu.reloadtree()
		};

		//save scrollPosition
		function getQueryVariable(variable, query) {
			var vars = query.split('&');
			for(var i = 0; i < vars.length; i++) {
				var pair = vars[i].split('=');
				if(decodeURIComponent(pair[0]) == variable) {
					return decodeURIComponent(pair[1]);
				}
			}
		}

		function scrollWork() {
			var frm = document.getElementById("mainframe").contentWindow;
			currentPageY = localStorage.getItem('page_y');
			pageUrl = localStorage.getItem('page_url');
			if(currentPageY === undefined) {
				localStorage.setItem('page_y', 0);
			}
			if(pageUrl === null) {
				pageUrl = frm.location.search.substring(1);
			}
			if(getQueryVariable('a', pageUrl) == getQueryVariable('a', frm.location.search.substring(1))) {
				if(getQueryVariable('id', pageUrl) == getQueryVariable('id', frm.location.search.substring(1))) {
					frm.scrollTo(0, currentPageY);
				}
			}

			frm.onscroll = function() {
				if(frm.pageYOffset > 0) {
					localStorage.setItem('page_y', frm.pageYOffset);
					localStorage.setItem('page_url', frm.location.search.substring(1));
				}
			}
		}

        function ExtractNumber(value) {
            var n = parseInt(value);
            return n == null || isNaN(n) ? 0 : n
        }

		// resizer 
        var _startY = 48;
        var _dragElement;
        var _oldZIndex = 999;
        var _left;
        var mask = document.createElement('div');
        mask.id = 'mask_resizer';
        mask.style.zIndex = _oldZIndex;

		if(!localStorage.getItem('MODX_lastPositionSideBar')) {
			localStorage.setItem('MODX_lastPositionSideBar', <?php echo $modx->config['manager_tree_width'] ?>);
		}
		
        InitDragDrop();

        function InitDragDrop() {
            document.getElementById('resizer').onmousedown = OnMouseDown;
            document.getElementById('resizer').onmouseup = OnMouseUp
        }

        function OnMouseDown(e) {
            if (e == null) e = window.event;
            _dragElement = e.target != null ? e.target : e.srcElement;
            if ((e.buttons == 1 && window.event != null || e.button == 0) && _dragElement.id == 'resizer') {
                _oldZIndex = _dragElement.style.zIndex;
                _dragElement.style.zIndex = 10000;
                _dragElement.style.background = '#bbb';
				localStorage.setItem('MODX_lastPositionSideBar', (_dragElement.offsetLeft > 0 ? _dragElement.offsetLeft : 0));
                document.body.appendChild(mask)
                document.onmousemove = OnMouseMove;
                document.body.focus();
                document.onselectstart = function () {
                    return false
                };
                _dragElement.ondragstart = function () {
                    return false
                };
                return false
            }
        }

        function OnMouseMove(e) {
            if (e == null) var e = window.event;
			if(e.clientX > 0) {
				_left = e.clientX
			} else {
				_left  = 0;
			}
            _dragElement.style.left = _left + 'px';
            document.getElementById('tree').style.width = _left + 'px';
            document.getElementById('main').style.left = _left + 'px'
			if(e.clientX < -2) {
				OnMouseUp(e);
			}
        }

        function OnMouseUp(e) {
            if (_dragElement != null && e.button == 0 && _dragElement.id == 'resizer') {
				if(e.clientX > 0) {
					document.getElementById('frameset').className = 'sidebar-opened';
					_left = e.clientX;
				} else {
					document.getElementById('frameset').className = 'sidebar-closed';
					_left  = 0;
				}
				document.cookie = 'MODX_positionSideBar=' + _left;
                _dragElement.style.zIndex = _oldZIndex;
                _dragElement.style.background = '';
                _dragElement.ondragstart = null;
                _dragElement = null;
                document.body.removeChild(mask);
                document.onmousemove = null;
                document.onselectstart = null;
            }
        }
		// end resizer
		
	</script>
	<?php
	$modx->invokeEvent('OnManagerFrameLoader', array('action' => $action));
	?>
</div>
</body>
</html>
