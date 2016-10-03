<?php
/**
 * Function:       Finnish language file for gsettings
 * Encoding:       UTF-8
 * Author:         Jeff Whitfield
 * Date:           2016/02/19
 * Version:        4.5.7.0
 * MODX version:   0.9.5-1.1
*/

$_lang['lang_code'] = 'fi';
$_lang['editor_theme_title'] = 'Teema';
$_lang['editor_theme_message'] = 'Valitse editorille haluamasi teema/ulkoasu.';
$_lang['editor_custom_plugins_title'] = 'Valinnaiset pluginit';
$_lang['editor_custom_plugins_message'] = 'Anna tähän pilkuilla eroteltuina ne pluginit, joita käytät kustomoidussa teemassa.';
$_lang['editor_custom_buttons_title'] = 'Valinnaiset painikkeet';
$_lang['editor_custom_buttons_message'] = 'Anna tähän pilkuilla eroteltuina joka riville ne painikkeet, joita käytät kustomoidussa teemassa. Varmista, että jokaiselle painikkeelle on asetettu plugin \'Valinnaiset pluginit\'-kohdassa.';
$_lang['editor_css_selectors_title'] = 'CSS-valitsimet';
$_lang['editor_css_selectors_message'] = 'Anna tähän css-valitsimet, joita haluat käyttää editorissa. Syötä ne näin:<br />[+editor_css_selectors_schema+]<br />Jos sinulla on esim. <b>.leipateksti</b> ja <b>.otsikko</b> valitsimina css-tiedostossasi, lisää ne listaan näin:<br />[+editor_css_selectors_example+]<br />Huomaa, ettei viimeisen kohdan jälkeen le puolipistettä.'; // add &quot;[+editor_css_selectors_separator+]&quot;
$_lang['settings'] = 'Asetukset';
$_lang['theme_simple'] = 'Yksinkertaistettu';
$_lang['theme_full'] = 'Täysi';
$_lang['theme_advanced'] = 'Edistynyt';
$_lang['theme_editor'] = 'Sisällönmuokkaajalle';
$_lang['theme_custom'] = 'Kustomoitu';
$_lang['theme_creative'] = 'Luova';
$_lang['theme_logic'] = 'XHTML';
$_lang['theme_legacy'] = 'Vanha tyyli';
$_lang['theme_global_settings'] = 'Käytä maailmanlaajuisesti';
$_lang['editor_skin_title'] = 'Ulkoasu';
$_lang['editor_skin_message'] = 'Työkalupalkin visuaalinen tyyli. Katso ';
$_lang['editor_entermode_title'] = 'Enter-näppäimen toiminto';
$_lang['editor_entermode_message'] = 'Valitse mitä tapahtuu kun enter-näppäintä painetaan.';
$_lang['entermode_opt1'] = 'Ympäröi kappale-elementillä &lt;p&gt;&lt;/p&gt;';
$_lang['entermode_opt2'] = 'Lisää rivivaihtoelementti &lt;br /&gt;';

$_lang['element_format_title'] = '(X)HTML-elementtien merkintätyyli';
$_lang['element_format_message'] = 'Tässä voit valita muotoillaanko tyhjät elementit HTML- vai XHTML-tyylisesti. XHTML on oletusvalinta. Tämä tarkoittaa että esimerkiksi &lt;br /&gt; muutetaan muotoon &lt;br&gt;, jos tämä asetus on kohdassa &quot;HTML&quot;.';
$_lang['schema_title'] = 'Schema';
$_lang['schema_message'] = 'The schema option enables you to switch between the HTML4 and HTML5 schema. This controls the valid elements and attributes that can be placed in the HTML. This value can either be the default html4 or html5.';

$_lang['tpl_title'] = 'Template Button';
$_lang['tpl_msg'] = 'You could define templates on chunk or ressource base for the template button in [+editorLabel+] (won\'t be displayed by default). The content of the chunk/of the resource will be inserted at the cursor position as html code in [+editorLabel+]. Multiple chunk names or ressource IDs have to be separated by comma.';
$_lang['tpl_docid'] = 'Sivujen IDt';
$_lang['tpl_chunkname'] = 'Palasten nimet';

$_lang['default'] = 'Default:&nbsp;';