/* -------------------------------------------------------------
:: Snippet: Ajax Search
----------------------------------------------------------------
    Short Description: 
        Ajax-driven search form

    Version:
        1.1

    Created by:
	    Jason Coward (opengeek - jason@opengeek.com)
	    Kyle Jaebker (kylej - kjaebker@muddydogpaws.com)
	    Ryan Thrash (rthrash - ryan@vertexworks.com)
	    
	    Live Search by Thomas (Shadock)

    Date:
        19-Sep-06

    Required Usage:
        [!AjaxSearch!]

    Changelog:
        18-Sep-06 -- Added code to only show results for allowed pages
        05-May-06 -- Added liveSearch functionality and new parameter
        21-Apr-06 -- Added code to make it compatible with tagcloud snippet
        20-Apr-06 -- Added code from eastbind & japanese community for other
          language searching
        4-Apr-06 -- Added search term highlighting
        1-Apr-06 -- initial commit into SVN
        30-Mar-06 -- initial work based on FSF_ajax from KyleJ

----------------------------------------------------------------
:: Description
----------------------------------------------------------------
    Ajax search form that degrades (works without JS enabled),
    and that provides for either live search or click-to-submit.
    Results returned to the current page without reloading.
	[TBD: Also searches Template Variable content (TVs).]

----------------------------------------------------------------
:: Parameters
----------------------------------------------------------------

    &AS_showForm [1 | 0] (optional)
        Show the search form with the results. Default is 1 (true)
        
    &AS_landing [int] (optional)
        Document id you would like the search to show on. (For non-ajax search)
        
    &AS_showResults [1 | 0] (optional)
        Show the results with the snippet. (For non-ajax search)
        
    &extract [1 | 0] (optional)
        Show the search words highlighting.
        
    &ajaxSearch [1 | 0] (optional)
        Use the ajaxSearch. Default is 1 (true)

    &ajaxSearchType [1 | 0] (optional)
        There are two forms of the ajaxSearch.
        0 - The form button is displayed and searching does not start until the
            button is pressed by the user.
        1 - There is no form button, the search is started automatically as the
            user types

    &ajaxMax [int] (optional)
        The number of results you would like returned from the ajax search.
        
    &grabMax [int] (optional)
        The number of results per page returned for non-ajax search
        and for the more results page.
        
    &showMoreResults [1 | 0] (optional)
        If you want a link to show all of the results from the ajax search.
        
    &moreResultsPage [int] (optional)
        Page you want the more results link to point to. This page should
        contain another call to this snippet for displaying results.
        
    &addJscript [1 | 0] (Default: 1)
        If you want the prototype and the scriptaculous libraries added
        to the header of your pages automatically set this to 1.  Set to
        0 if you do not want them inculded automatically.


----------------------------------------------------------------
:: CSS                         
----------------------------------------------------------------
    The following items are used to style the starting form and
    ajax result container.

    #ajaxSearch_form - id of the search form
    #ajaxSearch_input - id of the input box on the form
    #ajaxSearch_submit - id of the submit button
    #ajaxSearch_output - id of the div that the ajax results are returned in
    
    The following items are used to style the reults when the user
    does not have javascript or they have clicked the more results link
    
    #ajaxSearch_resultListContainer - id of the results container
    .ajaxSearch_paging - class for span of result pages listing
    .ajaxSearch_pagination - class for pagination paragraph
    .ajaxSearch_result - class for result container div
    .ajaxSearch_resultLink - class for result link
    .ajaxSearch_resultDescription - class for result description span
    .ajaxSearch_extract - class for content extract div (for highlighting)
    .ajaxSearch_highlight1,2,3 - classes for result highlighting.  You need to
        create as many classes as terms you think a user will search for.
    .ajaxSearch_resultsIntroFailure - class for no results paragraph
    .ajaxSearch_intro - class for intro paragraph

    The following items are used to style the results returned by
    the ajax request.

    .AS_ajax_result - class for the result container div
    .AS_ajax_resultLink - class for the result link
    .AS_ajax_resultDescription - class for the result description span
    .AS_ajax_extract - class for the content extract div (for highlighting)
    .AS_ajax_hightlight1,2,3 - classes for result highlighting.  You need to
        create as many classes as terms you think a user will search for.
    .AS_ajax_more - class for more search results div
    .AS_ajax_resultsIntroFailure - class for no results paragraph


----------------------------------------------------------------
:: Example Calls              
----------------------------------------------------------------
[!AjaxSearch!]
    A basic default call that renders a search form with the default 
    images and parametes

[!AjaxSearch? &showMoreResults=`1` &moreResultsPage=`25`!]
    Allows a link to a full-page search to go to another page.
    
[!AjaxSearch? &ajaxMax=`10` &extract=`0`!]
    Overrides the number of maximum results returned and removes search
    term highlighting.
    

----------------------------------------------------------------
:: Credits
----------------------------------------------------------------
   Based on Flex Search Form (FSF) by jardc@honeydewdsign.com 
   as modified by KyleJ (kjaebker@muddydogpaws.com).
   
   Also based on degradible live search demos at:
     http://orderedlist.com/articles/howto-animated-live-search/
     http://www.gizax.it/experiments/AHAH/degradabile/test/liveSearch.html
     
   The search highlighting was based off of the original FSF
     modification by Marc (MadeMyDay).

------------------------------------------------------------- */


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
   $searchStyle = 'partial';

   // $useAllWords [ true | false ]
   // If you want only documents which contain ALL words in the
   // search string, set to true. Otherwise, the search will return
   // all pages with ONE or more of the search words (which can be
   // a LOT more pages).
   $useAllWords = false;

   // $showSearchWithResults [1 | 0]
   // If you would like to turn off the search
   // form when showing results you can set
   // this to false. Can also be set in template
   // by using the snippet variable: AS_showForm
   // like this (1=true, 0=false):
   // [[AjaxSearch? AS_showForm=0]]
   $showSearchWithResults = 1;

   // $resultsPage [int]
   // The default behavior is to show the results on
   // the current page, but you may define the results
   // page any way you like. The priority is:
   //
   // 1- snippet variable - set in page template like this:
   //    [[AjaxSearch? AS_landing=int]]
   //    where int is the page id number of the page you want
   //    your results on
   // 2- querystring variable AS_form
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
   // variable AS_showResults as:
   // [[AjaxSearch? AS_showResults=0]]
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
   $grabMax = (isset($grabMax))? $grabMax : 10;

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

   // $extract [1 | 0]
   // show the search terms highlighted in a little extract (like Google)
   $extract = (isset($extract))? $extract : 1;

   // $ajaxSearch [1 | 0] (as passed in snippet variable ONLY)
   // Use this to display the search results using ajax
   // You must include the prototype and scriptaculous libraries in your template
   $ajaxSearch = (isset($ajaxSearch))? $ajaxSearch : 1;

   // $ajaxSearchType [1 | 0] (as passed in snippet variable ONLY)
   // Use this to display the search results using ajax
   $ajaxSearchType = (isset($ajaxSearchType))? $ajaxSearchType : 0;

   // $ajaxMax [int] - The maximum number of results to show for the ajaxsearch
   $ajaxMax = (isset($ajaxMax))? $ajaxMax : 6;

   // $showMoreResults [1 | 0]
   // Set this to 1 if you would like a link to show all of the search results
   $showMoreResults = (isset($showMoreResults))? $showMoreResults : 0;
   
   // $moreResultsPage [int]
   // The document id of the page you want the more results link to point to
   $moreResultsPage = (isset($moreResultsPage ))? $moreResultsPage : 0;
   
   // The text for the more results link
   $moreResultsText = 'Click here to view all results.';

   // $addJscript [1 | 0]
   // Set this to 1 if you would like to include the javascript libraries in the
   // header of your pages automatically.
   $addJscript = (isset($addJscript ))? $addJscript : 1;
   
   //Location of the ajaxsearch, prototype, and scriptaculous libraries
   //These will be set in the page head if they are not included in the template
   $jsInclude = 'assets/snippets/AjaxSearch/AjaxSearch.js';
   $jsPrototype = 'manager/media/script/scriptaculous/prototype.js';
   $jsScriptaculous = 'manager/media/script/scriptaculous/scriptaculous.js';

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

   // establish whether to show the form or not
   $showSearchWithResults = (isset($AS_showForm))? $AS_showForm : $showSearchWithResults;

   // establish whether to show the results or not
   $showResults = (isset($AS_showResults))? $AS_showResults : true;

