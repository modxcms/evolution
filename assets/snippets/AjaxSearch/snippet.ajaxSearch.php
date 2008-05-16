<?php
/* -------------------------------------------------------------
:: Snippet: ajaxSearch
----------------------------------------------------------------
  Short Description: 
        Ajax-driven & Flexible Search form

  Version:
        1.7.0.2

  Created by:
	    Jason Coward (opengeek - jason@opengeek.com)
	    Kyle Jaebker (kylej - kjaebker@muddydogpaws.com)
	    Ryan Thrash  (rthrash - ryan@vertexworks.com)
	    
	    Live Search by Thomas (Shadock)
	    Fixes & Additions by identity/Perrine/mikkelwe
	    Document selection from Ditto by Mark Kaplan
	    
	    Parts refactored and new features/fixes added by Coroico (coroico@wangba.fr)
	    
  Copyright & Licencing:
  ----------------------
  GNU General Public License (GPL) (http://www.gnu.org/copyleft/gpl.html)

  Originally based on the FlexSearchForm snippet created by jaredc (jaredc@honeydewdesign.com)

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


MORE : See the ajaxSearch.readme.txt file for more informations

---------------------------------------------------------------- */

// ajaxSearch version being executed
define('AS_VERSION', '1.7.0.2');

// Path where ajaxSearch is installed
define('AS_SPATH', 'assets/snippets/AjaxSearch/');

//==============================================================================
//include snippet file
define ('AS_PATH', $modx->config['base_path'].AS_SPATH);

$output = "";
include(AS_PATH.'includes/snippet.ajaxSearch.inc.php');

return $output;

?>