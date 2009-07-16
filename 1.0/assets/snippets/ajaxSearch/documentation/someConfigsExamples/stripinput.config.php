<?php

// How to improve the default stripInput function to strip some new input strings ?
// In this example we would like strip +someting+ or *something* input strings
// for that we define a new stripInput function: myOwnStripInput
// we reuse the functions stripslashes, stripTags and stripHtml provided by AS
// we add the function stripOtherTags
// in the snippet call add &stripInput=`myOwnStripInput` or defined it in this config file

$debug = -2; // to allow a debug trace with firePhp

function myOwnStripInput($searchString){

    if ($searchString !== ''){  
      // Remove escape characters
      $searchString = stripslashes($searchString);

      // Remove modx sensitive tags
      $searchString = stripTags($searchString);

      // Remove +something+ substring too
      $searchString = stripOtherTags($searchString);  

      // Strip HTML tags
      $searchString = stripHtml($searchString);  
    }  
    return $searchString;
  }

function stripOtherTags($text){
  // Regular expressions to remove +something+
  $modRegExArray[] = '~\+(.*?)\+~';   // +something+
  $modRegExArray[] = '~\*(.*?)\*~';   // *something*

  // Remove modx sensitive tags
  foreach ($modRegExArray as $mReg)$text = preg_replace($mReg,'',$text);
  return $text;
}

?>