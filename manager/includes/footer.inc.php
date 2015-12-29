<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
global $SystemAlertMsgQueque;
// display system alert window if messages are available
if (count($SystemAlertMsgQueque)>0) {
	include "sysalert.display.inc.php";
}
?>
	<script type='text/javascript'>      
        document.body.addEventListener('keydown', function (e) {
            if ((e.which == '115' || e.which == '83' ) && (e.ctrlKey || e.metaKey)) {
                document.getElementById( 'Button1' ).getElementsByTagName( 'a' )[0].click();
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
<!-- end footer -->

