<?php
// get the page to show document's data
echo $modx->get('ManagerTheme')->view('header')->render();
include_once(includeFileProcessor("actions/document_data.static.php",$manager_theme));
echo $modx->get('ManagerTheme')->view('footer')->render();
