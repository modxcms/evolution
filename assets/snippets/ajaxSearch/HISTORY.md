#Changelog:

05-jun-14 (1.10.1)

- Security/Bug fixes

27-mar-13 (1.10.0)

- Security/Bug fixes

26-sep-12 (1.9.3)

- Bug fixing
- Removed ajaxsearch's own striptags functions and substituted the use of $modx->stripTags
- minimum chars allowed to 2

05-dec-10 (1.9.2)

- Bug fixing

30-aug-10 (1.9.2)

- Bug fixing

18-may-10 (1.9.0)

- Completely refactored - MVC model implemented
- Defines categories and display of group of results
- Several AS call on same page
- parents (in / not in), documents (in / not in)
- Custom output
- Filtering search results by tv name
- Filter features (allow to set up specific search forms)
- Bug fixing

20-oct-09 (1.8.4)

- Sites and subsites notions
- Defines categories and display of group of results
- Several AS call on same page
- Bug fixing

14-jun-09 (1.8.4)

- Sites and subsites notions
- Defines categories and display of group of results
- Several AS call on same page
- Bug fixing

08-jun-09 (1.8.3)

- Bug fixing
- The number of results is available with the [+as.resultNumber+] placeholder

01-mar-09 (1.8.2)

- liveSearch parameter renamed
- Initialisation of configuration parameters is modified
- mbstring parameter added
- Limit the amount of keywords that will be queried by a search
- Capturing failed search criteria and search logs
- Compatibility with mootools 1.2.1 library
- Compatibility with jquery library
- Always display paging parameter added
- Bug fixing

02-oct-08 (1.8.1)

- subSearch added.
- mysql query redesigned.
- whereSearch parameter improved. Fields definition added
- withTvs parameter added. specify the search in Tvs
- metacharacter for filter
- improvement of the searchword list parameter
- debug - file and firebug console
- Bug fixing

21 -July-08 (1.8.0)

- define where to do the search (&whereSearch parameter)
- define which fields to use for the extracts (&extract parameter)
- use AjaxSearch with non MOdx tables
- order the results with the &order parameter
- define the ranking value and sort the results with it
- filter the unwanted documents of the search
- define the extract eliipsis
- define the extract separator
- Extended place holder templating and template parameters
- Improvement of the extract algorithm
- Define the number of extracts displayed in the search results
- Use of &advSearch parameter available from the front-end by the end user
- Choose your search term from a predefined search word list
- stripInput user function
- stripOutput user function
- Configuration file and $__ global parameters
- snippet code completely refactored and objectified
- Bugfixes regarding Quoted searchstring

06-Mar-08 (1.7.1)

- Advanced search (partial & relevance)
- Search in hidden documents from menu
- List of Ids limited to parent-documents ids in javascript
- Code cleaning

06-Jan-08 (1.7)

- Added custom config file
- Added list of parent-documents where to search
- Added opacity parameter (between 0 (transparent) and 1 (opaque)
- Added bugfixes regarding opacity with IE
- Using of DBAPI function instead of deprecated function
- Charset troubles corrected

22-Jan-07 (1.6)

- Added templating support (includes/templates.inc.php)
- Added language support
- Switched from prototype/scriptaculous to Mootools
	
03-Jan-07

- Added many bugfixes/additions from AjaxSearch forum

18-Sep-06

- Added code to only show results for allowed pages

05-May-06

- Added liveSearch functionality and new parameter

21-Apr-06

- Added code to make it compatible with tagcloud snippet

20-Apr-06

- Added code from eastbind & japanese community for other language searching

04-Apr-06

- Added search term highlighting

01-Apr-06

- initial commit into SVN

30-Mar-06

- initial work based on FSF_ajax from KyleJ