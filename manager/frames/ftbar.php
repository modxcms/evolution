<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>footbar</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
<style>
body {
	margin:							0px 0px 0px 0px;
	padding:						0px 0px 0px 0px;
	background-image: 				url("media/images/bg/body.jpg");
	background-color: 				#fff;}

.buttonbar {	
	cursor:							default;
	text-align:						right;
	font-size:						13px;
	color:							silver;
	font-family:					verdana,arial;
	height:							23px;
	padding-right:					10px;
	border-top:						1px solid #e0e0e0;
	border-bottom:					1px solid #e0e0e0;
	background-position: 			bottom;
	background-color:				#eeeeee;
	background-image: 				url("media/images/bg/toolbar.gif");
	background-repeat: 				repeat-x;}
	
.button {
	text-decoration:				none;
	color:							#707070;
	height:							30px;
	font-size:						10px; }

.button:hover {	
	text-decoration:				underline;
	color:							maroon; }
	
</style>
<script type="text/javascript">
	function goForward(){
		try {
			top.main.history.go(1);
		}catch(e) {
			alert(e.description ? e.description:e);
		}
	}

	function goBack(){
		try {
			top.main.history.go(-1);
		}catch(e) {
			alert(e.description ? e.description:e);
		}
	}
	
	function goReload(){
		try {
			top.main.location.reload();
		}catch(e) {
			alert(e.description ? e.description:e);
		}
	}


</script>
</head>
<body>
<div class="buttonbar">
	<a href="#" class="button" onclick="goBack();" title="<?php echo $_lang['back_title']; ?>"><?php echo $_lang['back']; ?></a> | 
	<a href="#" class="button" onclick="goForward();"><?php echo $_lang['forward']; ?></a> | 
	<a href="#" class="button" onclick="goReload()" title="<?php echo $_lang['reload_title']; ?>"><?php echo $_lang['reload']; ?></a>&nbsp;
	<img src="media/images/misc/logo_tbar.gif" align="absmiddle" />
</div>

</body>
</html>