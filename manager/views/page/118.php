<?php
// call settings ajax include
ob_clean();
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("includes/mutate_settings.ajax.php");
