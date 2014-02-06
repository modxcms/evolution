<?php

/** This file is part of KCFinder project
  *
  *      @desc Base configuration file
  *   @package KCFinder
  *   @version 2.51
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010, 2011 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */

// IMPORTANT!!! Do not remove uncommented settings in this file even if
// you are using session configuration.
// See http://kcfinder.sunhater.com/install for setting descriptions

if (file_exists(MODX_BASE_PATH . 'assets/plugins/transalias/transliterations/common.php')) {
	$commonTransliterations = array_merge(include(MODX_BASE_PATH . 'assets/plugins/transalias/transliterations/common.php'), array(' ' => '-', ':' => '.'));
} else {
	$commonTransliterations = array(
		'&' => 'and', '%' => '', '\'' => '',
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'E', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A',
		'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C',
		'Ď' => 'D', 'Đ' => 'D',
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E', 'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E',
		'Ĝ' => 'G', 'Ğ' => 'G', 'Ġ' => 'G', 'Ģ' => 'G',
		'Ĥ' => 'H', 'Ħ' => 'H',
		'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I', 'İ' => 'I',
		'Ĳ' => 'J', 'Ĵ' => 'J',
		'Ķ' => 'K',
		'Ľ' => 'L', 'Ĺ' => 'L', 'Ļ' => 'L', 'Ŀ' => 'L', 'Ł' => 'L',
		'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N', 'Ņ' => 'N', 'Ŋ' => 'N',
		'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
		'Œ' => 'E',
		'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R',
		'Ś' => 'S', 'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S',
		'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T', 'Ț' => 'T',
		'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ū' => 'U', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
		'Ŵ' => 'W',
		'Ŷ' => 'Y', 'Ÿ' => 'Y',
		'Ź' => 'Z', 'Ż' => 'Z',
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'e', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
		'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
		'ď' => 'd', 'đ' => 'd',
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
		'ƒ' => 'f',
		'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g',
		'ĥ' => 'h', 'ħ' => 'h',
		'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i', 'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i',
		'ĳ' => 'j', 'ĵ' => 'j',
		'ķ' => 'k', 'ĸ' => 'k',
		'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l', 'ŀ' => 'l',
		'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n', 'ŋ' => 'n',
		'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ō' => 'o', 'ŏ' => 'o',
		'œ' => 'e',
		'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r',
		'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ū' => 'u', 'ů' => 'u', 'ű' => 'u', 'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u',
		'ŵ' => 'w',
		'ÿ' => 'y', 'ŷ' => 'y',
		'ż' => 'z', 'ź' => 'z',
		'ß' => 's', 'ſ' => 's', 'ś' => 's',
		'Α' => 'A', 'Ά' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Έ' => 'E', 'Ζ' => 'Z', 'Η' => 'I', 'Ή' => 'I',
		'Θ' => 'TH', 'Ι' => 'I', 'Ί' => 'I', 'Ϊ' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => 'KS', 'Ο' => 'O',
		'Ό' => 'O', 'Π' => 'P', 'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Ύ' => 'Y', 'Ϋ' => 'Y', 'Φ' => 'F', 'Χ' => 'X',
		'Ψ' => 'PS', 'Ω' => 'O', 'Ώ' => 'O', 'α' => 'a', 'ά' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'έ' => 'e',
		'ζ' => 'z', 'η' => 'i', 'ή' => 'i', 'θ' => 'th', 'ι' => 'i', 'ί' => 'i', 'ϊ' => 'i', 'ΐ' => 'i', 'κ' => 'k', 'λ' => 'l',
		'μ' => 'm', 'ν' => 'n', 'ξ' => 'ks', 'ο' => 'o', 'ό' => 'o', 'π' => 'p', 'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y',
		'ύ' => 'y', 'ϋ' => 'y', 'ΰ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'o', 'ώ' => 'o',
		' ' => '-', ':' => '.'
	);
}

global $settings,$site_url;
$_CONFIG = array(

    'disabled' => false,
    'denyZipDownload' => $settings['denyZipDownload'],
    'denyExtensionRename' => $settings['denyExtensionRename'],
	'showHiddenFiles' => $settings['showHiddenFiles'],
	
    'theme' => "oxygen",

    'uploadURL' => rtrim($settings['rb_base_url'],'/'),
    'uploadDir' => rtrim($settings['rb_base_dir'],'/'),
    'siteURL' => $site_url,
	'assetsURL' => rtrim($settings['rb_base_url'],'/'),
    'dirPerms' => intval($settings['new_folder_permissions'],8),
    'filePerms' => intval($settings['new_file_permissions'],8),

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
        'files'   =>  str_replace(',',' ',$settings['upload_files']),
        'flash'   =>  str_replace(',',' ',$settings['upload_flash']),
        'images'  =>  str_replace(',',' ',$settings['upload_images']),

        // TinyMCE types
        'file'    =>  str_replace(',',' ',$settings['upload_files']),
        'media'   =>  str_replace(',',' ',$settings['upload_media']),
        'image'   =>  str_replace(',',' ',$settings['upload_images']),
    ),

    'filenameChangeChars' => $commonTransliterations,

    'dirnameChangeChars' => $commonTransliterations,

    'mime_magic' => "",

    'maxImageWidth' => $settings['maxImageWidth'],
    'maxImageHeight' => $settings['maxImageHeight'],

    'thumbWidth' => $settings['thumbWidth'],
    'thumbHeight' => $settings['thumbHeight'],

    'thumbsDir' => $settings['thumbsDir'],

    'jpegQuality' => $settings['jpegQuality'],

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

?>