<?php
if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
if(!array_key_exists('mail_check_timeperiod', $modx->config) || !is_numeric($modx->config['mail_check_timeperiod'])) {
	$modx->config['mail_check_timeperiod'] = 5;
}
$modx_textdir = isset($modx_textdir) ? $modx_textdir : null;
$mxla = $modx_lang_attribute ? $modx_lang_attribute : 'en';

if(!isset($modx->config['manager_tree_width'])) {
	$modx->config['manager_tree_width'] = '320';
}

$useEVOModal = '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html <?php echo ($modx_textdir ? 'dir="rtl" lang="' : 'lang="') . $mxla . '" xml:lang="' . $mxla . '"'; ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_manager_charset ?>" />
	<title>nav</title>
	<link rel="stylesheet" type="text/css" href="media/style/common/font-awesome/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="media/style/<?php echo $modx->config['manager_theme']; ?>/style.css" />
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="media/script/mootools/mootools.js" type="text/javascript"></script>
	<script src="media/script/mootools/moodx.js" type="text/javascript"></script>
	<script type="text/javascript" src="media/script/session.js"></script>
	<script type="text/javascript">
		// TREE FUNCTIONS - FRAME
		// These functions affect the tree frame and any items that may be pointing to the tree.
		var currentFrameState = 'open';
		var defaultFrameWidth = '<?php echo !$modx_textdir ? '260,*' : '*,260'?>';
		var userDefinedFrameWidth = '<?php echo !$modx_textdir ? '260,*' : '*,260'?>';

		var workText;
		var buildText;

		// Create the AJAX mail update object before requesting it
		var updateMailerAjx = new Ajax('index.php', {
			method: 'post',
			postBody: 'updateMsgCount=true',
			onComplete: showResponse
		});
		function updateMail(now) {
			try {
				// if 'now' is set, runs immediate ajax request (avoids problem on initial loading where periodical waits for time period before making first request)
				if(now)
					updateMailerAjx.request();
				return false;
			} catch(oException) {
				// Delay first run until we're ready...
				xx = updateMail.delay(1000 * 60, '', true);
			}
		}

		function showResponse(request) {
			var counts = request.split(',');
			var elm = $('msgCounter');
			if(elm) elm.innerHTML = '(' + counts[0] + ' / ' + counts[1] + ')';
			var elm = $('newMail');
			if(elm) elm.style.display = counts[0] > 0 ? 'inline' : 'none';
		}

		window.addEvent('load', function() {
			updateMail(true); // First run update
			updateMail.periodical(<?php echo $modx->config['mail_check_timeperiod'] * 1000 ?>, '', true); // Periodical Updater
			if(top.__hideTree) {
				// display toc icon
				var elm = $('tocText');
				if(elm) elm.innerHTML = "<a href='#' onclick='document.mainMenu.defaultTreeFrame();'><?php echo $_lang['show_tree']?></a>";
			}
		});


		function setTreeFrameWidth(pos) {
			if(pos > 0) {
				parent.document.getElementById('frameset').className = 'sidebar-opened';
				localStorage.setItem('MODX_lastPositionSideBar', 0);
			} else {
				parent.document.getElementById('frameset').className = 'sidebar-closed';
				localStorage.setItem('MODX_lastPositionSideBar', parseInt(parent.document.getElementById('tree').offsetWidth));
			}
			parent.document.cookie = 'MODX_positionSideBar=' + pos;
			parent.document.getElementById('tree').style.width = pos + 'px';
			parent.document.getElementById('resizer').style.left = pos + 'px';
			parent.document.getElementById('main').style.left = pos + 'px';
		}

		function toggleTreeFrame() {
			var pos = parseInt(parent.document.getElementById('tree').offsetWidth) != 0 ? 0 : (localStorage.getItem('MODX_lastPositionSideBar') ? parseInt(localStorage.getItem('MODX_lastPositionSideBar')) : 320);
			setTreeFrameWidth(pos);
		}


		function hideTreeFrame() {
			var pos = 0;
			setTreeFrameWidth(pos);
		}

		function defaultTreeFrame() {
			var pos = 300;
			setTreeFrameWidth(pos);
		}


		//toggle TopMenu Frame
		function setMenuFrameHeight(pos) {
			parent.document.getElementById('tree').style.top = pos + 'px';
			parent.document.getElementById('resizer').style.top = pos + 'px';
			parent.document.getElementById('resizer2').style.top = pos + 'px';
			parent.document.getElementById('main').style.top = pos + 'px';
			parent.document.getElementById('mainMenu').style.height = pos + 'px';
		}

		function toggleMenuFrame() {
			var pos = parseInt(parent.document.getElementById('mainMenu').style.height) != 5 ? 5 : 48;
			setMenuFrameHeight(pos);
		}

		function hideMenuFrame() {
			var pos = 5;
			setMenuFrameHeight(pos);
		}

		function defaultMenuFrame() {
			var pos = 65;
			setMenuFrameHeight(pos);
		}


		// TREE FUNCTIONS - Expand/ Collapse
		// These functions affect the expanded/collapsed state of the tree and any items that may be pointing to it
		function expandTree() {
			try {
				parent.tree.d.openAll();  // dtree
			} catch(oException) {
				zz = window.setTimeout('expandTree()', 1000);
			}
		}

		function collapseTree() {
			try {
				parent.tree.d.closeAll();  // dtree
			} catch(oException) {
				yy = window.setTimeout('collapseTree()', 1000);
			}
		}

		// GENERAL FUNCTIONS - Refresh
		// These functions are used for refreshing the tree or menu
		function reloadtree() {
			var elm = $('buildText');
			if(elm) {
				elm.innerHTML = '<img src="<?php echo $_style['icons_loading_doc_tree']?>" width="16" height="16" />&nbsp;<?php echo $_lang['loading_doc_tree']?>';
				elm.style.display = 'block';
			}
			top.tree.saveFolderState(); // save folder state
			setTimeout('top.tree.restoreTree()', 200);
		}

		function reloadmenu() {
			<?php if($manager_layout == 0) { ?>
			var elm = $('buildText');
			if(elm) {
				elm.innerHTML = '<img src="<?php echo $_style['icons_working']?>" width="16" height="16" />&nbsp;<?php echo $_lang['loading_menu']?>';
				elm.style.display = 'block';
			}
			parent.mainMenu.location.reload();
			<?php } ?>
		}

		function startrefresh(rFrame) {
			if(rFrame == 1) {
				x = window.setTimeout('reloadtree()', 500);
			}
			if(rFrame == 2) {
				x = window.setTimeout('reloadmenu()', 500);
			}
			if(rFrame == 9) {
				x = window.setTimeout('reloadmenu()', 500);
				y = window.setTimeout('reloadtree()', 500);
			}
			if(rFrame == 10) {
				window.top.location.href = "../<?php echo MGR_DIR;?>";
			}
		}

		// GENERAL FUNCTIONS - Work
		// These functions are used for showing the user the system is working
		function work() {
			var elm = $('workText');
			if(elm) elm.innerHTML = '<img src="<?php echo $_style['icons_working']?>" width="16" height="16" />&nbsp;<?php echo $_lang['working']?>';
			else w = window.setTimeout('work()', 50);
		}

		function stopWork() {
			var elm = $('workText');
			if(elm) elm.innerHTML = "";
			else  ww = window.setTimeout('stopWork()', 50);
		}

		// GENERAL FUNCTIONS - Remove locks
		// This function removes locks on documents, templates, parsers, and snippets
		function removeLocks() {
			if(confirm("<?php echo $_lang['confirm_remove_locks']?>") == true) {
				top.main.document.location.href = "index.php?a=67";
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

		function setLastClickedElement(type, id) {
			localStorage.setItem('MODX_lastClickedElement', '[' + type + ',' + id + ']');
		}

		function modxOpenWindow(data) {
			if(typeof data == 'object') {
				if(data.width == undefined)
					data.width = parent.window.outerWidth * 0.9;
				if(data.height == undefined)
					data.height = parent.window.outerHeight * 0.8;
				if(data.left == undefined)
					data.left = parent.window.outerWidth * 0.05;
				if(data.top == undefined)
					data.top = parent.window.outerHeight * 0.1;
				if(data.title == undefined)
					data.title = Math.floor((Math.random() * 999999) + 1);
				if(data.url !== undefined)
					window.open(data.url, data.title, 'width=' + data.width + ',height=' + data.height + ',top=' + data.top + ',left=' + data.left + ',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no');
				return false;
			}
		}
	</script>
</head>
<body id="topMenu" class="<?php echo $modx_textdir ? 'rtl' : 'ltr' ?>">
<?php
// invoke OnManagerTopPrerender event
$evtOut = $modx->invokeEvent('OnManagerTopPrerender', $_REQUEST);
if(is_array($evtOut)) {
	echo implode("\n", $evtOut);
}
?>

<div class="mainmenu">
	<form name="menuForm" action="l4mnu.php">
		<input type="hidden" name="sessToken" id="sessTokenInput" value="<?php echo md5(session_id()); ?>" />
		<div id="Navcontainer">
			<div id="divNav">
				<?php include('mainmenu.php'); ?>
			</div>
		</div>
	</form>
	<div id="topbar">
		<div id="topbar-container">
			<div id="statusbar">
				<span id="buildText"></span>
				<span id="workText"></span>
			</div>
		</div>
	</div>
</div>
<div id="supplementalNav">
	<ul>
		<li class="account">
			<a class="account-dropdown-toggle dropdown-toggle">
				<?php
				$user = $modx->getUserInfo($modx->getLoginUserID());
				?>
				<div class="username"><?php echo $user['username'] ?></div>
				<?php if($user['photo']) { ?>
					<div class="icon" style="background-image: url(<?php echo MODX_SITE_URL . $user['photo'] ?>);"></div>
				<?php } else { ?>
					<div class="icon">
						<i class="fa fa-user-circle" aria-hidden="true"></i>
					</div>
				<?php } ?>
			</a>
			<ul class="account-dropdown-menu dropdown-menu">
				<li id="tocText"></li>

				<?php if($modx->hasPermission('change_password')) { ?>
					<li>
						<a onclick="this.blur();" href="index.php?a=28" target="main"><i class="fa fa-lock"></i><?php echo $_lang['change_password'] ?>
						</a>
					</li>
				<?php } ?>

				<li>
					<a href="index.php?a=8" target="_top"><i class="fa fa-sign-out" aria-hidden="true"></i><?php echo $_lang['logout'] ?>
					</a>
				</li>

				<?php $style = $modx->config['settings_version'] != $modx->getVersionData('version') ? 'style="color:#ffff8a;"' : '';
				$version = stristr($modx->config['settings_version'], 'd') === FALSE ? 'MODX Evolution' : 'MODX EVO Custom';
				?>
				<?php
				echo sprintf('<li><span title="%s &ndash; %s" %s>' . $version . ' %s</span></li>', $site_name, $modx->getVersionData('full_appname'), $style, $modx->config['settings_version']);
				?>
			</ul>
		</li>
		<li>
			<a class="dropdown-toggle">
				<i class="fa fa-sliders" aria-hidden="true"></i>
			</a>
			<ul class="dropdown-menu">
				<?php if($modx->hasPermission('settings')) { ?>
					<li>
						<a href="index.php?a=17" target="main" onclick="document.mainMenu.setLastClickedElement(0,0);this.blur();"><i class="fa fa-cog fw"></i><?php echo $_lang['edit_settings'] ?>
						</a>
					</li>
				<?php } ?>

				<?php if($modx->hasPermission('view_eventlog')) { ?>
					<li>
						<a href="index.php?a=70" target="main" onclick="document.mainMenu.setLastClickedElement(0,0);this.blur();"><i class="fa fa-calendar"></i><?php echo $_lang['site_schedule'] ?>
						</a>
					</li>
				<?php } ?>

				<?php if($modx->hasPermission('view_eventlog')) { ?>
					<li>
						<a href="index.php?a=114" target="main" onclick="document.mainMenu.setLastClickedElement(0,0);this.blur();"><i class="fa fa-exclamation-triangle"></i><?php echo $_lang['eventlog_viewer'] ?>
						</a>
					</li>
				<?php } ?>

				<?php if($modx->hasPermission('logs')) { ?>
					<li>
						<a href="index.php?a=13" target="main" onclick="document.mainMenu.setLastClickedElement(0,0);this.blur();"><i class="fa fa-user-secret"></i><?php echo $_lang['view_logging'] ?>
						</a>
					</li>
					<li>
						<a href="index.php?a=53" target="main" onclick="document.mainMenu.setLastClickedElement(0,0);this.blur();"><i class="fa fa-info-circle"></i><?php echo $_lang['view_sysinfo'] ?>
						</a>
					</li>
				<?php } ?>

				<?php if($modx->hasPermission('help')) { ?>
					<li>
						<a href="index.php?a=9#version_notices" target="main" onclick="document.mainMenu.setLastClickedElement(0,0);this.blur();"><i class="fa fa-question-circle"></i><?php echo $_lang['help'] ?>
						</a>
					</li>
				<?php } ?>

			</ul>
		</li>
		<li>
			<a href="../" target="_blank" title="<?php echo $_lang['preview'] ?>" onclick="document.mainMenu.setLastClickedElement(0,0);this.blur();"><i class="fa fa-home"></i></a>
		</li>
	</ul>
</div>
<div id="searchform">
	<form action="index.php?a=71#results" method="post" target="main">
		<input type="hidden" value="Search" name="submitok" />
		<input type="text" name="searchid" size="25" class="form-control input-sm" autocomplete="off" placeholder="<?php echo $_lang['search'] ?>">
	</form>
</div>
<div id="menuSplitter"></div>
<script>
	jQuery(function() {

		stopWork();
		parent.scrollWork();

		// resizer
		var pos = {
			x: <?php echo $modx->config['manager_tree_width'] ?>
		};

		// dropdown mainMenu
		var dropdown = jQuery(parent.document).find('.dropdown');

		// Event hover
		jQuery('.dropdown-toggle').hover(function() {
			var $this = jQuery(this);
			var el = $this.parent().find('.dropdown-menu');
			var dropdown_menu = el.clone();
			var dropdown_index = el.index('.dropdown-menu');

			jQuery('a', dropdown_menu).each(function(index, element) {
				if(jQuery(element).attr('onclick')) {
					jQuery(element).attr('onclick', jQuery(element).attr('onclick').search('setLastClickedElement') == 0 ? 'document.mainMenu.' + jQuery(element).attr('onclick') : jQuery(element).attr('onclick'))
				}
			});

			jQuery('a', dropdown_menu).click(function() {
				dropdown.removeClass('show');
				jQuery('#nav li, #supplementalNav li').removeClass('active');
				el.parent('li').addClass('active');
			});

			if(jQuery(this).offset().left > jQuery(window).width() / 2) {
				dropdown.css({
					left: 'auto',
					right: jQuery(window).width() - (jQuery(this).offset().left + jQuery(this).outerWidth()) + 'px'
				})
			} else {
				dropdown.css({
					left: jQuery(this).offset().left + 'px',
					right: 'auto'
				})
			}

			if(dropdown.data('index') != dropdown_index) {
				dropdown.removeClass('show');
				dropdown.html(dropdown_menu).addClass('show').data('index', dropdown_index)
			} else {
				dropdown.toggleClass('show')
			}
		}, function() {
			var $this = jQuery(this);
			dropdown.removeClass('show');
			$this.removeClass('hover');

			dropdown.hover(function() {
				dropdown.addClass('show');
				jQuery('.dropdown-menu').eq(dropdown.data('index')).parent().find('.dropdown-toggle').addClass('hover')
			}, function() {
				dropdown.removeClass('show');
				$this.removeClass('hover')
			});
		});
		// Event
		
		// modx Search
		var searchresult = jQuery(parent.document).find('#searchresult');
		var searchresultWidth = 400/*(jQuery(this).outerWidth() - jQuery('#searchform').offset().left)*/;

		searchresult.css({
			'width': searchresultWidth + 'px',
			'margin-right': -searchresultWidth - 50 + 'px'
		});

		var modalParams = {
			width: parseInt(parent.window.outerWidth * 0.9),
			height: parseInt(parent.window.outerHeight * 0.8),
			left: parseInt(parent.window.outerWidth * 0.05),
			top: parseInt(parent.window.outerHeight * 0.1)
		};

		jQuery('#searchform input').on('keyup', function(e) {
			var self = this;
			e.preventDefault();
			jQuery(this).next('.fa').remove();

			if(this.value.length !== '' && this.value.length > 2) {
				var url = 'index.php?a=71&ajax=1';
				var params = {
					searchid: jQuery(this).val(),
					submitok: 'Search'
				};
				jQuery.ajax({
					url: url,
					data: params,
					method: 'post',
					beforeSend: function() {
						jQuery(self).after('<i class="fa fa-refresh fa-spin fa-fw"></i>');
					},
					dataFilter: function(data) {
						data = jQuery(data).find('.ajaxSearchResults');
						jQuery('a', data).each(function(i, el) {
							jQuery(el).attr('target', 'main').append('<span onclick="<?php echo($useEVOModal ? 'top.EVO.modal.show' : 'top.mainMenu.modxOpenWindow') ?>({title:\'' + el.innerText + '\',id:\'' + el.id + '\',url:\'' + el.href + '\',width:\'' + modalParams.width + 'px\',height:\'' + modalParams.height + 'px\',left:\'' + modalParams.left + 'px\',top:\'' + modalParams.top + 'px\'});return false;"><?php echo $_style['icons_external_link']?></span>');
						});
						return data.length ? data.html() : '';
					},
					success: function(data) {
						jQuery(self).next('.fa').fadeOut();
						if(data) {
							searchresult.html('<div class="ajaxSearchResults">' + data + '</div>').addClass('open');
							jQuery('a', searchresult).click(function() {
								jQuery('.selected', searchresult).removeClass('selected');
								jQuery(this).addClass('selected')
							})
						} else {
							searchresult.removeClass('open').empty()
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				})

			} else {
				searchresult.removeClass('open').empty()
			}
		}).on('focus', function() {
			if(jQuery('.ajaxSearchResults', searchresult).length) {
				searchresult.addClass('open')
			}
		}).on('blur', function() {
			setTimeout(function() {
				if(!searchresult.is(':hover')) {
					searchresult.removeClass('open')
				}
			}, 300)
		});

		jQuery('#searchform').hover(function() {
			if(jQuery('.ajaxSearchResults', searchresult).length) {
				searchresult.addClass('open')
			}
		}, function() {
			setTimeout(function() {
				if(!searchresult.is(':hover')) {
					searchresult.removeClass('open')
				}
			}, 300)
		});

		searchresult.hover(function() {
			jQuery(this).addClass('open')
		}, function() {
			jQuery(this).removeClass('open')
		});

		// end modx Search

	});
</script>
</body>
</html>