<?php
if (file_exists(dirname(__FILE__)."/../assets/cache/siteManager.php")) {
    include_once(dirname(__FILE__)."/../assets/cache/siteManager.php");
}else{
    define('MGR_DIR', 'manager');
}

global $moduleName;
global $moduleVersion;
global $moduleSQLBaseFile;
global $moduleSQLDataFile;

global $moduleChunks;
global $moduleTemplates;
global $moduleSnippets;
global $modulePlugins;
global $moduleModules;
global $moduleTVs;
global $moduleDependencies;

global $errors;

$create = false;

// set timout limit
@ set_time_limit(120); // used @ to prevent warning when using safe mode?

echo "<p>{$_lang['setup_database']}</p>\n";

$installMode= intval($_POST['installmode']);
$installData = $_POST['installdata'] == "1" ? 1 : 0;

//if ($installMode == 1) {
//	include "../".MGR_DIR."/includes/config.inc.php";
//} else {
// get db info from post
$database_server = $_POST['databasehost'];
$database_user = $_SESSION['databaseloginname'];
$database_password = $_SESSION['databaseloginpassword'];
$database_collation = $_POST['database_collation'];
$database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
$database_connection_charset = $_POST['database_connection_charset'];
$database_connection_method = $_POST['database_connection_method'];
$dbase = "`" . $_POST['database_name'] . "`";
$table_prefix = $_POST['tableprefix'];
$adminname = $_POST['cmsadmin'];
$adminemail = $_POST['cmsadminemail'];
$adminpass = $_POST['cmspassword'];
$managerlanguage = $_POST['managerlanguage'];
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
if (!$conn = mysqli_connect($database_server, $database_user, $database_password)) {
    echo "<span class=\"notok\">".$_lang["setup_database_create_connection_failed"]."</span></p><p>".$_lang['setup_database_create_connection_failed_note']."</p>";
    return;
} else {
    echo "<span class=\"ok\">".$_lang['ok']."</span></p>";
}

// select database
echo "<p>".$_lang['setup_database_selection']. str_replace("`", "", $dbase) . "`: ";
if (!mysqli_select_db($conn, str_replace("`", "", $dbase))) {
    echo "<span class=\"notok\" style='color:#707070'>".$_lang['setup_database_selection_failed']."</span>".$_lang['setup_database_selection_failed_note']."</p>";
    $create = true;
} else {
	if (function_exists('mysqli_set_charset')) mysqli_set_charset($conn, $database_charset);
    mysqli_query($conn, "{$database_connection_method} {$database_connection_charset}");
    echo "<span class=\"ok\">".$_lang['ok']."</span></p>";
}

