<?php
// get the page to manage files
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('partials.header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/files.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('partials.footer')->render();
