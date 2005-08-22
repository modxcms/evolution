<?php

/**
 *	Developer Support Functions
 *	Customize these functions to add support for your clients
 *	and save the customized file as overide.support.inc.php
 *	
 *	The system will first look for the override.support.inc.php file.
 *	If it's not found then it will use support.inc.php 
 *
 */

// Show support link on Manager Login page
function showSupportLink(){
?>
	<!-- Here you can add your own support information and website -->
	<div style="color:#808080">
	<p>Supported By:</p>
	<p style="text-align:center"><a href="http://www.modxcms.com/forums/" target="_blank"><strong>The MODx <br />Community</strong></a></p>
	</div>
<?php
}

// sends an mail to support site
function mailSupport($sender,$subject,$message){
	// to-do:
}

// checks support site for updates
function checkForUpdates() {
	// to-do:
}



?>