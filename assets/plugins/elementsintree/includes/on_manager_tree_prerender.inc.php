<?php

if(!defined('MODX_BASE_PATH')) die('What are you doing? Get out of here!');

// use icons
$ph['tabPadding'] = ($useIcons == 'yes') ? '10px' : '9px';

// tree buttons in tab
$ph['treeButtonsInTab_js']  = ($treeButtonsInTab == 'yes') ? file_get_contents($eit_base_path.'assets/js_treeButtonsInTab.tpl') : '';
$ph['treeButtonsInTab_css'] = ($treeButtonsInTab == 'yes') ? file_get_contents($eit_base_path.'assets/css_treeButtonsInTab.tpl') : '';
$ph['tabTreeTitle'] = '<i class="fa fa-sitemap"></i>';

// Prepare lang-strings
$unlockTranslations = array();
$unlockTranslations['msg']   = $_lang["unlock_element_id_warning"];
$unlockTranslations['type1'] = $_lang["lock_element_type_1"];
$unlockTranslations['type2'] = $_lang["lock_element_type_2"];
$unlockTranslations['type3'] = $_lang["lock_element_type_3"];
$unlockTranslations['type4'] = $_lang["lock_element_type_4"];
$unlockTranslations['type5'] = $_lang["lock_element_type_5"];
$unlockTranslations['type6'] = $_lang["lock_element_type_6"];
$unlockTranslations['type7'] = $_lang["lock_element_type_7"];
$unlockTranslations['type8'] = $_lang["lock_element_type_8"];

// start main output
$output = file_get_contents($eit_base_path.'assets/txt_content.tpl');
$output = $modx->parseText($output,$ph);
$e->output($output);
