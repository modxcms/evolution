<?php

if(!defined('MODX_BASE_PATH')) die('What are you doing? Get out of here!');

$triggerRequiredActions = array(19,23,300,77,101,108,106,107); // when reloadTree = true
$alwaysRefreshActions = array(16,301,78,22,102,76); // Always reload tree
if((in_array($_GET['a'],$triggerRequiredActions) && $_SESSION['elementsInTree']['reloadTree'] == true) 
    || in_array($_GET['a'], $alwaysRefreshActions)) 
{
    $_SESSION['elementsInTree']['reloadTree'] = false;
    $html  = "<!-- elementsInTree Start -->\n";
    $html .= "<script>";
    $html .= "jQuery(document).ready(function() {";
    $html .= "top.reloadElementsInTree();";
    $html .= "})\n";
    $html .= "</script>\n";
    $html .= "<!-- elementsInTree End -->\n";
    $e->output($html);
}
