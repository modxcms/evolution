//<?php
/**
 * DDocInfo
 * 
 * DDocInfo
 *
 * @category 	snippet
 * @version 	1
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties 
 * @internal	@modx_category Content
 * @internal    @installset base, sample
 */

$id = isset($id) ? (int)$id : $modx->documentObject['id'];
$field = isset($field) ? (string)$field : 'id';
if($field == 'id'){
    $out = $id;
}else{
    if($modx->documentObject['id'] == $id){
        $out = isset($modx->documentObject[$field]) ? $modx->documentObject[$field] : '';
        if(is_array($out)){
           $out = isset($out[1]) ? $out[1] : '';
        }
    }else{
        $out = $modx->doc->edit($id)->get($field);
    }
}
return (string)$out;