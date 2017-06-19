<?php

$url = "http://modx-store.com/get.php?get=file&cid=1";
$newfname = 'update.zip';
if (ini_get('allow_url_fopen') == true) {
	$file = fopen ($url, "rb");
	if (! $file) {
		throw new Exception("Could not open the file!");
	}
	if ($file) {
		$newf = fopen ($newfname, "wb");
		if ($newf)
		while(!feof($file)) {
			fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
		}
	}
	if ($file) fclose($file);
	if ($newf) fclose($newf);
} else if (function_exists('curl_init')) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$safeMode = @ini_get('safe_mode');
	$openBasedir = @ini_get('open_basedir');
	if (empty($safeMode) && empty($openBasedir)) {
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	}
	$content = curl_exec ($ch);
	file_put_contents($newfname,$content);				
}		
			
$zip = new ZipArchive;
$res = $zip->open(dirname(__FILE__).'/update.zip');
$zip->extractTo( dirname(__FILE__) );
$zip->close();
echo dirname(__FILE__).'/update.zip';
unlink('update.zip');
?>