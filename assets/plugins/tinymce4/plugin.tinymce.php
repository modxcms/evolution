<?php
/**
 * TinyMCE4
 *
 * Javascript rich text editor
 *
 * @category    plugin
 * @version     4.3.7.1
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @properties &styleFormats=Custom Style Formats;textarea;Title,cssClass|Title2,cssClass &customParams=Custom Parameters <b>(Be careful or leave empty!)</b>;textarea; &entityEncoding=Entity Encoding;list;named,numeric,raw;named &entities=Entities;text; &pathOptions=Path Options;list;Site config,Absolute path,Root relative,URL,No convert;Site config &resizing=Advanced Resizing;list;true,false;false &disabledButtons=Disabled Buttons;text; &webTheme=Web Theme;test;webuser &webPlugins=Web Plugins;text; &webButtons1=Web Buttons 1;text;bold italic underline strikethrough removeformat alignleft aligncenter alignright &webButtons2=Web Buttons 2;text;link unlink image undo redo &webButtons3=Web Buttons 3;text; &webButtons4=Web Buttons 4;text; &webAlign=Web Toolbar Alignment;list;ltr,rtl;ltr &width=Width;text;100% &height=Height;text;400px &introtextRte=<b>Introtext RTE</b><br/>add richtext-features to "introtext";list;enabled,disabled;disabled &inlineMode=<b>Inline-Mode</b>;list;enabled,disabled;disabled &inlineTheme=<b>Inline-Mode</b><br/>Theme;text;inline &editableClass=<b>Inline-Mode</b><br/>CSS-Class selector;text;editable &editableIds=<b>Inline-Mode</b><br/>Editable<br/>Modx-Phs->CSS-IDs<br/>(line-breaks allowed);textarea;longtitle->#modx_longtitle,content->#modx_content
 * @internal    @events OnLoadWebDocument,OnWebPagePrerender,OnRichTextEditorRegister,OnRichTextEditorInit,OnInterfaceSettingsRender
 * @internal    @modx_category Manager and Admin
 * @internal    @legacy_names TinyMCE4
 * @internal    @installset base
 *
 * @author Yama / updated: 2015-01-16
 * @author Dmi3yy / updated: 2016-01-07
 * @author Deesen / updated: 2016-04-04
 *
 * Latest Updates / Issues on Github : https://github.com/extras-evolution/tinymce4-for-modx-evo
 */
if (!defined('MODX_BASE_PATH')) { die('What are you doing? Get out of here!'); }

include(MODX_BASE_PATH."assets/plugins/tinymce4/plugin.tinymce.inc.php");