<?php

/*
* Filename:     assets/plugins/tinymce/lang/english.inc.php
* Function:     Dutch language file for TinyMCE
* Encoding:     utf-8
* Author:       Jeff Whitfield
*               Stefan van Zanden (18-07-2009 Small changes to conform language used in the manager)
 * Date:           2013-03-10
 * Version:        3.5.8
 * MODX version:   0.9.5-1.0.9
*/

$_lang['mce_editor_theme_title'] = "Thema:";
$_lang['mce_editor_theme_message'] = "Selecteer hier welk thema of skin u wilt gebruiken met de editor.";
$_lang['mce_editor_custom_plugins_title'] = "Aangepaste plugins:";
$_lang['mce_editor_custom_plugins_message'] = "Vul in welke plugins u wilt gebruiken voor het 'aangepaste' thema en scheidt de waarden door komma's.";
$_lang['mce_editor_custom_buttons_title'] = "Aangepaste knoppen:";
$_lang['mce_editor_custom_buttons_message'] = "Vul in welke knoppen u wilt gebruiken voor het 'aangepaste' thema en scheidt de waarden door komma's voor elke rij. Wees er zeker van dat voor elke knop de vereiste plugin aangezet is in de 'Aangepaste plugins' instelling.";
$_lang['mce_editor_css_selectors_title'] = "CSS selectors:";
$_lang['mce_editor_css_selectors_message'] = "Vul een lijst van selectors in die beschikbaar moeten zijn in de editor. Vul dit als volgt in:<br />'displayNaam1=selectorNaam1;displayNaam2=selectorNaam2'<br />Bijvoorbeeld: je hebt de <b>.mono</b> en <b>.smallText</b> selectors in uw CSS bestand, dan kunt u die hier invullen als:<br />'Monospaced text=mono;Small text=smallText'<br />Let erop dat de laatste waarde <b>niet</b> wordt afgesloten door een punt-komma.";
$_lang['mce_settings'] = "TinyMCE instellingen";
$_lang['mce_theme_simple'] = "Eenvoudig";
$_lang['mce_theme_advanced'] = "Geavanceerd";
$_lang['mce_theme_editor'] = "Content Editor";
$_lang['mce_theme_custom'] = "Aangepast";
$_lang['mce_theme_creative'] = 'Creative';
$_lang['mce_theme_logic'] = 'xhtml';
$_lang['mce_theme_legacy'] = 'legacy style';
$_lang['mce_theme_global_settings'] = "Gebruik het algemene instelling";
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