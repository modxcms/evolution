// Import theme specific language pack
tinyMCE.importThemeLanguagePack();

var TinyMCE_advanced_autoImportCSSClasses = true;

function TinyMCE_advanced_getButtonHTML(button_name) {
	switch (button_name) {
		case "bold":
			return '<img id="{$editor_id}_bold" src="{$themeurl}/images/{$lang_bold_img}" title="{$lang_bold_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'Bold\')">';

		case "italic":
			return '<img id="{$editor_id}_italic" src="{$themeurl}/images/{$lang_italic_img}" title="{$lang_italic_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'Italic\')">';

		case "underline":
			return '<img id="{$editor_id}_underline" src="{$themeurl}/images/underline.gif" title="{$lang_underline_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'Underline\')">';

		case "strikethrough":
			return '<img id="{$editor_id}_strikethrough" src="{$themeurl}/images/strikethrough.gif" title="{$lang_striketrough_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'Strikethrough\')">';

		case "justifyleft":
			return '<img id="{$editor_id}_left" src="{$themeurl}/images/left.gif" title="{$lang_justifyleft_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'JustifyLeft\')">';

		case "justifycenter":
			return '<img id="{$editor_id}_center" src="{$themeurl}/images/center.gif" title="{$lang_justifycenter_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'JustifyCenter\')">';

		case "justifyright":
			return '<img id="{$editor_id}_right" src="{$themeurl}/images/right.gif" title="{$lang_justifyright_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'JustifyRight\')">';

		case "justifyfull":
			return '<img id="{$editor_id}_full" src="{$themeurl}/images/full.gif" title="{$lang_justifyfull_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'JustifyFull\')">';

		case "styleselect":
			return '<select id="{$editor_id}_styleSelect" name="{$editor_id}_styleSelect" onchange="tinyMCE.execCommand(\'mceSetCSSClass\',false,this.options[this.selectedIndex].value);" class="mceSelectList">{$style_select_options}</select>';

		case "bullist":
			return '<img id="{$editor_id}_bullist" src="{$themeurl}/images/bullist.gif" title="{$lang_bullist_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'InsertUnorderedList\')">';

		case "numlist":
			return '<img id="{$editor_id}_numlist" src="{$themeurl}/images/numlist.gif" title="{$lang_numlist_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'InsertOrderedList\')">';

		case "outdent":
			return '<img id="{$editor_id}_outdent" src="{$themeurl}/images/outdent.gif" title="{$lang_outdent_desc}" width="20" height="20" class="mceButtonDisabled" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'Outdent\')">';

		case "indent":
			return '<img src="{$themeurl}/images/indent.gif" title="{$lang_indent_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'Indent\')">';

		case "undo":
			return '<img src="{$themeurl}/images/undo.gif" title="{$lang_undo_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'Undo\')">';

		case "redo":
			return '<img src="{$themeurl}/images/redo.gif" title="{$lang_redo_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'Redo\')">';

		case "link":
			return '<img id="{$editor_id}_link" src="{$themeurl}/images/link.gif" title="{$lang_link_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceLink\', true)">';

		case "unlink":
			return '<img src="{$themeurl}/images/unlink.gif" title="{$lang_unlink_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'unlink\')">';

		case "image":
			return '<img id="{$editor_id}_image" src="{$themeurl}/images/image.gif" title="{$lang_image_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceImage\', true)">';

		case "cleanup":
			return '<img src="{$themeurl}/images/cleanup.gif" title="{$lang_cleanup_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceCleanup\')">';

		case "help":
			return '<img src="{$themeurl}/images/help.gif" title="{$lang_help_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceHelp\')">';

		case "code":
			return '<img id="{$editor_id}_code" src="{$themeurl}/images/code.gif" title="{$lang_theme_code_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="TinyMCE_advanced_openHTMLSourceEditor();">';

		case "table":
			return '<img id="{$editor_id}_table" src="{$themeurl}/images/table.gif" title="{$lang_theme_table_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceInsertTable\',true);">';

		case "row_before":
			return '<img id="{$editor_id}_table_insert_row_before" src="{$themeurl}/images/table_insert_row_before.gif" title="{$lang_theme_table_insert_row_before_desc}" width="20" height="20" class="mceButtonDisabled" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceTableInsertRowBefore\');">';

		case "row_after":
			return '<img id="{$editor_id}_table_insert_row_after" src="{$themeurl}/images/table_insert_row_after.gif" title="{$lang_theme_table_insert_row_after_desc}" width="20" height="20" class="mceButtonDisabled" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceTableInsertRowAfter\');">';

		case "delete_row":
			return '<img id="{$editor_id}_table_delete_row" src="{$themeurl}/images/table_delete_row.gif" title="{$lang_theme_table_delete_row_desc}" width="20" height="20" class="mceButtonDisabled" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceTableDeleteRow\');">';

		case "col_before":
			return '<img id="{$editor_id}_table_insert_col_before" src="{$themeurl}/images/table_insert_col_before.gif" title="{$lang_theme_table_insert_col_before_desc}" width="20" height="20" class="mceButtonDisabled" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceTableInsertColBefore\');">';

		case "col_after":
			return '<img id="{$editor_id}_table_insert_col_after" src="{$themeurl}/images/table_insert_col_after.gif" title="{$lang_theme_table_insert_col_after_desc}" width="20" height="20" class="mceButtonDisabled" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceTableInsertColAfter\');">';

		case "delete_col":
			return '<img id="{$editor_id}_table_delete_col" src="{$themeurl}/images/table_delete_col.gif" title="{$lang_theme_table_delete_col_desc}" width="20" height="20" class="mceButtonDisabled" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceTableDeleteCol\');">';

		case "hr":
			return '<img id="{$editor_id}_hr" src="{$themeurl}/images/hr.gif" title="{$lang_theme_hr_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'inserthorizontalrule\');">';

		case "removeformat":
			return '<img id="{$editor_id}_removeformat" src="{$themeurl}/images/removeformat.gif" title="{$lang_theme_removeformat_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'removeformat\');">';

		case "sub":
			return '<img id="{$editor_id}_sub" src="{$themeurl}/images/sub.gif" title="{$lang_theme_sub_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'subscript\');">';

		case "sup":
			return '<img id="{$editor_id}_sup" src="{$themeurl}/images/sup.gif" title="{$lang_theme_sup_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'superscript\');">';

		case "custom1":
			return '<img id="{$editor_id}_custom1" src="{$themeurl}/images/custom_1.gif" title="{$lang_theme_custom1_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execCommand(\'mceRemoveEditor\',false,\'{$editor_id}\');">';

		case "separator":
			return '<img src="{$themeurl}/images/spacer.gif" width="1" height="15" class="mceSeparatorLine">';
	}

	return "";
}

