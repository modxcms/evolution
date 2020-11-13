<?php
/**
 * Handles dynamic search
 *
 * @package tinymce
 */
define('MODX_API_MODE', true);
include_once("../../../../../../index.php");

$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}
if(!isset($_SESSION['mgrValidated'])){
    die();
}

$query = $modx->db->escape($_GET['q']);

$where = "`pagetitle` LIKE '%".$query."%' OR `alias` LIKE '%".$query."%' AND deleted=0";

$result = $modx->db->select("id,pagetitle,alias", $modx->getFullTableName('site_content'), $where, '', 10); 

$a = array();
while( $row = $modx->db->getRow( $result ) ) { 
	$output .= $row['pagetitle'] . ' (' . $row['id'] . ')|'. $row['id'] . "\n"; 
	$a[] = array(
		'id' => $row['id']
		,'pagetitle' => $row['pagetitle'] .' ('.$row['id'].')'
		,'title' => $row['pagetitle']
		,'alias' => $row['alias']
	);

 }

exit(json_encode($a));