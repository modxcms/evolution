<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('bk_manager')) {
	$e->setError(3);
	$e->dumpError();
}

// Backup Manager by Raymond:

$mode = isset($_POST['mode'])? $_POST['mode']:"";

function callBack(&$dumpstring){
	$today = date("d_M_y");
	$today = strtolower($today);
	if(!headers_sent()) {
		header('Content-type: application/download');
		header('Content-Disposition: attachment; filename='.$today.'_database_backup.sql');
	}
	echo $dumpstring;
	return true;
}

if ($mode=='backup') {
	$tables = isset($_POST['chk'])? $_POST['chk']:"";
	if (!is_array($tables)) {
		echo "<html>";
		echo "<body>";
		echo "<script type=\"text/javascript\">alert('Please select a valid table from the list below');</script>";
		echo "</body>";
		echo "</html>";
		exit;
	}

	/*
	 * Code taken from Ralph A. Dahlgren MySQLdumper Snippet - Etomite 0.6 - 2004-09-27
	 * Modified by Raymond 3-Jan-2005
	 * Perform MySQLdumper data dump
	 */

	@set_time_limit(120); // set timeout limit to 2 minutes
	$dbname = str_replace("`","",$dbase);
	$dumper = new Mysqldumper($database_server, $database_user, $database_password, $dbname); # Variables have replaced original hard-coded values
	$dumper->setDBtables($tables);
	$dumper->setDroptables((isset($_POST['droptables'])? true:false));
	$dumpfinished = $dumper->createDump("callBack");
	if($dumpfinished) exit;
	else {
		$e->setError(1,"Unable to Backup Database");
		$e->dumpError();
		exit;
	}

	// MySQLdumper class can be found below
}
else {
	include_once "header.inc.php";	 // start normal header
}

?>
<div class="subTitle">
<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang["bk_manager"]; ?></span>
</div>

<div class="sectionHeader"><?php echo $_lang['database_tables']; ?></div><div class="sectionBody" id="lyr4">
<form name='frmdb' method='post'>
<input type='hidden' name='mode' value='' />
<script language='javascript'>
	function selectAll(){
		var f = document.forms['frmdb'];
		var c = f.elements['chk[]'];
		for(i=0;i<c.length;i++){
			c[i].checked=f.chkselall.checked;
		}
	}
	function submitForm(){
		var f = document.forms['frmdb'];
		f.mode.value='backup';
		f.target='fileDownloader';
		f.submit();
		return false;
	}

</script>
		<?php echo $_lang['table_hoverinfo']; ?><p />
		<span style='width:100%;'><a href='#' onclick='submitForm();return false;'><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/ed_save.gif' border=0 align='absmiddle'><?php echo $_lang["database_table_clickhere"]; ?></a> <?php echo $_lang["database_table_clickbackup"]; ?></span><br />&nbsp;&nbsp;&nbsp;
		<input type='checkbox' name='droptables'> <?php echo $_lang["database_table_droptablestatements"]; ?><p />
		<table border="0" cellpadding="1" cellspacing="1" width="100%" bgcolor="#707070">
		 <thead>
		 <tr>
			<td width="160"><input type=checkbox name='chkselall' onclick='selectAll()' title='Select All Tables' /><b><?php echo $_lang["database_table_tablename"]; ?></b></td>
			<td width="40" align="right"><b><?php echo $_lang["database_table_records"]; ?></b></td>
			<td width="120" align="right"><b><?php echo $_lang["database_table_datasize"]; ?></b></td>
			<td width="120" align="right"><b><?php echo $_lang["database_table_overhead"]; ?></b></td>
			<td width="120" align="right"><b><?php echo $_lang["database_table_effectivesize"]; ?></b></td>
			<td width="120" align="right"><b><?php echo $_lang["database_table_indexsize"]; ?></b></td>
			<td width="120" align="right"><b><?php echo $_lang["database_table_totalsize"]; ?></b></td>
		  </tr>
		  </thead>
		  <tbody>
