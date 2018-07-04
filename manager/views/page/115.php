<?php
// get event log details viewer
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('partials.header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/eventlog_details.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('partials.footer')->render();
