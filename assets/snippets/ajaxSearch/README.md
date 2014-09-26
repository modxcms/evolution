#AjaxSearch 1.10.1

Ajax and non-Ajax search that supports results highlighting

Authors: Coroico <coroico@wangba.fr><br/>Jason Coward <jason@opengeek.com><br/>KyleJaebker <kjaebker@muddydogpaws.com><br/>Ryan Thrash <ryan@vertexworks.com>

Basic usage:
[!AjaxSearch!]

#Parameters

Name | Description | Default value
-----|-------------|--------------
config | Load a custom configuration from *`config`.config.php* in the folder *assets/snippets/ajaxSearch/configs/*<br/>With @FILE a file relative to MODX base path could be specified. See **Configuration Files** section for further explanation. | `default`
debug | Output debugging information.<br/>`0` debug not active.<br/>`1` Parameters, search context and sql query logged. <br/>`2` Parameters, search context, sql query and templates logged.<br/>`3` Parameters, search context, sql query, templates and results logged. | `0`
language | Sets the ajaxSearch language.<br/>Available language files could be found in the folder *assets/snippets/ajaxSearch/lang/*:<br/>`arabic-utf8`, `Chinese`, `chinese_simplified-utf8`, `chinese_simplified`, `chinese_traditional-utf8`, `chinese_traditional`, `czech`, `danish`, `english-utf8`, `english`, `finnish`, `francais-utf8`, `francais`, `german`, `hebrew`, `icelandic`, `indonesian`, `italian`, `japanese-utf8`, `nederlands`, `norsk-utf8`, `norsk`, `persian`, `portuguese-br`, `portuguese`, `russian-UTF8`, `russian`, `slovak-utf8`, `slovak`, `spanish-utf8`, `spanish`, `svenska-utf8`, `svenska` | MODX manager language
ajaxSearch | Use the ajax mode.<br/>`0` off, `1` on, `>1` custom usage | `1`
advSearch | Set the advanced search options.<br/>`exactphrase` provides the documents which contain the exact phrase<br/>`allwords` provides the documents which contain all the words<br/>`nowords` provides the documents which do not contain the words<br/>`oneword` provides the document which contain at least one word | `oneword`
subSearch | Define sub-domains or sites where to search for each site. A custom search function has to be defined in the configuration file.<br/>Example: ``&subSearch=`products,employee` ``
asId | Unique id for AjaxSearch instance to distinguish several AjaxSearch instances on the same page. The id is used to link the snippet calls between them. Choose a short name. For example: ``&asId=`as2` ``. In ajax mode, the first AjaxSearch snippet call shouldn’t use the parameter `&asId`
timeLimit | Max execution time in seconds for the AjaxSearch script. 0 for an unlimited execution time | 60
whereSearch | Set in which tables & fields the search occurs. A separated list of keywords describing the tables where to search in the form `keyword:table fields`. If you want to exclude all content table fields from search you could use `content:null`<br/>The following keyword are predefined:<br/>`content` searches in `pagetitle`, `longtitle`, `description`, `alias`, `introtext`, `menutitle` and `content` in `site_content` table<br/>`tv` searches in `value` in `site_tmplvar_contentvalues` table, `jot` searches in `content` in `jot_content` table , `maxigallery` searches in `gal_title` and `gal_descr` of `maxi gallery` table | `content|tv`
withTvs | Add template variables als placeholder values to the search results. Could cause performances issues, so use it wisely.<br/>Format: `[[+-][:tvlist]]`<br/>Examples:<br/>`tvl,tv2,tv3` the content of tvl, tv2 and tv3 are added as placeholder values<br/>`+:tvl,tv2,tv3` the content of tvl, tv2 and tv3 are added as placeholder values<br/>`+` the content of all template variables of the site are added as placeholder values<br/>`:` the content of all template variables of the site are added as placeholder values<br/>`-:tvl,tv2,tv3` the content of all template variables of the site except of tvl, tv2 and tv3 are added as placeholder values | 
tvPhx | Add template variables als PHx placeholder values to the search results. Could cause performances issues, so use it wisely. Same usage as `&withTvs` | 
category | Define categories.<br/>Should contain the name of a template variable that is contains the category of each search result item. The displayed title of category could be renamed in the categConfig function. See grpLabel parameter. |
display | If the results comes from different sites, subsites or categories, the display could be set to mixed or unmixed. Possible values:<br/>`mixed` mixes all the results coming from the different areas.<br/>`unmixed` displays the results grouped by site, subsite or category. Each group of results can be paginated.<br/>In unmixed mode the results are ordered by the first field of the order parameter | `unmixed`
order | Sort order of results. Comma separated list of fields defined as searchable in the table definition. To suppress sorting, use ``&order=` ` ``<br/>Example: `pagetitle DESC, pub_date` | `publishedon,pagetitle`
rank | Define the rank of search results. Results are sorted by rank value.<br/>Comma separated list of fields (extract excluded) with optionally user defined weight.<br/>To suppress the rank sorting, use ``&rank=` ` ``<br/>`&rank` sort occurs after the `&order` sort. |
maxWords | Maximum number of words for searching.<br/>Could contain values between 1 and 10. | `5`
minChars | Minimum number of characters to require for a word to be valid for searching.<br/>Could contain values between 1 and 100.<br/>Contains the minimal length of each word with `&advSearch` set to `allwords`, `oneword` or `nowords`. Contains the minimal length of the search string with possible spaces with `&advSearch` set to `exactphrase` | `3`
showInputForm | Hide/show the search form when showing results.<br/>`0` off, `1` on | `1`
showIntro | Show/hide the introduction message displayed in the search form.<br/>`0` off, `1` on | `1`
extract | Maximum number of extracts that will be displayed per document and define which fields will be used to set up extracts.<br/>Format `n:searchable fields list` with *n = maximum number of extracts displayed*<br/>Example: ``&extract=`99:content` `` | One extract searched in `content`, `description`, `introtext` and `tv_content`
extractLength | Length of one extract.<br/>Could contain values between 50 and 800. | `200`
extractEllips | String to mark the start and the end of an extract inside of a sentence. | `...`
extractSeparator | String to mark the separation between each extract. | `<br />`
formatDate | Format of outputted dates (See date function in [PHP manual](http://www.php.net/manual/en/function.date.php)) | `d/m/y : H:i:s`
highlightResult | Create links that way that search terms will be highlighted when linked page is clicked.<br/>Requires Highlight plugin.<br/>`0` off, `1` on | `1`
pagingType | Type of pagination.<br/>`0` Results Pages 1 &#124; 2 &#124; 3, `1` Previous - X-Y/Z - Next, `2` X-Y/Z - Show 10 more results<br/>`0` or `1` could be used in non ajax mode, `1` or `2` could be used in ajax mode | `1`
pageLinkSeparator | Symbol between the page link numbers. | `|`
showResults | Show the search results.<br/>`0` off, `1` on | `1`
parents | Comma separated list of document IDs which children are searched/not searched until the depth of `&depth` parameter is reached.<br/>Could be prefixed with `in:` or `not in:`<br/>See examples in `&documents` parameter. | 
depth | Depth of the search starting with parents parameter or MODX root | `10`
documents | Comma separated list of document IDs that are searched/not searched.<br/>Could be prefixed with `in:` or `not in:`<br/>Examples:<br/>`in:28,29,30,31` searches in the documents 28, 29, 30, 31<br/>`not in:28,29,30,31` searches in all documents except in the documents 28, 29, 30, 31<br/>`in:28,29,30,31` could be shortened to `28,29,30,31`
hideMenu | Search in hidden documents from menu.<br/>`0` Search only in documents visible from menu.<br/>`1` Search only in documents hidden from menu.<br/>`2` Search in hidden or visible documents from menu. | `2`
hideLink | Search in weblinks.<br/>`0` Search in resources with resource type *Web page* and *Weblink*.<br/>`1` Search only in resources with resource type *Web page*. | `1`
filter | Exclude unwanted documents. Same usage as the filter parameter in Ditto 2.1<br/>The metacharacter `#` is replaced by the search string. The advSearch parameter is also taken into account, if advSearch is `oneword`, `nowords` or `allwords` then `#` is replaced by as many filters as search terms. Filtering by template variable name is possible this way.<br/>Examples:<br/>``&filter=`pagetitle,#,8` `` with search string *school child* and ``&advSearch=`oneword` `` is equivalent to ``&filter=`pagetitle,school,81 pagetitle,child,8` ``<br/>``&filter=`articleTags,volcano,7` `` displays only documents tagged with volcano.
output | Custom layout.<br/>`0` Snippet output at the place of the snippet call<br/>`1` Snippet output in the placeholders `[+as.inputForm+]` and `[+as.results+]` | `0`
stripInput | Transform the search input extract on fly by a php function. The parameter value contains the name of the function. The function itself is defined in a configuration file. | `defaultStripInput` – function is defined in *ajaxSearchInput.class.inc.php*
stripOutput |  Transform the search output extract on fly by a php function. The parameter value contains the name of the function. The function itself is defined in a configuration file. The document is parsed before the relevant piece of text around the search term is extracted. | `defaultStripOutput` – function is defined in *ajaxSearchResults.class.inc.php*
breadcrumbs | Display the breadcrumb path to the found document.<br/>`0` disallow the breadcrumbs link<br/>`name,parameters` the name/parameters of a function or the name/parameters of a snippet.<br/>The function itself is defined in a configuration file and it is called with the following parameters.<br/>`$main` (array) main table definition<br/>`$row` (array) search result row<br/>`$params` (array) breadcrumbs parameters passed with `&breadcrumbs` parameter. The parameters are divided by `,`, the parameter name is divided from the parameter value by `:`.<br/>The function returns the breadcrumbs link as a string.<br/>Example:<br/>`Breadcrumbs,showHomeCrumb:0,showCrumbsAtHome:1` calls the Breadcrumbs snippet or a function named *Breadcrumbs* with the parameter array `showHomeCrumb => 0, showCrumbsAtHome => 1`
clearDefault | Clearing default text.<br/>`0` off<br/>`1` on. Include the clear default javascript function in the head. Add the class *cleardefault*  to the input in the search form and set this parameter to `1` | `0`
jsClearDefault | Location of the clearDefault javascript library. | AjaxSearch path + `js/clearDefault/clearDefault.js`
mbstring | Use the php_mbstring extension.<br/>`0` off, `1` on | `1`
asLog | Log failed and successful search requests.<br/>Format: `level[:comment[:purge]]`<br/>*level*<br/>`0` disallow the ajaxSearch log (default)<br/>`1` failed search requests are logged<br/>`2` all ajaxSearch requests are logged<br/>*comment*<br/>`0` user comment not allowed (default)<br/>`1` user comment allowed<br/>*purge* – number of logs allowed before the log table is purged<br/>`0` no Purge allowed (= illimited number of logs)<br/>``&aslog=`x` `` is equivalent to ``&asLog=`x:0:200` ``<br/>``&aslog=`x:y` `` is equivalent to ``&asLog=`x:y:200` ``<br/>``&asLog=`1:1:500` `` means that 500 failed search requests possibly commented by the end user could be stored in the ajaxSearchLog table. See the AjaxSearch Log Manager module | `0:0:200`

##Ajax

Used in ajaxSearch mode

Name | Description | Default value
-----|-------------|--------------
liveSearch | Use the live search (Show results during typing).<br/>`0` off, `1` on. | `0`
ajaxMax | Maximum number of results showed in a group of results. | `6`
showMoreResults | Display a link to show all search results.<br/>`0` off, `1` on. | `0`
moreResultsPage | Document ID of the target page for the more results link. This page should contain another AjaxSearch snippet call to display the results. | `0`
opacity | Opacity of the *ajaxSearch_output* div. | `1`
jscript | Select the javascript framework used by AjaxSearch javascript.<br/>`jquery` jQuery Framework<br/>`mootools2` MooTools 1.2 Framework<br/>`mootools` MooTools 1.1 Framework | `mootools2`
addJscript | Add the MooTools library to the head section of web pages automatically.<br/>`0` off, `1` on. | `1`
jsMooTools | Location of the MooTools 1.1 javascript library. | `manager/media/script/mootools/mootools.js`
jsMooTools2 | Location of the MooTools 1.2 javascript library. | AjaxSearch path + ` js/mootools2/mootools1.2.js`
jsJquery | Location of the jQuery javascript library. | `assets/js/jquery.min.js`

##Non Ajax

Used in non-ajaxSearch mode

Name | Description | Default value
-----|-------------|--------------
landingPage | Document ID of the target page for the search result (non Ajax search) |
grabMax | Maximum number of results showed in a group of results. | `6`
showPagingAlways | Always display paging, even if the result is only one page. If ``&showMoreResults=`1` `` and `&moreResultsPage` is defined, then it could happen that ajaxsearch result will only have one page and pagination is not shown.<br/>`0` off, `1` on. | `0`

#Templates
To use another template, define the template name and location with `@FILE:assets/snippets/ajaxSearch/templates/folderName/templateName.tpl.html` or create a new chunk, copy/paste the content of an existing template and edit it.

##Input Templates

Name | Description | Default value
-----|-------------|--------------
tplInput | Style the AjaxSearch input form. Could contain the following form elements:<br/>A set of check boxes to select the value of the advanced Search parameter (name=advSearch)<br/>An input or a dropdown select field (name=search), to catch the search terms | AjaxSearch path + `templates/input.tpl.html`

To demonstrate the possibilities, there are several demos in `templates/inputTemplates` folder:

- `input1.tpl.html` Simple search with a simple input field.
- `input2.tpl.html` Simple search with a multiple input list.
- `input3.tpl.html` Advanced search. An input field with the selection of the advanced Search parameter.

The advanced search parameter can be mixed with the input list (2 & 3).

In liveSearch mode (ajax) the submit button is not displayed. A search is done after each character is typed.

##Non-Ajax Output Templates

Name | Description | Default value
-----|-------------|--------------
tplGrpResult | Style the non-ajax output group result outer | AjaxSearch path + `templates/grpResult.tpl.html`
tplResults | Style the non-ajax output results outer | AjaxSearch path + `templates/results.tpl.html`
tplResult | Style each output result | AjaxSearch path + `templates/result.tpl.html`
tplComment | Style the comment form (also used with the ajax mode) | AjaxSearch path + `templates/comment.tpl.html`
tplPaging0 | Style the paging links – type 0 | AjaxSearch path + `templates/Paging0.tpl.html`
tplPaging1 | Style the paging links – type 1 | AjaxSearch path + `templates/Paging1.tpl.html`

##Ajax Output Templates

Name | Description | Default value
-----|-------------|--------------
tplAjaxGrpResult | Style the ajax output group result outer | AjaxSearch path + `templates/ajaxGrpResult.tpl.html`
tplAjaxResults | Style the ajax output results outer | AjaxSearch path + `templates/ajaxResults.tpl.html`
tplAjaxResult | Style each output result | AjaxSearch path + `templates/ajaxResult.tpl.html`
tplPaging1 | Style the paging links - type 1 | AjaxSearch path + `templates/Paging1.tpl.html`
tplPaging2 | Style the paging links - type 2 | AjaxSearch path + `templates/Paging2.tpl.html`

##Placeholders

These placeholders are used in the templates.

Name | Description
-----|------------
`[+as.searchString+]` | The search string used for the search. E.g.: "Search results for [+as.searchString+]" as pagetitle for the landing page.
`[+as.****+]` | The field with the column name **** of the main table and of the joined table could be displayed in tplResult and tplAjaxResult templates. All TVs could be displayed with `&withTvs` or `&tvPhx`
`[+as.****Show+]` | Boolean value which is equal to 0 when the placeholder **** is empty, otherwise 1
`[+as.****Class+]` | Class name equal to: ajaxSearch_result**** for the non ajax results and AS_ajax_result**** for ajax results

### Placeholder Examples

With ``&whereSearch=`content,tv` ``, there are these placeholders:

Name | Value
-----|------------
`[+as.id+]` | Table `content`, field `id`
`[+as.publishon+]` | Table `content`, field `publishon`
`[+as.pagetitle+]` | Table `content`, field `pagetitle`
`[+as.longtitle+]` | Table `content`, field `longtitle`
`[+as.description+]` | Table `content`, field `description`
`[+as.alias+]` | Table `content`, field `alias`
`[+as.introtext+]` | Table `content`, field `introtext`
`[+as.menutitle+]` | Table `content`, field `menutitle`
`[+as.content+]` | Table `content`, field `content`
`[+as.tv_value+]` | Table `tv`, concatened values of TVs
`[+as.breadcrumbs+]` | With `` `&breadcrumbs=`1` ``
`[+as.extract+]` | Extracts defined with `&extract`

With ``&whereSearch=`jot` ``, there are these placeholders:

Name | Value
-----|------------
`[+as.jot_content]` | Jot content

With ``&whereSearch=`maxi gallery` ``, there are these placeholders:

Name | Value
-----|------------
`[+as.gal_title]` | Image title
`[+as.gal_descr]` | Image description

All the placeholder above have the according `[+as.****Show+]` and `[+as.****Class+]` placeholder set.

##Configuration Files

###Default Values

The default configuration file contains all the required default values.

In a configuration file a global parameter could be initialized with the following syntax: `$__param = value;`. This value  could be later overwritten by a snippet call parameter. Example: `$__hideLink = 0;`

A not overwritable global parameter could be initialized with the following syntax: `$param = 'value';` This value could not be  overwritten by a snippet call parameter. E.g: `$hideLink = 0;`

###Custom stripInput Function

A custom stripInput function could be defines with the following code:

```
￼if (!function_exists(myStripInput)) { 
  function myStripInput($searchString) {
    ... // change the searchString
    return $searchString;
  }
}
```

CAUTION: Use the functions stripslashes, stripTags and stripHtml provided by AjaxSearch

###Custom ￼stripOutput Function

A custom stripOutput function could be defines with the following code:

```
￼if (!function_exists(myStripOutput)) { 
  function myStripOutput($text) {
    ... // change the output text
    return $text;
  }
}
```

###Custom categConfig Function

A custom categConfig function could be defines with the following code:

```
if (!function_exists('categConfig')) {
  function categConfig($site = 'defsite', $category = '') {
    $config = array();
    // set up config depending $site & $category 
    ....
    return $config;
  } 
}
```

￼Look in the someConfigsExamples folder to see an example for a custom categories function.

In this function a label could be defined that is linked with the group of results. This variable should not contain a comma `,`.
￼￼
##Custom PHx Modifiers

If you want to use ￼￼￼￼images in the AjaxSearch templates you could use the possible following PHx modifier on the imgTag placeholder:

Modifier | Value
---------|------------
imgwidth | Image width
imgheigth | Image height
imgattr | Image attributes in the form `height="xxx" width="yyy"`
imgmaxwidth | Limit the width of the image to a maximum. Under this limit, keep the true width
imgmaxheight | Limit the height of the image to a maximum. Under this limit, keep the true height

###Examples

Show an image with its original height and width:
`<img src="[+as.imgTag+]" width="[+as.imgTag:imgwidth+]" height="[+as.imgTag:imgheigth+]"/>`
`<img src="[+as.imgTag+]" [+as.imgTag:imgattr+] />`

Show an image with limited height and width:
``<img src="[+as.imgTag+]" width="[+as.imgTag:imgmaxwidth=`60`+]" height="[+as.imgTag:imgmaxheight=`90`+]"/>``

￼￼￼These modifiers are useful in ajax mode to to determine the size of the AjaxSearch pop-up before the images are retrieved from the server.

##AjaxSearch Log Manager Module

A module provided to display the search logs from the MODx manager
￼￼￼￼￼￼
##Highlight Plugins
￼￼￼￼
Authors: Coroico <coroico@wangba.fr>, Kyle Jaebker <kjaebker@muddydogpaws.com>, Susan Ottwell <sottwell@sottwell.com>

When a user clicks on the link from the AjaxSearch results the target page will have the terms highlighted.
￼
###￼￼￼￼searchHighlight Plugin
￼
Highlight the containing terms between `<body>` and `</body>`
￼￼￼
###advSearchHighlight Plugin

Highlight the containing terms between `<!--start highlight-->` and `<!--end highlight-->`. Several distinct blocks are allowed. 

To create show the searchterms and a link on the page that removes the highlighting and insert the following code on the page where this should appear: `<!--search_terms-->`

The following variables in the plugin code could be used to change the text:

Name | Description
-----|------------
`$termText` | The text before the search terms
`$removeText` | The text for the remove link

##AjaxSearch Glossary

###ajaxSearch Mode

Search results displayed in current page through AJAX request.

###Non ajaxSearch Mode

Search results displayed in a new page.

###Search Term

The entry term in the search form.

###Extract

Part of a document extracted and added in the results page.

###Search Result

Title (as a link to the document), description and extract.

###Rank

Value of ranking depending of number of search results and where the results have been found.

###Search Results Page

All the search results with optionaly a more results link to a showMoreResult page or all the search results paginated with the grabMax parameter.

###Highlighted Term

In the search results page, the search terms found could be (or not) highlighted. This needs the plugin searchHighlight. 

###Category

A group of search results tagged with the same TV.

##AjaxSearch Resources Links

Forum board: MODx: http://forums.modx.com/board/94/ajaxsearch

ClipperCMS: http://www.clippercms.com/forum/extras-%28core%29/

Bugs and new feature requests: https://github.com/modxcms/ajaxsearch