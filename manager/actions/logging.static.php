<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('logs')) {
	$e->setError(3);
	$e->dumpError();
}?>
<div class="subTitle">
<span class="right"><?php echo $_lang["mgrlog_view"]; ?></span>
</div>

<div class="sectionHeader"><?php echo $_lang["mgrlog_query"]; ?></div><div class="sectionBody" id="lyr1">
<p><?php echo $_lang["mgrlog_query_msg"]; ?></p>
<form action='index.php?a=13' name="logging" method='POST'>
<table border=0 cellpadding=2 cellspacing=0>
 <thead>
  <tr>
    <td width="200"><b><?php echo $_lang["mgrlog_field"]; ?></b></td>
    <td align="right"><b><?php echo $_lang["mgrlog_value"]; ?></b></td>
  </tr>
 </thead>
 </tbody>
  <tr>
    <td><b><?php echo $_lang["mgrlog_user"]; ?>
    </b></td>
    <td align="right">
      <?php
// get all users currently in logging
$sql = "SELECT DISTINCT(username) AS name, internalKey FROM $dbase.`".$table_prefix."manager_log`";
$rs = mysql_query($sql);
?>
		<select name="searchuser" class="inputBox" style="width:240px">
		<option value="0"><?php echo $_lang["mgrlog_anyall"]; ?></option>
		<?php
		while ($row = mysql_fetch_assoc($rs)) {
			$selectedtext = $row['internalKey']==$_REQUEST['searchuser'] ? "selected='selected'" : "" ;
			?>
				<option value="<?php echo $row['internalKey']; ?>" <?php echo $selectedtext; ?>><?php echo $row['name']; ?></option>
			<?php
		}
		?>
		</select>
    </td>
  </tr>
  <tr bgcolor='#eeeeee'>
    <td><b><?php echo $_lang["mgrlog_action"]; ?></b></td>
    <td align="right">
      <select name="action" class='inputBox' style='width:240px;'>
        <option value="0"><?php echo $_lang["mgrlog_anyall"]; ?></option>
        <?php
include_once "actionlist.inc.php";
for($i = 1; $i < 1000; $i++) {
	$actionname = getAction($i);
	if($actionname!="Idle") {
		$actions[$i] = $actionname;
		$selectedtext = $i==$_REQUEST['action'] ? "selected='selected'" : "" ;
		echo "\t\t\t<option value='$i' $selectedtext>$i - $actionname</option>/n";
	}
}
?>
      </select>
    </td>
  </tr>
  <tr bgcolor='#ffffff'>
    <td><b><?php echo $_lang["mgrlog_itemid"]; ?></b></td>
    <td align="right">
      <?php
// get all itemid currently in logging
$sql = "SELECT DISTINCT(itemid) AS item, itemid FROM $dbase.`".$table_prefix."manager_log`";
$rs = mysql_query($sql);
?>
		<select name="itemid" class="inputBox" style="width:240px">
		<option value="0"><?php echo $_lang["mgrlog_anyall"]; ?></option>
		<?php
		while ($row = mysql_fetch_assoc($rs)) {
			$selectedtext = $row['itemid']==$_REQUEST['itemid'] ? "selected='selected'" : "" ;
			?>
				<option value="<?php echo $row['itemid']; ?>" <?php echo $selectedtext; ?>><?php echo $row['item']; ?></option>
			<?php
		}
		?>
		</select>
    </td>
  </tr>
  <tr bgcolor='#eeeeee'>
    <td><b><?php echo $_lang["mgrlog_itemname"]; ?></b></td>
    <td align="right">
      <?php
