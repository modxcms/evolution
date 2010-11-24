<?php

class TinyMCE
{

	function TinyMCE($params)
	{
	}
	
	function get_mce_settings($params)
	{
		global $modx, $_lang;
		// language settings
		if (! @include_once($params['mce_path'] .'lang/'.$modx->config['manager_language'].'.inc.php'))
		{
			include_once($params['mce_path'] .'lang/english.inc.php');
		}
	
		if($modx->manager->action == 11 || $modx->manager->action == 12)
		{
			$theme_options .= '<option value="">' . $_lang['tinymce_theme_global_settings'] . '</option>' . PHP_EOL;
		}
		$themes['simple']   = $_lang['tinymce_theme_simple'];
		$themes['editor']   = $_lang['tinymce_theme_editor'];
		$themes['creative'] = $_lang['tinymce_theme_creative'];
		$themes['logic']    = $_lang['tinymce_theme_logic'];
		$themes['advanced'] = $_lang['tinymce_theme_advanced'];
		$themes['legacy']   = (!empty($_lang['tinymce_theme_legacy'])) ? $_lang['tinymce_theme_legacy'] : 'legacy';
		$themes['full']     = (!empty($_lang['tinymce_theme_full']))   ? $_lang['tinymce_theme_full'] : 'full';
		$themes['custom']   = $_lang['tinymce_theme_custom'];
		foreach ($themes as $key => $value)
		{
			$theme_options .= '<option value="' . $key . '"' . ($key == $params['theme'] ? ' selected="selected"' : '') . '>' . $value . '</option>' . PHP_EOL;
		}
		
		$ph = $_lang;
		switch($_SESSION['browser'])
		{
			case 'mz':
			case 'sf':
			case 'op':
				$ph['display'] = 'table-row';
				break;
			default:
				$ph['display'] = 'block';
		}
		$ph['display'] = $modx->config['use_editor']==1 ? $ph['display']: 'none';
		
		$ph['css_selectors']       = isset($params['css_selectors']) ? htmlspecialchars($params['css_selectors']) : '';
		$ph['theme_options']       = $theme_options;
		$ph['custom_plugins']      = $params['custom_plugins'];
		$ph['custom_buttons1']     = $params['custom_buttons1'];
		$ph['custom_buttons2']     = $params['custom_buttons2'];
		$ph['custom_buttons3']     = $params['custom_buttons3'];
		$ph['custom_buttons4']     = $params['custom_buttons4'];
		
		$gsettings = file_get_contents($params['mce_path'] . 'inc/gsettings.html.inc');
		
		foreach($ph as $name => $value)
		{
			$name = '[+' . $name . '+]';
			$gsettings = str_replace($name, $value, $gsettings);
		}
		return $gsettings;
	}
	
	function get_mce_script($params)
	{
		global $modx, $_lang;
		
		switch($params['theme'])
		{
		case 'simple':
			$plugins  = 'emotions,advimage,advlink,paste,contextmenu';
			$buttons1 = 'undo,redo,|,bold,strikethrough,|,justifyleft,justifycenter,justifyright,|,link,unlink,image,emotions,|,hr,|,help';
			$buttons2 = '';
		    break;
		case 'editor':
			$plugins  = 'autosave,save,advlist,style,fullscreen,advimage,paste,advlink,media,contextmenu,table';//clearfloat,
			$buttons1 = 'undo,redo,|,bold,forecolor,backcolor,strikethrough,formatselect,fontsizeselect,pastetext,pasteword,code,|,fullscreen,help';
			$buttons2 = 'image,media,link,unlink,anchor,|,justifyleft,justifycenter,justifyright,clearfloat,|,bullist,numlist,|,blockquote,outdent,indent,|,table,hr,|,styleprops,removeformat';
			$buttons3 = '';
			$buttons4 = '';
		    break;
		case 'creative':
			$plugins = 'autosave,advlist,layer,style,fullscreen,advimage,advhr,paste,advlink,media,contextmenu,table';
			$buttons1 = 'undo,undo,redo,|,bold,forecolor,backcolor,strikethrough,formatselect,styleselect,fontsizeselect,code';
			$buttons2 = 'image,media,link,unlink,anchor,|,bullist,numlist,|,blockquote,outdent,indent,|,justifyleft,justifycenter,justifyright,|,advhr,|,styleprops,removeformat,|,pastetext,pasteword';
			$buttons3 = 'insertlayer,absolute,moveforward,movebackward,|,tablecontrols,|,fullscreen,help';
		    break;
		case 'logic':
			$plugins = 'autosave,advlist,xhtmlxtras,style,fullscreen,advimage,paste,advlink,media,contextmenu,table';
			$buttons1 = 'undo,redo,|,bold,forecolor,backcolor,strikethrough,formatselect,styleselect,fontsizeselect,code,|,fullscreen,help';
			$buttons2 = 'image,media,link,unlink,anchor,|,bullist,numlist,|,blockquote,outdent,indent,|,justifyleft,justifycenter,justifyright,|,table,|,hr,|,styleprops,removeformat,|,pastetext,pasteword';
			$buttons3 = 'charmap,sup,sub,|,cite,ins,del,abbr,acronym,attribs';
		    break;
		case 'legacy':
			$plugins  = 'autosave,advlist,style,advimage,advlink,searchreplace,print,contextmenu,paste,fullscreen,nonbreaking,xhtmlxtras,visualchars,media';
			$buttons1 = 'undo,redo,selectall,|,pastetext,pasteword,|,search,replace,|,nonbreaking,hr,charmap,|,image,link,unlink,anchor,media,|,cleanup,removeformat,|,fullscreen,print,code,help';
			$buttons2 = 'bold,italic,underline,strikethrough,sub,sup,|,blockquote,|,bullist,numlist,outdent,indent,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,|,styleprops';
			$buttons3 = '';
		    break;
		case 'advanced':
			$plugins  = '';
			$buttons1 = 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect';
			$buttons2 = 'bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code';
			$buttons3 = 'hr,removeformat,visualaid,|,sub,sup,|,charmap';
		    break;
		case 'full':
			$plugins  = 'pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave';
			$buttons1 = 'save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect';
			$buttons2 = 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor';
			$buttons3 = 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen';
			$buttons4 = 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft';
		    break;
		case 'custom':
			$plugins  = $params['custom_plugins'];
			$buttons1 = $params['custom_buttons1'];
			$buttons2 = $params['custom_buttons2'];
			$buttons3 = $params['custom_buttons3'];
		    break;
		}
		
		$str  = $this->build_mce_init($params,$plugins,$buttons1,$buttons2,$buttons3,$buttons4);
		$str .= PHP_EOL;
		$str .= $this->build_tiny_callback($params);
		
		return $str;
	}
	
