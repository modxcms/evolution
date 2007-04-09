<?php

/*
 * Title: Language File
 * Purpose:
 *      Default Swedish language file for Ditto
 *     
 * Note:
 *      New language keys should added at the bottom of this page
 *
 * Translation: Pontus Ågren
 * Date: 2007-02-24
 */

$_lang['language'] = "svenska";

$_lang['abbr_lang'] = "sv";

$_lang['file_does_not_exist'] = " finns inte. Kontrollera filen.";

$_lang['default_template'] = <<<TPL

    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">av <strong>[+author+]</strong> den [+createdon:date=`%d-%b-%y %H:%M`+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>

TPL;

$_lang["bad_tpl"] = "<p>&[+tpl+] innehåller inga platshållare eller också är det ett ogiltigt chunk-namn, kodblock eller filnamn. Kontrollera det.</p>";

$_lang['no_documents'] = '<p>Inga dokument hittades.</p>';

$_lang['resource_array_error'] = 'Fel i resursfältet';

$_lang['prev'] = "&lt; Föregående";

$_lang['next'] = "Nästa &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2007";	

$_lang['invalid_class'] = "Ditto-klassen är ogiltig. Kontrollera den.";

$_lang['none'] = "Inga";

$_lang['edit'] = "Redigera";

$_lang['yes'] = "Ja";

$_lang['no'] = "Nej";

$_lang['params'] = "Parametrar";

$_lang['debug_head'] = "
<h2>Ditto version [+version+]</h2>
<h3>Debug-info</h3>
Antal dokument som förväntades bli summerade: [+summarize+]<br />
Antal dokument som hämtades från databasen: [+recordCount+]<br />
Sortera efter: [+sortBy+]<br />
Sorteringsriktning: [+sortDir+]<br />
Börja vid [+start+] och stanna vid [+stop+] av [+total+]<br />
Förhämtning: [+prefetch+]<br />
<h3>IDn</h3>
[+ids+]<br />
<h3>Snippetparametrar</h3>
[+call+]<br />
<h3>Filter</h3>
[+filter+]<br />
<h3>Fält</h3>
<div class='ditto_dbg_fields'>
[+fields+]
</div><br />
<h3>Dokumentdata</h3>
";

$_lang["debug_styles"] = "
<style>
  .debug {
    border: 1px solid #888;
    border-left: 5px solid #888;
    background-color: white;
    padding: 3px !important;
    margin: 5px 3px !important;
  }

 	table { border: 1px solid #888; margin: 0; padding: 0;}
  table td table {border: 0;}
  table th td {border: 1px solid #888;}
  table td { background-color:#FFFFFF; padding: 2px;}
  table th { background-color:#888; padding: 2px; border: 1px solid #888;}
  table td th { background-color:#008CBA; color: white; border: 1px solid #888; }
  table td {vertical-align: top !important;}
    .ditto_dbg_fields table table table { width: 80px; display: block} 
    .ditto_dbg_fields table table table td{ width: 80px; display: block; float: left; border: none; background: none !important;} 
    .ditto_dbg_fields table table table {display: block; float: left; border: 1px solid #888;}
  table table tr td tr td{border: 1px solid #888; }
	
</style>
";

$_lang['debug_item'] = "[+pagetitle+] ([+id+])";

?>