<?php

	function nicesize($size) {
		$a = array("B", "KB", "MB", "GB", "TB", "PB");

		$pos = 0;
		while ($size >= 1024) {
			   $size /= 1024;
			   $pos++;
		}
		if($size==0) {
			return "-";
		} else {
			return round($size,2)." ".$a[$pos];
		}
	}

	$sql = "SHOW TABLE STATUS FROM $dbase;";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	for ($i = 0; $i < $limit; $i++) {
		$db_status = mysql_fetch_assoc($rs);
		$bgcolor = ($i % 2) ? '#EEEEEE' : '#FFFFFF';
?>
		  <tr bgcolor="<?php echo $bgcolor; ?>" title="<?php echo $db_status['Comment']; ?>" style="cursor:default">
			<td><input type=checkbox name='chk[]' value='<?php echo $db_status['Name']; ?>' <?php if(isset($tables)) echo strstr(implode(",",$tables),$db_status['Name'])===false ? "":" checked='checked'";?>><b style="color:#009933"><?php echo $db_status['Name']; ?></b></td>
			<td align="right"><?php echo $db_status['Rows']; ?></td>
<?php
	// enable record deletion for certain tables
	// sottwell@sottwell.com
	// 08-2005
	if($modx->hasPermission('settings') && ($db_status['Name'] == "`" . $table_prefix."event_log`" || $db_status['Name'] == "`" . $table_prefix."log_access`" || $db_status['Name'] == "`" . $table_prefix."log_hosts`" || $db_status['Name'] == "`".$table_prefix."log_visitors`" || $db_status['Name'] == "`" . $table_prefix."manager_log`")) {
		echo "<td dir='ltr' align='right'>";
		echo "<a href='index.php?a=54&mode=$action&u=".$db_status['Name']."' title='".$_lang['truncate_table']."'>".nicesize($db_status['Data_length']+$db_status['Data_free'])."</a>";
		echo "</td>";
	}
	else {
		echo "<td dir='ltr' align='right'>".nicesize($db_status['Data_length']+$db_status['Data_free'])."</td>";
	}

	if($modx->hasPermission('settings')) {
		echo  "<td align='right'>".($db_status['Data_free']>0 ? "<a href='index.php?a=54&mode=$action&t=".$db_status['Name']."' title='".$_lang['optimize_table']."' ><span dir='ltr'>".nicesize($db_status['Data_free'])."</a>" : "-")."</span></td>";
	}
	else {
		echo  "<td align='right'>".($db_status['Data_free']>0 ? nicesize($db_status['Data_free']) : "-")."</td>";
	}
?>
			<td dir='ltr' align="right"><?php echo nicesize($db_status['Data_length']-$db_status['Data_free']); ?></td>
			<td dir='ltr' align="right"><?php echo nicesize($db_status['Index_length']); ?></td>
			<td dir='ltr' align="right"><?php echo nicesize($db_status['Index_length']+$db_status['Data_length']+$db_status['Data_free']); ?></td>
		  </tr>
<?php
	$total = $total+$db_status['Index_length']+$db_status['Data_length'];
	$totaloverhead = $totaloverhead+$db_status['Data_free'];
	}
?>
		  <tr bgcolor="#CCCCCC">
  			<td valign="top"><b><?php echo $_lang['database_table_totals']; ?></b></td>
			<td colspan="2">&nbsp;</td>
			<td dir='ltr' align="right" valign="top"><?php echo $totaloverhead>0 ? "<b style='color:#990033'>".nicesize($totaloverhead)."</b><br>(".number_format($totaloverhead)." B)" : "-"; ?></td>
			<td colspan="2">&nbsp;</td>
			<td dir='ltr' align="right" valign="top"><?php echo "<b>".nicesize($total)."</b><br>(".number_format($total)." B)"; ?></td>
		  </tr>
		  </tbody>
		</table>
<?php
	if($totaloverhead>0) { ?>
		<p><?php echo $_lang['database_overhead']; ?></p>
<?php } ?>
</form>
</div>
<!-- This iframe is used when downloading file backup file -->
<iframe name="fileDownloader" width="1" height="1" style="display:none; width:1px; height:1px;"></iframe>

<?php include_once "footer.inc.php"; // send footer ?>

<?php
/*
* @package  MySQLdumper
* @version  1.0
* @author   Dennis Mozes <opensource@mosix.nl>
* @url		http://www.mosix.nl/mysqldumper
* @since    PHP 4.0
* @copyright Dennis Mozes
* @license GNU/LGPL License: http://www.gnu.org/copyleft/lgpl.html
*
* Modified by Raymond for use with this module
*
**/
class Mysqldumper {
	var $_host;
	var $_dbuser;
	var $_dbpassword;
	var $_dbname;
	var $_dbtables;
	var $_isDroptables;
    var $_dbcharset;

	function Mysqldumper($host = "localhost", $dbuser = "", $dbpassword = "", $dbname = "", $connection_charset= "utf8") {
		$this->setHost($host);
		$this->setDBuser($dbuser);
		$this->setDBpassword($dbpassword);
		$this->setDBname($dbname);
        $this->setDBcharset($connection_charset);
		// Don't drop tables by default.
		$this->setDroptables(false);
	}

	function setHost($host) {
		$this->_host = $host;
	}

	function getHost() {
		return $this->_host;
	}

	function setDBname($dbname) {
		$this->_dbname = $dbname;
	}

	function getDBname() {
		return $this->_dbname;
	}

	function getDBuser() {
		return $this->_dbuser;
	}

