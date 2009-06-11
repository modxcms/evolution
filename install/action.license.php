<form name="install" id="install_form" action="index.php?action=mode" method="post">
    <input type="hidden" value="<?php echo $install_language?>" name="language" />
    <?php echo $_lang['license']?>

        <input type="checkbox" value="1" id="chkagree" name="chkagree" style="line-height:18px" <?php echo isset($_POST['chkagree']) ? 'checked="checked" ':""; ?>/><label for="chkagree" style="display:inline;float:none;line-height:18px;"> <?php echo $_lang['iagree_box']?> </label>
    <p class="buttonlinks">
        <a href="javascript:document.getElementById('install_form').action='index.php?action=welcome';document.getElementById('install_form').submit();" class="prev" title="<?php echo $_lang['btnback_value']?>"><span><?php echo $_lang['btnback_value']?></span></a>
        <a style="display:inline;" href="javascript:document.getElementById('install_form').submit();" title="<?php echo $_lang['btnnext_value']?>"><span><?php echo $_lang['btnnext_value']?></span></a>
    </p>
</form>
<br />
<p>&nbsp;</p>
	