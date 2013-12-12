<?php
/**
 * Language file for eForm
 *
 * Language:       English
 * Encoding:       Latin 1
 * Translated by:  -
 * Date:           26.10.2009
 */

$_lang["ef_thankyou_message"] = '<h3>Merci!</h3><p>Vos informations ont �t� correctement transmises.</p>';
$_lang["ef_no_doc"] = 'Ressource ou Chunk introuvable pour le Mod�le ID=';
$_lang["ef_validation_message"] = '<div class="errors"><strong>Des erreurs ont �t� d�tect�es dans le formulaire:</strong><br />[+ef_wrapper+]</div>';
$_lang['ef_rule_passed'] = 'Pass�e en utilisant la r�gle [+rule+] (input="[+input+]").';
$_lang['ef_rule_failed'] = '<span style="color:red;">�chec</span> en utilisant la r�gle [+rule+] (input="[+input+]")';
$_lang["ef_required_message"] = ' Les champs requis ci-dessous sont introuvables: {fields}<br />';
$_lang['ef_error_list_rule'] = 'Erreur dans la validation du champ du fomulaire! La r�gle #LIST a �t� d�clar�e mais aucune valeur de liste trouv�e: ';
$_lang["ef_invalid_number"] = ' n\'est pas un nombre valide';
$_lang["ef_invalid_date"] = ' n\'est pas une date valide';
$_lang["ef_invalid_email"] = ' n\'est pas une adresse email valide';
$_lang["ef_upload_exceeded"] = ' a d�pass� la limite de taille en envoi.';
$_lang["ef_upload_error"] = ': erreur dans l\'envoi du fichier.'; //NEW
$_lang["ef_failed_default"] = 'Valeur incorrecte';
$_lang["ef_failed_vericode"] = 'Code de v�rification invalide.';
$_lang["ef_failed_range"] = 'La valeur n\'est pas dans l\'intervalle autoris�';
$_lang["ef_failed_list"] = 'La valeur n\'est pas dans la liste des valeurs autoris�es';
$_lang["ef_failed_eval"] = 'Valeur non valid�e';
$_lang["ef_failed_ereg"] = 'Valeur non valid�e';
$_lang["ef_failed_upload"] = 'Type de fichier incorrect.';
$_lang["ef_error_validation_rule"] = 'R�gle de validation non reconnue';
$_lang["ef_error_filter_rule"] = 'Filtre de texte non reconnu';
$_lang["ef_tamper_attempt"] = 'Tentative de falsification du code du formulaire d�tect�e!';
$_lang["ef_error_formid"] = 'ID ou nom du formulaire incorrect.';
$_lang["ef_debug_info"] = 'Information de d�buggage: ';
$_lang["ef_is_own_id"] = '<span class=\"ef-form-error\">Le Mod�le de formulaire � utiliser est identifi� par une Ressource contenant un appel du Snippet! Vous ne pouvez pas avoir le formulaire et l\'appel du Snippet contenus dans la m�me Ressource.</span> ID=';
$_lang["ef_sql_no_result"] = ' validation silencieuse pass�e. <span style=\"color:red;\"> SQL n\'a retourn� aucun resultat!</span> ';
$_lang['ef_regex_error'] = 'erreur dans l\'expression r�guli�re';
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">ATTENTION - LE MODE DEBUGGAGE EST ACTIV�</span> <br />Assurez-vous de d�sactiver le mode d�buggage avant de d�ployer ce formulaire en production!</p>';
$_lang['ef_mail_abuse_subject'] = 'Un abus potentiel de formulaire a �t� d�tect� au niveau du formulaire ID';
$_lang['ef_mail_abuse_message'] = '<p>Un formulaire de votre site web a peut-�tre fait l\'objet d\'une tentative d\'injection d\'email. Le d�tail des valeurs envoy�es est imprim� ci-dessous. Le texte suspect� est mis en valeur � l\'aide des tags \[..]\.  </p>';
$_lang['ef_mail_abuse_error'] = '<strong>Des entr�es invalides ou dangereuses ont �t� d�tect�es dans votre formulaire</strong>.';
$_lang['ef_eval_deprecated'] = 'La r�gle #EVAL est obsol�te et ne devrait plus fonctionner dans les versions futures. Utilisez #FUNCTION � la place.';
$_lang['ef_multiple_submit'] = '<p>Ce formulaire a d�j� �t� soumis avec succ�s. Il est inutile de soumettre le m�me formulaire � plusieurs reprises.</p>';
$_lang['ef_submit_time_limit'] = '<p>Ce formulaire a d�j� �t� soumis avec succ�s. La soumission du formulaire est bloqu�e pour ".($submitLimit/60)." minutes.</p>';
$_lang['ef_version_error'] = '<strong>ATTENTION!</strong> La version du Snippet eForm (version:&nbsp;$version) est diff�rente de celle des fichiers eForm pr�sents sur votre serveur (version:&nbsp;$fileVersion). Pensez � mettre � jour les fichiers lorsque vous mettez � jour le Snippet.';
$_lang['ef_thousands_separator'] = ''; //leave empty to use (php) locale, only needed if you want to overide locale setting!
$_lang['ef_date_format'] = '%d-%b-%Y %H:%M:%S';
$_lang['ef_mail_error'] = 'Le serveur mail est incapable d\'exp�dier l\'email';
?>