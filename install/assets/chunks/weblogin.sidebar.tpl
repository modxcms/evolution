/**
 * WebLoginSideBar
 * 
 * WebLogin Tpl
 * 
 * @category	chunk
 * @version 	1.1
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal @modx_category Login
 * @internal    @installset base, sample
 */
<!-- #declare:separator <hr> -->
<!-- login form section-->
<form method="post" name="loginfrm" action="[+action+]">
    <input type="hidden" value="[+rememberme+]" name="rememberme" />
    <fieldset>
        <h3>Your Login Details</h3>
        <label for="username">User: <input type="text" name="username" id="username" tabindex="1" onkeypress="return webLoginEnter(document.loginfrm.password);" value="[+username+]" /></label>
    	<label for="password">Password: <input type="password" name="password" id="password" tabindex="2" onkeypress="return webLoginEnter(document.loginfrm.cmdweblogin);" value="" /></label>
    	<input type="checkbox" id="checkbox_1" name="checkbox_1" tabindex="3" size="1" value="" [+checkbox+] onclick="webLoginCheckRemember()" /><label for="checkbox_1" class="checkbox">Remember me</label>
    	<input type="submit" value="[+logintext+]" name="cmdweblogin" class="button" />
	<a href="#" onclick="webLoginShowForm(2);return false;" id="forgotpsswd">Forget Your Password?</a>
	</fieldset>
</form>
<hr>
<!-- log out hyperlink section -->
<h4>You're already logged in</h4>
Do you wish to <a href="[+action+]" class="button">[+logouttext+]</a>?
<hr>
<!-- Password reminder form section -->
<form name="loginreminder" method="post" action="[+action+]">
    <fieldset>
        <h3>It happens to everyone...</h3>
        <input type="hidden" name="txtpwdrem" value="0" />
        <label for="txtwebemail">Enter the email address of your account to reset your password: <input type="text" name="txtwebemail" id="txtwebemail" size="24" /></label>
        <label>To return to the login form, press the cancel button.</label>
    	<input type="submit" value="Submit" name="cmdweblogin" class="button" /> <input type="reset" value="Cancel" name="cmdcancel" onclick="webLoginShowForm(1);" class="button" style="clear:none;display:inline" />
    </fieldset>
</form>

