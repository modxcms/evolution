
AjaxSearch Readme version 1.8.3

---------------------------------------------------------------
:: Snippet: AjaxSearch
----------------------------------------------------------------
  Short Description: 
        Ajax-driven & Flexible Search form

  Version:
        1.8.3  - 08/06/2009

  Created by:
      Coroico - (coroico@wangba.fr)
	    Jason Coward (opengeek - jason@opengeek.com)
	    Kyle Jaebker (kylej - kjaebker@muddydogpaws.com)
	    Ryan Thrash  (rthrash - ryan@vertexworks.com)

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

----------------------------------------------------------------
:: Changelog:
----------------------------------------------------------------

  08-june-09 (1.8.3)
    -- Bug fixing
    -- The number of results is available with the [+as.resultNumber+] placeholder

  01-mar-09 (1.8.2)
    -- liveSearch parameter renamed
    -- Initialisation of configuration parameters is modified
    -- mbstring parameter added
    -- Limit the amount of keywords that will be queried by a search
    -- Capturing failed search criteria and search logs
    -- Compatibility with mootools 1.2.1 library
    -- Compatibility with jquery library
    -- Always display paging parameter added
    -- Bug fixing
    
  02-oct-08 (1.8.1)
    -- subSearch added. 
    -- mysql query redesigned.
    -- whereSearch parameter improved. Fields definition added
    -- withTvs parameter added. specify the search in Tvs
    -- # metacharacter for filter
    -- improvement of the searchword list parameter
    -- debug - file and firebug console
    -- Bug fixing
    
  21 -July-08 (1.8.0)
    -- define where to do the search (&whereSearch parameter)
    -- define which fields to use for the extracts (&extract parameter)
    -- use AjaxSearch with non MOdx tables
    -- order the results with the &order parameter
    -- define the ranking value and sort the results with it
    -- filter the unwanted documents of the search
    -- define the extract eliipsis
    -- define the extract separator
    -- Extended place holder templating and template parameters
    -- Improvement of the extract algorithm
    -- Define the number of extracts displayed in the search results
    -- Use of &advSearch parameter available from the front-end by the end user
    -- Choose your search term from a predefined search word list
    -- stripInput user function
    -- stripOutput user function
    -- Configuration file and $__ global parameters
    -- snippet code completely refactored and objectified
    -- Bugfixes regarding Quoted searchstring

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
    - Uses the MooTools or jQuery js library for AJAX and visual effects

    non-ajaxSearch mode (FSF) :
    - Search results displayed in a new page
    - customize the paginating of results
    - works without JS enabled as FlexSearchForm
    - designed to load only the required FSF code
    
----------------------------------------------------------------
:: General Parameters (all are optional parameters)
----------------------------------------------------------------

Keep in mind that all parameters are optional.
If not used, the default value of each parameter will be applied
The simplest snippet call is [!Ajaxsearch!] without any parameters.

---- &config [config_name | "default"] (optional)

        Load a custom configuration
        config_name - Other config installed in the configs folder or in any folder within the MODx base path via @FILE
        Configuration files are named in the form: <config_name>.config.php

        To limit the number of javascript variables, the default parameters are stored in the default.config.inc.php file. 
        This file is read by the ajaxSearch class and by the ajaxSearchPopup class.
        The only parameters transmitted by JavaScript to the ajaxSearchPopup class are:
        - the subSearch and the advSearch parameters
        - the parameters used in the snippet call
        Keep care that all the default values defined in the default.config.php should be defined in your own config file
        
        Parameters in the config file should be defined as php variables. e.g:
        $ajaxMax = 4;
        
        To avoid to overwrite the parameters used in the snippet call use $__ instead of $ e.g:
        $__ajaxMax = 4;
        
        For instance, in a AjaxSearch call if we have [!AjaxSearch? &AS_landing=`25`] and
        in the config file $__AS_landing = `12`; that means that by default the page #12
        will be used as a landing page, except in the document where &AS_landing=`25` 
        is set in the snippet call.



