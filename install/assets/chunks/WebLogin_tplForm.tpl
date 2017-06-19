/**
 * WebLogin_tplForm
 * 
 * WebLogin Tpl
 * 
 * @category	chunk
 * @version 	1.1
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @modx_category Demo Content
 * @internal    @overwrite true
 * @internal    @installset base, sample
 */
<!-- #declare:separator <hr> -->
<!-- login form section-->
<form method="post" name="loginfrm" action="[+action+]">
	<input type="hidden" value="[+rememberme+]" name="rememberme">
	<div class="form-group">
		<label for="username">User:</label>
		<input type="text" name="username" id="username" tabindex="1" class="form-control" onkeypress="return webLoginEnter(document.loginfrm.password);" value="[+username+]">
	</div>
	<div class="form-group">
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" tabindex="2" class="form-control" onkeypress="return webLoginEnter(document.loginfrm.cmdweblogin);" value="">
	</div>
	<div class="checkbox">
		<label>
			<input type="checkbox" id="checkbox_1" name="checkbox_1" tabindex="3" size="1" value="" [+checkbox+] onclick="webLoginCheckRemember()"> Remember me
		</label>
	</div>
	<input type="submit" value="[+logintext+]" name="cmdweblogin" class="btn btn-primary">
	<a href="#" onclick="webLoginShowForm(2);return false;" id="forgotpsswd" class="btn btn-text">Forget Your Password?</a>
</form>
<hr>
<!-- log out hyperlink section -->
<h4>You're already logged in</h4>
Do you wish to <a href="[+action+]" class="button">[+logouttext+]</a>?
<hr>
<!-- Password reminder form section -->
<form name="loginreminder" method="post" action="[+action+]">
	<input type="hidden" name="txtpwdrem" value="0">
	<h4>It happens to everyone...</h4>
	<div class="form-group">
		<label for="txtwebemail">Enter the email address of your account to reset your password:</label>
		<input type="text" name="txtwebemail" id="txtwebemail">
	</div>
	<label>To return to the login form, press the cancel button.</label>
	<input type="submit" value="Submit" name="cmdweblogin" class="btn btn-primary">
	<input type="reset" value="Cancel" name="cmdcancel" onclick="webLoginShowForm(1);" class="btn btn-default">
</form>
