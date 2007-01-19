<?php

/**
 * Filename:       assets/snippets/ditto/italian.inc.php
 * Function:       Default Italian language file for Ditto.
 * Author:         The MODx Project
 * Date:           18/10/2006
 * Version:        1.0.2
 * MODx version:   0.9.5.1
 * Translation: Edipo, Nicola Lambathaki (Banzai)
*/

// NOTE: New language keys should added at the bottom of this page

$_lang['file_does_not_exist'] = " non esiste. Prego controlla il file.";

$_lang['default_template'] = '
    <div class="ditto_summaryPost">
        <h3><a href="[~[+id+]~]">[+title+]</a></h3>
        <div>[+summary+]</div>
        <p>[+link+]</p>
        <div style="text-align:right;">di <strong>[+author+]</strong> il [+date+]</div>
    </div>
';

$_lang['blank_tpl'] = "Il tuo template per Ditto è vuoto o c'è un errore nel nome del chunk, per favore controlla.";

$_lang['missing_placeholders_tpl'] = 'Nel tuo template per Ditto mancano i segnaposto (placeholders), per favore controlla il tuo template: <br /><br /><hr /><br /><br />';

$_lang['missing_placeholders_tpl_2'] = '<br /><br /><hr /><br />';

$_lang['default_splitter'] = "<!-- divisore -->";

$_lang['more_text'] = "Leggi tutto...";

$_lang['no_entries'] = '<p>Nessun inserimento.</p>';

$_lang['date_format'] = "%d-%b-%y %H:%M";

$_lang['archives'] = "Archivi";

$_lang['prev'] = "&lt; Precedente";

$_lang['next'] = "Successivo &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2006";

$_lang['rss_lang'] = "it";

$_lang['debug_summarized'] = "Presunto numero di post da sommarizzare (nrposts/count):";

$_lang['debug_returned'] = "<br />Totale presunto per essere rinviato:";

$_lang['debug_retrieved_from_db'] = "Conto totali nel db:";

$_lang['debug_sort_by'] = "Ordina per (sortby):";

$_lang['debug_sort_dir'] = "Senso d'ordine (sortdir):";

$_lang['debug_start_at'] = "Inizia a";

$_lang['debug_stop_at'] = "e termina a";

$_lang['debug_out_of'] = "fuori di";

$_lang['debug_document_data'] = "Dati del documento per";

$_lang['default_archive_template'] = "<a href=\"[~[+id+]~]\">[+title+]</a> (<span class=\"ditto_date\">[+date+]</span>)";

$_lang['invalid_class'] = "La classe di Ditto non è valida. Per favore controllala.";

// New language key added 2-July-2006 to 5-July-2006

// Keys deprecated : $_lang['api_method'] and $_lang['GetAllSubDocs_method']

$_lang['tvs'] = "TV:";

$_lang['api'] = "Sto usando le API di MODx 0.9.2.1";

?>