function TinyMCE_advanced_getEditorTemplate(settings) {
	var template = new Array();

	template['html'] = '\
	<table class="mceEditor" border="0" cellpadding="0" cellspacing="0" width="{$width}" height="{$height}">\
	<tr><td align="center">\
	<iframe id="{$editor_id}" class="mceEditorArea" border="1" frameborder="0" src="{$default_document}" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" style="width:{$area_width};height:{$area_height}" width="{$area_width}" height="{$area_height}"></iframe>\
	</td></tr>\
	<tr><td class="mceToolbar" align="left" height="1">';

	// Render row 1
	var buttonNamesRow1 = tinyMCE.getParam("theme_advanced_buttons1", "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect").split(',');
	for (var i=0; i<buttonNamesRow1.length; i++)
		template['html'] += TinyMCE_advanced_getButtonHTML(buttonNamesRow1[i]);

	//if (buttonNamesRow1.length > 0)
	//	template['html'] += "<br>";

	// Render row 2
	var buttonNamesRow2 = tinyMCE.getParam("theme_advanced_buttons2", "bullist,numlist,separator,outdent,indent,separator,undo,redo,separator,link,unlink,image,cleanup,code").split(',');
	for (var i=0; i<buttonNamesRow2.length; i++)
		template['html'] += TinyMCE_advanced_getButtonHTML(buttonNamesRow2[i]);

	//if (buttonNamesRow2.length > 0)
		template['html'] += "<br>";

	// Render row 3
	var buttonNamesRow3 = tinyMCE.getParam("theme_advanced_buttons3", "table,separator,row_before,row_after,delete_row,separator,col_before,col_after,delete_col,separator,hr,removeformat,separator,sub,sup").split(',');
	for (var i=0; i<buttonNamesRow3.length; i++)
		template['html'] += TinyMCE_advanced_getButtonHTML(buttonNamesRow3[i]);

	template['html'] += '</td></tr></table>';

	// Setup style select options
	var styleSelectHTML = '<option value="">-- {$lang_theme_style_select} --</option>';
	if (settings['theme_advanced_styles']) {
		var stylesAr = settings['theme_advanced_styles'].split(';');
		for (var i=0; i<stylesAr.length; i++) {
			var key, value;

			key = stylesAr[i].split('=')[0];
			value = stylesAr[i].split('=')[1];

			styleSelectHTML += '<option value="' + value + '">' + key + '</option>';
		}

		TinyMCE_advanced_autoImportCSSClasses = false;
	}

	template['html'] = tinyMCE.replaceVar(template['html'], 'style_select_options', styleSelectHTML);
	template['delta_width'] = 0;
	template['delta_height'] = -40;

	return template;
}

