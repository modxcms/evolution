<!-- Friendly URL settings  -->
<div class="tab-page" id="tabPage3">
<h2 class="tab"><?php echo $_lang['settings_furls'] ?></h2>
<script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPage3" ) );</script>
<table border="0" cellspacing="0" cellpadding="3">
<tr>
<td nowrap class="warning" valign="top"><?php echo $_lang['friendlyurls_title'] ?></td>
<td>
    <?php echo wrap_label($_lang['yes'],form_radio('friendly_urls', 1, 'id="furlRowOn"'));?><br />
    <?php echo wrap_label($_lang['no'], form_radio('friendly_urls', 0, 'id="furlRowOff"'));?>
</td>
</tr>
<tr>
<td width="200">&nbsp;</td>
<td class="comment"><?php echo $_lang['friendlyurls_message'] ?></td>
</tr>
<tr>
<td colspan="2"><div class="split"></div></td>
</tr>
<tr>
    <td nowrap class="warning"><?php echo $_lang['xhtml_urls_title'] ?></td>
    <td>
        <?php echo wrap_label($_lang['yes'],form_radio('xhtml_urls', 1));?><br />
        <?php echo wrap_label($_lang['no'], form_radio('xhtml_urls', 0));?>
    </td>
</tr>
<tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['xhtml_urls_message'] ?></td>
</tr>
<tr>
<td colspan="2"><div class="split"></div></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td nowrap class="warning" valign="top"><?php echo $_lang['friendlyurlsprefix_title'] ?></td>
<td><input onchange="documentDirty=true;" type="text" maxlength="50" style="width: 200px;" name="friendly_url_prefix" value="<?php echo $friendly_url_prefix; ?>" /></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td width="200">&nbsp;</td>
<td class="comment"><?php echo $_lang['friendlyurlsprefix_message'] ?></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td colspan="2"><div class="split"></div></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td nowrap class="warning" valign="top"><?php echo $_lang['friendlyurlsuffix_title'] ?></td>
<td><input onchange="documentDirty=true;" type="text" maxlength="50" style="width: 200px;" name="friendly_url_suffix" value="<?php echo $friendly_url_suffix; ?>" /></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td width="200">&nbsp;</td>
<td class="comment"><?php echo $_lang['friendlyurlsuffix_message'] ?></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td colspan="2"><div class="split"></div></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<th><?php echo $_lang['make_folders_title'] ?></th>
<td>
  <?php echo wrap_label($_lang['yes'],form_radio('make_folders','1'));?><br />
  <?php echo wrap_label($_lang['no'],form_radio('make_folders','0'));?>
</td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
        <td width="200">&nbsp;</td>
        <td class="comment"><?php echo $_lang['make_folders_message'] ?></td>
      </tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td colspan="2"><div class="split"></div></td>
</tr>
  
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<th><?php echo $_lang['seostrict_title'] ?></th>
<td>
  <?php echo wrap_label($_lang['yes'],form_radio('seostrict','1'));?><br />
  <?php echo wrap_label($_lang['no'],form_radio('seostrict','0'));?>
 </td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
        <td width="200">&nbsp;</td>
        <td class="comment"><?php echo $_lang['seostrict_message'] ?></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td colspan="2"><div class="split"></div></td>
</tr>

<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
  <th><?php echo $_lang['aliaslistingfolder_title'] ?></th>
  <td>
      <?php echo wrap_label($_lang['yes'],form_radio('aliaslistingfolder','1'));?><br />
      <?php echo wrap_label($_lang['no'],form_radio('aliaslistingfolder','0'));?>
  </td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
  <td width="200">&nbsp;</td>
  <td class="comment"><?php echo $_lang['aliaslistingfolder_message'] ?></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
  <td colspan="2"><div class="split"></div></td>
</tr>

<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td nowrap class="warning" valign="top"><?php echo $_lang['friendly_alias_title'] ?></td>
<td>
  <?php echo wrap_label($_lang['yes'],form_radio('friendly_alias_urls','1'));?><br />
  <?php echo wrap_label($_lang['no'],form_radio('friendly_alias_urls','0'));?>
</td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td width="200">&nbsp;</td>
<td class="comment"><?php echo $_lang['friendly_alias_message'] ?></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td colspan="2"><div class="split"></div></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td nowrap class="warning" valign="top"><?php echo $_lang['use_alias_path_title'] ?></td>
<td>
  <?php echo wrap_label($_lang['yes'],form_radio('use_alias_path','1'));?><br />
  <?php echo wrap_label($_lang['no'],form_radio('use_alias_path','0'));?>
</td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td width="200">&nbsp;</td>
<td class="comment"><?php echo $_lang['use_alias_path_message'] ?></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td colspan="2"><div class="split"></div></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td nowrap class="warning" valign="top"><?php echo $_lang['duplicate_alias_title'] ?></td>
<td>
  <?php echo wrap_label($_lang['yes'],form_radio('allow_duplicate_alias','1'));?><br />
  <?php echo wrap_label($_lang['no'],form_radio('allow_duplicate_alias','0'));?>
</td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td width="200">&nbsp;</td>
<td class="comment"><?php echo $_lang['duplicate_alias_message'] ?></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td colspan="2"><div class="split"></div></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td nowrap class="warning" valign="top"><?php echo $_lang['automatic_alias_title'] ?></td>
<td>
  <?php echo wrap_label($_lang['yes'],form_radio('automatic_alias','1'));?><br />
  <?php echo wrap_label($_lang['no'],form_radio('automatic_alias','0'));?>
</td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td width="200">&nbsp;</td>
<td class="comment"><?php echo $_lang['automatic_alias_message'] ?></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td colspan="2"><div class="split"></div></td>
</tr>
<tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
<td colspan="2">
    <?php
        // invoke OnFriendlyURLSettingsRender event
        $evtOut = $modx->invokeEvent('OnFriendlyURLSettingsRender');
        if(is_array($evtOut)) echo implode("",$evtOut);
    ?>
</td>
</tr>
</table>
</div>
