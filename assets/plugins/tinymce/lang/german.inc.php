<?php
/*
 * Filename:       assets/plugins/tinymce/lang/german.inc.php
 * Function:       German language file for TinyMCE
 * Encoding:       UTF-8
 * Author:         Jeff Whitfield / translated by Marc Hinse
 * Date:           2007/04/17
 * Version:        2.0.9
 * MODx version:   0.9.6
*/

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['tinymce_editor_theme_title'] = "Template:";
$_lang['tinymce_editor_theme_message'] = "Hier können Sie ein Template aussuchen, das im TinyMCE verwendet werden soll. Es handelt sich nicht um das Aussehen, sondern um die Anzahl an Plugins/Funktionen (wie Bilder hochladen, Links einfügen etc.), die für den Nutzer verfügbar sein sollen.";
$_lang['tinymce_editor_custom_plugins_title'] = "Individuelle Plugins:";
$_lang['tinymce_editor_custom_plugins_message'] = "Geben Sie die Plugins an, die Sie bei Auswahl des \'individuellen\' Templates verwenden wollen. Bitte Plugins durch Komma trennen.";
$_lang['tinymce_editor_custom_buttons_title'] = "Individuelle Buttons:";
$_lang['tinymce_editor_custom_buttons_message'] = "Geben Sie die Buttons an, die Sie bei Auswahl des \'individuellen\' Templates verwenden wollen. Bitte Buttons für jede Reihe durch Komma trennen.Stellen Sie sicher, dass die für Buttons benötigten Plugins aktiviert sind unter \'Individuelle Plugins\'.";
$_lang['tinymce_editor_css_selectors_title'] = "CSS Selektoren:";
$_lang['tinymce_editor_css_selectors_message'] = "Hier können Sie eine Auswahl an Selektoren definieren, die im TinyMCE verfügbar sein sollen. Bitte so eintragen:<br />'displayName=selectorName;displayName2=selectorName2'<br />Zum Beispiel mit <b>.mono</b> und <b>.smallText</b> als Selektoren in der CSS Datei, tragen Sie diese ein als:<br />'Monospaced text=mono;Small text=smallText'<br />Bitte achten Sie darauf, dass der letzte Eintrag nicht mit Semikolon abgeschlossen werden darf.";
$_lang['tinymce_settings'] = "TinyMCE Einstellungen";
$_lang['tinymce_theme_simple'] = "Wenige Plugins";
$_lang['tinymce_theme_advanced'] = "Mittlere Anzahl Plugins";
$_lang['tinymce_theme_editor'] = "Alle Plugins";
$_lang['tinymce_theme_custom'] = "Individuell";
$_lang['tinymce_theme_creative'] = 'Creative';
$_lang['tinymce_theme_logic'] = 'xhtml';
$_lang['tinymce_theme_global_settings'] = "Verwenden Sie die globale Einstellung";
?>