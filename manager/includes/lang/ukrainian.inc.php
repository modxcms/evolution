<?php
/**
 * MODX Manager language file
 *
 * @version 1.1
 * @date 2015/02/15
 * @author The MODX Project Team
 *
 * @language Ukraine
 * @package modx
 * @subpackage manager
 *
 * Please commit your language changes on Transifex (https://www.transifex.com/projects/p/modx-evolution/) or on GitHub (https://github.com/modxcms/evolution).
 */

$filename = dirname(__FILE__) . '/russian-UTF8.inc.php';
$contents = file_get_contents($filename);
eval('?>' . $contents);
$modx_lang_attribute = 'uk'; // Manager HTML/XML Language Attribute see http://en.wikipedia.org/wiki/ISO_639-1
setlocale (LC_ALL, 'uk_UA.UTF-8');
