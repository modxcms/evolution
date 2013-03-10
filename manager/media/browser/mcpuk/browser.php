<?php
define('MODX_BASE_PATH', realpath('../../../../'));
$rb = new FBROWSER();
$ph = array();
$ph['seturl_js'] = $rb->seturl_js();
$output = $rb->render_fbrowser($ph);
echo $output;

class FBROWSER
{
	function seturl_js()
	{
		$seturl_js_filename = (isset($_GET['editor']) && !stristr($_GET['editor'],"..")) ? 'seturl_js_'  . htmlspecialchars($_GET['editor']) . '.inc' : '';
		$seturl_js_path = MODX_BASE_PATH . 'assets/plugins/';
		
		if($seturl_js_filename!='' && file_exists($seturl_js_path . $seturl_js_filename))
		{
			$result = file_get_contents($seturl_js_path . $seturl_js_filename);
		}
		else
		{
			switch($_GET['editor'])
			{
				case 'tinymce' :
				case 'tinymce3':
					$editor_path = isset($_GET['editorpath']) ? htmlspecialchars($_GET['editorpath'], ENT_QUOTES) : '';
					$result = file_get_contents('seturl_js_tinymce.inc');
					$result = str_replace('[+editor_path+]', $editor_path, $result);
					break;
				default:
				$result = '<script src="seturl.js" type="text/javascript"></script>' . PHP_EOL;
			}
		}
		return $result;
	}
	
	function render_fbrowser($ph)
	{
		$browser_html = file_get_contents('browser.html.inc');
		$browser_html2 = $browser_html;
		foreach($ph as $name => $value)
		{
			$name = '[+' . $name . '+]';
			$browser_html = str_replace($name, $value, $browser_html);
		}
		return $browser_html;
	}
}
