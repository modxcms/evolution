<?php
/*
 * Filename:       assets/plugins/tinymce/lang/german.inc.php
 * Function:       German language file for TinyMCE
 * Encoding:       UTF-8
 * Author:         Jeff Whitfield / translated by Marc Hinse
 * Date:           2013-03-10
 * Version:        3.5.8
 * MODX version:   0.9.5-1.0.9
*/
include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['mce_editor_theme_title'] = "Template:";
$_lang['mce_editor_theme_message'] = "Hier können Sie ein Template aussuchen, das im TinyMCE verwendet werden soll. Es handelt sich nicht um das Aussehen, sondern um die Anzahl an Plugins/Funktionen (wie Bilder hochladen, Links einfügen etc.), die für den Nutzer verfügbar sein sollen.";
$_lang['mce_editor_custom_plugins_title'] = "Individuelle Plugins:";
$_lang['mce_editor_custom_plugins_message'] = "Geben Sie die Plugins an, die Sie bei Auswahl des \'individuellen\' Templates verwenden wollen. Bitte Plugins durch Komma trennen.";
$_lang['mce_editor_custom_buttons_title'] = "Individuelle Buttons:";
$_lang['mce_editor_custom_buttons_message'] = "Geben Sie die Buttons an, die Sie bei Auswahl des \'individuellen\' Templates verwenden wollen. Bitte Buttons für jede Reihe durch Komma trennen.Stellen Sie sicher, dass die für Buttons benötigten Plugins aktiviert sind unter \'Individuelle Plugins\'.";
$_lang['mce_editor_css_selectors_title'] = "CSS Selektoren:";
$_lang['mce_editor_css_selectors_message'] = "Hier können Sie eine Auswahl an Selektoren definieren, die im TinyMCE verfügbar sein sollen. Bitte so eintragen:<br />'displayName=selectorName;displayName2=selectorName2'<br />Zum Beispiel mit <b>.mono</b> und <b>.smallText</b> als Selektoren in der CSS Datei, tragen Sie diese ein als:<br />'Monospaced text=mono;Small text=smallText'<br />Bitte achten Sie darauf, dass der letzte Eintrag nicht mit Semikolon abgeschlossen werden darf.";
$_lang['mce_settings'] = "TinyMCE Einstellungen";
$_lang['mce_theme_simple'] = "Wenige Plugins";
$_lang['mce_theme_advanced'] = "Mittlere Anzahl Plugins";
$_lang['mce_theme_editor'] = "Alle Plugins";
$_lang['mce_theme_custom'] = "Individuell";
$_lang['mce_theme_creative'] = 'Creative';
$_lang['mce_theme_logic'] = 'xhtml';
$_lang['mce_theme_legacy'] = 'legacy style';
$_lang['mce_theme_global_settings'] = "Verwenden Sie die globale Einstellung";
$_lang['mce_editor_skin_title'] = 'Skin';
$_lang['mce_editor_skin_message'] = 'Design of toolbar. see tinymce/tiny_mce/themes/advanced/skins/<br />';
$_lang['mce_editor_entermode_title'] = 'Enter key mode';
$_lang['mce_editor_entermode_message'] = 'Operation when the enter key is pressed is set up.';
$_lang['mce_entermode_opt1'] = 'Wrap &lt;p&gt;&lt;/p&gt;';
$_lang['mce_entermode_opt2'] = 'Insert &lt;br /&gt;';

$_lang['mce_element_format_title'] = 'Element format';
$_lang['mce_element_format_message'] = 'This option enables control if elements should be in html or xhtml mode. xhtml is the default state for this option. This means that for example &lt;br /&gt; will be &lt;br&gt; if you set this option to &quot;html&quot;.';
$_lang['mce_schema_title'] = 'Schema';
$_lang['mce_schema_message'] = 'The schema option enables you to switch between the HTML4 and HTML5 schema. This controls the valid elements and attributes that can be placed in the HTML. This value can either be the default html4 or html5.';

$_lang['mce_toolbar1_msg'] = 'Default : undo,redo,|,bold,forecolor,backcolor,strikethrough,formatselect,fontsizeselect, pastetext,pasteword,code,|,fullscreen,help';
$_lang['mce_toolbar2_msg'] = 'Default : image,media,link,unlink,anchor,|,justifyleft,justifycenter,justifyright,|,bullist, numlist,|,blockquote,outdent,indent,|,table,hr,|,template,visualblocks,styleprops,removeformat';

$_lang['mce_tpl_title'] = 'Template button';
$_lang['mce_tpl_msg'] = 'You can insert the HTML block which you registered beforehand from toolbar. You make HTML block as resource or a chunk, and can appoint plural number with a comma.';
$_lang['mce_tpl_docid'] = 'Resource IDs';
$_lang['mce_tpl_chunkname'] = 'Chunk names';