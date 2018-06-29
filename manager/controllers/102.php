<?php
// get the edit plugin action
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/mutate_plugin.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
