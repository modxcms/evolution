<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('change_password') && $_REQUEST['a']==28) {
	$e->setError(3);
	$e->dumpError();	
}
?>

<div class="subTitle">
<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['change_password']; ?></span>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td id="Button1" onclick="documentDirty=false; document.userform.save.click();"><img src="media/images/icons/save.gif" align="absmiddle"> <?php echo $_lang['save']; ?></td>
				<script>createButton(document.getElementById("Button1"));</script>
		</tr>
	</table>
</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['change_password']; ?></div><div class="sectionBody">
<form action="index.php?a=34" method="post" name="userform">
<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">

<?php echo $_lang['change_password_message']; ?> <p>

<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td><?php echo $_lang['change_password_new']; ?>:</td>
    <td>&nbsp;</td>
    <td><input type="password" name="pass1" class="inputBox" style="width:150px" value=""></td>
  </tr>
  <tr>
    <td><?php echo $_lang['change_password_confirm']; ?>:</td>
    <td>&nbsp;</td>
    <td><input type="password" name="pass2" class="inputBox" style="width:150px" value=""></td>
  </tr>
</table> 

<input type="submit" name="save" style="display:none">
</form>

</div>