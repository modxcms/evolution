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
	<style type="text/css">
	.evtMsg {
		top:-400px;
		left:-400px;
		font-family:Verdana, Arial;
		font-size: 12px;
		border: 2px solid #4791C5;
		background-color: #FFFFE9;
		z-index:1000}
	.scrollbtn {
		width:					100%;
		height:					10px;
		font-size: 				5px;
		text-align:				center;
		background-image: 		url("media/images/misc/buttonbar.gif");
		background-color:		#A3C8F5;
		border:1px solid 		#9ABBE5;}
	</style>		
	<script type="text/javascript" language="JavaScript">
		if(!document.initWebElm) {
			var src = '<\script type="text/javascript" language="JavaScript" src="media/script/bin/webelm.js"><\/script>';
			document.write(src);
		}
	</script>
	<script type="text/javascript" language="JavaScript">
		var sysAlertWindow;
		document.setIncludePath("media/script/bin/");
		document.addEventListener('oninit',function(){ 
			document.include('dynelement');  
			document.include('floater');  
		}); 

		document.addEventListener('onload',function(){ 			
			var src = '<!--start scroller -->'
			+'<div style="color:white;background-color:#9ABBE5;padding:3px;font-weight:bold;"><div id="closeSysAlert" style="float:right"><a href="javascript:void(0);" onclick="closeSystemAlerts();return false;"><img border="0" src="media/images/icons/close.gif" width="16" height="16" alt="<?php echo $_lang['close'] ?>" /></a></div> <?php echo $_lang['sys_alert'] ?> </div><div style="position:relative;width:100%;">'
			+'<div id="up" class="scrollbtn" style="position:relative;" title="<?php echo $_lang['scroll_up'];?>" onmousedown="scrollDown()" onmouseup="scrollReset()" onmouseout="scrollReset()"><img src="media/images/icons/arrow_up.gif" width="5" height="6" alt="up" /></div>'
			+'<div id="navbar" style="position:relative;overflow:hidden;height:200px">'
			+'<div id="navbarcontent" style="position:relative;padding:3px;">'
			+'<?php echo mysql_escape_string($sysMsgs);?>'
			+'</div>'
			+'</div>'
			+'<div id="dn" class="scrollbtn" style="position:relative;" title="<?php echo $_lang['scroll_dn'];?>" onmousedown="scrollup()" onmouseup="scrollReset()" onmouseout="scrollReset()"><img src="media/images/icons/arrow_dn.gif" width="5" height="6" alt="down" /></div>'
			+'</div>'
			+'<!-- end scroller -->';
			sysAlertWindow = new Floater('sysAlertWindow',src,(document.ua.ie ? 10:25),10,"top-right");

		});

		function closeSystemAlerts() {
			if(sysAlertWindow)
				sysAlertWindow.style.visibility = "hidden";
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
	<script>Floater.Render("sysAlertWindow",220,240,'evtMsg');</script>

<?php
	}
?>