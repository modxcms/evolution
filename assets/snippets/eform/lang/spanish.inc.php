<?php
/**
* No borrar estas lineas por favor
* snippets/eform/spanish.inc.php
* Archivo de eForm en espa�ol
* Traducido por "alconpez" y "ARES1983" de forma separada y adjuntada.
*/


$_lang["ef_thankyou_message"] = "<h3>�Gracias!</h3><p>Su informaci�n ha sido enviada con �xito.</p>";
$_lang["ef_no_doc"] = "No se encontr� el documento o el Chunk para la plantilla id=";
//$_lang["ef_no_chunk"] = ""; //desaprobado
$_lang["ef_validation_message"] = "<strong>Se detectaron algunos errores:</strong><br />";
$_lang["ef_required_message"] = "  Los siguientes campos contienen errores: {fields}<br />";
$_lang["ef_invalid_number"] = " no es un numero v�lido";
$_lang["ef_invalid_date"] = " no es una fecha v�lida";
$_lang["ef_invalid_email"] = " no es una direcci�n de e-mail v�lida";
$_lang["ef_upload_exceeded"] = " ha excedido el l�mite m�ximo de subida.";
$_lang["ef_failed_default"] = "Valor incorrecto";
$_lang["ef_failed_vericode"] = "C�digo de verificaci�n incorrecto.";
$_lang["ef_failed_range"] = "El valor no est� dentro del rango permitido";
$_lang["ef_failed_list"] = "El valor no est� en la lista de los valores permitidos";
$_lang["ef_failed_eval"] = "El valor no es v�lido";
$_lang["ef_failed_ereg"] = "El valor no es v�lido";
$_lang["ef_failed_upload"] = "El tipo de archivo es incorrecto.";
$_lang["ef_error_validation_rule"] = "No se pudo reconocer la regla de validaci�n";
$_lang["ef_tamper_attempt"] = "�Se detect� intento de falsificaci�n!";
$_lang["ef_error_formid"] = "El n�mero de Id o el nombre del Formulario es inv�lido.";
$_lang["ef_debug_info"] = "Informaci�n de eliminaci�n de errores (Debug): ";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">�La plantilla del formulario ha sido fijada al id de la p�gina que contiene la llamada al snippet! No puedes tener el formulario en el mismo documento que la llamada al snippet.</span> id=";
$_lang["ef_sql_no_result"] = " la validaci�n paso silenciosamente. <span style=\"color:red;\"> �El SQL no devolvi� ning�n resultado!</span> ";
$_lang['ef_regex_error'] = 'error en una expresi�n regular ';
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">ATENCI�N - EL INFORMADOR DE ERRORES, EST� ACTIVO</span> <br />�Asegurese de desactivarlo antes de publicar este formulario!</p>';
$_lang['ef_mail_abuse_subject'] = 'Un posible abuso ha sido detectado en el formulario de e-mail, con el id';
/** En Discusion traducido por ARES1983.
$_lang['ef_mail_abuse_message'] = '<p>Un formulario en su pagina web pudo haber sido el tema de una tentativa de la inyecci�n del e-mail. Los detalles de los valores fijados se imprimen abajo. El texto sospechoso se ve entre las etiquetas\[..]\ </p>';
*/
/** En Discusion traducido por alconpez.
$_lang['ef_mail_abuse_message'] = '<p>Un formulario de su website puede haber sido objeto de un intento de "mail injection". Los detalles de los valores escritos se encuentran debajo. El texto sospechoso est� entre los tags \[..]\  </p>';
*/
/** Original Ingles Inicio
*/
$_lang['ef_mail_abuse_message'] = '<p>A form on your website may have been the subject of an email injection attempt. The details of the posted values are printed below. Suspected text has been embedded in \[..]\ tags.  </p>';
/** Original Ingles Fin
*/
$_lang['ef_mail_abuse_error'] = '<strong>Se detectaron entradas inv�lidas o inseguras en su formulario</strong>.';
$_lang['ef_eval_deprecated'] = "La regla #EVAL se desaprueba y puede que no funcione en futuras versiones. Utilizar #FUNCTION en lugar de otra.";
?>
