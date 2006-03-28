<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('messages') && $_REQUEST['a']==10) {
	$e->setError(3);
	$e->dumpError();
}
?>
<div class="subTitle">
<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['messages_title']; ?></span>
</div>

<?php if(isset($_REQUEST['id']) && $_REQUEST['m']=='r') { ?>
<div class="sectionHeader"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['messages_read_message']; ?></div><div class="sectionBody" id="lyr3">
<?php
$sql = "SELECT * FROM $dbase.".$table_prefix."user_messages WHERE $dbase.".$table_prefix."user_messages.id=".$_REQUEST['id'];
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit!=1) {
	echo "Wrong number of messages returned!";
} else {
	$message=mysql_fetch_assoc($rs);
	if($message['recipient']!=$modx->getLoginUserID()) {
		echo $_lang['messages_not_allowed_to_read'];
	} else {
		// output message!
		// get the name of the sender
		$sender = $message['sender'];
		if($sender==0) {
			$sendername = $_lang['messages_system_user'];
		} else {
			$sql = "SELECT username FROM $dbase.".$table_prefix."manager_users WHERE id=$sender";
			$rs2 = mysql_query($sql);
			$row2 = mysql_fetch_assoc($rs2);
			$sendername = $row2['username'];
		}
?>
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2">
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td id="Button11" onclick="document.location.href='index.php?a=10&t=c&m=rp&id=<?php echo $message['id']; ?>';"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/reply.gif" align="absmiddle"> <?php echo $_lang['messages_reply']; ?></td>
				<script>createButton(document.getElementById("Button11"));</script>
<?php if($message['sender']==0) { ?>				<script>document.getElementById("Button11").setEnabled(false);</script><?php } ?>
			<td id="Button21" onclick="document.location.href='index.php?a=10&t=c&m=f&id=<?php echo $message['id']; ?>';"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/forward.gif" align="absmiddle"> <?php echo $_lang['messages_forward']; ?></span></td>
				<script>createButton(document.getElementById("Button21"));</script>
			<td id="Button31" onclick="document.location.href='index.php?a=65&id=<?php echo $message['id']; ?>';"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle"> <?php echo $_lang['delete']; ?></span></td>
				<script>createButton(document.getElementById("Button31"));</script>
		</tr>
	</table>
	</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td style="width: 120px;"><b><?php echo $_lang['messages_from']; ?>:</b></td>
    <td style="width: 480px;"><?php echo $sendername; ?></td>
  </tr>
  <tr>
    <td><b><?php echo $_lang['messages_sent']; ?>:</b></td>
    <td><?php echo strftime('%d-%m-%y, %H:%M:%S', $message['postdate']+$server_offset_time); ?></td>
  </tr>
  <tr>
    <td><b><?php echo $_lang['messages_subject']; ?>:</b></td>
    <td><?php echo $message['subject']; ?></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
	<?php
	// format the message :)
	$message = str_replace ("\n", "<br>", $message['message']);
	$dashcount = substr_count($message, "-----");
	$message = str_replace ("-----", "<i style='color:#666;'>", $message);
	for( $i=0; $i<$dashcount; $i++ ){
	$message .= "</i>";
	}

	echo $message;
	?>

	</td>
  </tr>
</table>
<?php
		// mark the message as read
		$sql = "UPDATE $dbase.".$table_prefix."user_messages SET $dbase.".$table_prefix."user_messages.messageread=1 WHERE $dbase.".$table_prefix."user_messages.id=".$_REQUEST['id'];
		$rs = mysql_query($sql);
	}
}
?>
	</div>
<?php } ?>


<div class="sectionHeader"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['messages_inbox']; ?></div><div class="sectionBody">
<?php
// Get  number of rows
$sql = "SELECT count(id) FROM $dbase.".$table_prefix."user_messages WHERE recipient=".$modx->getLoginUserID()."";
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
$int_num_result = $number_of_messages;


$extargv = 	"&a=10"; // extra argv here (could be anything depending on your page)

include_once "paginate.inc.php";
// New instance of the Paging class, you can modify the color and the width of the html table
$p = new Paging( $num_rows, $int_cur_position, $int_num_result, $extargv );

// Load up the 2 array in order to display result
$array_paging = $p->getPagingArray();
$array_row_paging = $p->getPagingRowArray();

// Display the result as you like...
$pager .= $_lang['showing']." ". $array_paging['lower'];
$pager .=  " ".$_lang['to']." ". $array_paging['upper'];
$pager .=  " (". $array_paging['total']." ".$_lang['total'].")";
$pager .=  "<br>". $array_paging['previous_link'] ."<<</a> " ;
for( $i=0; $i<sizeof($array_row_paging); $i++ ){
  $pager .=  $array_row_paging[$i] ."&nbsp;";
}
$pager .=  $array_paging['next_link'] .">></a>";

// The above exemple print somethings like:
// Results 1 to 20 of 597  <<< 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 >>>
// Of course you can now play with array_row_paging in order to print
// only the results you would like...