// get all itemname currently in logging
$sql = "SELECT DISTINCT(itemname), itemname FROM $dbase.`".$table_prefix."manager_log`";
$rs = mysql_query($sql);
?>
		<select name="itemname" class="inputBox" style="width:240px">
		<option value="0"><?php echo $_lang["mgrlog_anyall"]; ?></option>
		<?php
		while ($row = mysql_fetch_assoc($rs)) {
			$selectedtext = $row['itemname']==$_REQUEST['itemname'] ? "selected='selected'" : "" ;
			?>
				<option value="<?php echo $row['itemname']; ?>" <?php echo $selectedtext; ?>><?php echo $row['itemname']; ?></option>
			<?php
		}
		?>
		</select>
    </td>
  </tr>
  <tr bgcolor='#ffffff'>
    <td><b><?php echo $_lang["message_message"]; ?></b></td>
    <td align="right">
      <input type=text name='message' class="inputbox" style="width:240px" value="<?php echo $_REQUEST['message']; ?>">
    </td>
  </tr>
  <script language="JavaScript" src="media/script/datefunctions.js"></script>
  <tr bgcolor='#eeeeee'>
    <td><b><?php echo $_lang["mgrlog_datefr"]; ?></b></td>
        <td align="right"><input type=hidden name='datefrom' class="inputbox" style="width:100px" value="<?php echo isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : "" ; ?>">
          <span id="datefrom_show" style="font-weight: bold;"><?php echo isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : "<i>(not set)</i>" ; ?></span>
		  <a href="javascript:cal1.popup();" onMouseover="window.status='Select a date'; return true;" onMouseout="window.status=''; return true;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal.gif" width="16" height="16" border="0" align="absimddle"></a>
		  <a onClick="document.logging.datefrom.value=''; document.getElementById('datefrom_show').innerHTML='(not set)'; return true;" onMouseover="window.status='Don\'t set a date'; return true;" onMouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="No date"></a>
	  </td>
  </tr>
  <tr bgcolor='#ffffff'>
    <td><b><?php echo $_lang["mgrlog_dateto"]; ?></b></td>
    <td align="right">
		  <input type=hidden name='dateto' class="inputbox" style="width:100px" value="<?php echo isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : "" ; ?>">
          <span id="dateto_show" style="font-weight: bold;"><?php echo isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : "<i>(not set)</i>" ; ?></span>
		  <a href="javascript:cal2.popup();" onMouseover="window.status='Select a date'; return true;" onMouseout="window.status=''; return true;"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal.gif" width="16" height="16" border="0" align="absimddle"></a>
		  <a onClick="document.logging.dateto.value=''; document.getElementById('dateto_show').innerHTML='(not set)'; return true;" onMouseover="window.status='Don\'t set a date'; return true;" onMouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="No date"></a>
		 </td>
      </tr>
  </tr>
  <tr bgcolor='#eeeeee'>
    <td><b><?php echo $_lang["mgrlog_results"]; ?></b></td>
    <td align="right">
      <input type=text name='nrresults' class="inputbox" style="width:100px" value="<?php echo isset($_REQUEST['nrresults']) ? $_REQUEST['nrresults'] : $number_of_logs; ?>"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="18" height="16" border="0">
    </td>
  </tr>
  <tr bgcolor='#FFFFFF'>
    <td colspan="2">
	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<tr>
			<td id="Button1"><a href="#" onclick="document.logging.log_submit.click();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle"> <?php echo $_lang['search']; ?></a></td>
			<td id="Button2"><a href="index.php?a=13"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['cancel']; ?></span></a></td>
		</tr>
	</table>
      <input type='submit' name='log_submit' value='<?php echo $_lang["mgrlog_searchlogs"]?>' style="display:none">
    </td>
  </tr>
  </tbody>
</table>
</form>
</div>

<script type="text/javascript">
    var cal1 = new calendar1(document.forms['logging'].elements['datefrom'], document.getElementById("datefrom_show"));
    cal1.path="<?php echo str_replace("index.php", "media/", $_SERVER["PHP_SELF"]); ?>";
    cal1.year_scroll = true;
    cal1.time_comp = false;

    var cal2 = new calendar1(document.forms['logging'].elements['dateto'], document.getElementById("dateto_show"));
    cal2.path="<?php echo str_replace("index.php", "media/", $_SERVER["PHP_SELF"]); ?>";
    cal1.year_scroll = true;
    cal1.time_comp = false;
</script>


