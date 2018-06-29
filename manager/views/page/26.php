<?php
// get the cache emptying processor
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/refresh_site.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
