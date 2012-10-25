<?php
$langs = array();
if( $handle = opendir('lang/') ) {
	while( false !== ( $file = readdir( $handle ) ) ) {
		if( strpos( $file, '.inc.php' ) ) $langs[] = str_replace('.inc.php', '', $file);
	}
	closedir( $handle );
}
sort( $langs );
?>
<form name="install" id="install_form" action="index.php?action=mode" method="post">
    <h2>Choose language:&nbsp;&nbsp;
    <select name="language">
<?php
foreach ($langs as $language) {
    $abrv_language = explode('-',$language);
	echo '<option value="' . $language . '"'. ( ($abrv_language[0] == 'english') ? ' selected="selected"' : null ) .'>' . ucwords( $abrv_language[0] ). '</option>'."\n";
}
?>
    </select></h2>
        <p class="buttonlinks">
            <a style="display:inline;" href="javascript:document.getElementById('install_form').submit();" title="<?php echo $_lang['begin']?>"><span><?php echo $_lang['btnnext_value']?></span></a>
        </p>
</form>
