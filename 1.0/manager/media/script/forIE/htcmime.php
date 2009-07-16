<?php
// Credit: http://www.hoeben.net/node/83
//
// Usage >> instead of the following in your stylesheet:
// body { behavior: url(assets/js/csshover.htc) }
//
// Use the following in the head of your document (shown in
// an IE Conditional Comment...adjust paths as needed):
//
// <!--[if lt IE 7]>
//   body { behavior: url(assets/js/htcmime.php?file=csshover.htc) }
//   img { behavior: url(assets/js/htcmime.php?file=pngbehavior.htc); }
// <![endif]-->
//
// NOTE: the img behavior is an alternate to using sleight.js

// Get component file name
$fname = (array_key_exists("file", $_GET)) ? $_GET["file"] : "";
$fpath = dirname(__FILE__);
$fname = basename($fname);

// basename() also strips \x00, we don't need to worry about ? and # in path:
// Must be real files anyway, fopen() does not support wildcards
$ext = array_pop(explode('.', $fname));

$filename = $fpath . '/' . $fname;
if (strcasecmp($ext, "htc") != 0 || !file_exists($filename))
  exit ("No file specified, file not found or illegal file.");

$flen = filesize($filename);

header("Content-type: text/x-component");
header("Content-Length: ".$flen);
header("Content-Disposition: inline; filename=$filename");

$fp = fopen($filename, "r");

echo fread($fp, $flen);

fclose($fp);
?>