AjaxSearch Readme ver 1.5

Created By: KyleJ (kjaebker@muddydogpaws.com)
This code is based off of the FlexSearchForm snippet created by jaredc
The javascript code is based off of the example by Steve Smith of orderlist.com
The search highlighting is based off of the code by Marc (MadeMyDay | http://www.modxcms.de)
The search highlighting plugin is based off of code from sottwell (www.sottwell.com)
The live Search functionality is from Thomas (shadock)
Many fixes/additions were contributed by mikkelwe/identity/Perrine

Note: For this code to work you must include a call to the Mootools js library in your template.
This is done automatically with the addJscript parameter unless you set it to 0.


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
        If you want the Mootools library added
        to the header of your pages automatically set this to 1.  Set to
        0 if you do not want them inculded automatically.


----------------------------------------------------------------
:: How-to use this snippet
----------------------------------------------------------------

1. Copy the index-ajax.php to the root-level of your MODx installation.

2. Copy the contents of the file snippet-ajaxSearch-tpl.php into a new snippet named AjaxSearch

3. Create a directory named AjaxSearch under the assets/snippets folder.

4. Open the AjaxSearch.js file and set the loading & close image path to an image you want to display while the search is working.

5. Copy the files: AjaxSearch.js, AjaxSearch.php, and AjaxSearch.inc.php and the loading/closing images as appropriate into the AjaxSearch folder.

6. Add the snippet call like the following:  [!AjaxSearch!]

    Note: If javascript is disabled the snippet functions like the original FlexSearchForm.
        So you will want to set any of the other options in the snippet call for these users.
        Test by calling via [!AjaxSearch? &ajaxSearch=0 &otherParamsAsNeeded=`here` !]

7. Use the following styles to change how your search looks:

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

8. If you are using the display more results link setup a new page with the snippet call to display your results.

9. Test and see the search working with Ajax!


----------------------------------------------------------------
:: How-to use the search highlight plugin
----------------------------------------------------------------

1. Create a new plugin named Search_Highlight.

2. Copy the contents of the file Search_Highlight_plugin.php into the plugin.

3. On the System Events tab select OnWebPagePrerender.

4. Somewhere in your template or document add the html:  <!--search_terms-->
       This will display the terms and a link to remove the highlighting

5. Do a search and click the link to see the search highlighting carried through to the page.
