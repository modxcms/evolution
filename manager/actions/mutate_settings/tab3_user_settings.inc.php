<!-- User settings -->
<div class="tab-page" id="tabPage4">
<h2 class="tab"><?php echo $_lang['settings_users'] ?></h2>
<script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPage4" ) );</script>
<table border="0" cellspacing="0" cellpadding="3">
<tr>
    <th><?php echo $_lang['check_files_onlogin_title'] ?></th>
    <td>
      <textarea name="check_files_onlogin"><?php echo $check_files_onlogin;?></textarea><br />
        
</td>
</tr>
<tr>
    <td width="200">&nbsp;</td>
    <td class="comment">  <?php echo $_lang['check_files_onlogin_message'] ?></td>
</tr>
<tr>
  <td colspan="2"><div class="split"></div></td>
</tr>
  <tr>
    <td nowrap class="warning"><?php echo $_lang['udperms_title'] ?></td>
    <td>
        <?php echo wrap_label($_lang['yes'],form_radio('use_udperms', 1, 'id="udPermsOn"'));?><br />
        <?php echo wrap_label($_lang['no'], form_radio('use_udperms', 0, 'id="udPermsOff"'));?>
    </td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['udperms_message'] ?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="udPerms" <?php echo showHide($use_udperms==1);?>>
    <td nowrap class="warning"><?php echo $_lang['udperms_allowroot_title'] ?></td>
    <td>
      <input type="radio" name="udperms_allowroot" value="1" <?php echo $udperms_allowroot=='1' ? 'checked="checked"' : "" ; ?> />
      <?php echo $_lang['yes']?><br />
      <input type="radio" name="udperms_allowroot" value="0" <?php echo $udperms_allowroot=='0' ? 'checked="checked"' : "" ; ?> />
      <?php echo $_lang['no']?>
    </td>
  </tr>
  <tr class="udPerms" <?php echo showHide($use_udperms==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['udperms_allowroot_message'] ?></td>
  </tr>
  <tr class="udPerms" <?php echo showHide($use_udperms==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="udPerms">
    <td nowrap class="warning"><?php echo $_lang['failed_login_title'] ?></td>
    <td><input type="text" name="failed_login_attempts" style="width:50px" value="<?php echo $failed_login_attempts; ?>" /></td>
  </tr>
  <tr class="udPerms">
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['failed_login_message'] ?></td>
  </tr>
  <tr class="udPerms">
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td nowrap class="warning"><?php echo $_lang['blocked_minutes_title'] ?></td>
    <td><input type="text" name="blocked_minutes" style="width:100px" value="<?php echo $blocked_minutes; ?>" /></td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['blocked_minutes_message'] ?></td>
  </tr>
   <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
<th><?php echo $_lang['a17_error_reporting_title']; ?></th>
<td>
<?php echo wrap_label($_lang['a17_error_reporting_opt0'], form_radio('error_reporting','0'));?><br />
<?php echo wrap_label($_lang['a17_error_reporting_opt1'], form_radio('error_reporting','1'));?><br />
<?php echo wrap_label($_lang['a17_error_reporting_opt2'], form_radio('error_reporting','2'));?><br />
<?php echo wrap_label($_lang['a17_error_reporting_opt99'],form_radio('error_reporting','99'));?>
</td>
</tr>
<tr>
    <td width="200">&nbsp;</td>
    <td class="comment"> <?php echo $_lang['a17_error_reporting_msg'];?></td>
</tr>
<tr><td colspan="2"><div class="split"></div></td></tr>
<tr>
<th><?php echo $_lang['mutate_settings.dynamic.php6']; ?></th>
<td>
<?php echo wrap_label($_lang['mutate_settings.dynamic.php7'],form_radio('send_errormail','0'));?><br />
<?php echo wrap_label('error',form_radio('send_errormail','3'));?><br />
<?php echo wrap_label('error + warning',form_radio('send_errormail','2'));?><br />
<?php echo wrap_label('error + warning + information',form_radio('send_errormail','1'));?><br />
<?php echo parseText($_lang['mutate_settings.dynamic.php8'],array('emailsender'=>$modx->config['emailsender']));?></td>
</tr>

<tr>
  <td colspan="2"><div class="split"></div></td>
</tr>
<tr>
<th><?php echo $_lang['pwd_hash_algo_title'] ?></th>
<td>
<?php
if(empty($pwd_hash_algo)) $phm['sel']['UNCRYPT'] = 1;
$phm['e']['BLOWFISH_Y'] = $modx->manager->checkHashAlgorithm('BLOWFISH_Y') ? 0:1;
$phm['e']['BLOWFISH_A'] = $modx->manager->checkHashAlgorithm('BLOWFISH_A') ? 0:1;
$phm['e']['SHA512']     = $modx->manager->checkHashAlgorithm('SHA512') ? 0:1;
$phm['e']['SHA256']     = $modx->manager->checkHashAlgorithm('SHA256') ? 0:1;
$phm['e']['MD5']        = $modx->manager->checkHashAlgorithm('MD5') ? 0:1;
$phm['e']['UNCRYPT']    = $modx->manager->checkHashAlgorithm('UNCRYPT') ? 0:1;
?>
<?php echo wrap_label('CRYPT_BLOWFISH_Y (salt &amp; stretch)',form_radio('pwd_hash_algo','BLOWFISH_Y', '', $phm['e']['BLOWFISH_Y']));?><br />
<?php echo wrap_label('CRYPT_BLOWFISH_A (salt &amp; stretch)',form_radio('pwd_hash_algo','BLOWFISH_A', '', $phm['e']['BLOWFISH_A']));?><br />
<?php echo wrap_label('CRYPT_SHA512 (salt &amp; stretch)'    ,form_radio('pwd_hash_algo','SHA512'    , '', $phm['e']['SHA512']));?><br />
<?php echo wrap_label('CRYPT_SHA256 (salt &amp; stretch)'    ,form_radio('pwd_hash_algo','SHA256'    , '', $phm['e']['SHA256']));?><br />
<?php echo wrap_label('CRYPT_MD5 (salt &amp; stretch)'       ,form_radio('pwd_hash_algo','MD5'       , '', $phm['e']['MD5']));?><br />
<?php echo wrap_label('UNCRYPT(32 chars salt + SHA-1 hash)'  ,form_radio('pwd_hash_algo','UNCRYPT'  , '', $phm['e']['UNCRYPT']));?>
</td>
</tr>
<tr>
    <td width="200">&nbsp;</td>
    <td class="comment">  <?php echo $_lang['pwd_hash_algo_message']?></td>
</tr>
   <tr>
    <td colspan="2"><div class="split"></div></td>
</tr>
<tr>
<th><?php echo $_lang['enable_bindings_title'] ?></th>
<td>
<?php echo wrap_label($_lang['yes'],form_radio('enable_bindings','1'));?><br />
<?php echo wrap_label($_lang['no'], form_radio('enable_bindings','0'));?>

</td>
</tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment">  <?php echo $_lang['enable_bindings_message'] ?></td>
</tr>
   <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <?php
      // Check for GD before allowing captcha to be enabled
      $gdAvailable = extension_loaded('gd');
  ?>
<?php
$gdAvailable = extension_loaded('gd');
if(!$gdAvailable) $use_captcha = 0;
?>
  <tr>
    <td nowrap class="warning"><?php echo $_lang['captcha_title'] ?></td>
    <td> <label><input type="radio" id="captchaOn" name="use_captcha" value="1" <?php echo ($use_captcha==1) ? 'checked="checked"' : "" ; echo (!$gdAvailable)? ' readonly="readonly"' : ''; ?> />
      <?php echo $_lang['yes']?></label><br />
      <label><input type="radio" id="captchaOff" name="use_captcha" value="0" <?php echo ($use_captcha==0) ? 'checked="checked"' : "" ; echo (!$gdAvailable)? ' readonly="readonly"' : '';?> />
      <?php echo $_lang['no']?></label> </td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['captcha_message'] ?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="captchaRow" <?php echo showHide($use_captcha==1);?>>
    <td nowrap class="warning"><?php echo $_lang['captcha_words_title'] ?>
      <br />
      <p><?php echo $_lang['update_settings_from_language']; ?></p>
      <select name="reload_captcha_words" id="reload_captcha_words_select" onchange="confirmLangChange(this, 'captcha_words_default', 'captcha_words_input');">
<?php echo get_lang_options('captcha_words_default');?>
      </select>
    </td>
    <td><input type="text" id="captcha_words_input" name="captcha_words" style="width:250px" value="<?php echo $captcha_words; ?>" />
        <input type="hidden" name="captcha_words_default" id="captcha_words_default_hidden" value="<?php echo addslashes($_lang['captcha_words_default']);?>" />
    </td>
  </tr>
  <tr class="captchaRow" <?php echo showHide($use_captcha==1);?>>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['captcha_words_message'] ?></td>
  </tr>
  <tr class="captchaRow" <?php echo showHide($use_captcha==1);?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td nowrap class="warning"><?php echo $_lang['emailsender_title'] ?></td>
    <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="emailsender" value="<?php echo $emailsender; ?>" /></td>
  </tr>
   <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['emailsender_message'] ?></td>
  </tr>

  <!--for smtp-->

  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td nowrap class="warning"><?php echo $_lang['email_method_title'] ?></td>
    <td>
        <?php echo wrap_label($_lang['email_method_mail'],form_radio('email_method','mail','id="useMail"'));?><br />
        <?php echo wrap_label($_lang['email_method_smtp'],form_radio('email_method','smtp','id="useSmtp"'));?>
    </td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td nowrap class="warning"><?php echo $_lang['smtp_auth_title'] ?></td>
    <td>
        <?php echo wrap_label($_lang['yes'],form_radio('smtp_auth','1' ));?><br />
        <?php echo wrap_label($_lang['no'],form_radio('smtp_auth','0' ));?>
    </td>
  </tr>
  <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  
  <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td nowrap class="warning"><?php echo $_lang['smtp_secure_title'] ?></td>
    <td >
     <select name="smtp_secure" size="1" class="inputBox">
  <option value="none" ><?php echo $_lang['no'] ?></option>
   <option value="ssl" <?php if($smtp_secure == 'ssl') echo "selected='selected'"; ?> >SSL</option>
  <option value="tls" <?php if($smtp_secure == 'tls') echo "selected='selected'"; ?> >TLS</option>
 </select>
 <br />
  </td>
  </tr>
  <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  
  <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td nowrap class="warning"><?php echo $_lang['smtp_host_title'] ?></td>
    <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtp_host" value="<?php echo $smtp_host; ?>" /></td>
  </tr>
  <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td nowrap class="warning"><?php echo $_lang['smtp_port_title'] ?></td>
    <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtp_port" value="<?php echo $smtp_port; ?>" /></td>
  </tr>
  <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td nowrap class="warning"><?php echo $_lang['smtp_username_title'] ?></td>
    <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtp_username" value="<?php echo $smtp_username; ?>" /></td>
  </tr>
   <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td nowrap class="warning"><?php echo $_lang['smtp_password_title'] ?></td>
    <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtppw" value="********************" autocomplete="off" /></td>
  </tr>
  <tr class="smtpRow" <?php echo showHide($email_method=='smtp');?>>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td nowrap class="warning"><?php echo $_lang['emailsubject_title'] ?>
      <br />
      <p><?php echo $_lang['update_settings_from_language']; ?></p>
      <select name="reload_emailsubject" id="reload_emailsubject_select" onchange="confirmLangChange(this, 'emailsubject_default', 'emailsubject_field');">
<?php echo get_lang_options('emailsubject_default');?>
      </select>
    </td>
    <td ><input id="emailsubject_field" name="emailsubject" onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" value="<?php echo $emailsubject; ?>" />
        <input type="hidden" name="emailsubject_default" id="emailsubject_default_hidden" value="<?php echo addslashes($_lang['emailsubject_default']);?>" />
    </td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['emailsubject_message'] ?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td nowrap class="warning" valign="top"><?php echo $_lang['signupemail_title'] ?>
      <br />
      <p><?php echo $_lang['update_settings_from_language']; ?></p>
      <select name="reload_signupemail_message" id="reload_signupemail_message_select" onchange="confirmLangChange(this, 'system_email_signup', 'signupemail_message_textarea');">
<?php echo get_lang_options('system_email_signup');?>
      </select>
    </td>
    <td> <textarea id="signupemail_message_textarea" name="signupemail_message" style="width:100%; height: 120px;"><?php echo $signupemail_message; ?></textarea>
        <input type="hidden" name="system_email_signup_default" id="system_email_signup_hidden" value="<?php echo addslashes($_lang['system_email_signup']);?>" />
    </td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['signupemail_message'] ?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td nowrap class="warning" valign="top"><?php echo $_lang['websignupemail_title'] ?>
      <br />
      <p><?php echo $_lang['update_settings_from_language']; ?></p>
      <select name="reload_websignupemail_message" id="reload_websignupemail_message_select" onchange="confirmLangChange(this, 'system_email_websignup', 'websignupemail_message_textarea');">
<?php echo get_lang_options('system_email_websignup');?>
      </select>
    </td>
    <td> <textarea id="websignupemail_message_textarea" name="websignupemail_message" style="width:100%; height: 120px;"><?php echo $websignupemail_message; ?></textarea>
        <input type="hidden" name="system_email_websignup_default" id="system_email_websignup_hidden" value="<?php echo addslashes($_lang['system_email_websignup']);?>" />
    </td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['websignupemail_message'] ?></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td nowrap class="warning" valign="top"><?php echo $_lang['webpwdreminder_title'] ?>
      <br />
      <p><?php echo $_lang['update_settings_from_language']; ?></p>
      <select name="reload_system_email_webreminder_message" id="reload_system_email_webreminder_select" onchange="confirmLangChange(this, 'system_email_webreminder', 'system_email_webreminder_textarea');">
<?php echo get_lang_options('system_email_webreminder');?>
      </select>
    </td>
    <td> <textarea id="system_email_webreminder_textarea" name="webpwdreminder_message" style="width:100%; height: 120px;"><?php echo $webpwdreminder_message; ?></textarea>
        <input type="hidden" name="system_email_webreminder_default" id="system_email_webreminder_hidden" value="<?php echo addslashes($_lang['system_email_webreminder']);?>" />
    </td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class="comment"><?php echo $_lang['webpwdreminder_message'] ?></td>
  </tr>
  <tr>
    <td colspan="2">
        <?php
            // invoke OnUserSettingsRender event
            $evtOut = $modx->invokeEvent('OnUserSettingsRender');
            if(is_array($evtOut)) echo implode("",$evtOut);
        ?>
    </td>
  </tr>
</table>
</div>
