<?php
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/web_access_permissions.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
