// -----------------------
// Snippet: FlexSearchForm
// -----------------------
// Version: 0.6j
// Date: 2005.02.01
// jaredc@honeydewdesign.com
//
// This snippet was designed to create a search form
// that is highly flexible in how it is presented. It
// can be used as both a small, subtle, persistent
// search field element, as well as present the search
// results. All elements are branded with classes
// for easy CSS styling.

// CONFIGURE

  // MAIN SNIPPET SETUP OPTIONS
  // --------------------------

   // $searchStyle [ 'relevance' | 'partial' ]
   // This option allows you to decide to use a faster,
   // relevance sorted search ('relevance') which WILL NOT
   // inlclude partial matches. Or use a slower, but
   // more inclusive search ('partial') that will include
   // partial matches. Results will NOT be sorted based on
   // relevance.
   //
   // This option contributed by Rich from Snappy Graffix Media to 
   // allow partial matching and LIKE matching of the search term.
   // sam@snappygraffix.com
   $searchStyle = 'relevance';
   
   // $useAllWords [ true | false ]
   // If you want only documents which contain ALL words in the 
   // search string, set to true. Otherwise, the search will return
   // all pages with ONE or more of the search words (which can be 
   // a LOT more pages).
   $useAllWords = true;

   // $showSearchWithResults [1 | 0]
   // If you would like to turn off the search
   // form when showing results you can set
   // this to false. Can also be set in template
   // by using the snippet variable: FSF_showForm
   // like this (1=true, 0=false):
   // [[FlexSearchForm?FSF_showForm=0]]
   $showSearchWithResults = 1;

   // $resultsPage [int]
   // The default behavior is to show the results on
   // the current page, but you may define the results
   // page any way you like. The priority is:
   //
   // 1- snippet variable - set in page template like this:
   //    [[FlexSearchForm?FSF_landing=int]]
   //    where int is the page id number of the page you want
   //    your results on
   // 2- querystring variable FSF_form
   // 3- variable set here
   // 4- use current page
   //
   // This is VERY handy when you want to put the search form in
   // a discrete and/or small place on your page- like a side
   // column, but don't want all your results to show up there!
   // Set to results page or leave 0 as default
   $resultsPage = 0;

   // $showResults [1 | 0] (as passed in snippet variable ONLY)
   // You can define whether this snippet will show the results
   // of the search with it. Do this by assigning the snippet
   // variable FSF_showResults as:
   // [[FlexSearchForm?FSF_showResults=0]]
   //
   // This is useful in situations where you want to show the
   // search results in a different place than the search form.
   // In that type of situation, you would include two of these
   // snippets on the same page, one showing results, and one
   // showing the form.
   //
   // Default is true.

   // $grabMax [ int ]   
   // Set to the max number of records you would like on
   // each page. Set to 0 if unlimited.
   $grabMax = 10;

   // $pageLinkSeparator [ string ]
   // What you want, if anything, between your page link numbers
   $pageLinkSeparator = " | ";

   // $stripHTML [ true | false ]
   // Allow HTML characters in the search? Probably not.
   $stripHTML = true;
		
   // $stripSnip [ true | false ]
   // Strip out snippet calls etc from the search string?
   $stripSnip = true;
   
   // $stripSnippets [ true | false ]
   // Strip out snippet names so users will not be able to "search"
   // to see what snippets are used in your content. This is a 
   // security benefit, as users will not be able to search for what pages
   // use specific snippets.
   $stripSnippets = true;
   
   // $xhtmlStrict [ true | false ]
   // If you want this form to validate as XHTML Strict compliance, then
   // this needs to be true, but IE has a fieldset bug that I don't know
   // a workaround for. So you can set this to false to avoid it.
   $xhtmlStrict = false;

   // $minChars [ int ]
   // Minimum number of characters to require for a word to be valid for
   // searching. MySQL will typically NOT search for words with less than
   // 4 characters (relevance mode). If you have $useAllWords = true and
   // a three or fewer word appears in the search string, the results will
   // always be 0. Setting this drops those words from the search in THAT
   // CIRCUMSTANCE ONLY (relevance mode, useAllWords=true).
   $minChars = 4;


  // LANGUAGE AND LABELS
  // --------------------------

   // $resultsIntroFailure
   // If nothing is found for the search. You should give the user
   // some indication that the search was a failure.
   $resultsIntroFailure = 'There were no search results. Please try using more general terms to get more results.';

   // $searchButtonText [string]
   // Label the search button what
   // you wish
   $searchButtonText = 'Go!';
   
   // $boxText [ string ]
   // What, if anything, you want to have in the search box when the
   // form first loads. When clicked, it will disappear. This uses 
   // JavaScript. If you don't want this feature or the JavaScript,
   // just set to empty string: ''
   $boxText = 'Search here...';

   // $introMessage [ string ]
   // This is the text that will show up if the person arrives
   // at the search page without having done a search first.
   // You can leave this as an empty string if you like.
   $introMessage = 'Please enter a search term to begin your search.';
   
   // $resultsFoundTextSingle, $resultsFoundTextMultiple [ string patttern ]
   // The string that will tell the user how many results were found.
   // two variables will be provided at runtime:
   // %d    The number of results found (integer)
   // %s    The search string itself.
   $resultsFoundTextSingle = '%d result found for "%s".';
   $resultsFoundTextMultiple = '%d results found for "%s".';
   
   // $paginationTextSinglePage and $paginationTextMultiplePages [ string ]
   // The text that comes before the links to other pages. In this
   // example, "Result pages: " was the $paginationTextMultiplePages:
   // Result pages: 1 | 2 | 3 | 4
   // Page numbers will only appear if there is more than one page.
   $paginationTextSinglePage = '';
   $paginationTextMultiplePages = 'Result pages: ';