// try to create the database
if ($create) {
    echo "<p>".$_lang['setup_database_creation']. str_replace("`", "", $dbase) . "`: ";
    //	if(!@mysqli_create_db(str_replace("`","",$dbase), $conn)) {
    if (! mysqli_query($conn, "CREATE DATABASE $dbase DEFAULT CHARACTER SET $database_charset COLLATE $database_collation")) {
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
    if (@ $rs = mysqli_query($conn, "SELECT COUNT(*) FROM $dbase.`" . $table_prefix . "site_content`")) {
        echo "<span class=\"notok\">" . $_lang['failed'] . "</span>" . $_lang['table_prefix_already_inuse'] . "</p>";
        $errors += 1;
        echo "<p>" . $_lang['table_prefix_already_inuse_note'] . "</p>";
        return;
    } else {
        echo "<span class=\"ok\">" . $_lang['ok'] . "</span></p>";
    }
}

if(!function_exists('parseProperties')) {
    // parses a resource property string and returns the result as an array
    // duplicate of method in documentParser class
    function parseProperties($propertyString) {
        $parameter= array ();
        if (!empty ($propertyString)) {
            $tmpParams= explode("&", $propertyString);
            for ($x= 0; $x < count($tmpParams); $x++) {
                if (strpos($tmpParams[$x], '=', 0)) {
                    $pTmp= explode("=", $tmpParams[$x]);
                    $pvTmp= explode(";", trim($pTmp[1]));
                    if ($pvTmp[1] == 'list' && $pvTmp[3] != "")
                        $parameter[trim($pTmp[0])]= $pvTmp[3]; //list default
                    else
                        if ($pvTmp[1] != 'list' && $pvTmp[2] != "")
                            $parameter[trim($pTmp[0])]= $pvTmp[2];
                }
            }
        }
        return $parameter;
    }
}

// check status of Inherit Parent Template plugin
$auto_template_logic = 'parent';
if ($installMode != 0) {
    $rs = mysqli_query($conn, "SELECT properties, disabled FROM $dbase.`" . $table_prefix . "site_plugins` WHERE name='Inherit Parent Template'");
    $row = mysqli_fetch_row($rs);
    if(!$row) {
        // not installed
        $auto_template_logic = 'system';
    } else {
        if($row[1] == 1) {
            // installed but disabled
            $auto_template_logic = 'system';
        } else {
            // installed, enabled .. see how it's configured
            $properties = parseProperties($row[0]);
            if(isset($properties['inheritTemplate'])) {
                if($properties['inheritTemplate'] == 'From First Sibling') {
                    $auto_template_logic = 'sibling';
                }
            }
        }
    }
}

// open db connection
$setupPath = realpath(dirname(__FILE__));
include "{$setupPath}/setup.info.php";
include "{$setupPath}/sqlParser.class.php";
$sqlParser = new SqlParser($database_server, $database_user, $database_password, str_replace("`", "", $dbase), $table_prefix, $adminname, $adminemail, $adminpass, $database_connection_charset, $managerlanguage, $database_connection_method, $auto_template_logic);
$sqlParser->mode = ($installMode < 1) ? "new" : "upd";
/* image and file manager paths now handled via settings screen in Manager
$sqlParser->imageUrl = 'http://' . $_SERVER['SERVER_NAME'] . $base_url . "assets/";
$sqlParser->imageUrl = "assets/";
$sqlParser->imagePath = $base_path . "assets/";
$sqlParser->fileManagerPath = $base_path;
*/
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

// custom or not
if (file_exists(dirname(__FILE__)."/../../assets/cache/siteManager.php")) {
    $mgrdir = 'include_once(dirname(__FILE__)."/../../assets/cache/siteManager.php");';
}else{
    $mgrdir = 'define(\'MGR_DIR\', \'manager\');';
}

// write the config.inc.php file if new installation
echo "<p>" . $_lang['writing_config_file'];
$configString = '<?php
/**
 * MODX Configuration file
 */
$database_type = \'mysqli\';
$database_server = \'' . $database_server . '\';
$database_user = \'' . mysqli_real_escape_string($conn, $database_user) . '\';
$database_password = \'' . mysqli_real_escape_string($conn, $database_password) . '\';
$database_connection_charset = \'' . $database_connection_charset . '\';
$database_connection_method = \'' . $database_connection_method . '\';
$dbase = \'`' . str_replace("`", "", $dbase) . '`\';
$table_prefix = \'' . $table_prefix . '\';

$lastInstallTime = '.time().';

$site_sessionname = \'' . $site_sessionname . '\';
$https_port = \'443\';

if(!defined(\'MGR_DIR\'))
'.$mgrdir.'

// automatically assign base_path and base_url
if(empty($base_path)||empty($base_url)||$_REQUEST[\'base_path\']||$_REQUEST[\'base_url\']) {
    $sapi= \'undefined\';
    if (!strstr($_SERVER[\'PHP_SELF\'], $_SERVER[\'SCRIPT_NAME\']) && ($sapi= @ php_sapi_name()) == \'cgi\') {
        $script_name= $_SERVER[\'PHP_SELF\'];
    } else {
        $script_name= $_SERVER[\'SCRIPT_NAME\'];
    }
    $script_name = str_replace("\\\\", "/", dirname($script_name));
    if(strpos($script_name,MGR_DIR)!==false)
        $separator = MGR_DIR;
    elseif(strpos($script_name,\'/assets/\')!==false)
        $separator = \'assets\';
    else $separator = \'\';

    if($separator!==\'\') $a= explode(\'/\'.$separator, $script_name);
    else $a = array($script_name);

    if (count($a) > 1)
        array_pop($a);
    $url= implode($separator, $a);
    reset($a);
    $a= explode(MGR_DIR, str_replace("\\\\", "/", dirname(__FILE__)));
    if (count($a) > 1)
        array_pop($a);
    $pth= implode(MGR_DIR, $a);
    unset ($a);
    $base_url= $url . (substr($url, -1) != "/" ? "/" : "");
    $base_path= $pth . (substr($pth, -1) != "/" && substr($pth, -1) != "\\\\" ? "/" : "");
}

// check for valid hostnames
$site_hostname = str_replace(\':\' . $_SERVER[\'SERVER_PORT\'], \'\', $_SERVER[\'HTTP_HOST\']);
if (!defined(\'MODX_SITE_HOSTNAMES\')) {
	$site_hostnames_path = $base_path . \'assets/cache/siteHostnames.php\';
	if (is_file($site_hostnames_path)) {
		include_once($site_hostnames_path);
	} else {
		define(\'MODX_SITE_HOSTNAMES\', \'\');
	}
}
$site_hostnames = explode(\',\', MODX_SITE_HOSTNAMES);
if (!empty($site_hostnames[0]) && !in_array($site_hostname, $site_hostnames)) {
    $site_hostname = $site_hostnames[0];
}

// assign site_url
$site_url= ((isset ($_SERVER[\'HTTPS\']) && strtolower($_SERVER[\'HTTPS\']) == \'on\') || $_SERVER[\'SERVER_PORT\'] == $https_port) ? \'https://\' : \'http://\';
$site_url .= $site_hostname;
if ($_SERVER[\'SERVER_PORT\'] != 80)
    $site_url= str_replace(\':\' . $_SERVER[\'SERVER_PORT\'], \'\', $site_url); // remove port from HTTP_HOST  
$site_url .= ($_SERVER[\'SERVER_PORT\'] == 80 || (isset ($_SERVER[\'HTTPS\']) && strtolower($_SERVER[\'HTTPS\']) == \'on\') || $_SERVER[\'SERVER_PORT\'] == $https_port) ? \'\' : \':\' . $_SERVER[\'SERVER_PORT\'];
$site_url .= $base_url;

if (!defined(\'MODX_BASE_PATH\')) define(\'MODX_BASE_PATH\', $base_path);
if (!defined(\'MODX_BASE_URL\')) define(\'MODX_BASE_URL\', $base_url);
if (!defined(\'MODX_SITE_URL\')) define(\'MODX_SITE_URL\', $site_url);
if (!defined(\'MODX_MANAGER_PATH\')) define(\'MODX_MANAGER_PATH\', $base_path.MGR_DIR.\'/\');
if (!defined(\'MODX_MANAGER_URL\')) define(\'MODX_MANAGER_URL\', $site_url.MGR_DIR.\'/\');

// start cms session
if(!function_exists(\'startCMSSession\')) {
    function removeInvalidCmsSessionFromStorage(&$storage, $session_name) {
      if (isset($storage[$session_name]) && $storage[$session_name] === \'\')
      {
        unset($storage[$session_name]);
      }
    }
    function removeInvalidCmsSessionIds($session_name) {
        // session ids is invalid iff it is empty string
        // storage priorioty can see in PHP source ext/session/session.c
        removeInvalidCmsSessionFromStorage($_COOKIE, $session_name);
        removeInvalidCmsSessionFromStorage($_GET, $session_name);
        removeInvalidCmsSessionFromStorage($_POST, $session_name);
    }
    function startCMSSession(){
        global $site_sessionname, $https_port;
        session_name($site_sessionname);
        removeInvalidCmsSessionIds($site_sessionname);
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
        $secure = ((isset ($_SERVER[\'HTTPS\']) && strtolower($_SERVER[\'HTTPS\']) == \'on\') || $_SERVER[\'SERVER_PORT\'] == $https_port);
        setcookie(session_name(), session_id(), $cookieExpiration, MODX_BASE_URL, null, $secure, true);
    }
}';
$configString .= "\n?>";
$filename = '../'.MGR_DIR.'/includes/config.inc.php';
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
$chmodSuccess = @chmod($filename, 0404);

if ($configFileFailed == true) {
    echo "<span class=\"notok\">" . $_lang['failed'] . "</span></p>";
    $errors += 1;
?>
    <p><?php echo $_lang['cant_write_config_file']?><span class="mono"><?php echo MGR_DIR; ?>/includes/config.inc.php</span></p>
    <textarea style="width:400px; height:160px;">
    <?php echo $configString; ?>
    </textarea>
    <p><?php echo $_lang['cant_write_config_file_note']?></p>
<?php
    return;
} else {
    echo "<span class=\"ok\">" . $_lang['ok'] . "</span></p>";
}

// generate new site_id and set manager theme to MODxRE
if ($installMode == 0) {
    $siteid = uniqid('');
    mysqli_query($sqlParser->conn, "REPLACE INTO $dbase.`" . $table_prefix . "system_settings` (setting_name,setting_value) VALUES('site_id','$siteid'),('manager_theme','MODxRE2')");
} else {
    // update site_id if missing
    $ds = mysqli_query($sqlParser->conn, "SELECT setting_name,setting_value FROM $dbase.`" . $table_prefix . "system_settings` WHERE setting_name='site_id'");
    if ($ds) {
        $r = mysqli_fetch_assoc($ds);
        $siteid = $r['setting_value'];
        if ($siteid == '' || $siteid = 'MzGeQ2faT4Dw06+U49x3') {
            $siteid = uniqid('');
            mysqli_query($sqlParser->conn, "REPLACE INTO $dbase.`" . $table_prefix . "system_settings` (setting_name,setting_value) VALUES('site_id','$siteid')");
        }
    }
}


// Install Templates
if (isset ($_POST['template']) || $installData) {
    echo "<h3>" . $_lang['templates'] . ":</h3> ";
    $selTemplates = $_POST['template'];
    foreach ($moduleTemplates as $k=>$moduleTemplate) {
        $installSample = in_array('sample', $moduleTemplate[6]) && $installData == 1;
        if($installSample || in_array($k, $selTemplates)) {
            $name = mysqli_real_escape_string($conn, $moduleTemplate[0]);
            $desc = mysqli_real_escape_string($conn, $moduleTemplate[1]);
            $category = mysqli_real_escape_string($conn, $moduleTemplate[4]);
            $locked = mysqli_real_escape_string($conn, $moduleTemplate[5]);
            $filecontent = $moduleTemplate[3];
            if (!file_exists($filecontent)) {
                echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_template'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
            } else {
                // Create the category if it does not already exist
                $category_id = getCreateDbCategory($category, $sqlParser);

                // Strip the first comment up top
                $template = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', file_get_contents($filecontent), 1);
                $template = mysqli_real_escape_string($conn, $template);

                // See if the template already exists
                $rs = mysqli_query($sqlParser->conn, "SELECT * FROM $dbase.`" . $table_prefix . "site_templates` WHERE templatename='$name'");

                if (mysqli_num_rows($rs)) {
                    if (!mysqli_query($sqlParser->conn, "UPDATE $dbase.`" . $table_prefix . "site_templates` SET content='$template', description='$desc', category=$category_id, locked='$locked'  WHERE templatename='$name';")) {
                        $errors += 1;
                        echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
                } else {
                    if (!@ mysqli_query($sqlParser->conn, "INSERT INTO $dbase.`" . $table_prefix . "site_templates` (templatename,description,content,category,locked) VALUES('$name','$desc','$template',$category_id,'$locked');")) {
                        $errors += 1;
                        echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
                }
            }
        }
    }
}

// Install Template Variables
if (isset ($_POST['tv']) || $installData) {
    echo "<h3>" . $_lang['tvs'] . ":</h3> ";
    $selTVs = $_POST['tv'];
    foreach ($moduleTVs as $k=>$moduleTV) {
        $installSample = in_array('sample', $moduleTV[12]) && $installData == 1;
        if($installSample || in_array($k, $selTVs)) {
            $name = mysqli_real_escape_string($conn, $moduleTV[0]);
            $caption = mysqli_real_escape_string($conn, $moduleTV[1]);
            $desc = mysqli_real_escape_string($conn, $moduleTV[2]);
            $input_type = mysqli_real_escape_string($conn, $moduleTV[3]);
            $input_options = mysqli_real_escape_string($conn, $moduleTV[4]);
            $input_default = mysqli_real_escape_string($conn, $moduleTV[5]);
            $output_widget = mysqli_real_escape_string($conn, $moduleTV[6]);
            $output_widget_params = mysqli_real_escape_string($conn, $moduleTV[7]);
            $filecontent = $moduleTV[8];
            $assignments = $moduleTV[9];
            $category = mysqli_real_escape_string($conn, $moduleTV[10]);
            $locked = mysqli_real_escape_string($conn, $moduleTV[11]);


            // Create the category if it does not already exist
            $category = getCreateDbCategory($category, $sqlParser);

            $rs = mysqli_query($sqlParser->conn, "SELECT * FROM $dbase.`" . $table_prefix . "site_tmplvars` WHERE name='$name'");
            if (mysqli_num_rows($rs)) {
                $insert = true;
                while($row = mysqli_fetch_assoc($rs)) {
                    if (!mysqli_query($sqlParser->conn, "UPDATE $dbase.`" . $table_prefix . "site_tmplvars` SET type='$input_type', caption='$caption', description='$desc', category=$category, locked=$locked, elements='$input_options', display='$output_widget', display_params='$output_widget_params', default_text='$input_default' WHERE id={$row['id']};")) {
                        echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                        return;
                    }
                    $insert = false;
                }
                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
            } else {
                //$q = "INSERT INTO $dbase.`" . $table_prefix . "site_tmplvars` (type,name,caption,description,category,locked,elements,display,display_params,default_text) VALUES('$input_type','$name','$caption','$desc',(SELECT (CASE COUNT(*) WHEN 0 THEN 0 ELSE `id` END) `id` FROM $dbase.`" . $table_prefix . "categories` WHERE `category` = '$category'),$locked,'$input_options','$output_widget','$output_widget_params','$input_default');";
                $q = "INSERT INTO $dbase.`" . $table_prefix . "site_tmplvars` (type,name,caption,description,category,locked,elements,display,display_params,default_text) VALUES('$input_type','$name','$caption','$desc',$category,$locked,'$input_options','$output_widget','$output_widget_params','$input_default');";
                if (!mysqli_query($sqlParser->conn, $q)) {
                    echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                    return;
                }
                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
            }

            // add template assignments
            $assignments = explode(',', $assignments);

            if (count($assignments) > 0) {

                // remove existing tv -> template assignments
                $ds=mysqli_query($sqlParser->conn, "SELECT id FROM $dbase.`".$table_prefix."site_tmplvars` WHERE name='$name' AND description='$desc';");
                $row = mysqli_fetch_assoc($ds);
                $id = $row["id"];
                mysqli_query($sqlParser->conn, 'DELETE FROM ' . $dbase . '.`' . $table_prefix . 'site_tmplvar_templates` WHERE tmplvarid = \'' . $id . '\'');

                // add tv -> template assignments
                foreach ($assignments as $assignment) {
                    $template = mysqli_real_escape_string($conn, $assignment);
                    $ts = mysqli_query($sqlParser->conn, "SELECT id FROM $dbase.`".$table_prefix."site_templates` WHERE templatename='$template';");
                    if ($ds && $ts) {
                        $tRow = mysqli_fetch_assoc($ts);
                        $templateId = $tRow['id'];
                        mysqli_query($sqlParser->conn, "INSERT INTO $dbase.`" . $table_prefix . "site_tmplvar_templates` (tmplvarid, templateid) VALUES($id, $templateId)");
                   }
                }
            }
        }
    }
}

// Install Chunks
if (isset ($_POST['chunk']) || $installData) {
    echo "<h3>" . $_lang['chunks'] . ":</h3> ";
    $selChunks = $_POST['chunk'];
    foreach ($moduleChunks as $k=>$moduleChunk) {
        $installSample = in_array('sample', $moduleChunk[5]) && $installData == 1;
        if($installSample || in_array($k, $selChunks)) {

            $name = mysqli_real_escape_string($conn, $moduleChunk[0]);
            $desc = mysqli_real_escape_string($conn, $moduleChunk[1]);
            $category = mysqli_real_escape_string($conn, $moduleChunk[3]);
            $overwrite = mysqli_real_escape_string($conn, $moduleChunk[4]);
            $filecontent = $moduleChunk[2];

            if (!file_exists($filecontent))
                echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_chunk'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
            else {

                // Create the category if it does not already exist
                $category_id = getCreateDbCategory($category, $sqlParser);

                $chunk = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', file_get_contents($filecontent), 1);
                $chunk = mysqli_real_escape_string($conn, $chunk);
                $rs = mysqli_query($sqlParser->conn, "SELECT * FROM $dbase.`" . $table_prefix . "site_htmlsnippets` WHERE name='$name'");
                $count_original_name = mysqli_num_rows($rs);
                if($overwrite == 'false') {
                    $newname = $name . '-' . str_replace('.', '_', $modx_version);
                    $rs = mysqli_query($sqlParser->conn, "SELECT * FROM $dbase.`" . $table_prefix . "site_htmlsnippets` WHERE name='$newname'");
                    $count_new_name = mysqli_num_rows($rs);
                }
                $update = $count_original_name > 0 && $overwrite == 'true';
                if ($update) {
                    if (!mysqli_query($sqlParser->conn, "UPDATE $dbase.`" . $table_prefix . "site_htmlsnippets` SET snippet='$chunk', description='$desc', category=$category_id WHERE name='$name';")) {
                        $errors += 1;
                        echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
                } elseif($count_new_name == 0) {
                    if($count_original_name > 0 && $overwrite == 'false') {
                        $name = $newname;
                    }
                    if (!mysqli_query($sqlParser->conn, "INSERT INTO $dbase.`" . $table_prefix . "site_htmlsnippets` (name,description,snippet,category) VALUES('$name','$desc','$chunk',$category_id);")) {
                        $errors += 1;
                        echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
                }
            }
        }
    }
}

// Install Modules
if (isset ($_POST['module']) || $installData) {
    echo "<h3>" . $_lang['modules'] . ":</h3> ";
    $selModules = $_POST['module'];
    foreach ($moduleModules as $k=>$moduleModule) {
        $installSample = in_array('sample', $moduleModule[7]) && $installData == 1;
        if($installSample || in_array($k, $selModules)) {
            $name = mysqli_real_escape_string($conn, $moduleModule[0]);
            $desc = mysqli_real_escape_string($conn, $moduleModule[1]);
            $filecontent = $moduleModule[2];
            $properties = mysqli_real_escape_string($conn, $moduleModule[3]);
            $guid = mysqli_real_escape_string($conn, $moduleModule[4]);
            $shared = mysqli_real_escape_string($conn, $moduleModule[5]);
            $category = mysqli_real_escape_string($conn, $moduleModule[6]);
            if (!file_exists($filecontent))
                echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_module'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
            else {

                // Create the category if it does not already exist
                $category = getCreateDbCategory($category, $sqlParser);

                $module = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filecontent), 2));
                // $module = removeDocblock($module, 'module'); // Modules have no fileBinding, keep docblock for info-tab
                $module = mysqli_real_escape_string($conn, $module);
                $rs = mysqli_query($sqlParser->conn, "SELECT * FROM $dbase.`" . $table_prefix . "site_modules` WHERE name='$name'");
                if (mysqli_num_rows($rs)) {
                    $row = mysqli_fetch_assoc($rs);
                    $props = propUpdate($properties,mysqli_real_escape_string($conn, $row['properties']));
                    if (!mysqli_query($sqlParser->conn, "UPDATE $dbase.`" . $table_prefix . "site_modules` SET modulecode='$module', description='$desc', properties='$props', enable_sharedparams='$shared' WHERE name='$name';")) {
                        echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
                } else {
                    if (!mysqli_query($sqlParser->conn, "INSERT INTO $dbase.`" . $table_prefix . "site_modules` (name,description,modulecode,properties,guid,enable_sharedparams,category) VALUES('$name','$desc','$module','$properties','$guid','$shared', $category);")) {
                        echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
                }
            }
        }
    }
}

// Install Plugins
if (isset ($_POST['plugin']) || $installData) {
    echo "<h3>" . $_lang['plugins'] . ":</h3> ";
    $selPlugs = $_POST['plugin'];
    foreach ($modulePlugins as $k=>$modulePlugin) {
        $installSample = in_array('sample', $modulePlugin[8]) && $installData == 1;
        if($installSample || in_array($k, $selPlugs)) {
            $name = mysqli_real_escape_string($conn, $modulePlugin[0]);
            $desc = mysqli_real_escape_string($conn, $modulePlugin[1]);
            $filecontent = $modulePlugin[2];
            $properties = mysqli_real_escape_string($conn, $modulePlugin[3]);
            $events = explode(",", $modulePlugin[4]);
            $guid = mysqli_real_escape_string($conn, $modulePlugin[5]);
            $category = mysqli_real_escape_string($conn, $modulePlugin[6]);
            $leg_names = '';
            $disabled = $modulePlugin[9];
            if(array_key_exists(7, $modulePlugin)) {
                // parse comma-separated legacy names and prepare them for sql IN clause
                $leg_names = "'" . implode("','", preg_split('/\s*,\s*/', mysqli_real_escape_string($conn, $modulePlugin[7]))) . "'";
            }
            if (!file_exists($filecontent))
                echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_plugin'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
            else {

                // disable legacy versions based on legacy_names provided
                if(!empty($leg_names)) {
                    $update_query = "UPDATE $dbase.`" . $table_prefix . "site_plugins` SET disabled='1' WHERE name IN ($leg_names);";
                    $rs = mysqli_query($sqlParser->conn, $update_query);
                }

                // Create the category if it does not already exist
                $category = getCreateDbCategory($category, $sqlParser);

                $plugin = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filecontent), 2));
                $plugin = removeDocblock($plugin, 'plugin');
                $plugin = mysqli_real_escape_string($conn, $plugin);
                $rs = mysqli_query($sqlParser->conn, "SELECT * FROM $dbase.`" . $table_prefix . "site_plugins` WHERE name='$name'");
                if (mysqli_num_rows($rs)) {
                    $insert = true;
                    while($row = mysqli_fetch_assoc($rs)) {
                        $props = propUpdate($properties,mysqli_real_escape_string($conn, $row['properties']));
                        if($row['description'] == $desc){
                            if (! mysqli_query($sqlParser->conn, "UPDATE $dbase.`" . $table_prefix . "site_plugins` SET plugincode='$plugin', description='$desc', properties='$props' WHERE id={$row['id']};")) {
                                echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                                return;
                            }
                            $insert = false;
                        } else {
                            if (!mysqli_query($sqlParser->conn, "UPDATE $dbase.`" . $table_prefix . "site_plugins` SET disabled='1' WHERE id={$row['id']};")) {
                                echo "<p>".mysqli_error($sqlParser->conn)."</p>";
                                return;
                            }
                        }
                    }
                    if($insert === true) {
                        if(!mysqli_query($sqlParser->conn, "INSERT INTO $dbase.`".$table_prefix."site_plugins` (name,description,plugincode,properties,moduleguid,disabled,category) VALUES('$name','$desc','$plugin','$properties','$guid','0',$category);")) {
                            echo "<p>".mysqli_error($sqlParser->conn)."</p>";
                            return;
                        }
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
                } else {
                    if (!mysqli_query($sqlParser->conn, "INSERT INTO $dbase.`" . $table_prefix . "site_plugins` (name,description,plugincode,properties,moduleguid,category,disabled) VALUES('$name','$desc','$plugin','$properties','$guid',$category,$disabled);")) {
                        echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
                }
                // add system events
                if (count($events) > 0) {
                    $ds=mysqli_query($sqlParser->conn, "SELECT id FROM $dbase.`".$table_prefix."site_plugins` WHERE name='$name' AND description='$desc';");
                    if ($ds) {
                        $row = mysqli_fetch_assoc($ds);
                        $id = $row["id"];
                        // remove existing events
                        mysqli_query($sqlParser->conn, 'DELETE FROM ' . $dbase . '.`' . $table_prefix . 'site_plugin_events` WHERE pluginid = \'' . $id . '\'');
                        // add new events
                        mysqli_query($sqlParser->conn, "INSERT INTO $dbase.`" . $table_prefix . "site_plugin_events` (pluginid, evtid) SELECT '$id' as 'pluginid',se.id as 'evtid' FROM $dbase.`" . $table_prefix . "system_eventnames` se WHERE name IN ('" . implode("','", $events) . "')");
                    }
                }
            }
        }
    }
}

// Install Snippets
if (isset ($_POST['snippet']) || $installData) {
    echo "<h3>" . $_lang['snippets'] . ":</h3> ";
    $selSnips = $_POST['snippet'];
    foreach ($moduleSnippets as $k=>$moduleSnippet) {
        $installSample = in_array('sample', $moduleSnippet[5]) && $installData == 1;
        if($installSample || in_array($k, $selSnips)) {
            $name = mysqli_real_escape_string($conn, $moduleSnippet[0]);
            $desc = mysqli_real_escape_string($conn, $moduleSnippet[1]);
            $filecontent = $moduleSnippet[2];
            $properties = mysqli_real_escape_string($conn, $moduleSnippet[3]);
            $category = mysqli_real_escape_string($conn, $moduleSnippet[4]);
            if (!file_exists($filecontent))
                echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_snippet'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
            else {

                // Create the category if it does not already exist
                $category = getCreateDbCategory($category, $sqlParser);

                $snippet = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filecontent)));
                $snippet = removeDocblock($snippet, 'snippet');
                $snippet = mysqli_real_escape_string($conn, $snippet);
                $rs = mysqli_query($sqlParser->conn, "SELECT * FROM $dbase.`" . $table_prefix . "site_snippets` WHERE name='$name'");
                if (mysqli_num_rows($rs)) {
                    $row = mysqli_fetch_assoc($rs);
                    $props = propUpdate($properties,mysqli_real_escape_string($conn, $row['properties']));
                    if (!mysqli_query($sqlParser->conn, "UPDATE $dbase.`" . $table_prefix . "site_snippets` SET snippet='$snippet', description='$desc', properties='$props' WHERE name='$name';")) {
                        echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
                } else {
                    if (!mysqli_query($sqlParser->conn, "INSERT INTO $dbase.`" . $table_prefix . "site_snippets` (name,description,snippet,properties,category) VALUES('$name','$desc','$snippet','$properties',$category);")) {
                        echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
                }
            }
        }
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

// Install Dependencies
foreach ($moduleDependencies as $dependency) {
	$ds = mysqli_query($sqlParser->conn, 'SELECT id, guid FROM ' . $dbase . '`' . $sqlParser->prefix . 'site_modules` WHERE name="' . $dependency['module'] . '"');
	if (!$ds) {
		echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
		return;
	} else {
		$row = mysqli_fetch_assoc($ds);
		$moduleId = $row["id"];
		$moduleGuid = $row["guid"];
	}
	// get extra id
	$ds = mysqli_query($sqlParser->conn, 'SELECT id FROM ' . $dbase . '`' . $sqlParser->prefix . 'site_' . $dependency['table'] . '` WHERE ' . $dependency['column'] . '="' . $dependency['name'] . '"');
	if (!$ds) {
		echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
		return;
	} else {
		$row = mysqli_fetch_assoc($ds);
		$extraId = $row["id"];
	}
	// setup extra as module dependency
	$ds = mysqli_query($sqlParser->conn, 'SELECT module FROM ' . $dbase . '`' . $sqlParser->prefix . 'site_module_depobj` WHERE module=' . $moduleId . ' AND resource=' . $extraId . ' AND type=' . $dependency['type'] . ' LIMIT 1');
	if (!$ds) {
		echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
		return;
	} else {
		if (mysqli_num_rows($ds) === 0) {
			mysqli_query($sqlParser->conn, 'INSERT INTO ' . $dbase . '`' . $sqlParser->prefix . 'site_module_depobj` (module, resource, type) VALUES(' . $moduleId . ',' . $extraId . ',' . $dependency['type'] . ')');
			echo '<p>&nbsp;&nbsp;' . $dependency['module'] . ' Module: <span class="ok">' . $_lang['depedency_create'] . '</span></p>';
		} else {
			mysqli_query($sqlParser->conn, 'UPDATE ' . $dbase . '`' . $sqlParser->prefix . 'site_module_depobj` SET module = ' . $moduleId . ', resource = ' . $extraId . ', type = ' . $dependency['type'] . ' WHERE module=' . $moduleId . ' AND resource=' . $extraId . ' AND type=' . $dependency['type']);
			echo '<p>&nbsp;&nbsp;' . $dependency['module'] . ' Module: <span class="ok">' . $_lang['depedency_update'] . '</span></p>';
		}
		if ($dependency['type'] == 30 || $dependency['type'] == 40) {
			// set extra guid for plugins and snippets
			$ds = mysqli_query($sqlParser->conn, 'SELECT id FROM ' . $dbase . '`' . $sqlParser->prefix . 'site_' . $dependency['table'] . '` WHERE id=' . $extraId . ' LIMIT 1');
			if (!$ds) {
				echo "<p>" . mysqli_error($sqlParser->conn) . "</p>";
				return;
			} else {
				if (mysqli_num_rows($ds) != 0) {
					mysqli_query($sqlParser->conn, 'UPDATE ' . $dbase . '`' . $sqlParser->prefix . 'site_' . $dependency['table'] . '` SET moduleguid = ' . $moduleGuid . ' WHERE id=' . $extraId);
					echo '<p>&nbsp;&nbsp;' . $dependency['name'] . ': <span class="ok">' . $_lang['guid_set'] . '</span></p>';
				}
			}
		}
	}
}

// call back function
if ($callBackFnc != "")
    $callBackFnc ($sqlParser);

// Setup the MODX API -- needed for the cache processor
define('MODX_API_MODE', true);
define('MODX_BASE_PATH', $base_path);
if (!defined('MODX_MANAGER_PATH')) define('MODX_MANAGER_PATH', $base_path.MGR_DIR.'/');
$database_type = 'mysqli';
// initiate a new document parser
include_once('../'.MGR_DIR.'/includes/document.parser.class.inc.php');
$modx = new DocumentParser;
$modx->db->connect();
// always empty cache after install
include_once "../".MGR_DIR."/processors/cache_sync.class.processor.php";
$sync = new synccache();
$sync->setCachepath("../assets/cache/");
$sync->setReport(false);
$sync->emptyCache(); // first empty the cache

// try to chmod the cache go-rwx (for suexeced php)
$chmodSuccess = @chmod('../assets/cache/siteCache.idx.php', 0600);
$chmodSuccess = @chmod('../assets/cache/sitePublishing.idx.php', 0600);

// remove any locks on the manager functions so initial manager login is not blocked
mysqli_query($conn, "TRUNCATE TABLE `".$table_prefix."active_users`");

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
    echo "<p><img src=\"img/ico_info.png\" width=\"40\" height=\"42\" align=\"left\" style=\"margin-right:10px;\" />" . $_lang['installation_note'] . "</p>";
} else {
    echo "<p><img src=\"img/ico_info.png\" width=\"40\" height=\"42\" align=\"left\" style=\"margin-right:10px;\" />" . $_lang['upgrade_note'] . "</p>";
}

// Property Update function
function propUpdate($new,$old){
    // Split properties up into arrays
    $returnArr = array();
    $newArr = explode("&",$new);
    $oldArr = explode("&",$old);
    $return = '';

    foreach ($newArr as $k => $v) {
        if(!empty($v)){
            $tempArr = explode("=",trim($v));
            $returnArr[$tempArr[0]] = $tempArr[1];
        }
    }
    foreach ($oldArr as $k => $v) {
        if(!empty($v)){
            $tempArr = explode("=",trim($v));
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

function getCreateDbCategory($category, $sqlParser) {
    $dbase = $sqlParser->dbname;
    $dbase = '`' . trim($dbase,'`') . '`';
    $table_prefix = $sqlParser->prefix;
    $category_id = 0;
    if(!empty($category)) {
        $category = mysqli_real_escape_string($sqlParser->conn, $category);
        $rs = mysqli_query($sqlParser->conn, "SELECT id FROM $dbase.`".$table_prefix."categories` WHERE category = '".$category."'");
        if(mysqli_num_rows($rs) && ($row = mysqli_fetch_assoc($rs))) {
            $category_id = $row['id'];
        } else {
            $q = "INSERT INTO $dbase.`".$table_prefix."categories` (`category`) VALUES ('{$category}');";
            $rs = mysqli_query($sqlParser->conn, $q);
            if($rs) {
                $category_id = mysqli_insert_id($sqlParser->conn);
            }
        }
    }
    return $category_id;
}

// Remove installer Docblock only from components using plugin FileSource / fileBinding
function removeDocblock($code, $type) {
    
    $cleaned = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $code, 1);
    
    // Procedure taken from plugin.filesource.php
    switch($type) {
        case 'snippet':
            $elm_name = 'snippets';
            $include = 'return require';
            $count = 47;
            break;

        case 'plugin':
            $elm_name = 'plugins';
            $include = 'require';
            $count = 39;
            break;

        default:
            return $cleaned;
    };
    if(substr(trim($cleaned),0,$count) == $include.' MODX_BASE_PATH.\'assets/'.$elm_name.'/')
        return $cleaned;
    
    // fileBinding not found - return code incl docblock
    return $code;
}