<?php
// get the logout processor
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("processors/logout.processor.php");
