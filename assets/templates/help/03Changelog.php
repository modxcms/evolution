<?php
if (IN_MANAGER_MODE != "true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
?>

<div class="sectionHeader">Changelog</div>
<div class="sectionBody">
<?php
	$changeLog = MODX_BASE_PATH . 'assets/docs/changelog.txt';
	if(is_readable($changeLog))
		echo str_replace("\n",'<br>',file_get_contents($changeLog));
?>
</div>