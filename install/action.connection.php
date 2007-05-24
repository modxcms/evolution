<?php
$installMode = intval($_POST['installmode']);

$test= (isset ($_POST['cmdtest']) && $_POST['cmdtest']);
if ($test) {
    // Test db connection
    $color = '';
    $uid = $_POST["databaseloginname"];
    $pwd = $_POST["databaseloginpassword"];
    $host = $_POST["databasehost"];
    $db2test = $_POST["database_name"];
    $tbl_prefix = $_POST["tableprefix"];
    // connect to the database
    $status = $_lang['status_connecting'];
    if (!$conn = @ mysql_connect($host, $uid, $pwd)) {
        $status .= $_lang['status_failed'];
        $color = '#ff0000';
    } else {
        $status .= $_lang['status_passed'];
        // select database
        $status .= $_lang['status_checking_database'];
        if (!@ mysql_select_db(str_replace("`", "", $db2test), $conn)) {
            $status .= $_lang['status_failed_could_not_select_database']." $db2test!";
            $color = '#ff0000';
        }
        elseif ($installMode < 1 && $rs = @ mysql_query("SELECT COUNT(*) FROM {$db2test}.`{$tbl_prefix}site_content`")) {
            $status .= $_lang['status_failed_table_prefix_already_in_use'];
            $color = '#ff0000';
        } else {
            $status .= $_lang['status_passed'];
            $color = '#007700';
        }
    }
} // end - Test db connecttion

// Determine upgradeability
$upgradeable= 0;
if ($installMode > 0) {
	if (file_exists("../manager/includes/config.inc.php")) {
	    // Include the file so we can test its validity
	    include "../manager/includes/config.inc.php";
	    // We need to have all connection settings - but prefix may be empty so we have to ignore it
	    if ($dbase) {
	        if (!@ $conn = mysql_connect($database_server, $database_user, $database_password)) {
	            $upgradeable = isset ($_POST['installmode']) && $_POST['installmode'] == 'new' ? 0 : 2;
	        }
	        elseif (!@ mysql_select_db(trim($dbase, '`'), $conn)) {
	            $upgradeable = isset ($_POST['installmode']) && $_POST['installmode'] == 'new' ? 0 : 2;
	        } else {
	            $upgradeable = 1;
	        }
	        $database_name= trim($dbase, '`');
	    } else {
	        $upgradable= 2;
	    }
	}
} else {
    $database_name= 'modx';
    $database_server= 'localhost';
    $table_prefix= 'modx_';
}

// check the database collation if not specified in the configuration
if ($upgradeable && (!isset ($database_connection_charset) || empty($database_connection_charset))) {
    if (!$rs = @ mysql_query("show session variables like 'collation_database'")) {
        $rs = @ mysql_query("show session variables like 'collation_server'");
    }
    if ($rs && $collation = mysql_fetch_row($rs)) {
        $database_collation = $collation[1];
    }
    if (empty ($database_collation)) {
        $database_collation = 'utf8_general_ci';
    }
    $database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
    $database_connection_charset = $database_charset;
} else {
    $database_collation = 'utf8_general_ci';
}

?>

<form name="install" action="index.php?action=options" method="post">
	<div>
		<input type="hidden" value="<?php echo $install_language?>" name="language" />
		<input type="hidden" value="1" name="chkagree" <?php echo isset($_POST['chkagree']) ? 'checked="checked" ':""; ?>/>
		<input type="hidden" value="<?php echo $installMode ?>" name="installmode" />
	</div>
	<p class="title"><?php echo $_lang['connection_screen_connection_information']?></p>
	<p><?php echo $_lang['connection_screen_connection_and_login_information']?></p>
	<p><?php echo $_lang['connection_screen_connection_note']?></p>
	<div class="labelHolder"><label for="database_name"><?php echo $_lang['connection_screen_database_name']?></label>
		<input id="database_name" value="<?php echo isset($_POST['database_name']) ? $_POST['database_name']:$database_name ?>" name="database_name" />
	</div>
	<div class="labelHolder"><label for="tableprefix"><?php echo $_lang['connection_screen_table_prefix']?></label>
		<input id="tableprefix" value="<?php echo isset($_POST['tableprefix']) ? $_POST['tableprefix']:$table_prefix ?>" name="tableprefix" />
	</div>
