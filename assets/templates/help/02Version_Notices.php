<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
?>

<div class="sectionHeader"><?php echo $_lang['version_notices']; ?></div><div class="sectionBody">
    <?php
    if ($handle = opendir(MODX_BASE_PATH . 'assets/templates/help/version_notices')) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && $file != ".svn") {
                $notices[] = str_replace('.php', '', $file);
            }
        }
        closedir($handle);
    }

    usort($notices, 'version_compare');
    $notices = array_reverse($notices);
    
    foreach($notices as $v) {
        echo '<h2 style="border-top:1px solid #aaa;border-bottom:1px solid #aaa;padding:.25em 0;">MODX v'.$v.'</h2>';
        include(MODX_BASE_PATH . "assets/templates/help/version_notices/{$v}.php");
        echo '<div style="display:block;height:3em;"></div>';
    }
    ?>
</div>