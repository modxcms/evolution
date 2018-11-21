<?php
/**
 * phpthumb
 *
 * PHPThumb creates thumbnails and altered images on the fly and caches them
 *
 * @category    snippet
 * @version    1.3.2
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @properties
 * @internal    @modx_category Content
 * @internal    @installset base, sample
 * @documentation Usage: [[phpthumb? &input=`[+image+]` &options=`w=150,h=76,far=C,bg=FFFFFF`]]
 * @documentation phpThumb docs http://phpthumb.sourceforge.net/demo/docs/phpthumb.readme.txt
 * @reportissues https://github.com/modxcms/evolution
 * @link        noimage.png here [+site_url+]assets/snippets/phpthumb/noimage.png
 * @author      Bumkaka
 * @author      Many contributors since then
 * @lastupdate  30/10/2018
 */
if (!defined('MODX_BASE_PATH')) {
    die('What are you doing? Get out of here!');
}

$newFolderAccessMode = $modx->getConfig('new_folder_permissions');
$newFolderAccessMode = empty($new) ? 0777 : octdec($newFolderAccessMode);

$cacheFolder = isset($cacheFolder) ? $cacheFolder : $modx->getCacheFolder() . "images";
/**
 * @see: https://github.com/kalessil/phpinspectionsea/blob/master/docs/probable-bugs.md#mkdir-race-condition
 */
$path = MODX_BASE_PATH . $cacheFolder;
if (!file_exists($path) && mkdir($path) && is_dir($path)) {
    chmod(MODX_BASE_PATH . $cacheFolder, $newFolderAccessMode);
}

$tmpFolder = $modx->getCacheFolder() . 'tmp';
if (!empty($input)) {
    $input = rawurldecode($input);
}

if (empty($input) || !file_exists(MODX_BASE_PATH . $input)) {
    $input = isset($noImage) ? $noImage : 'assets/snippets/phpthumb/noimage.png';
}

/**
 * allow read in phpthumb cache folder
 */
if (!file_exists(MODX_BASE_PATH . $cacheFolder . '/.htaccess') &&
    $cacheFolder !== $modx->getCacheFolder() &&
    strpos($cacheFolder, $modx->getCacheFolder()) === 0
) {
    file_put_contents(MODX_BASE_PATH . $cacheFolder . '/.htaccess', "order deny,allow\nallow from all\n");
}

$path = MODX_BASE_PATH . $tmpFolder;
if (!file_exists($path) && mkdir($path) && is_dir($path)) {
    chmod($path, $newFolderAccessMode);
}

$path_parts = pathinfo($input);
$tmpImagesFolder = str_replace('assets/images', '',$path_parts['dirname']);
$tmpImagesFolder = explode('/', $tmpImagesFolder);
$ext = strtolower($path_parts['extension']);
$options = 'f=' . (in_array($ext, array('png', 'gif', 'jpeg')) ? $ext : 'jpg&q=85') . '&' .
    strtr($options, array(',' => '&', '_' => '=', '{' => '[', '}' => ']'));

parse_str($options, $params);
foreach ($tmpImagesFolder as $folder) {
    if (!empty($folder)) {
        $cacheFolder .= '/' . $folder;
        $path = MODX_BASE_PATH . $cacheFolder;
        if (!file_exists($path) && mkdir($path) && is_dir($path)) {
            chmod($path, $newFolderAccessMode);
        }
    }
}

$fNamePref = rtrim($cacheFolder, '/') . '/';
$fName = $path_parts['filename'];
$fNameSuf = '-' .
    $params['w'] .'x' . $params['h'] . '-' .
    substr(md5(serialize($params) . filemtime(MODX_BASE_PATH . $input)), 0, 3) .
    '.' . $params['f'];

$outputFilename = MODX_BASE_PATH . $fNamePref . $fName . $fNameSuf;
if (!file_exists($outputFilename)) {
    require_once MODX_BASE_PATH . 'assets/snippets/phpthumb/phpthumb.class.php';
    $phpThumb = new phpthumb();
    $phpThumb->config_cache_directory = MODX_BASE_PATH . $tmpFolder;
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

return $fNamePref . rawurlencode($fName) . $fNameSuf;