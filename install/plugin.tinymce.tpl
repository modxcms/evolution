/**
 * TinyMCE RichText Editor Plugin 
 * Written By Raymond Irving - June 20, 2005
 * Modified By Jeff Whitfield - September 9, 2005
 *
 * Version 2.0.6.1b
 *
 * Events: OnRichTextEditorInit, OnRichTextEditorRegister, OnInterfaceSettingsRender
 *
 */

// When used from the web front-end 
// TinyMCE will use the following theme
$webTinyMCETheme = isset($webtheme) ? $webtheme:"simple";

// Set extended valid elements
global $tinymce_elements;
$tinymce_elements = "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]";

// Set variables from plugin configuration
global $tinymce_plugins;
global $tinymce_buttons1;
global $tinymce_buttons2;
global $tinymce_buttons3;
global $tinymce_buttons4;
global $tinymce_disable_buttons;
global $tinymce_formats;
global $tinymce_entity_encoding;
global $tinymce_entities;
$tinymce_plugins = isset($tinyPlugins) ? $tinyPlugins :"";
$tinymce_buttons1 = isset($tinyButtons1) ? $tinyButtons1 :"";
$tinymce_buttons2 = isset($tinyButtons2) ? $tinyButtons2 :"";
$tinymce_buttons3 = isset($tinyButtons3) ? $tinyButtons3 :"";
$tinymce_buttons4 = isset($tinyButtons4) ? $tinyButtons4 :"";
$tinymce_disable_buttons = isset($disabledButtons) ? $disabledButtons :"";
$tinymce_formats = isset($tinyFormats) ? $tinyFormats :"";
$tinymce_entity_encoding = isset($entity_encoding) ? $entity_encoding :"";
$tinymce_entities = ($entity_encoding == "named" && !empty($entities)) ? "entities : \"".$entities."\"," :"";
// Set path variable
if(!isset($tinymce_path)) { 
	global $tinymce_path;
	$tinymce_path = $modx->config['base_path'].'assets/plugins/tinymce'; 
}

// Language Settings and Functions
global $manager_language;
global $frontend_language;
$manager_language = $modx->config['manager_language'];
$frontend_language = isset($modx->config['fe_editor_lang']) ? $modx->config['fe_editor_lang']:"";

if (!function_exists('getTinyMCELang')) {
	function getTinyMCELang($lang){
		switch($lang){
			case "english":
			$returnlang = "en";
			break;
			
			case "finnish":
			$returnlang = "fi";
			break;
	
			case "francais":
			$returnlang = "fr";
			break;
			
			case "german":
			$returnlang = "de";
			break;
			
			case "italian":
			$returnlang = "it";
			break;
			
			case "japanese-utf8":
			$returnlang = "ja_utf-8";
			break;
			
			case "japanese-euc":
			$returnlang = "ja_euc-jp";
			break;

			case "nederlands":
			$returnlang = "nl";
			break;
	
			case "norsk":
			$returnlang = "nn";
			break;
	
			case "simple_chinese-gb2312":
			$returnlang = "zh_cn";
			break;
			
			case "spanish":
			$returnlang = "es";
			break;
			
			case "svenska":
			$returnlang = "sv";
			break;
			
			default:
			$returnlang = "en";
		}
		return $returnlang;
	}
}

