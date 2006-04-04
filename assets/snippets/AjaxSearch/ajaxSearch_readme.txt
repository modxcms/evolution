FlexSearchForm-Ajax Readme

Created By: KyleJ (kjaebker@muddydogpaws.com)
This code is based off of the FlexSearchForm snippet created by jaredc
The javascript code is based off of the example by Steve Smith of orderlist.com

Note: For this code to work you must include a call to the prototype and the scriptaculous js libraries in your template.


*******Update 03/20/06***********
There are new parameters that are set in the snippet call:
You no longer need to modify the ajaxsearch.php file

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
***********************************

How-To use this snippet:

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
        .AS_resultLink {
            text-decoration: underline;
        }
        .AS_resultDescription{
            color: #555;
        }
        .AS_ajax_more {
            color: #555;
        }

8. If you are using the display more results link setup a new page with the snippet call to display your results.

9. Add the following link to your template: <script src="assets/snippets/AjaxSearch/AjaxSearch.js" type="text/javascript"></script>

10. Test and see the search working with Ajax!


