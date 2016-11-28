<!-- Interface & editor settings -->
<div class="tab-page" id="tabPage5">
<h2 class="tab"><?php echo $_lang['settings_ui'] ?></h2>
<script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPage5" ) );</script>
<table border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td nowrap class="warning"><?php echo $_lang['language_title']?></td>
    <td> <select name="manager_language" size="1" class="inputBox" onchange="documentDirty=true;">
<?php echo get_lang_options(null, $manager_language);?>
      </select> </td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['language_message']?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td nowrap class="warning"><?php echo $_lang['charset_title']?></td>
    <td> <select name="modx_charset" size="1" class="inputBox" style="width:250px;" onchange="documentDirty=true;">
        <?php include "charsets.php"; ?>
      </select> </td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['charset_message']?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
    <tr>
      <td nowrap class="warning"><?php echo $_lang['manager_theme']?></td>
      <td> <select name="manager_theme" size="1" class="inputBox" onchange="documentDirty=true;document.forms['settings'].theme_refresher.value = Date.parse(new Date())">
       <?php
          $dir = dir("media/style/");
          while ($file = $dir->read()) {
              if($file!="." && $file!=".." && is_dir("media/style/$file") && substr($file,0,1) != '.') {
                  if($file==='common') continue;
                  $themename = $file;
                  $selectedtext = $themename==$manager_theme ? "selected='selected'" : "" ;
                  echo "<option value='$themename' $selectedtext>".ucwords(str_replace("_", " ", $themename))."</option>";
              }
          }
          $dir->close();
       ?>
       </select><input type="hidden" name="theme_refresher" value="" /></td>
    </tr>
    <tr>
      <td width="200">&nbsp;</td>
      <td class="comment"><?php echo $_lang['manager_theme_message']?></td>
    </tr>
    
    <tr>
      <td colspan="2"><div class="split"></div></td>
    </tr>
     <tr>
       <td nowrap class="warning"><?php echo $_lang['warning_visibility'] ?></td>
       <td> <label><input type="radio" name="warning_visibility" value="0" <?php echo $warning_visibility=='0' ? 'checked="checked"' : ""; ?> />
         <?php echo $_lang['administrators']?></label><br />
         <label><input type="radio" name="warning_visibility" value="1" <?php echo ($warning_visibility=='1') ? 'checked="checked"' : ""; ?> />
         <?php echo $_lang['everybody']?></label></td>
     </tr>
     <tr>
       <td width="200">&nbsp;</td>
       <td class="comment"><?php echo $_lang['warning_visibility_message']?></td>
     </tr>
    
    <tr>
      <td colspan="2"><div class="split"></div></td>
    </tr>
     <tr>
         <td nowrap class="warning"><?php echo $_lang['tree_page_click'] ?></td>
         <td>
           <label><input type="radio" name="tree_page_click" value="27" <?php echo $tree_page_click=='27' ? 'checked="checked"' : ""; ?> />
           <?php echo $_lang['edit_resource']?></label><br />
           <label><input type="radio" name="tree_page_click" value="3" <?php echo ($tree_page_click=='3') ? 'checked="checked"' : ""; ?> />
           <?php echo $_lang['doc_data_title']?></label></td>
       </tr>
     <tr>
       <td width="200">&nbsp;</td>
       <td class="comment"><?php echo $_lang['tree_page_click_message']?></td>
     </tr>

    <tr>
        <td colspan="2"><div class="split"></div></td>
    </tr>
    <tr>
        <td nowrap class="warning"><?php echo $_lang['use_breadcrumbs'] ?></td>
        <td>
            <label><input type="radio" name="use_breadcrumbs" value="1" <?php echo $use_breadcrumbs=='1' ? 'checked="checked"' : ""; ?> />
            <?php echo $_lang['yes']?></label><br />
            <label><input type="radio" name="use_breadcrumbs" value="0" <?php echo ($use_breadcrumbs=='0') ? 'checked="checked"' : ""; ?> />
            <?php echo $_lang['no']?></label></td>
    </tr>
    <tr>
        <td width="200">&nbsp;</td>
        <td class="comment"><?php echo $_lang['use_breadcrumbs_message']?></td>
    </tr>

     <tr>
      <td colspan="2"><div class="split"></div></td>
    </tr>
     <tr>
       <td nowrap class="warning"><?php echo $_lang['remember_last_tab'] ?></td>
       <td>
         <label><input type="radio" name="remember_last_tab" value="1" <?php echo $remember_last_tab=='1' ? 'checked="checked"' : ""; ?> />
         <?php echo $_lang['yes']?></label><br />
         <label><input type="radio" name="remember_last_tab" value="0" <?php echo ($remember_last_tab=='0') ? 'checked="checked"' : ""; ?> />
         <?php echo $_lang['no']?></label></td>
     </tr>
     <tr>
       <td width="200">&nbsp;</td>
       <td class="comment"><?php echo $_lang['remember_last_tab_message']?></td>
     </tr>
    <tr>
      <td colspan="2"><div class="split"></div></td>
    </tr>
