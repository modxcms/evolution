<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
$mxla = $modx_lang_attribute ? $modx_lang_attribute : 'en';

// invoke OnManagerRegClientStartupHTMLBlock event
$evtOut = $modx->invokeEvent('OnManagerMainFrameHeaderHTMLBlock');
$modx_textdir = isset($modx_textdir) ? $modx_textdir : null;
$onManagerMainFrameHeaderHTMLBlock = is_array($evtOut) ? implode("\n", $evtOut) : '';
$textdir = $modx_textdir==='rtl' ? 'rtl' : 'ltr';
if(!isset($modx->config['mgr_jquery_path']))  $modx->config['mgr_jquery_path'] = 'media/script/jquery/jquery.min.js';
if(!isset($modx->config['mgr_date_picker_path'])) $modx->config['mgr_date_picker_path'] = 'media/script/air-datepicker/datepicker.inc.php';

?>
<!DOCTYPE html>
<html lang="<?php echo  $mxla;?>" dir="<?php echo  $textdir;?>"><head>
    <title>MODX</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_manager_charset; ?>" />
    <link rel="stylesheet" type="text/css" href="media/style/<?php echo $modx->config['manager_theme']; ?>/style.css" />
    <link rel="stylesheet" href="media/style/common/font-awesome/css/font-awesome.min.css" />
    <?php echo sprintf('<script src="%s" type="text/javascript"></script>'."\n", $modx->config['mgr_jquery_path']); ?>
    <script src="media/script/mootools/mootools.js" type="text/javascript"></script>
    <script src="media/script/mootools/moodx.js" type="text/javascript"></script>
    <!-- OnManagerMainFrameHeaderHTMLBlock -->
    <?php echo $onManagerMainFrameHeaderHTMLBlock . "\n"; ?>
    <script type="text/javascript">
		/* <![CDATA[ */
	
        jQuery(function(){
        jQuery(window).on('beforeunload',function(){
        	if(documentDirty) {stopWorker();return '<?php echo addslashes($_lang['warning_not_saved']);?>';}
                jQuery('#actions').fadeOut(100);
            	jQuery('input,textarea,select').addClass('readonly');
            });
        });
        
        window.addEvent('load', document_onload);
        window.addEvent('beforeunload', document_onunload);
        
        function document_onload() {
            stopWorker();
            hideLoader();
<?php
	if(isset($_REQUEST['r']) && preg_match('@^[0-9]+$@',$_REQUEST['r']))
	    echo 'doRefresh(' . $_REQUEST['r'] . ");\n";
?>
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
        if (parent.tree) parent.tree.ca = "open";

		// call the updateMail function, updates mail notification in top navigation
		if (top.mainMenu) {
			if(top.mainMenu.updateMail) {
				top.mainMenu.updateMail(true);
			}
		}
		
        function stopWorker() {
            try {
                parent.mainMenu.stopWork();
            } catch(oException) {
                ww = window.setTimeout('stopWorker()',500);
            }
        }

        function doRefresh(r) {
            try {
                rr = r;
                top.mainMenu.startrefresh(rr);
            } catch(oException) {
                vv = window.setTimeout('doRefresh()',1000);
            }
        }
        var documentDirty=false;

        function checkDirt(evt) {
            if(documentDirty==true) {
				var message = "<?php echo $_lang['warning_not_saved']; ?>";
				if (typeof evt == 'undefined') {
					evt = window.event;
            }
				if (evt) {
					evt.returnValue = message;
        }
				return message;
  			}
        }

        function saveWait(fName) {
            document.getElementById("savingMessage").innerHTML = "<?php echo $_lang['saving']; ?>";
            for(i = 0; i < document.forms[fName].elements.length; i++) {
                document.forms[fName].elements[i].disabled='disabled';
            }
        }

        var managerPath = "";

        function hideLoader() {
            document.getElementById('preLoader').style.display = "none";
        }

        hideL = window.setTimeout("hideLoader()", 1500);

        // add the 'unsaved changes' warning event handler
        if( window.addEventListener ) {
			window.addEventListener('beforeunload',checkDirt,false);
		} else if ( window.attachEvent ) {
			window.attachEvent('onbeforeunload',checkDirt);
		} else {
			window.onbeforeunload = checkDirt;
		}
		/* ]]> */
    </script>
</head>
<body ondragstart="return false"<?php echo $modx_textdir ? ' class="rtl"':''?>>

<div id="preLoader"><table width="100%" border="0" cellpadding="0"><tr><td align="center"><div class="preLoaderText"><?php echo $_style['ajax_loader']; ?></div></td></tr></table></div>