// Styles
// These styles are included in this snippet:
//
// .FSF_form{}
// .FSF_input {}
// .FSF_submit {}
//
// .FSF_SearchResults {}
// .FSF_resultsIntroFailure{}
// .FSF_result {}
// .FSF_resultLink {}
// .FSF_resultDescription {}
// .FSF_pagination
// .FSF_intro

// -------------
// End configure
// -------------

// establish whether to show the form or not
$showSearchWithResults = (isset($FSF_showForm))? $FSF_showForm : $showSearchWithResults;

// establish whether to show the results or not
$showResults = (isset($FSF_showResults))? $FSF_showResults : true;

// establish results page
if (isset($FSF_landing)){ // set in snippet
  $searchAction = "[~".$FSF_landing."~]";
} elseif ($resultsPage > 0){ // locally set
  $searchAction = "[~".$resultsPage."~]";
}  else { //otherwise
  $searchAction = "[~".$modx->documentIdentifier."~]";
}

// Set newline variable
$newline = "\n";

// Initialize search string
$searchString = '';

// CLEAN SEARCH STRING
if ( isset($_POST['search']) || isset($_GET['FSF_search']) ){
  // Prefer post to get
  $searchString = (isset($_POST['search']))? $_POST['search'] : urldecode($_GET['FSF_search']) ;
  // block sensitive search patterns
  $searchString =
  (
  $searchString != "{{" &&
  $searchString != "[[" &&
  $searchString != "[!" &&
  $searchString != "[(" &&
  $searchString != "[~" &&
  $searchString != "[*" 
  )
  ?
  $searchString : "" ;

  // Remove dangerous tags and such

  // Strip HTML too
  if ($stripHTML){
    $searchString = strip_tags($searchString);
  }

  // Regular expressions of things to remove from search string
  $modRegExArray[] = '~\[\[(.*?)\]\]~';   // [[snippets]]
  $modRegExArray[] = '~\[!(.*?)!\]~';     // [!noCacheSnippets!]
  $modRegExArray[] = '!\[\~(.*?)\~\]!is'; // [~links~]
  $modRegExArray[] = '~\[\((.*?)\)\]~';   // [(settings)]
  $modRegExArray[] = '~{{(.*?)}}~';       // {{chunks}}
  $modRegExArray[] = '~\[\*(.*?)\*\]~';   // [*attributes*]
  
  // Remove modx sensitive tags
  if ($stripSnip){
    foreach ($modRegExArray as $mReg){
      $searchString = preg_replace($mReg,'',$searchString);
    }
  }

  // Remove snippet names
  if ($stripSnippets && $searchString != ''){
    // get all the snippet names
    $tbl = $modx->dbConfig['dbase'] . "." . $modx->dbConfig['table_prefix'] . "site_snippets";
    $snippetSql = "SELECT $tbl.name FROM $tbl;";
    $snippetRs = $modx->dbQuery($snippetSql);
    $snippetCount = $modx->recordCount($snippetRs);
    $snippetNameArray = array();
    for ($s = 0; $s < $snippetCount; $s++){
      $thisSnippetRow = $modx->fetchRow($snippetRs);
      $snippetNameArray[] = strtolower($thisSnippetRow['name']);
    }
    // Split search into strings
    $searchWords = explode(' ',$searchString);
    $cleansedWords = '';
    foreach($searchWords as $word){
      if ($word != '' && 
          !in_array(strtolower($word),$snippetNameArray) &&
            ((!$useAllWords) ||
            ($searchStyle == 'partial') ||
            (strlen($word) >= $minChars && $useAllWords && $searchStyle == 'relevance'))
         ){
        $cleansedWords .= $word.' ';
      }
    }
    // Remove last space
    $cleansedWords = substr($cleansedWords,0,(strlen($cleansedWords)-1));
    
    $searchString = $cleansedWords;
  }

} // End cleansing search string

