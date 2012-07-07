<?php
if(IN_MANAGER_MODE != 'true') exit();

// display system alert window if messages are available
if (count($SystemAlertMsgQueque)>0) {
	include "sysalert.display.inc.php";
}
?>
</body>
</html>
<!-- end footer -->

