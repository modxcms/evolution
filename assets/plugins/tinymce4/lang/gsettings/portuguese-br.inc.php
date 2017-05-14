<?php
/**
 * Function:       Portuguese language file for gsettings
 * Encoding:       UTF-8
 * Author:         Jeff Whitfield and João Peixoto (joeindio@gmail.com)
 * Date:           2016/02/19
 * Version:        4.5.7.0
 * MODX version:   0.9.5-1.1
*/

$_lang['lang_code'] = 'pt_BR';
$_lang['editor_theme_title'] = 'Tema';
$_lang['editor_theme_message'] = 'Aqui pode seleccionar que tema ou capa deseja utilizar com o editor.';
$_lang['editor_custom_plugins_title'] = 'Plugins personalizados';
$_lang['editor_custom_plugins_message'] = 'Indique os plugins a utilizar com o tema personalizado, numa lista separada por vírgulas.';
$_lang['editor_custom_buttons_title'] = 'Botões personalizados';
$_lang['editor_custom_buttons_message'] = 'Indique os botões a utilizar para o tema personalizado, numa lista separada por vírgulas para cada linha. Assegure-se de que cada botão tem o plugin requerido activado na opção \'Plugins Personalizados\'.';
$_lang['editor_css_selectors_title'] = 'Selectores CSS';
$_lang['editor_css_selectors_message'] = 'Aqui pode indicar uma lista dos selectores que deverão estar disponíveis no editor. Indique-os da seguinte forma:<br />[+editor_css_selectors_schema+]<br />Por exemplo, se tiver os selectores <b>.mono</b> e <b>.texto Pequeno</b> no seu ficheiro CSS, pode adicioná-los aqui como:<br />[+editor_css_selectors_example+]<br />Note que a última entrada não deverá ter ponto e vírgula a segui-la.'; // add &quot;[+editor_css_selectors_separator+]&quot;
$_lang['settings'] = 'Opções';
$_lang['theme_simple'] = 'Simples';
$_lang['theme_full'] = 'Full';
$_lang['theme_advanced'] = 'Avançado';
$_lang['theme_editor'] = 'Editor de Conteúdo';
$_lang['theme_custom'] = 'Personalizar';
$_lang['theme_creative'] = 'Creative';
$_lang['theme_logic'] = 'xhtml';
$_lang['theme_legacy'] = 'legacy style';
$_lang['theme_global_settings'] = 'Use a configuração global';
$_lang['editor_skin_title'] = 'Skin';
$_lang['editor_skin_message'] = 'Design of toolbar. see ';
$_lang['editor_entermode_title'] = 'Enter Key Mode';
$_lang['editor_entermode_message'] = 'Operation when the enter key is pressed is set up.';
$_lang['entermode_opt1'] = 'Wrap &lt;p&gt;&lt;/p&gt;';
$_lang['entermode_opt2'] = 'Insert &lt;br /&gt;';

$_lang['element_format_title'] = 'Element Format';
$_lang['element_format_message'] = 'This option enables control if elements should be in html or xhtml mode. xhtml is the default state for this option. This means that for example &lt;br /&gt; will be &lt;br&gt; if you set this option to &quot;html&quot;.';
$_lang['schema_title'] = 'Schema';
$_lang['schema_message'] = 'The schema option enables you to switch between the HTML4 and HTML5 schema. This controls the valid elements and attributes that can be placed in the HTML. This value can either be the default html4 or html5.';

$_lang['tpl_title'] = 'Template Button';
$_lang['tpl_msg'] = 'You could define templates on chunk or ressource base for the template button in [+editorLabel+] (won\'t be displayed by default). The content of the chunk/of the resource will be inserted at the cursor position as html code in [+editorLabel+]. Multiple chunk names or ressource IDs have to be separated by comma.';
$_lang['tpl_docid'] = 'Resource IDs';
$_lang['tpl_chunkname'] = 'Chunk Names';

$_lang['default'] = 'Default:&nbsp;';