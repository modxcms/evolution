<?php
/**
* System Alert Box Styles
* 
* These styles are inserted inline with the system alert code,
* that is why they are in a separate file.
* 
*/
$sysalert_style =<<<EOD

/* System Alert Box
---------------------------------------------------------- */
#sysAlertWrapper {
	top: 20px;
    position: absolute;
    z-index: 50001;
    width: 100%;
    height: 100%;
    text-align: center;
    vertical-align: middle;
    padding: 10px;
}

#sysAlertWindow {
	width:90%;
	margin-right: auto;
	margin-left: auto;
	height:200px;
	border: 1px solid #264f17;
	background-color: #FFFFE9;
	font-family: verdana, arial;
	font-size: 12px;
	overflow:hidden;
    text-align: left;
}

#closeSysAlert {
	float: right;
}
.evtMsgHeading {
	color:#000;
	background: #bee860 url(media/style/{$manager_theme}/images/misc/greenfade.gif) repeat-x top;
	position:relative;
	padding:5px;
	font-weight:bold;
}

.evtMsg {
	background-color: #FFFFE9;
	font-size: 12px;
	padding:5px;
	width: 98%; /* force IE to display the scrollbars */
	/* Don't wrap its contents, and show scrollbars. */
	white-space: nowrap;
	overflow: auto;
	height: 160px;
}
.scrollbtn {
	width: 100%;
	height: 10px;
	font-size: 5px;
	text-align: center;
	background-image: url(images/bg/buttonbar.gif);
	background-color: #c5db88;
}

EOD;
?>