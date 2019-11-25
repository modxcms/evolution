<?php
// get the tv-rank action
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
echo evolutionCMS()->get('ManagerTheme')->view('partials.header')->render();
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/mutate_tv_rank.dynamic.php");
echo evolutionCMS()->get('ManagerTheme')->view('partials.footer')->render();
