<?php
if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
$mxla = $modx_lang_attribute ? $modx_lang_attribute : 'en';

// invoke OnManagerRegClientStartupHTMLBlock event
$evtOut = $modx->invokeEvent('OnManagerMainFrameHeaderHTMLBlock');
$modx_textdir = isset($modx_textdir) ? $modx_textdir : null;
$onManagerMainFrameHeaderHTMLBlock = is_array($evtOut) ? implode("\n", $evtOut) : '';
$textdir = $modx_textdir === 'rtl' ? 'rtl' : 'ltr';
if(!isset($modx->config['mgr_jquery_path'])) {
	$modx->config['mgr_jquery_path'] = 'media/script/jquery/jquery.min.js';
}
if(!isset($modx->config['mgr_date_picker_path'])) {
	$modx->config['mgr_date_picker_path'] = 'media/script/air-datepicker/datepicker.inc.php';
}

if(!empty($_COOKIE['MODX_themeColor'])) {
	$body_class .= ' ' . $_COOKIE['MODX_themeColor'];
}

?>
<!DOCTYPE html>
<html lang="<?php echo $mxla; ?>" dir="<?php echo $textdir; ?>">
<head>
	<title>Evolution CMS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_manager_charset; ?>" />
	<link rel="stylesheet" type="text/css" href="media/style/<?php echo $modx->config['manager_theme']; ?>/style.css?v=<?php echo $modx->config['settings_version'] ?>" />
	<script type="text/javascript" src="media/script/tabpane.js"></script>
	<?php echo sprintf('<script src="%s" type="text/javascript"></script>' . "\n", $modx->config['mgr_jquery_path']); ?>
	
	<?php 
	$aArr = array('2');
	if(!in_array($_REQUEST['a'] ,$aArr)) {?>
		<script src="media/script/mootools/mootools.js" type="text/javascript"></script>
		<script src="media/script/mootools/moodx.js" type="text/javascript"></script>
	<?php } ?>

	<!-- OnManagerMainFrameHeaderHTMLBlock -->
	<?php echo $onManagerMainFrameHeaderHTMLBlock . "\n"; ?>

	<script type="text/javascript">
		/* <![CDATA[ */

		function document_onload() {
			stopWorker();

			<?php
			if(isset($_REQUEST['r']) && preg_match('@^[0-9]+$@', $_REQUEST['r'])) {
				echo 'doRefresh(' . $_REQUEST['r'] . ");\n";
			}
			?>

			var actionButtons = document.getElementById('actions'),
				actionSelect = document.getElementById('stay');
			if(actionButtons !== null && actionSelect !== null) {
				var actionPlus = actionButtons.querySelector('.plus'),
					actionSaveButton = actionButtons.querySelector('a#Button1') || actionButtons.querySelector('#Button1 > a'),
					actionStay = [];
				actionPlus.classList.add('dropdown-toggle');
				actionStay['stay1'] = '<i class="<?php echo $_style['actions_file'] ?>"></i>';
				actionStay['stay2'] = '<i class="<?php echo $_style['actions_pencil'] ?>"></i>';
				actionStay['stay3'] = '<i class="<?php echo $_style['actions_reply'] ?>"></i>';
				if(actionSelect.value) {
					actionSaveButton.innerHTML += '<i class="<?php echo $_style['actions_plus'] ?>"></i><span> + </span>' + actionStay['stay' + actionSelect.value] + '<span>' + actionSelect.children['stay' + actionSelect.value].innerHTML + '</span>'
				}
				var actionSelectNewOption = null,
					actionSelectOptions = actionSelect.children,
					div = document.createElement('div');
				div.className = 'dropdown-menu';
				actionSaveButton.parentNode.classList.add('dropdown');
				for(var i = 0; i < actionSelectOptions.length; i++) {
					if(!actionSelectOptions[i].selected) {
						actionSelectNewOption = document.createElement('SPAN');
						actionSelectNewOption.className = 'btn btn-block';
						actionSelectNewOption.dataset.id = i;
						actionSelectNewOption.innerHTML = actionStay[actionSelect.children[i].id] + ' <span>' + actionSelect.children[i].innerHTML + '</span>';
						actionSelectNewOption.onclick = function() {
							var s = actionSelect.querySelector('option[selected=selected]');
							if(s) s.selected = false;
							actionSelect.children[this.dataset.id].selected = true;
							actionSaveButton.click()
						};
						div.appendChild(actionSelectNewOption)
					}
				}
				actionSaveButton.parentNode.appendChild(div);
				actionPlus.onclick = function() {
					this.parentNode.classList.toggle('show')
				}
			}
		}

		function reset_path(elementName) {
			document.getElementById(elementName).value = document.getElementById('default_' + elementName).innerHTML;
		}

		var dontShowWorker = false;

		function document_onunload() {
			if(!dontShowWorker) {
				top.mainMenu.work();
			}
		}

		// set tree to default action.
		if(parent.tree) parent.tree.ca = "open";

		// call the updateMail function, updates mail notification in top navigation
		if(top.mainMenu) {
			if(top.mainMenu.updateMail) {
				top.mainMenu.updateMail(true);
			}
		}

		function stopWorker() {
			try {
				parent.mainMenu.stopWork();
			} catch(oException) {
				ww = window.setTimeout('stopWorker()', 500);
			}
		}

		function doRefresh(r) {
			try {
				rr = r;
				top.mainMenu.startrefresh(rr);
			} catch(oException) {
				vv = window.setTimeout('doRefresh()', 1000);
			}
		}

		var documentDirty = false;
		var timerForUnload;

		function checkDirt(evt) {
			if(documentDirty === true) {
				var message = "<?php echo $_lang['warning_not_saved']; ?>";
				if(typeof evt === 'undefined') {
					evt = window.event;
				}
				if(evt) {
					evt.returnValue = message;
				}
				timerForUnload = setTimeout('stopWorker()', 100);
				return message;
			}
		}

		function saveWait(fName) {
			document.getElementById("savingMessage").innerHTML = "<?php echo $_lang['saving']; ?>";
			for(i = 0; i < document.forms[fName].elements.length; i++) {
				document.forms[fName].elements[i].disabled = 'disabled';
			}
		}

		var managerPath = "";

		function hideLoader() {
			document.getElementById('preLoader').style.display = "none";
		}

		// add the 'unsaved changes' warning event handler
		if(typeof window.addEventListener !== "undefined") {
			window.addEventListener('beforeunload', function() {
				checkDirt();
				document_onunload()
			}, false)
		} else if(typeof window.attachEvent !== "undefined") {
			window.attachEvent('onbeforeunload', function() {
				checkDirt();
				document_onunload()
			})
		} else {
			window.onbeforeunload = function() {
				checkDirt();
				document_onunload()
			}
		}

		if(typeof window.addEventListener !== "undefined") {
			window.addEventListener("load", function() {
				document_onload()
			}, false);
		} else if(typeof window.attachEvent !== "undefined") {
			window.attachEvent("onload", function() {
				document_onload()
			})
		} else {
			window.onload = function() {
				document_onload()
			}
		}

		window.onunload = function() {
			clearTimeout(timerForUnload);
		}

		/* ]]> */
	</script>
</head>
<body <?php echo $modx_textdir ? ' class="rtl"' : '' ?> class="<?php echo $body_class ?>">
