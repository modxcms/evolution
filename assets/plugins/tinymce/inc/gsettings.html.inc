<table id="editorRow_TinyMCE" style="width:inherit;" border="0" cellspacing="0" cellpadding="3">
  <tr class="row1" style="display: [+display+];">
    <td colspan="2" class="warning" style="color:#707070; background-color:#eeeeee"><h4>[+tinymce_settings+]</h4></td>
  </tr>
  <tr class="row1" style="display: [+display+];">
    <td nowrap class="warning"><b>[+tinymce_editor_theme_title+]</b></td>
    <td>
    <select name="tinymce_editor_theme">
    [+themeOptions+]
	</select>
	</td>
  </tr>
  <tr class="row1" style="display: [+display+];">
    <td width="200">&nbsp;</td>
    <td class="comment">[+tinymce_editor_theme_message+]</td>
  </tr>
  <tr class="row1" style="display: [+display+];">
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="row1" style="display: [+display+];">
	<td nowrap class="warning"><b>[+tinymce_editor_custom_plugins_title+]</b></td>
	<td><input onChange="documentDirty=true;" type="text" class="inputBox" maxlength="6500" style="width: 300px;" name="tinymce_custom_plugins" value="[+custom_plugins+]" />
	</td>
  </tr>
  <tr class="row1" style="display: [+display+];">
	<td width="200">&nbsp;</td>
	<td class="comment">[+tinymce_editor_custom_plugins_message+]</td>
  </tr>
  <tr class="row1" style="display: [+display+];">
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="row1" style="display: [+display+];">
	<td nowrap class="warning" valign="top"><b>[+tinymce_editor_custom_buttons_title+]</b></td>
	<td>
	Row 1: <input onChange="documentDirty=true;" type="text" class="inputBox" maxlength="6500" style="width: 300px;" name="tinymce_custom_buttons1" value="[+custom_buttons1+]" /><br/>
	Row 2: <input onChange="documentDirty=true;" type="text" class="inputBox" maxlength="6500" style="width: 300px;" name="tinymce_custom_buttons2" value="[+custom_buttons2+]" /><br/>
	Row 3: <input onChange="documentDirty=true;" type="text" class="inputBox" maxlength="6500" style="width: 300px;" name="tinymce_custom_buttons3" value="[+custom_buttons3+]" /><br/>
	Row 4: <input onChange="documentDirty=true;" type="text" class="inputBox" maxlength="6500" style="width: 300px;" name="tinymce_custom_buttons4" value="[+custom_buttons4+]" />
	</td>
  </tr>
  <tr class="row1" style="display: [+display+];">
	<td width="200">&nbsp;</td>
	<td class="comment">[+tinymce_editor_custom_buttons_message+]</td>
  </tr>
  <tr class="row1" style="display: [+display+];">
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="row1" style="display: [+display+];">
	<td nowrap class="warning"><b>[+tinymce_editor_css_selectors_title+]</b></td>
	<td><input onChange="documentDirty=true;" type="text" class="inputBox" maxlength="6500" style="width: 300px;" name="tinymce_css_selectors" value="[+css_selectors+]" />
	</td>
  </tr>
  <tr class="row1" style="display: [+display+];">
	<td width="200">&nbsp;</td>
	<td class="comment">[+tinymce_editor_css_selectors_message+]</td>
  </tr>
</table>
