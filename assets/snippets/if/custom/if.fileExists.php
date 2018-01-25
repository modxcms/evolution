<?php
/*
 Structure of a Custom Condition
 
 function nameOfCustomCondition($subject, $operand) {
    // Add your conditions here
    $result = $subject == $operand ? true : false;

    // If $operand is not used, return boolean
    return $result;

    // If $operand is used, return array
    // "true" causes $i++
    return array($result, true);
 }
 
*/

function fileExists($subject, $operand) {
    return file_exists(MODX_BASE_PATH.$subject);
}