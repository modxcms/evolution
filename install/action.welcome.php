<form name="install" action="index.php?action=license" method="post">
	<div>
		<input type="hidden" value="<?php echo $install_language?>" name="language" />
	</div>
	<table width="100%">
	<tr>
	<td valign="top">
<?php
	echo '	<p class="title">' . $_lang['welcome_message_welcome'] . '</p>';
	echo '	<p>' . $_lang['welcome_message_text'] . '</p>';
	echo '	<p>' . $_lang['welcome_message_select_begin_button'] . '</p>';
?>
		<br />
		<center><img src="<?php echo include_image('img_splash.gif')?>" alt="<? echo $_lang['modx_install']?>" /></center>
	</td>
	<td align="center" width="280">
		<img src="<?php echo include_image('img_box.png')?>" alt="MODx Create and Do More with Less" />&nbsp;
	</td>
	</tr>
	</table>
	<div id="navbar">
		<input type="submit" value="<?php echo $_lang['Begin']?>" name="cmdnext" style="float:right;width:100px;" />
		<span style="float:right">&nbsp;</span>
		<input type="submit" value="<?php echo $_lang['btnback_value']?>" name="cmdback" style="float:right;width:100px;" onclick="this.form.action='index.php?action=language';this.form.submit();return false;" />
	</div>
</form>