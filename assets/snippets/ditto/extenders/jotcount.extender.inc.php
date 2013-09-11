<?php
// ����������� ����������� �� ����, ������� ������ �� ���������� ����� COUNT(*) (��������� � ���������������� �� �������)
$result = $modx->db->select('uparent, COUNT(*)', $modx->getFullTableName("jot_content"), 'published=1 AND deleted=0 GROUP BY uparent', 'COUNT(*) DESC');
$counts = $modx->db->makeArray( $result );

// �������� ������ � ��� document_id => comments_count
$jotcount = array();
foreach($counts as $k=>$v) $jotcount[$v['uparent']] = $v['COUNT(*)'];
// ��������� "�����������" ������ ��� ������ �� ������� jotph()
$GLOBALS['jotcount'] = $jotcount;

// ��������� ���������� ������������ [+jotcount+] � ditto
$placeholders['jotcount'] = array(array("id","*"),"jotph","id");

// �������, ����������� [+jotcount+] ��� ������ � ������� &tpl
if(!function_exists("jotph")) {
  function jotph($resource) {
    global $jotcount;
    if(!$r = $jotcount[$resource['id']]) $r = 0;
    return $r;
  }
}
?>