// getTinyMCESettings function
if (!function_exists('getTinyMCESettings')) {
	function getTinyMCESettings() {
		global $_lang;
		global $use_editor;
		global $tinymce_editor_theme;
		global $tinymce_css_selectors;
		global $tinymce_editor_relative_urls;
		global $displayStyle;
		global $tinymce_path;
		global $manager_language;
		global $frontend_language;
		global $tinymce_compressor;

		// language settings
		if (file_exists($tinymce_path.'/lang/'.$manager_language.'.inc.php')){
			include_once($tinymce_path.'/lang/'.$manager_language.'.inc.php');
		} else {
			include_once($tinymce_path.'/lang/english.inc.php');		
		}
		
		// compressor setting
		if($tinymce_compressor=='enabled'){
			 $compressor_on = "selected='selected'";
			 $compressor_off = "";
		} else if ($tinymce_compressor=='disabled' || empty($tinymce_compressor)){
			 $compressor_on = "";
			 $compressor_off = "selected='selected'";
		}			
		
		$simpleTheme = $tinymce_editor_theme=='simple' ? "selected='selected'" : "" ;
		$advTheme = $tinymce_editor_theme=='advanced' ? " selected='selected'" : "";
		$fullTheme = !isset($tinymce_editor_theme) || $tinymce_editor_theme=='full' ? " selected='selected'" : "";
		$rootrelative = $tinymce_editor_relative_urls=='rootrelative' ? "selected='selected'" : "" ;
		$docrelative = $tinymce_editor_relative_urls=='docrelative' ? "selected='selected'" : "" ;
		$fullpathurl = $tinymce_editor_relative_urls=='fullpathurl' ? "selected='selected'" : "" ;
		$display = $use_editor==1 ? $displayStyle : 'none';		
		$cssSelectors = isset($tinymce_css_selectors) ? htmlspecialchars($tinymce_css_selectors) : "";
		
		return <<<TinyMCE_HTML_Settings
		<table id='editorRow_TinyMCE' style="width:inherit;" border="0" cellspacing="0" cellpadding="3"> 
		  <tr class='row1' style="display: $display;"> 
            <td colspan="2" class="warning" style="color:#707070; background-color:#eeeeee"><h4>{$_lang["tinymce_settings"]}</h4></td> 
          </tr> 
          <tr class='row1' style="display: $display"> 
            <td nowrap class="warning"><b>{$_lang["tinymce_editor_theme_title"]}</b></td> 
            <td>
            <select name="tinymce_editor_theme">
					<option value="simple" $simpleTheme>Simple</option>
					<option value="advanced" $advTheme>Advanced</option>
					<option value="full" $fullTheme>Full Featured</option>
				</select>
			</td> 
          </tr> 
          <tr class='row1' style="display: $display"> 
            <td width="200">&nbsp;</td> 
            <td class='comment'>{$_lang["tinymce_editor_theme_message"]}</td> 
          </tr> 
		  <tr class='row1' style="display: $display"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
		  <tr class='row1' style="display:$display;"> 
			<td nowrap class="warning"><b>{$_lang["tinymce_editor_css_selectors_title"]}</b></td> 
			<td><input onChange="documentDirty=true;" type='text' maxlength='65000' style="width: 300px;" name="tinymce_css_selectors" value="$cssSelectors" /> 
			</td> 
		  </tr> 
		  <tr class='row1' style="display: $display;"> 
			<td width="200">&nbsp;</td> 
			<td class='comment'>{$_lang["tinymce_editor_css_selectors_message"]}</td> 
		  </tr> 
		  <tr class='row1' style="display: $display"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
		  <tr class='row1' style="display:$display;"> 
			<td nowrap class="warning"><b>{$_lang["tinymce_editor_relative_urls_title"]}</b></td> 
			<td>
            <select name="tinymce_editor_relative_urls">
					<option value="docrelative" $docrelative>Document Relative</option>
					<option value="rootrelative" $rootrelative>Root Relative</option>
					<option value="fullpathurl" $fullpathurl>Full Path</option>
			</select>			
			</td> 
		  </tr> 
		  <tr class='row1' style="display: $display;"> 
			<td width="200">&nbsp;</td> 
			<td class='comment'>{$_lang["tinymce_editor_relative_urls_message"]}</td> 
		  </tr> 
		  <tr class='row1' style="display: $display;"> 
			<td colspan="2"><div class='split'></div></td> 
		  </tr> 
		  <tr class='row1' style="display:$display;"> 
			<td nowrap class="warning"><b>{$_lang["tinymce_compressor_title"]}</b></td> 
			<td>
            <select name="tinymce_compressor">
					<option value="enabled" $compressor_on>{$_lang["enabled"]}</option>
					<option value="disabled" $compressor_off>{$_lang["disabled"]}</option>
			</select>			
			</td> 
		  </tr> 
		  <tr class='row1' style="display: $display;"> 
			<td width="200">&nbsp;</td> 
			<td class='comment'>{$_lang["tinymce_compressor_message"]}</td> 
		  </tr> 
		</table>
TinyMCE_HTML_Settings;
	}
}


