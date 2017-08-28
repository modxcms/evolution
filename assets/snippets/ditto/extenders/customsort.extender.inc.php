<?php
$GLOBALS['documents'] = explode(',', $documents);
$orderBy['custom'][]  = array ('id', 'customsort');
$ditto->advSort = true;

if (!function_exists('customsort')){
    function customsort($a, $b){
        $pos_a = array_search($a['id'], $GLOBALS['documents']);
        $pos_b = array_search($b['id'], $GLOBALS['documents']);
        
        if ($pos_a == $pos_b) return 0;
        
        return ($pos_a < $pos_b) ? -1 : 1;
    }
}
