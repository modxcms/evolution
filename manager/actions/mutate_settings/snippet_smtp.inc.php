<table style="border:1px dotted #ccc;padding:8px;">
  <tr>
    <td><?php echo $_lang['smtp_auth_title'] ?></td>
    <td>
        <?php echo wrap_label($_lang['yes'],form_radio('smtp_auth','1' ));?><br />
        <?php echo wrap_label($_lang['no'],form_radio('smtp_auth','0' ));?>
    </td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  
  <tr>
    <td><?php echo $_lang['smtp_secure_title'] ?></td>
    <td >
     <select name="smtp_secure" size="1" class="inputBox">
  <option value="none" ><?php echo $_lang['no'] ?></option>
   <option value="ssl" <?php if($smtp_secure == 'ssl') echo "selected='selected'"; ?> >SSL</option>
  <option value="tls" <?php if($smtp_secure == 'tls') echo "selected='selected'"; ?> >TLS</option>
 </select>
 <br />
  </td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  
  <tr>
    <td><?php echo $_lang['smtp_host_title'] ?></td>
    <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtp_host" value="<?php echo $smtp_host; ?>" /></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td><?php echo $_lang['smtp_port_title'] ?></td>
    <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtp_port" value="<?php echo $smtp_port; ?>" /></td>
  </tr>
  <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td><?php echo $_lang['smtp_username_title'] ?></td>
    <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtp_username" value="<?php echo $smtp_username; ?>" /></td>
  </tr>
   <tr>
    <td colspan="2"><div class="split"></div></td>
  </tr>
  <tr>
    <td><?php echo $_lang['smtp_password_title'] ?></td>
    <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtppw" value="********************" autocomplete="off" /></td>
  </tr>
</table>
