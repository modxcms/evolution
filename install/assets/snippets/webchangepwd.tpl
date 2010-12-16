//<?php
/**
 * WebChangePwd
 * 
 * Allows Web User to change their password from the front-end of the website
 *
 * @category 	snippet
 * @version 	1.0
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties
 * @internal	@modx_category Login
 * @internal    @installset base
 */


# Created By Raymond Irving April, 2005
#::::::::::::::::::::::::::::::::::::::::
# Params:	
#
#	&tpl			- (Optional)
#		Chunk name or document id to use as a template
#				  
#	Note: Templats design:
#			section 1: change pwd template
#			section 2: notification template 
#
# Examples:
#
#	[[WebChangePwd? &tpl=`ChangePwd`]] 

# Set Snippet Paths 
$snipPath  = (($modx->insideManager())? "../":"");
$snipPath .= "assets/snippets/";

# check if inside manager
if ($m = $modx->insideManager()) {
	return ''; # don't go any further when inside manager
}


# Snippet customize settings
$tpl		= isset($tpl)? $tpl:"";

# System settings
$isPostBack		= count($_POST) && isset($_POST['cmdwebchngpwd']);

# Start processing
include_once $snipPath."weblogin/weblogin.common.inc.php";
include_once $snipPath."weblogin/webchangepwd.inc.php";

# Return
return $output;