---- &debug = [ 0 | 1 | 2 | 3 | -1 | -2 | -3 ] (optional) - Output debugging information

      0 : debug not activated (Default)
        
      1, 2, 3 : File mode
      debug activated. Trace is by default logged into a file named ajaxSearch_log.txt
      in the ajaxSearch folder.
      
        1 : Parameters, search context and sql query logged.
        2 : Parameters, search context, sql query AND templates logged
        3 : Parameters, search context, sql query, templates AND Results logged
      
      To avoid an increasing of the file, only one transaction is logged. Overwritted 
      by the log of the following one.
      
      -1, -2, -3 : FireBug mode
      debug activated. The trace is logged into the firebug console of Mozilla.
      
        -1 : Parameters, search context and sql query logged.
        -2 : Parameters, search context, sql query AND templates logged
        -3 : Parameters, search context, sql query, templates AND Results logged    
        
        with the FireBug mode you need to install:
        the Firebug plugin under Firefox : https://addons.mozilla.org/en-US/firefox/addon/1843
        and the FirePhp plugin (version 0.2.b.1 or upper) : http://www.firephp.org/
        
      For the FireBug mode, Php5 is mandatory. These level -1,-2,-3 are switched 
      respectively to 1,2,3 if your server runs whith Php4.
      
      For security reasons, the full name of tables (with database name) have 
      been replaced by short names (prefix + table name only)
      
      The output produce the SELECT statement and you could use it directly by 
      copy & paste in PhpMyAdmin to retrieve your results and undertsand it.

         
---- &language [ language_name | manager_language ]    (optional)
        with manager_language = $modx->config['manager_language'] by default
        See in the lang folder the languages available


---- &ajaxSearch [1 | 0]   (optional)
        Use the ajaxSearch mode. Default is 1 (true)
        The AjaxSearch mode use an Ajax call to get the results without full page reloading


---- &advSearch [ 'exactphrase' | 'allwords' | 'nowords' | 'oneword' ]  (optional)
        Advanced search    
        - exactphrase : provides the documents which contain the exact phrase 
        - allwords : provides the documents which contain all the words
        - nowords : provides the documents which do not contain the words
        - oneword : provides the document which contain at least one word [default] 


---- &subSearch : [int, int | 5, 1]      (optional)
        subSearch allow to use radio buttons to select sub-domains where to search
        Initialize the subSearch by defining the number of possible choices (radio-buttons)
        and choose the default checked selection
        By default 5 choices and the first one selected


---- &whereSearch : [comma separated list of key | content,tv] (optional)
        define in which tables the search occurs
        by default in documents and TVs
        other predefined key: jot, maxigallery
        by default all the text fields are searchable but you could specify the fields like this:
        whereSearch=`content:pagetitle,introtext,content|tv:tv_value|maxigallery:gal_title`
        
        You could also add your own tables where to do a search by defining your own keys.


---- &withTvs    (optional)     
        Define which Tvs are used for the search in Tvs
        a comma separated list of TV names
        by default all TVs are used (empty list)


---- &order    (optional)
        Define in which order are sorted the displayed search results
        `comma separated list of fields`
        by default: 'publishedon,pagetitle' (sorted by published date and then pagetitle)

        The fields should come from the tables used and defined from the whereSearch parameter
        You could add DESC to sort by decreasing order. By default increasing order (ASC) is used.
    
        e.g : &order=`longtitle DESC,introtext`


---- &rank     (optional)
        define the ranking of search results
        &rank=`comma separarted list of fields with optionaly user defined weight`
        by default: pagetitle:100,extract

        The rank is a calculated value used to sort the results. This value is function of
        number of search term found and optionaly of a specified user weight.
        
        e.g: &rank=`pagetitle:100,extract` 
        rank['pagetitle'] = nb_search_terms_found * 100
        rank['extract'] = nb_search_terms_found * 1
        
        Results are sorted with the rank values.
        For instance, a document with a search term found in the pagetitle, and 6 terms found
        in the extract will have a rank of 106.
            
        e.g: &rank=`pagetitle,extract` 
        rank['pagetitle'] = nb_search_terms_found * 1
        rank['extract'] = nb_search_terms_found * 1


---- &maxWords [ 1 < int < 10 ] (optional)
        Maximum number of words for searching - Default: 5


---- &minChars [ 2 < int < 100 ] (optional)
        Minimum number of characters to require for a word to be valid for searching.
        Length of each word with $advSearch = 'allwords', 'oneword' or 'nowords'
        Length of the search string with possible spaces with $advSearch = 'exactphrase'


---- &AS_showForm [1 | 0] (optional)
        Show the search form with the results. Default is 1 (true)


---- &AS_showResults [1 | 0] (optional)
        Show the results with the snippet. (For non-ajax search)



