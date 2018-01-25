<?php
/**
 * Title: Language File
 * Purpose: Default French language file for Ditto
 * Author: David Mollière, Jean-Christophe Brebion
 *
 * Please commit your language changes on Transifex (https://www.transifex.com/projects/p/modx-evolution/) or on GitHub (https://github.com/modxcms/evolution).
 */
$_lang['language'] = "french";
$_lang['abbr_lang'] = "fr";
$_lang['file_does_not_exist'] = "n\'existe pas. Merci de vérifier le fichier.";
$_lang['extender_does_not_exist'] = "extender does not exist. Please check it.";
$_lang['default_template'] = '
    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_introText">[+introtext+]</div>
        <div class="ditto_documentInfo">par <strong>[+author+]</strong> le [+createdon:date=`%d-%b-%y %H:%M`+]</div>
    </div>
';
$_lang["bad_tpl"] = "<p>&[+tpl+] either does not contain any placeholders or is an invalid chunk name, code block, or filename. Please check it.</p>";
$_lang['missing_placeholders_tpl'] = 'One of your Ditto templates are missing placeholders, please check the template below:';
$_lang['no_documents'] = '<p>Aucune Ressource trouvée.</p>';
$_lang['resource_array_error'] = 'Resource Array Error';
$_lang['prev'] = "&lt; Précédent";
$_lang['next'] = "Suivant &gt;";
$_lang['button_splitter'] = "|";
$_lang['default_copyright'] = "[(site_name)] 2009";
$_lang['invalid_class'] = "La classe Ditto est invalide. Merci de la vérifier.";
$_lang['none'] = "Aucun";
$_lang['edit'] = "Éditer";
$_lang['dateFormat'] = "%d-%b-%y %H:%M";

// Debug Tab Names
$_lang['info'] = "Informations";
$_lang['modx'] = "MODX";
$_lang['fields'] = "Fields";
$_lang['templates'] = "Modèles";
$_lang['filters'] = "Filters";
$_lang['prefetch_data'] = "Prefetch Data";
$_lang['retrieved_data'] = "Retreived Data";

// Debug Text
$_lang['placeholders'] = "Placeholders";
$_lang['params'] = "Paramètres";
$_lang['basic_info'] = "Basic Info";
$_lang['document_info'] = "Document Info";
$_lang['debug'] = "Debug";
$_lang['version'] = "Version";
$_lang['summarize'] = "Summarize";
$_lang['total'] = "Total";
$_lang['sortBy'] = "Trier par";
$_lang['sortDir'] = "Direction du tri";
$_lang['start'] = "Commencer à";
$_lang['stop'] = "Arrêter à ";
$_lang['ditto_IDs'] = "IDs";
$_lang['ditto_IDs_selected'] = "Selected IDs";
$_lang['ditto_IDs_all'] = "All IDs";
$_lang['open_dbg_console'] = "Open Debug Console";
$_lang['save_dbg_console'] = "Save Debug Console";
