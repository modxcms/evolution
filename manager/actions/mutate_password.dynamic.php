<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
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

<h1><?php echo $_style['page_change_password']; echo $_lang['change_password'] ?></h1>

<?php echo $_style['actionbuttons']['dynamic']['save'] ?>

<div class="tab-page">
	<div class="contaier container-body">
		<form action="index.php?a=34" method="post" name="userform">
			<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
			<p><?php echo $_lang['change_password_message'] ?></p>
			<div class="row form-row">
				<div class="col-sm-3 col-md-2"><?php echo $_lang['change_password_new'] ?>:</div>
				<div class="col-sm-4 col-md-3"><input type="password" name="pass1" class="form-control" value="" /></div>
			</div>
			<div class="row form-row">
				<div class="col-sm-3 col-md-2"><?php echo $_lang['change_password_confirm'] ?>:</div>
				<div class="col-sm-4 col-md-3"><input type="password" name="pass2" class="form-control" value="" /></div>
			</div>
			<input type="submit" name="save" style="display:none">
		</form>
	</div>
</div>