// check querystring
$validSearch = ($searchString != '')? true : false ;

//check for offset
$offset = (isset($_GET['FSF_offset']))? $_GET['FSF_offset'] : 0;

// initialize output
$SearchForm = '';

// establish form
if (($validSearch && ($showSearchWithResults)) || $showSearchWithResults){
  $SearchForm .= '<form class="FSF_form" action="'.$searchAction.'" method="post">';
  $SearchForm .= ($xhtmlStrict)? '<fieldset><legend>Search</legend>' : '' ;
  // decide what goes in search box
  $searchBoxVal = ($searchString == '' && $boxText != '')? $boxText : $searchString ;
  $SearchForm .= '<input class="FSF_input" type="text" name="search" value="'.$searchBoxVal.'"  ';
  $SearchForm .= ($boxText)? 'onfocus="this.value=(this.value==\''.$boxText.'\')? \'\' : this.value ;" />' : '/>';
  $SearchForm .= '<input class="FSF_submit" type="submit" name="sub" value="'.$searchButtonText.'" />';
  $SearchForm .= ($xhtmlStrict)? '</fieldset>' : '';
  $SearchForm .= '</form>'.$newline;
}

if ($showResults) {
  if($validSearch) {
    $search = explode(" ", $searchString);
    $tbl = $modx->dbConfig['dbase'] . "." . $modx->dbConfig['table_prefix'] . "site_content";

    if ($searchStyle == 'partial'){
      $sql = "SELECT id, pagetitle, description ";
      $sql .= "FROM $tbl ";
      $sql .= "WHERE ";
      if (count($search)>1 && $useAllWords){
        foreach ($search as $searchTerm){
          $sql .= "(pagetitle LIKE '%$searchString%' OR description LIKE '%$searchString%' OR content LIKE '%$searchTerm%') AND ";
        }
      } else {
        $sql .= "(pagetitle LIKE '%$searchString%' OR description LIKE '%$searchString%' OR content LIKE '%$searchString%') AND ";
      }
      $sql .= "$tbl.published = 1 AND $tbl.searchable=1 AND $tbl.deleted=0;";
    } else {
      $sql = "SELECT id, pagetitle, description ";
      $sql .= "FROM $tbl WHERE ";
      if (count($search)>1 && $useAllWords){
        foreach ($search as $searchTerm){
          $sql .= "MATCH (pagetitle, description, content) AGAINST ('$searchTerm') AND ";
        }
      } else {
        $sql .= "MATCH (pagetitle, description, content) AGAINST ('$searchString') AND ";
      }
      $sql .= "$tbl.published = 1 AND $tbl.searchable=1 AND $tbl.deleted=0;";
    }

    $rs = $modx->dbQuery($sql);
    $limit = $modx->recordCount($rs);

    if($limit>0) {
      $SearchForm .= '<div class="FSF_searchResults">'.$newline;

      // pagination
      if ($grabMax > 0){
        $numResultPages = ceil($limit/$grabMax);
        $resultPageLinks = '<span class="FSF_pages">';
        $resultPageLinks .= ($limit>$grabMax)? $paginationTextMultiplePages : $paginationTextSinglePage ;
        $resultPageLinkNumber = 1;
        for ($nrp=0;$nrp<$limit && $limit > $grabMax;$nrp+=$grabMax){
          if($offset == ($resultPageLinkNumber-1)*$grabMax){
            $resultPageLinks .= $resultPageLinkNumber;
          } else {
            $resultPageLinks .= '<a href="[~' . $modx->documentObject['id'] . '~]&FSF_offset=' . $nrp . '&FSF_search=' . urlencode($searchString) . '">' . $resultPageLinkNumber . '</a>';
          }
          $resultPageLinks .= ($nrp+$grabMax < $limit)? $pageLinkSeparator : '' ;
          $resultPageLinkNumber++;
        }
        $resultPageLinks .= "</span>".$newline;
        $SearchForm .= '<p class="FSF_pagination">';
        $resultsFoundText = ($limit > 1)? $resultsFoundTextMultiple : $resultsFoundTextSingle ;
        $SearchForm .= sprintf($resultsFoundText,$limit,$searchString);
        $SearchForm .= '<br />'.$resultPageLinks."</p>".$newline;
      } // end if grabMax

      // search results
      $useLimit = ($grabMax > 0)? $offset+$grabMax : $limit;
      for ($y = $offset; ($y < $useLimit) && ($y<$limit); $y++) {
        $moveToRow = mysql_data_seek($rs,$y);
        $SearchFormsrc=$modx->fetchRow($rs);
        $SearchForm.='<div class="FSF_result">'.$newline;
        $SearchForm.='<a class="FSF_resultLink" href="[~'.$SearchFormsrc['id'].'~]" title="' . $SearchFormsrc['pagetitle'] . '">' . $SearchFormsrc['pagetitle'] . "</a>".$newline;
        $SearchForm.=$SearchFormsrc['description']!='' ? '<span class="FSF_resultDescription">' . $SearchFormsrc['description'] . "</span>".$newline : "" ;
        $SearchForm.='</div><!--end FlexSearchResult-->'.$newline;
      }
      $SearchForm.='<p class="FSF_pagination">'.$resultPageLinks.'</p>';
      $SearchForm.='</div><!--end FlexSearchResults-->'.$newline;
    } else {
      $SearchForm.='<p class="FSF_resultsIntroFailure">'.$resultsIntroFailure.'</p>';
    } // end if records found

  } else if (!$validSearch && isset($_POST['sub'])) {

    // message to show if search was performed but for something invalid
    $SearchForm .= '<p class="FSF_resultsIntroFailure">'.$resultsIntroFailure.'</p>';
    
  } else { // end if validSearch

    $SearchForm .= '<p class="FSF_intro">'.$introMessage.'</p>';

  } // end if not validSearch
} // end if showResults

return $SearchForm;
