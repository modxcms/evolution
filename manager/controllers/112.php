<?php
// execute/run the module
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
//include_once "header.inc.php";
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("processors/execute_module.processor.php");
//include_once "footer.inc.php";