$sql = "SELECT * FROM $dbase.".$table_prefix."user_messages WHERE $dbase.".$table_prefix."user_messages.recipient=".$modx->getLoginUserID()." ORDER BY postdate DESC LIMIT ".$int_cur_position.", ".$int_num_result;
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit<1) {
	echo $_lang['messages_no_messages'];
} else {
echo $pager;
$dotablestuff = 1;
?>
<script type="text/javascript" src="media/script/sortabletable.js"></script>
  <table border=0 cellpadding=0 cellspacing=0  class="sort-table" id="table-1" width='100%'>
    <thead>
      <tr bgcolor='#CCCCCC'>
        <td width="12"></td>
        <td width="60%"><b><?php echo $_lang['messages_subject']; ?></b></td>
        <td><b><?php echo $_lang['messages_from']; ?></b></td>
        <td><b><?php echo $_lang['messages_private']; ?></b></td>
		<td width="20%"><b><?php echo $_lang['messages_sent']; ?></b></td>
      </tr>
    </thead>
    <tbody>
<?php
		for ($i = 0; $i < $limit; $i++) {
			$message = mysql_fetch_assoc($rs);
			$sender = $message['sender'];
			if($sender==0) {
				$sendername = "[System]";
			} else {
				$sql = "SELECT username FROM $dbase.".$table_prefix."manager_users WHERE id=$sender";
				$rs2 = mysql_query($sql);
				$row2 = mysql_fetch_assoc($rs2);
				$sendername = $row2['username'];
			}
			$classname = ($i % 2) ? 'class="even" ' : 'class="odd" ';
			$messagestyle = $message['messageread']==0 ? "messageUnread" : "messageRead";
?>
    <tr <?php echo $classname; ?>>
	  <td ><?php echo $message['messageread']==0 ? "<img src='media/images/icons/new1-09.gif'>" : ""; ?></td>
      <td class="<?php echo $messagestyle; ?>" style="cursor: pointer; text-decoration: underline;" onClick="document.location.href='index.php?a=10&id=<?php echo $message['id']; ?>&m=r';"><?php echo $message['subject']; ?></td>
	  <td ><?php echo $sendername; ?></td>
	  <td ><?php echo $message['private']==0 ? "No" : "Yes"; ?></td>
      <td ><?php echo strftime('%d-%m-%y, %H:%M:%S', $message['postdate']+$server_offset_time); ?></td>
    </tr>
    <?php
			}
	}

if($dotablestuff==1) { ?>
</tbody>
</table>
<script type="text/javascript">

var st1 = new SortableTable(document.getElementById("table-1"),
	["None", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "CaseInsensitiveString", "None"]);

function addClassName(el, sClassName) {
	var s = el.className;
	var p = s.split(" ");
	var l = p.length;
	for (var i = 0; i < l; i++) {
		if (p[i] == sClassName)
			return;
	}
	p[p.length] = sClassName;
	el.className = p.join(" ");

}

function removeClassName(el, sClassName) {
	var s = el.className;
	var p = s.split(" ");
	var np = [];
	var l = p.length;
	var j = 0;
	for (var i = 0; i < l; i++) {
		if (p[i] != sClassName)
			np[j++] = p[i];
	}
	el.className = np.join(" ");
}

st1.onsort = function () {
	var rows = st1.tBody.rows;
	var l = rows.length;
	for (var i = 0; i < l; i++) {
		removeClassName(rows[i], i % 2 ? "odd" : "even");
		addClassName(rows[i], i % 2 ? "even" : "odd");
	}
};
</script>
<?php } ?>
	</div>

<div class="sectionHeader"><img src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['messages_compose']; ?></div><div class="sectionBody">
<?php
if(($_REQUEST['m']=='rp' || $_REQUEST['m']=='f') && isset($_REQUEST['id'])) {
	$sql = "SELECT * FROM $dbase.".$table_prefix."user_messages WHERE $dbase.".$table_prefix."user_messages.id=".$_REQUEST['id'];
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if($limit!=1) {
		echo "Wrong number of messages returned!";
	} else {
		$message=mysql_fetch_assoc($rs);
		if($message['recipient']!=$modx->getLoginUserID()) {
			echo $_lang['messages_not_allowed_to_read'];
		} else {
			// output message!
			// get the name of the sender
			$sender = $message['sender'];
			if($sender==0) {
				$sendername = "[System]";
			} else {
				$sql = "SELECT username FROM $dbase.".$table_prefix."manager_users WHERE id=$sender";
				$rs2 = mysql_query($sql);
				$row2 = mysql_fetch_assoc($rs2);
				$sendername = $row2['username'];
			}
			$subjecttext = $_REQUEST['m']=='rp' ? "Re: " : "Fwd: ";
			$subjecttext .= $message['subject'];
			$messagetext = "\n\n\n-----\n".$_lang['messages_from'].": $sendername\n".$_lang['messages_sent'].": ".strftime('%d-%m-%y, %H:%M:%S', $message['postdate']+$server_offset_time)."\n".$_lang['messages_subject'].": ".$message['subject']."\n\n".$message['message'];
			if($_REQUEST['m']=='rp') {
				$recipientindex = $message['sender'];
			}
		}
	}
}



