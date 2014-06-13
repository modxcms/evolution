<?php
/**
 * Language file for eForm
 *
 * Language:       French
 * Encoding:       UTF-8
 * Translated by:  -
 * Date:           2014/06/13
 */
$_lang["ef_date_format"] = "%d-%b-%Y %H:%M:%S";
$_lang["ef_debug_info"] = "Information de débuggage: ";
$_lang["ef_debug_warning"] = "<p style=\"color:red;\"><span style=\"font-size:1.5em;font-weight:bold;\">ATTENTION - LE MODE DEBUGGAGE EST ACTIVÉ</span> <br />Assurez-vous de désactiver le mode débuggage avant de déployer ce formulaire en production!</p>";
$_lang["ef_error_filter_rule"] = "Filtre de texte non reconnu";
$_lang["ef_error_formid"] = "ID ou nom du formulaire incorrect.";
$_lang["ef_error_list_rule"] = "Erreur dans la validation du champ du fomulaire! La règle #LIST a été déclarée mais aucune valeur de liste trouvée: ";
$_lang["ef_error_validation_rule"] = "Règle de validation non reconnue";
$_lang["ef_eval_deprecated"] = "La règle #EVAL est obsolète et ne devrait plus fonctionner dans les versions futures. Utilisez #FUNCTION à la place.";
$_lang["ef_failed_default"] = "Valeur incorrecte";
$_lang["ef_failed_ereg"] = "Valeur non validée";
$_lang["ef_failed_eval"] = "Valeur non validée";
$_lang["ef_failed_list"] = "La valeur n'est pas dans la liste des valeurs autorisées";
$_lang["ef_failed_range"] = "La valeur n'est pas dans l'intervalle autorisé";
$_lang["ef_failed_upload"] = "Type de fichier incorrect.";
$_lang["ef_failed_vericode"] = "Code de vérification invalide.";
$_lang["ef_invalid_date"] = " n'est pas une date valide";
$_lang["ef_invalid_email"] = " n'est pas une adresse email valide";
$_lang["ef_invalid_number"] = " n'est pas un nombre valide";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Le Modèle de formulaire à utiliser est identifié par une Ressource contenant un appel du Snippet! Vous ne pouvez pas avoir le formulaire et l'appel du Snippet contenus dans la même Ressource.</span> ID=";
$_lang["ef_mail_abuse_error"] = "<strong>Des entrées invalides ou dangereuses ont été détectées dans votre formulaire</strong>.";
$_lang["ef_mail_abuse_message"] = "<p>Un formulaire de votre site web a peut-être fait l'objet d'une tentative d'injection d'email. Le détail des valeurs envoyées est imprimé ci-dessous. Le texte suspecté est mis en valeur à l'aide des tags \[..]\.  </p>";
$_lang["ef_mail_abuse_subject"] = "Un abus potentiel de formulaire a été détecté au niveau du formulaire ID";
$_lang["ef_mail_error"] = "Le serveur mail est incapable d'expédier l'email";
$_lang["ef_multiple_submit"] = "<p>Ce formulaire a déjà été soumis avec succès. Il est inutile de soumettre le même formulaire à plusieurs reprises.</p>";
$_lang["ef_no_doc"] = "Ressource ou Chunk introuvable pour le Modèle ID=";
$_lang["ef_regex_error"] = "erreur dans l'expression régulière";
$_lang["ef_required_message"] = "Les champs requis ci-dessous sont introuvables: [+fields+]";
$_lang["ef_rule_failed"] = "<span style=\"color:red;\">Échec</span> en utilisant la règle [+rule+] (input=\"[+input+]\")";
$_lang["ef_rule_passed"] = "Passée en utilisant la règle [+rule+] (input=\"[+input+]\").";
$_lang["ef_sql_no_result"] = " validation silencieuse passée. <span style=\"color:red;\"> SQL n'a retourné aucun resultat!</span> ";
$_lang["ef_submit_time_limit"] = "<p>Ce formulaire a déjà été soumis avec succès. La soumission du formulaire est bloquée pour [+submitLimitMinutes+] minutes.</p>";
$_lang["ef_tamper_attempt"] = "Tentative de falsification du code du formulaire détectée!";
$_lang["ef_thankyou_message"] = "<h3>Merci!</h3><p>Vos informations ont été correctement transmises.</p>";
$_lang["ef_thousands_separator"] = "";
$_lang["ef_upload_error"] = ": erreur dans l'envoi du fichier.";
$_lang["ef_upload_exceeded"] = " a dépassé la limite de taille en envoi.";
$_lang["ef_validation_message"] = "Des erreurs ont été détectées dans le formulaire:";
$_lang["ef_version_error"] = "<strong>ATTENTION!</strong> La version du Snippet eForm (version:&nbsp;[+version+]) est différente de celle des fichiers eForm présents sur votre serveur (version:&nbsp;[+fileVersion+]). Pensez à mettre à jour les fichiers lorsque vous mettez à jour le Snippet.";
?>