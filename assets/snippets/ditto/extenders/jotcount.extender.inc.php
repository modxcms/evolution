<?php
$where = 'published=1 AND deleted=0 GROUP BY uparent';
$result = $modx->db->select('uparent, COUNT(*)', '[+prefix+]jot_content', $where, 'COUNT(*) DESC');
$counts = $modx->db->makeArray( $result );

$jotcount = array();
foreach($counts as $k=>$v) {
    $jotcount[$v['uparent']] = $v['COUNT(*)'];
}

$GLOBALS['jotcount'] = $jotcount;

$placeholders['jotcount'] = array(array('id','*'),'jotph','id');

if(!function_exists('jotph')) {
    function jotph($resource) {
        global $jotcount;
        if(!$r = $jotcount[$resource['id']]) $r = 0;
        return $r;
    }
}
