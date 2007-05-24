<?php
// Determine upgradeability
$upgradeable = 0;
if (file_exists("../manager/includes/config.inc.php")) {
    // Include the file so we can test its validity
    include "../manager/includes/config.inc.php";
    // We need to have all connection settings - tho prefix may be empty so we have to ignore it
    if ($dbase) {
        if (!@ $conn = mysql_connect($database_server, $database_user, $database_password)) {
            $upgradeable = isset ($_POST['installmode']) && $_POST['installmode'] == 'new' ? 0 : 2;
        }
        elseif (!@ mysql_select_db(trim($dbase, '`'), $conn)) {
            $upgradeable = isset ($_POST['installmode']) && $_POST['installmode'] == 'new' ? 0 : 2;
        } else {
            $upgradeable = 1;
        }
    } else {
        $upgradeable= 2;
    }
}
?>
<form name="install" action="index.php?action=connection" method="post">
	<div>
		<input type="hidden" value="<?php echo $install_language?>" name="language" />
		<input type="hidden" value="1" id="chkagree" name="chkagree" <?php echo isset($_POST['chkagree']) ? 'checked="checked" ':""; ?>/>
	</div>
	<p class="title"><?php echo $_lang['installation_mode']?></p>
	<table border="0" width="100%">
	  <tr>
		<td nowrap valign="top" width="37%">
		<img src="im_new_inst.gif" align="left" width="32" height="32" hspace="5" />
		<input type="radio" name="installmode" id="installmode1" value="0" <?php echo !$upgradeable ? 'checked="checked"':'' ?> />
		<label for="installmode1" class="nofloat"><?php echo $_lang['installation_new_installation']?></label></td>
		<td width="61%"><?php echo $_lang['installation_install_new_copy'] . $moduleName?>
		<strong><?php echo $_lang['installation_install_new_note']?></strong></td>
	  </tr>
      <tr>
    	<td nowrap valign="top" width="37%">&nbsp;</td>
    	<td width="61%">&nbsp;</td>
      </tr>
	  <tr>
		<td nowrap valign="top" width="37%">
		<img src="im_inst_upgrade.gif" align="left" width="32" height="32" hspace="5" />
		<input type="radio" name="installmode" id="installmode2" value="1" <?php echo $upgradeable !== 1 ? 'disabled="disabled"' : '' ?> <?php echo ($_POST['installmode']=='1' || $upgradeable === 1) ? 'checked="checked"':'' ?> />
		<label for="installmode2" class="nofloat"><?php echo $_lang['installation_upgrade_existing']?></label></td>
		<td width="61%"><?php echo $_lang['installation_upgrade_existing_note']?></td>
	  </tr>
	  <tr>
		<td nowrap valign="top" width="37%">&nbsp;</td>
		<td width="61%">&nbsp;</td>
	  </tr>
	  <tr>
		<td nowrap valign="top" width="37%">
		<img src="im_inst_upgrade.gif" align="left" width="32" height="32" hspace="5" />
		<input type="radio" name="installmode" id="installmode3" value="2" <?php echo !$upgradeable ? 'disabled="disabled"':'' ?> <?php echo ($_POST['installmode']=='2' || $upgradeable === 2) ? 'checked="checked"':'' ?> />
		<label for="installmode3" class="nofloat"><?php echo $_lang['installation_upgrade_advanced']?></label></td>
		<td width="61%"><?php echo $_lang['installation_upgrade_advanced_note']?></td>
	  </tr>
	</table>
	<br />
	<div id="navbar">
		<input type="submit" value="<?php echo $_lang['btnnext_value']?>" name="cmdnext" style="float:right;width:100px;" onclick="if (this.form.installmode[1].checked) this.form.action='index.php?action=options'; this.form.submit();return false;" />
		<span style="float:right">&nbsp;</span>
		<input type="submit" value="<?php echo $_lang['btnback_value']?>" name="cmdback" style="float:right;width:100px;" onclick="this.form.action='index.php?action=license';this.form.submit();return false;" />
	</div>
</form>