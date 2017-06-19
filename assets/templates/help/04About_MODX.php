<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

$downloadLinks = array(
	0=>array('title'=>$_lang["information"],'link'=>'https://modx.com/community/modx-evolution'),
	1=>array('title'=>$_lang["download"],'link'=>'https://modx.com/download/evolution/'),
	2=>array('title'=>$_lang["previous_releases"],'link'=>'https://modx.com/download/evolution/previous-releases.html'),
	3=>array('title'=>$_lang["extras"],'link'=>array(
		'https://modx.com/extras/?product=evolution',
		'http://extras.evolution-cms.com/',
		'https://github.com/extras-evolution'
	)),
);

$translationLinks = array(
	0=>array('title'=>'MODX Evolution','link'=>'https://www.transifex.com/modx/modx-evolution/'),
	1=>array('title'=>$_lang["extras"],'link'=>'https://www.transifex.com/modx/modx-evolution-extras/'),
);

function createList($sectionHeader, $linkArr) {
	$output = '<div class="sectionHeader">'.$sectionHeader.'</div><div class="sectionBody">'."\n";
	$output .= '<table width="500"  border="0" cellspacing="0" cellpadding="0">'."\n";
	$links = '';
	foreach($linkArr as $row) {
		if (!is_array($row['link'])) $row['link'] = array($row['link']);
		foreach ($row['link'] as $link) {
			$links .= $links != '' ? '<br/>' : '';
			$links .= '<a href="' . $link . '" target="_blank">' . $link . '</a>';
		}
		$output .= '
		<tr>
			<td align="left"><strong>' . $row["title"] . '</strong></td>
			<td align="left">' . $links . '</td>
		</tr>';
		$links = '';
	}
	$output .= '</table></div>'."\n";
	return $output;
}

echo createList($_lang['evo_downloads_title'], $downloadLinks);
echo createList($_lang['help_translating_title'], $translationLinks);

?>

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

