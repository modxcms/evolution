<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo $modx->config['manager_direction'] == 'rtl' ? 'dir="rtl"' : '';?> lang="<?php echo $modx->config['manager_lang_attribute'];?>" xml:lang="<?php echo $modx->config['manager_lang_attribute'];?>">
<head>
    <title>MODx</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_charset; ?>" />
    <link rel="stylesheet" type="text/css" href="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>style.css" />

    <script src="media/script/mootools/mootools.js" type="text/javascript"></script>
    <script src="media/script/mootools/moodx.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript">
        window.addEvent('load', document_onload);
        window.addEvent('beforeunload', document_onunload);
        
        function document_onload() {
            stopWorker();
            hideLoader();
            <?php echo isset($_REQUEST['r']) ? " doRefresh(".$_REQUEST['r'].");" : "" ;?>;
        };

        var dontShowWorker = false;
        function document_onunload() {
            if(!dontShowWorker) {
                top.mainMenu.work();
            }
        };

        // set tree to default action.
        parent.tree.ca = "open";

		// call the updateMail function, updates mail notification in top navigation
		top.mainMenu.updateMail(true);
		
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

    </script>
</head>
<body ondragstart="return false">

<div id="preLoader"><table width="100%" border="0" cellpadding="0"><tr><td align="center"><div class="preLoaderText"><?php echo $_lang['loading_page']; ?></div></td></tr></table></div>
