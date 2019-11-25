<?php

/** This file is part of KCFinder project
 *
 *      @desc Base configuration file
 *   @package KCFinder
 *   @version 2.54
 *    @author Pavel Tzonkov <sunhater@sunhater.com>
 * @copyright 2010-2014 KCFinder Project
 *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
 *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
 *      @link http://kcfinder.sunhater.com
 */

// IMPORTANT!!! Do not remove uncommented settings in this file even if
// you are using session configuration.
// See http://kcfinder.sunhater.com/install for setting descriptions

$modx = evolutionCMS();
$_CONFIG = array(
    'disabled' => false,
    'denyZipDownload' => $modx->getConfig('denyZipDownload'),
    'denyExtensionRename' => $modx->getConfig('denyExtensionRename'),
    'showHiddenFiles' => $modx->getConfig('showHiddenFiles'),
    'theme' => "evo",
    'uploadURL'           => rtrim($modx->getConfig('rb_base_url'), '/'),
    'uploadDir'           => rtrim($modx->getConfig('rb_base_dir'), '/'),
    'siteURL' => MODX_SITE_URL,
    'assetsURL'           => rtrim($modx->getConfig('rb_base_url'), '/'),
    'dirPerms'            => intval($modx->getConfig('new_folder_permissions'), 8),
    'filePerms'           => intval($modx->getConfig('new_file_permissions'), 8),
    'maxfilesize'         => (int)$modx->getConfig('upload_maxsize'),
    'noThumbnailsRecreation' => $modx->getConfig('noThumbnailsRecreation'),

    'access' => array(

        'files' => array(
            'upload' => true,
            'delete' => true,
            'copy' => true,
            'move' => true,
            'rename' => true
        ),

        'dirs' => array(
            'create' => true,
            'delete' => true,
            'rename' => true
        )
    ),

    'deniedExts' => "exe com msi bat php phps phtml php3 php4 cgi pl",

    'types' => array(

        // CKEditor & FCKEditor types
        'files'  => str_replace(',', ' ', $modx->getConfig('upload_files')),
        'flash'  => str_replace(',', ' ', $modx->getConfig('upload_flash')),
        'images' => str_replace(',', ' ', $modx->getConfig('upload_images')),

        // TinyMCE types
        'file'   => str_replace(',', ' ', $modx->getConfig('upload_files')),
        'media'  => str_replace(',', ' ', $modx->getConfig('upload_media')),
        'image'  => str_replace(',', ' ', $modx->getConfig('upload_images')),
    ),
    'dirnameChangeChars' => array(
        ' ' => "_",
        ':' => "."
    ),
    'mime_magic' => "",

    'maxImageWidth' => $modx->getConfig('maxImageWidth'),
    'maxImageHeight' => $modx->getConfig('maxImageHeight'),
    'clientResize'   => $modx->getConfig('clientResize') && $modx->getConfig('maxImageWidth') && $modx->getConfig('maxImageHeight') ? array('maxWidth'  => $modx->getConfig('maxImageWidth'),
                                                                                                                                            'maxHeight' => $modx->getConfig('maxImageHeight'),
                                                                                                                                            'quality'   => $modx->getConfig('jpegQuality') / 100
    ) : array(),

    'thumbWidth' => $modx->getConfig('thumbWidth'),
    'thumbHeight' => $modx->getConfig('thumbHeight'),
    'thumbsDir' => $modx->getConfig('thumbsDir'),

    'jpegQuality' => $modx->getConfig('jpegQuality'),

    'cookieDomain' => "",
    'cookiePath' => "",
    'cookiePrefix' => 'KCFINDER_',

    // THE FOLLOWING SETTINGS CANNOT BE OVERRIDED WITH SESSION CONFIGURATION
    '_check4htaccess' => false,
    '_tinyMCEPath' => MODX_BASE_URL . "assets/plugins/tinymce/tiny_mce",

    '_sessionVar' => &$_SESSION['KCFINDER'],
    //'_sessionLifetime' => 30,
    //'_sessionDir' => "/full/directory/path",

    //'_sessionDomain' => ".mysite.com",
    //'_sessionPath' => "/my/path",
);
