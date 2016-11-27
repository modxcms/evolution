<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('edit_user')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

?>
<!-- User Roles -->

<h1 class="pagetitle">
  <span class="pagetitle-icon">
    <i class="fa fa-legal"></i>
  </span>
  <span class="pagetitle-text">
    <?php echo $_lang['role_management_title']; ?>
  </span>
</h1>
<div class="section">
<div class="sectionBody">
<p class="element-edit-message"><?php echo $_lang['role_management_msg']; ?></p>

<ul class="actionButtons">
	<li><a href="index.php?a=38"><?php echo $_lang['new_role']; ?></a></li>
</ul>
<?php

$rs = $modx->db->select('name, id, description', $modx->getFullTableName('user_roles'), '', 'name');
$limit = $modx->db->getRecordCount($rs);
if($limit<1){
	echo "<p>The request returned no roles!</p>";
} else {
	echo '<ul class="c-roleslist">';
	while ($row = $modx->db->getRow($rs)) {
		if($row['id']==1) {
          echo '<li class="c-roleslist-item c-roleslist-item--admin"><span class="c-roleslist-name">' . $row['name'] . '</span> <span class="c-roleslist-hyphen">-</span> <span class="c-roleslist-description">' . $_lang['administrator_role_message'] . '</span></li>';
		} else {
          echo '<li class="c-roleslist-item"><span class="c-roleslist-name"><a href="index.php?id=' . $row['id'] . '&a=35">' . $row['name'] . '</a></span> <span class="c-roleslist-hyphen">-</span> <span class="c-roleslist-description">' . $row['description'] . '</span></li>';
		}
	}
	echo '</ul>';
}
?>
</div>
</div>
