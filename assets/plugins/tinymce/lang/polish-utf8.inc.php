<?php
/**
 * Filename:       assets/plugins/tinymce/lang/english.inc.php
 * Function:       Polish language file for TinyMCE
 * Encoding:       UTF-8
 * Author:         Jeff Whitfield and Piotr Matysiak
 * Date:           2014/02/01
 * Version:        3.5.10
 * MODX version:   0.9.5-1.0.15
*/

$_lang['mce_editor_theme_title'] = 'Motyw';
$_lang['mce_editor_theme_message'] = 'Tu możesz wybrać, którego motywu lub skórki ma używać edytor.';
$_lang['mce_editor_custom_plugins_title'] = 'Niestandardowe wtyczki';
$_lang['mce_editor_custom_plugins_message'] = 'Wprowadź nazwy wtyczek w postaci listy oddzielonej przecinkami, których będzie używać temat \'custom\'.<br />Domyślnie: template,visualblocks,autolink,inlinepopups,autosave,save,advlist,style,fullscreen, advimage,paste,advlink,media,contextmenu,table';
$_lang['mce_editor_custom_buttons_title'] = 'Niestandardowe przyciski';
$_lang['mce_editor_custom_buttons_message'] = 'Wprowadź nazwy przycisków w postaci listy oddzielonej przecinkami, których będzie używać temat \'custom\'. Upewnij się, że każdy z nich posiada wtyczkę w ustawieniu \'Własne wtyczki\'.';
$_lang['mce_editor_css_selectors_title'] = 'Selektory CSS';
$_lang['mce_editor_css_selectors_message'] = 'Tutaj możesz wprowadzić listę selektorów, które będą dostępne w edytorze. Wprowadź w ten sposób:<br />\'nazwaWidoczna=nazwaSelektora;nazwaWidoczna2=nazwaSelektora2\'<br />Na przykład, powiedzmy że w pliku CSS posiadasz selektory <b>.mono</b> oraz <b>.smallText</b>. Możesz dodać je tak:<br />\'Monospaced text=mono;Small text=smallText\'<br />Zwróć uwagę, że ostatni element nie posiada średnika.';
$_lang['mce_settings'] = 'Ustawienia TinyMCE';
$_lang['mce_theme_simple'] = 'Prosty';
$_lang['mce_theme_full'] = 'Pełny';
$_lang['mce_theme_advanced'] = 'Zaawansowany';
$_lang['mce_theme_editor'] = 'Styl MODX';
$_lang['mce_theme_custom'] = 'Niestandardowy';
$_lang['mce_theme_creative'] = 'Twórczy';
$_lang['mce_theme_logic'] = 'xhtml';
$_lang['mce_theme_legacy'] = 'Klasyczny styl';
$_lang['mce_theme_global_settings'] = 'Użyj ustawienia globalnego';
$_lang['mce_editor_skin_title'] = 'Skórka';
$_lang['mce_editor_skin_message'] = 'Wygląd paska narzędzi. Zobacz tinymce/tiny_mce/themes/advanced/skins/<br />';
$_lang['mce_editor_entermode_title'] = 'Tryb klawisza ENTER';
$_lang['mce_editor_entermode_message'] = 'Operacja po wciśnięciu klawisza ENTER.';
$_lang['mce_entermode_opt1'] = 'Owiń w &lt;p&gt;&lt;/p&gt;';
$_lang['mce_entermode_opt2'] = 'Wstaw &lt;br /&gt;';

$_lang['mce_element_format_title'] = 'Format elementu';
$_lang['mce_element_format_message'] = 'This option enables control if elements should be in html or xhtml mode. xhtml is the default state for this option. This means that for example &lt;br /&gt; will be &lt;br&gt; if you set this option to &quot;html&quot;.';
$_lang['mce_schema_title'] = 'Schemat';
$_lang['mce_schema_message'] = 'The schema option enables you to switch between the HTML4 and HTML5 schema. This controls the valid elements and attributes that can be placed in the HTML. This value can either be the default html4 or html5.';

$_lang['mce_toolbar1_msg'] = 'Domyślne: undo,redo,|,bold,forecolor,backcolor,strikethrough,formatselect,fontsizeselect, pastetext,pasteword,code,|,fullscreen,help';
$_lang['mce_toolbar2_msg'] = 'Domyślne: image,media,link,unlink,anchor,|,justifyleft,justifycenter,justifyright,|,bullist, numlist,|,blockquote,outdent,indent,|,table,hr,|,template,visualblocks,styleprops,removeformat';

$_lang['mce_tpl_title'] = 'Przycisk szablonu';
$_lang['mce_tpl_msg'] = 'You could define templates on chunk or ressource base for the template button in TinyMCE (won\'t be displayed by default). The content of the chunk/of the resource will be inserted at the cursor position as html code in TinyMCE. Multiple chunk names or ressource IDs have to be separated by comma.';
$_lang['mce_tpl_docid'] = 'ID zasobów';
$_lang['mce_tpl_chunkname'] = 'Nazwy chunków';