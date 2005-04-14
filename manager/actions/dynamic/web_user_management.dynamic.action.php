<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if($_SESSION['permissions']['edit_user']!=1 && $_REQUEST['a']==99) {	
	$e->setError(3);
	$e->dumpError();	
}

?>

<div class="subTitle">
	<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $_lang['web_user_management_title']; ?></span>
</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;<?php echo $_lang['web_user_management_title']; ?></div>
<div class="sectionBody">
<p><?php echo $_lang['web_user_management_msg']; ?></p>

<table border="0" width="100%">
<tr>
<td width="100%" valign="top" style="border-left:1px dotted silver;">
	<ul>
		<li><a href="index.php?a=87"><?php echo $_lang['new_web_user']; ?></a></li>
	</ul>
	<?php // Web Users
		$sql = "select username, id from $dbase.".$table_prefix."web_users order by username"; 
		$rs = mysql_query($sql); 
		
		include_once "controls/datasetpager.class.php";	
		$dp = new DataSetPager('',$rs,10);
		$dp->setRenderRowFnc("RenderWebUsers");
		$dp->render();
		
		$pager	= $dp->getRenderedPager();
		$rows 	= $dp->getRenderedRows();
		if($pager) $pager = "Page $pager";
		else $pager = "<br />";
		
		echo "<table border='0' width='100%'>";
		echo "<tr><td align='right'>$pager</td></tr>";
		echo "<tr><td><ul>$rows</ul></td></tr>";
		echo "</table>";
		
		function RenderWebUsers($i,$row){
			if($i<1){
				return "<li>The request returned no web users!</li>";
			}
			else {
				return "<li><a href='index.php?id=".$row['id']."&a=88'>".$row['username']."</a></li>";
			}
		}		
	?>
</td>
</tr>
</table>

</div>

