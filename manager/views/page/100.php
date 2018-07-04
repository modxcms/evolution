<?php
// change the plugin priority
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
//include_once "header.inc.php"; - in action file
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/mutate_plugin_priority.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('partials.footer')->render();
