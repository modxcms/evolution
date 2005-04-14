<?php

/**
 *	Developer Support Functions
 *	Customize these functions to add support for your clients
 *	
 */

// Show support link on Manager Login page
function showSupportLink(){
?>
	<!-- Here you can add your own support information and website -->
	<div style="color:#808080">
	<p>Support By:</p>
	<p style="text-align:center"><a href="http://www.vertexworks.com/forums/" target="_blank"><strong>The MODx <br />Community</strong></a></p>
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