function TinyMCE_advanced_getInsertLinkTemplate() {
	var template = new Array();

	template['html'] = '\
<html><head><title>{$lang_insert_link_title}</title>\
<link href="{$css}" rel="stylesheet" type="text/css">\
<script language="javascript">\
function init() {\
	for (var i=0; i<document.forms[0].target.options.length; i++) {\
		var option = document.forms[0].target.options[i];\
\
		if (option.value == \'{$target}\')\
			option.selected = true;\
	}\
\
	window.focus();\
}\
\
function insertLink() {\
	if (window.opener) {\
		var href = document.forms[0].href.value;\
		var target = document.forms[0].target.options[document.forms[0].target.selectedIndex].value;\
\
		window.opener.tinyMCE.insertLink(href, target);\
		top.close();\
	}\
}\
\
function cancelAction() {\
	top.close();\
}\
</script>\
</head><body onload="init();">\
\
<form onsubmit="insertLink();return false;">\
<table border="0" cellpadding="0" cellspacing="0" width="100%">\
<tr><td align="center" valign="middle">\
<table border="0" cellpadding="4" cellspacing="0">\
<tr><td colspan="2" class="title">{$lang_insert_link_title}</td></tr>\
<tr><td>{$lang_insert_link_url}:</td><td><input name="href" type="text" id="href" value="{$href}" style="width: 200px"></td></tr>\
<tr><td>{$lang_insert_link_target}:</td>\
<td><select name="target" style="width: 200px">\
<option value="_self">{$lang_insert_link_target_same}</option>\
<option value="_blank">{$lang_insert_link_target_blank}</option>\
</select></td></tr>\
<tr><td><input type="button" name="insert" value="{$lang_insert}" onclick="insertLink();">\
</td><td align="right"><input type="button" name="cancel" value="{$lang_cancel}" onclick="cancelAction();"></td></tr>\
</table>\
</td></tr></table>\
</form></body></html>';

	template['width'] = 320;
	template['height'] = 130;

	return template;
}

