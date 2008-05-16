<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>
<script type="text/javascript" src="media/script/tabpane.js"></script>
<br />
<div class="sectionHeader"><img src="media/style/<?php echo $manager_theme; ?>/images/icons/b02.gif" alt="Help" style="vertical-align: text-bottom;" />&nbsp;&nbsp;<?php echo $_lang['help']; ?></div>
<div class="sectionBody">
    <div class="tab-pane" id="resourcesPane">
        <script type="text/javascript">
            tpResources = new WebFXTabPane( document.getElementById( "resourcesPane" ) );
        </script>
<?php
if ($handle = opendir('help')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
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
    include "./help/$v";
    echo '</div>';
}
?>
    </div>
</div>
