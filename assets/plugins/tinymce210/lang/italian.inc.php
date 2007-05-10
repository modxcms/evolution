<?php
/*
 * Filename:       assets/plugins/tinymce/lang/italian.inc.php
 * Function:       Italian language file for TinyMCE.  Needs to be translated and re-encoded!
 * Encoding:       ISO-Latin-1
 * Author:         Jeff Whitfield
 * Date:           2007/01/06
 * Version:        2.0.9
 * MODx version:   0.9.5
*/

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['tinymce_editor_theme_title'] = "Tema:";
$_lang['tinymce_editor_theme_message'] = "Qui puoi impostare il tema o la skin da usare con l'editor.";
$_lang['tinymce_editor_custom_plugins_title'] = "Plugins personalizzati:";
$_lang['tinymce_editor_custom_plugins_message'] = "Inserisci la lista dei plugins da usare nel tema 'personalizzato' separandoli con una virgola.";
$_lang['tinymce_editor_custom_buttons_title'] = "Bottoni personalizzati:";
$_lang['tinymce_editor_custom_buttons_message'] = "Inserisci in queste righe la lista dei bottoni da usare nel tema 'personalizzato' separandoli con una virgola. Assicurati di aver abilitato il corrispondente plugin nell'opzione 'Plugins personalizzati' .";
$_lang['tinymce_editor_css_selectors_title'] = "Selettori CSS:";
$_lang['tinymce_editor_css_selectors_message'] = "Qui puoi inserire la lista dei selettori che saranno diponibili nell'editor. Insericili nel seguente modo:<br />'displayName=selectorName;displayName2=selectorName2'<br />
Per esempio, supponiamo tu abbia i selettori <b>.mono</b> e <b>.smallText</b> snel tuo file CSS, potresti inserirli come:<br />'Monospaced text=mono;Small text=smallText'<br />NOTA: L'ultimo inserimento non deve essere concluso con il punto e virgola.";
$_lang['tinymce_settings'] = "Impostazioni TinyMCE";
$_lang['tinymce_theme_simple'] = "Minima";
$_lang['tinymce_theme_advanced'] = "Avanzata";
$_lang['tinymce_theme_editor'] = "Editor contenuti";
$_lang['tinymce_theme_custom'] = "Personalizzata";
?>
