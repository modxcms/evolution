
    <form name="install" id="install_form" action="index.php?action=license" method="post">
        <input type="hidden" value="<?php echo $install_language?>" name="language" />

<?php
	echo '	<h2>' . $_lang['welcome_message_welcome'] . '</h2>';
	echo '	<p>' . $_lang['welcome_message_text'] . '</p>';
	echo '	<p>' . $_lang['welcome_message_select_begin_button'] . '</p>';
?>

        <p class="buttonlinks">
            <a href="javascript:document.getElementById('install_form').action='index.php?action=language';document.getElementById('install_form').submit();" class="prev" title="<?php echo $_lang['btnback_value']?>"><span><?php echo $_lang['btnback_value']?></span></a>
            <a href="javascript:document.getElementById('install_form').submit();" title="<?php echo $_lang['begin']?>"><span><?php echo $_lang['begin']?></span></a>
        </p>

    </form>
