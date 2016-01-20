<h2><?php 
if(IN_MANAGER_MODE!='true' && !$modx->hasPermission('exec_module')) die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');

echo $_lang['install_results']?></h2>
<?php


//ob_start();
include "instprocessor.php";
//$content = ob_get_contents();
//ob_end_clean();
//echo $content;

?>
<form name="install" id="install_form" action="index.php?action=options" method="post">
<?php
if ($errors == 0) {
	// check if install folder is removeable
    if (is_writable("../install")) { ?>
<span id="removeinstall" style="float:left;cursor:pointer;color:#505050;line-height:18px;" onclick="var chk=document.install.rminstaller; if(chk) chk.checked=!chk.checked;"><input type="checkbox" name="rminstaller" onclick="event.cancelBubble=true;" <?php echo (empty ($errors) ? 'checked="checked"' : '') ?> style="cursor:default;" /><?php echo $_lang['remove_install_folder_auto'] ?></span>
<?php 
    } else {
?>

<?php
    }
}
?>
    <p class="buttonlinks">
        <a href="javascript:parent.jQuery.fancybox.close();" title="<?php echo $_lang['btnclose_value']?>"><span><?php echo $_lang['btnclose_value']?></span></a>
    </p>
	<br />
</form>
<br />
<script type="text/javascript">
/* <![CDATA[ */
function closepage(){
	var chk = document.install.rminstaller;
	if(chk && chk.checked) {
		// remove install folder and files
		window.location.href = "../<?php echo MGR_DIR;?>/processors/remove_installer.processor.php?rminstall=1";
	}
	else {
		window.location.href = "index.php?a=2";
	}
}
/* ]]> */
</script>
