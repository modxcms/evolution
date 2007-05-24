<form name="install" action="index.php?action=mode" method="post">
	<div>
		<input type="hidden" value="<?php echo $install_language?>" name="language" />
	</div>
	<div style="padding-right:10px;">
	<?php echo $_lang['license']?>
	</div>
	<br />
	<div id="navbar">
		<input type="submit" value="<?php echo $_lang['btnnext_value']?>" name="cmdnext" style="float:right;width:100px;" />
		<span style="float:right">&nbsp;</span>
		<input type="submit" value="<?php echo $_lang['btnback_value']?>" name="cmdback" style="float:right;width:100px;" onclick="this.form.action='index.php?action=welcome';this.form.submit();return false;" />
		<input type="checkbox" value="1" id="chkagree" name="chkagree" style="line-height:18px" <?php echo isset($_POST['chkagree']) ? 'checked="checked" ':""; ?>/><label for="chkagree" style="display:inline;float:none;line-height:18px;"> <?php echo $_lang['iagree_box']?> </label></span>
	</div>
</form>
	