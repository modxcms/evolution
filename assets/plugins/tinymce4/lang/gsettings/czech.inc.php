<?php
/**
 * Function:       Czech language file for gsettings
 * Encoding:       UTF-8
 * Author:         Hansek, COEX (hansek@coex.cz)
 * Date:           2016/02/19
 * Version:        4.5.7.0
 * MODX version:   0.9.5-1.1
*/

$_lang['lang_code'] = 'cs';
$_lang['editor_theme_title'] = 'Téma';
$_lang['editor_theme_message'] = 'Zde si můžete vybrat téma nebo skin, který se použije v editoru.';
$_lang['editor_custom_plugins_title'] = 'Uživatelské Pluginy';
$_lang['editor_custom_plugins_message'] = 'Zadejte pluginy, které se použijí pro \'uživatelské\' téma jako čárkou dělený seznam.';
$_lang['editor_custom_buttons_title'] = 'Uživatelská tlačítka';
$_lang['editor_custom_buttons_message'] = 'Zadejte tlačítka, oddělená čárkou, která se použijí v tématu \'custom\' pro každý řádek. Ujistěte se, že každé tlačítko má aktivní Plugin v nastavení \'Uživatelské Pluginy\'.';
$_lang['editor_css_selectors_title'] = 'CSS selectory';
$_lang['editor_css_selectors_message'] = 'Na tomto místě můžete vydefinovat třídy, které následně budete moci použít v editoru. Vkládejte je v následujícím tvaru:<br />[+editor_css_selectors_schema+]<br />Například řekněme, že chceme mít třídy <b>.mono</b> a <b>.smallText</b> v našem CSS souboru, můžeme je přidat zde ve tvaru:<br />[+editor_css_selectors_example+]<br />Pozor na to, že poslední výraz nesmí být ukončen středníkem.'; // add &quot;[+editor_css_selectors_separator+]&quot;
$_lang['settings'] = 'Nastavení';
$_lang['theme_simple'] = 'Simple';
$_lang['theme_full'] = 'Full';
$_lang['theme_advanced'] = 'Advanced';
$_lang['theme_editor'] = 'MODX style';
$_lang['theme_custom'] = 'Custom';
$_lang['theme_creative'] = 'Creative';
$_lang['theme_logic'] = 'xhtml';
$_lang['theme_legacy'] = 'legacy style';
$_lang['theme_global_settings'] = 'Použít globální nastavení';
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