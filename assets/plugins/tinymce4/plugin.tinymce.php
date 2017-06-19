<?php
/**
 * TinyMCE4
 *
 * Javascript rich text editor
 *
 * @category    plugin
 * @version     4.3.7.2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @properties &styleFormats=Custom Style Formats;textarea;Title,cssClass|Title2,cssClass &customParams=Custom Parameters <b>(Be careful or leave empty!)</b>;textarea; &entityEncoding=Entity Encoding;list;named,numeric,raw;named &entities=Entities;text; &pathOptions=Path Options;list;Site config,Absolute path,Root relative,URL,No convert;Site config &resizing=Advanced Resizing;list;true,false;false &disabledButtons=Disabled Buttons;text; &webTheme=Web Theme;test;webuser &webPlugins=Web Plugins;text; &webButtons1=Web Buttons 1;text;bold italic underline strikethrough removeformat alignleft aligncenter alignright &webButtons2=Web Buttons 2;text;link unlink image undo redo &webButtons3=Web Buttons 3;text; &webButtons4=Web Buttons 4;text; &webAlign=Web Toolbar Alignment;list;ltr,rtl;ltr &width=Width;text;100% &height=Height;text;400px &introtextRte=<b>Introtext RTE</b><br/>add richtext-features to "introtext";list;enabled,disabled;disabled &inlineMode=<b>Inline-Mode</b>;list;enabled,disabled;disabled &inlineTheme=<b>Inline-Mode</b><br/>Theme;text;inline &browser_spellcheck=<b>Browser Spellcheck</b><br/>At least one dictionary must be installed inside your browser;list;enabled,disabled;disabled
 * @internal    @events OnLoadWebDocument,OnParseDocument,OnWebPagePrerender,OnLoadWebPageCache,OnRichTextEditorRegister,OnRichTextEditorInit,OnInterfaceSettingsRender
 * @internal    @modx_category Manager and Admin
 * @internal    @legacy_names TinyMCE4
 * @internal    @installset base
 * @logo        /assets/plugins/tinymce4/tinymce/logo.png
 * @reportissues https://github.com/extras-evolution/tinymce4-for-modx-evo
 * @documentation Plugin docs https://github.com/extras-evolution/tinymce4-for-modx-evo
 * @documentation Official TinyMCE4-docs https://www.tinymce.com/docs/
 * @author      Deesen
 * @lastupdate  2016-11-01
 */
if (!defined('MODX_BASE_PATH')) { die('What are you doing? Get out of here!'); }

require(MODX_BASE_PATH."assets/plugins/tinymce4/plugin.tinymce.inc.php");