<?php

/**
 * Installer remover processor
 * --------------------------------
 * This little script will be used by the installer to remove
 * the install folder from the web root after an install. Having
 * the install folder present after an install is considered a
 * security risk
 *
 * This file is mormally called from the installer
 *
 */
include_once dirname(__DIR__) . '/includes/functions/processors.php';
$msg = '';
$pth = dirname(dirname(__DIR__)) . '/install/';
$pth = str_replace('\\', '/', $pth);

if (isset($_GET['rminstall'])) {
    if (is_dir($pth)) {
        if (! rmdirRecursive($pth)) {
            $msg = 'An error occured while attempting to remove the install folder';
        }
    }
}
if ($msg) {
    echo "<script>alert('" . addslashes($msg) . "');</script>";
}

echo "<script>window.location='../index.php?a=2';</script>";


