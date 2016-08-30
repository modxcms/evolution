//<?php
/**
 * WebChangePwd
 * 
 * Allows Web User to change their password from the front-end of the website
 *
 * @category 	snippet
 * @version 	1.1.2
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties
 * @internal	@modx_category Login
 * @internal    @installset base
 * @documentation [+site_url+]assets/snippets/weblogin/docs/webchangepwd.html
 * @documentation http://www.opensourcecms.com/news/details.php?newsid=660
 * @reportissues https://github.com/modxcms/evolution
 * @author      Created By Raymond Irving April, 2005
 * @author      Ryan Thrash http://thrash.me
 * @author      Jason Coward http://opengeek.com
 * @author      Shaun McCormick, garryn, Dmi3yy
 * @lastupdate  09/02/2016
 */

# Set Snippet Paths 
$snipPath  = (($modx->isBackend())? "../":"");
$snipPath .= "assets/snippets/";

# check if inside manager
if ($m = $modx->isBackend()) {
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



