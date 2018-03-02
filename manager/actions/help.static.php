<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
$helpBasePath = "actions/help/";
?>

<h1>
	<?php echo $_style['page_help'];  echo $_lang['help']; ?>
</h1>

<div class="sectionBody">
	<div class="tab-pane" id="helpPane">
		<script type="text/javascript">
			tp = new WebFXTabPane(document.getElementById("helpPane"), <?php echo $modx->config['remember_last_tab'] == 1 ? 'true' : 'false'; ?> );
		</script>

		<?php
		if($handle = opendir('actions/help')) {
			while(false !== ($file = readdir($handle))) {
				if($file != "." && $file != ".." && $file != ".svn" && $file != 'index.html' && !is_dir($helpBasePath . $file)) {
					$help[] = $file;
				}
			}
			closedir($handle);
		}

		natcasesort($help);

		foreach($help as $k => $v) {

			$helpname = substr($v, 0, strrpos($v, '.'));

			$prefix = substr($helpname, 0, 2);
			if(is_numeric($prefix)) {
				$helpname = substr($helpname, 2, strlen($helpname) - 1);
			}

			$hnLower = strtolower($helpname);
			$helpname = isset($_lang[$hnLower]) ? $_lang[$hnLower] : str_replace('_', ' ', $helpname);

			echo '<div class="tab-page" id="tab' . $k . 'Help">';
			echo '<h2 class="tab">' . $helpname . '</h2>';
			echo '<script type="text/javascript">tp.addTabPage( document.getElementById( "tab' . $k . 'Help" ) );</script>';
			include_once($helpBasePath . "{$v}");
			echo '</div>';
		}
		?>
	</div>
</div>
<script>
	if(window.location.hash == '#version_notices') tp.setSelectedIndex(1);
</script>
