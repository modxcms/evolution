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

$_lang['file_does_not_exist'] = " eksisterer ikke. tjek filen.";

$_lang['default_template'] = '
    <div class="ditto_summaryPost">
        <h3><a href="[~[+id+]~]">[+title+]</a></h3>
        <div>[+summary+]</div>
        <p>[+link+]</p>
        <div style="text-align:right;">by <strong>[+author+]</strong> den [+date+]</div>
    </div>
';

$_lang['blank_tpl'] = "er blank eller du har en stavefejl i dit chunk navn, venligst tjek det.";

$_lang['missing_placeholders_tpl'] = 'En af dine Ditto skabeloner mangler en placeholder, tjek venligst skabelonen nedenunder: <br /><br /><hr /><br /><br />';

$_lang['missing_placeholders_tpl_2'] = '<br /><br /><hr /><br />';

$_lang['default_splitter'] = "<!-- splitter -->";

$_lang['more_text'] = "L&aelig;s mere...";

$_lang['no_entries'] = '<p>Ingen artikler fundet.</p>';

$_lang['date_format'] = "%d-%b-%y %H:%M";

$_lang['archives'] = "Arkiv";

$_lang['prev'] = "&lt; tidligere";

$_lang['next'] = "N&aelig;ste &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2007";	

$_lang['rss_lang'] = "da";

$_lang['debug_summarized'] = "Antal som skal resumeres (summarize):";

$_lang['debug_returned'] = "<br />Total som det er meningen der skal returneres:";

$_lang['debug_retrieved_from_db'] = "antal af total i db:";

$_lang['debug_sort_by'] = "Sorter efter (sortBy):";

$_lang['debug_sort_dir'] = "Sorter retning (sortDir):";

$_lang['debug_start_at'] = "Start ved";

$_lang['debug_stop_at'] = "og stop ved";

$_lang['debug_out_of'] = "ude af";

$_lang['debug_document_data'] = "Dokument Data for ";

$_lang['default_archive_template'] = "<a href=\"[~[+id+]~]\">[+title+]</a> (<span class=\"ditto_date\">[+date+]</span>)";

$_lang['invalid_class'] = "Ditto klassen er ugyldig. tjek den venligst.";

// New language key added 2-July-2006 to 5-July-2006

// Keys deprecated : $_lang['api_method'] and $_lang['GetAllSubDocs_method'] 

$_lang['tvs'] = "TVs:";

$_lang['api'] = "Bruger den nye MODx 0.9.2.1 API";

?>