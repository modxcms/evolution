<?php
$installMode = intval($_POST['installmode']);

echo "<p>Setup has carried out a number of checks to see if everything's ready to start the setup.</p>";
$errors = 0;
// check PHP version
echo "<p>Checking PHP version: ";
$php_ver_comp = version_compare(phpversion(), "4.1.0");
$php_ver_comp2 = version_compare(phpversion(), "4.3.8");
// -1 if left is less, 0 if equal, +1 if left is higher
if ($php_ver_comp < 0) {
    echo "<span class=\"notok\">Failed!</span> - You are running on PHP " . phpversion() . ", and ModX requires PHP 4.1.0 or later</p>";
    $errors += 1;
} else {
    echo "<span class=\"ok\">OK!</span></p>";
    if ($php_ver_comp2 < 0) {
        echo "<fieldset><legend>Security notice</legend><p>While MODx will work on your PHP version (" . phpversion() . "), usage of MODx on this version is not recommended. Your version of PHP is vulnerable to numerous security holes. Please upgrade to PHP version is 4.3.8 or higher, which patches these holes. It is recommended you upgrade to this version for the security of your own website.</p></fieldset>";
    }
}
// check sessions
echo "<p>Checking if sessions are properly configured: ";
if ($_SESSION['session_test'] != 1) {
    echo "<span class=\"notok\">Failed!</span></p>";
    $errors += 1;
} else {
    echo "<span class=\"ok\">OK!</span></p>";
}
// check directories
// cache exists?
echo "<p>Checking if <span class=\"mono\">assets/cache</span> directory exists: ";
if (!file_exists("../assets/cache")) {
    echo "<span class=\"notok\">Failed!</span></p>";
    $errors += 1;
} else {
    echo "<span class=\"ok\">OK!</span></p>";
}
// cache writable?
echo "<p>Checking if <span class=\"mono\">assets/cache</span> directory is writable: ";
if (!is_writable("../assets/cache")) {
    echo "<span class=\"notok\">Failed!</span></p>";
    $errors += 1;
} else {
    echo "<span class=\"ok\">OK!</span></p>";
}
// cache files writable?
echo "<p>Checking if <span class=\"mono\">assets/cache/siteCache.idx.php</span> file is writable: ";
if (!is_writable("../assets/cache/siteCache.idx.php")) {
    echo "<span class=\"notok\">Failed!</span></p>";
    $errors += 1;
} else {
    echo "<span class=\"ok\">OK!</span></p>";
}
echo "<p>Checking if <span class=\"mono\">assets/cache/sitePublishing.idx.php</span> file is writable: ";
if (!is_writable("../assets/cache/sitePublishing.idx.php")) {
    echo "<span class=\"notok\">Failed!</span></p>";
    $errors += 1;
} else {
    echo "<span class=\"ok\">OK!</span></p>";
}
//	echo "<p>Checking if <span class=\"mono\">assets/cache/siteSnippets.cache.php</span> file is writable: ";
//	if(!is_writable("../assets/cache/siteSnippets.cache.php")) {
//		echo "<span class=\"notok\">Failed!</span></p>";
//		$errors += 1;
//	} else {
//		echo "<span class=\"ok\">OK!</span></p>";
//	}
//	echo "<p>Checking if <span class=\"mono\">assets/cache/sitePlugins.cache.php</span> file is writable: ";
//	if(!is_writable("../assets/cache/sitePlugins.cache.php")) {
//		echo "<span class=\"notok\">Failed!</span></p>";
//		$errors += 1;
//	} else {
//		echo "<span class=\"ok\">OK!</span></p>";
//	}
// images exists?
echo "<p>Checking if <span class=\"mono\">assets/images</span> directory exists: ";
if (!file_exists("../assets/images")) {
    echo "<span class=\"notok\">Failed!</span></p>";
    $errors += 1;
} else {
    echo "<span class=\"ok\">OK!</span></p>";
}
// images writable?
echo "<p>Checking if <span class=\"mono\">assets/images</span> directory is writable: ";
if (!is_writable("../assets/images")) {
    echo "<span class=\"notok\">Failed!</span></p>";
    $errors += 1;
} else {
    echo "<span class=\"ok\">OK!</span></p>";
}
// export exists?
echo "<p>Checking if <span class=\"mono\">assets/export</span> directory exists: ";
if (!file_exists("../assets/export")) {
    echo "<span class=\"notok\">Failed!</span></p>";
    $errors += 1;
} else {
    echo "<span class=\"ok\">OK!</span></p>";
}
// export writable?
echo "<p>Checking if <span class=\"mono\">assets/export</span> directory is writable: ";
if (!is_writable("../assets/export")) {
    echo "<span class=\"notok\">Failed!</span></p>";
    $errors += 1;
} else {
    echo "<span class=\"ok\">OK!</span></p>";
}
// config.inc.php writable?
echo "<p>Checking if <span class=\"mono\">manager/includes/config.inc.php</span> exists and is writable: ";
if (!file_exists("../manager/includes/config.inc.php")) {
    // make an attempt to create the file
    @ $hnd = fopen("../manager/includes/config.inc.php", 'w');
    @ fwrite($hnd, "<?php //MODx configuration file ?>");
    @ fclose($hnd);
}
$isWriteable = is_writable("../manager/includes/config.inc.php");
if (!$isWriteable) {
    echo "<span class=\"notok\">Failed!</span></p><p><strong>For new Linux/Unix installs, please create a blank file named <span class=\"mono\">config.inc.php</span> in the <span class=\"mono\">manager/includes/</span> directory with file permissions set to 0666.</strong></p>";
    $errors += 1;
} else {
    echo "<span class=\"ok\">OK!</span></p>";
}