/* -------------
  End configure
 -------------- */

// Set Snippet Paths
$snipPath = $modx->config['base_path'] . "assets/snippets/";

include_once $snipPath."AjaxSearch/AjaxSearch.inc.php";

$liveSearch = $ajaxSearchType;

// establish results page
if (isset($AS_landing)) { // set in snippet
  $searchAction = "[~".$AS_landing."~]";
} elseif ($resultsPage > 0) { // locally set
  $searchAction = "[~".$resultsPage."~]";
}  else { //otherwise
  $searchAction = "[~".$modx->documentIdentifier."~]";
}

// Set newline variable
$newline = "\n";

// Initialize search string
$searchString = '';

// CLEAN SEARCH STRING
if ( isset($_POST['search']) || isset($_GET['AS_search']) || isset($_GET['FSF_search'])) {
  // Prefer post to get
  if (isset($_POST['search'])) {
    $searchString = $_POST['search'];
  } elseif (isset($_GET['AS_search'])) {
    $searchString = urldecode($_GET['AS_search']);
  } else {
    // Code to make tag cloud snippet work with this search
    $searchString = urldecode($_GET['FSF_search']);
  }
  
  //$searchString = (isset($_POST['search']))? $_POST['search'] : urldecode($_GET['AS_search']) ;
  //**********************************************************************************************************************
  $searchString = initSearchString($searchString,$stripHTML,$stripSnip,$stripSnippets,$useAllWords,$searchStyle,$minChars,$ajaxSearch);
  //**********************************************************************************************************************
} // End cleansing search string

