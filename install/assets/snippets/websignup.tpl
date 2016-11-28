//<?php
/**
 * WebSignup
 * 
 * Basic Web User account creation/signup system
 *
 * @category 	snippet
 * @version 	1.1.2
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties &tpl=Template;string;
 * @internal	@modx_category Login
 * @internal    @installset base, sample
 * @documentation [+site_url+]assets/snippets/weblogin/docs/websignup.html
 * @documentation http://www.opensourcecms.com/news/details.php?newsid=660
 * @reportissues https://github.com/modxcms/evolution
 * @author      Created By Raymond Irving April, 2005
 * @author      Ryan Thrash http://thrash.me
 * @author      Jason Coward http://opengeek.com
 * @author      Shaun McCormick, garryn, Dmi3yy
 * @lastupdate  09/02/2016
 */

# Set Snippet Paths 
$snipPath = $modx->config['base_path'] . "assets/snippets/";

# check if inside manager
if ($m = $modx->isBackend()) {
    return ''; # don't go any further when inside manager
}


# Snippet customize settings
$tpl = isset($tpl)? $tpl:"";
$useCaptcha = isset($useCaptcha)? $useCaptcha : $modx->config['use_captcha'] ;
// Override captcha if no GD
if ($useCaptcha && !gd_info()) $useCaptcha = 0;

# setup web groups
$groups = isset($groups) ? array_filter(array_map('trim', explode(',', $groups))):array();

# System settings
$isPostBack        = count($_POST) && isset($_POST['cmdwebsignup']);

$output = '';

# Start processing
include_once $snipPath."weblogin/weblogin.common.inc.php";
include_once $snipPath."weblogin/websignup.inc.php";

# Return
return $output;