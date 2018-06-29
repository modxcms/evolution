<?php
// get the new user page
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/mutate_user.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
