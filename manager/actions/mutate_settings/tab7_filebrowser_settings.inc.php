<!-- KCFinder settings -->
<div class="tab-page" id="tabPage8">
<h2 class="tab"><?php echo $_lang['settings_KC'] ?></h2>
<script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPage8" ) );</script>
<table border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td nowrap class="warning"><?php echo $_lang['rb_title']?></td>
    <td>
        <?php echo wrap_label($_lang['yes'],form_radio('use_browser', 1, 'id="rbRowOn"'));?><br />
        <?php echo wrap_label($_lang['no'], form_radio('use_browser', 0, 'id="rbRowOff"'));?>
    </td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['rb_message']?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td nowrap class="warning"><?php echo $_lang['which_browser_default_title']?></td>
    <td>
        <select name="which_browser" size="1" class="inputBox" onchange="documentDirty=true;">
            <?php
            foreach (glob("media/browser/*", GLOB_ONLYDIR) as $dir) {
                $dir = str_replace('\\', '/', $dir);
                $browser_name = substr($dir, strrpos($dir, '/') + 1);
                $selected = $browser_name == $which_browser ? ' selected="selected"' : '';
                echo '<option value="' . $browser_name . '"' . $selected . '>' . "{$browser_name}</option>\n";
            }
            ?>
        </select>
    </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['which_browser_default_msg']?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td nowrap class="warning"><?php echo $_lang['rb_webuser_title']?></td>
    <td>
      <label><input type="radio" name="rb_webuser" value="1" <?php echo $rb_webuser=='1' ? 'checked="checked"' : "" ; ?> />
      <?php echo $_lang['yes']?></label><br />
      <label><input type="radio" name="rb_webuser" value="0" <?php echo $rb_webuser=='0' ? 'checked="checked"' : "" ; ?> />
      <?php echo $_lang['no']?></label>
    </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['rb_webuser_message']?></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td nowrap class="warning"><?php echo $_lang['rb_base_dir_title']?></td>
    <td>
        <?php echo $_lang['default']; ?> <span id="default_rb_base_dir">[(base_path)]assets/</span><br />
        <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="rb_base_dir" id="rb_base_dir" value="<?php echo $rb_base_dir; ?>" /> <input type="button" onclick="reset_path('rb_base_dir');" value="<?php echo $_lang['reset']; ?>" name="reset_rb_base_dir">
    </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['rb_base_dir_message']?></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td nowrap class="warning"><?php echo $_lang['rb_base_url_title']?></td>
    <td>
      <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="rb_base_url" value="<?php echo $rb_base_url; ?>" />
      </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['rb_base_url_message']?></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
      <td nowrap class="warning"><?php echo $_lang['clean_uploaded_filename']?></td>
      <td>
        <label><input type="radio" name="clean_uploaded_filename" value="1" <?php echo $clean_uploaded_filename=='1' ? 'checked="checked"' : "" ; ?> />
        <?php echo $_lang['yes']?></label><br />
        <label><input type="radio" name="clean_uploaded_filename" value="0" <?php echo $clean_uploaded_filename=='0' ? 'checked="checked"' : "" ; ?> />
        <?php echo $_lang['no']?></label>
      </td>
  </tr>
    <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
      <td width="200">&nbsp;</td>
      <td class="comment"><?php echo $_lang['clean_uploaded_filename_message'];?></td>
    </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
  <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td nowrap class="warning"><?php echo $_lang['settings_strip_image_paths_title']?></td>
    <td>
      <label><input type="radio" name="strip_image_paths" value="1" <?php echo $strip_image_paths=='1' ? 'checked="checked"' : "" ; ?> />
      <?php echo $_lang['yes']?></label><br />
      <label><input type="radio" name="strip_image_paths" value="0" <?php echo $strip_image_paths=='0' ? 'checked="checked"' : "" ; ?> />
      <?php echo $_lang['no']?></label>
    </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['settings_strip_image_paths_message']?></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td nowrap class="warning"><?php echo $_lang['maxImageWidth']?></td>
    <td>
      <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="maxImageWidth" value="<?php echo $maxImageWidth; ?>" />
    </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['maxImageWidth_message']?></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td nowrap class="warning"><?php echo $_lang['maxImageHeight']?></td>
    <td>
      <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="maxImageHeight" value="<?php echo $maxImageHeight; ?>" />
    </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['maxImageHeight_message']?></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td nowrap class="warning"><?php echo $_lang['thumbWidth']?></td>
    <td>
      <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="thumbWidth" value="<?php echo $thumbWidth; ?>" />
    </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['thumbWidth_message']?></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td nowrap class="warning"><?php echo $_lang['thumbHeight']?></td>
    <td>
      <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="thumbHeight" value="<?php echo $thumbHeight; ?>" />
    </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['thumbHeight_message']?></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td nowrap class="warning"><?php echo $_lang['thumbsDir']?></td>
    <td>
      <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="thumbsDir" value="<?php echo $thumbsDir; ?>" />
    </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['thumbsDir_message']?></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td nowrap class="warning"><?php echo $_lang['jpegQuality']?></td>
    <td>
      <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="jpegQuality" value="<?php echo $jpegQuality; ?>" />
    </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['jpegQuality_message']?></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
       <td nowrap class="warning"><?php echo $_lang['denyZipDownload'] ?></td>
        <td>
          <label><input type="radio" name="denyZipDownload" value="0" <?php echo $denyZipDownload=='0' ? 'checked="checked"' : ""; ?> />
          <?php echo $_lang['no']?></label><br />
          <label><input type="radio" name="denyZipDownload" value="1" <?php echo $denyZipDownload=='1' ? 'checked="checked"' : ""; ?> />
          <?php echo $_lang['yes']?></label>
        </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
        <td nowrap class="warning"><?php echo $_lang['denyExtensionRename'] ?></td>
        <td>
           <label><input type="radio" name="denyExtensionRename" value="0" <?php echo $denyExtensionRename=='0' ? 'checked="checked"' : ""; ?> />
           <?php echo $_lang['no']?></label><br />
           <label><input type="radio" name="denyExtensionRename" value="1" <?php echo $denyExtensionRename=='1' ? 'checked="checked"' : ""; ?> />
           <?php echo $_lang['yes']?></label>
        </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
       <td nowrap class="warning"><?php echo $_lang['showHiddenFiles'] ?></td>
       <td>
         <label><input type="radio" name="showHiddenFiles" value="0" <?php echo $showHiddenFiles=='0' ? 'checked="checked"' : ""; ?> />
         <?php echo $_lang['no']?></label><br />
         <label><input type="radio" name="showHiddenFiles" value="1" <?php echo $showHiddenFiles=='1' ? 'checked="checked"' : ""; ?> />
         <?php echo $_lang['yes']?></label>
        </td>
  </tr>
  <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  
  <tr>
    <td colspan="2">
        <?php
            // invoke OnMiscSettingsRender event
            $evtOut = $modx->invokeEvent('OnMiscSettingsRender');
            if(is_array($evtOut)) echo implode("",$evtOut);
        ?>
    </td>
  </tr>
</table>
</div>
