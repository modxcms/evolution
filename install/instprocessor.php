<?php
global $moduleName;
global $moduleVersion;
global $moduleSQLBaseFile;
global $moduleSQLDataFile;
global $moduleSQLUpdateFile;

global $moduleChunks;
global $moduleTemplates;
global $moduleSnippets;
global $modulePlugins;
global $moduleModules;
global $moduleTVs;

global $errors;

$create = false;

// set timout limit
@ set_time_limit(120); // used @ to prevent warning when using safe mode?

echo $_lang['setup_database'];

$installMode= intval($_POST['installmode']);
$installData = $_POST['installdata'] ? 1 : 0;

//if ($installMode == 1) {
//	include "../manager/includes/config.inc.php";
//} else {
	// get db info from post
	$database_server = $_POST['databasehost'];
	$database_user = $_POST['databaseloginname'];
	$database_password = $_POST['databaseloginpassword'];
	$database_collation = $_POST['database_collation'];
	$database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
	$database_connection_charset = $_POST['database_connection_charset'];
	$database_connection_method = $_POST['database_connection_method'];
	$dbase = "`" . $_POST['database_name'] . "`";
	$table_prefix = $_POST['tableprefix'];
	$adminname = $_POST['cmsadmin'];
	$adminemail = $_POST['cmsadminemail'];
	$adminpass = $_POST['cmspassword'];
	$managerlanguage = $_POST['language'];
//}

// set session name variable
if (!isset ($site_sessionname)) {
	$site_sessionname = 'SN' . uniqid('');
}

// get base path and url
$a = explode("install", str_replace("\\", "/", dirname($_SERVER["PHP_SELF"])));
if (count($a) > 1)
	array_pop($a);
$url = implode("install", $a);
reset($a);
$a = explode("install", str_replace("\\", "/", realpath(dirname(__FILE__))));
if (count($a) > 1)
	array_pop($a);
$pth = implode("install", $a);
unset ($a);
$base_url = $url . (substr($url, -1) != "/" ? "/" : "");
$base_path = $pth . (substr($pth, -1) != "/" ? "/" : "");

// connect to the database
echo "<p>". $_lang['setup_database_create_connection'];
if (!@ $conn = mysql_connect($database_server, $database_user, $database_password)) {
	echo "<span class=\"notok\">".$_lang["setup_database_create_connection_failed"]."</span></p><p>".$_lang['setup_database_create_connection_failed_note']."</p>";
	return;
} else {
	echo "<span class=\"ok\">".$_lang['ok']."</span></p>";
}

// select database
echo "<p>".$_lang['setup_database_selection']. str_replace("`", "", $dbase) . "`: ";
if (!@ mysql_select_db(str_replace("`", "", $dbase), $conn)) {
	echo "<span class=\"notok\" style='color:#707070'>".$_lang['setup_database_selection_failed']."</span>".$_lang['setup_database_selection_failed_note']."</p>";
	$create = true;
} else {
    @ mysql_query("{$database_connection_method} {$database_connection_charset}");
	echo "<span class=\"ok\">".$_lang['ok']."</span></p>";
}

// try to create the database
if ($create) {
	echo "<p>".$_lang['setup_database_creation']. str_replace("`", "", $dbase) . "`: ";
	//	if(!@mysql_create_db(str_replace("`","",$dbase), $conn)) {
	if (! mysql_query("CREATE DATABASE $dbase DEFAULT CHARACTER SET $database_charset COLLATE $database_collation")) {
		echo "<span class=\"notok\">".$_lang['setup_database_creation_failed']."</span>".$_lang['setup_database_creation_failed_note']."</p>";
		$errors += 1;
?>
        <pre>
        database charset = <?php $database_charset ?>
        database collation = <?php $database_collation ?>
        </pre>
		<p><?php echo $_lang['setup_database_creation_failed_note2']?></p>
<?php

		return;
	} else {
		echo "<span class=\"ok\">".$_lang['ok']."</span></p>";
	}
}

