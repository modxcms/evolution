<?php
//TinyMCE RichText Editor Plugin v3.2.4.1

// getTinyMCESettings function
if (!function_exists('getTinyMCESettings')) {
	function getTinyMCESettings($_lang, $path, $manager_language='english', $use_editor, $theme, $css, $plugins, $buttons1, $buttons2, $buttons3, $buttons4, $displayStyle, $action) {
		// language settings
		if (! @include_once($path.'/lang/'.$manager_language.'.inc.php')){
		  include_once($path.'/lang/english.inc.php');
		}
		// Check for previous 'full' theme setting for backwards compatibility 
		if($theme == "full"){
		    $theme == "editor";
		}
		
		if($action == 11 || $action == 12){ 
		    $themeOptions .= "					<option value=\"\">".$_lang['tinymce_theme_global_settings']."</option>\n";
		}
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
	function getTinyMCEScript($elmList, $theme='simple', $width, $height, $language='en', $frontend, $base_url, $plugins, $buttons1, $buttons2, $buttons3, $buttons4, $disabledButtons, $blockFormats, $entity_encoding, $entities, $pathoptions, $cleanup, $resizing, $css_path, $css_selectors, $use_browser, $toolbar_align, $advimage_styles, $advlink_styles, $linklist, $customparams, $site_url, $tinyURL, $webuser) {
		// Set theme
		if($theme == "editor" || $theme == "custom" || $theme == "full"){
			$tinyTheme = "advanced";
			if(($theme == "editor" || $theme == "full") || ($theme == "custom" && (empty($plugins) || empty($buttons1)))){
				$blockFormats = "p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre,address";
				$plugins = "style,advimage,advlink,searchreplace,print,contextmenu,paste,fullscreen,nonbreaking,xhtmlxtras,visualchars,media";
				$buttons1 = "undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,link,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help";
				$buttons2 = "bold,italic,underline,strikethrough,sub,sup,separator,blockquote,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops";
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
				$convert_urls = true;
				$remove_script_host = "true";
				$document_base_url = "		  document_base_url : \"".$site_url."\",\n";
			break;
			
			case "docrelative":
				$relative_urls = "true";
				$convert_urls = true;
				$document_base_url = "		  document_base_url : \"".$site_url."\",\n";
				$remove_script_host = "true";
			break;
			
			case "fullpathurl":
				$relative_urls = "false";
				$remove_script_host = "false";
			break;
			
			default:
				$relative_urls = "true";
				$document_base_url = "		  document_base_url : \"".$site_url."\",\n";
				$remove_script_host = "true";
		}		
        		
		$cssPath = !empty($css_path) ? "		  content_css : \"".$css_path."\",\n" : "";
		$cssSelector = !empty($css_selectors) ? "		  theme_advanced_styles : \"".$css_selectors."\",\n" : "";
		$elmList = !empty($elmList) ? "		  elements : \"".$elmList."\",\n" : "";
		
		// Build init options
		$tinymceInit .= "		  theme : \"".$tinyTheme."\",\n";
		$tinymceInit .= "		  mode : \"exact\",\n";		
		$tinymceInit .= $width ? "		  width : \"".str_replace("px", "", $width)."\",\n" : "";
		$tinymceInit .= $height ? "		  height : \"".str_replace("px", "", $height)."\",\n" : "";
		$tinymceInit .= "		  relative_urls : ".$relative_urls.",\n";
		$tinymceInit .= $document_base_url;
		$tinymceInit .= "		  remove_script_host : ".$remove_script_host.",\n";
		$tinymceInit .= $convert_urls == false ? "		  convert_urls : false,\n":"";
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

		// Advanced options		
		if($theme == "editor" || $theme == "custom"){
			if($frontend=='false' || ($frontend=='true' && $webuser)){
				$tinymceInit .= ($use_browser==1 ? "		  file_browser_callback : \"myFileBrowser\",\n":"");

$tinyCallback = <<<TINY_CALLBACK
	function myFileBrowser (field_name, url, type, win) {
	    if (type == 'media') {type = win.document.getElementById('media_type').value;}		
		var cmsURL = '{$base_url}manager/media/browser/mcpuk/browser.php?Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}&editor=tinymce3&editorpath={$tinyURL}';    // script URL - use an absolute path!
		switch (type) {
			case "image":
				type = 'images';
				break;
			case "media":
            case "qt":
            case "wmp":
            case "rmp":
                type = 'media';
				break;
            case "shockwave":
			case "flash": 
                type = 'flash';
				break;
			case "file":
				type = 'files';
				break;
			default:
				return false;
		}
		if (cmsURL.indexOf("?") < 0) {
		    //add the type as the only query parameter
		    cmsURL = cmsURL + "?type=" + type;
		}
		else {
		    //add the type as an additional query parameter
		    // (PHP session ID is now included if there is one at all)
		    cmsURL = cmsURL + "&type=" + type;
		}
		
		var windowManager = tinyMCE.activeEditor.windowManager.open({
		    file : cmsURL,
		    width : screen.width * 0.7,  // Your dimensions may differ - toy around with them!
		    height : screen.height * 0.7,
		    resizable : "yes",
		    inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
		    close_previous : "no"
		}, {
		    window : win,
		    input : field_name
		});
		if (window.focus) {windowManager.focus()}
		return false;
	}
TINY_CALLBACK;
				
			}
			if($frontend=='false'){
				$tinymceInit .= ($linklist == 'enabled') ? "		  external_link_list_url : \"".$tinyURL."/tinymce.linklist.php\",\n" : "";			
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
			if(!empty($customparams)){
			    $params = explode(",",$customparams);
			    $paramsCount = count($params);
        		for ($i=0;$i<$paramsCount;$i++) {
        			if(!empty($params[$i])){
        				$tinymceInit .= "		  ".trim($params[$i]).",\n";
        			}
        		}			    
			}
		}		
		if($frontend=='false'){
			$tinymceInit .= "		  onchange_callback : \"myCustomOnChangeHandler\",\n";			
		}
		$tinymceInit .= "		  button_tile_map : false \n";

$script = <<<TINY_SCRIPT
<script language="javascript" type="text/javascript" src="{$tinyURL}/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="{$tinyURL}/xconfig.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
{$tinymceInit}
	});
{$tinyCallback}
function myCustomOnChangeHandler() {
	documentDirty = true;
}
</script>
TINY_SCRIPT;

		return $script;
	}
}
?>