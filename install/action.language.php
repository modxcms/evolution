<?php
$langs = array();

if ($handle = opendir("lang/")) {
	while (false !== ($file = readdir($handle))) {
		if (strpos($file, '.') === false) {
			if (is_dir($setupPath."lang/".$file)) {
				$langs[] = $file;
			}
		}
	}
	closedir($handle);
}
sort($langs);
?>
<form name="install" action="index.php?action=welcome" method="post">
	<table width="100%">
	<tr>
	<td valign="top">
		<center><img src="<?php echo include_image('img_splash.gif')?>" alt="<? echo $_lang['modx_install']?>" /></center>
	</td>
	<td align="center" width="280">
		<img src="<?php echo include_image('img_box.png')?>" alt="MODx Create and Do More with Less" />&nbsp;
	</td>
	</tr>
	</table>
	<div id="navbar">
	<h1 style="float:left;">Choose language:&nbsp;&nbsp;</h1>
	<select name="language" style="float:left;">
	<?php 
    foreach ($langs as $language) {
      $abrv_language = explode('-',$language); // filter of character set extension
      echo '<option value="' . $language . '">' . $abrv_language[0] . '</option>'."\n";
    }
  ?>
	</select>
	<input type="submit" value="<?php echo $_lang['btnnext_value']; ?>" style="float:right;width:100px;" />
	</div>
</form>