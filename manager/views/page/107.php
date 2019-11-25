<?php
// get the new module action
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('partials.header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/mutate_module.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('partials.footer')->render();
