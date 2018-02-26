<?php
/**
 * Function:       Polish language file for gsettings
 * Encoding:       UTF-8
 * Author:         Jeff Whitfield and Piotr Matysiak
 * Date:           2016/02/19
 * Version:        4.5.7.0
 * MODX version:   0.9.5-1.1
*/

$_lang['lang_code'] = 'pl';
$_lang['editor_theme_title'] = 'Motyw';
$_lang['editor_theme_message'] = 'Tu możesz wybrać, którego motywu lub skórki ma używać edytor.';
$_lang['editor_custom_plugins_title'] = 'Niestandardowe wtyczki';
$_lang['editor_custom_plugins_message'] = 'Wprowadź nazwy wtyczek w postaci listy oddzielonej przecinkami, których będzie używać temat \'custom\'.';
$_lang['editor_custom_buttons_title'] = 'Niestandardowe przyciski';
$_lang['editor_custom_buttons_message'] = 'Wprowadź nazwy przycisków w postaci listy oddzielonej przecinkami, których będzie używać temat \'custom\'. Upewnij się, że każdy z nich posiada wtyczkę w ustawieniu \'Własne wtyczki\'.';
$_lang['editor_css_selectors_title'] = 'Selektory CSS';
$_lang['editor_css_selectors_message'] = 'Tutaj możesz wprowadzić listę selektorów, które będą dostępne w edytorze. Wprowadź w ten sposób:<br />[+editor_css_selectors_schema+]<br />Na przykład, powiedzmy że w pliku CSS posiadasz selektory <b>.mono</b> oraz <b>.smallText</b>. Możesz dodać je tak:<br />[+editor_css_selectors_example+]<br />Zwróć uwagę, że ostatni element nie posiada średnika.'; // add &quot;[+editor_css_selectors_separator+]&quot;
$_lang['settings'] = 'Ustawienia';
$_lang['theme_simple'] = 'Prosty';
$_lang['theme_full'] = 'Pełny';
$_lang['theme_advanced'] = 'Zaawansowany';
$_lang['theme_editor'] = 'Styl MODX';
$_lang['theme_custom'] = 'Niestandardowy';
$_lang['theme_creative'] = 'Twórczy';
$_lang['theme_logic'] = 'xhtml';
$_lang['theme_legacy'] = 'Klasyczny styl';
$_lang['theme_global_settings'] = 'Użyj ustawienia globalnego';
$_lang['editor_skin_title'] = 'Skórka';
$_lang['editor_skin_message'] = 'Wygląd paska narzędzi. Zobacz ';
$_lang['editor_entermode_title'] = 'Tryb klawisza ENTER';
$_lang['editor_entermode_message'] = 'Operacja po wciśnięciu klawisza ENTER.';
$_lang['entermode_opt1'] = 'Owiń w &lt;p&gt;&lt;/p&gt;';
$_lang['entermode_opt2'] = 'Wstaw &lt;br /&gt;';

$_lang['element_format_title'] = 'Format elementu';
$_lang['element_format_message'] = 'This option enables control if elements should be in html or xhtml mode. xhtml is the default state for this option. This means that for example &lt;br /&gt; will be &lt;br&gt; if you set this option to &quot;html&quot;.';
$_lang['schema_title'] = 'Schemat';
$_lang['schema_message'] = 'The schema option enables you to switch between the HTML4 and HTML5 schema. This controls the valid elements and attributes that can be placed in the HTML. This value can either be the default html4 or html5.';

$_lang['tpl_title'] = 'Przycisk szablonu';
$_lang['tpl_msg'] = 'You could define templates on chunk or ressource base for the template button in [+editorLabel+] (won\'t be displayed by default). The content of the chunk/of the resource will be inserted at the cursor position as html code in [+editorLabel+]. Multiple chunk names or ressource IDs have to be separated by comma.';
$_lang['tpl_docid'] = 'ID zasobów';
$_lang['tpl_chunkname'] = 'Nazwy chunków';

$_lang['default'] = 'Default:&nbsp;';