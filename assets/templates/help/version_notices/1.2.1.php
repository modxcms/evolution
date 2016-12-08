<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
?>
<p></p>
<h1>Server-Configuration</h1>
<ul>
	<li><strong>Turn off PHP directive "register_globals"</strong>
		<p>In case your server has "register_globals" set to ON for whatever reason, protect.inc.php will stop further script execution.</p>
	</li>
</ul>