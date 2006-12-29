<?php

/**
 * Filename:       assets/snippets/ditto/portuguese.inc.php
 * Function:       Default Portuguese language file for Ditto.
 * Author:         The MODx Project
 * Date:           	18/12/2006
 * Version:        1.0.2
 * MODx version:   0.9.5
*/
// NOTE: New language keys should added at the bottom of this page

$_lang['file_does_not_exist'] = " n&atilde;o existe. Por favor verifique o ficheiro.";

$_lang['default_template'] = '
    <div class="ditto_summaryPost">
        <h3><a href="[~[+id+]~]">[+title+]</a></h3>
        <div>[+summary+]</div>
        <p>[+link+]</p>
        <div class="imgright">por <strong>[+author+]</strong> a [+date+]</div>
    </div>
';

$_lang['blank_tpl'] = "est&aacute; em branco ou existe um erro no nome do Chunk, por favor verique esta situa&ccedil;&atilde;o.";

$_lang['missing_placeholders_tpl'] = 'Faltam ponteiros (placeholders) num dos seus templates do Ditto por favor verifique o template abaixo: <br /><br /><hr /><br /><br />';

$_lang['missing_placeholders_tpl_2'] = '<br /><br /><hr /><br />';

$_lang['default_splitter'] = "<!-- splitter -->";

$_lang['more_text'] = "Ler mais...";

$_lang['no_entries'] = '<p>Nenhum artigo encontrado.</p>';

$_lang['date_format'] = "%d-%b-%y %H:%M";

$_lang['archives'] = "Arquivos";

$_lang['prev'] = "&lt; anterios";

$_lang['next'] = "Seguinte &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2006";	

$_lang['rss_lang'] = "pt";

$_lang['debug_summarized'] = "N&uacute;mero supostamente resumido:";

$_lang['debug_returned'] = "<br />Total supostamente a retornar:";

$_lang['debug_retrieved_from_db'] = "Contagem do total na BD:";

$_lang['debug_sort_by'] = "Ordenar por (sortBy):";

$_lang['debug_sort_dir'] = "Direc&ccedil;&atilde;o de ordena&ccedil;&atilde;o (sortDir):";

$_lang['debug_start_at'] = "Come&ccedil;ar em";

$_lang['debug_stop_at'] = "e parar em";

$_lang['debug_out_of'] = "de entre";

$_lang['debug_document_data'] = "Dados do documento para ";

$_lang['default_archive_template'] = "<a href=\"[~[+id+]~]\">[+title+]</a> (<span class=\"ditto_date\">[+date+]</span>)";

$_lang['invalid_class'] = "A classe do Ditto class &eacute; inv&aacute;lida. Por favor verifique esta situa&ccedil;&atilde;o.";

// New language key added 2-July-2006 to 5-July-2006

// Keys deprecated : $_lang['api_method'] and $_lang['GetAllSubDocs_method'] 

$_lang['tvs'] = "TVs:";

$_lang['api'] = "A usar a nova API do MODx 0.9.2.1 API";

?>