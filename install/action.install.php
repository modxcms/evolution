<?php
ob_start();
include "instprocessor.php";
$content = ob_get_contents();
ob_end_clean();
echo $content;

?>
<form name="install" action="index.php?action=options" method="post">
	<div id="navbar">
<?php
if ($errors == 0) {
    // check if install folder is removeable
    if (is_writable("../install")) { ?>
<span id="removeinstall" style="float:left;cursor:pointer;color:#505050;line-height:18px;" onclick="var chk=document.install.rminstaller; if(chk) chk.checked=!chk.checked;"><input type="checkbox" name="rminstaller" onclick="event.cancelBubble=true;" <?php echo (empty ($errors) ? 'checked="checked"' : '') ?> style="cursor:default;" />Remove the install folder and files from my website <br />&nbsp;(This operation requires delete permission to the granted to the install folder). </span>
<?php 
    } else { 
?>
<span id="removeinstall" style="float:left;color:#505050;line-height:18px;">Please remember to remove the &quot;<b>install</b>&quot; folder before you log into the Content Manager.</span>
<?php
    }
}
?>
		<input type="submit" value="Close" name="cmdnext" style="float:right;width:100px;" onclick="closepage(); return false;" />
	</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
	function closepage(){
		var chk = document.install.rminstaller;
		if(chk && chk.checked) {
			// remove install folder and files
			window.location.href = "../manager/processors/remove_installer.processor.php?rminstall=1";
		}
		else {
			window.location.href = "../manager/";
		}
	}
/* ]]> */
</script>
