<?php
// get the table optimizer/truncate processor
extract(evolutionCMS()->get('ManagerTheme')->getViewAttributes(), EXTR_OVERWRITE);

/**
 * @TODO: White list of tables allowed for truncate operation
 */
include_once evolutionCMS()->get('ManagerTheme')->getFileProcessor("processors/optimize_table.processor.php");