---- &extract [int : Comma separated list of displayable fields | '1:content,description,introtext,tv_value] (optional)
    
        Define the maximum number of extracts that will be displayed per 
        document and define which fields will be used to set up extracts
  
        An extract is a bit of text where search term have been found (and may be highlighted).
        &extract is different of &whereSearch. 
        &extract define which fields will be used to set up the text where to extract the search term. 
        &whereSearch define which tables use for the search (and then provides a list of displayable 
        fields where to search and possibly used by &extract)
        
        &extract : `int : Comma separated list of searchable fields`
        
        int:
            0 - no extract
            n - n extracts allowed per document.
        
        Comma separated list of displayable fields:
        The field names used for the extract come from the "displayed" fields of
        tables defined by the &whereSearch parameter (main and joined tables)
        
        Searchable fields are string type fields that can could contain the search terms.
        
        For "content", the searcheable fields available are: 
          pagetitle, longtitle, description, alias, introtext, menutitle, content
        
        For "tv", the fields available are : 
          tv_value which is a concatenation of all the tv "values" field
          
        For "jot", the fields available are : 
          jot_content which a concatenation of all the "content" fields of jot table
          
        For "maxigallery", the fields available are : 
          gal_title, gal_descr which are a concatenation of title & descr fields of maxigallery table
        
        e.g:  &extract=`5:description,introtext,content,tv_value`
        allows a maximum of 5 extracts per document found by search.
        Extracts display content from description,introtext,content of document AND 
        tv_value from all the TV linked with the document
        
        e.g: &whereSearch=`content,maxigallery`  &extract=`10:galtitle,galdescr`
        allows a maximum of 10 extracts per document found by search. 
        Extract are built by parsing ONLY the fields title and descr of maxigallery
        
        e.g: &whereSearch=`content,tv,jot`  &extract=`tv_value,jot_content`
        allows a maximum of 1 extract per document found by search (by default) 
        Extracts display content from tv_value (TV values) and jot_content (comment)
        
        e.g &whereSearch=`product,shop`  &extract=`name,shop_name,shop_address`
        In this example, product and shop are customs tables (defined in config file)
        Extracts display content from name (product), shop_name and shop address(shop)


---- &extractEllips : define your ellipsis in extract    (optional)
        string used as ellipsis to start/end an extract
        by default : " ... "


---- &extractSeparator : Define how separate extracts      (optional)
        html tag like <br /> or <hr /> or any other html tag
        Default : "<br />"


---- &extractLength [50 < int < 800] (optional)
        Length of extract around the search words found - between 50 and 800 characters


---- &formatDate [ string ]    (optional)
        The format of outputted dates. See http://www.php.net/manual/en/function.date.php
        by default : "d/m/y : H:i:s" e.g: 21/01/08 : 23:09:22


---- &hideMenu [ 0 | 1 | 2 ]    (optional)
        Search in hidden documents from menu
        - 0 : search only in documents visible from menu
        - 1 : search only in documents hidden from menu
        - 2 : search in hidden or visible documents from menu [default]


---- &hideLink [0 | 1] : Search in content of type reference   (optional)
    
        - 0 : search in content of document AND reference type 
        - 1 : search only in content of document type (default)


---- &parents [comma-separated list of integers  MODx document IDs] (optional)
        A list of parent-documents whose descendants you want searched to &depth depth when searching. 
        All parent-documents by default


---- &depth [int] (optional)
        Number of levels deep to go.
        Any number greater than or equal to 1. 10 levels by default


---- &documents [comma-separated list of integers  MODx document IDs] (optional)
        A list of documents where to search


---- &filter : exclude unwanted documents      (optional)
        &filter runs as the &filter Ditto 2.1 parameter. 
        (see http://ditto.modxcms.com/tutorials/basic_filtering.html)

        &filter=`field,criterion,mode`
        
        Where:
    
        "field" is the name of any field from main table or joined table
        "criterion" is a value of the same data type (number, string, date, etc.) as the field.
        "mode" is a number from 1 to 8 that specifies what kind of comparison to make 
            between the value of the field and the specified criterion.
        "," (comma) is the "Local Filter Delimiter", i.e. the character that tells 
            where the division is between the three parts of the clause.
    
        There must be no spaces in the "criteria" term unless you want them used in the comparison.
        
        The filter clause: id,50,2  means "exclude any document whose id field equals 50."
        
        A filter may include multiple clauses, separated by the global delimiter, in the form: clause|clause|clause|etc.
    
        Where:
    
        "clause" is any filter clause as defined above.
        "|" (the pipe symbol) is the Global Filter Delimite, the character that 
             tells Ditto where the division is between clauses.
        "etc." means there is no fixed limit to the number of clauses you may include.
    
        Multiple clauses have an "OR" relationship. I.e. a document will be excluded 
        if it meets the criterion of any one clause (clause-1 OR clause-2 OR clause-3, etc.).
    
        The filter id,50,2|id,52,2 means "exclude any document whose id field equals 50 or 52." 
    
        Comparison Modes	
        Exclude a document if its value in the specified field
        1     is not equal to the criterion (!=)
        2     is equal to the criterion (==)
        3     is less than the criterion (<)
        4     is greater than the criterion (>)
        5     is less than or equal to the criterion (<=)
        6     is greater than or equal to the criterion (>=)
        7     does not contain the text of the criterion
        8     does contain the text of the criterion


---- &stripInput user function     (optional)
        to transform on fly the search input text
        by default: defaultStripInput

        StripInput user function should be define in the config file as follow :
            
        // StripInput user function. 
        // string functionName(string $searchstring)
        // functionName : name of stripInput function passed as &stripInput parameter
        // $searchstring : string php variable name as searchString input value
        // return the filtered searchString value
        /*
        function myStripInput($searchString){
            
            Any Php code which filter the input search string
            The following internal functions could be called:
              $searchString = stripHtml($searchString) : strip all the html tags
              $searchString = stripHtmlExceptImage($searchString) : strip all the html tags execpt image tag.
              $searchString = stripTags($searchString) : strip all the MODx tags
              $searchString = stripSnip($searchString) : strip all the snippet names
              
            You could also developp you own filter based on regular expressions.
            See http://fr.php.net/manual/en/intro.pcre.php
         
          return $searchString;
        }

        By default : defaultStripInput function will be used if &stripInput parameter
        is not set or if the function is not defined :
        
        function defaultStripInput($searchString){
        
          if ($searchString != ''){  
            // Remove escape characters
            $searchString = stripslashes($searchString);
        
            // Remove modx sensitive tags
            $searchString = stripTags($searchString);
          
            // Strip HTML tags
            $searchString = stripHtml($searchString);  
          }  
          return $searchString;
        }


---- &stripOutput user function    (optional)
        to transform on fly the result output
        by default: defaultStripOutut

        StripOutput user function should be define in the config file as follow :
            
        // string functionName(string $text)
        // functionName : name of stripOutput function passed as &stripOutput parameter
        // $text : string php variable name as results
        // return the filtered results
        /*    
        function myStripOutput($text){
            
            Any Php code which filter the results
            The following internal functions could be called:
              $text = stripTags($text); // strip all the MODx tags
              $text = stripJscript($text); // strip jscript 
              $text = stripLineBreaking($text); // replace line breaking tags with whitespace
              $text = stripHtml($text); // strip all the html tags
              
            You could also developp you own filter based on regular expressions.
            See http://fr.php.net/manual/en/intro.pcre.php
            
          return $text;
        }
        */
        
        By default : defaultStripOutput function will be used if &stripOutput parameter
        is not set or if the function is not defined :
        
        function defaultStripOutput($text){
        
          // replace line breaking tags with whitespace
          $text = stripLineBreaking($text);
          // strip modx sensitive tags
          $text = stripTags($text);
          // strip Jscript
          $text = stripJscripts($text);
          // strip html tags. Tags should be correctly ended
          $text = stripHTML($text);  
        
          return $text;
        }


---- &searchWordList user function   (optional)
        to define a search word list: [user_function_name,params] 
        where params is an optional array of parameters

        You could define a list of predefined search terms by using:   
        &searchWordList=`myTermList` 
        
        with the definition of the function myTermList :
        
        // searchWordList user function
        // Uncomment and complete the core function and choose your own function name
        // string functionName()
        // functionName : name of searchWordList function passed as &searchWordList parameter
        // return a comma separated list of words
        
        function mySearchWordList(params){ 
        
          $list = "primary,school,children,africa,education,teacher";
          return $list;
        }
        
        Use the followings template lines to display the list of search terms :
        
            <select name="search[]" id="ajaxSearch_select" size="3" multiple="multiple">
              [+as.selectList+]
            </select>
        
        See the template file layout.tpl.htm in the directory templates/multipleInputList
        
        To combine search word list and use of &advSearch from front end use the template 
        file layout.tpl.htm from the directory templates/optionMultipleList
        
                
---- &clearDefault       (optional)
        clearing default text: [1 | 0]
        Set this to 1 if you would like to include the clear default js function
        add the class "cleardefault" to your input text form and set this parameter

        When the user clicks on the box, the default text is wiped away so that they
        can begin typing. If they click away from the box, without typing anything in,
        we will add the default text back so that they don’t forget what was meant to 
        be typed.
        
        See http://www.yourhtmlsource.com/forms/clearingdefaulttext.html
        
        e.g : &clearingDefault=`0`
    
        To work the input text should contains the class="cleardefault"
        e.g (from templates/layoutTpl):
        <input class="cleardefault" id="ajaxSearch_input" type="text" name="search" value="[+as.inputValue+]"[+as.inputOptions+] />
    
        Could be used with all the input text forms of your site
        The location of the js library is set with &jsClearDefault and the default
        file is : js/clearDefault.js 
        
        
---- &jsClearDefault       (optional)
        Location of the clearDefault javascript library


---- &breadcrumbs        (optional)
        0 : disallow the breadcrumbs link
        Name of the breadcrumbs function : allow the breadcrumbs link
        The function name could be followed by some parameter initialization
        e.g: &breadcrumbs=`Breadcrumbs,showHomeCrumb:0,showCrumbsAtHome:1`

        The breadcrumbs function could be custom function or a snippet
        The function nor the snippet should in any case exist

        Breadcrumbs user function could be define in the config file as follow :    
        
        // Breadcrumbs user function. 
        // string functionName(array $main, array $row, array $params)
        // functionName : name of breadcrumbs function passed with &breadcrumbs parameter
        // $main : array as main table definition
        // $row : array as search result row
        // $params : array as breadcrumbs parameters passed with &breadcrumbs parameter
        // return the breadcrumbs link as a string
        function myBreadcrumbs($main, $row, $params){
            
            // use $main, $row and $params to set up your own custom breadcrumbs
            return $breadcrumbs;
        }


---- &tvPhx      (optional)
        Set placeHolders for TV (template variables)
        0 : disallow the feature (default)
        'tv:displayTV' : set up a placeholder named [+as.tvName.+] for each TV (named tvName) linked to the documents found
        displayTV is a provided ajaxSearch function which render the TV output
        tvPhx could also be used with your own custom tables


---- &addJscript [1 | 0]     (optional)
        Set this to 1 if you would like to include the mootool/jquery librairy
        in the header of your pages automatically.


---- &jScript ['jquery'|'mootools1.2'|'mootools'](optional)
        Set this to jquery if you would like to include the jquery library
        set this to mootools1.2 to include the mootools 1.2.1 library (limited to JS functions used by AS)
        Default: mootools


---- &jsMooTools
        Location of the mootools javascript library
        by default: 'manager/media/script/mootools/mootools.js'


---- &jquery
        Location of the jquery javascript library
        by default: AS_SPATH . 'js/jquery.js'


---- &tplLayout chunk to style the ajaxSearch input form and layout
        @FILE:".AS_SPATH.'templates/layout.tpl.html' by default


---- &mbstring  (optional)
        Set to 0 if you can't load the php_mbstring extension
        by default: 1


---- &asLog - ajaxSearch log [ level [: comment [: purge]]]
        level:
          0 : disallow the ajaxSearch log (default)
          1 : failed search requests are logged
          2 : all ajaxSearch requests are logged
        comment:
          0 : user comment not allowed (default)
          1 : user comment allowed
        purge: number of logs allowed before to do an automatic purge of the table
          0 : no purge allowed (= illimited number of logs)
          default: 200 
    
      &asLog=`x` is equivalent to &asLog=`x:0:200`
      &asLog=`x:y` is equivalent to &asLog=`x:y:200`

      &asLog=`1:1:500` means that 500 failed search requests possibly commented
                       by the end user could be stored in the ajaxSearch_log table
      
      &asLog=`1:1:500` means that 500 failed search requests possibly commented
                       by the end user could be stored in the ajaxSearch_log table


---- &tplComment
        chunk to style comment form
        by default: @FILE:".AS_SPATH.'templates/comment.tpl.html'

    The comment form is protected from spamming by the use of a hidden input field.
    (idea suggested from SPForm by Bob Ray ) This field should be hidden by the 
    CSS styling. If it's filled in (presumably by spammer autobots) nothing is sent. 
    The "hidden" content is not really hidden, just not visible, so no worries 
    about being penalized by Google. Visually challenged users of
    text-only browsers or audio browsers MAY see the input field and fill it
    (although the text warns them not to).

    You need to paste the following code into your CSS file. Otherwise the field will not be hidden.

    .ajaxSearch_hiddenField {
      position:absolute;
      text-decoration:underline;
      background-color:#CC0000;
      left:0px;
      top:-500px;
      width:1px;
      height:1px;
      overflow:hidden;
    }

    Keep spammers from pasting too many links into the comment and sending it(counts "http" instances); 
    A maximum of 2 links per comment is allowed otherwise the comment is rejected.
    You could adjust this value in the file classes/ajaxSearchLog.class.inc by changing the CMT_MAX_LINKS definition.
    
    The maximum length of the comment is of 100 characters. Otherwise the comment is rejected.
    Helps short-circuit injection attacks. You could this value in the file classes/ajaxSearchLog.class.inc by 
    changing the CMT_MAX_LENGTH definition.

    The user searches are stored in the database table $modx_table_prefix."ajaxsearch_log"
    These data are not for the end user only for the site administrator.

    Informations stored per each search are the following:

      - id : internal id of the search request looged
      - searchstring : the search terms used for the search
      - nb_results : number of results found
      - results : document ids found
      - comment : comment leave by the user regarding the search results
      - as_call : ajaxSearch snippet call used
      - as_select : select statement used (could be reused thru phpmyadmin)
      vdate : date and hour of the request
      - ip : user IP

    The table could be drop without any impacts on the AjaxSearch behaviour. Simply,
    if the asLog parameter is set and the table inexisting, the table is rebuilt.

    A 'Did you find what you are looking for?' form is available for the user when the option comment is set. 
    In this case the user could leave a comment about the search results.
    &tplComment parameter define which form template used.

    A module will be provided later to manage these search datas
    We could imagine the following features:
    - drop the table
    - delete (successfull, unsuccessfull, all) searches executed before a specific date
    - delete (successfull, unsuccessfull, all) searches executed before N days
    - give me as meta tag keywords, the N most used (successfull, unsuccessfull) search terms
    - replay a specific search with a new debug level
    - delete uncommented (successfull, unsuccessfull, all) searches
    - filter view with commented (successfull, unsuccessfull, all) searches
    
    
----------------------------------------------------------------
:: Ajax Parameters - Used only with the ajaxSearch mode
----------------------------------------------------------------

---- &ajaxMax [int] (optional)
        The number of results you would like returned from the ajax search.


---- &showMoreResults [1 | 0] (optional)
        If you want a link to show all of the results from the ajax search.


---- &moreResultsPage [int] (optional)
        Page you want the more results link to point to. This page should contain 
        another call to this snippet for displaying results.


---- &liveSearch [1 | 0] (optional)
        There are two forms of the ajaxSearch.
        0 - The form button is displayed and searching does not start until the button is pressed by the user.
        1 - There is no form button, the search is started automatically as the user types (liveSearch)


---- &opacity [float value between 0. and 1.] (optional)
        Opacity of the ajaxSearch_output div where are returned the ajax results. Default is 1.
        Float value between 0. (transparent) and 1. (opaque)        


---- &addJscript [1 | 0] (optional)
        If you want the mootools library added to the header of your pages automatically set this to 1.  
        Set to 0 if you do not want them inculded automatically. Default is 1.


---- &tplAjaxResults
        chunk to style the ajax output results outer
        by default: @FILE:".AS_SPATH.'templates/ajaxResults.tpl.html'


---- &tplAjaxResult
        chunk to style each output result
        by default: @FILE:".AS_SPATH.'templates/ajaxResult.tpl.html'
        
        
----------------------------------------------------------------
:: Non Ajax Parameters - Used only with the non-ajaxSearch mode
----------------------------------------------------------------

---- &AS_landing [int] (optional)
        Document id you would like the search to show on. (For non-ajax search)


---- &grabMax [int] (optional)
        The number of results per page returned (For non-ajax search)


---- &pageLinkSeparator [ string ] (optional)
        separator of the paging's links
        any string you want, between your page link numbers
        by default: " | "


---- &showPagingAlways [1 | 0] (optional)
	      always display paging. Even if you get only one page.
	      Set this to `1` if you would like to always show paging.
              
        Two use cases: You are using non-ajax search or when you are using ajax search but 
        you have set up showMoreResults to `1` and you have defined moreResultsPage, 
        then it may happen that ajax search result have only one page and pagination isn't showed
	      by default : 0


---- &tplResults
        chunk to style the non-ajax output results outer
        by default: @FILE:".AS_SPATH.'templates/results.tpl.html'


---- &tplResult
        chunk to style each output result
        by default: @FILE:".AS_SPATH.'templates/result.tpl.html'


---- &tplPaging
        chunk to style the paging links
        @FILE:".AS_SPATH.'templates/paging.tpl.html'


----------------------------------------------------------------
:: CSS                         
----------------------------------------------------------------
    The following items are used to style the starting form and
    ajax result container.

    #ajaxSearch_form - id of the search form
    #ajaxSearch_input - id of the input box on the form
    #ajaxSearch_submit - id of the submit button
    #ajaxSearch_output - id of the div that the ajax results are returned in


    The following items are used to style the results when the user does not have 
    javascript or they have clicked the more results link
    
    #ajaxSearch_resultListContainer - id of the results container
    .ajaxSearch_paging - class for span of result pages listing
    .ajaxSearch_currentPage - class for span the current page
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
:: Templating and Placeholders                         
----------------------------------------------------------------

  To use an another template, define the template name and location with 
  @FILE:assets/snippets/ajaxSearch/templates/folderName/templateName.tpl.htm
  or create a chunck parameter by copy/paste and change of an existing template.

    &tplLayout : chunk to style the ajaxSearch input form and layout
          Global ajaxSearch layout (input form, intro message, results)
          by default : /templates/layout.tpl.html

  Non ajax mode & more results page :

    &tplResults : chunk to style the output results outer
          Results outer layout (number of results, list of results, paging)
          by default : /templates/results.tpl.html

    &tplResult : chunk to style each output result
          Result template (title link, description, extract, breadcrumbs link)
          by default : /templates/result.tpl.html
  
    &tplPaging : chunk to style the paging links
          Paging link template (pages, current page)
          by default : /templates/paging.tpl.html

    &tplComment
        chunk to style comment form
        by default: @FILE:".AS_SPATH.'templates/comment.tpl.html'

  Ajax mode :

    &tplAjaxResults : chunk to style the ajax output results outer
          Results outer layout (number of results, list of results, more results link)
          by default : /templates/ajaxResults.tpl.html
  
    &tplAjaxResult : chunk to style each ajax output result
          Result template (title link, description, extract, breadcrumbs link)
          by default : /templates/ajaxResult.tpl.html


searchString available as placeholder
Use [+as.searchString+] to get the search string used for the search.
For instance use "Search results for [+as.searchString+]" as pagetitle for your landing page.
    
All the fields of the main table and of the joined table defined as "displayed" 
in the table definition could be used with the tplResult and tplAjaxResult templates:

For each field named "xxxx" we having:
[+as.xxxx+] the content of the field named xxxx
[+as.xxxxShow+] a boolean value which is equal to 0 when xxxx='', 1 otherwise
[+as.xxxxClass+] a class name equal to:
- ajaxSearch_resultXxxx for the non ajax results (&tplResult)
- AS_ajax_resultXxxx for the ajax window (&tplAjaxResult)


For instance, with &whereSearch="content,tv", the following informations are available

"content" as main table - id field : [+as.id+] 
"content" as main table - date field : [+as.publishon+]
"content" as main table - displayed fields :

[+as.pagetitle+],   [+as.pagetitleShow+],   [+as.pagetitleClass+]
[+as.longtitle+],   [+as.longtitleShow+],   [+as.longtitleClass+]
[+as.description+], [+as.descriptionShow+], [+as.descriptionClass+]
[+as.alias+],       [+as.aliasShow+],       [+as.aliasClass+]
[+as.introtext+],   [+as.introtextShow+],   [+as.introtextClass+]
[+as.menutitle+],   [+as.menutitleShow+],   [+as.menutitleClass+]
[+as.content+],     [+as.contentShow+],     [+as.contentClass+]

"content" as main table - breadcrumbs link:
[+as.breadcrumbs+],[+as.breadcrumbsShow+],[+as.breadcrumbsClass+]


"tv" as joined table - 'concat_alias' field : [+as.tv_value], [+as.tv_valueShow+], [+as.tv_valueClass+]

and in any case the extract result built with &extract parameter:


[+as.extract+], [+as.extractShow+], [+as.extractClass+]
with AS_ajax_resultExtract (ajax) or ajaxSearch_resultExtract (non-ajax) as class names

With &whereSearch="content,tv,maxigallery,jot" we add :

[+as.jot_content], [+as.jot_contentShow], [+as.jot_contentClass]
[+as.gal_title],   [+as.gal_titleShow],   [+as.gal_titleClass]
[+as.gal_descr],   [+as.gal_descrShow],   [+as.gal_descrClass]

[+as.resultNumber+] is available to display the number of the result

&tvPhx : Set placeHolders for TV (template variables)

   0 : disallow the feature
   'tv:displayTV' : set up placeholders for each TV (named tvName) linked to the documents found (default)
   
  Placeholders for TVs are:
  
  [+as.tvName+], [+as.tvNameShow+], [+as.tvNameClass+]
  
  Where tvName is the MODx name of a TV
  
  [+as.tvName+] is the HTML output of your TV
  [+as.tvNameShow+] = 1 if the TV is not NULL
  [+as.tvNameClass+] :  
    - ajaxSearch_resultTvName for the non ajax results (&tplResult)
    - AS_ajax_resultTvName for the ajax window (&tplAjaxResult)
    
    e.g: AS_ajax_resultAsTag1, a tv named "asTag1"
    (take care, the first letter of tvName should be here an upper case)
  
  displayTV is a provided ajaxSearch function which render the Modx TV output. Don't change the name!
  
  But tvPhx could also be a user function and runs with your custom tables.


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

[!AjaxSearch? &ajaxSearch=`0` &AS_landing=`25`!]
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
:: IMPORTANT NOTE
----------------------------------------------------------------

  * Your database character set is different of utf8: 

   1. the php_mbstring extension for the multibyte support should be loaded. 
      Check the phpinfo file of your server
   2. the $database_connection_charset variable of your /manager/includes/config.inc.php file 
      should be set with the MySql database character set (not the collation) of your database. 
      e.g: latin1, latin2, ... 

Searching for words containing characters like "å,ä,ö,Å,Ä,Ö" require to configure 
your editor to avoid entity encoding. 
With TinyMCE, for that, change the entity_encoding parameter from "named" to "raw" 
in the configuration tab and save again your documents.


----------------------------------------------------------------
:: How-to change the ajaxSearch folder location
----------------------------------------------------------------

To change the location of the ajaxSearch snippet folder :

1. change the definition of AS_SPATH in snippet.ajaxSearch.txt

// Path where ajaxSearch is installed
define('AS_SPATH', 'assets/snippets/ajaxSearch/');

2. change the definition of AS_SPATH in ajaxSearchPopup.php

define ('AS_SPATH' , 'assets/snippets/ajaxSearch/');

3. if you the default release of mootools, in the js/ajaxSearch.js file 
change the _base value:

var _base = 'assets/snippets/ajaxSearch/';

Change this _base value in the js/ajaxSearch-mootools-1.2.js if you use mootools 1.2
Change this _base value in the js/ajaxSearch-jquery.js if you use jquery

4. change the _base value in the js/ajaxSearchCmt.js file


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
:: How-to use the search advHighlight plugin
----------------------------------------------------------------

AdvSearch_Highlight is an advanced "Multi-Part" variant of Search_Highlight
It allows to frame with "<!--start highlight-->" and <!--end highlight-->
several parts of the site that will be highligthed.

Install it as the search highlight plugin.


----------------------------------------------------------------
:: Where find more support
----------------------------------------------------------------
1. Look at :

    Modx Community forum >> support >> Repository Items Support >> support/comments for ajaxSearch
    
    http://modxcms.com/AjaxSearch-490.html
    
    
2. Documentation : http://wiki.modxcms.com/index.php/AjaxSearch

3. Demo site : http://www.modx.wangba.fr

4. Bugs & features : http://svn.modxcms.com/jira/browse/AJAXSEARCH
   Don't hesitate to signup for an account to post an issue or a new feature
