<?php
/**
 * Language file for eForm
 *
 * Language:       Spanish
 * Encoding:       UTF-8
 * Translated by:  "alconpez" y "ARES1983"
 * Date:           2013/12/31
 */
$_lang["ef_date_format"] = "%d-%b-%Y %H:%M:%S";
$_lang["ef_debug_info"] = "Información de eliminación de errores (Debug): ";
$_lang["ef_debug_warning"] = "<p style=\"color:red;\"><span style=\"font-size:1.5em;font-weight:bold;\">ATENCIÓN - EL INFORMADOR DE ERRORES, ESTÁ ACTIVO</span> <br />¡Asegurese de desactivarlo antes de publicar este formulario!</p>";
$_lang["ef_error_filter_rule"] = "Text filter not recognized";
$_lang["ef_error_formid"] = "El número de Id o el nombre del Formulario es inválido.";
$_lang["ef_error_list_rule"] = "Error in validating form field! #LIST rule declared but no list values found: ";
$_lang["ef_error_validation_rule"] = "No se pudo reconocer la regla de validación";
$_lang["ef_eval_deprecated"] = "La regla #EVAL se desaprueba y puede que no funcione en futuras versiones. Utilizar #FUNCTION en lugar de otra.";
$_lang["ef_failed_default"] = "Valor incorrecto";
$_lang["ef_failed_ereg"] = "El valor no es válido";
$_lang["ef_failed_eval"] = "El valor no es válido";
$_lang["ef_failed_list"] = "El valor no está en la lista de los valores permitidos";
$_lang["ef_failed_range"] = "El valor no está dentro del rango permitido";
$_lang["ef_failed_upload"] = "El tipo de archivo es incorrecto.";
$_lang["ef_failed_vericode"] = "Código de verificación incorrecto.";
$_lang["ef_invalid_date"] = " no es una fecha válida";
$_lang["ef_invalid_email"] = " no es una dirección de e-mail válida";
$_lang["ef_invalid_number"] = " no es un numero válido";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">¡La plantilla del formulario ha sido fijada al id de la página que contiene la llamada al snippet! No puedes tener el formulario en el mismo documento que la llamada al snippet.</span> id=";
$_lang["ef_mail_abuse_error"] = "<strong>Se detectaron entradas inválidas o inseguras en su formulario</strong>.";
$_lang["ef_mail_abuse_message"] = "<p>Un formulario en su pagina web pudo haber sido el tema de una tentativa de la inyección del e-mail. Los detalles de los valores fijados se imprimen abajo. El texto sospechoso se ve entre las etiquetas\[..]\ </p>";
$_lang["ef_mail_abuse_subject"] = "Un posible abuso ha sido detectado en el formulario de e-mail, con el id";
$_lang["ef_mail_error"] = "Mailer was unable to send mail";
$_lang["ef_multiple_submit"] = "<p>This form was already submitted succesfully. There is no need to submit your information multiple times.</p>";
$_lang["ef_no_doc"] = "No se encontró el documento o el Chunk para la plantilla id=";
$_lang["ef_regex_error"] = "error en una expresión regular ";
$_lang["ef_required_message"] = "  Los siguientes campos contienen errores: {fields}<br />";
$_lang["ef_rule_failed"] = "<span style=\"color:red;\">Failed</span> using rule [+rule+] (input=\"[+input+]\")";
$_lang["ef_rule_passed"] = "Passed using rule [+rule+] (input=\"[+input+]\").";
$_lang["ef_sql_no_result"] = " la validación paso silenciosamente. <span style=\"color:red;\"> ¡El SQL no devolvió ningún resultado!</span> ";
$_lang["ef_submit_time_limit"] = "<p>This form was already submitted succesfully. Re-submission of the form is disabled for [+submitLimitMinutes+] minutes.</p>";
$_lang["ef_tamper_attempt"] = "¡Se detectó intento de falsificación!";
$_lang["ef_thankyou_message"] = "<h3>¡Gracias!</h3><p>Su información ha sido enviada con éxito.</p>";
$_lang["ef_thousands_separator"] = "";
$_lang["ef_upload_error"] = ": error in uploading file.";
$_lang["ef_upload_exceeded"] = " ha excedido el límite máximo de subida.";
$_lang["ef_validation_message"] = "<strong>Se detectaron algunos errores:</strong><br />";
$_lang["ef_version_error"] = "<strong>WARNING!</strong> The version of the eForm snippet (version:&nbsp;[+version+]) is different from the included eForm file (version:&nbsp;[+fileVersion+]). Please make sure you use the same version for both.";
?>