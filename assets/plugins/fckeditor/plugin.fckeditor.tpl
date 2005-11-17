/**
 * FCKEditor - RichText Editor Plugin
 * Written By Raymond Irving - June 22, 2005
 * Modified By Jeff Whitfield - October 13, 2005
 *
 * Both frontend and backend interface provided
 *
 * Configuration:
 * &webset=Web Toolbars;string;['Bold','Italic','Underline','-','Link','Unlink']
 *
 * Version 2.1.1rev1
 * FCKeditor v2.1.1
 *
 */

// When used from the web front-end 
// FCK will display the selected toolbar or custom buttons
$webToolbarSet = isset($webset) ? $webset:"basic";
$webCustomToolbar = isset($webcustom) && ($webset == "custom") ? $webcustom:"";

// getFCKEditorSettings function
if (!function_exists('getFCKEditorSettings')) {
	function getFCKEditorSettings() {
		global $_lang;
		global $use_editor;
		global $displayStyle;
		global $fck_editor_style;
		global $fck_editor_toolbar;
		global $fck_editor_toolbar_customset;
		global $fck_editor_autolang;

		// language settings
		$_lang['FCKEditor_settings'] = "FCKEditor Settings";
		$_lang['fck_editor_style_title'] = "XML Style:";
		$_lang["fck_editor_style_message"] = "Enter the path and file name to the FCKEditor xml style selector file.The best way to enter the path is to enter the path from the root of your server, for example: /assets/plugins/fckeditor/fckstyles.xml. If you do not wish to load a stylesheet into the editor, leave this field blank.";
		$_lang['fck_editor_toolbar_title'] = "Toolbar set:";
		$_lang['fck_editor_toolbar_message'] = "Here you can select which toolbar set to use with FCKEditor.  Choose Basic for limited options, Standard for more options,  Advance for all the available options or Custom to customize your toolbar.";
		$_lang['fck_editor_custom_toolbar'] = "Custom toolbar:";
		$_lang['fck_editor_custom_message'] = "Use this option to customize the toolbar set for the FCKEditor. Here you should enter the javascript syntax supported by the editor. For Example, use ['Bold','Italic','-','Link'] to display the Bold, Italic and Link icons . Each icon must be separated by a comma (,) and grouped using the [] bracket.";
		$_lang['fck_editor_autolang_title'] = "Auto Language:";
		$_lang['fck_editor_autolang_message'] = "Select the 'Yes' option to have the FCKEditor automatically detect the language used by the browser and load the appropriate language files. FCKEditor language files must be added to the 'assets/plugins/fckeditor/editor/lang' folder";

		$display = $use_editor==1 ? $displayStyle : 'none';
		$cusDisplay = $use_editor==1 && $fck_editor_toolbar=='custom' ? $displayStyle : 'none';
		
		$basTool = !isset($fck_editor_toolbar) || $fck_editor_toolbar=='default' ? "selected='selected'" : "";
		$stnTool = $fck_editor_toolbar=='standard' ? "selected='selected'" : "";
		$advTool = $fck_editor_toolbar=='advanced' ? "selected='selected'" : "";
		$cusTool = $fck_editor_toolbar=='custom' ? "selected='selected'" : "";
		$tbCustomset = isset($fck_editor_toolbar_customset) ? htmlspecialchars($fck_editor_toolbar_customset) : "['Bold','Italic','Underline','-','Link','Unlink']";
		$xmlStyle = isset($fck_editor_style) ? htmlspecialchars($fck_editor_style) : "";
		$autoLang = isset($fck_editor_autolang) ? $fck_editor_autolang : 0;
		$autoNo = ($fck_editor_autolang=='0' || !isset($fck_editor_autolang)) ? 'checked="checked"' : '';
		$autoYes = $fck_editor_autolang=='1' ? 'checked="checked"' : '';

		return <<<FCKEditor_HTML_Settings
		<table id='editorRow_FCKEditor' style="width:inherit;" border="0" cellspacing="0" cellpadding="3"> 
		  <tr class='row1' style="display: $display;"> 
            <td colspan="2" class="warning" style="color:#707070; background-color:#eeeeee"><h4>{$_lang["FCKEditor_settings"]}<h4></td> 
          </tr> 
          <tr class='row1' style="display: $display"> 
            <td nowrap class="warning"><b>{$_lang["fck_editor_autolang_title"]}</b></td> 
            <td> <input onChange="documentDirty=true;" type="radio" name="fck_editor_autolang" value="1" $autoYes /> 
              {$_lang['yes']}<br /> 
              <input onChange="documentDirty=true;" type="radio" name="fck_editor_autolang" value="0" $autoNo /> 
              {$_lang['no']} </td> 
          </tr> 
          <tr class='row1' style="display: $display"> 
            <td width="200">&nbsp;</td> 
            <td class='comment'>{$_lang["fck_editor_autolang_message"]}</td> 
          </tr> 
		  <tr class='row1' style="display: $display"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
          
          <tr class='row1' style="display: $display"> 
            <td nowrap class="warning"><b>{$_lang["fck_editor_style_title"]}</b></td> 
            <td><input onChange="documentDirty=true;" type='text' maxlength='255' style="width: 300px;" name="fck_editor_style" value="$xmlStyle" /> 
			</td> 
          </tr> 
          <tr class='row1' style="display: $display"> 
            <td width="200">&nbsp;</td> 
            <td class='comment'>{$_lang["fck_editor_style_message"]}</td> 
          </tr> 
		  <tr class='row1' style="display: $display"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
          <tr class='row1' style="display: $display"> 
            <td nowrap class="warning"><b>{$_lang["fck_editor_toolbar_title"]}</b></td> 
            <td>
            <select name="fck_editor_toolbar" onChange="documentDirty=true;if(this.selectedIndex==3) showHide(/fck_customset/,1); else showHide(/fck_customset/,0);">
					<option value="basic" $basTool>{$_lang["basic"]}</option>
					<option value="standard" $stnTool>{$_lang["standard"]}</option>
					<option value="advanced" $advTool>{$_lang["advanced"]}</option>
					<option value="custom" $cusTool>{$_lang["custom"]}</option>
				</select>
			</td> 
          </tr> 
          <tr class='row1' style="display: $display"> 
            <td width="200">&nbsp;</td> 
            <td class='comment'>{$_lang["fck_editor_toolbar_message"]}</td> 
          </tr> 
		  <tr class='row1' style="display: $display"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
          <tr id='fck_customset1' class='row3' style="display: $cusDisplay"> 
            <td nowrap class="warning"><b>{$_lang["fck_editor_custom_toolbar"]}</b></td> 
            <td>
            <input name="fck_editor_toolbar_customset" type="text" style="width:300px;" maxlength='65000' onChange="documentDirty=true;" value="$tbCustomset" />
			</td> 
          </tr> 
          <tr id='fck_customset2' class='row3' style="display: $cusDisplay"> 
            <td width="200">&nbsp;</td> 
            <td class='comment'>{$_lang["fck_editor_custom_message"]}</td> 
          </tr> 
		  <tr id='fck_customset3' class='row3' style="display: $cusDisplay"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
		</table>
FCKEditor_HTML_Settings;
	}
}


