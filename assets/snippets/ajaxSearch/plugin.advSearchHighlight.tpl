/*
  ------------------------------------------------------------------------
  Plugin: advSearch_Highlight v1.3
  ------------------------------------------------------------------------
  Changes:
  18/07/08 - advSearch parameter and pcre modifier added
  29/04/08 - Added highlight markups to select sections where highligth terms
  10/02/08 - Strip_tags added to avoid sql injection and XSS. Use of $_REQUEST 
  01/03/07 - Added fies/updates from forum from users mikkelwe/identity
  (better highlight replacement, additional div around term/removal message)
  ------------------------------------------------------------------------
  Description: When a user clicks on the link from the AjaxSearch results
    the target page will have the terms highlighted.
    
    AdvSearch_Highlight is an advanced "Multi-Part" variant of Search_Highlight
    It allows to frame with "<!--start highlight-->" and <!--end highlight-->
    several parts of the site that will be highligthed
  ------------------------------------------------------------------------
  Created By:  Susan Ottwell (sottwell@sottwell.com)
               Kyle Jaebker (kjaebker@muddydogpaws.com)
               
  Refactored by Coroico (www.modx.wangba.fr)
  ------------------------------------------------------------------------
  Based off the the code by Susan Ottwell (www.sottwell.com)
    http://modxcms.com/forums/index.php/topic,1237.0.html
  ------------------------------------------------------------------------
  CSS:
    The classes used for the highlighting are the same as the AjaxSearch
  ------------------------------------------------------------------------
  Notes:
    To add a link to remove the highlighting and to show the searchterms
    put the following on your page where you would like this to appear:
    
      <!--search_terms-->
    
    Example output for this:
    
      Search Terms: the, template
      Remove Highlighting
      
    Set the following variables to change the text:
    
      $termText - the text before the search terms
      $removeText - the text for the remove link
  ------------------------------------------------------------------------
*/

if (isset($_REQUEST['searched']) && isset($_REQUEST['highlight'])) {

  // Set these to customize the text for the highlighting key
  // --------------------------------------------------------
     $termText = '<div class="searchTerms">Search Terms: ';
     $removeText = 'Remove Highlighting';
  // --------------------------------------------------------

  $highlightText = $termText;

  $searched = strip_tags(urldecode($_REQUEST['searched']));
  $highlight = strip_tags(urldecode($_REQUEST['highlight']));
  
  if (isset($_REQUEST['advsearch'])) $advsearch = strip_tags(urldecode($_REQUEST['advsearch']));
  else $advsearch = 'oneword'; 

  if ($advsearch != 'nowords') {
    
    $output = $modx->documentOutput; // get the parsed document
  
    $part = explode("<!--start highlight-->", $output); // break out the page
  
    $searchArray = array();
    if ($advsearch == 'exactphrase') $searchArray[0] = $searched;
    else $searchArray = explode(' ', $searched);
  
    $highlightClass = explode(' ',$highlight); // break out the highlight classes
  
    $i = 0; // for individual class names
    $nbp = count($part); // number of parts
    $pcreModifier = ($database_connection_charset == 'utf8') ? 'iu' : 'i';
    
    foreach($searchArray as $word) {
      $i++;
      $class = $highlightClass[0].' '.$highlightClass[$i];
  
      $highlightText .= ($i > 1) ? ', ' : '';
      $highlightText .= '<span class="'.$class.'">'.$word.'</span>';
  
      $pattern = '/' . preg_quote($word, '/') . '(?=[^>]*<)/' . $pcreModifier;
      $replacement = '<span class="' . $class . '">${0}</span>';
      for ($p=0;$p<$nbp;$p++){ 
        $section = explode("<!--end highlight-->", $part[$p], 2); // break out the part in section
        if (count($section) == 2) $section[0] = preg_replace($pattern, $replacement, $section[0]);
        $part[$p] = implode("<!--end highlight-->",$section);
      }
    }
  
    $output = implode("<!--start highlight-->", $part);
  
    $removeUrl = $modx->makeUrl($modx->documentIdentifier);
    $highlightText .= '<br /><a href="'.$removeUrl.'" class="ajaxSearch_removeHighlight">'.$removeText.'</a></div>';
  
    $output = str_replace('<!--search_terms-->',$highlightText,$output);
  
    $modx->documentOutput = $output;
  }
}