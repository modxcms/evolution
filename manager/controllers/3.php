<?php
// get the page to show document's data
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/document_data.static.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
