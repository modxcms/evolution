<?php
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}
//[[phpthumb? &input=`[+image+]` &options=`w=150,h=76,far=C,bg=FFFFFF`]]
//Author: Bumkaka

$cacheFolder=isset($cacheFolder) ? $cacheFolder : "assets/cache/images";
$tmpFolder = 'assets/cache/tmp';
if (!empty($input)) $input = rawurldecode($input);

if(empty($input) || !file_exists(MODX_BASE_PATH . $input)){
    $input = isset($noImage) ? $noImage : 'assets/snippets/phpthumb/noimage.png';
}

// allow read in phpthumb cache folder
if (strpos($cacheFolder, 'assets/cache/') === 0 && $cacheFolder != 'assets/cache/' && !is_file(MODX_BASE_PATH . $cacheFolder . '/.htaccess')) {
	file_put_contents(MODX_BASE_PATH . $cacheFolder . '/.htaccess', "order deny,allow\nallow from all\n");
}


if(!is_dir(MODX_BASE_PATH.$tmpFolder)) mkdir(MODX_BASE_PATH.$tmpFolder);

$path_parts=pathinfo($input);
$tmpImagesFolder=str_replace(MODX_BASE_PATH . "assets/images","",$path_parts['dirname']);
$tmpImagesFolder=str_replace("assets/images","",$tmpImagesFolder);
$tmpImagesFolder=explode("/",$tmpImagesFolder);
$ext=strtolower($path_parts['extension']);
$options = 'f='.(in_array($ext,explode(",","png,gif"))?$ext:"jpg&q=96").'&'.strtr($options, Array("," => "&", "_" => "=", '{' => '[', '}' => ']'));
parse_str($options, $params);
foreach ($tmpImagesFolder as $folder) {
    if (!empty($folder)) {
        $cacheFolder.="/".$folder;
        if(!is_dir(MODX_BASE_PATH.$cacheFolder)) mkdir(MODX_BASE_PATH.$cacheFolder);
    }
}
  
$fname_preffix=$cacheFolder."/".$params['w']."x".$params['h'].'-';
$fname = $path_parts['filename'].".".substr(md5(serialize($params)),0,3).".".$params['f'];
$outputFilename =MODX_BASE_PATH.$fname_preffix.$fname;
if (!file_exists($outputFilename)) {
    require_once MODX_BASE_PATH.'assets/snippets/phpthumb/phpthumb.class.php';
    $phpThumb = new phpthumb();
    $phpThumb->config_temp_directory = $tmpFolder;
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
return $fname_preffix.rawurlencode($fname);
?>