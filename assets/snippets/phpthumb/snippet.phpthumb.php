<?php
/**
 * phpthumb
 *
 * PHPThumb creates thumbnails and altered images on the fly and caches them
 *
 * @category 	snippet
 * @version 	1.3
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties
 * @internal	@modx_category Content
 * @internal    @installset base, sample
 * @documentation Usage: [[phpthumb? &input=`[+image+]` &options=`w=150,h=76,far=C,bg=FFFFFF`]]
 * @documentation phpThumb docs http://phpthumb.sourceforge.net/demo/docs/phpthumb.readme.txt
 * @reportissues https://github.com/modxcms/evolution
 * @link        noimage.png here [+site_url+]assets/snippets/phpthumb/noimage.png
 * @author      Bumkaka
 * @author      Many contributors since then
 * @lastupdate  09/04/2016
 */
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

$newfolderaccessmode = $modx->config['new_folder_permissions'] ? octdec($modx->config['new_folder_permissions']) : 0777;

$cacheFolder=isset($cacheFolder) ? $cacheFolder : "assets/cache/images";
if(!is_dir(MODX_BASE_PATH.$cacheFolder)) {
    mkdir(MODX_BASE_PATH.$cacheFolder);
    chmod(MODX_BASE_PATH.$cacheFolder, $newfolderaccessmode);
}

$tmpFolder = 'assets/cache/tmp';
if (!empty($input)) $input = rawurldecode($input);

if(empty($input) || !file_exists(MODX_BASE_PATH . $input)){
    $input = isset($noImage) ? $noImage : 'assets/snippets/phpthumb/noimage.png';
}

// allow read in phpthumb cache folder
if (strpos($cacheFolder, 'assets/cache/') === 0 && $cacheFolder != 'assets/cache/' && !is_file(MODX_BASE_PATH . $cacheFolder . '/.htaccess')) {
	file_put_contents(MODX_BASE_PATH . $cacheFolder . '/.htaccess', "order deny,allow\nallow from all\n");
}


if(!is_dir(MODX_BASE_PATH.$tmpFolder)) {
    mkdir(MODX_BASE_PATH.$tmpFolder);
    chmod(MODX_BASE_PATH.$tmpFolder, $newfolderaccessmode);
}

$path_parts=pathinfo($input);
$tmpImagesFolder=str_replace(MODX_BASE_PATH . "assets/images","",$path_parts['dirname']);
$tmpImagesFolder=str_replace("assets/images","",$tmpImagesFolder);
$tmpImagesFolder=explode("/",$tmpImagesFolder);
$ext=strtolower($path_parts['extension']);
$options = 'f='.(in_array($ext,explode(",","png,gif,jpeg"))?$ext:"jpg&q=85").'&'.strtr($options, Array("," => "&", "_" => "=", '{' => '[', '}' => ']'));
parse_str($options, $params);
foreach ($tmpImagesFolder as $folder) {
    if (!empty($folder)) {
        $cacheFolder.="/".$folder;
        if(!is_dir(MODX_BASE_PATH.$cacheFolder)) {
            mkdir(MODX_BASE_PATH.$cacheFolder);
            chmod(MODX_BASE_PATH.$cacheFolder, $newfolderaccessmode);
        }
    }
}

$fname_preffix = "$cacheFolder/";
$fname = $path_parts['filename'];
$fname_suffix = "-{$params['w']}x{$params['h']}-".substr(md5(serialize($params).filemtime(MODX_BASE_PATH . $input)),0,3).".{$params['f']}";
$outputFilename = MODX_BASE_PATH.$fname_preffix.$fname.$fname_suffix;
if (!file_exists($outputFilename)) {
    require_once MODX_BASE_PATH.'assets/snippets/phpthumb/phpthumb.class.php';
    $phpThumb = new phpthumb();
    $phpThumb->config_temp_directory = $tmpFolder;
    $phpThumb->config_document_root = MODX_BASE_PATH;
    $phpThumb->setSourceFilename(MODX_BASE_PATH . $input);
    foreach ($params as $key => $value) {
        $phpThumb->setParameter($key, $value);
    }
	if ($phpThumb->GenerateThumbnail()) {
        $phpThumb->RenderToFile($outputFilename);
	} else {
        $modx->logEvent(0, 3, implode('<br/>', $phpThumb->debugmessages), 'phpthumb');
    }
}
return $fname_preffix.rawurlencode($fname).$fname_suffix;
?>
