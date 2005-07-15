<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>
<div class="subTitle">
<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['credits']; ?></span>
</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['credits']; ?></div><div class="sectionBody">
<table width="500"  border="0" cellspacing="0" cellpadding="0">
  <tr height="70">
    <td width="120" align="center"><a href="http://www.interactivetools.com/products/htmlarea/" target="_blank"><img src="media/images/credits/area.gif" border="0"></a></td>
    <td align="left"><?php echo $_lang['credits_htmlarea']; ?></td>
  </tr>
  <tr height="70">
    <td align="center"><a href="http://www.php.net" target="_blank"><img src="media/images/credits/php.gif" border="0"></a></td>
    <td align="left"><?php echo $_lang['credits_php']; ?></td>
  </tr>
  <tr height="70">
    <td align="center"><a href="http://www.mysql.com" target="_blank"><img src="media/images/credits/mysql.gif" border="0"></a></td>
    <td align="left"><?php echo $_lang['credits_mysql']; ?></td>
  </tr>
  <tr height="70">
    <td align="center"><a href="http://webfx.eae.net" target="_blank"><img src="media/images/credits/webfx.gif" border="0"></a></td>
    <td align="left"><?php echo $_lang['credits_webfx']; ?></td>
  </tr>
  <tr height="70">
    <td align="center"><a href="http://www.destroydrop.com" target="_blank"><img src="media/images/credits/dtree.gif" border="0"></a></td>
    <td align="left"><?php echo $_lang['credits_dTree']; ?></td>
  </tr>     
  <tr height="70">
    <td align="center"><a href="http://www.everaldo.com" target="_blank"><img src="media/images/credits/penguin.gif" border="0"></a></td>
    <td align="left"><?php echo $_lang['credits_everaldo']; ?></td>
  </tr>     
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top"><?php echo $_lang['credits_shouts_title']; ?></td>
	<td align="left"><?php echo $_lang['credits_shouts_msg']; ?></td>
  </tr>
</table>
</div>