	function setDBpassword($dbpassword) {
		$this->_dbpassword = $dbpassword;
	}

	function getDBpassword() {
		return $this->_dbpassword;
	}

	function setDBuser($dbuser) {
		$this->_dbuser = $dbuser;
	}

	function setDBtables($dbtables) {
		$this->_dbtables = $dbtables;
	}

	// If set to true, it will generate 'DROP TABLE IF EXISTS'-statements for each table.
	function setDroptables($state) {
		$this->_isDroptables = $state;
	}

	function isDroptables() {
		return $this->_isDroptables;
	}

    function setDBcharset($dbcharset) {
        $this->_dbcharset = $dbcharset;
    }

    function getDBcharset() {
        return $this->_dbcharset;
    }

	function createDump($callBack) {

		global $site_name,$full_appname;

		// Set line feed
		$lf = "\n";

		$resource = mysql_connect($this->getHost(), $this->getDBuser(), $this->getDBpassword());
		mysql_select_db($this->getDBname(), $resource);
        $database_connection_charset= $this->getDBcharset();
        @mysql_query("SET CHARACTER SET {$database_connection_charset}");
		$result = mysql_query("SHOW TABLES",$resource);
		$tables = $this->result2Array(0, $result);
		foreach ($tables as $tblval) {
			$result = mysql_query("SHOW CREATE TABLE `$tblval`");
			$createtable[$tblval] = $this->result2Array(1, $result);
		}
		// Set header
		$output = "#". $lf;
		$output .= "# ".addslashes($site_name)." Database Dump" . $lf;
		$output .= "# ".$full_appname.$lf;
		$output .= "# ". $lf;
		$output .= "# Host: " . $this->getHost() . $lf;
		$output .= "# Generation Time: " . date("M j, Y at H:i") . $lf;
		$output .= "# Server version: ". mysql_get_server_info() . $lf;
		$output .= "# PHP Version: " . phpversion() . $lf;
		$output .= "# Database : `" . $this->getDBname() . "`" . $lf;
		$output .= "#";

		// Generate dumptext for the tables.
		if (isset($this->_dbtables) && count($this->_dbtables)) {
			$this->_dbtables = implode(",",$this->_dbtables);
		}
		else {
			unset($this->_dbtables);
		}
		foreach ($tables as $tblval) {
			// check for selected table
			if(isset($this->_dbtables)) {
				if (strstr(",".$this->_dbtables.",",",$tblval,")===false) {
					continue;
				}
			}
			$output .= $lf . $lf . "# --------------------------------------------------------" . $lf . $lf;
			$output .= "#". $lf . "# Table structure for table `$tblval`" . $lf;
			$output .= "#" . $lf . $lf;
			// Generate DROP TABLE statement when client wants it to.
			if($this->isDroptables()) {
				$output .= "DROP TABLE IF EXISTS `$tblval`;" . $lf;
			}
			$output .= $createtable[$tblval][0].";" . $lf;
			$output .= $lf;
			$output .= "#". $lf . "# Dumping data for table `$tblval`". $lf . "#" . $lf;
			$result = mysql_query("SELECT * FROM `$tblval`");
			$rows = $this->loadObjectList("", $result);
			foreach($rows as $row) {
				$insertdump = $lf;
				$insertdump .= "INSERT INTO `$tblval` VALUES (";
				$arr = $this->object2Array($row);
				foreach($arr as $key => $value) {
					$value = addslashes($value);
					$value = str_replace("\n", '\\r\\n', $value);
					$value = str_replace("\r", '', $value);
					$insertdump .= "'$value',";
				}
				$output .= rtrim($insertdump,',') . ");";
			}
			// invoke callback -- raymond
			if ($callBack) {
				if (!$callBack($output)) break;
				$output = "";
			}
		}
		mysql_close($resource);
		return ($callBack) ? true: $output;
	}

	// Private function object2Array.
	function object2Array($obj) {
		$array = null;
		if(is_object($obj)) {
			$array = array();
			foreach (get_object_vars($obj) as $key => $value) {
				if(is_object($value))
					$array[$key] = $this->object2Array($value);
				else
					$array[$key] = $value;
			}
		}
		return $array;
	}

	// Private function loadObjectList.
	function loadObjectList($key='', $resource) {
		$array = array();
		while ($row = mysql_fetch_object($resource)) {
			if ($key)
				$array[$row->$key] = $row;
			else
				$array[] = $row;
		}
		mysql_free_result($resource);
		return $array;
	}

	// Private function result2Array.
	function result2Array($numinarray = 0, $resource) {
		$array = array();
		while ($row = mysql_fetch_row($resource)) {
			$array[] = $row[$numinarray];
		}
		mysql_free_result($resource);
		return $array;
	}
}

?>
