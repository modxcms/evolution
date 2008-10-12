<?php
// For a global parameter initialisation use the following syntax $__param = 'value';
// To overwrite parameter snippet call use $param = 'value';

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
/*
function myStripOutput($results){ 

  return $results;
}
*/
// searchWordList user function
// Uncomment and complete the core function and choose your own function name
// string functionName()
// functionName : name of searchWordList function passed as &searchWordList parameter
// return a comma separated list of words
/*function searchWordList($params){ 

  switch($params[0]){ 
    case '61':
      $list = "primary,school,education,children,teacher,africa,litteracy,bicycle";
      break;         
    case '62':
      $list = "primaire,école,éducation,enfants,professeur,afrique,littérature,bicyclette";
      break; 
  }
  return $list;
}
*/
?>