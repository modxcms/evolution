//<?php
/**
 * WebSignup
 * 
 * Basic Web User account creation/signup system
 *
 * @category 	snippet
 * @version 	1.1
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties &tpl=Template;string;
 * @internal	@modx_category Login
 * @internal    @installset base, sample
 */


# Created By Raymond Irving April, 2005
#::::::::::::::::::::::::::::::::::::::::
# Usage:     
#    Allows a web user to signup for a new web account from the website
#    This snippet provides a basic set of form fields for the signup form
#    You can customize this snippet to create your own signup form
#
# Params:    
#
#    &tpl        - (Optional) Chunk name or document id to use as a template
#	    		   If custom template AND captcha on AND using WebSignup and 
#                  WebLogin on the same page make sure you have a field named
#                  cmdwebsignup. In the default template it is the submit button 
#                  One can use a hidden field.
#    &groups     - Web users groups to be assigned to users
#    &useCaptcha - (Optional) Determine to use (1) or not to use (0) captcha
#                  on signup form - if not defined, will default to system
#                  setting. GD is required for this feature. If GD is not 
#                  available, useCaptcha will automatically be set to false;
#                  
#    Note: Templats design:
#        section 1: signup template
#        section 2: notification template 
#
# Examples:
#
#    [[WebSignup? &tpl=`SignupForm` &groups=`NewsReaders,WebUsers`]] 

# Set Snippet Paths 
$snipPath = $modx->config['base_path'] . "assets/snippets/";

# check if inside manager
if ($m = $modx->insideManager()) {
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