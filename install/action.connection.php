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
    $status = ' Connection to host: ';
    if (!$conn = @ mysql_connect($host, $uid, $pwd)) {
        $status .= "failed!";
        $color = '#ff0000';
    } else {
        $status .= 'passed';
        // select database
        $status .= '...    Checking database: ';
        if (!@ mysql_select_db(str_replace("`", "", $db2test), $conn)) {
            $status .= "failed - could not select database $db2test!";
            $color = '#ff0000';
        }
        elseif ($installMode < 1 && $rs = @ mysql_query("SELECT COUNT(*) FROM {$db2test}.`{$tbl_prefix}site_content`")) {
            $status .= "failed - table prefix already in use!";
            $color = '#ff0000';
        } else {
            $status .= 'passed';
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
		<input type="hidden" value="1" name="chkagree" <?php echo isset($_POST['chkagree']) ? 'checked="checked" ':""; ?>/>
		<input type="hidden" value="<?php echo $installMode ?>" name="installmode" />
	</div>
	<p class="title">Connection Information</p>
	<p>Database connection and login information</p>
	<p>Please enter the name of the database created for MODX. If you there
	is no database yet, the installer will attempt to create a database for
	you. This may fail depending on the MySQL configuration or the database
	user permissions for your domain/installation.</p>
	<div class="labelHolder"><label for="database_name">Database name:</label>
		<input id="database_name" value="<?php echo isset($_POST['database_name']) ? $_POST['database_name']:$database_name ?>" name="database_name" />
	</div>
	<div class="labelHolder"><label for="tableprefix">Table prefix:</label>
		<input id="tableprefix" value="<?php echo isset($_POST['tableprefix']) ? $_POST['tableprefix']:$table_prefix ?>" name="tableprefix" />
	</div>
<?php if ($installMode == 0) { ?>
	<div class="labelHolder"><label for="database_collation">Collation:</label>
		<input id="database_collation" value="<?php echo isset($_POST['database_collation']) ? $_POST['database_collation']:$database_collation ?>" name="database_collation" />
	</div>
<?php } else { ?>
	<div class="labelHolder"><label for="database_connection_charset">Connection character set:</label> 
		<input id="database_connection_charset" value="<?php echo isset($_POST['database_connection_charset']) ? $_POST['database_connection_charset']:$database_connection_charset ?>" name="database_connection_charset" />
	</div>
<?php } ?>
	<br />
	<p>Now please enter the login data for your database.</p>
	<br />
	<div class="labelHolder"><label for="databasehost">Database host:</label>
		<input id="databasehost" value="<?php echo isset($_POST['databasehost']) ? $_POST['databasehost']:$database_server ?>" name="databasehost" />
	</div>
	<div class="labelHolder"><label for="databaseloginname">Database login name:</label>
		<input id="databaseloginname" name="databaseloginname" value="<?php echo isset($_POST['databaseloginname']) ? $_POST['databaseloginname']:"" ?>" />
	</div>
	<div class="labelHolder"><label for="databaseloginpassword">Database password:</label> 
		<input id="databaseloginpassword" type="password" name="databaseloginpassword" value="<?php echo isset($_POST['databaseloginpassword']) ? $_POST['databaseloginpassword']:"" ?>" />&nbsp;
		<input type="button" name="cmdtestbtn" value="Test connection" style="width:130px" onclick="this.form.cmdtest.value=1;this.form.action='index.php?action=connection';this.form.submit();return false;" />
		<input type="hidden" name="cmdtest" value="0" />
	</div>
	<div>
		<p style="color:<?php echo $color ?>;"><?php echo $status ?></p>
	</div>
<?php
	if ($installMode == 0) {
?>
	<div id="AUH">
		<p class="title">Default Admin User</p>
		<p>Now you&#39;ll need to enter some details for the main administrator
		account. You can fill in your own name here, and a password you&#39;re
		not likely to forget. You&#39;ll need these to log into Admin once setup
		is complete.</p>
		<div class="labelHolder"><label for="cmsadmin">Administrator username:</label>
			<input id="cmsadmin" value="<?php echo isset($_POST['cmsadmin']) ? $_POST['cmsadmin']:"admin" ?>" name="cmsadmin" />
		</div>
		<div class="labelHolder"><label for="cmsadminemail">Administrator email:</label>
			<input id="cmsadminemail" value="<?php echo isset($_POST['cmsadminemail']) ? $_POST['cmsadminemail']:"" ?>" name="cmsadminemail" />
		</div>
		<div class="labelHolder"><label for="cmspassword">Administrator	password:</label>
			<input id="cmspassword" type="password"	name="cmspassword" value="<?php echo isset($_POST['cmspassword']) ? $_POST['cmspassword']:"" ?>" />
		</div>
		<div class="labelHolder"><label for="cmspasswordconfirm">Confirm password:</label>
			<input id="cmspasswordconfirm" type="password" name="cmspasswordconfirm" value="<?php echo isset($_POST['cmspasswordconfirm']) ? $_POST['cmspasswordconfirm']:"" ?>" />
		</div>
	</div>
	
	<br />
<?php
	}
?>

	<br />
	
	<div id="navbar">
		<input type="submit" value="Next" name="cmdnext" style="float:right;width:100px;" onclick="if (validate()) this.form.submit(); return false;" />
		<span style="float:right">&nbsp;</span>
		<input type="submit" value="Back" name="cmdback" style="float:right;width:100px;" onclick="this.form.action='index.php?action=mode';this.form.submit();return false;" />
	</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
	// validate
	function validate() {
		var f = document.install;
		if(f.database_name.value=="") {
			alert('You need to enter a value for database name!');
			f.database_name.focus();
			return false;
		}
           var alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
           if(alpha.indexOf(f.tableprefix.value.charAt(0),0) == -1) {
			alert('Table prefixes must start with a letter!');
			f.tableprefix.focus();
			return false;
		}
		if(f.databasehost.value=="") {
			alert('You need to enter a value for database host!');
			f.databasehost.focus();
			return false;
		}
		if(f.databaseloginname.value=="") {
			alert('You need to enter your database login name!');
			f.databaseloginname.focus();
			return false;
		}
		if(f.cmsadmin.value=="") {
			alert('You need to enter a username for the system admin account!');
			f.cmsadmin.focus();
			return false;
		}
		if(f.cmspassword.value=="") {
			alert('You need to a password for the system admin account!');
			f.cmspassword.focus();
			return false;
		}
		if(f.cmspassword.value!=f.cmspasswordconfirm.value) {
			alert('The administrator password and the confirmation don\'t match!');
			f.cmspassword.focus();
			return false;
		}
		return true;
	}
/* ]]> */
</script>