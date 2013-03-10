<?php
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}
// Set the name of the plugin folder
$plugin_dir = "tinymce";

global $usersettings,$settings;

// Set path and base setting variables
if(!isset($mce_path))
{ 
	$mce_path = MODX_BASE_PATH . 'assets/plugins/'.$plugin_dir . '/'; 
	$mce_url  = MODX_BASE_URL  . 'assets/plugins/'.$plugin_dir . '/'; 
}
$params = $modx->event->params;
$params['mce_path']         = $mce_path;
$params['mce_url']          = $mce_url;

include_once $mce_path . 'functions.php';

$mce = new TinyMCE($params);

// Handle event
$e = &$modx->event; 
switch ($e->name)
{
	case "OnRichTextEditorRegister": // register only for backend
		$e->output("TinyMCE");
		break;

	case "OnRichTextEditorInit": 
		if($editor!=="TinyMCE") return;
		
		$params['css_selectors']   = $modx->config['tinymce_css_selectors'];
		$params['use_browser']     = $modx->config['use_browser'];
		$params['editor_css_path'] = $modx->config['editor_css_path'];
		
		if($modx->isBackend() || (intval($_GET['quickmanagertv']) == 1 && isset($_SESSION['mgrValidated'])))
		{
			$params['theme']              = $modx->config['tinymce_editor_theme'];
			$params['mce_editor_skin']    = $modx->config['mce_editor_skin'];
			$params['mce_entermode']      = $modx->config['mce_entermode'];
			$params['language']           = $mce->get_lang($modx->config['manager_language']);
			$params['frontend']           = false;
			$params['custom_plugins']     = $modx->config['tinymce_custom_plugins'];
			$params['custom_buttons1']    = $modx->config['tinymce_custom_buttons1'];
			$params['custom_buttons2']    = $modx->config['tinymce_custom_buttons2'];
			$params['custom_buttons3']    = $modx->config['tinymce_custom_buttons3'];
			$params['custom_buttons4']    = $modx->config['tinymce_custom_buttons4'];
			$params['toolbar_align']      = $modx->config['manager_direction'];
			$params['webuser']            = null;
			
			$html = $mce->get_mce_script($params);
		}
		else
		{
			$frontend_language = isset($modx->config['fe_editor_lang']) ? $modx->config['fe_editor_lang']:'';
			$webuser = (isset($modx->config['rb_webuser']) ? $modx->config['rb_webuser'] : null);
			
			$params['theme']           = $webtheme;
			$params['webuser']         = $webuser;
			$params['language']        = $mce->get_lang($frontend_language);
			$params['frontend']        = true;
			$params['custom_plugins']  = $webPlugins;
			$params['custom_buttons1'] = $webButtons1;
			$params['custom_buttons2'] = $webButtons2;
			$params['custom_buttons3'] = $webButtons3;
			$params['custom_buttons4'] = $webButtons4;
			$params['toolbar_align']   = $webAlign;
			
			$html = $mce->get_mce_script($params);
		}
		$e->output($html);
		break;

	case "OnInterfaceSettingsRender":
		switch ($modx->manager->action)
		{
    		case 11:
        		$mce_settings = array();
        		break;
    		case 12:
    		case 119:
        		$mce_settings = $usersettings;
    			if(!empty($usersettings['tinymce_editor_theme']))
    			{
    				$usersettings['tinymce_editor_theme'] = $settings['tinymce_editor_theme'];
    			}
        		break;
    		case 17:
        		$mce_settings = $settings;
        		break;
    		default:
        		$mce_settings = $settings;
        		break;
    	}
    	
		$params['theme']              = $mce_settings['tinymce_editor_theme'];
		$params['mce_editor_skin']    = $mce_settings['mce_editor_skin'];
		$params['mce_entermode']      = $mce_settings['mce_entermode'];
		$params['mce_element_format'] = $mce_settings['mce_element_format'];
		$params['mce_schema']         = $mce_settings['mce_schema'];
		$params['css_selectors']      = $mce_settings['tinymce_css_selectors'];
		$params['custom_plugins']     = $mce_settings['tinymce_custom_plugins'];
		$params['custom_buttons1']    = $mce_settings['tinymce_custom_buttons1'];
		$params['custom_buttons2']    = $mce_settings['tinymce_custom_buttons2'];
		$params['custom_buttons3']    = $mce_settings['tinymce_custom_buttons3'];
		$params['custom_buttons4']    = $mce_settings['tinymce_custom_buttons4'];
		$params['mce_template_docs']  = $mce_settings['mce_template_docs'];
		$params['mce_template_chunks']= $mce_settings['mce_template_chunks'];
    	
		$html = $mce->get_mce_settings($params);
		$e->output($html);
		break;

   default :
      return; // stop here - this is very important. 
      break; 
}
