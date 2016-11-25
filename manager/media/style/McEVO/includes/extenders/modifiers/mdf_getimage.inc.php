<?php
$pattern = '/<img[\s\n]+.*src=[\s\n]*"([^"]+\.(jpg|jpeg|png|gif))"[^>]+>/i';
preg_match_all($pattern , $value , $images);
if($opt==='')
{
    if($images[1][0])  return $images[1][0];
    else               return '';
}
else
{
    foreach($images[0] as $i=>$image)
    {
        if(strpos($image,$opt)!==false) return $images[1][$i];
    }
}

return '';
