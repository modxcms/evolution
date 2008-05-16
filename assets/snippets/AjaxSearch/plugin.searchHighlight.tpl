/*
  ------------------------------------------------------------------------
  Plugin: Search_Highlight v1.2.0.2
  ------------------------------------------------------------------------
  Changes:
  10/02/08 - Strip_tags added to avoid sql injection and XSS. Use of $_REQUEST 
  01/03/07 - Added fies/updates from forum from users mikkelwe/identity
  (better highlight replacement, additional div around term/removal message)
  ------------------------------------------------------------------------
  Description: When a user clicks on the link from the AjaxSearch results
    the target page will have the terms highlighted.
  ------------------------------------------------------------------------
  Created By:  Susan Ottwell (sottwell@sottwell.com)
               Kyle Jaebker (kjaebker@muddydogpaws.com)
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

if(isset($_REQUEST['searched']) && isset($_REQUEST['highlight'])) {

  // Set these to customize the text for the highlighting key
  // --------------------------------------------------------
     $termText = '<div class="searchTerms">Search Terms: ';
     $removeText = 'Remove Highlighting';
  // --------------------------------------------------------

  $highlightText = $termText;

  $searched = strip_tags(urldecode($_REQUEST['searched']));
  $highlight = strip_tags(urldecode($_REQUEST['highlight']));
  $output = $modx->documentOutput; // get the parsed document

  $body = explode("<body>", $output); // break out the head

  $searchArray = explode(' ', $searched); // break apart the search terms

  $highlightClass = explode(' ',$highlight); // break out the highlight classes

  $i = 0; // for individual class names

  foreach($searchArray as $word) {
    $i++;
    $class = $highlightClass[0].' '.$highlightClass[$i];

    $highlightText .= ($i > 1) ? ', ' : '';
    $highlightText .= '<span class="'.$class.'">'.$word.'</span>';

    $pattern = '/' . preg_quote($word) . '(?=[^>]*<)/i';
    $replacement = '<span class="' . $class . '">${0}</span>';
    $body[1] = preg_replace($pattern, $replacement, $body[1]);
  }

  $output = implode("<body>", $body);

  $removeUrl = $modx->makeUrl($modx->documentIdentifier);
  $highlightText .= '<br /><a href="'.$removeUrl.'" class="ajaxSearch_removeHighlight">'.$removeText.'</a></div>';

  $output = str_replace('<!--search_terms-->',$highlightText,$output);

  $modx->documentOutput = $output;
}