// getFCKEditorScript function
if (!function_exists('getFCKEditorScript')) {
	function getFCKEditorScript($elmList,$tbWebSet='',$tbCustomSet='',$width='100%',$height='400') {
		global $base_url;
		global $site_url;
		global $use_browser;
		global $editor_css_path;
		global $fck_editor_toolbar;
		global $fck_editor_toolbar_customset;
		global $fck_editor_autolang;
		
		$toolbar = $tbCustomSet ? "custom" : ($tbWebSet ? $tbWebSet : $fck_editor_toolbar);
		$tbCustomSet = "[ ".($tbCustomSet ? $tbCustomSet:$fck_editor_toolbar_customset)." ]"; // remember [[snippets]] detection :)
		$autoLang = $fck_editor_autolang ? 'true': 'false';
		$editor_css_path = !empty($editor_css_path) ? $editor_css_path : $base_url."assets/plugins/fckeditor/editor/css/fck_editorarea.css";
		
		$width = str_replace("px","",$width);
		$height = str_replace("px","",$height);
		
		// build fck instances
		foreach($elmList as $fckInstance) {
			$fckInstanceObj = "oFCK" . $fckInstance;
			$fckInstances .= "<script language='javascript' type='text/javascript'>".
					"var $fckInstanceObj = new FCKeditor('$fckInstance');".
					"$fckInstanceObj.Width = '".$width."';".
					"$fckInstanceObj.Height = '".$height."';".
					"$fckInstanceObj.BaseHref = '".$site_url."';".
					"$fckInstanceObj.BasePath = '".$base_url."assets/plugins/fckeditor/';".
					"$fckInstanceObj.Config['ImageBrowser'] = ".($use_browser==1 ? "true":"false").";".
					"$fckInstanceObj.Config['ImageBrowserURL'] = FCKImageBrowserURL;".
					"$fckInstanceObj.Config['LinkBrowser'] = ".($use_browser==1 ? "true":"false").";".
					"$fckInstanceObj.Config['LinkBrowserURL'] = FCKLinkBrowserURL;".
					"$fckInstanceObj.Config['FlashBrowser'] = ".($use_browser==1 ? "true":"false").";".
					"$fckInstanceObj.Config['FlashBrowserURL'] = FCKFlashBrowserURL;".
					"$fckInstanceObj.Config['SpellChecker'] = 'SpellerPages';".
					"$fckInstanceObj.Config['CustomConfigurationsPath'] = '".$base_url."assets/plugins/fckeditor/custom_config.js';".
					"$fckInstanceObj.ToolbarSet = '".$toolbar."';".
					"$fckInstanceObj.Config['EditorAreaCSS'] = FCKEditorAreaCSS;".
					"$fckInstanceObj.ReplaceTextarea();".
					"</script>\n";
		}
		return <<<FCKEditor_SCRIPT
		<script language="javascript" type="text/javascript" src="{$base_url}assets/plugins/fckeditor/fckeditor.js"></script>
		<script language="javascript" type="text/javascript">
			var FCKImageBrowserURL = '{$base_url}manager/media/browser/mcpuk/browser.html?Type=images&Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}';
			var FCKLinkBrowserURL = '{$base_url}manager/media/browser/mcpuk/browser.html?Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}';
			var FCKFlashBrowserURL = '{$base_url}manager/media/browser/mcpuk/browser.html?Type=flash&Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}';
			var FCKCustomToolbarSet = {$tbCustomSet};
			var FCKAutoLanguage = {$autoLang};
			var FCKEditorAreaCSS = '{$editor_css_path}';
			function FCKeditor_OnComplete(edtInstance) {
				if (edtInstance){ // to-do: add better listener
					edtInstance.AttachToOnSelectionChange(tvOnFCKChangeCallback);
				}
			};
			
			function tvOnFCKChangeCallback(edtInstance) {
				if (edtInstance) {
					elm = edtInstance.LinkedField;
					if(elm && elm.onchange) elm.onchange();
				}
			}
		</script>
		$fckInstances
FCKEditor_SCRIPT;
	}
}


// Handle event

$e = &$modx->Event; 
switch ($e->name) { 
	case "OnRichTextEditorRegister":
		$e->output("FCKEditor");
		break;

	case "OnRichTextEditorInit":
		if($editor=="FCKEditor") {
			if(isset($forfrontend)||$modx->isFrontend()) $html = getFCKEditorScript($elements,$webToolbarSet,$webCustomToolbar,$width,$height);
			else $html = getFCKEditorScript($elements);
			$e->output($html);
		}		
		break;

	case "OnInterfaceSettingsRender":
		$html = getFCKEditorSettings();
		$e->output($html);
		break;

   default :    
      return; // stop here - this is very important. 
      break; 
}