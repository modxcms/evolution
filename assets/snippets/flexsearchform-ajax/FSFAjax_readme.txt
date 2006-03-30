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

1. Copy the contents of the file flexsearchform-ajax.txt into a new snippet named FlexSearchForm-ajax
2. Create a directory named flexsearchform-ajax under the assets/snippet folder.
3. Open the ajaxSearch.js file and set the loading & close image path to an image you want to display while the search is working.
4. Copy the files: ajaxSearch.js, ajaxSearch.php, and FlexSearchForm.inc.php into the flexsearchform-ajax folder.
5. Add the snippet call like the following:  [!FlexSearchForm-ajax?ajaxSearch=1!]
    Note: If javascript is disabled the snippet functions like the original FlexSearchForm.
        So you will want to set any of the other options in the snippet call for these users.
6. Use the following styles to change how your search looks:
    #FSF_ajax_searchResults
    .FSF_ajax_result
    .FSF_ajax_resultLink
    .FSF_ajax_resultDescription
    .FSF_ajax_more
7. If you are using the display more results link setup a new page with the snippet call to display your results.
8. Add the following link to your templaet: <script src="assets/snippets/flexsearchform-ajax/ajaxSearch.js" type="text/javascript"></script>
9. Test and see the search working with AJAX!