// connect to the database
if ($installMode == 1) {
    include "../manager/includes/config.inc.php";
} else {
    // get db info from post
    $database_server = $_POST['databasehost'];
    $database_user = $_POST['databaseloginname'];
    $database_password = $_POST['databaseloginpassword'];
    $database_collation = $_POST['database_collation'];
    $database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
    $database_connection_charset = $_POST['database_connection_charset'];
    $dbase = $_POST['database_name'];
    $table_prefix = $_POST['tableprefix'];
}
echo "<p>Creating connection to the database: ";
if (!@ $conn = mysql_connect($database_server, $database_user, $database_password)) {
    $errors += 1;
    echo "<span class=\"notok\">Database connection failed!</span><p />Please check the database login details and try again.</p>";
} else {
    echo "<span class=\"ok\">OK!</span></p>";
}
// make sure we can use the database
if ($installMode > 0 && !@ mysql_query("USE {$dbase}")) {
    $errors += 1;
    echo "<span class=\"notok\">Database could not be selected!</span><p />Please check the database permissions for the specified user and try again.</p>";
}

// check the database collation if not specified in the configuration
if (!isset ($database_connection_charset) || empty ($database_connection_charset)) {
    if (!$rs = @ mysql_query("show session variables like 'collation_database'")) {
        $rs = @ mysql_query("show session variables like 'collation_server'");
    }
    if ($rs && $collation = mysql_fetch_row($rs)) {
        $database_collation = $collation[1];
    }
    if (empty ($database_collation)) {
        $database_collation = 'utf8_unicode_ci';
    }
    $database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
    $database_connection_charset = $database_charset;
}

// check table prefix
if ($conn && $installMode == 0) {
    echo "<p>Checking table prefix `" . $table_prefix . "`: ";
    if (@ $rs = mysql_query("SELECT COUNT(*) FROM $dbase.`" . $table_prefix . "site_content`")) {
        echo "<span class=\"notok\">Failed!</span></b> - Table prefix is already in use in this database!</p>";
        $errors += 1;
        echo "<p>Setup couldn't install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.</p>";
    } else {
        echo "<span class=\"ok\">OK!</span></p>";
    }
} elseif ($conn && $installMode == 2) {
    echo "<p>Checking table prefix `" . $table_prefix . "`: ";
    if (!$rs = @mysql_query("SELECT COUNT(*) FROM $dbase.`" . $table_prefix . "site_content`")) {
        echo "<span class=\"notok\">Failed!</span></b> - Table prefix does not exist in this database!</p>";
        $errors += 1;
        echo "<p>Setup couldn't install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.</p>";
    } else {
        echo "<span class=\"ok\">OK!</span></p>";
    }
}


