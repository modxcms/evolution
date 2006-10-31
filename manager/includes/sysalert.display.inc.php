<?php

	/**
	 *	System Alert Message Queque Display file
	 *	Written By Raymond Irving, April, 2005
	 *
	 *	Used to display system alert messages inside the browser 
	 *
	 */
	
	$sysMsgs = "";
	$limit = count($SystemAlertMsgQueque);
	for($i=0;$i<$limit;$i++) {
		$sysMsgs .= $SystemAlertMsgQueque[$i]."<hr>";
	}
	// reset message queque
	unset($_SESSION['SystemAlertMsgQueque']);
	$_SESSION['SystemAlertMsgQueque'] = array();
	$SystemAlertMsgQueque = &$_SESSION['SystemAlertMsgQueque'];

	if($sysMsgs!="") {
?>	

<?php // fetch the styles
if (file_exists($modx->config['base_path'].'manager/media/style/'.$manager_theme.'/sysalert_style.php')) {
	include_once ($modx->config['base_path'].'manager/media/style/'.$manager_theme.'/sysalert_style.php');
	echo '<style type="text/css">';
	echo $sysalert_style;
	echo '</style>';
} 
?>

	<script type="text/javascript">
		if(!document.initWebElm) {
			var src = '<\script type="text/javascript" src="media/script/bin/webelm.js"><\/script>';
			document.write(src);
		}
	</script>
	<script type="text/javascript">
		var sysAlertWindow;
		document.setIncludePath("media/script/bin/");
		document.addEventListener('oninit',function(){ 
			document.include('dynelement');  
			document.include('floater');  
		}); 

				document.addEventListener('onload',function(){ 			
			var src = '<!--start scroller -->'
			+'<div class="evtMsgHeading"><div id="closeSysAlert"><a href="javascript:void(0);" onclick="closeSystemAlerts();return false;"><img border="0" src="media/style/<?php echo ($manager_theme ? "$manager_theme/":"") ?>images/icons/close.gif" width="16" height="16" alt="<?php echo $_lang['close'] ?>" /></a></div> <?php echo $_lang['sys_alert'] ?> </div><div>'
			+'<div id="up" class="scrollbtn" title="<?php echo $_lang['scroll_up'];?>" onmousedown="scrollDown()" onmouseup="scrollReset()" onmouseout="scrollReset()"><img src="media/style/<?php echo ($manager_theme ? "$manager_theme/":"") ?>images/icons/arrow_up.gif" width="5" height="6" alt="up" /></div>'
			+'<div id="navbar" class="evtMsgContainer">'
			+'<div id="navbarcontent" class="evtMsg">'
			+'<?php echo mysql_escape_string($sysMsgs);?>'
			+'</div>'
			+'</div>'
			+'<div id="dn" class="scrollbtn" title="<?php echo $_lang['scroll_dn'];?>" onmousedown="scrollup()" onmouseup="scrollReset()" onmouseout="scrollReset()"><img src="media/style/<?php echo ($manager_theme ? "$manager_theme/":"") ?>images/icons/arrow_dn.gif" width="5" height="6" alt="down" /></div>'
			+'</div>'
			+'<!-- end scroller -->';
			sysAlertWindow = new Floater('sysAlertWindow',src,(document.ua.ie ? 10:25),10,"top-right");

		});

		function closeSystemAlerts() {
			if(sysAlertWindow)
				sysAlertWindow.style.display = "none";
		};
		
		// Scroller functions
		var timer = 0,speed = 0;
		function scrollup(){
			var navbar = document.getElementById ? document.getElementById("navbar"):document.all["navbar"];
			var navbarc = document.getElementById ? document.getElementById("navbarcontent"):document.all["navbarcontent"];
			var navbarheight= parseInt(navbar.style.height);
			var navbaractualheight= parseInt(navbarc.offsetHeight);
			var nctop = parseInt(navbarc.style.top||0);
			speed = (timer) ? speed+1:4;
			if (nctop>(navbarheight-navbaractualheight)) navbarc.style.top=(nctop-speed)+"px";
			timer=setTimeout("scrollup()",60)
		}
		function scrollDown(){
			var navbar = document.getElementById ? document.getElementById("navbar"):document.all["navbar"];
			var navbarc = document.getElementById ? document.getElementById("navbarcontent"):document.all["navbarcontent"];
			var navbarheight= parseInt(navbar.style.height);
			var navbaractualheight= parseInt(navbarc.offsetHeight);
			var nctop = parseInt(navbarc.style.top||0);
			speed = (timer) ? speed+1:4;
			if (nctop<0) navbarc.style.top=(nctop+speed)+"px"
			timer=setTimeout("scrollDown()",60)
		};
		function scrollReset(){
			clearTimeout(timer);
			timer = 0;
		};
	</script>
	<script>Floater.Render("sysAlertWindow",220,240,'systemAlert');</script>

<?php
	}
?>