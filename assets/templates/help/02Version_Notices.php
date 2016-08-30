<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

    if ($handle = opendir(MODX_BASE_PATH . 'assets/templates/help/version_notices')) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && $file != ".svn" && is_readable(MODX_BASE_PATH . "assets/templates/help/version_notices/{$file}")) {
                $notices[] = str_replace('.php', '', $file);
            }
        }
        closedir($handle);
    }

    usort($notices, 'version_compare');
    $notices = array_reverse($notices);
    
    foreach($notices as $v) {
        echo '<div class="sectionHeader">MODX v'.$v.'</div><div class="sectionBody">';
        include(MODX_BASE_PATH . "assets/templates/help/version_notices/{$v}.php");
        echo '</div><br/>';
    }
?>