function TinyMCE_advanced_getInsertImageTemplate() {
	var template = new Array();

	template['html'] = '\
<html><head><title>{$lang_insert_image_title}</title>\
<link href="{$css}" rel="stylesheet" type="text/css">\
\
<script language="javascript">\
function insertImage() {\
	if (window.opener) {\
		var src = document.forms[0].src.value;\
		var alt = document.forms[0].alt.value;\
		var border = document.forms[0].border.value;\
		var vspace = document.forms[0].vspace.value;\
		var hspace = document.forms[0].hspace.value;\
		var width = document.forms[0].width.value;\
		var height = document.forms[0].height.value;\
		var align = document.forms[0].align.options[document.forms[0].align.selectedIndex].value;\
\
		window.opener.tinyMCE.insertImage(src, alt, border, hspace, vspace, width, height, align);\
		top.close();\
	}\
}\
\
function init() {\
	for (var i=0; i<document.forms[0].align.options.length; i++) {\
		if (document.forms[0].align.options[i].value == "{$align}")\
			document.forms[0].align.options.selectedIndex = i;\
	}\
\
	window.focus();\
}\
\
function cancelAction() {\
	top.close();\
}\
</script>\
</head><body onload="window.focus();init();">\
<form onsubmit="insertImage();return false;">\
<table border="0" cellpadding="0" cellspacing="0" width="100%">\
<tr><td align="center" valign="middle">\
<table border="0" cellpadding="4" cellspacing="0">\
<tr><td colspan="2" class="title">{$lang_insert_image_title}</td></tr>\
<tr><td>{$lang_insert_image_src}:</td><td><input name="src" type="text" id="src" value="{$src}" style="width: 200px"></td></tr>\
<tr><td>{$lang_insert_image_alt}:</td>\
<td><input name="alt" type="text" id="alt" value="{$alt}" style="width: 200px"></td></tr>\
<tr><td>{$lang_insert_image_align}:</td>\
<td><select name="align">\
<option value="">{$lang_insert_image_align_default}</option>\
<option value="baseline">{$lang_insert_image_align_baseline}</option>\
<option value="top">{$lang_insert_image_align_top}</option>\
<option value="middle">{$lang_insert_image_align_middle}</option>\
<option value="bottom">{$lang_insert_image_align_bottom}</option>\
<option value="texttop">{$lang_insert_image_align_texttop}</option>\
<option value="absmiddle">{$lang_insert_image_align_absmiddle}</option>\
<option value="absbottom">{$lang_insert_image_align_absbottom}</option>\
<option value="left">{$lang_insert_image_align_left}</option>\
<option value="right">{$lang_insert_image_align_right}</option>\
</select></td></tr>\
<tr><td>{$lang_insert_image_dimensions}:</td>\
<td><input name="width" type="text" id="width" value="{$width}" size="3" maxlength="3"> x <input name="height" type="text" id="height" value="{$height}" size="3" maxlength="3"></td></tr>\
<tr><td>{$lang_insert_image_border}:</td>\
<td><input name="border" type="text" id="border" value="{$border}" size="3" maxlength="3"></td></tr>\
<tr><td>{$lang_insert_image_vspace}:</td>\
<td><input name="vspace" type="text" id="vspace" value="{$vspace}" size="3" maxlength="3"></td></tr>\
<tr><td>{$lang_insert_image_hspace}:</td>\
<td><input name="hspace" type="text" id="hspace" value="{$hspace}" size="3" maxlength="3"></td></tr>\
<tr><td><input type="button" name="insert" value="{$lang_insert}" onclick="insertImage();">\
</td><td align="right"><input type="button" name="cancel" value="{$lang_cancel}" onclick="cancelAction();"></td></tr>\
</table>\
</td></tr></table>\
</form></body></html>';

	template['width'] = 340;
	template['height'] = 260;

	// Language specific width addon
	if (typeof tinyMCELang['lang_insert_image_delta_width'] != "undefined")
		template['width'] += tinyMCELang['lang_insert_image_delta_width'];

	// Language specific height addon
	if (typeof tinyMCELang['lang_insert_image_delta_height'] != "undefined")
		template['height'] += tinyMCELang['lang_insert_image_delta_height'];

	return template;
}

function TinyMCE_advanced_getInsertTableTemplate(settings) {
	var template = new Array();

	template['html'] = '\
<html><head><title>{$lang_insert_table_title}</title>\
<link href="{$css}" rel="stylesheet" type="text/css">\
\
<script language="javascript">\
function insertTable() {\
	if (window.opener) {\
		var args = new Array();\
		args["cols"] = document.forms[0].cols.value;\
		args["rows"] = document.forms[0].rows.value;\
		args["border"] = document.forms[0].border.value;\
		args["cellpadding"] = document.forms[0].cellpadding.value;\
		args["cellspacing"] = document.forms[0].cellspacing.value;\
		args["width"] = document.forms[0].width.value;\
		args["height"] = document.forms[0].height.value;\
		args["align"] = document.forms[0].align.options[document.forms[0].align.selectedIndex].value;\
\
		window.opener.tinyMCE.execCommand("mceInsertTable", false, args);\
		top.close();\
	}\
}\
\
function init() {\
	for (var i=0; i<document.forms[0].align.options.length; i++) {\
		if (document.forms[0].align.options[i].value == "{$align}")\
			document.forms[0].align.options.selectedIndex = i;\
	}\
\
	if ("{$action}" == "update") {\
		document.forms[0].cols.disabled = true;\
		document.forms[0].rows.disabled = true;\
	}\
\
	window.focus();\
}\
\
function cancelAction() {\
	top.close();\
}\
</script>\
</head><body onload="window.focus();init();">\
<form onsubmit="insertTable();return false;">\
<table border="0" cellpadding="0" cellspacing="0" width="100%">\
<tr><td align="center" valign="middle">\
<table border="0" cellpadding="4" cellspacing="0"> \
<tr><td colspan="4" class="title">{$lang_insert_table_title}</td></tr> \
<tr><td>{$lang_insert_table_cols}:</td> \
<td><input name="cols" type="text" id="cols" value="{$cols}" size="3" maxlength="3"></td> \
<td>{$lang_insert_table_rows}:</td> \
<td><input name="rows" type="text" id="rows" value="{$rows}" size="3" maxlength="3"></td> \
</tr><tr><td>{$lang_insert_table_cellpadding}:</td> \
<td><input name="cellpadding" type="text" id="cellpadding" value="{$cellpadding}" size="3" maxlength="3"></td> \
<td>{$lang_insert_table_cellspacing}:</td> \
<td><input name="cellspacing" type="text" id="cellspacing" value="{$cellspacing}" size="3" maxlength="3"></td> \
</tr><tr><td>{$lang_insert_table_align}:</td> \
<td><select name="align"> \
<option value="">{$lang_insert_table_align_default}</option> \
<option value="center">{$lang_insert_table_align_middle}</option> \
<option value="left">{$lang_insert_table_align_left}</option> \
<option value="right">{$lang_insert_table_align_right}</option> \
</select></td> \
<td>{$lang_insert_table_border}:</td> \
<td><input name="border" type="text" id="border" value="{$border}" size="3" maxlength="3"></td></tr> \
<tr><td>{$lang_insert_table_width}:</td> \
<td><input name="width" type="text" id="width" value="{$width}" size="4" maxlength="4"></td> \
<td>{$lang_insert_table_height}: </td><td><input name="height" type="text" id="height" value="{$height}" size="4" maxlength="4"></td> </tr><tr><td><input type="button" name="insert" value="{$lang_insert}" onclick="insertTable();"></td> \
<td align="right">&nbsp;</td><td align="right">&nbsp;</td> \
<td align="right"><input type="button" name="cancel" value="{$lang_cancel}" onclick="cancelAction();"></td></tr></table> \
</td></tr></table>\
</form></body></html>';

	template['width'] = 330;
	template['height'] = 180;

	// Language specific width addon
	if (typeof tinyMCELang['lang_insert_table_delta_width'] != "undefined")
		template['width'] += tinyMCELang['lang_insert_table_delta_width'];

	// Language specific height addon
	if (typeof tinyMCELang['lang_insert_table_delta_height'] != "undefined")
		template['height'] += tinyMCELang['lang_insert_table_delta_height'];

	return template;
}