<tr>
<th><?php echo $_lang['setting_resource_tree_node_name'] ?></th>
<td>
<select name="resource_tree_node_name" size="1" class="inputBox">
<?php
$tpl = '<option value="[+value+]" [+selected+]>[*[+value+]*]</option>' . "\n";
$option = array('pagetitle','longtitle','menutitle','alias','createdon','editedon','publishedon');
$output = array();
foreach($option as $v)
{
$selected = ($v==$resource_tree_node_name) ? 'selected' : '';
$s = array('[+value+]','[+selected+]');
$r = array($v,$selected);
$output[] = str_replace($s,$r,$tpl);
}
echo implode("\n",$output)
?>
</select><br />

</td>
</tr>
<tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['setting_resource_tree_node_name_desc']?><br/><b><?php echo $_lang['setting_resource_tree_node_name_desc_add']?></b></td>
</tr>
<tr>
<td colspan="2"><div class="split"></div></td>
</tr>
    <tr>
        <td nowrap class="warning"><?php echo $_lang['session_timeout'] ?></td>
        <td><input onchange="documentDirty=true;" type="text" maxlength="3" size="5" name="session_timeout" value="<?php echo $session_timeout; ?>" /></td>
    </tr>
    <tr>
        <td width="200">&nbsp;</td>
        <td class="comment"><?php echo $_lang['session_timeout_msg']?></td>
    </tr>
    <tr>
        <td colspan="2"><div class="split"></div></td>
    </tr>
     <tr>
         <td nowrap class="warning"><?php echo $_lang['tree_show_protected'] ?></td>
         <td>
           <label><input type="radio" name="tree_show_protected" value="1" <?php echo ($tree_show_protected=='1') ? 'checked="checked" ' : ''; ?>/>
           <?php echo $_lang['yes']?></label><br />
           <label><input type="radio" name="tree_show_protected" value="0" <?php echo ($tree_show_protected=='0') ? 'checked="checked" ' : ''; ?>/>
           <?php echo $_lang['no']?></label></td>
       </tr>
         <tr>
           <td width="200">&nbsp;</td>
           <td class="comment"><?php echo $_lang['tree_show_protected_message']?></td>
         </tr>
    <tr>
      <td colspan="2"><div class="split"></div></td>
    </tr>
     <tr>
         <td nowrap class="warning"><?php echo $_lang['show_meta'] ?></td>
         <td>
           <label><input type="radio" name="show_meta" value="1" <?php echo $show_meta=='1' ? 'checked="checked"' : ""; ?> />
           <?php echo $_lang['yes']?></label><br />
           <label><input type="radio" name="show_meta" value="0" <?php echo ($show_meta=='0') ? 'checked="checked"' : ''; ?> />
           <?php echo $_lang['no']?></label></td>
       </tr>
     <tr>
       <td width="200">&nbsp;</td>
       <td class="comment"><?php echo $_lang['show_meta_message']?></td>
     </tr>
