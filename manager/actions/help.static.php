<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') exit();

?>
<script type="text/javascript" src="media/script/tabpane.js"></script>

<h1><?php echo $_lang['help']; ?></h1>

<div class="sectionBody">
    <div class="tab-pane" id="resourcesPane">
        <script type="text/javascript">
            tpResources = new WebFXTabPane( document.getElementById( "resourcesPane" ), <?php echo $modx->config['remember_last_tab'] == 1 ? 'true' : 'false'; ?> );
        </script>
<?php
if ($handle = opendir('../assets/templates/help')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != ".." && $file != ".svn") {
            $help[] = $file;
        }
    }
    closedir($handle);
}


natcasesort($help);

foreach($help as $k=>$v) {

    $helpname =  substr($v, 0, strrpos($v, '.'));

    $prefix = substr($helpname, 0, 2);
    if(is_numeric($prefix)) {
        $helpname =  substr($helpname, 2, strlen($helpname)-1 );
    }

    $helpname = str_replace('_', ' ', $helpname);
    echo '<div class="tab-page" id="tab'.$v.'Help">';
    echo '<h2 class="tab">'.$helpname.'</h2>';
    echo '<script type="text/javascript">tpResources.addTabPage( document.getElementById( "tab'.$v.'Help" ) );</script>';
    include "../assets/templates/help/$v";
    echo '</div>';
}
?>
    </div>
</div>
