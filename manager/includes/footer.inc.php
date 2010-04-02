<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

// display system alert window if messages are available
if (count($SystemAlertMsgQueque)>0) {
	include "sysalert.display.inc.php";
}
?>
</body>
</html>
<!-- end footer -->

