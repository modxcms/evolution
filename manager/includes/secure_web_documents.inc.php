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
function secureWebDocument($docid = '', $context = 1)
{
    $context = $context == 0 ? 0 : 1;
    $privateField = $context ? 'privateweb' : 'privatemgr';
    if (is_numeric($docid) && $docid > 0) {
        \EvolutionCMS\Models\SiteContent::withTrashed()->find($docid)->update([$privateField => 0]);
    } else {
        \EvolutionCMS\Models\SiteContent::withTrashed()->where($privateField, 1)->update([$privateField => 0]);
    }

    $documentIds = \EvolutionCMS\Models\SiteContent::withTrashed()->select('site_content.id')->distinct()
        ->leftJoin('document_groups', 'site_content.id', '=', 'document_groups.document')
        ->leftJoin('membergroup_access', function(Illuminate\Database\Query\JoinClause $join) use ($context) {
            $join->on('document_groups.document_group', '=', 'membergroup_access.documentgroup')
                ->where('membergroup_access.context', '=', $context);
        })->where('membergroup_access.id', '>', 0);
    if (is_numeric($docid) && $docid > 0) {
        $documentIds = $documentIds->where('site_content.id', $docid);
    }

    $ids = $documentIds->get()->pluck('id');

    if (count($ids) > 0) {
        \EvolutionCMS\Models\SiteContent::withTrashed()->whereIn('id', $ids)->update([$privateField => 1]);
    }
}

function secureMgrDocument($docid = '', $context = 0)
{
    secureWebDocument($docid, $context);
}
