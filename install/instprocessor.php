
<?php

error_reporting(E_ALL ^ E_NOTICE);

$create = false;
$errors = 0;

// set timout limit
set_time_limit(120); //?

echo "Setup will now attempt to setup the database:<br />";

$installMode = $_POST['installmode']=='upd' ? 1:0;
$installSample = intval($_POST['installsample']);
if($installMode==1) {
	include "../manager/includes/config.inc.php";
}
else {
	// get db info from post
	$database_server = $_POST['databasehost'];
	$database_user = $_POST['databaseloginname'];
	$database_password = $_POST['databaseloginpassword'];
	$dbase = $_POST['databasename'];
	$table_prefix = $_POST['tableprefix'];
	$adminname = $_POST['cmsadmin'];
	$adminpass = $_POST['cmspassword'];
}

// connect to the database
echo "<br />Creating connection to the database:";
if(!@$conn = mysql_connect($database_server, $database_user, $database_password)) {
	echo "<span class='notok'>Database connection failed!</span><p />Please check the database login details and try again.<br />";
	return;
} 
else {
	echo "<span class='ok'>OK!</span>";
}

// select database
echo "<br />Selecting database `".str_replace("`","",$dbase)."`:";
if(!@mysql_select_db(str_replace("`","",$dbase), $conn)) {
	echo "<span class='notok' style='color:#707070'>Database selection failed...</span></b> The database does not exist. Setup will attempt to create it:";
	$errors += 1;
	$create = true;
} else {
	echo "<span class='ok'>OK!</span>";
}

// try to create the database
if($create) {
	echo "<br />Creating database `".str_replace("`","",$dbase)."`:";
	if(!@mysql_create_db(str_replace("`","",$dbase), $conn)) {
		echo "<span class='notok'>Database creation failed!</span></b> - Setup could not create the database!";
		$errors += 1;
?>
		<p>
		Setup could not create the database, and no existing database with the same name was found. <br />
		It is likely that your hosting provider's security does not allow external scripts to create a database. <br />
		Please create a database according to your hosting provider's procedure, and run Setup again.</p>
<?php
		return;
	} 
	else {
		echo "<span class='ok'>OK!</span>";
	}
}

// check table prefix
if($installMode==0) {
	echo "<br />Checking table prefix `".$table_prefix."`: ";
	if(@$rs=mysql_query("SELECT COUNT(*) FROM $dbase.".$table_prefix."site_content")) {
		echo "<span class='notok'>Failed!</span></b> - Table prefix is already in use in this database!";
		$errors += 1;
		echo "<p>Setup couldn't install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.</p>";
		return;
	} 
	else {
		echo "<span class='ok'>OK!</span>";
	}
}

// open db connection
include "sqlParser.class.php";
$sqlParser = new SqlParser($database_server, $database_user, $database_password, str_replace("`","",$dbase), $table_prefix, $adminname, $adminpass);
$sqlParser->connect();

// install/update database
echo "<br />Creating database tables: ";
if($moduleSQLBaseFile) {
	$sqlParser->ignoreDuplicateErrors = true;
	$sqlParser->process($moduleSQLBaseFile);
	// display database results
	if ($sqlParser->installFailed==true) {
		echo "<p /><span class='notok'><b>Database Alerts!</b></span> <br />";
		echo "<span>MODx setup couldn't install/alter some tables inside the selected database.";
		echo "<p />The last error to occur was <i>".$sqlParser->mysqlErrors[count($sqlParser->mysqlErrors)-1]["error"]."</i> during the execution of SQL statement <span class='mono'>".strip_tags($sqlParser->mysqlErrors[count($sqlParser->mysqlErrors)-1]["sql"])."</span>.";
		echo "<p />Some table were not updated. This might be due to previous modifications.<br />";
		return;
	}
	else {
		echo "<span class='ok'><b>OK!</b></span>";
	}
}

// install sample data
if(installSample && $moduleSQLDataFile) {
	$sqlParser->process($moduleSQLDataFile);
}

// write the config.inc.php file if new installation
echo "<br />Writing configuration file: ";
$configString = '<?php
	/**
	 *	MODx Configuration file
	 *
	 */
	$database_type = "mysql";
	$database_server = "'.$database_server.'";
	$database_user = "'.$database_user.'";
	$database_password = "'.$database_password.'";
	$dbase = "`'.str_replace("`","",$dbase).'`";
	$table_prefix = "'.$table_prefix.'";		
	error_reporting(E_ALL ^ E_NOTICE);

    // automatically assign base_path and base_url
	if($base_path==""||$base_url=="") {
		$a = explode("manager",str_replace("\\\\","/",dirname($_SERVER["PHP_SELF"])));
		if(count($a)>1) array_pop($a);
		$url = implode("manager",$a); reset($a);
		$a = explode("manager",dirname(__FILE__));
		if(count($a)>1) array_pop($a);
		$pth = implode("manager",$a); unset($a);
		$base_url = $url.(substr($url,-1)!="/"? "/":"");
		$base_path = $pth.(substr($pth,-1)!="/"? "/":"");
	}';
$configString .= "\n?>";
$filename = '../manager/includes/config.inc.php';
$configFileFailed = false;
if (@!$handle = fopen($filename, 'w')) {
	$configFileFailed = true;
}