<?php if ($installMode == 0) { ?>
	<div class="labelHolder"><label for="database_collation"><?php echo $_lang['connection_screen_collation']?></label>
		<input id="database_collation" value="<?php echo isset($_POST['database_collation']) ? $_POST['database_collation']:$database_collation ?>" name="database_collation" />
	</div>
<?php } else { ?>
	<div class="labelHolder"><label for="database_connection_charset"><?php echo $_lang['connection_screen_character_set']?></label> 
		<input id="database_connection_charset" value="<?php echo isset($_POST['database_connection_charset']) ? $_POST['database_connection_charset']:$database_connection_charset ?>" name="database_connection_charset" />
	</div>
<?php } ?>
	<br />
	<p><?php echo $_lang['connection_screen_database_info']?></p>
	<br />
	<div class="labelHolder"><label for="databasehost"><?php echo $_lang['connection_screen_database_host']?></label>
		<input id="databasehost" value="<?php echo isset($_POST['databasehost']) ? $_POST['databasehost']:$database_server ?>" name="databasehost" />
	</div>
	<div class="labelHolder"><label for="databaseloginname"><?php echo $_lang['connection_screen_database_login']?></label>
		<input id="databaseloginname" name="databaseloginname" value="<?php echo isset($_POST['databaseloginname']) ? $_POST['databaseloginname']:"" ?>" />
	</div>
	<div class="labelHolder"><label for="databaseloginpassword"><?php echo $_lang['connection_screen_database_pass']?></label>
		<input id="databaseloginpassword" type="password" name="databaseloginpassword" value="<?php echo isset($_POST['databaseloginpassword']) ? $_POST['databaseloginpassword']:"" ?>" />&nbsp;
		<input type="button" name="cmdtestbtn" value="<?php echo $_lang['connection_screen_test_connection']?>" style="width:130px" onclick="this.form.cmdtest.value=1;this.form.action='index.php?action=connection';this.form.submit();return false;" />
		<input type="hidden" name="cmdtest" value="0" />
	</div>
	<div>
		<p style="color:<?php echo $color ?>;"><?php echo $status ?></p>
	</div>
<?php
	if ($installMode == 0) {
?>
	<div id="AUH">
		<p class="title"><?php echo $_lang['connection_screen_default_admin_user']?></p>
		<p><?php echo $_lang['connection_screen_default_admin_note']?></p>
		<div class="labelHolder"><label for="cmsadmin"><?php echo $_lang['connection_screen_default_admin_login']?></label>
			<input id="cmsadmin" value="<?php echo isset($_POST['cmsadmin']) ? $_POST['cmsadmin']:"admin" ?>" name="cmsadmin" />
		</div>
		<div class="labelHolder"><label for="cmsadminemail"><?php echo $_lang['connection_screen_default_admin_email']?></label>
			<input id="cmsadminemail" value="<?php echo isset($_POST['cmsadminemail']) ? $_POST['cmsadminemail']:"" ?>" name="cmsadminemail" />
		</div>
		<div class="labelHolder"><label for="cmspassword"><?php echo $_lang['connection_screen_default_admin_password']?></label>
			<input id="cmspassword" type="password"	name="cmspassword" value="<?php echo isset($_POST['cmspassword']) ? $_POST['cmspassword']:"" ?>" />
		</div>
		<div class="labelHolder"><label for="cmspasswordconfirm"><?php echo $_lang['connection_screen_default_admin_password_confirm']?></label>
			<input id="cmspasswordconfirm" type="password" name="cmspasswordconfirm" value="<?php echo isset($_POST['cmspasswordconfirm']) ? $_POST['cmspasswordconfirm']:"" ?>" />
		</div>
	</div>
	
	<br />
<?php
	}
?>

	<br />
	
	<div id="navbar">
		<input type="submit" value="<?php echo $_lang['btnnext_value']?>" name="cmdnext" style="float:right;width:100px;" onclick="if (validate()) this.form.submit(); return false;" />
		<span style="float:right">&nbsp;</span>
		<input type="submit" value="<?php echo $_lang['btnback_value']?>" name="cmdback" style="float:right;width:100px;" onclick="this.form.action='index.php?action=mode';this.form.submit();return false;" />
	</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
	// validate
	function validate() {
		var f = document.install;
		if(f.database_name.value=="") {
			alert('<?php echo $_lang['alert_enter_database_name']?>');
			f.database_name.focus();
			return false;
		}
		var alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if(alpha.indexOf(f.tableprefix.value.charAt(0),0) == -1) {
			alert('<?php echo $_lang['alert_table_prefixes']?>');
			f.tableprefix.focus();
			return false;
		}
		if(f.databasehost.value=="") {
			alert('<?php echo $_lang['alert_enter_host']?>');
			f.databasehost.focus();
			return false;
		}
		if(f.databaseloginname.value=="") {
			alert('<?php echo $_lang['alert_enter_login']?>');
			f.databaseloginname.focus();
			return false;
		}
		if(f.cmsadmin.value=="") {
			alert('<?php echo $_lang['alert_enter_adminlogin']?>');
			f.cmsadmin.focus();
			return false;
		}
		if(f.cmspassword.value=="") {
			alert('<?php echo $_lang['alert_enter_adminpassword']?>');
			f.cmspassword.focus();
			return false;
		}
		if(f.cmspassword.value!=f.cmspasswordconfirm.value) {
			alert('<?php echo $_lang['alert_enter_adminconfirm']?>');
			f.cmspassword.focus();
			return false;
		}
		return true;
	}
	/* ]]> */
</script>

