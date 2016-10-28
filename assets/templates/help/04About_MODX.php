<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

$downloadLinks = array(
	0=>array('title'=>'Information','link'=>'https://modx.com/community/modx-evolution'),
	1=>array('title'=>'Download','link'=>'https://modx.com/download/evolution/'),
	2=>array('title'=>'Previous Releases','link'=>'https://modx.com/download/evolution/previous-releases.html'),
	3=>array('title'=>'Extras','link'=>'https://modx.com/extras/?product=evolution'),
);
?>

<div class="sectionHeader">Evolution Downloads</div><div class="sectionBody">
	<table width="500"  border="0" cellspacing="0" cellpadding="0">
	<?php foreach($downloadLinks as $row) { ?>
		<tr>
			<td align="left"><strong><?php echo $row["title"]; ?></strong></td>
			<td align="left"><a href="<?php echo $row["link"]; ?>" target="_blank"><?php echo $row["link"]; ?></a></td>
		</tr>
	<?php } ?>
		
	</table> 
</div>

<div class="sectionHeader"><?php echo $_lang['about_title']; ?></div><div class="sectionBody">
<?php echo $_lang['about_msg']; ?> <?php echo $_lang['credits_shouts_msg']; ?>
</div>

<div class="sectionHeader"><?php echo $_lang['help_title']; ?></div><div class="sectionBody">
<?php echo $_lang['help_msg']; ?>
</div>

<div class="sectionHeader"><?php echo $_lang['credits']; ?></div><div class="sectionBody">
<table width="500"  border="0" cellspacing="0" cellpadding="0">
  <tr height="70">
    <td align="center"><a href="http://www.php.net" target="_blank"><img src="media/style/<?php echo $modx->config['manager_theme']; ?>/images/credits/php.gif" border="0"></a></td>
    <td align="center"><a href="http://www.mysql.com" target="_blank"><img src="media/style/<?php echo $modx->config['manager_theme']; ?>/images/credits/mysql.gif" border="0"></a></td>
    <td align="center"><a href="http://www.destroydrop.com" target="_blank"><img src="media/style/<?php echo $modx->config['manager_theme']; ?>/images/credits/dtree.gif" border="0"></a></td>
    <td align="center"><a href="http://www.everaldo.com" target="_blank"><img src="media/style/<?php echo $modx->config['manager_theme']; ?>/images/credits/penguin.gif" border="0"></a></td>
  </tr>
</table>
</div>

