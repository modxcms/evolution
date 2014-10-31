<?php
/**
 * Filename:       assets/plugins/tinymce/lang/czech.inc.php
 * Function:       Czech language file for TinyMCE
 * Encoding:       UTF-8
 * Author:         Hansek, COEX (hansek@coex.cz)
 * Date:           2014/02/01
 * Version:        3.5.10
 * MODX version:   0.9.5-1.0.15
*/

$_lang['mce_editor_theme_title'] = 'Téma';
$_lang['mce_editor_theme_message'] = 'Zde si můžete vybrat téma nebo skin, který se použije v editoru.';
$_lang['mce_editor_custom_plugins_title'] = 'Uživatelské Pluginy';
$_lang['mce_editor_custom_plugins_message'] = 'Zadejte pluginy, které se použijí pro \'uživatelské\' téma jako čárkou dělený seznam.';
$_lang['mce_editor_custom_buttons_title'] = 'Uživatelská tlačítka';
$_lang['mce_editor_custom_buttons_message'] = 'Zadejte tlačítka, oddělená čárkou, která se použijí v tématu \'custom\' pro každý řádek. Ujistěte se, že každé tlačítko má aktivní Plugin v nastavení \'Uživatelské Pluginy\'.';
$_lang['mce_editor_css_selectors_title'] = 'CSS selectory';
$_lang['mce_editor_css_selectors_message'] = 'Na tomto místě můžete vydefinovat třídy, které následně budete moci použít v editoru. Vkládejte je v následujícím tvaru:<br />\'zobrazovanéJméno=jmenoSelektoru;zobrazovanéJméno2=jmenoSelektoru2\'<br />Například řekněme, že chceme mít třídy <b>.mono</b> a <b>.smallText</b> v našem CSS souboru, můžeme je přidat zde ve tvaru:<br />\'Monospaced text=mono;Small text=smallText\'<br />Pozor na to, že poslední výraz nesmí být ukončen středníkem.';
$_lang['mce_settings'] = 'TinyMCE nastavení';
$_lang['mce_theme_simple'] = 'Simple';
$_lang['mce_theme_full'] = 'Full';
$_lang['mce_theme_advanced'] = 'Advanced';
$_lang['mce_theme_editor'] = 'MODX style';
$_lang['mce_theme_custom'] = 'Custom';
$_lang['mce_theme_creative'] = 'Creative';
$_lang['mce_theme_logic'] = 'xhtml';
$_lang['mce_theme_legacy'] = 'legacy style';
$_lang['mce_theme_global_settings'] = 'Použít globální nastavení';
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