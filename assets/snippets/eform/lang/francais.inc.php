<?php
/**
* snippets/eform/francais.inc.php
* Fichier Langue en francais pour eForm
*/


$_lang["ef_thankyou_message"] = "<h3>Merci !</h3><p>Vos informations ont été correctement transmises.</p>";
$_lang["ef_no_doc"] = "Document ou chunk introuvable pour le modèle n°=";
//$_lang["ef_no_chunk"] = ""; //obsolète
$_lang["ef_validation_message"] = "<div class=\"errors\"><strong>Des erreurs ont été detectées dans le formulaire :</strong><br />[+ef_wrapper+]</div>";
$_lang["ef_required_message"] = " Les champs indispensables ci-dessous sont introuvables: {fields}<br />";
$_lang["ef_invalid_number"] = " n'est pas un nombre valide";
$_lang["ef_invalid_date"] = " n'est pas une date valide";
$_lang["ef_invalid_email"] = " n'est pas une adresse mail valide";
$_lang["ef_upload_exceeded"] = " a dépassé la limite de taille en upload.";
$_lang["ef_failed_default"] = "Valeur incorrecte";
$_lang["ef_failed_vericode"] = "Code de vérification invalide.";
$_lang["ef_failed_range"] = "La valeur n'est pas dans l'intervalle permis";
$_lang["ef_failed_list"] = "La valeur n'est pas dans la liste des valeurs permises";
$_lang["ef_failed_eval"] = "Valeur non validée";
$_lang["ef_failed_ereg"] = "Valeur non validée";
$_lang["ef_failed_upload"] = "type de fichier incorrect.";
$_lang["ef_error_validation_rule"] = "Règle de validation non reconnue";
$_lang["ef_tamper_attempt"] = "tentative pour trafiquer le code du formulaire detectée!";
$_lang["ef_error_formid"] = "identifiant ou nom du formulaire incorrect.";
$_lang["ef_debug_info"] = "Information de déboggage: ";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Le modèle de formulaire à utiliser est identifié par une page contenant un appel du snippet! Vous ne pouvez pas avoir le formulaire et l'appel du snippet dans le même document.</span> id=";
$_lang["ef_sql_no_result"] = " Validation silencieuse passée. <span style=\"color:red;\"> SQL n'a retouné aucun resultat!</span> ";
$_lang['ef_regex_error'] = 'Erreur dans l\'expression régulière';
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">ATTENTION - LE MODE DEBUG EST ACTIVÉ</span> <br />Assurez-vous de désactiver le mode debuggage avant de deployer ce formulaire!</p>';
$_lang['ef_mail_abuse_subject'] = 'un abus potentiel de formualaire a été détecté avec le formulaire n°';
$_lang['ef_mail_abuse_message'] = '<p>Un formulaire de votre site Web a peut-être fait l\'objet d\'une tentative d\'injection d\'email. Le détail des valeurs envoyées sont imprimées ci-dessous. Le texte suspecté est mis en valeur à l\'aide des tags \[..]\ .  </p>';
$_lang['ef_mail_abuse_error'] = '<strong>Des entrées invalides ou dangereuses ont été detectées dans votre formulaire</strong>.';
$_lang['ef_eval_deprecated'] = "La règle #EVAL est obsolète et ne devrait plus fonctionner dans les versions futures. Utilisez #FUNCTION à la place.";
$_lang['ef_multiple_submit'] = "<p>Ce formulaire a déjà été soumis avec succès. Il est inutile de soumettre le même formulaire plusieurs fois.</p>";
$_lang['ef_submit_time_limit'] = "<p>Ce formulaire a déjà été soumis avec succès. La soumission du formulaire est bloquée pour ".($submitLimit/60)." minutes.</p>";
$_lang['ef_version_error'] = "<strong>ATTENTION!</strong> La version du snippet eForum (version:&nbsp;$version) est différente de celle des fichiers eForm présents sur votre serveur (version:&nbsp;$fileVersion). Attention de bien mettre à jour les fichiers lorsque vous mettez à jour le snippet.";
$_lang['ef_thousands_separator'] = ''; //leave empty to use (php) locale, only needed if you want to overide locale setting!
$_lang['ef_date_format'] = '%d-%b-%Y %H:%M:%S';
$_lang['ef_mail_error'] = 'Le serveur mail est incapable d\'expédier l\'email';
?>