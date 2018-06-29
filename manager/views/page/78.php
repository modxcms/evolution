<?php
// get the edit snippet action
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/mutate_htmlsnippet.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
