<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if(!$modx->hasPermission('edit_user') && $_REQUEST['a']==75) {
	$e->setError(3);
	$e->dumpError();
}

// get search string
$query = $_REQUEST['search'];
$sqlQuery = mysql_escape_string($query);

// context menu
include_once $base_path."manager/includes/controls/contextmenu.php";
$cm = new ContextMenu("cntxm", 150);
$dir="style/".($manager_theme ? "$manager_theme/":"");
$cm->addItem($_lang["edit"],"js:menuAction(1)","media/".$dir."images/icons/logging.gif",(!$modx->hasPermission('edit_user') ? 1:0));
$cm->addItem($_lang["delete"], "js:menuAction(2)","media/".$dir."images/icons/delete.gif",(!$modx->hasPermission('delete_user') ? 1:0));
echo $cm->render();

?>

<div class="subTitle">
	<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['role_management_title']; ?></span>
</div>

<!-- User Roles -->
<div class="sectionHeader"><?php echo $_lang['role_management_title']; ?></div><div class="sectionBody">
<p><?php echo $_lang['role_management_msg']; ?></p>

<ul>
	<li><a href="index.php?a=38"><?php echo $_lang['new_role']; ?></a></li>
</ul>
<br />
<ul>
<?php

$sql = "select name, id, description from $dbase.".$table_prefix."user_roles order by name";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit<1){
	echo "The request returned no roles!</div>";
	exit;
	include_once "footer.inc.php";
}
for($i=0; $i<$limit; $i++) {
	$row = mysql_fetch_assoc($rs);
	if($row['id']==1) {
?>
	<li><span style="width: 200px"><i><?php echo $row['name']; ?></i></span> - <i><?php echo $_lang['administrator_role_message']; ?></i></li>
<?php
	} else {
?>
	<li><span style="width: 200px"><a href="index.php?id=<?php echo $row['id']; ?>&a=35"><?php echo $row['name']; ?></a></span> - <?php echo $row['description']; ?></li>
<?php
	}
}

?>
</ul>
</div>