// write $somecontent to our opened file.
if (@fwrite($handle, $configString) === FALSE) {
	$configFileFailed = true;
}
@fclose($handle);	
if($configFileFailed==true) {
	echo "<span class='notok'>Failed!</span></b>";
	$errors += 1;
?>
	<p>MODx couldn't write the config file. Please copy the following into the <span class="mono">manager/includes/config.inc.php</span> file:</p>
	<textarea style="width:400px; height:160px;">
	<?php echo $configString; ?>
	</textarea>
	<p>Once that's been done, you can log into MODx Admin by pointing your browser at yoursite/manager/.</p>
<?php
	return;
} 
else {
	echo "<span class='ok'>OK!</span>";
}


// generate new site id number and set the manager theme to MODx 
if($installMode==0) {
	$siteid = uniqid("");
	mysql_query("REPLACE INTO $dbase.`".$table_prefix."system_settings` (setting_name,setting_value) VALUES('site_id','$siteid'),('manager_theme','MODx')",$sqlParser->conn);
}

// Install Snippet
if(isset($_POST['snippet'])) {
	echo "<p /><b><span style='color:#707070'>Snippets:</span></b> ";
	$selSnips = $_POST['snippet'];
	foreach($selSnips as $si) {
		$si = (int)trim($si);
		$name		= mysql_escape_string($moduleSnippets[$si][0]);
		$desc 		= mysql_escape_string($moduleSnippets[$si][1]);
		$type		= $moduleSnippets[$si][2]; // 0:file, 1:content
		$filecontent= $moduleSnippets[$si][3];
		$properties	= $moduleSnippets[$si][4];
		if ($type==0 && !file_exists($filecontent)) echo "<br />&nbsp;&nbsp;$name: <span class='notok'>Unable to install snippet. File '$filecontent' not found.</span>";
		else{
			$snippet = ($type==1)? $filecontent:implode ('', file($filecontent));
			$snippet = mysql_escape_string($snippet);			
			$rs = mysql_query("SELECT * FROM $dbase.`".$table_prefix."site_snippets` WHERE name='$name'",$sqlParser->conn);
			if (mysql_num_rows($rs)) {
				if(!@mysql_query("UPDATE $dbase.`".$table_prefix."site_snippets` SET snippet='$snippet',properties='$properties' WHERE name='$name';",$sqlParser->conn)) {
					echo "<p />".mysql_error();
					return;
				}
				echo "<br />&nbsp;&nbsp;$name: <span class='ok'>Upgraded</span>";
			}
			else{					
				if(!@mysql_query("INSERT INTO $dbase.`".$table_prefix."site_snippets` (name,description,snippet,properties) VALUES('$name','$desc','$snippet','$properties');",$sqlParser->conn)) {
					echo "<p />".mysql_error();
					return;
				}
				echo "<br />&nbsp;&nbsp;$name: <span class='ok'>Installed</span>";
			}
		}
	}
}

// Install Chunks
if(isset($_POST['chunk'])) {				
	echo "<p /><b><span style='color:#707070'>Chunks</span>:</b> ";
	$selChunks = $_POST['chunk'];
	foreach($selChunks as $si) {
		$si = (int)trim($si);
		$name		= mysql_escape_string($moduleChunks[$si][0]);
		$desc 		= mysql_escape_string($moduleChunks[$si][1]);
		$type		= $moduleChunks[$si][2]; // 0:file, 1:content
		$filecontent= $moduleChunks[$si][3];
		if ($type==0 && !file_exists($filecontent)) echo "<br />&nbsp;&nbsp;$name: <span class='notok'>Unable to install chunk. File '$filecontent' not found.</span>";
		else {
			$chunk = ($type==1)? $filecontent:implode ('', file($filecontent));
			$chunk = mysql_escape_string($chunk);			
			$rs = mysql_query("SELECT * FROM $dbase.`".$table_prefix."site_htmlsnippets` WHERE name='$name'",$sqlParser->conn);
			if (mysql_num_rows($rs)) {
				if(!@mysql_query("UPDATE $dbase.`".$table_prefix."site_htmlsnippets` SET snippet='$chunk' WHERE name='$name';",$sqlParser->conn)) {
					echo "<p />".mysql_error();
					return;
				}
				echo "<br />&nbsp;&nbsp;$name: <span class='ok'>Upgraded</span>";
			}
			else{
				if(!@mysql_query("INSERT INTO $dbase.`".$table_prefix."site_htmlsnippets` (name,description,snippet) VALUES('$name','$desc','$chunk');",$sqlParser->conn)) {
					echo "<p />".mysql_error();
					return;
				}
				echo "<br />&nbsp;&nbsp;$name: <span class='ok'>Installed</span>";
			}
		}
	}
}

// call back function
if ($callBackFnc!="") $callBackFnc($sqlParser);

// always empty cache after install
include_once "../manager/processors/cache_sync.class.processor.php";
$sync = new synccache();
$sync->setCachepath("../assets/cache/");
$sync->setReport(false);
$sync->emptyCache(); // first empty the cache

// setup completed!
echo "<p />";
echo "Installation was successful! ";
echo "To log into the Content Manager (manager/index.php) you can click on the 'Close' button or <a href='../manager/'>click here</a>.";

if($installMode==0) {
	echo "<p /><b>Note:</b> After logging into the manager you must edit and save your system configuration settings before browsing the site.";
}

echo "<p />&nbsp;";

// close db connection
$sqlParser->close();


		
?>

