<?php
/**
 * Filename:       assets/plugins/tinymce/lang/portuguese.inc.php
 * Function:       Portuguese language file for TinyMCE
 * Encoding:       UTF-8
 * Author:         Jeff Whitfield - translation João Peixoto (joeindio@gmail.com)
 * Date:           2014/02/01
 * Version:        3.5.10
 * MODX version:   0.9.5-1.0.15
*/

$_lang['mce_editor_theme_title'] = 'Tema';
$_lang['mce_editor_theme_message'] = 'Aqui pode seleccionar que tema ou capa deseja utilizar com o editor.';
$_lang['mce_editor_custom_plugins_title'] = 'Plugins personalizados';
$_lang['mce_editor_custom_plugins_message'] = 'Indique os plugins a utilizar com o tema personalizado, numa lista separada por vírgulas.<br />Default : template,visualblocks,autolink,inlinepopups,autosave,save,advlist,style,fullscreen, advimage,paste,advlink,media,contextmenu,table';
$_lang['mce_editor_custom_buttons_title'] = 'Botões personalizados';
$_lang['mce_editor_custom_buttons_message'] = 'Indique os botões a utilizar para o tema personalizado, numa lista separada por vírgulas para cada linha. Assegure-se de que cada botão tem o plugin requerido activado na opção \'Plugins Personalizados\'.';
$_lang['mce_editor_css_selectors_title'] = 'Selectores CSS';
$_lang['mce_editor_css_selectors_message'] = 'Aqui pode indicar uma lista dos selectores que deverão estar disponíveis no editor. Indique-os da seguinte forma:<br />\'nomeMostrado=nomeSelector;nomeMostrado2=nomeSelector2\'<br />Por exemplo, se tiver os selectores <b>.mono</b> e <b>.texto Pequeno</b> no seu ficheiro CSS, pode adicioná-los aqui como:<br />\'Texto com espaçamento simples=mono;Texto Pequeno=textoPequeno\'<br />Note que a última entrada não deverá ter ponto e vírgula a segui-la.';
$_lang['mce_settings'] = 'Opções TinyMCE';
$_lang['mce_theme_simple'] = 'Simples';
$_lang['mce_theme_full'] = 'Full';
$_lang['mce_theme_advanced'] = 'Avançado';
$_lang['mce_theme_editor'] = 'Editor de Conteúdo';
$_lang['mce_theme_custom'] = 'Personalizar';
$_lang['mce_theme_creative'] = 'Creative';
$_lang['mce_theme_logic'] = 'xhtml';
$_lang['mce_theme_legacy'] = 'legacy style';
$_lang['mce_theme_global_settings'] = 'Use a configuração global';
$_lang['mce_editor_skin_title'] = 'Skin';
$_lang['mce_editor_skin_message'] = 'Design of toolbar. see tinymce/tiny_mce/themes/advanced/skins/<br />';
$_lang['mce_editor_entermode_title'] = 'Enter Key Mode';
$_lang['mce_editor_entermode_message'] = 'Operation when the enter key is pressed is set up.';
$_lang['mce_entermode_opt1'] = 'Wrap &lt;p&gt;&lt;/p&gt;';
$_lang['mce_entermode_opt2'] = 'Insert &lt;br /&gt;';

$_lang['mce_element_format_title'] = 'Element Format';
$_lang['mce_element_format_message'] = 'This option enables control if elements should be in html or xhtml mode. xhtml is the default state for this option. This means that for example &lt;br /&gt; will be &lt;br&gt; if you set this option to &quot;html&quot;.';
$_lang['mce_schema_title'] = 'Schema';
$_lang['mce_schema_message'] = 'The schema option enables you to switch between the HTML4 and HTML5 schema. This controls the valid elements and attributes that can be placed in the HTML. This value can either be the default html4 or html5.';

$_lang['mce_toolbar1_msg'] = 'Default: undo,redo,|,bold,forecolor,backcolor,strikethrough,formatselect,fontsizeselect, pastetext,pasteword,code,|,fullscreen,help';
$_lang['mce_toolbar2_msg'] = 'Default: image,media,link,unlink,anchor,|,justifyleft,justifycenter,justifyright,|,bullist, numlist,|,blockquote,outdent,indent,|,table,hr,|,template,visualblocks,styleprops,removeformat';

$_lang['mce_tpl_title'] = 'Template Button';
$_lang['mce_tpl_msg'] = 'You could define templates on chunk or ressource base for the template button in TinyMCE (won\'t be displayed by default). The content of the chunk/of the resource will be inserted at the cursor position as html code in TinyMCE. Multiple chunk names or ressource IDs have to be separated by comma.';
$_lang['mce_tpl_docid'] = 'Resource IDs';
$_lang['mce_tpl_chunkname'] = 'Chunk Names';