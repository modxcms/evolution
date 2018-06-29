<?php
// get the save web user processor
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("processors/save_web_user.processor.php");