// check table prefix
if ($installMode == 0) {
	echo "<p>" . $_lang['checking_table_prefix'] . $table_prefix . "`: ";
	if (@ $rs = mysql_query("SELECT COUNT(*) FROM $dbase.`" . $table_prefix . "site_content`")) {
		echo "<span class=\"notok\">" . $_lang['failed'] . "</span>" . $_lang['table_prefix_already_inuse'] . "</p>";
		$errors += 1;
		echo "<p>" . $_lang['table_prefix_already_inuse_note'] . "</p>";
		return;
	} else {
		echo "<span class=\"ok\">" . $_lang['ok'] . "</span></p>";
	}
}

// open db connection
$setupPath = realpath(dirname(__FILE__));
include "{$setupPath}/setup.info.php";
include "{$setupPath}/sqlParser.class.php";
$sqlParser = new SqlParser($database_server, $database_user, $database_password, str_replace("`", "", $dbase), $table_prefix, $adminname, $adminemail, $adminpass, $database_connection_charset, $managerlanguage, $database_connection_method);
$sqlParser->mode = ($installMode < 1) ? "new" : "upd";
//$sqlParser->imageUrl = 'http://' . $_SERVER['SERVER_NAME'] . $base_url . "assets/";
$sqlParser->imageUrl = "assets/";
$sqlParser->imagePath = $base_path . "assets/";
$sqlParser->fileManagerPath = $base_path;
$sqlParser->ignoreDuplicateErrors = true;
$sqlParser->connect();

// install/update database
echo "<p>" . $_lang['setup_database_creating_tables'];
if ($moduleSQLBaseFile) {
	$sqlParser->process($moduleSQLBaseFile);
	// display database results
	if ($sqlParser->installFailed == true) {
		$errors += 1;
		echo "<span class=\"notok\"><b>" . $_lang['database_alerts'] . "</span></p>";
		echo "<p>" . $_lang['setup_couldnt_install'] . "</p>";
		echo "<p>" . $_lang['installation_error_occured'] . "<br /><br />";
		for ($i = 0; $i < count($sqlParser->mysqlErrors); $i++) {
			echo "<em>" . $sqlParser->mysqlErrors[$i]["error"] . "</em>" . $_lang['during_execution_of_sql'] . "<span class='mono'>" . strip_tags($sqlParser->mysqlErrors[$i]["sql"]) . "</span>.<hr />";
		}
		echo "</p>";
		echo "<p>" . $_lang['some_tables_not_updated'] . "</p>";
		return;
	} else {
		echo "<span class=\"ok\">".$_lang['ok']."</span></p>";
	}
}

// install data
if ($installData && $moduleSQLDataFile) {
	echo "<p>" . $_lang['installing_demo_site'];
	$sqlParser->process($moduleSQLDataFile);
	// display database results
	if ($sqlParser->installFailed == true) {
		$errors += 1;
		echo "<span class=\"notok\"><b>" . $_lang['database_alerts'] . "</span></p>";
		echo "<p>" . $_lang['setup_couldnt_install'] . "</p>";
		echo "<p>" . $_lang['installation_error_occured'] . "<br /><br />";
		for ($i = 0; $i < count($sqlParser->mysqlErrors); $i++) {
			echo "<em>" . $sqlParser->mysqlErrors[$i]["error"] . "</em>" . $_lang['during_execution_of_sql'] . "<span class='mono'>" . strip_tags($sqlParser->mysqlErrors[$i]["sql"]) . "</span>.<hr />";
		}
		echo "</p>";
		echo "<p>" . $_lang['some_tables_not_updated'] . "</p>";
		return;
	} else {
		echo "<span class=\"ok\">".$_lang['ok']."</span></p>";
	}
}

// write the config.inc.php file if new installation
echo "<p>" . $_lang['writing_config_file'];
$configString = '<?php
/**
 *	MODx Configuration file
 */
$database_type = \'mysql\';
$database_server = \'' . $database_server . '\';
$database_user = \'' . $database_user . '\';
$database_password = \'' . $database_password . '\';
$database_connection_charset = \'' . $database_connection_charset . '\';
$database_connection_method = \'' . $database_connection_method . '\';
$dbase = \'`' . str_replace("`", "", $dbase) . '`\';
$table_prefix = \'' . $table_prefix . '\';
error_reporting(E_ALL & ~E_NOTICE);

