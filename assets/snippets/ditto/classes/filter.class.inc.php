<?php

/*
 * Title: Filter Class
 * Purpose:
 *      The Filter class contains all functions relating to filtering,
 *         the removing of documents from the result set
*/

class filter {
    var $array_key, $filtertype, $filterValue, $filterArgs;

// ---------------------------------------------------
// Function: execute
// Filter documents via either a custom filter or basic filter
// ---------------------------------------------------
    function execute($resource, $filter) {
        global $modx;
        foreach ($filter['basic'] as $current) {
            
            if (!is_array($current) || count($current)==0) continue;
            
            if(substr($current['value'],0,5) == '@EVAL') $this->filterValue = $modx->safeEval(substr($current['value'],5));
            else                                         $this->filterValue = $current['value'];
            
            if(strpos($this->filterValue,'[+') !== false) {
                $this->filterValue = $modx->mergePlaceholderContent($this->filterValue);
            }
            
            $this->array_key  = $current['source'];
            $this->filtertype = isset ($current['mode']) ? $current['mode'] : 1;
            $resource = array_filter($resource, array($this, 'basicFilter'));
        }
        foreach ($filter['custom'] as $current) {
            $resource = array_filter($resource, $current);
        }
        return $resource;
    }
    
// ---------------------------------------------------
// Function: basicFilter
// Do basic comparison filtering
// ---------------------------------------------------
    
    function basicFilter ($value) {
            $unset = 1;
            $key = $this->array_key;
            switch ($this->filtertype) {
                case '!=' :
                case 1 :
                    if (!isset ($value[$key]) || $value[$key] != $this->filterValue)
                        $unset = 0;
                    break;
                case '==' :
                case 2 :
                    if ($value[$key] == $this->filterValue)
                        $unset = 0;
                    break;
                case '<' :
                case 3 :
                    if ($value[$key] < $this->filterValue)
                        $unset = 0;
                    break;
                case '>' :
                case 4 :
                    if ($value[$key] > $this->filterValue)
                        $unset = 0;
                    break;
                case '<=' :
                case 5 :
                    if (!($value[$key] <= $this->filterValue))
                        $unset = 0;
                    break;
                case '>=' :
                case 6 :
                    if (!($value[$key] >= $this->filterValue))
                        $unset = 0;
                    break;
                    
                // Cases 7 & 8 created by MODX Testing Team Member ZAP
                case 7 :
                    if (strpos($value[$key], $this->filterValue)===FALSE)
                        $unset = 0;
                    break;
                case 8 :
                    if (strpos($value[$key], $this->filterValue)!==FALSE)
                        $unset = 0;
                    break;    
                
                // Cases 9-11 created by highlander
                case 9 : // case insenstive version of #7 - exclude records that do not contain the text of the criterion
                    if (strpos(strtolower($value[$key]), strtolower($this->filterValue))===FALSE)
                        $unset = 0;
                    break;
                case 10 : // case insenstive version of #8 - exclude records that do contain the text of the criterion
                    if (strpos(strtolower($value[$key]), strtolower($this->filterValue))!==FALSE)
                        $unset = 0;
                    break;
                case 11 : // checks leading character of the field
                    $firstChr = strtoupper(substr($value[$key], 0, 1));
                    if ($firstChr!=$this->filterValue)
                        $unset = 0;
                    break;    
                    //Added by Andchir (http://modx-shopkeeper.ru/)
                case 12 :
                    $inputArr = explode('~',$value[$key]);
                      $check = 0;
                      foreach($inputArr as $val){
                        if(empty($this->filterValue) || empty($val))
                          return;
                        if (strpos($this->filterValue, $val)!==false)
                          $check++;
                      }
                    $unset = $check>0 ? 1 : 0;
                    unset($val,$check);
                break;    
                    //Added by Dmi3yy
                case 13 :
                    $inputArr = explode('~',$value[$key]);
                    $check = 0;
                    foreach($inputArr as $val){
                        if(empty($this->filterValue) || empty($val))
                            return;
                        
                        $iA = explode('~',$this->filterValue);
                        foreach($iA as $ii){
                            $iB = explode(',',$val);
                            foreach($iB as $iii){
                                if (trim($ii) == trim($iii)) $check++;
                            }
                        }
                    }
                    $unset = $check>0 ? 1 : 0;
                    unset($val,$check);
                break;
                    // Cases 21-22 created by Sergey Davydov <webmaster@collection.com.ua> 08.11.2011
                case 21 : // array version of #1 - exlude records that do not in miltiple values such a '65||115' and have output delimeted list by comma
                    if (!isset ($value[$key]) || !in_array($this->filterValue,explode(',',$value[$key])))
                        $unset = 0;
                break;
                case 22 : // array version of #2 - exlude records that in miltiple values such a '65||115' and have output delimeted list by comma
                if (in_array($this->filterValue,explode(',',$value[$key])))
                    $unset = 0;
                break;
        }
        return $unset;
    }
}
