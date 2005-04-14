<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
$enable_debug=false;?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Frame 12</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $etomite_charset; ?>">
<style>
body {
	margin : 0px 0px 0px 0px;
	background: #4791C5;
}

SPAN, TD {
	font-family:Verdana, Arial, Helvetica, sans-serif; 
	color: White;
}
TD {
	padding-top: 4px;
	font-size:11px;
}
SPAN {
	font-size:10px;
}
a, a:hover, a:visited, a:active {
	font-family:Verdana, Arial, Helvetica, sans-serif; 
	font-size:10px;
	color: White;
	text-decoration:none;
}
</style>
</head>
<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" style="height:20px;">
  <tr>
    <td width="10">&nbsp;</td>
    <td valign="middle">
		<span id=tocText></span>
		<span id=buildText>&nbsp;&nbsp;<img src='media/images/icons/b02.gif' align='absmiddle' width='16' height='16'>&nbsp;<b><?php echo $_lang['loading_doc_tree']; ?></b></span>
		<span id=workText>&nbsp;<img src='media/images/icons/delete.gif' align='absmiddle' width='16' height='16'>&nbsp;<b><?php echo $_lang['working']; ?></b></span>
	</td>
    <td>&nbsp;</td>
    <td align='right'><b><?php echo $site_name ;?></b> - <b><?php echo $full_appname; ?></b></td>
    <td width="20">&nbsp;</td>
  </tr>
</table>


</body>
</html>
