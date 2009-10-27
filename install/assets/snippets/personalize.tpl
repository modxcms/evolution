//<?php
/**
 * Personalize
 * 
 * Basic personalization for web users
 *
 * @category 	snippet
 * @version 	2.0
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties 
 * @internal	@modx_category Login
 */

#::::::::::::::::::::::::::::::::::::::::
# Created By:Ryan Thrash (modx@vertexworks.com), 
#	and then powered up by kudo (kudo@kudolink.com)
#
# Date: Aug 03, 2006
#
# Changelog: 
# Dec 01, 05 -- initial release
# Jun 19, 06 -- updated description
# Jul 19, 06 -- hacked by kudo to output chunks
# Aug 03, 06 -- added placeholder for username
#
#::::::::::::::::::::::::::::::::::::::::
# Description: 	
#	Checks to see if webusers are logged in and displays yesChunk if the user
#	is logged or noChunk if user is not logged. Insert only the chunk name as
#	param, without {{}}. Can use a placeholder to output the username.
#	TESTED: can be used more than once per page.
#	TESTED: chunks can contain snippets.
#	
#	
# Params:
#	&yesChunk [string] [REQUIRED]
#		Output for LOGGED users
#
#	&noChunk [string] [REQUIRED] 
#		Output for NOT logged users
#
#	&ph [string] (optional) 
#		Placeholder for placing the username
#		ATTENTION!: place this ph only in yesChunk!
#	
#
# Example Usage:
#
#	[[LoggedOrNot? &yesChunk=`Link` &noChunk=`Register` &ph=`name`]]
#
#	Having Chunks named {{Link}} and another {{Register}}, the first will be
#	published to registered user, the second to non-registered users.
#
#::::::::::::::::::::::::::::::::::::::::

# prepare params and variables
$o = '';
$yesChunk = (isset($yesChunk))? $yesChunk : '';
$noChunk = (isset($noChunk))? $noChunk : '';

# do the work
$test = $modx->getLoginUserName();
if ($test) {
    $o = $modx->getChunk($yesChunk);
  } else {
    $o = $modx->getChunk($noChunk);
}

if (isset($ph)) {
	$modx->setPlaceholder($ph,$test);
	return $o;
} else {
	return $o;
}
