<?php

/*
 * Title: Compatibility
 * Purpose:
 *  	Backwards compatibility file for use with Ditto 1.0.2 calls
*/

// ---------------------------------------------------
// Group: Parameters
// $newParameters = $oldParameter;
// ---------------------------------------------------

$parents = isset($parents) ? $parents.','.$startID : $startID;

$removeChunk = isset($commentsChunk) ? $commentsChunk.','.$removeChunk : $commentsChunk;

$hiddenFields = isset($hiddenTVs) ? $hiddenTVs.','.$hiddenFields : $hiddenTVs;

$dateSource = isset($dateFormatType) ? $dateFormatType : $dateSource;

$depth = (isset($descendentDepth))? $descendentDepth : $depth;

$abbrLanguage = (isset($rssLanguage))? $rssLanguage : $abbrLanguage;

$tplAlt = isset($tplAltRows) ? $tplAltRows : $tplAlt;

$tplFirst = isset($tplFirstRow) ? ($tplFirstRow) : $tplFirst;

$tplLast = isset($tplLastRow) ? ($tplLastRow) : $tplLast;

$tplPaginatePrevious = isset($tplArchivePrevious)? ($tplArchivePrevious) : $tplPaginatePrevious;

$tplPaginateNext = isset($tplArchiveNext)? ($tplArchiveNext) : $tplPaginateNext;

$noResults = isset($emptyText) ? $emptyText : $noResults;

$extenders[] = "summary";
	// load the summary extender
?>