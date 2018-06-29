<?php
// get the test page
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("test_page.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
