<?php
/**
 * TinyMCE Rich Text Editor
 *
 * Javascript WYSIWYG Editor
 *
 * @category    plugin
 * @version     3.5.12
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @properties &customparams=Custom Parameters;textarea;valid_elements : "*[*]", &mce_formats=Block Formats;text;p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre &entity_encoding=Entity Encoding;list;named,numeric,raw;named &entities=Entities;text; &mce_path_options=Path Options;list;Site config,Absolute path,Root relative,URL,No convert;Site config &mce_resizing=Advanced Resizing;list;true,false;true &disabledButtons=Disabled Buttons;text; &link_list=Link List;list;enabled,disabled;enabled &webtheme=Web Theme;list;simple,editor,creative,custom;simple &webPlugins=Web Plugins;text;style,advimage,advlink,searchreplace,contextmenu,paste,fullscreen,xhtmlxtras,media &webButtons1=Web Buttons 1;text;undo,redo,selectall,|,pastetext,pasteword,|,search,replace,|,hr,charmap,|,image,link,unlink,anchor,media,|,cleanup,removeformat,|,fullscreen,code,help &webButtons2=Web Buttons 2;text;bold,italic,underline,strikethrough,sub,sup,|,|,blockquote,bullist,numlist,outdent,indent,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,|,styleprops &webButtons3=Web Buttons 3;text; &webButtons4=Web Buttons 4;text; &webAlign=Web Toolbar Alignment;list;ltr,rtl;ltr &width=Width;text;100% &height=Height;text;500
 * @internal    @events OnRichTextEditorRegister,OnRichTextEditorInit,OnInterfaceSettingsRender
 * @internal    @modx_category Manager and Admin
 * @internal    @legacy_names TinyMCE
 * @internal    @installset base
 * @reportissues https://github.com/modxcms/evolution
 * @documentation MODX Docs https://rtfm.modx.com/extras/evo/tinymce
 * @documentation Official docs http://archive.tinymce.com/wiki.php/TinyMCE3x:TinyMCE_3.x
 * @author Jeff Whitfield
 * @author Mikko Lammi / updated: 03/09/2010
 * @author Dmi3yy / updated: 2013-11-01
 * @author Kari Söderholm aka Haprog / updated: 2014-04-02
 * @author yama / updated: 2014-05-16
 * @author Pathologic, MrSwed, Bossloper
 * @lastupdate  09/04/2016
 */
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}
// Set the name of the plugin folder

global $usersettings,$settings;

// Set path and base setting variables

$params['mce_path']         = $mce_path;
$params['mce_url']          = $mce_url;

$plugin_dir = 'tinymce';
include_once("{$mce_path}functions.php");

$mce = new TinyMCE();

// Handle event
$e = &$modx->event; 
switch ($e->name)
{
	case "OnRichTextEditorRegister": // register only for backend
		$e->output('TinyMCE');
		break;

	case "OnRichTextEditorInit":
		if($editor!=='TinyMCE') return;
		
		$html = $mce->get_mce_script();
		$e->output($html);
		break;

	case "OnInterfaceSettingsRender":
		$html = $mce->get_mce_settings();
		$e->output($html);
		break;

   default :
      return; // stop here - this is very important. 
      break; 
}
