<?php

/*
 * Title: Example
 * Purpose:
 *      Example file for basing new Extenders on
*/

// ---------------------------------------------------
// Group: Parameters
// Define any parameters needed in the extender or to override Ditto defaults
// ---------------------------------------------------

if(!isset($param)) $param = 'default';
/*
    Param: param

    Purpose:
     The purpose of your parameter goes here

    Options:
    Any options that your parameter can have go here
    
    Default:
    'default'
*/

// ---------------------------------------------------
// Group: Placeholders
// Defin the values of custom placeholders for access in the tpl like so [+phname+]
// ---------------------------------------------------

$placeholders['example'] = array(array('pagetitle','*'),'exampleFunction','pagetitle');
    // Variable: $placeholders['example']
    // Add the placeholder example to the custom placeholders list 
    // with the source pagetitle in both display and backend using the 
    // exampleFunction callback and pagetitle as the field for QuickEdit.
    // If you only needed the placeholder in the frontent you would just
    // use 'pagetitle'  as the first value of the array. If the callback 
    // was in a class use the array($initialized_class,'member') method.

// ---------------------------------------------------
// Group: Filters
// Define custom or basic filters within the extender to expand Ditto's filtering capabilities
// ---------------------------------------------------

$filters['custom']['exampleFilter'] = array('pagetitle','exampleFilter'); 
    // Variable: $filters['custom']['exampleFilter']
    // Add the filter exampleFilter to the custom filters 
    // list with the source pagetitle and the callback
    // exampleFilter

$filters['parsed'][] = array('exampleFilter' => array('source'=>'id','value'=>'9239423942','mode'=>'2'));
    // Variable: $filters['parsed'][]
    // Add the pre-parsed filter to the parsed filters list with the
    // source as id, the value of 9239423942 and the mode 2

if (!function_exists('exampleFunction')) {
    // wrap functions in !functino_exists statements to ensure that they are not defined twice
    
    // ---------------------------------------------------
    // Function: exampleFunction
    // 
    // Takes the resource array for an individual document
    // and returns the value of the placeholder, in this 
    // case the uppercase version of the pagetitle
    // ---------------------------------------------------
    function exampleFunction($resource) {
        return strtoupper($resource['pagetitle']);
    }
}

if (!function_exists('exampleFilter')) {
    // wrap functions in !functino_exists statements to ensure that they are not defined twice
    
    // ---------------------------------------------------
    // Function: exampleFilter
    // 
    // Takes the resource array for an individual document
    // and asks for the return of a 0 or 1 with 1 removing 
    // the document and 0 leaving it in the result set. 
    // In this case, if the lower case value of the pagetitle
    // is foo, it is removed while all other documents are shown
    // ---------------------------------------------------
    function exampleFilter($resource) {
        if (strtolower($resource['pagetitle'])=='foo') return 1;
        else                                           return 0;
    }
}
