<?php
/*
 * Filename:       assets/plugins/tinymce/lang/svenska.inc.php
 * Function:       Swedish language file for TinyMCE.
 * Encoding:       ISO-Latin-1
 * Author:         Jeff Whitfield
 * Date:           2007/01/06
 * Version:        2.1.0
 * MODx version:   0.9.6
 *
 * Translation:    Pontus Ågren (Pont)
 * Date:           2007-03-13
 */

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['tinymce_editor_theme_title'] = "Tema";
$_lang['tinymce_editor_theme_message'] = "Här kan du välja vilket tema eller skal som editorn ska använda.";
$_lang['tinymce_editor_custom_plugins_title'] = "Anpassade plugins";
$_lang['tinymce_editor_custom_plugins_message'] = "Ange de plugins som ska användas i det \"anpassade\" temat som en komma-avgränsad lista.";
$_lang['tinymce_editor_custom_buttons_title'] = "Anpassade knappar";
$_lang['tinymce_editor_custom_buttons_message'] = "Ange de knappar som ska användas i det \"anpassade\" temat som en komma-avgränsad lista för varje rad. Kontrollera att varje knapp har den tillhörande pluginnen vald i inställningarna för \"Anpassade plugins\".";
$_lang['tinymce_editor_css_selectors_title'] = "CSS-selektorer";
$_lang['tinymce_editor_css_selectors_message'] = "Här kan du ange en lista med selektorer som ska finnas tillgängliga i editorn. Skriv in dom så här:<br />\"namnEtikett=selektorNamn;namnEtikett2=selektorNamn2\"<br />Om du till exempel har selektorerna <b>.mono</b> och <b>.litenText</b> i din css-fil, så kan du ange dom här som:<br />\"Jämnbred text=mono;Liten text=litenText\"<br />Notera att den sista definitionen inte ska följas av ett semikolon.";
$_lang['tinymce_settings'] = "Inställningar för TinyMCE";
$_lang['tinymce_theme_simple'] = "Enkelt";
$_lang['tinymce_theme_advanced'] = "Avancerat";
$_lang['tinymce_theme_editor'] = "Innehållseditor";
$_lang['tinymce_theme_custom'] = "Anpassat";
$_lang['tinymce_theme_creative'] = 'Creative';
$_lang['tinymce_theme_logic'] = 'xhtml';
$_lang['tinymce_theme_global_settings'] = "Använd globalt";
?>