// check querystring
$validSearch = ($searchString != '')? true : false ;

//check for offset
$offset = (isset($_GET['AS_offset']))? $_GET['AS_offset'] : 0;

// initialize output
$SearchForm = '';
$useAllWords = ($useAllWords) ? 1 : 0;

if ($docgrp = $modx->getUserDocGroups()) {
  $docgrp = implode(",", $docgrp);
}

if ($ajaxSearch) {
  $searchFormId = 'id="ajaxSearch_form"'.$newline;

  //Adding the javascript libraries to the header
  if ($addJscript) {
    $modx->regClientStartupScript($jsPrototype);
    $modx->regClientStartupScript($jsScriptaculous);
  }

  $modx->regClientStartupScript($jsInclude);
  
  $jsVars = <<<EOD
    <script type="text/javascript">
      <!--
      stripHtml = $stripHTML;
      stripSnip = $stripSnip;
      stripSnippets = $stripSnippets;
      useAllWords = $useAllWords;
      searchStyle = '$searchStyle';
      minChars = $minChars;
      maxResults = $ajaxMax;
      showMoreResults = $showMoreResults;
      moreResultsPage = $moreResultsPage;
      moreResultsText = '$moreResultsText';
      resultsIntroFailure = '$resultsIntroFailure';
      extract = $extract;
      liveSearch = $liveSearch;
      docgrp = '$docgrp';
      //-->
    </script>
EOD;

  $modx->regClientStartupScript($jsVars);

} else {
  $searchFormId = '';
}

// establish form
if (($validSearch && ($showSearchWithResults)) || $showSearchWithResults){
  $SearchForm .= '<form '.$searchFormId.' action="'.$searchAction.'" method="post">'.$newline;
  // wrap it in a fieldset for XHTML strict compliance?
  $SearchForm .= ($xhtmlStrict)? '<fieldset><legend>Ajax Search Form</legend>' : '' ;

  // decide what goes in search box
  $searchBoxVal = ($searchString == '' && $boxText != '')? $boxText : $searchString ;
  $SearchForm .= '<label for="ajaxSearch_input"><input id="ajaxSearch_input" type="text" name="search" value="'.$searchBoxVal.'" ';
  $SearchForm .= ($boxText)? 'onfocus="this.value=(this.value==\''.$boxText.'\')? \'\' : this.value ;" />' : '/>';
  $SearchForm .= '</label>'.$newline;
  
  // the search button
  $SearchForm .= '<label for="ajaxSearch_submit"><input id="ajaxSearch_submit" type="submit" name="sub" value="'.$searchButtonText.'" />';
  $SearchForm .= ($xhtmlStrict)? '</fieldset>' : '';
  $SearchForm .= '</label></form>'.$newline;
}

