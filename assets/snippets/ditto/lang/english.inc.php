<?php

/**
 * Filename:       assets/snippets/ditto/english.inc.php
 * Function:       Default English language file for Ditto.
 * Author:         The MODx Project
 * Date:           2006/07/2
 * Version:        1.0.2
 * MODx version:   0.9.2.1
*/

// NOTE: New language keys should added at the bottom of this page

$_lang['file_does_not_exist'] = " does not exist. Please check the file.";

$_lang['default_template'] = '
    <div class="ditto_summaryPost">
        <h3><a href="[~[+id+]~]">[+title+]</a></h3>
        <div>[+summary+]</div>
        <p>[+link+]</p>
        <div style="text-align:right;">by <strong>[+author+]</strong> on [+date+]</div>
    </div>
';

$_lang['blank_tpl'] = "is blank or you have a typo in the chunk name, please check it.";

$_lang['missing_placeholders_tpl'] = 'One of your Ditto templates are missing placeholders, please check the template below: <br /><br /><hr /><br /><br />';

$_lang['missing_placeholders_tpl_2'] = '<br /><br /><hr /><br />';

$_lang['default_splitter'] = "<!-- splitter -->";

$_lang['more_text'] = "Read more...";

$_lang['no_entries'] = '<p>No entries found.</p>';

$_lang['date_format'] = "%d-%b-%y %H:%M";

$_lang['archives'] = "Archives";

$_lang['prev'] = "&lt; Previous";

$_lang['next'] = "Next &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2006";	

$_lang['rss_lang'] = "en";

$_lang['debug_summarized'] = "Number supposed to be summarized (summarize):";

$_lang['debug_returned'] = "<br />Total supposed to be returned:";

$_lang['debug_retrieved_from_db'] = "Count of total in db:";

$_lang['debug_sort_by'] = "Sort by (sortBy):";

$_lang['debug_sort_dir'] = "Sort direction (sortDir):";

$_lang['debug_start_at'] = "Start at";

$_lang['debug_stop_at'] = "and stop at";

$_lang['debug_out_of'] = "out of";

$_lang['debug_document_data'] = "Document Data for ";

$_lang['default_archive_template'] = "<a href=\"[~[+id+]~]\">[+title+]</a> (<span class=\"ditto_date\">[+date+]</span>)";

$_lang['invalid_class'] = "The Ditto class is invalid. Please check it.";

// New language key added 2-July-2006 to 5-July-2006

// Keys deprecated : $_lang['api_method'] and $_lang['GetAllSubDocs_method'] 

$_lang['tvs'] = "TVs:";

$_lang['api'] = "Using the new MODx 0.9.2.1 API";

?>