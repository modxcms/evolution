
AjaxSearch Readme version 1.7.1

---------------------------------------------------------------
:: Snippet: ajaxSearch
----------------------------------------------------------------
  Short Description: 
        Ajax-driven & Flexible Search form

  Version:
        1.7.1

  Created by:
	    Jason Coward (opengeek - jason@opengeek.com)
	    Kyle Jaebker (kylej - kjaebker@muddydogpaws.com)
	    Ryan Thrash  (rthrash - ryan@vertexworks.com)

  Parts refactored and new features/fixes added by Coroico (coroico@wangba.fr)
  
----------------------------------------------------------------
:: Credits
----------------------------------------------------------------
   
   Based on Flex Search Form (FSF) by jardc@honeydewdsign.com 
   as modified by KyleJ (kjaebker@muddydogpaws.com)
   and Coroico (coroico@wangba.fr)
   	     
The document subset selection is based off of the ditto code by Mark Kaplan

The javascript code is based off of the example by Steve Smith of orderlist.com
http://orderedlist.com/articles/howto-animated-live-search/

The search highlighting is based off of the code by Marc (MadeMyDay | http://www.modxcms.de)
The search highlighting plugin is based off of code from sottwell (www.sottwell.com)
The live Search functionality is from Thomas (shadock)
http://www.gizax.it/experiments/AHAH/degradabile/test/liveSearch.html

Many fixes/additions were contributed by mikkelwe/identity/Perrine
     
  Copyright & Licencing:
  ----------------------
  GNU General Public License (GPL) (http://www.gnu.org/copyleft/gpl.html)

  Originally based on the FlexSearchForm snippet created by jaredc (jaredc@honeydewdesign.com)
  
Note: For this code to work you must include a call to the Mootools js library in your template.

This is done automatically with the addJscript parameter unless you set it to 0.

---------------------------------------------------------------
:: Changelog:
----------------------------------------------------------------

  06-Mar-08 (1.7.1)
    -- Advanced search (partial & relevance)
    -- Search in hidden documents from menu
    -- List of Ids limited to parent-documents ids in javascript
    -- Code cleaning
  06-Jan-08 (1.7)
    -- Added custom config file
    -- Added list of parent-documents where to search
    -- Added opacity parameter (between 0 (transparent) and 1 (opaque)
    -- Added bugfixes regarding opacity with IE
    -- Using of DBAPI function instead of deprecated function
    -- Charset troubles corrected
	22-Jan-07 (1.6)
		-- Added templating support (includes/templates.inc.php)
		-- Added language support
		-- Switched from prototype/scriptaculous to Mootools
	03-Jan-07 -- Added many bugfixes/additions from AjaxSearch forum
	18-Sep-06 -- Added code to only show results for allowed pages
	05-May-06 -- Added liveSearch functionality and new parameter
	21-Apr-06 -- Added code to make it compatible with tagcloud snippet
	20-Apr-06 -- Added code from eastbind & japanese community for other language searching
	04-Apr-06 -- Added search term highlighting
	01-Apr-06 -- initial commit into SVN
	30-Mar-06 -- initial work based on FSF_ajax from KyleJ

----------------------------------------------------------------
:: Description
----------------------------------------------------------------

    The AjaxSearch snippet is an enhanced version of the original FlexSearchForm
    snippet for MODx. This snippet adds AJAX functionality on top of the robust 
    content searching.
    
    - search in title, description, content and TVs of documents
    - search in a subset of documents
    - highlighting of searchword in the results returned
   
    It could works in two modes:
    
    ajaxSearch mode : 
    - Search results displayed in current page through AJAX request
    - Multiple search options including live search and non-AJAX option
    - Available link to view all results in a new page (FSF) when only a subset is retuned
    - Customize the number of results returned
    - Uses the MooTools js library for AJAX and visual effects

    non-ajaxSearch mode (FSF) :
    - Search results displayed in a new page
    - customize the paginating of results
    - works without JS enabled as FlexSearchForm
    - designed to load only the required FSF code
    
----------------------------------------------------------------
:: General Parameters
----------------------------------------------------------------

    &config [config_name | "default"] (optional)
        Load a custom configuration
        config_name - Other config installed in the configs folder or in any folder within the MODx base path via @FILE
        Configuration files are named in the form: <config_name>.config.php
        
    &language [ language_name | manager_language ] (optional)
        with manager_language = $modx->config['manager_language'] by default 

    &ajaxSearch [1 | 0] (optional)
        Use the ajaxSearch mode. Default is 1 (true)
        The AjaxSearch mode use an Ajax call to get the results without full page reloading

    &AS_showForm [1 | 0] (optional)
        Show the search form with the results. Default is 1 (true)

    &AS_showResults [1 | 0] (optional)
        Show the results with the snippet. (For non-ajax search)

    &advSearch [ 'exactphrase' | 'allwords' | 'nowords' | 'oneword' ]
        Advanced search    
        - exactphrase : provides the documents which contain the exact phrase 
        - allwords : provides the documents which contain all the words
        - nowords : provides the documents which do not contain the words
        - oneword : provides the document which contain at least one word [default] 

    &hideMenu [ 0 | 1 | 2 ]    
        Search in hidden documents from menu
        - 0 : search only in documents visible from menu
        - 1 : search only in documents hidden from menu
        - 2 : search in hidden or visible documents from menu [default]

    &extract [1 | 0] (optional)
        Show the search words highlighting.
        
    &extractLength [int] (optional)
        Length of extract around the search words found - between 50 and 800 characters
        
    &parents [comma-separated list of integers  MODx document IDs] (optional)
        A list of parent-documents whose descendants you want searched to &depth depth when searching. 
        All parent-documents by default

    &depth [int] (optional)
        Number of levels deep to go.
        Any number greater than or equal to 1. 10 levels by default

    &documents [comma-separated list of integers  MODx document IDs] (optional)
        A list of documents where to search

----------------------------------------------------------------
:: Ajax Parameters - Used only with the ajaxSearch mode
----------------------------------------------------------------

    &ajaxMax [int] (optional)
        The number of results you would like returned from the ajax search.
        
    &showMoreResults [1 | 0] (optional)
        If you want a link to show all of the results from the ajax search.
        
    &moreResultsPage [int] (optional)
        Page you want the more results link to point to. This page should contain another call to this snippet for displaying results.
        
    &ajaxSearchType [1 | 0] (optional)
        There are two forms of the ajaxSearch.
        0 - The form button is displayed and searching does not start until the button is pressed by the user.
        1 - There is no form button, the search is started automatically as the user types (liveSearch)

    &opacity [float value between 0. and 1.] (optional)
        Opacity of the ajaxSearch_output div where are returned the ajax results. Défault is 1.
        Float value between 0. (transparent) and 1. (opaque)        
        
    &addJscript [1 | 0] (optional)
        If you want the mootools library added to the header of your pages automatically set this to 1.  
        Set to 0 if you do not want them inculded automatically. Défault is 1.

----------------------------------------------------------------
:: Non Ajax Parameters - Used only with the non-ajaxSearch mode
----------------------------------------------------------------

    &AS_landing [int] (optional)
        Document id you would like the search to show on. (For non-ajax search)
        
    &grabMax [int] (optional)
        The number of results per page returned (For non-ajax search)

----------------------------------------------------------------
:: CSS                         
----------------------------------------------------------------
    The following items are used to style the starting form and
    ajax result container.

    #ajaxSearch_form - id of the search form
    #ajaxSearch_input - id of the input box on the form
    #ajaxSearch_submit - id of the submit button
    #ajaxSearch_output - id of the div that the ajax results are returned in
    
    The following items are used to style the reults when the user does not have 
    javascript or they have clicked the more results link
    
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

    The following items are used to style the results returned by the ajax request.

    .AS_ajax_result - class for the result container div
    .AS_ajax_resultLink - class for the result link
    .AS_ajax_resultDescription - class for the result description span
    .AS_ajax_extract - class for the content extract div (for highlighting)
    .AS_ajax_hightlight1,2,3 - classes for result highlighting.  You need to create 
        as many classes as terms you think a user will search for.
    .AS_ajax_more - class for more search results div
    .AS_ajax_resultsIntroFailure - class for no results paragraph


----------------------------------------------------------------
:: Ajax Mode Example Calls              
----------------------------------------------------------------
[!AjaxSearch!]
    A basic (Ajax) default call that renders a search form with the default images and parameters

[!AjaxSearch? &showMoreResults=`1` &moreResultsPage=`25`!]
    Allows a link to a full-page search to go to another page.
    in this example, the document #25 should contain a non-ajaxSearch snippet call like :  
    [!AjaxSearch? &ajaxSearch=`0` &AS_showForm=`0`!] to display the results without the 
    search form again
    
[!AjaxSearch? &ajaxMax=`10` &extract=`0`!]
    Overrides the number of maximum results returned and removes search term highlighting.
    
[!AjaxSearch? &documents=`2,3,8,16`!]
    A call that renders a search form with the default images and parameters
    search terms are searched among the documents `2,3,8,16`

[!AjaxSearch? &parents=`5,7` &depth=`2`!]
    A call that renders a search form with the default images and parameters
    search terms are searched on 2 levels among the document childs of documents 5, 7

----------------------------------------------------------------
:: Non-Ajax Mode Example Calls              
----------------------------------------------------------------   
[!AjaxSearch? &ajaxSearch=`0`!]
    A basic non-Ajax default call that renders a search form with the default images
    and non-Ajax parameters

[!AjaxSearch? &ajaxSearch=`0` &AS_landing= `25`!]
    In this example, search results will be displayed on document #25 
    This document should contain a non-ajaxSearch snippet call like :  
    [!AjaxSearch? &ajaxSearch=`0` &AS_showForm=`0` &grabMax=`10` &extract=`0`!] 
    to display the results without the search form again
    And overrides the number of maximum results returned per page and removes search term highlighting.    
        
-----------------------------------------------------------------
:: How-to use this snippet
-----------------------------------------------------------------

1. Copy the contents of the file snippet.ajaxSearch.php into a new snippet named AjaxSearch

2. Create a directory named ajaxSearch under the assets/snippets folder.

3. Open the js/ajaxSearch.js file and set the loading & close image path to an image 
   you want to display while the search is working.

4. Copy the files from the zip into the ajaxSearch folder.

5. Add the snippet call like the following:  [!AjaxSearch!]

    Note: If javascript is disabled the snippet functions like the original FlexSearchForm.
        So you will want to set any of the other options in the snippet call for these users.
        Test by calling via [!AjaxSearch? &ajaxSearch=0 &otherParamsAsNeeded=`here` !]

6. Use the following styles to change how your search looks:

        #ajaxSearch_form {
            color: #444;
            width: auto;
        }
        #ajaxSearch_input {
            width: auto;
            display: inline;
            height: 17px;
            border: 1px solid #ddd;
            border-left-color: #c3c3c3;
            border-top-color: #7c7c7c;
            background: #fff url(images/input-bg.gif) repeat-x top left;
            margin: 0 3px 0 0;
            padding: 3px 0 0;
            vertical-align: top;
        }
        #ajaxSearch_submit {
            display: inline;
            height: 22px;
            line-height: 22px;
        }
        #ajaxSearch_output {
            border: 1px solid #444;
            padding: 10px;
            background: #fff;
            display: block;
            height: auto;
            vertical-align: top;
        }
        .ajaxSearch_paging {
    
        }
        .AS_ajax_result {
            color: #444;
            margin-bottom: 3px;
        }
        .AS_ajax_resultLink {
            text-decoration: underline;
        }
        .AS_ajax_resultDescription{
            color: #555;
        }
        .AS_ajax_more {
            color: #555;
        }

