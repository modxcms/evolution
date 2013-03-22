<style type="text/css">
textarea.mce {width:95%;height:53px;display:block;}
#editorRow_TinyMCE {width:99%;}
#editorRow_TinyMCE #editorRow_TinyMCE th {width:220px;margin-left:25px;}
#editorRow_TinyMCE td,#editorRow_TinyMCE th {border-bottom:1px dotted #ccc;}

</style>
<table id="editorRow_TinyMCE" class="settings editorRow">
  <tr class="row1" style="display: [+display+];">
    <th colspan="2" style="color:#707070; background-color:#eeeeee"><h4 style="margin:3px;">[+mce_settings+]</h4></th>
  </tr>
  <tr class="row1" style="display: [+display+];">
    <th>[+mce_editor_theme_title+]</th>
    <td>
    <select name="tinymce_editor_theme" class="inputBox">
    [+theme_options+]
  </select>
    <div>[+mce_editor_theme_message+]</div>
  </td>
  </tr>
  <tr class="row1" style="display: [+display+];">
    <th>[+mce_editor_skin_title+]</th>
    <td>
    <select name="mce_editor_skin" class="inputBox">
    [+skin_options+]
  </select>
    <div>[+mce_editor_skin_message+]</div>
  </td>
  </tr>

  <tr class="row1" style="display: [+display+];">
  <th>[+mce_tpl_title+]</th>
  <td>
    <div style="margin-bottom:8px;"><label>[+mce_tpl_docid+] <input type="text" class="inputBox" style="width: 300px;" name="mce_template_docs" value="[+mce_template_docs+]" /></label></div>
    <div><label>[+mce_tpl_chunkname+] <input type="text" class="inputBox" style="width: 300px;" name="mce_template_chunks" value="[+mce_template_chunks+]" /></label></div>
  <div>[+mce_tpl_msg+]</div>
  </td>
  </tr>

  <tr class="row1" style="display: [+display+];">
    <th>[+mce_editor_entermode_title+]</th>
    <td>
    [+entermode_options+]
    <div>[+mce_editor_entermode_message+]</div>
  </td>
  </tr>
  <tr class="row1" style="display: [+display+];">
    <th>[+mce_element_format_title+]</th>
    <td>
    [+element_format_options+]
    <div>[+mce_element_format_message+]</div>
  </td>
  </tr>
  <tr class="row1" style="display: [+display+];">
    <th>[+mce_schema_title+]</th>
    <td>
    [+schema_options+]
    <div>[+mce_schema_message+]</div>
  </td>
  </tr>
  <tr class="row1" style="display: [+display+];">
  <th>[+mce_editor_custom_plugins_title+]</th>
  <td>
    <textarea class="inputBox mce" name="tinymce_custom_plugins" />[+custom_plugins+]</textarea>
    <div>[+mce_editor_custom_plugins_message+]</div>
  </td>
  </tr>
  <tr class="row1" style="display: [+display+];">
  <th>[+mce_editor_custom_buttons_title+]</th>
  <td>
  Row 1: <textarea class="inputBox mce" name="tinymce_custom_buttons1" />[+custom_buttons1+]</textarea>
  <div>[+mce_toolbar1_msg+]</div>
  Row 2: <textarea class="inputBox mce" name="tinymce_custom_buttons2" />[+custom_buttons2+]</textarea>
  <div>[+mce_toolbar2_msg+]</div>
  Row 3: <textarea class="inputBox mce" name="tinymce_custom_buttons3" />[+custom_buttons3+]</textarea>
  Row 4: <textarea class="inputBox mce" name="tinymce_custom_buttons4" />[+custom_buttons4+]</textarea>
  <div>[+mce_editor_custom_buttons_message+]</div>
  </td>
  </tr>
  <tr class="row1" style="display: [+display+];">
  <th>[+mce_editor_css_selectors_title+]</th>
  <td><input type="text" class="inputBox" style="width: 300px;" name="tinymce_css_selectors" value="[+css_selectors+]" />
  <div>[+mce_editor_css_selectors_message+]</div>
  </td>
  </tr>
</table>