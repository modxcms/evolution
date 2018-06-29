<?php
// get the wait page (so the tree can reload)
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/wait.static.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
