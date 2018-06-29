<?php
// get the delete role page
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("processors/delete_role.processor.php");
