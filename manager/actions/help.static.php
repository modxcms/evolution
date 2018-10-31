<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
$help = glob(__DIR__ . '/help/' . '*.phtml');
natcasesort($help);
$content = [];

foreach ($help as $index => $file) {
    $fileName = basename($file, '.phtml');

    preg_match('/^(\d+)(.*)$/', $fileName, $prefix);
    if (isset($prefix[1]) && is_numeric($prefix[1])) {
        $helpname = $prefix[2];
    } else {
        $helpname = $fileName;
    }

    $hnLower = strtolower($helpname);
    $helpname = isset($_lang[$hnLower]) ? $_lang[$hnLower] : str_replace('_', ' ', $helpname);

    $tab = '<div class="tab-page" id="tab' . $index . 'Help">' .
        '<h2 class="tab">' . $helpname . '</h2>' .
        '<script type="text/javascript">tp.addTabPage( document.getElementById( "tab' . $index . 'Help" ) );</script>';

    ob_start();
    include_once $file;
    $tab .= ob_get_contents();
    ob_end_clean();

    $tab .= '</div>';

    $content[] = $tab;
}
?>

<h1><?=$_style['page_help'] . $_lang['help'] ?></h1>

<div class="sectionBody">
    <div class="tab-pane" id="helpPane">
        <script type="text/javascript">
          tp = new WebFXTabPane(
            document.getElementById("helpPane"),
              <?=(bool)$modx->getConfig('remember_last_tab') ? 'true' : 'false' ?>
          );
        </script>
        <?=implode('', $content) ?>
    </div>
</div>
<script>
  if (window.location.hash == '#version_notices') tp.setSelectedIndex(1);
</script>
