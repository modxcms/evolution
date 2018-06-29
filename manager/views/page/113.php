<?php
// get the module resources (dependencies) action
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/mutate_module_resources.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