?>

<script type="text/javascript">
function hideSpans(showSpan) {
	document.getElementById("userspan").style.display="none";
	document.getElementById("groupspan").style.display="none";
	document.getElementById("allspan").style.display="none";
	if(showSpan==1) {
		document.getElementById("userspan").style.display="block";
	}
	if(showSpan==2) {
		document.getElementById("groupspan").style.display="block";
	}
	if(showSpan==3) {
		document.getElementById("allspan").style.display="block";
	}
}
</script>
<form action="index.php?a=66" method="post" name="messagefrm">
<fieldset style="width: 600px;">
<LEGEND><b><?php echo $_lang['messages_send_to']; ?>:</b></LEGEND>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<INPUT TYPE=RADIO NAME="sendto" VALUE="u" checked onClick='hideSpans(1);'><?php echo $_lang['messages_user']; ?>&nbsp;&nbsp;&nbsp;
	<INPUT TYPE=RADIO NAME="sendto" VALUE="g" onClick='hideSpans(2);'><?php echo $_lang['messages_group']; ?>&nbsp;&nbsp;&nbsp;
	<INPUT TYPE=RADIO NAME="sendto" VALUE="a" onClick='hideSpans(3);'><?php echo $_lang['messages_all']; ?>&nbsp;&nbsp;<br />
<span id='userspan' style="display:block;"> <?php echo $_lang['messages_select_user']; ?>:&nbsp;
	<?php
	// get all usernames
	$sql = "SELECT username, id FROM $dbase.".$table_prefix."manager_users";
	$rs = mysql_query($sql);
	?>
	<select name="user" class="inputBox" style="width:150px">
	<?php
		while ($row = mysql_fetch_assoc($rs)) {
			?>
			<option value="<?php echo $row['id']; ?>" ><?php echo $row['username']; ?></option>
			<?php
		}
	?>
	</select>
</span>
<span id='groupspan' style="display:none;"> <?php echo $_lang['messages_select_group']; ?>:&nbsp;
	<?php
	// get all usernames
	$sql = "SELECT name, id FROM $dbase.".$table_prefix."user_roles";
	$rs = mysql_query($sql);
	?>
	<select name="group" class="inputBox" style="width:150px">
	<?php
	while ($row = mysql_fetch_assoc($rs)) {
		?>
		<option value="<?php echo $row['id']; ?>" ><?php echo $row['name']; ?></option>
		<?php
	}
	?>
</select>
</span>
<span id='allspan' style="display:none;">
</span>
	</td>
  </tr>
</table>
</fieldset>

<p>

<fieldset style="width: 600px;">
<LEGEND><b><?php echo $_lang['messages_message']; ?>:</b></LEGEND>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $_lang['messages_subject']; ?>:</td>
    <td><input name="messagesubject" type=text class="inputBox" style="width: 500px;" maxlength="60" value="<?php echo $subjecttext; ?>"></td>
  </tr>
  <tr>
    <td valign="top"><?php echo $_lang['messages_message']; ?>:</td>
    <td><textarea name="messagebody" style="width:500px; height: 200px;" onLoad="this.focus()" class="inputBox"><?php echo $messagetext; ?></textarea></td>
  </tr>
  <tr>
  	<td></td>
  </tr>
</table>
<p>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td id="Button1" onclick="documentDirty=false; document.messagefrm.submit();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle"> <?php echo $_lang['messages_send']; ?></td>
				<script>createButton(document.getElementById("Button1"));</script>
			<td id="Button2" onclick="document.location.href='index.php?a=10&t=c';"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang['cancel']; ?></span></td>
				<script>createButton(document.getElementById("Button2"));</script>
		</tr>
	</table>
</p>
</fieldset>
</form>
</div>

<?php
// count messages again, as any action on the messages page may have altered the message count
$sql="SELECT count(*) FROM $dbase.".$table_prefix."user_messages where recipient=".$modx->getLoginUserID()." and messageread=0;";
$rs = mysql_query($sql);
$row = mysql_fetch_assoc($rs);
$_SESSION['nrnewmessages'] = $row['count(*)'];
$sql="SELECT count(*) FROM $dbase.".$table_prefix."user_messages where recipient=".$modx->getLoginUserID()."";
$rs = mysql_query($sql);
$row = mysql_fetch_assoc($rs);
$_SESSION['nrtotalmessages'] = $row['count(*)'];
$messagesallowed = $modx->hasPermission('messages');
?>
<script type="text/javascript">
function msgCountAgain() {
	try {
		top.scripter.startmsgcount(<?php echo $_SESSION['nrnewmessages'] ; ?>,<?php echo $_SESSION['nrtotalmessages'] ; ?>,<?php echo $messagesallowed ? 1:0 ; ?>);
	} catch(oException) {
		vv = window.setTimeout('msgCountAgain()',1500);
	}
}

v = setTimeout('msgCountAgain()', 1500); // do this with a slight delay so it overwrites msgCount()

</script>
