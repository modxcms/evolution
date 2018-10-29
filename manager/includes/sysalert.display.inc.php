<?php

/**
 *    System Alert Message Queue Display file
 *    Written By Raymond Irving, April, 2005
 *
 *    Used to display system alert messages inside the browser
 *
 */

require_once(dirname(__FILE__) . '/protect.inc.php');

$sysMsgs = '';
$limit = count($SystemAlertMsgQueque);
for ($i = 0; $i < $limit; $i++) {
    $sysMsgs .= $SystemAlertMsgQueque[$i] . '<hr sys/>';
}
// reset message queque
unset($_SESSION['SystemAlertMsgQueque']);
$_SESSION['SystemAlertMsgQueque'] = array();
$SystemAlertMsgQueque = &$_SESSION['SystemAlertMsgQueque'];

if ($sysMsgs != '') {
    // fetch the styles
    ?>
    <link rel="stylesheet" type="text/css" href="<?= MODX_MANAGER_URL ?>media/style/<?= $manager_theme ?>/style.css" />
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        if (parent.modx) {
          parent.modx.popup({
            title: '<?= $_lang['sys_alert'] ?>',
            content: '<?= $modx->db->escape($sysMsgs) ?>',
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
    <?php
}
?>
