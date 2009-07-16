<!-- #declare:separator <hr> --> 
<!-- login form section-->
<form method="post" name="loginfrm" action="[+action+]" style="margin: 0px; padding: 0px;"> 
<input type="hidden" value="[+rememberme+]" name="rememberme"> 
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><b>User:</b></td>
    <td><input type="text" name="username" tabindex="1" onkeypress="return webLoginEnter(document.loginfrm.password);" size="8" style="width: 150px;" value="[+username+]" /></td>
  </tr>
  <tr>
    <td><b>Password:</b></td>
    <td><input type="password" name="password" tabindex="2" onkeypress="return webLoginEnter(document.loginfrm.cmdweblogin);" style="width: 150px;" value="" /></td>
  </tr>
  <tr>
    <td><label for="chkbox" style="cursor:pointer">Remember me:&nbsp; </label></td>
    <td>
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><input type="checkbox" id="chkbox" name="chkbox" tabindex="4" size="1" value="" [+checkbox+] onclick="webLoginCheckRemember()" /></td>
        <td align="right">                                    
        <input type="submit" value="[+logintext+]" name="cmdweblogin" /></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td colspan="2"><a href="#" onclick="webLoginShowForm(2);return false;">Forget Password?</a></td>
  </tr>
</table>
</td>
</tr>
</table>
</form>
<hr>
<!-- log out hyperlink section -->
<a href='[+action+]'>[+logouttext+]</a>
<hr>
<!-- Password reminder form section -->
<form name="loginreminder" method="post" action="[+action+]" style="margin: 0px; padding: 0px;">
<input type="hidden" name="txtpwdrem" value="0" />
<table border="0">
    <tr>
      <td>Enter the email address of your account <br />below to receive your password:</td>
    </tr>
    <tr>
      <td><input type="text" name="txtwebemail" size="24" /></td>
    </tr>
    <tr>
      <td align="right"><input type="submit" value="Submit" name="cmdweblogin" />
      <input type="reset" value="Cancel" name="cmdcancel" onclick="webLoginShowForm(1);" /></td>
    </tr>
  </table>
</form>

