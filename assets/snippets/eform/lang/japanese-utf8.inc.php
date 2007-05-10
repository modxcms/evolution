<?php
/**
* snippets/eform/japanese-utf8.inc.php
* 日本語 language file for eForm
*/

//-- JAPANESE LANGUAGE FILE ENCODED IN UTF-8
include_once(dirname(__FILE__).'/english.inc.php'); // fall back to English defaults if needed
/* Set locale to Japanese */
setlocale (LC_ALL, 'ja_JP');

$_lang["ef_thankyou_message"] = "<h3>ありがとうございます。</h3><p>入力された情報は無事送信されました。</p>";
$_lang["ef_no_doc"] = "テンプレートのドキュメントまたはチャンクが見つかりません。 id=";
$_lang["ef_validation_message"] = "<div class=\"errors\"><strong>いくつかのエラーが見つかりました</strong><br />[+ef_wrapper+]</div>";
$_lang["ef_required_message"] = "{fields}は、必須項目です<br />";
$_lang["ef_invalid_number"] = "は、有効な数字ではありません";
$_lang["ef_invalid_date"] = "は、有効な日付形式ではありません";
$_lang["ef_invalid_email"] = "は、有効なメールアドレス形式ではありません";
$_lang["ef_upload_exceeded"] = "は、アップロードの上限を超えています.";
$_lang["ef_failed_default"] = "無効な値です";
$_lang["ef_failed_vericode"] = "有効なコードではありません";
$_lang["ef_failed_range"] = "有効範囲外です";
$_lang["ef_failed_list"] = "有効なリスト項目ではありません";
$_lang["ef_failed_eval"] = "有効な値ではありません";
$_lang["ef_failed_ereg"] = "有効な値ではありません";
$_lang["ef_failed_upload"] = "有効なファイルタイプではありません";
$_lang["ef_error_validation_rule"] = "ルールが正しくありません";
$_lang["ef_tamper_attempt"] = "不正な変更の試みを発見しました!";
$_lang["ef_error_formid"] = "フォームIDまたはフォーム名が無効です";
$_lang["ef_debug_info"] = "Debug info: ";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Form template set to id of page containing snippet call! You can not have the form in the same document as the snippet call.</span> id=";
$_lang["ef_sql_no_result"] = " silently passed validation. <span style=\"color:red;\"> SQL returned no result!</span> ";
$_lang['ef_regex_error'] = 'error in regular expression ';
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">WARNING - DEBUGGING IS ON</span> <br />Make sure you turn debugging off before making this form live!</p>';
$_lang['ef_mail_abuse_subject'] = 'Potential email form abuse detected for form id';
$_lang['ef_mail_abuse_message'] = '<p>A form on your website may have been the subject of an email injection attempt. The details of the posted values are printed below. Suspected text has been embedded in \[..]\ tags.  </p>';
$_lang['ef_mail_abuse_error'] = '<strong>Invalid or insecure entries were detected in your form</strong>.';
$_lang['ef_eval_deprecated'] = 'The #EVAL rule is deprecated and may not work in future versions. Use #FUNCTION instead.';
?>
