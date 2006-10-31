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

.systemAlert {
	width: 95%;
	border: 1px solid #264f17;
	background-color: #FFFFE9;
	z-index:1000;
	font-family: verdana, arial;
	font-size: 12px;
}
#closeSysAlert {
	float: right;
}
.evtMsgHeading {
	color:#fff;
	background: #9EBD5A;
	padding:3px;
	font-weight:bold;
}
.evtMsgContainer {
	position:relative;
	overflow:hidden;
	height:200px;
}
.evtMsg {
	background-color: #FFFFE9;
	font-size: 12px;
	padding:3px;
	width: 95%; /* force IE to display the scrollbars */
	/* Don't wrap its contents, and show scrollbars. */
	white-space: nowrap;
	overflow: auto;
	height: 190px;
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