// andrazk 20070416 - add install flag and disable manager login
// assets/cache writable?
if (is_writable("../assets/cache")) {
		  if (file_exists('../assets/cache/installProc.inc.php')) {
		      @chmod('../assets/cache/installProc.inc.php', 0755);
		      unlink('../assets/cache/installProc.inc.php');
		  }

		  // make an attempt to create the file
		  @ $hnd = fopen("../assets/cache/installProc.inc.php", 'w');
		  @ fwrite($hnd, '<?php $installStartTime = '.time().'; ?>');
		  @ fclose($hnd);
}


if ($errors > 0) {
    ?>
<p>Unfortunately, Setup cannot continue at the moment, due to the above
<?php echo $errors > 1 ? $errors." " : "" ; ?>error<?php echo $errors > 1 ? "s" : "" ; ?>.
Please correct the error<?php echo $errors > 1 ? "s" : "" ; ?>, and try
again. If you need help figuring out how to fix the problem<?php echo $errors > 1 ? "s" : "" ; ?>,
visit the <a href="http://www.modxcms.com/forums/" target="_blank">Operation
MODx Forums</a>.</p>
</p>
<?php
}

echo "<p>&nbsp;</p>";

$nextAction= $errors > 0 ? 'summary' : 'install';

?>
<form name="install" action="index.php?action=<?php echo $nextAction ?>" method="post">
	<div>
		<input type="hidden" value="1" name="chkagree" <?php echo isset($_POST['chkagree']) ? 'checked="checked" ':""; ?>/>
		<input type="hidden" value="<?php echo $installMode ?>" name="installmode" />

		<input type="hidden" value="<?php echo trim($_POST['database_name'], '`'); ?>" name="database_name" />
		<input type="hidden" value="<?php echo $_POST['tableprefix'] ?>" name="tableprefix" />
		<input type="hidden" value="<?php echo $_POST['database_collation'] ?>" name="database_collation" />
		<input type="hidden" value="<?php echo $_POST['database_connection_charset'] ?>" name="database_connection_charset" />
		<input type="hidden" value="<?php echo $_POST['databasehost'] ?>" name="databasehost" />
		<input type="hidden" value="<?php echo $_POST['databaseloginname'] ?>" name="databaseloginname" />
		<input type="hidden" value="<?php echo $_POST['databaseloginpassword'] ?>" name="databaseloginpassword" />
		<input type="hidden" value="<?php echo $_POST['cmsadmin'] ?>" name="cmsadmin" />
		<input type="hidden" value="<?php echo $_POST['cmsadminemail'] ?>" name="cmsadminemail" />
		<input type="hidden" value="<?php echo $_POST['cmspassword'] ?>" name="cmspassword" />
		
		<input type="hidden" value="1" name="options_selected" />
		
		<input type="hidden" value="<?php echo $_POST['installdata'] ?>" name="installdata" />
<?php
$templates = isset ($_POST['template']) ? $_POST['template'] : array ();
foreach ($templates as $i => $template) echo "<input type=\"hidden\" name=\"template[]\" value=\"{$i}\" />\n";
$chunks = isset ($_POST['chunk']) ? $_POST['chunk'] : array ();
foreach ($chunks as $i => $chunk) echo "<input type=\"hidden\" name=\"chunk[]\" value=\"{$i}\" />\n";
$snippets = isset ($_POST['snippet']) ? $_POST['snippet'] : array ();
foreach ($snippets as $i => $snippet) echo "<input type=\"hidden\" name=\"snippet[]\" value=\"{$i}\" />\n";
$plugins = isset ($_POST['plugin']) ? $_POST['plugin'] : array ();
foreach ($plugins as $i => $plugin) echo "<input type=\"hidden\" name=\"plugin[]\" value=\"{$i}\" />\n";
$modules = isset ($_POST['module']) ? $_POST['module'] : array ();
foreach ($modules as $i => $module) echo "<input type=\"hidden\" name=\"module[]\" value=\"{$i}\" />\n";
?>
	</div>
	<div id="navbar">
		<input type="submit" value="Next" name="cmdnext" style="float:right;width:100px;" />
		<span style="float:right">&nbsp;</span>
		<input type="submit" value="Back" name="cmdback" style="float:right;width:100px;" onclick="this.form.action='index.php?action=options';this.form.submit();return false;" />
	</div>
</form>
