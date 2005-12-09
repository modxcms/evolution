#::::::::::::::::::::::::::::::::::::::::
# Snippet Name: Personalize 
# Short Desc: basic personalization for logged in users
# Version: 1.0
# Created By: Ryan Thrash (modx@vertexworks.com)
#
# Date: December 1, 2005
#
# Changelog: 
# Dec 1, 05 -- initial release
#
#::::::::::::::::::::::::::::::::::::::::
# Description: 	
#	Checks to see if users belong to a certain group and 
#	displays the specified chunk if they do. Performs several
#	sanity checks and allows to be used multiple times on a page.
#	Only meant to be used once per page.
#
# Params:
#	&message [string] (optional)
#		simple message to prepend in front of the username
#
#	&wrapper [string] (optional) 
#		optional element to wrap the message in
#
#	&class [string] (optional) 
#		optional name of the class for the wrapper element
#
#	&ph [boolean] ( optional ) 
#		if set, outputs to the ph name passed in, instead 
#		of directly returning the output
#
# Example Usage:
#
#	[[Personalize? &message=`Welcome back, ` &wrapper=`h3` &class=`welcome`]]
#
#	For a logged in user John, would return: 
#	<h3 class="welcome">Welcome back, John</h3>
#
#::::::::::::::::::::::::::::::::::::::::

# is there a class defined?
$class = (isset($class))? ' class="'.$class.'"' : '';

# build the wrappers as needed
if (isset($wrapper)) {
	$w1 = '<'.$wrapper.$class.'>' ;
	$w2 = '</'.$wrapper.'>';
} else {
	$w1 = '';
	$w2 = '';
}

# add in the message
$message = (isset($message))? $message : '';

# do the work
$o = '';
$test = $modx->getLoginUserName();
$o = ($test)? "$w1$message$test$w2" : '';

if (isset($ph)) {
	$modx->setPlaceholder($ph,$o);
} else {
	return $o;
}