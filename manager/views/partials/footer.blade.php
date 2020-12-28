<?php
global $SystemAlertMsgQueque;
// display system alert window if messages are available
if (count($SystemAlertMsgQueque) > 0) {
    include MODX_MANAGER_PATH . 'includes/sysalert.display.inc.php';
}
?>
@stack('scripts.bot')
<script>
  document.body.addEventListener('keydown', function(e) {
    if ((e.which === 115 || e.which === 83) && (e.ctrlKey || e.metaKey) && !e.altKey) {
      var Button1 = document.querySelector('a#Button1') || document.querySelector('#Button1 > a');
      if (Button1) Button1.click();
      e.preventDefault();
    }
  });
</script>
@if(ManagerTheme::isLoadDatePicker())
    {!! $modx->getManagerApi()->loadDatePicker($modx->getConfig('mgr_date_picker_path')) !!}
@endif

@include('manager::partials.debug')
{!! $modx->getRegisteredClientScripts() !!}
</body>
</html>
