<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}

/**
 *    Secure Web Documents
 *    This script will mark web documents as private
 *
 *    A document will be marked as private only if a web user group
 *    is assigned to the document group that the document belongs to.
 *
 * @param string $docid
 */
function secureWebDocument($docid = '')
{
    $modx = evolutionCMS();

    $modx->db->update('privateweb = 0', $modx->getFullTableName("site_content"),
        ($docid > 0 ? "id='$docid'" : "privateweb = 1"));
    $rs = $modx->db->select(
        'DISTINCT sc.id',
        $modx->getFullTableName("site_content") . " sc
			LEFT JOIN " . $modx->getFullTableName("document_groups") . " dg ON dg.document = sc.id
			LEFT JOIN " . $modx->getFullTableName("webgroup_access") . " wga ON wga.documentgroup = dg.document_group",
        ($docid > 0 ? " sc.id='{$docid}' AND " : "") . "wga.id>0"
    );
    $ids = $modx->db->getColumn("id", $rs);
    if (count($ids) > 0) {
        $modx->db->update('privateweb = 1', $modx->getFullTableName("site_content"),
            "id IN (" . implode(", ", $ids) . ")");
    }
}
