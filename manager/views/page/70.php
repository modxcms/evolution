<?php
// get the schedule page
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/site_schedule.static.php");
echo evolutionCMS()->get('ManagerTheme')->view('footer')->render();
