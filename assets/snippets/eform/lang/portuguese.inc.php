<?php
/**
* snippets/eform/portuguese.inc.php
* Portuguese language file for eForm
*/



$_lang["ef_thankyou_message"] = "<h3>Obrigado!</h3><p>As suas informa&ccedil;&otilde;es foram submetidas com sucesso.</p>";
$_lang["ef_no_doc"] = "Documento ou Chunk n&atilde;o encontrado para o template com id=";
$_lang["ef_validation_message"] = "<div class=\"errors\"><strong class=\"invalid\">Foram detectados alguns erros no formul&aacute;rio:</strong><br />[+ef_wrapper+]</div>";
$_lang["ef_required_message"] = " <b>Os seguintes campos est&atilde;o em falta:</b><br />{fields}<br />";
$_lang["ef_invalid_number"] = " n&atilde;o &eacute; um n&uacute;mero v&aacute;lido.";
$_lang["ef_invalid_date"] = " n&atilde;o &eacute; uma data v&aacute;lida.";
$_lang["ef_invalid_email"] = " n&atilde;o &eacute; um endere&ccedil;o de e-mail v&aacute;lido.";
$_lang["ef_upload_exceeded"] = " excedeu o limite m&aacute;ximo de envio (upload).";
$_lang["ef_failed_default"] = "Valor incorrecto.";
$_lang["ef_failed_vericode"] = "C&oacute;digo de verifica&ccedil;&atilde;o inv&aacute;lido.";
$_lang["ef_failed_range"] = "Valor n&atilde;o se encontra dentro da gama permitida.";
$_lang["ef_failed_list"] = "Valor n&atilde;o se encontra na lista de valores permitidos.";
$_lang["ef_failed_eval"] = "Valor n&atilde;o foi validado.";
$_lang["ef_failed_ereg"] = "Valor n&atilde;o foi validado.";
$_lang["ef_failed_upload"] = "Tipo de ficheiro incorrecto.";
$_lang["ef_error_validation_rule"] = "Regra de valida&ccedil;&atilde;o n&atilde;o reconhecida.";
$_lang["ef_tamper_attempt"] = "Tentativa de falsifica&ccedil;&atilde;o detectada!";
$_lang["ef_error_formid"] = "ID ou nome de formul&aacute;rio inv&aacute;lido.";
$_lang["ef_debug_info"] = "Informa&ccedil;&atilde;o de Depura&ccedil;&atilde;o: ";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">O template do formul&aacute;rio foi atribu&iacute;do &agrave; ID da p&aacute;gina que cont&eacute;m a chamada do Snippet eForm! N&atilde;o pode ter o formul&aacute;rio no mesmo documento em que faz a chamada ao Snippet.</span> id=";
$_lang["ef_sql_no_result"] = " passou a valida&ccedil;&atilde;o silenciosamente. <span style=\"color:red;\"> O SQL n&atilde;o devolveu nenhum resultado!</span> ";
$_lang['ef_regex_error'] = 'erro na "regular expression" ';
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">ATEN&Ccedil;&Atilde;O - DEPURA&Ccedil;&Atilde;O EST&Aacute; LIGADA</span> <br />Certifique-se de que desliga a depura&ccedil;&atilde;o antes de colocar este formul&aacute;rio online!</p>';
$_lang['ef_mail_abuse_subject'] = 'Potencial abuso de formul&aacute;rio de e-mail detectado para a id';
$_lang['ef_mail_abuse_message'] = '<p>Um formul&aacute;rio no seu site poder&aacute; ter sido objecto de uma tentativa de injec&ccedil;&atilde;o por e-mail. Os detalhes dos valores enviados s&atilde;o mostrados em seguida. Texto suspeito foi marcado com etiquetas \[..\].  </p>';
$_lang['ef_mail_abuse_error'] = '<strong>Entradas inv&aacute;lidas ou n&atilde;o seguras foram detectadas no formul&aacute;rio</strong>.';
$_lang['ef_eval_deprecated'] = "A regra #EVAL foi depreciada e poder&aacute; n&atilde;o fncionar em futuras vers&otilde;es. Em alternativa use #FUNCTION.";
$_lang['ef_multiple_submit'] = "<p class=\"invalid\">Este formulário já foi submetido com sucesso. Não h&aacute; necessidade de submeter o formul&aacute;rio m&uacute;ltiplas vezes.</p>";
$_lang['ef_submit_time_limit'] = "<p>Este formul&aacute; j&aacute; foi submetido. O reenvio do formul&aacute;rio est&aacute; desactivado por ".($submitLimit/60)." minutos.</p>";
$_lang['ef_version_error'] = "<strong>ATEN&Ccedil;&Atilde;O!</strong> A vers&atilde;o do snippet eForm (vers&atilde;o:&nbsp;$version) &eacute; diferente da inclu&iacute;da no ficheiro eForm (vers&atilde;o:&nbsp;$fileVersion). Por favor certifique-se de que usa a mesma vers&atilde;o em ambos.";
?>