<div class="sectionHeader"><?php echo $_lang["mgrlog_qresults"]; ?></div><div class="sectionBody" id="lyr2">
<?php
if(isset($_REQUEST['log_submit'])){

// function to check date and convert to us date
function convertdate($date) {
	global $_lang;
	list ($day, $month, $year) = split ("-", $date);
	$date_valid = checkdate($month, $day, $year);
	if($date_valid==false) {
		echo $_lang["mgrlog_datecheckfalse"];
		exit;
	}
	if (($timestamp = strtotime("$month/$day/$year")) === -1) {
		echo $_lang["mgrlog_dateinvalid"];
		exit;
	} else {
	   return $timestamp;
	}
}


function isNumber($var)
{
	if(strlen($var)==0) {
		return false;
	}
	for ($i=0;$i<strlen($var);$i++) {
		if ( substr_count ("0123456789", substr ($var, $i, 1) ) == 0 ) {
			return false;
		}
	}
	return true;
}

// get the selections the user made.
$sqladd ="";
if($_REQUEST['searchuser']!=0) $sqladd .= " AND internalKey='".intval($_REQUEST['searchuser'])."'";
if($_REQUEST['action']!=0) $sqladd .= " AND action=".intval($_REQUEST['action']);
if($_REQUEST['itemid']!=0 || $_REQUEST['itemid']=="-") $sqladd .= " AND itemid='".$_REQUEST['itemid']."'";
if($_REQUEST['itemname']!='0') $sqladd .= " AND itemname='".mysql_escape_string($_REQUEST['itemname'])."'";  // hier
if($_REQUEST['message']!="") $sqladd .= " AND message LIKE '%".mysql_escape_string($_REQUEST['message'])."%'";
// date stuff
if($_REQUEST['datefrom']!="") $sqladd .= " AND timestamp>".convertdate($_REQUEST['datefrom']);
if($_REQUEST['dateto']!="") $sqladd .= " AND timestamp<".convertdate($_REQUEST['dateto']);

// Get  number of rows
$sql = "SELECT count(id) FROM $dbase.`".$table_prefix."manager_log` WHERE 1=1";
$sql .= $sqladd;
//echo "<fieldset>".$sql."</fieldset>";
$rs=mysql_query($sql);
$countrows = mysql_fetch_assoc($rs);
$num_rows = $countrows['count(id)'];

// ==============================================================
// Exemple Usage
// Note: I make 2 query to the database for this exemple, it
// could (and should) be made with only one query...
// ==============================================================

// If current position is not set, set it to zero
if( !isset( $_REQUEST['int_cur_position'] ) || $_REQUEST['int_cur_position'] == 0 ){
  $int_cur_position = 0;
} else {
	$int_cur_position = $_REQUEST['int_cur_position'];
}

// Number of result to display on the page, will be in the LIMIT of the sql query also
$int_num_result = isNumber($_REQUEST['nrresults']) ? $_REQUEST['nrresults'] : $number_of_logs ;


$extargv = 	"&a=13&searchuser=".$_REQUEST['searchuser']."&action=".$_REQUEST['action'].
			"&itemid=".$_REQUEST['itemid']."&itemname=".$_REQUEST['itemname']."&message=".
			$_REQUEST['message']."&dateto=".$_REQUEST['dateto']."&datefrom=".
			$_REQUEST['datefrom']."&nrresults=".$int_num_result."&log_submit=".$_REQUEST['log_submit']; // extra argv here (could be anything depending on your page)


// build the sql
$sql = "SELECT * FROM $dbase.`".$table_prefix."manager_log` WHERE 1=1";
$sql .= $sqladd;

$sql .= " LIMIT ".$int_cur_position.", ".$int_num_result;
//echo "<fieldset>".$sql."</fieldset>";

		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		if($limit<1) {
			echo '<p>'.$_lang["mgrlog_emptysrch"].'</p>
			</div></div>';
			exit;
		} else {
            echo '<p>'.$_lang["mgrlog_sortinst"].'</p>';

include_once "paginate.inc.php";
// New instance of the Paging class, you can modify the color and the width of the html table
$p = new Paging( $num_rows, $int_cur_position, $int_num_result, $extargv );

// Load up the 2 array in order to display result
$array_paging = $p->getPagingArray();
$array_row_paging = $p->getPagingRowArray();
$current_row = $int_cur_position/$int_num_result;

// Display the result as you like...
print $_lang["paging_showing"]." ". $array_paging['lower'];
print " ". $_lang["paging_to"] . " ". $array_paging['upper'];
print " (". $array_paging['total'] . " " . $_lang["paging_total"] . ")";
print "<br>". $array_paging['first_link'] . $_lang["paging_first"] . "</a> " ;
print $array_paging['previous_link'] . $_lang["paging_prev"] . "</a> " ;
$pagesfound = sizeof($array_row_paging);
if($pagesfound>6) {
	print $array_row_paging[$current_row-1]; // ."&nbsp;";
	print $array_row_paging[$current_row]; // ."&nbsp;";
	print $array_row_paging[$current_row+1]; // ."&nbsp;";
} else {
	for( $i=0; $i<$pagesfound; $i++ ){
	  print $array_row_paging[$i] ."&nbsp;";
	}
}
print $array_paging['next_link'] . $_lang["paging_next"] . "</a> ";
print $array_paging['last_link'] . $_lang["paging_last"] . "</a>";
// The above exemple print somethings like:
// Results 1 to 20 of 597  <<< 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 >>>
// Of course you can now play with array_row_paging in order to print
// only the results you would like...
?>
<P>
	<script type="text/javascript" src="media/script/tablesort.js"></script>
  <table border=0 cellpadding="2" cellspacing="1" bgcolor="#707070" class="sortabletable sortable-onload-6 rowstyle-even" id="table-1" width="95%">
    <thead>
      <tr>
        <th class="sortable"><b><?php echo $_lang["mgrlog_username"]; ?></b></th>
        <th class="sortable"><b><?php echo $_lang["mgrlog_actionid"]; ?></b></th>
        <th class="sortable"><b><?php echo $_lang["mgrlog_itemid"]; ?></b></th>
        <th class="sortable"><b><?php echo $_lang["mgrlog_itemname"]; ?></b></th>
        <th class="sortable"><b><?php echo $_lang["mgrlog_msg"]; ?></b></th>
        <th class="sortable"><b><?php echo $_lang["mgrlog_time"]; ?></b></th>
      </tr>
    </thead>
    <tbody>
     <?php
			for ($i = 0; $i < $limit; $i++) {
				$logentry = mysql_fetch_assoc($rs);
?>
    <tr>
      <td><?php echo ucfirst($logentry['username'])." (".$logentry['internalKey'].")"; ?></td>
	  <td><?php echo $logentry['action']; ?></td>
      <td><?php echo $logentry['itemid']=="-" ? "" : $logentry['itemid'] ; ?></td>
      <td><?php echo $logentry['itemname']; ?></td>
      <td><?php echo $logentry['message']; ?></td>
      <td><?php echo strftime('%d-%m-%y, %H:%M:%S', $logentry['timestamp']+$server_offset_time); ?></td>
    </tr>
    <?php
			}
		}
?>
    </tbody>
     </table>
</div>
<?php
} else {
    echo $_lang["mgrlog_noquery"];
}
?>
</div>

</div>