<tr>
<td colspan="2"><div class="split"></div></td>
</tr>
<tr>
         <td nowrap class="warning"><?php echo $_lang['datepicker_offset'] ?></td>
         <td><input onchange="documentDirty=true;" type="text" maxlength="50" size="5" name="datepicker_offset" value="<?php echo $datepicker_offset; ?>" /></td>
       </tr>
       <tr>
           <td width="200">&nbsp;</td>
           <td class="comment"><?php echo $_lang['datepicker_offset_message']?></td>
     </tr>
    <tr>
      <td colspan="2"><div class="split"></div></td>
    </tr>
    <tr>
      <td nowrap class="warning"><?php echo $_lang['datetime_format']?></td>
      <td> <select name="datetime_format" size="1" class="inputBox">
      <?php
          $datetime_format_list = array('dd-mm-YYYY', 'mm/dd/YYYY', 'YYYY/mm/dd');
          $str = '';
          foreach($datetime_format_list as $value)
          {
              $selectedtext = ($datetime_format == $value) ? ' selected' : '';
              $str .= '<option value="' . $value . '"' . $selectedtext . '>';
              $str .= $value . '</option>' . PHP_EOL;
          }
          echo $str;
      ?>
       </select></td>
    </tr>
    <tr>
      <td width="200">&nbsp;</td>
      <td class="comment"><?php echo $_lang['datetime_format_message']?></td>
    </tr>
    <tr>
      <td colspan="2"><div class="split"></div></td>
    </tr>
  <tr>
    <td nowrap class="warning"><?php echo $_lang['nologentries_title']?></td>
    <td><input onchange="documentDirty=true;" type="text" maxlength="50" size="5" name="number_of_logs" value="<?php echo $number_of_logs; ?>" /></td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['nologentries_message']?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td nowrap class="warning"><?php echo $_lang['mail_check_timeperiod_title'] ?></td>
    <td><input type="text" name="mail_check_timeperiod" onchange="documentDirty=true;" size="5" value="<?php echo $mail_check_timeperiod; ?>" /></td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['mail_check_timeperiod_message'] ?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td nowrap class="warning"><?php echo $_lang['nomessages_title']?></td>
    <td><input onchange="documentDirty=true;" type="text" maxlength="50" size="5" name="number_of_messages" value="<?php echo $number_of_messages; ?>" /></td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['nomessages_message']?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td nowrap class="warning"><?php echo $_lang['noresults_title']?></td>
    <td><input onchange="documentDirty=true;" type="text" maxlength="50" size="5" name="number_of_results" value="<?php echo $number_of_results; ?>" /></td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['noresults_message']?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
<?php
    // invoke OnRichTextEditorRegister event
    $evtOut = $modx->invokeEvent('OnRichTextEditorRegister');
    if(!is_array($evtOut)) {
        $evtOut = array();
        $use_editor = 0;
    }
?>
  <tr <?php echo showHide(0<count($evtOut));?>>
    <td nowrap class="warning"><?php echo $_lang['use_editor_title']?></td>
    <td>
        <?php echo wrap_label($_lang['yes'],form_radio('use_editor', 1, 'id="editorRowOn"'));?><br />
        <?php echo wrap_label($_lang['no'], form_radio('use_editor', 0, 'id="editorRowOff"'));?>
    </td>
  </tr>
  <tr <?php echo showHide(0<count($evtOut));?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['use_editor_message']?></td>
  </tr>
  <tr <?php echo showHide(0<count($evtOut));?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  
  
  <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
    <td nowrap class="warning"><?php echo $_lang['which_editor_title']?></td>
    <td>
        <select name="which_editor" onchange="documentDirty=true;">
            <?php
                // invoke OnRichTextEditorRegister event
                echo "<option value='none'".($which_editor=='none' ? " selected='selected'" : "").">".$_lang['none']."</option>\n";
                if(is_array($evtOut)) {
                    foreach($evtOut as $editor) {
                        echo "<option value='$editor'".($which_editor==$editor ? " selected='selected'" : "").">$editor</option>\n";
                    }
                }
            ?>
        </select>
    </td>
  </tr>
  <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['which_editor_message']?></td>
  </tr>
  <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
    <td nowrap class="warning"><?php echo $_lang['fe_editor_lang_title']?></td>
    <td> <select name="fe_editor_lang" size="1" class="inputBox" onchange="documentDirty=true;">
<?php echo get_lang_options(null, $fe_editor_lang);?>
      </select> </td>
  </tr>
  <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['fe_editor_lang_message']?></td>
  </tr>
  <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
    <td nowrap class="warning"><?php echo $_lang['editor_css_path_title']?></td>
    <td><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="editor_css_path" value="<?php echo $editor_css_path; ?>" />
    </td>
  </tr>
  <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['editor_css_path_message']?></td>
  </tr>
  <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td colspan="2">
        <?php
            // invoke OnInterfaceSettingsRender event
            $evtOut = $modx->invokeEvent('OnInterfaceSettingsRender');
            if(is_array($evtOut)) echo implode("",$evtOut);
        ?>
    </td>
  </tr>
</table>
</div>
