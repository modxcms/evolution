<?php
if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('change_password')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}
?>

<script type="text/javascript">
	var actions = {
		save: function() {
			documentDirty = false;
			document.userform.save.click();
		},
		cancel: function() {
			documentDirty = false;
			document.location.href = 'index.php?a=2';
		}
	}
</script>

<h1><?php echo $_lang['change_password'] ?></h1>

<?php echo $_style['actionbuttons']['dynamic']['save'] ?>

<div class="section">
	<div class="sectionHeader"><?php echo $_lang['change_password'] ?></div>
	<div class="sectionBody">
		<form action="index.php?a=34" method="post" name="userform">
			<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
			<p><?php echo $_lang['change_password_message'] ?></p>
			<table border="0" cellspacing="0" cellpadding="4">
				<tr>
					<td><?php echo $_lang['change_password_new'] ?>:</td>
					<td>&nbsp;</td>
					<td><input type="password" name="pass1" class="inputBox" style="width:150px" value=""></td>
				</tr>
				<tr>
					<td><?php echo $_lang['change_password_confirm'] ?>:</td>
					<td>&nbsp;</td>
					<td><input type="password" name="pass2" class="inputBox" style="width:150px" value=""></td>
				</tr>
			</table>
			<input type="submit" name="save" style="display:none">
		</form>
	</div>
</div>