function TinyMCE_advanced_handleNodeChange(editor_id, node) {
	TinyMCE_advanced_setupCSSClasses(editor_id);

	// Reset old states
	tinyMCE.switchClassSticky(editor_id + '_left', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_right', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_center', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_full', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_bold', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_italic', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_underline', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_strikethrough', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_bullist', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_numlist', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_sub', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_sup', 'mceButtonNormal');

	tinyMCE.switchClassSticky(editor_id + '_table', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_table_insert_row_before', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_table_insert_row_after', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_table_delete_row', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_table_insert_col_before', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_table_insert_col_after', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_table_delete_col', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_outdent', 'mceButtonDisabled', true);

	// Within a td element
	if (tinyMCE.getParentElement(node, "td")) {
		tinyMCE.switchClassSticky(editor_id + '_table_insert_row_before', 'mceButtonNormal', false);
		tinyMCE.switchClassSticky(editor_id + '_table_insert_row_after', 'mceButtonNormal', false);
		tinyMCE.switchClassSticky(editor_id + '_table_delete_row', 'mceButtonNormal', false);
		tinyMCE.switchClassSticky(editor_id + '_table_insert_col_before', 'mceButtonNormal', false);
		tinyMCE.switchClassSticky(editor_id + '_table_insert_col_after', 'mceButtonNormal', false);
		tinyMCE.switchClassSticky(editor_id + '_table_delete_col', 'mceButtonNormal', false);
	}

	// Within table
	if (tinyMCE.getParentElement(node, "table"))
		tinyMCE.switchClassSticky(editor_id + '_table', 'mceButtonSelected');

	// Within li, blockquote
	if (tinyMCE.getParentElement(node, "li,blockquote"))
		tinyMCE.switchClassSticky(editor_id + '_outdent', 'mceButtonNormal', false);

	// Select class in select box
	var selectElm = document.getElementById(editor_id + "_styleSelect");
	if (selectElm) {
		classNode = node;
		breakOut = false;
		var index = 0;

		do {
			if (classNode && classNode.className) {
				for (var i=0; i<selectElm.options.length; i++) {
					if (selectElm.options[i].value == classNode.className) {
						index = i;
						breakOut = true;
						break;
					}
				}
			}
		} while (!breakOut && (classNode = classNode.parentNode));

		selectElm.selectedIndex = index;
	}

	// Handle align attributes
	alignNode = node;
	breakOut = false;
	do {
		if (!alignNode.getAttribute || !alignNode.getAttribute('align'))
			continue;

		switch (alignNode.getAttribute('align').toLowerCase()) {
			case "left":
				tinyMCE.switchClassSticky(editor_id + '_left', 'mceButtonSelected');
				breakOut = true;
			break;

			case "right":
				tinyMCE.switchClassSticky(editor_id + '_right', 'mceButtonSelected');
				breakOut = true;
			break;

			case "middle":
			case "center":
				tinyMCE.switchClassSticky(editor_id + '_center', 'mceButtonSelected');
				breakOut = true;
			break;

			case "justify":
				tinyMCE.switchClassSticky(editor_id + '_full', 'mceButtonSelected');
				breakOut = true;
			break;
		}
	} while (!breakOut && (alignNode = alignNode.parentNode));

	// Handle elements
	do {
		switch (node.nodeName.toLowerCase()) {
			case "b":
			case "strong":
				tinyMCE.switchClassSticky(editor_id + '_bold', 'mceButtonSelected');
			break;

			case "i":
			case "em":
				tinyMCE.switchClassSticky(editor_id + '_italic', 'mceButtonSelected');
			break;

			case "u":
				tinyMCE.switchClassSticky(editor_id + '_underline', 'mceButtonSelected');
			break;

			case "strike":
				tinyMCE.switchClassSticky(editor_id + '_strikethrough', 'mceButtonSelected');
			break;
			
			case "ul":
				tinyMCE.switchClassSticky(editor_id + '_bullist', 'mceButtonSelected');
			break;

			case "ol":
				tinyMCE.switchClassSticky(editor_id + '_numlist', 'mceButtonSelected');
			break;

			case "sub":
				tinyMCE.switchClassSticky(editor_id + '_sub', 'mceButtonSelected');
			break;

			case "sup":
				tinyMCE.switchClassSticky(editor_id + '_sup', 'mceButtonSelected');
			break;
		}
	} while ((node = node.parentNode));
}

// Custom HTML editor function
function TinyMCE_advanced_openHTMLSourceEditor() {
	// Alert if the editor isn't selected.
	if (tinyMCE.getContent() == null) {
		if (tinyMCE.settings['focus_alert'])
			alert(tinyMCELang['lang_focus_alert']);

		return;
	}

	var template = new Array();

	template['html'] = '<html><head><title>{$lang_theme_code_title}</title>\
		<link href="{$css}" rel="stylesheet" type="text/css">\
		<script language="javascript">\
		function saveContent() {\
			if (window.opener) {\
				window.opener.tinyMCE.setContent(document.getElementById(\'htmlSource\').value);\
				window.close();\
			}\
		}\
		window.focus();\
		</script></head>\
		<body>\
		<div class="title">{$lang_theme_code_title}</div><br>\
		<textarea id="htmlSource" name="htmlSource" cols="60" rows="15" style="width: ' + tinyMCE.getParam("theme_advanced_source_editor_area_width", 320) + 'px; height: ' + tinyMCE.getParam("theme_advanced_source_editor_area_height", 190) + 'px">' + tinyMCE.getContent() + '</textarea><br>\
		<input type="button" name="Button" value="{$lang_theme_code_save}" onclick="saveContent();">\
		</body></html>';

	template['width'] = tinyMCE.getParam("theme_advanced_source_editor_width", 340);
	template['height'] = tinyMCE.getParam("theme_advanced_source_editor_height", 270);

	tinyMCE.openWindow(template);
}

// This function auto imports CSS classes into the class selection droplist
function TinyMCE_advanced_setupCSSClasses(editor_id) {
	if (!TinyMCE_advanced_autoImportCSSClasses)
		return;

	var selectElm = document.getElementById(editor_id + '_styleSelect');

	if (selectElm && selectElm.getAttribute('cssImported') != 'true') {
		var doc = tinyMCE.instances[editor_id].contentWindow.document;
		var csses = tinyMCE.isMSIE ? doc.styleSheets(0).rules : doc.styleSheets[0].cssRules;

		if (csses && selectElm) {
			for (var i=0; i<csses.length; i++) {
				var className = csses[i].selectorText;
				if (csses[i].selectorText.charAt(0) == '.') {
					className = className.substring(1);
					selectElm.options[selectElm.length] = new Option(className, className);	 
				}
			}
		}

		// Only do this once
		selectElm.setAttribute('cssImported', 'true');
	}
}