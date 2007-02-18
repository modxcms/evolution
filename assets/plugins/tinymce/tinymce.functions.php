<?php
// getTinyMCESettings function
if (!function_exists('getTinyMCESettings')) {
	function getTinyMCESettings($_lang, $path, $manager_language='english', $use_editor, $theme, $css, $plugins, $buttons1, $buttons2, $buttons3, $buttons4, $displayStyle) {
		// language settings
		include_once($path.'/lang/'.$manager_language.'.inc.php');
		
		$arrThemes[] = array("simple",$_lang['tinymce_theme_simple']);
		$arrThemes[] = array("advanced",$_lang['tinymce_theme_advanced']);
		$arrThemes[] = array("editor",$_lang['tinymce_theme_editor']);
		$arrThemes[] = array("custom",$_lang['tinymce_theme_custom']);
		$arrThemesCount = count($arrThemes);
		for ($i=0;$i<$arrThemesCount;$i++) {
				$themeOptions .= "					<option value=\"".$arrThemes[$i][0]."\"".($arrThemes[$i][0] == $theme ? " selected=\"selected\"" : "").">".$arrThemes[$i][1]."</option>\n";
		}
		
		$display = $use_editor==1 ? $displayStyle : 'none';
		$css = isset($css) ? htmlspecialchars($css) : "";
		
		return <<<TINYMCE_HTML
		<table id='editorRow_TinyMCE' style="width:inherit;" border="0" cellspacing="0" cellpadding="3"> 
		  <tr class='row1' style="display: $display;"> 
            <td colspan="2" class="warning" style="color:#707070; background-color:#eeeeee"><h4>{$_lang["tinymce_settings"]}</h4></td> 
          </tr> 
          <tr class='row1' style="display: $display"> 
            <td nowrap class="warning"><b>{$_lang["tinymce_editor_theme_title"]}</b></td> 
            <td>
            <select name="tinymce_editor_theme">
{$themeOptions}
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
			<td nowrap class="warning"><b>{$_lang["tinymce_editor_custom_plugins_title"]}</b></td> 
			<td><input onChange="documentDirty=true;" type='text' maxlength='65000' style="width: 300px;" name="tinymce_custom_plugins" value="$plugins" /> 
			</td> 
		  </tr> 
		  <tr class='row1' style="display: $display;"> 
			<td width="200">&nbsp;</td> 
			<td class='comment'>{$_lang["tinymce_editor_custom_plugins_message"]}</td> 
		  </tr> 
		  <tr class='row1' style="display: $display"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
		  <tr class='row1' style="display:$display;"> 
			<td nowrap class="warning" valign="top"><b>{$_lang["tinymce_editor_custom_buttons_title"]}</b></td> 
			<td>
			Row 1: <input onChange="documentDirty=true;" type='text' maxlength='65000' style="width: 300px;" name="tinymce_custom_buttons1" value="$buttons1" /><br/> 
			Row 2: <input onChange="documentDirty=true;" type='text' maxlength='65000' style="width: 300px;" name="tinymce_custom_buttons2" value="$buttons2" /><br/> 
			Row 3: <input onChange="documentDirty=true;" type='text' maxlength='65000' style="width: 300px;" name="tinymce_custom_buttons3" value="$buttons3" /><br/>
			Row 4: <input onChange="documentDirty=true;" type='text' maxlength='65000' style="width: 300px;" name="tinymce_custom_buttons4" value="$buttons4" /> 
			</td> 
		  </tr> 
		  <tr class='row1' style="display: $display;"> 
			<td width="200">&nbsp;</td> 
			<td class='comment'>{$_lang["tinymce_editor_custom_buttons_message"]}</td> 
		  </tr> 
		  <tr class='row1' style="display: $display"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
		  <tr class='row1' style="display:$display;"> 
			<td nowrap class="warning"><b>{$_lang["tinymce_editor_css_selectors_title"]}</b></td> 
			<td><input onChange="documentDirty=true;" type='text' maxlength='65000' style="width: 300px;" name="tinymce_css_selectors" value="$css" /> 
			</td> 
		  </tr> 
		  <tr class='row1' style="display: $display;"> 
			<td width="200">&nbsp;</td> 
			<td class='comment'>{$_lang["tinymce_editor_css_selectors_message"]}</td> 
		  </tr> 
		</table>
TINYMCE_HTML;
	}
}