$lastInstallTime = '.time().';

$site_sessionname = \'' . $site_sessionname . '\';
$https_port = \'443\';

// automatically assign base_path and base_url
if(empty($base_path)||empty($base_url)||$_REQUEST[\'base_path\']||$_REQUEST[\'base_url\']) {
    $sapi= \'undefined\';
    if (!strstr($_SERVER[\'PHP_SELF\'], $_SERVER[\'SCRIPT_NAME\']) && ($sapi= @ php_sapi_name()) == \'cgi\') {
        $script_name= $_SERVER[\'PHP_SELF\'];
    } else {
        $script_name= $_SERVER[\'SCRIPT_NAME\'];
    }
    $a= explode("/manager", str_replace("\\\\", "/", dirname($script_name)));
    if (count($a) > 1)
        array_pop($a);
    $url= implode("manager", $a);
    reset($a);
    $a= explode("manager", str_replace("\\\\", "/", dirname(__FILE__)));
    if (count($a) > 1)
        array_pop($a);
    $pth= implode("manager", $a);
    unset ($a);
    $base_url= $url . (substr($url, -1) != "/" ? "/" : "");
    $base_path= $pth . (substr($pth, -1) != "/" && substr($pth, -1) != "\\\\" ? "/" : "");
    // assign site_url
    $site_url= ((isset ($_SERVER[\'HTTPS\']) && strtolower($_SERVER[\'HTTPS\']) == \'on\') || $_SERVER[\'SERVER_PORT\'] == $https_port) ? \'https://\' : \'http://\';
    $site_url .= $_SERVER[\'HTTP_HOST\'];
    if ($_SERVER[\'SERVER_PORT\'] != 80)
        $site_url= str_replace(\':\' . $_SERVER[\'SERVER_PORT\'], \'\', $site_url); // remove port from HTTP_HOST  
    $site_url .= ($_SERVER[\'SERVER_PORT\'] == 80 || (isset ($_SERVER[\'HTTPS\']) && strtolower($_SERVER[\'HTTPS\']) == \'on\') || $_SERVER[\'SERVER_PORT\'] == $https_port) ? \'\' : \':\' . $_SERVER[\'SERVER_PORT\'];
    $site_url .= $base_url;
}

if (!defined(\'MODX_BASE_PATH\')) define(\'MODX_BASE_PATH\', $base_path);
if (!defined(\'MODX_BASE_URL\')) define(\'MODX_BASE_URL\', $base_url);
if (!defined(\'MODX_SITE_URL\')) define(\'MODX_SITE_URL\', $site_url);
if (!defined(\'MODX_MANAGER_PATH\')) define(\'MODX_MANAGER_PATH\', $base_path.\'manager/\');
if (!defined(\'MODX_MANAGER_URL\')) define(\'MODX_MANAGER_URL\', $site_url.\'manager/\');

// start cms session
if(!function_exists(\'startCMSSession\')) {
	function startCMSSession(){
		global $site_sessionname;
		session_name($site_sessionname);
		session_start();
		$cookieExpiration= 0;
        if (isset ($_SESSION[\'mgrValidated\']) || isset ($_SESSION[\'webValidated\'])) {
            $contextKey= isset ($_SESSION[\'mgrValidated\']) ? \'mgr\' : \'web\';
            if (isset ($_SESSION[\'modx.\' . $contextKey . \'.session.cookie.lifetime\']) && is_numeric($_SESSION[\'modx.\' . $contextKey . \'.session.cookie.lifetime\'])) {
                $cookieLifetime= intval($_SESSION[\'modx.\' . $contextKey . \'.session.cookie.lifetime\']);
            }
            if ($cookieLifetime) {
                $cookieExpiration= time() + $cookieLifetime;
            }
			if (!isset($_SESSION[\'modx.session.created.time\'])) {
			  $_SESSION[\'modx.session.created.time\'] = time();
			}
        }
		setcookie(session_name(), session_id(), $cookieExpiration, MODX_BASE_URL);
	}
}';
$configString .= "\n?>";
$filename = '../manager/includes/config.inc.php';
$configFileFailed = false;
if (@ !$handle = fopen($filename, 'w')) {
	$configFileFailed = true;
}

// write $somecontent to our opened file.
if (@ fwrite($handle, $configString) === FALSE) {
	$configFileFailed = true;
}
@ fclose($handle);

// try to chmod the config file go-rwx (for suexeced php)
$chmodSuccess = @chmod($filename, 0600);

if ($configFileFailed == true) {
	echo "<span class=\"notok\">" . $_lang['failed'] . "</span></p>";
	$errors += 1;
?>
	<p><?php echo $_lang['cant_write_config_file']?><span class="mono">manager/includes/config.inc.php</span></p>
	<textarea style="width:400px; height:160px;">
	<?php echo $configString; ?>
	</textarea>
	<p><?php echo $_lang['cant_write_config_file_note']?></p>
<?php

	return;
} else {
	echo "<span class=\"ok\">" . $_lang['ok'] . "</span></p>";
}

// generate new site_id and set manager theme to MODx
if ($installMode == 0) {
	$siteid = uniqid('');
	mysql_query("REPLACE INTO $dbase.`" . $table_prefix . "system_settings` (setting_name,setting_value) VALUES('site_id','$siteid'),('manager_theme','MODxLight')", $sqlParser->conn);
} else {
	// update site_id if missing
	$ds = mysql_query("SELECT setting_name,setting_value FROM $dbase.`" . $table_prefix . "system_settings` WHERE setting_name='site_id'", $sqlParser->conn);
	if ($ds) {
		$r = mysql_fetch_assoc($ds);
		$siteid = $r['setting_value'];
		if ($siteid == '' || $siteid = 'MzGeQ2faT4Dw06+U49x3') {
			$siteid = uniqid('');
			mysql_query("REPLACE INTO $dbase.`" . $table_prefix . "system_settings` (setting_name,setting_value) VALUES('site_id','$siteid')", $sqlParser->conn);
		}
	}
}

// Install Templates
if (isset ($_POST['template'])) {
	echo "<p style=\"color:#707070\">" . $_lang['templates'] . ":</p> ";
	$selTemplates = $_POST['template'];
	foreach ($selTemplates as $si) {
		$si = (int) trim($si);
		$name = mysql_escape_string($moduleTemplates[$si][0]);
		$desc = mysql_escape_string($moduleTemplates[$si][1]);
		$type = $moduleTemplates[$si][2]; // 0:file, 1:content
		$filecontent = $moduleTemplates[$si][3];
		if ($type == 0 && !file_exists($filecontent))
			echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_template'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
		else {
			$template = ($type == 1) ? $filecontent : implode('', file($filecontent));
			$template = mysql_escape_string($template);
			$rs = mysql_query("SELECT * FROM $dbase.`" . $table_prefix . "site_templates` WHERE templatename='$name'", $sqlParser->conn);
			if (mysql_num_rows($rs)) {
				if (!@ mysql_query("UPDATE $dbase.`" . $table_prefix . "site_templates` SET content='$template', description='$desc' WHERE templatename='$name';", $sqlParser->conn)) {
					$errors += 1;
					echo "<p>" . mysql_error() . "</p>";
					return;
				}
				echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
			} else {
				if (!@ mysql_query("INSERT INTO $dbase.`" . $table_prefix . "site_templates` (templatename,description,content) VALUES('$name','$desc','$template');", $sqlParser->conn)) {
					$errors += 1;
					echo "<p>" . mysql_error() . "</p>";
					return;
				}
				echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
			}
		}
	}
}

// Install Chunks
if (isset ($_POST['chunk'])) {
	echo "<p style=\"color:#707070\">" . $_lang['chunks'] . ":</p> ";
	$selChunks = $_POST['chunk'];
	foreach ($selChunks as $si) {
		$si = (int) trim($si);
		$name = mysql_escape_string($moduleChunks[$si][0]);
		$desc = mysql_escape_string($moduleChunks[$si][1]);
		$type = $moduleChunks[$si][2]; // 0:file, 1:content
		$filecontent = $moduleChunks[$si][3];
		if ($type == 0 && !file_exists($filecontent))
			echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_chunk'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
		else {
			$chunk = ($type == 1) ? $filecontent : implode('', file($filecontent));
			$chunk = mysql_escape_string($chunk);
			$rs = mysql_query("SELECT * FROM $dbase.`" . $table_prefix . "site_htmlsnippets` WHERE name='$name'", $sqlParser->conn);
			if (mysql_num_rows($rs)) {
				if (!@ mysql_query("UPDATE $dbase.`" . $table_prefix . "site_htmlsnippets` SET snippet='$chunk', description='$desc' WHERE name='$name';", $sqlParser->conn)) {
					$errors += 1;
					echo "<p>" . mysql_error() . "</p>";
					return;
				}
				echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
			} else {
				if (!@ mysql_query("INSERT INTO $dbase.`" . $table_prefix . "site_htmlsnippets` (name,description,snippet) VALUES('$name','$desc','$chunk');", $sqlParser->conn)) {
					$errors += 1;
					echo "<p>" . mysql_error() . "</p>";
					return;
				}
				echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
			}
		}
	}
}

// Install module
if (isset ($_POST['module'])) {
	echo "<p style=\"color:#707070\">" . $_lang['modules'] . ":</p> ";
	$selPlugs = $_POST['module'];
	foreach ($selPlugs as $si) {
		$si = (int) trim($si);
		$name = mysql_escape_string($moduleModules[$si][0]);
		$desc = mysql_escape_string($moduleModules[$si][1]);
		$type = $moduleModules[$si][2]; // 0:file, 1:content
		$filecontent = $moduleModules[$si][3];
		$properties = mysql_escape_string($moduleModules[$si][4]);
		$guid = mysql_escape_string($moduleModules[$si][5]);
		$shared = mysql_escape_string($moduleModules[$si][6]);
		if ($type == 0 && !file_exists($filecontent))
			echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_module'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
		else {
			$module = ($type == 1) ? $filecontent : implode('', file($filecontent));
			$module = mysql_escape_string($module);
			$rs = mysql_query("SELECT * FROM $dbase.`" . $table_prefix . "site_modules` WHERE name='$name'", $sqlParser->conn);
			if (mysql_num_rows($rs)) {
			    $row = mysql_fetch_assoc($rs);
			    $props = propUpdate($properties,$row['properties']);
			    if (!@ mysql_query("UPDATE $dbase.`" . $table_prefix . "site_modules` SET modulecode='$module', description='$desc', properties='$props', enable_sharedparams='$shared' WHERE name='$name';", $sqlParser->conn)) {
					echo "<p>" . mysql_error() . "</p>";
					return;
				}
				echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
			} else {
				if (!@ mysql_query("INSERT INTO $dbase.`" . $table_prefix . "site_modules` (name,description,modulecode,properties,guid,enable_sharedparams) VALUES('$name','$desc','$module','$properties','$guid','$shared');", $sqlParser->conn)) {
					echo "<p>" . mysql_error() . "</p>";
					return;
				}
				echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
			}
		}
	}
}

// Install plugins
if (isset ($_POST['plugin'])) {
	echo "<p style=\"color:#707070\">" . $_lang['plugins'] . ":</p> ";
	$selPlugs = $_POST['plugin'];
	foreach ($selPlugs as $si) {
		$si = (int) trim($si);
		$name = mysql_escape_string($modulePlugins[$si][0]);
		$desc = mysql_escape_string($modulePlugins[$si][1]);
		$type = $modulePlugins[$si][2]; // 0:file, 1:content
		$filecontent = $modulePlugins[$si][3];
		$properties = mysql_escape_string($modulePlugins[$si][4]);
		$events = explode(",", $modulePlugins[$si][5]);
		$guid = mysql_escape_string($modulePlugins[$si][6]);
		if ($type == 0 && !file_exists($filecontent))
			echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_plugin'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
		else {
			$plugin = ($type == 1) ? $filecontent : implode('', file($filecontent));
			$plugin = mysql_escape_string($plugin);
			$rs = mysql_query("SELECT * FROM $dbase.`" . $table_prefix . "site_plugins` WHERE name='$name'", $sqlParser->conn);
			if (mysql_num_rows($rs)) {
			    $row = mysql_fetch_assoc($rs);
			    $props = propUpdate($properties,$row['properties']);
			    if($row['description'] == $desc){
				    if (!@ mysql_query("UPDATE $dbase.`" . $table_prefix . "site_plugins` SET plugincode='$plugin', description='$desc', properties='$props' WHERE name='$name';", $sqlParser->conn)) {
					    echo "<p>" . mysql_error() . "</p>";
					    return;
					}
			    } else {
			    	if (!@ mysql_query("UPDATE $dbase.`" . $table_prefix . "site_plugins` SET disabled='1' WHERE name='$name';", $sqlParser->conn)) {
						echo "<p>".mysql_error()."</p>";
						return;
					}
			        if(!@mysql_query("INSERT INTO $dbase.`".$table_prefix."site_plugins` (name,description,plugincode,properties,moduleguid,disabled) VALUES('$name','$desc','$plugin','$properties','$guid','0');",$sqlParser->conn)) {
						echo "<p>".mysql_error()."</p>";
						return;
			    	}
			    }
				echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
			} else {
				if (!@ mysql_query("INSERT INTO $dbase.`" . $table_prefix . "site_plugins` (name,description,plugincode,properties,moduleguid) VALUES('$name','$desc','$plugin','$properties','$guid');", $sqlParser->conn)) {
					echo "<p>" . mysql_error() . "</p>";
					return;
				}
				echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
			}
			// add system events
			if (count($events) > 0) {
				$ds=mysql_query("SELECT id FROM $dbase.`".$table_prefix."site_plugins` WHERE name='$name' AND description='$desc';",$sqlParser->conn);
				if ($ds) {
					$row = mysql_fetch_assoc($ds);
					$id = $row["id"];
					// remove existing events
					mysql_query('DELETE FROM ' . $dbase . '.`' . $table_prefix . 'site_plugin_events` WHERE pluginid = \'' . $id . '\'');
					// add new events
					mysql_query("INSERT INTO $dbase.`" . $table_prefix . "site_plugin_events` (pluginid, evtid) SELECT '$id' as 'pluginid',se.id as 'evtid' FROM $dbase.`" . $table_prefix . "system_eventnames` se WHERE name IN ('" . implode("','", $events) . "')");
				}
			}
		}
	}
}

// Install Snippet
if (isset ($_POST['snippet'])) {
	echo "<p style=\"color:#707070\">" . $_lang['snippets'] . ":</p> ";
	$selSnips = $_POST['snippet'];
	foreach ($selSnips as $si) {
		$si = (int) trim($si);
		$name = mysql_escape_string($moduleSnippets[$si][0]);
		$desc = mysql_escape_string($moduleSnippets[$si][1]);
		$type = $moduleSnippets[$si][2]; // 0:file, 1:content
		$filecontent = $moduleSnippets[$si][3];
		$properties = mysql_escape_string($moduleSnippets[$si][4]);
		if ($type == 0 && !file_exists($filecontent))
			echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_snippet'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
		else {
			$snippet = ($type == 1) ? $filecontent : implode('', file($filecontent));
			$snippet = mysql_escape_string($snippet);
			$rs = mysql_query("SELECT * FROM $dbase.`" . $table_prefix . "site_snippets` WHERE name='$name'", $sqlParser->conn);
			if (mysql_num_rows($rs)) {
			    $row = mysql_fetch_assoc($rs);
			    $props = propUpdate($properties,$row['properties']);
			    if (!@ mysql_query("UPDATE $dbase.`" . $table_prefix . "site_snippets` SET snippet='$snippet', description='$desc', properties='$props' WHERE name='$name';", $sqlParser->conn)) {
					echo "<p>" . mysql_error() . "</p>";
					return;
				}
				echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
			} else {
				if (!@ mysql_query("INSERT INTO $dbase.`" . $table_prefix . "site_snippets` (name,description,snippet,properties) VALUES('$name','$desc','$snippet','$properties');", $sqlParser->conn)) {
					echo "<p>" . mysql_error() . "</p>";
					return;
				}
				echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
			}
		}
	}
}

// install updates now all records have been added
if ($installData && $moduleSQLUpdateFile) {
	echo "<p>" . $_lang['running_database_updates'];
	$sqlParser->process($moduleSQLUpdateFile);
	// display database results
	if ($sqlParser->installFailed == true) {
		$errors += 1;
		echo "<span class=\"notok\"><b>" . $_lang['database_alerts'] . "</span></p>";
		echo "<p>" . $_lang['setup_couldnt_install'] . "</p>";
		echo "<p>" . $_lang["installation_error_occured"] . "<br /><br />";
		for ($i = 0; $i < count($sqlParser->mysqlErrors); $i++) {
			echo "<em>" . $sqlParser->mysqlErrors[$i]["error"] . "</em>" . $_lang['during_execution_of_sql'] . "<span class='mono'>" . strip_tags($sqlParser->mysqlErrors[$i]["sql"]) . "</span>.<hr />";
		}
		echo "</p>";
		echo "<p>" . $_lang['some_tables_not_updated'] . "</p>";
		return;
	} else {
		echo "<span class=\"ok\">".$_lang['ok']."</span></p>";
	}
}

// call back function
if ($callBackFnc != "")
	$callBackFnc ($sqlParser);


// Setup the MODx API -- needed for the cache processor
define('MODX_API_MODE', true);
define('MODX_BASE_PATH', $base_path);
$database_type = 'mysql';
// initiate a new document parser
include_once('../manager/includes/document.parser.class.inc.php');
$modx = new DocumentParser;
$modx->db->connect();
// always empty cache after install
include_once "../manager/processors/cache_sync.class.processor.php";
$sync = new synccache();
$sync->setCachepath("../assets/cache/");
$sync->setReport(false);
$sync->emptyCache(); // first empty the cache

// try to chmod the cache go-rwx (for suexeced php)
$chmodSuccess = @chmod('../assets/cache/siteCache.idx.php', 0600);
$chmodSuccess = @chmod('../assets/cache/sitePublishing.idx.php', 0600);

// remove any locks on the manager functions so initial manager login is not blocked
mysql_query("TRUNCATE TABLE `".$table_prefix."active_users`");

// close db connection
$sqlParser->close();

// andrazk 20070416 - release manager access
  if (file_exists('../assets/cache/installProc.inc.php')) {
	  @chmod('../assets/cache/installProc.inc.php', 0755);
    unlink('../assets/cache/installProc.inc.php');
	}

// setup completed!
echo "<p><b>" . $_lang['installation_successful'] . "</b></p>";
echo "<p>" . $_lang['to_log_into_content_manager'] . "</p>";
if ($installMode == 0) {
	echo "<p><img src=\"img_info.gif\" width=\"32\" height=\"32\" align=\"left\" style=\"margin-right:10px;\" />" . $_lang['installation_note'] . "</p><br />&nbsp;";
} else {
	echo "<p><img src=\"img_info.gif\" width=\"32\" height=\"32\" align=\"left\" style=\"margin-right:10px;\" />" . $_lang['upgrade_note'] . "</p><br />&nbsp;";
}

// Property Update function
function propUpdate($new,$old){
    // Split properties up into arrays
    $returnArr = array();
    $newArr = explode("&",$new);
    $oldArr = explode("&",$old);

    foreach ($newArr as $k => $v) {
        if(!empty($v)){
	        $tempArr = split("=",trim($v));
	        $returnArr[$tempArr[0]] = $tempArr[1];
        }
    }
    foreach ($oldArr as $k => $v) {
        if(!empty($v)){
            $tempArr = split("=",trim($v));
            $returnArr[$tempArr[0]] = $tempArr[1];
        }
    }

    // Make unique array
    $returnArr = array_unique($returnArr);

    // Build new string for new properties value
    foreach ($returnArr as $k => $v) {
        $return .= "&$k=$v ";
    }

    return $return;
}
?>
