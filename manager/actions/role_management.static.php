<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('edit_user')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

?>
<!-- User Roles -->

<h1><?php echo $_lang['role_management_title']; ?></h1>
<div class="section">
<div class="sectionBody">
<p><?php echo $_lang['role_management_msg']; ?></p>

<ul class="actionButtons">
	<li><a href="index.php?a=38"><?php echo $_lang['new_role']; ?></a></li>
</ul>
<?php

$rs = $modx->db->select('name, id, description', $modx->getFullTableName('user_roles'), '', 'name');
$limit = $modx->db->getRecordCount($rs);
if($limit<1){
	echo "<p>The request returned no roles!</p>";
} else {
	echo "<ul>";
	while ($row = $modx->db->getRow($rs)) {
		if($row['id']==1) {
			echo '<li><span style="width: 200px"><i>' . $row['name'] . '</i></span> - <i>' . $_lang['administrator_role_message'] . '</i></li>';
		} else {
			echo '<li><span style="width: 200px"><a href="index.php?id=' . $row['id'] . '&a=35">' . $row['name'] . '</a></span> - ' . $row['description'] . '</li>';
		}
	}
	echo "</ul>";
}
?>
</div>
</div>
