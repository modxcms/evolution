<?php
/**
* snippets/eform/english.inc.php
* English language file for eForm
*/


$_lang["ef_thankyou_message"] = "<h3>Thank You!</h3><p>Your information was successfully submitted.</p>";
$_lang["ef_no_doc"] = "Document or chunk not found for template id=";
$_lang["ef_validation_message"] = "<div class=\"errors\"><strong>Some errors were detected in your form:</strong><br />[+ef_wrapper+]</div>";
$_lang["ef_required_message"] = " The following required field(s) are missing: {fields}<br />";
$_lang["ef_invalid_number"] = " is not a valid number";
$_lang["ef_invalid_date"] = " is not a valid date";
$_lang["ef_invalid_email"] = " is not a valid email address";
$_lang["ef_upload_exceeded"] = " has exceeded maximum upload limit.";
$_lang["ef_failed_default"] = "Incorrect value";
$_lang["ef_failed_vericode"] = "Invalid verification code.";
$_lang["ef_failed_range"] = "Value not in permitted range";
$_lang["ef_failed_list"] = "Value not in list of permitted values";
$_lang["ef_failed_eval"] = "Value did not validate";
$_lang["ef_failed_ereg"] = "Value did not validate";
$_lang["ef_failed_upload"] = "Incorrect file type.";
$_lang["ef_error_validation_rule"] = "Validation rule not recognized";
$_lang["ef_tamper_attempt"] = "Tampering attempt detected!";
$_lang["ef_error_formid"] = "Invalid Form Id number or name.";
$_lang["ef_debug_info"] = "Debug info: ";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Form template set to id of page containing snippet call! You can not have the form in the same document as the snippet call.</span> id=";
$_lang["ef_sql_no_result"] = " silently passed validation. <span style=\"color:red;\"> SQL returned no result!</span> ";
$_lang['ef_regex_error'] = 'error in regular expression ';
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">WARNING - DEBUGGING IS ON</span> <br />Make sure you turn debugging off before making this form live!</p>';
$_lang['ef_mail_abuse_subject'] = 'Potential email form abuse detected for form id';
$_lang['ef_mail_abuse_message'] = '<p>A form on your website may have been the subject of an email injection attempt. The details of the posted values are printed below. Suspected text has been embedded in \[..]\ tags.  </p>';
$_lang['ef_mail_abuse_error'] = '<strong>Invalid or insecure entries were detected in your form</strong>.';
$_lang['ef_eval_deprecated'] = "The #EVAL rule is deprecated and may not work in future versions. Use #FUNCTION instead.";
?>