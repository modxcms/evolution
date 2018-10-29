<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
global $SystemAlertMsgQueque;
// display system alert window if messages are available
if (!empty($SystemAlertMsgQueque)) {
    include MODX_MANAGER_PATH . 'includes/sysalert.display.inc.php';
}
?>
<script type='text/javascript'>
  document.body.addEventListener('keydown', function(e) {
    if ((e.which === 115 || e.which === 83) && (e.ctrlKey || e.metaKey) && !e.altKey) {
      var Button1 = document.querySelector('a#Button1') || document.querySelector('#Button1 > a');
      if (Button1) Button1.click();
      e.preventDefault();
    }
  });
</script>
<?php
if (in_array($modx->manager->action, array(
    85,
    27,
    4,
    72,
    13,
    11,
    12,
    87,
    88
))) {
    echo $modx->manager->loadDatePicker($modx->config['mgr_date_picker_path']);
}
?>
</body>
</html>
<!-- end footer -->
