# WebSignup 1.1
# Created By Raymond Irving April, 2005
#::::::::::::::::::::::::::::::::::::::::
# Usage: 	
#	Allows a web user to signup for a new web account from the website
#	This snippet provides a basic set of form fields for the signup form
#	You can customize this snippet to create your own signup form
#
# Params:	
#
#	&tpl		- (Optional) Chunk name or document id to use as a template
#	&groups		- Web users groups to be assigned to users
#				  
#	Note: Templats design:
#			section 1: signup template
#			section 2: notification template 
#
# Examples:
#
#	[[WebSignup? &tpl=`SignupForm` &groups=`NewsReaders,WebUsers`]] 

# Set Snippet Paths 
$snipPath  = (($modx->insideManager())? "../":"");
$snipPath .= "assets/snippets/";

# check if inside manager
if ($m = $modx->insideManager()) {
	return ''; # don't go any further when inside manager
}


# Snippet customize settings
$tpl = isset($tpl)? $tpl:"";

# setup web groups
$groups = isset($groups) ? explode(',',$groups):array();
for($i=0;$i<count($groups);$i++) $groups[$i] = trim($groups[$i]);

# System settings
$isPostBack		= count($_POST) && isset($_POST['cmdwebsignup']);

# Start processing
include_once $snipPath."weblogin/weblogin.common.inc.php";
include_once $snipPath."weblogin/websignup.inc.php";

# Return
return $output;
