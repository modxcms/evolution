<?php
$data = include EVO_CORE_PATH . 'factory/version.php';

$modx_version      = $data['version']; // Current version number
$modx_release_date = $data['release_date']; // Date of release
$modx_branch       = $data['branch']; // Codebase name
$modx_full_appname = $data['full_appname'];
