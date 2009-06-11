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
<form name="install" id="install_form" action="index.php?action=welcome" method="post">
    <h2>Choose language:&nbsp;&nbsp;
    <select name="language">
<?php
foreach ($langs as $language) {
    $abrv_language = explode('-',$language); // filter of character set extension
    if ($abrv_language[0] == 'english') {
        echo '<option value="' . $language . '" selected="selected">' . $abrv_language[0] . '</option>'."\n";
    } else {
        echo '<option value="' . $language . '">' . $abrv_language[0] . '</option>'."\n";
    }
}
?>
    </select></h2>
        <p class="buttonlinks">
            <a style="display:inline;" href="javascript:document.getElementById('install_form').submit();" title="<?php echo $_lang['begin']?>"><span><?php echo $_lang['btnnext_value']?></span></a>
        </p>
</form>
