<?php

/**
 *    System Alert Message Queue Display file
 *    Written By Raymond Irving, April, 2005
 *
 *    Used to display system alert messages inside the browser
 *
 */

$sysMsgs = '';
$limit = count($SystemAlertMsgQueque);
for ($i = 0; $i < $limit; $i++) {
    $sysMsgs .= $SystemAlertMsgQueque[$i] . '<hr sys/>';
}
// reset message queque
unset($_SESSION['SystemAlertMsgQueque']);
$_SESSION['SystemAlertMsgQueque'] = array();
$SystemAlertMsgQueque = &$_SESSION['SystemAlertMsgQueque'];

if ($sysMsgs != '') : ?>
    <link rel="stylesheet" type="text/css" href="<?=MODX_MANAGER_URL;?>media/style/<?=ManagerTheme::getTheme();?>/style.css" />
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        if (parent.modx) {
          parent.modx.popup({
            title: '<?php echo $_lang['sys_alert']; ?>',
            content: '<?php echo $modx->getDatabase()->escape($sysMsgs);?>',
            wrap: document.body,
            type: 'warning',
            width: '400px',
            hide: 0,
            hover: 0,
            overlay: 1,
            overlayclose: 1
          });
        }
      });
    </script>
<? endif; ?>
