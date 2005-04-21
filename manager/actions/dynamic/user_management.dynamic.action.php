<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if($_SESSION['permissions']['edit_user']!=1 && $_REQUEST['a']==75) {	
	$e->setError(3);
	$e->dumpError();	
}
?>

<div class="subTitle">
	<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['user_management_title']; ?></span>
</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['user_management_title']; ?></div>
<div class="sectionBody">
<p><?php echo $_lang['user_management_msg']; ?></p>

<table border="0" width="100%">
<tr>
<td width="100%" valign="top">
	<ul>
		<li><a href="index.php?a=11"><?php echo $_lang['new_user']; ?></a></li>
	</ul>	
	<?php // Backend Users
		$sql = "select username, id from $dbase.".$table_prefix."manager_users order by username"; 
		$rs = mysql_query($sql); 

		include_once "controls/datasetpager.class.php";	
		$dp = new DataSetPager('',$rs,10);
		$dp->setRenderRowFnc("RenderAdminUsers");
		$dp->render();

		$pager	= $dp->getRenderedPager();
		$rows 	= $dp->getRenderedRows();
		if($pager) $pager = "Page $pager";
		else $pager = "<br />";

		echo "<table border='0' width='100%'>";
		echo "<tr><td align='right'>$pager</td></tr>";
		echo "<tr><td><ul>$rows</ul></td></tr>";
		echo "</table>";

		function RenderAdminUsers($i,$row){
			if($i<1){
				return "<li>The request returned no users!</li>";
			}
			else {
				return "<li><a href='index.php?id=".$row['id']."&a=12'>".$row['username']."</a></li>";
			}
		}
	?>
</td>
</tr>
</table>

</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['role_management_title']; ?></div><div class="sectionBody">
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