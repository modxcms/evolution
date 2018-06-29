<?php
// get the edit role page
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/mutate_role.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