// getTinyMCEScript function
if (!function_exists('getTinyMCEScript')) {
	function getTinyMCEScript($elmList, $theme='simple', $width, $height, $language='en', $frontend, $base_url, $plugins, $buttons1, $buttons2, $buttons3, $buttons4, $disabledButtons, $blockFormats, $entity_encoding, $entities, $compressor, $pathoptions, $cleanup, $resizing,  $css_path, $css_selectors, $use_browser, $toolbar_align, $advimage_styles, $advlink_styles) {
		// Set theme
		if($theme == "editor" || $theme == "custom"){
			$tinyTheme = "advanced";
			if($theme == "editor" || ($theme == "custom" && (empty($plugins) || empty($buttons1)))){
				$blockFormats = "p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre,address";
				$plugins = "text;style,advimage,advlink,searchreplace,print,contextmenu,paste,fullscreen,noneditable,nonbreaking,xhtmlxtras,visualchars,media";
				$buttons1 = "undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,link,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help";
				$buttons2 = "bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops";
				$buttons3 = ""; 
				$buttons4 = ""; 			
		    }
		} else {
			$tinyTheme = $theme;
		}
		
		// Set relative URL options
		switch($pathoptions){
			case "rootrelative":
				$relative_urls = "false";
				$remove_script_host = "true";
			break;
			
			case "docrelative":
				$relative_urls = "true";
				$document_base_url = "		  document_base_url : \"".$base_url."\",\n";
				$remove_script_host = "true";
			break;
			
			case "fullpathurl":
				$relative_urls = "false";
				$remove_script_host = "false";
			break;
			
			default:
				$relative_urls = "true";
				$document_base_url = "		  document_base_url : \"".$base_url."\",\n";
				$remove_script_host = "true";
		}		
        		
		$cssPath = !empty($css_path) ? "		  content_css : \"".$css_path."\",\n" : "";
		$cssSelector = !empty($css_selectors) ? "		  theme_advanced_styles : \"".$css_selectors."\",\n" : "";
		$elmList = !empty($elmList) ? "		  elements : \"".$elmList."\",\n" : "";
		
		// Build init options
		$tinymceInit .= "		  theme : \"".$tinyTheme."\",\n";
		$tinymceInit .= "		  mode : \"exact\",\n";		
		$tinymceInit .= $width ? "		  width : \"".$width."\",\n" : "";
		$tinymceInit .= $height ? "		  height : \"".$height."\",\n" : "";
		$tinymceInit .= "		  relative_urls : ".$relative_urls.",\n";
		$tinymceInit .= $document_base_url;
		$tinymceInit .= "		  remove_script_host : ".$remove_script_host.",\n";
		$tinymceInit .= "		  language : \"".$language."\",\n";
		$tinymceInit .= $elmList;
		$tinymceInit .= "		  valid_elements : tinymce_valid_elements,\n";
		$tinymceInit .= "		  extended_valid_elements : tinymce_extended_valid_elements,\n";
		$tinymceInit .= "		  invalid_elements : tinymce_invalid_elements,\n";
		$tinymceInit .= $cssPath;
		$tinymceInit .= "		  entity_encoding : \"".$entity_encoding."\",\n";
		$tinymceInit .= ($entity_encoding == "named" && !empty($entities)) ? "		  entities : \"".$entities."\",\n" :"";
		$tinymceInit .= "		  cleanup: ".(($cleanup == "enabled" || empty($cleanup)) ? "true" : "false").",\n";
		$tinymceInit .= "		  apply_source_formatting : true,\n";
		$tinymceInit .= "		  remove_linebreaks : false,\n";
		$tinymceInit .= "		  convert_fonts_to_spans : \"true\",\n";
		$tinymceInit .= "		  onchange_callback : \"tvOnTinyMCEChangeCallBack\",\n";		

		// Advanced options		
		if($theme == "editor" || $theme == "custom"){
			if($frontend=='false'){
				$tinymceInit .= "		  external_link_list_url : \"".$base_url."assets/plugins/tinymce/tinymce.linklist.php\",\n";
				$tinymceInit .= ($use_browser==1 ? "		  resource_browser_path : \"".$base_url."manager/media/browser/mcpuk/browser.html?Connector=".$base_url."manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath=".$base_url."\",\n" : "");
				$tinymceInit .= ($use_browser==1 ? "		  file_browser_callback : \"fileBrowserCallBack\",\n":"");

$tinyCallback = <<<TINY_CALLBACK
	function fileBrowserCallBack(field_name, url, type, win) {
		// This is where you insert your custom filebrowser logic
		var win=tinyMCE.getWindowArg("window");
		win.BrowseServer(field_name);
	}
TINY_CALLBACK;
			
			}
			
			if(isset($blockFormats)){$tinymceInit .= "		  theme_advanced_blockformats : \"".$blockFormats."\",\n";}
			$tinymceInit .= $cssSelector;
			$tinymceInit .= "		  plugins : \"".$plugins."\",\n";
			$tinymceInit .= "		  theme_advanced_buttons0 : \"\",\n";
			$tinymceInit .= "		  theme_advanced_buttons1 : \"".$buttons1."\",\n";
			$tinymceInit .= "		  theme_advanced_buttons2 : \"".$buttons2."\",\n";
			$tinymceInit .= "		  theme_advanced_buttons3 : \"".$buttons3."\",\n";
			$tinymceInit .= "		  theme_advanced_buttons4 : \"".$buttons4."\",\n";
			$tinymceInit .= "		  theme_advanced_toolbar_location : \"top\",\n";
			$tinymceInit .= "		  theme_advanced_toolbar_align : \"".($toolbar_align =="rtl" ? "right" : "left")."\",\n";
			$tinymceInit .= "		  theme_advanced_path_location : \"bottom\",\n";
			$tinymceInit .= "		  theme_advanced_disable : \"".$disabledButtons."\",\n";
			$tinymceInit .= "		  theme_advanced_resizing : ".(!empty($resizing) ? $resizing : "false").",\n";
			$tinymceInit .= "		  theme_advanced_resize_horizontal : false,\n";
			$tinymceInit .= (!empty($advimage_styles) ? "		  advimage_styles : \"".$advimage_styles."\",\n" : "");
			$tinymceInit .= (!empty($advlink_styles) ? "		  advlink_styles : \"".$advlink_styles."\",\n" : "");
			$tinymceInit .= "		  plugin_insertdate_dateFormat : \"%Y-%m-%d\",\n";
			$tinymceInit .= "		  plugin_insertdate_timeFormat : \"%H:%M:%S\",\n";			
		}
		
		$tinymceInit .= "		  button_tile_map : false \n";
		$scriptfile = (($frontend=='false' && $compressor == 'enabled') ? 'tiny_mce_gzip.php' : 'tiny_mce.js');

$script = <<<TINY_SCRIPT
<script language="javascript" type="text/javascript" src="{$base_url}assets/plugins/tinymce/jscripts/tiny_mce/{$scriptfile}"></script>
<script language="javascript" type="text/javascript" src="{$base_url}assets/plugins/tinymce/xconfig.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
{$tinymceInit}
	});
{$tinyCallback}
	function tvOnTinyMCEChangeCallBack(i){
		  i.oldTargetElement.onchange();            
	}
</script>
TINY_SCRIPT;

		return $script;
	}
}
?>