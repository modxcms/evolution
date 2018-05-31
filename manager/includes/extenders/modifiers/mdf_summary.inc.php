<?php
if(strpos($opt,',')) list($limit,$delim) = explode(',', $opt);
elseif(preg_match('/^[1-9][0-9]*$/',$opt)) {$limit=$opt;$delim='';}
else {$limit=124;$delim='';}

if($delim==='') $delim = $modx->config['manager_language']==='japanese-utf8' ? '。' : '.';
$limit = (int)$limit;

$content = $modx->filter->parseDocumentSource($value);

$content = strip_tags($content);

$content = str_replace(array("\r\n","\r","\n","\t",'&nbsp;'),' ',$content);
if(preg_match('/\s+/',$content))
    $content = preg_replace('/\s+/',' ',$content);
$content = trim($content);

$pos = $modx->filter->strpos($content, $delim);

if($pos!==false && $pos<$limit) {
    $_ = explode($delim, $content);
    $text = '';
    foreach($_ as $v) {
        if($limit <= $modx->filter->strlen($text.$v.$delim)) break;
        $text .= $v.$delim;
    }
    if($text) $content = $text;
}

if($limit<$modx->filter->strlen($content) && strpos($content,' ')!==false) {
    $_ = explode(' ', $content);
    $text = '';
    foreach($_ as $v) {
        if($limit <= $modx->filter->strlen($text.$v.' ')) break;
        $text .= $v . ' ';
    }
    if($text!=='') $content = $text;
}

if($limit < $modx->filter->strlen($content)) $content = $modx->filter->substr($content, 0, $limit);
if($modx->filter->substr($content,-1)==$delim) $content = rtrim($content,$delim) . $delim;

return $content;
