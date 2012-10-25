<?php

/*
 * Title: Ditto French Language File
 * Desc: Aggregates documents to create blogs, article/news
 * 		 collections, etc.,with full support for templating.
 * About: Default English language file for Ditto.
 * Author: Mark Kaplan     French Translation : David Mollière
 * Note: New language keys should added at the bottom of this page
 * Version: 2.0 RC1
 */

$_lang['language'] = "french";

$_lang['abbr_lang'] = "fr";

$_lang['file_does_not_exist'] = "n\'existe pas. Merci de vérifier le fichier.";

$_lang['default_template'] = <<<TPL

    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_introText">[+introtext+]</div>
        <div class="ditto_documentInfo">par <strong>[+author+]</strong> le [+createdon:date=`%d-%b-%y %H:%M`+]</div>
    </div>

TPL;

$_lang['no_documents'] = '<p>Aucun document trouvé.</p>';

$_lang['resource_array_error'] = 'Resource Array Error';
 
$_lang['prev'] = "&lt; Précédent";

$_lang['next'] = "Suivant &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2007";

$_lang['invalid_class'] = "La classe Ditto est invalide. Merci de la vérifier.";

$_lang['none'] = "Aucun";

$_lang['edit'] = "Editer";

$_lang['yes'] = "Oui";

$_lang['no'] = "Non";

$_lang['params'] = "Paramètres";

$_lang['debug_head'] = "
<h2>Ditto version [+version+]</h2>
<h3>Information de debug</h3>
Nombre de documents à afficher : [+summarize+]<br />
Nombre de documents extraits de la base : [+recordCount+]<br />
Trier par : [+sortBy+]<br />
Direction du tri : [+sortDir+]<br />
Commencer à l'item [+start+] et arrêter à [+stop+] parmi [+total+]<br />
Prefetch: [+prefetch+]<br />
<h3>IDs</h3>
[+ids+]<br />
<h3>Paramètres du snippet</h3>
[+call+]<br />
<h3>Filtres</h3>
[+filter+]<br />
<h3>Champs</h3>
<div class='ditto_dbg_fields'>
[+fields+]
</div><br />
<h3>Données du document</h3>
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
	.ditto_dbg_fields table table table { width: 60px; display: block}
	.ditto_dbg_fields table table table td{display: block; float: left;}
	table table tr td tr td{border: 1px solid #888; }
	
</style>
";

$_lang['debug_item'] = "[+pagetitle+] ([+id+])";

?>