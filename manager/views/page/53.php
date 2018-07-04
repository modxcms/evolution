<?php
// get the settings editor
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('partials.header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/sysinfo.static.php");
echo evolutionCMS()->get('ManagerTheme')->view('partials.footer')->render();