7. If you are using the display more results link setup a new page with the snippet 
   call to display your results.

8. Test and see the search working with Ajax!


----------------------------------------------------------------
:: How-to change the ajaxSearch folder location
----------------------------------------------------------------

To change the location of the ajaxSearch snippet folder :

1. change the definition of AS_PATH in snippet.ajaxSearch.php

// Path where ajaxSearch is installed
define('AS_SPATH', 'assets/snippets/ajaxSearch/');

2. change the definition of AS_PATH in ajaxSearch.php

define('AS_PATH' , MODX_BASE_PATH . 'assets/snippets/ajaxSearch/');

3. in the js/ajaxSearch.js file change the _base value:

var _base = 'assets/snippets/ajaxSearch/';


----------------------------------------------------------------
:: How-to use the search highlight plugin
----------------------------------------------------------------

1. Create a new plugin named Search_Highlight.

2. Copy the contents of the file plugin.searchHighlight.tpl into the plugin.

3. On the System Events tab select OnWebPagePrerender.

4. Somewhere in your template or document add the html:  <!--search_terms-->
       This will display the terms and a link to remove the highlighting

5. Do a search and click the link to see the search highlighting carried through to the page.

----------------------------------------------------------------
:: Where find more support
----------------------------------------------------------------
1. Look at :

    Modx Community forum >> support >> Repository Items Support >> support/comments for ajaxSearch
    
    http://modxcms.com/AjaxSearch-490.html
    
2. Documentation : http://wiki.modxcms.com/index.php/AjaxSearch

3. Demo site : http://www.modx.wangba.fr
