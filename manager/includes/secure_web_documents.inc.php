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
    if (is_numeric($docid) && $docid > 0) {
        \EvolutionCMS\Models\SiteContent::find($docid)->update(['privatweb' => 0]);
    } else {
        \EvolutionCMS\Models\SiteContent::where('privateweb', 1)->update(['privatweb' => 0]);
    }
    $documentIds = \EvolutionCMS\Models\SiteContent::query()->select('site_content.id')->distinct()
        ->leftJoin('document_groups', 'site_content.id', '=', 'document_groups.document')
        ->leftJoin('webgroup_access', 'document_groups.document_group', '=', 'webgroup_access.documentgroup')
        ->where('webgroup_access.id', '>', 0);
    if (is_numeric($docid) && $docid > 0) {
        $documentIds = $documentIds->where('site_content.id', $docid);
    }

    $ids = $documentIds->get()->pluck('id');
    if (count($ids) > 0) {
        \EvolutionCMS\Models\SiteContent::whereIn('id', $ids)->update(['privatweb' => 1]);
    }
}