if ($showResults) {
  if($validSearch) {
    //**********************************************************************************************************************
    $rs = doSearch($searchString,$searchStyle,$useAllWords,$ajaxSearch,$docgrp);
    //**********************************************************************************************************************
    $limit = $modx->recordCount($rs);
    $search = explode(" ", $searchString);

    if($limit>0) {
      $SearchForm .= '<div id="ajaxSearch_resultListContainer">'.$newline;

      // pagination
      if ($grabMax > 0){
        $numResultPages = ceil($limit/$grabMax);
        $resultPageLinks = '<span class="ajaxSearch_paging">';
        $resultPageLinks .= ($limit>$grabMax)? $paginationTextMultiplePages : $paginationTextSinglePage ;
        $resultPageLinkNumber = 1;
        for ($nrp=0;$nrp<$limit && $limit > $grabMax;$nrp+=$grabMax){
          if($offset == ($resultPageLinkNumber-1)*$grabMax){
            $resultPageLinks .= $resultPageLinkNumber;
          } else {
            $resultPageLinks .= '<a href="[~' . $modx->documentObject['id'] . '~]&AS_offset=' . $nrp . '&AS_search=' . urlencode($searchString) . '">' . $resultPageLinkNumber . '</a>';
          }
          $resultPageLinks .= ($nrp+$grabMax < $limit)? $pageLinkSeparator : '' ;
          $resultPageLinkNumber++;
        }
        $resultPageLinks .= "</span>".$newline;
        $SearchForm .= '<p class="ajaxSearch_pagination">';
        $resultsFoundText = ($limit > 1)? $resultsFoundTextMultiple : $resultsFoundTextSingle ;
        if ($extract) {
            $hits=1;
            $searchwords='';
            foreach ($search as $words) {
                $searchwords.='<span class="ajaxSearch_highlight ajaxSearch_highlight'.$hits.'">'.$words.'</span>&nbsp;';
                $hits++;
            }
            $SearchForm .= sprintf($resultsFoundText,$limit,$searchwords);
        } else {
            $SearchForm .= sprintf($resultsFoundText,$limit,$searchString);
        }
        $SearchForm .= '<br />'.$resultPageLinks."</p>".$newline;
      } // end if grabMax

      // search results
      $useLimit = ($grabMax > 0)? $offset+$grabMax : $limit;
      for ($y = $offset; ($y < $useLimit) && ($y<$limit); $y++) {
        $moveToRow = mysql_data_seek($rs,$y);
        $SearchFormsrc=$modx->fetchRow($rs);
        if ($extract) {
            $highlightClass = 'ajaxSearch_highlight';
            $text=$SearchFormsrc['content'];
            if (count($search)>1){
                $count=1;
                $summary='';
                foreach ($search as $searchTerm){
                    $summary .= PrepareSearchContent( $text, $length=200, $searchTerm );
                    $summary = preg_replace( '/' . preg_quote( $searchTerm, '/' ) . '/i', '<span class="ajaxSearch_highlight ajaxSearch_highlight'.$count.'">\0</span>', $summary );
                    $highlightClass .= ' ajaxSearch_highlight'.$count;
                    $count++;
                }
                $text=$summary;
            } else {
                $search=$searchString;
                $text=PrepareSearchContent( $text, $length=200, $search );
                $text = preg_replace( '/' . preg_quote( $searchString, '/' ) . '/i', '<span class="ajaxSearch_highlight ajaxSearch_highlight1">\0</span>', $text );
                $highlightClass .= ' ajaxSearch_highlight1';
            }
        }
        $SearchForm.='<div class="ajaxSearch_result">'.$newline;
        
        if ($extract) {
            $SearchForm.='<a class="ajaxSearch_resultLink" href="[~'.$SearchFormsrc['id'].'~]&searched='.urlencode($searchString).'&highlight='.urlencode($highlightClass).'" title="' . $SearchFormsrc['pagetitle'] . '">' . $SearchFormsrc['pagetitle'] . "</a>".$newline;
        } else {
            $SearchForm.='<a class="ajaxSearch_resultLink" href="[~'.$SearchFormsrc['id'].'~]" title="' . $SearchFormsrc['pagetitle'] . '">' . $SearchFormsrc['pagetitle'] . "</a>".$newline;
        }
        
        $SearchForm.=$SearchFormsrc['description']!='' ? '<span class="ajaxSearch_resultDescription">' . $SearchFormsrc['description'] . "</span>".$newline : "" ;
        if ($extract) {
            $SearchForm.='<div class="ajaxSearch_extract">'. $text . '</div>'.$newline;
        }
        $SearchForm.='</div>'.$newline.'<!--end FlexSearchResult-->'.$newline;
      }
      $SearchForm.='<p class="ajaxSearch_pagination">'.$resultPageLinks.'</p>';
      $SearchForm.='</div>'.$newline.'<!--end FlexSearchResults-->'.$newline;
    } else {
      $SearchForm.='<p class="ajaxSearch_resultsIntroFailure">'.$resultsIntroFailure.'</p>';
    } // end if records found

  } else if (!$validSearch && isset($_POST['sub'])) {

    // message to show if search was performed but for something invalid
    $SearchForm .= '<p class="ajaxSearch_resultsIntroFailure">'.$resultsIntroFailure.'</p>';

  } else { // end if validSearch

    $introMessage = '<p class="ajaxSearch_intro" id="ajaxSearch_intro">'.$introMessage.'</p>';
    $SearchForm .= $introMessage;

  } // end if not validSearch
} // end if showResults

if ($ajaxSearch) {
    $introMessage = '<div id="ajaxSearch_output" style="display:none;"> </div>';
    $SearchForm .= $introMessage;
}

return $SearchForm;
