<?php
// get the home page
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/welcome.static.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
