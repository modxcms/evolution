<?php
// For a global parameter initialisation use the following syntax $__param = 'value';
// To overwrite parameter snippet call use $param = 'value';

$__stripOutput = 'saveImage';

// StripInput user function. 
// Uncomment and complete the core function and choose your own function name
// string functionName(string searchstring)
// functionName : name of stripInput function passed as &stripInput parameter
// searchstring : string php variable name as searchString input value
// advSearch : string advSearch variable as advSearch parameter and return value
// return the filtered searchString value
/*
function myStripInput($searchString, &$advSearch){
  $advSearch = 'exactphrase';
  return $searchString;
}
*/
// StripOutput user function
// Uncomment and complete the core function and choose your own function name
// string functionName(string results)
// functionName : name of stripOutput function passed as &stripOutput parameter
// results : string php variable name as results
// return the filtered results

function saveImage($results){ 

  // replace line Breaking by space
  $results = stripLineBreaking($results);
  // strip other html tags
  $results = stripHtmlExceptImage($results);
  // strip javascript tags
  $results = stripJscripts($results);
  
  return $results;
}

// searchWordList user function
// Uncomment and complete the core function and choose your own function name
// string functionName()
// functionName : name of searchWordList function passed as &searchWordList parameter
// return a comma separated list of words

function enSearchWordList(){ 

  $list = "guatemala,samassekou,equateur,cromer,zatreanu,burnett";
  return $list;
}

?>