// getTinyMCEScript function
if (!function_exists('getTinyMCEScript')) {
	function getTinyMCEScript($elmList,$webTheme='',$width='',$height='',$lang='') {
		global $base_url;
		global $use_browser;
		global $editor_css_path;
		global $tinymce_editor_theme;
		global $tinymce_css_selectors;
		global $tinymce_editor_relative_urls;
		global $tinymce_plugins;
		global $tinymce_buttons1;
		global $tinymce_buttons2;
		global $tinymce_buttons3;
		global $tinymce_buttons4;
		global $tinymce_disable_buttons;
		global $tinymce_formats;
		global $tinymce_elements;
		global $tinymce_entity_encoding;
		global $tinymce_entities;
		global $tinymce_compressor;
						
		$scriptfile = ($tinymce_compressor == 'enabled' ? 'tiny_mce_gzip.php' : 'tiny_mce.js');
		$tinymce_editor_theme = $webTheme ? $webTheme : $tinymce_editor_theme;
		$theme = !empty($tinymce_editor_theme) ? "theme : \"$tinymce_editor_theme\"," : "theme : \"simple\",";
		$cssPath = !empty($editor_css_path) ? "content_css : \"$editor_css_path\"," : "";
		$cssSelector = !empty($tinymce_css_selectors) ? "theme_advanced_styles : \"$tinymce_css_selectors\"," : "";
		$elmList = !empty($elmList) ? "elements : \"$elmList\"," : "";
		$fileBrowserCallback = ($use_browser==1 ? "file_browser_callback : \"fileBrowserCallBack\"":"");
		$webWidth = $width ? "width : \"$width\"," : "";
		$webHeight = $height ? "height : \"$height\"," : "";
		$tinymce_language = !empty($lang) ? getTinyMCELang($lang) : getTinyMCELang($manager_language);
		switch($tinymce_editor_relative_urls){
			case "rootrelative":
				$relative_urls = "false";
				$remove_script_host = "true";
			break;
			
			case "docrelative":
				$relative_urls = "true";
				$document_base_url = "document_base_url : \"".$base_url."\",";
				$remove_script_host = "true";
			break;
			
			case "fullpathurl":
				$relative_urls = "false";
				$remove_script_host = "false";
			break;
			
			default:
				$relative_urls = "true";
				$document_base_url = "document_base_url : \"".$base_url."\",";
				$remove_script_host = "true";
		}

		$fullScript = <<<FULL_SCRIPT
<script language="javascript" type="text/javascript" src="{$base_url}assets/plugins/tinymce/jscripts/tiny_mce/{$scriptfile}"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		  theme : "advanced",
		  mode : "exact",
		  relative_urls : {$relative_urls},
		  {$document_base_url}
		  external_link_list_url : "{$base_url}assets/plugins/tinymce/modxLinkList.php",
		  remove_script_host : {$remove_script_host},
		  language : "{$tinymce_language}",
		  $elmList
		  $webWidth
		  $webHeight
		  plugins : "{$tinymce_plugins}",
		  theme_advanced_buttons0 : "",
		  theme_advanced_buttons1 : "{$tinymce_buttons1}",
		  theme_advanced_buttons2 : "{$tinymce_buttons2}",
		  theme_advanced_buttons3 : "{$tinymce_buttons3}",
		  theme_advanced_buttons4 : "{$tinymce_buttons4}",
		  theme_advanced_toolbar_location : "top",
		  theme_advanced_toolbar_align : "left",
		  theme_advanced_path_location : "bottom",
		  theme_advanced_disable : "{$tinymce_disable_buttons}",
		  theme_advanced_blockformats : "{$tinymce_formats}",
		  plugin_insertdate_dateFormat : "%Y-%m-%d",
		  plugin_insertdate_timeFormat : "%H:%M:%S",
		  extended_valid_elements : "{$tinymce_elements}",
		  $cssPath
		  $cssSelector
		  entity_encoding : "{$tinymce_entity_encoding}",
		  $tinymce_entities
		  apply_source_formatting : true,
		  remove_linebreaks : false,
		  button_tile_map : false,
		  onchange_callback : "tvOnTinyMCEChangeCallBack",
		  resource_browser_path : "{$base_url}manager/media/browser/mcpuk/browser.html?Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}",
		  $fileBrowserCallback
	   });
	
	function fileBrowserCallBack(field_name, url, type, win) {
		// This is where you insert your custom filebrowser logic
		var win=tinyMCE.getWindowArg("window");
		win.BrowseServer(field_name);
	}

	function tvOnTinyMCEChangeCallBack(i){
		  i.oldTargetElement.onchange();            
	}
</script>
FULL_SCRIPT;

		$stdScript = <<<STD_SCRIPT
<script language="javascript" type="text/javascript" src="{$base_url}assets/plugins/tinymce/jscripts/tiny_mce/{$scriptfile}"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		  $theme
		  mode : "exact",
		  language : "{$tinymce_language}",
		  $elmList
		  theme_advanced_blockformats : "{$tinymce_formats}",
		  extended_valid_elements : "{$tinymce_elements}",
		  $cssPath
		  $cssSelector
		  entity_encoding : "{$tinymce_entity_encoding}",
		  $tinymce_entities
		  apply_source_formatting : true,
		  remove_linebreaks : false,
		  button_tile_map : false,
		  relative_urls : {$relative_urls},
		  {$document_base_url}
		  remove_script_host : {$remove_script_host}
	   });
</script>
STD_SCRIPT;

		$tinymceScript = !empty($tinymce_editor_theme)?($tinymce_editor_theme == 'full' ? $fullScript : $stdScript):$fullScript;
		return $tinymceScript;
	}
}

// Handle event

$e = &$modx->Event; 
switch ($e->name) { 
	case "OnRichTextEditorRegister": // register only for backend
		$e->output("TinyMCE");
		break;

	case "OnRichTextEditorInit": 
		if($editor=="TinyMCE") {
			$elementList = implode(",", $elements);
			if(isset($forfrontend)||$modx->isFrontend()){
				$html = getTinyMCEScript($elementList,$webTinyMCETheme,$width,$height,$frontend_language);
			} else {
				$html = getTinyMCEScript($elementList);
			}
			$e->output($html);
		}		
		break;

	case "OnInterfaceSettingsRender":
		$html = getTinyMCESettings();
		$e->output($html);
		break;

   default :    
      return; // stop here - this is very important. 
      break; 
}