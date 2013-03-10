<?php
/*
 * Filename:       assets/plugins/tinymce/lang/russian.inc.php
 * Function:       Russian language file for TinyMCE
 * Encoding:       Windows-1251
 * Author:         Jeff Whitfield / translated by Victor Safronovich
 * Date:           2013-03-10
 * Version:        3.5.8
 * MODX version:   0.9.5-1.0.9
*/

$_lang['mce_editor_theme_title'] = "Тема WYSIWYG-редактора:";
$_lang['mce_editor_theme_message'] = "Выберите какую тему или шаблон вы будете использовать для WYSIWYG-редактора.";
$_lang['mce_editor_custom_plugins_title'] = "Индивидуальные плагины:";
$_lang['mce_editor_custom_plugins_message'] = "Введите, через запятую, плагины для &laquo;Индивидуальной&raquo; темы.";
$_lang['mce_editor_custom_buttons_title'] = "Индивидуальные кнопки:";
$_lang['mce_editor_custom_buttons_message'] = "Введите, через запятую, кнопки для &laquo;Индивидуальной&raquo; темы. Убедитесь что дополнения, к которым относятся данные кнопки, прописанны в настройке &laquo;Индивидуальные плагины&raquo;.";
$_lang['mce_editor_css_selectors_title'] = "Селекторы CSS:";
$_lang['mce_editor_css_selectors_message'] = "Введите список селекторов, который будет доступен в WYSIWYG-редакторе. Введите их следующим образом:<br />&laquo;имя, которое будет показано в WYSIWYG-редакторе&raquo;=&laquo;имя селектора&raquo;;displayName2=selectorName2<br />Например, в вашем CSS файле есть <b>.mono</b> и <b>.smallText</b> селекторы, чтобы их добавить надо прописать:<br />'Монохромный текст=mono;Мелкий текст=smallText'<br />NB: не ставьте точку с запятой(;) после последней записи.";
$_lang['mce_settings'] = "Настройки TinyMCE";
$_lang['mce_theme_simple'] = "Простая";
$_lang['mce_theme_advanced'] = "Продвинутая";
$_lang['mce_theme_editor'] = "Редактор контента";
$_lang['mce_theme_custom'] = "Индивидуальная";
$_lang['mce_theme_creative'] = 'Creative';
$_lang['mce_theme_logic'] = 'xhtml';
$_lang['mce_theme_legacy'] = 'legacy style';
$_lang['mce_theme_global_settings'] = "Использовать глобальные настройки";
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