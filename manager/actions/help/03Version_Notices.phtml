<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}

    if ($handle = opendir('actions/help/version_notices')) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && $file != ".svn" && is_readable("actions/help/version_notices/{$file}")) {
                $notices[] = str_replace('.php', '', $file);
            }
        }
        closedir($handle);
    }

    usort($notices, 'version_compare');
    $notices = array_reverse($notices);

    foreach($notices as $v) {
        if ($v >= '1.3.0') {
            $cms = 'EVO';
        }
        else {
            $cms = 'MODX EVO';
        }
        echo '<div class="sectionHeader"> '.$cms.' '.$v.'</div><div class="sectionBody">';
        include("actions/help/version_notices/{$v}.php");
        echo '</div><br/>';

    }
