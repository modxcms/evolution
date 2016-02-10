<?php
/*
 * events: OnRichTextEditorRegister, OnRichTextEditorInit
 * configuration: &customparams=Custom Parameters;textarea;valid_elements : "*[*]", &mce_formats=Block Formats;text;p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre &entity_encoding=Entity Encoding;list;named,numeric,raw;named &entities=Entities;text; &mce_path_options=Path Options;list;Site config,Absolute path,Root relative,URL,No convert;Site config &mce_resizing=Advanced Resizing;list;true,false;true &disabledButtons=Disabled Buttons;text; &link_list=Link List;list;enabled,disabled;enabled &custom_plugins=Custom Plugins;text; &custom_buttons1=Custom Buttons 1;text;bold,italic,underline &custom_buttons2=Custom Buttons 2;text;strikethrough &custom_buttons3=Custom Buttons 3;text; &custom_buttons4=Custom Buttons 4;text; &toolbar_align=Toolbar Alignment;list;ltr,rtl;ltr &width=Width;text;200 &height=Height;text;200
 *
 * */

if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

global $usersettings,$settings;

// Set path and base setting variables
$params['mce_path']         = $mce_path;
$params['mce_url']          = $mce_url;

$plugin_dir = 'tinymce';
include_once("{$mce_path}functions.php");

$mce = new TinyMCE();

// overwrite for mini
$mce->params['theme'] = 'custom';
$mce->params['custom_buttons1'] = !empty( $custom_buttons1 ) ? $custom_buttons1 : 'bold,italic,underline,strikethrough';
$mce->params['custom_buttons2'] = !empty( $custom_buttons2 ) ? $custom_buttons2 : '';
$mce->params['custom_buttons3'] = !empty( $custom_buttons3 ) ? $custom_buttons3 : '';
$mce->params['custom_buttons4'] = !empty( $custom_buttons4 ) ? $custom_buttons4 : '';

// Handle event
$e = &$modx->event; 
switch ($e->name)
{
	case "OnRichTextEditorRegister": // register only for backend
		$e->output('TinyMCE Mini');
		break;

	case "OnRichTextEditorInit":
		if($editor!=='TinyMCE Mini') return;
		
		$html = $mce->get_mce_script();
		$e->output($html);
		break;

	case "OnInterfaceSettingsRender":
		// $html = $mce->get_mce_settings();
		// $e->output($html);
		break;

   default :
      return; // stop here - this is very important. 
      break; 
}