	function build_mce_init($params,$plugins,$buttons1,$buttons2,$buttons3,$buttons4)
	{
		global $modx, $action;
		$ph['mce_url'] = $params['mce_url'];
		$ph['elmList'] = implode(",", $params['elements']);
		$ph['width'] = (!empty($params['width'])) ? $params['width'] : '100%';
		$ph['height'] = (!empty($params['height'])) ? $params['height'] : '300';
		$ph['language'] = (empty($params['language'])) ? 'en' : $params['language'];
		
		$ph['document_base_url'] = MODX_SITE_URL;
		switch($params['pathoptions'])
		{
			case 'docrelative':
				$ph['relative_urls']      = 'true';
				$ph['remove_script_host'] = 'true';
				$ph['convert_urls']       = 'true';
				break;
			case 'rootrelative':
				$ph['relative_urls']      = 'false';
				$ph['remove_script_host'] = 'true';
				$ph['convert_urls']       = 'true';
				break;
			case 'fullpathurl':
				$ph['relative_urls']      = 'false';
				$ph['remove_script_host'] = 'false';
				$ph['convert_urls']       = 'true';
				break;
			default:
				$ph['relative_urls']      = 'true';
				$ph['remove_script_host'] = 'true';
				$ph['convert_urls']       = 'false';
		}
	
		$ph['forced_root_block']       = ($action !== '78') ? 'p' : '';
		$ph['force_p_newlines']        = ($action !== '78') ? 'true' : 'false';
		$ph['toolbar_align']           = $params['toolbar_align'];
		$ph['file_browser_callback']   = 'modx_fb';
		$ph['plugins']                 = $plugins;
		$ph['buttons1']                = $buttons1;
		$ph['buttons2']                = $buttons2;
		$ph['buttons3']                = $buttons3;
		$ph['buttons4']                = $buttons4;
		$ph['blockFormats']            = (empty($params['blockFormats'])) ? 'p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre,address' : $params['blockFormats'];
		$ph['css_selectors']           = $params['css_selectors'];
		$ph['disabledButtons']         = $params['disabledButtons'];
		$ph['resizing']                = $params['resizing'];
		$ph['date_format']             = $modx->toDateFormat(null, 'formatOnly');
		$ph['time_format']             = '%H:%M:%S';
		$ph['entity_encoding']         = $params['entity_encoding'];
		$ph['onchange_callback']       = ($params['frontend']!==false)? "'myCustomOnChangeHandler'" : 'false';
		$ph['terminate']               = (!empty($params['customparams'])) ? ',' : '';
		$ph['customparams']            = rtrim($params['customparams'], ',');
		$content_css[] = $params['mce_url'] . 'style/content.css';
		if ($params['editor_css_path']!=='') $content_css[] = MODX_BASE_URL . $params['editor_css_path'];
			$ph['content_css']         = join(',', $content_css);
		$ph['link_list']               = ($params['link_list']=='enabled')? "'" . $params['mce_url'] . 'inc/tinymce.linklist.php' . "'" : 'false';
	
		$mce_init = file_get_contents($params['mce_path'] . 'js/mce_init.js.inc');
		
		foreach($ph as $name => $value)
		{
			$name = '[+' . $name . '+]';
			$mce_init = str_replace($name, $value, $mce_init);
		}
		return $mce_init;
	}
	
	function build_tiny_callback($params)
	{
		$ph['cmsurl']  = MODX_BASE_URL . 'manager/media/browser/mcpuk/browser.php?Connector=';
		$ph['cmsurl'] .= MODX_BASE_URL . 'manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath=';
		$ph['cmsurl'] .= MODX_BASE_URL . '&editor=tinymce&editorpath=' . $params['mce_url'];
		$modx_fb = file_get_contents($params['mce_path'] . 'js/modx_fb.js.inc');
		
		foreach($ph as $name => $value)
		{
			$name = '[+' . $name . '+]';
			$modx_fb = str_replace($name, $value, $modx_fb);
		}
		return $modx_fb;
	}
}