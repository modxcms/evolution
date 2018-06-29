<?php
// for ajax-requests
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("actions/mutate_categories.dynamic.php");
