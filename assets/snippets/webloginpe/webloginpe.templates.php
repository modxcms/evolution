<?php
	
	$wlpeDefaultFormTpl = '[+wlpe.message+]
	<div id="wlpeLogin">
		<form id="wlpeLoginForm" action="[~[*id*]~]" method="POST">
			<fieldset id="wlpeLoginFieldset">
				<legend id="wlpeLegend">Web User Login Form</legend>
				<label id="wlpeUsernameLabel" for="wlpeUsername">Username
					<input id="wlpeUsername" type="text" name="username" />
				</label>
				
				<label id="wlpePasswordLabel" for="wlpePassword">Password
					<input id="wlpePassword" type="password" name="password" />
				</label>
				
				<label id="wlpeStayLoggedInLabel" for="wlpeStayLoggedIn">Stay Logged In
					<select id="wlpeStayLoggedIn" name="stayloggedin">
						<option value="">No</option>
						<option value="3600">1 Hour</option>
						<option value="86400">1 Day</option>
						<option value="604800">1 Week</option>
						<option value="2678400">1 Month</option>
						<option value="315569260">Forever</option>
					</select>
				</label>
			</fieldset>
			<fieldset id="wlpeLoginButtons">
				<button type="submit" id="wlpeLoginButton" name="service" value="login">Login</button>
				<button type="submit" id="wlpeReminderButton" name="service" value="forgot">Forgot Password</button>
				<button type="submit" id="wlpeRegisterButton" name="service" value="registernew">Register</button>
			</fieldset>
		</form>
	</div>';
	
	$wlpeDefaultSuccessTpl = '[+wlpe.message+]
	<div id="wlpeUser">
		<div id="wlpeUserInfo">
			<div id="wlpeWelcome">
				<img id="wlpeMyProfileImg" src="[+user.photo+]" alt="[+user.username+]" title="[+user.username+]" height="70" width="70" />
				<p id="wlpeWelcomeParagraph">Welcome back [+user.username+]!</p>
			</div>
			<p id="wlpeLoginCount">You have logged into [(site_name)] [+user.logincount+] times now.</p>
			<p>Your last login was [+user.lastlogin+]</p>
			<blockquote>
				[+user.comment+]
			</blockquote>
		</div>
		<form id="wlpeUserForm" action="[~[*id*]~]" method="POST">
			<fieldset id="wlpeUserButtons">
				<button type="submit" id="wlpeLogoutButton" name="service" value="logout">Log Out</button>
				<button type="submit" id="wlpeProfileButton" name="service" value="profile">Profile</button>
			</fieldset>
		</form>
	</div>';
	
	$wlpeProfileTpl = '[+wlpe.message+]
	<div id="wlpeUser">
		<form enctype="multipart/form-data" id="wlpeUserProfileForm" action="[~[*id*]~]" method="POST">
			<fieldset id="wlpeUserProfileInput">
				<div id="wlpeUserInfo">
					<h3 id="wlpeProfileWelcome">Hello [+user.fullname+] ([+user.username+])!</h3>
					<p id="wlpeProfileInfo" class="info">Use this form to update your profile information</p>
				</div>
			
				<legend>Your User Profile</legend>

				<label for="wlpeUserProfileFullName">Full Name
				<input id="wlpeUserProfileFullName" type="text" name="fullname" value="[+user.fullname+]" />
				</label>

				<label for="wlpeUserProfileEmail">Email
				<input id="wlpeUserProfileEmail" type="text" name="email" value="[+user.email+]" />
				</label>

				<label for="wlpeUserProfilePhone">Phone number
				<input id="wlpeUserProfilePhone" type="text" name="phone" value="[+user.phone+]" />
				</label>

				<label for="wlpeUserProfileMobile">Mobile number
				<input id="wlpeUserProfileMobile" type="text" name="mobilephone" value="[+user.mobilephone+]" />
				</label>

				<label for="wlpeUserProfileFax">Fax number
				<input id="wlpeUserProfileFax" type="text" name="fax" value="[+user.fax+]" />
				</label>

				<label for="wlpeUserProfileState">State
				<input id="wlpeUserProfileState" type="text" name="state" value="[+user.state+]" />
				</label>

				<label for="wlpeUserProfileZip">Zip Code
				<input id="wlpeUserProfileZip" type="text" name="zip" value="[+user.zip+]" />
				</label>

				[+form.country+]

				<label for="wlpeUserProfileDob">Date of birth <span class="info">(DD-MM-YYYY)</span>
				<input id="wlpeUserProfileDob" type="text" name="dob" value="[+user.dob+]" />
				</label>

				[+form.gender+]
				
				<label for="wlpeUserProfileComment">Comment/Signature
				<textarea id="wlpeUserProfileComment" name="comment">[+user.comment+]</textarea>
				</label>

				<img id="wlpeUserProfilePhotoImg" src="[+user.photo+]" alt="[+user.username+]" title="[+user.fullname+]" height="100" width="100" />

				<label for="wlpeUserProfilePhoto" id="wlpeUserPhotoLabel">User Photo
				<input type="hidden" id="wlpeUserHiddenPhoto" name="userphoto" value="[+user.photo+]" />
				<input id="wlpeUserProfilePhoto" type="file" name="photo" value="" />
				</label>

				<p id="wlpeUserProfilePhotoInfo" class="info">No bigger than 100kb. will be resized to 100 x 100.</p>
								
				<fieldset id="wlpeNewPasswordArea">
					<legend id="wlpeNewPasswordAreaLegend">Change your password</legend>
					<p id="wlpeNewPasswordInfo">Change your password <br /><span class="info">(leave blank if you do not want a new password).</span></p>

					<label for="wlpeUserProfilePassword">New Password
					<input id="wlpeUserProfilePassword" type="password" name="password" />
					</label>

					<label for="wlpeUserProfilePasswordConfirm">New Password (confirm)
					<input id="wlpeUserProfilePasswordConfirm" type="password" name="password.confirm" />
					</label>
				</fieldset>
				
			</fieldset>
			<fieldset id="wlpeUserProfileButtons">
				<button type="submit" id="wlpeSaveProfileButton" name="service" value="saveprofile">Save</button>
				<button type="submit" id="wlpeProfileDoneButton" name="service" value="cancel">Done</button>
				<button type="submit" id="wlpeProfileLogoutButton" name="service" value="logout">Logout</button>
				<button type="submit" id="wlpeProfileDeleteButton" name="service" value="deleteprofile">Delete My Profile</button>
			</fieldset>
		</form>
	</div>';
	
	$wlpeProfileDeleteTpl = '[+wlpe.message+]
	<div id="wlpeProfileDelete">
		<form id="wlpeProfileDeleteForm" name="profileDelteForm" action="[~[*id*]~]" method="POST">
			<fieldset id="wlpeProfileDeleteFieldset">
				<legend id="wlpeProfileDeleteFieldsetLegend">Delete Your Profile</legend>
				<h1 id="wlpeProfileDeleteWarning" class="warning">WARNING!</h1>
				<p>You are about to delete your profile. Are you sure you want to continue?</p>
				
			</fieldset>
			<fieldset id="wlpeProfileDeleteButtonsFieldset">
				<button type="submit" id="wlpeProfileDeleteButton" name="service" value="confirmdeleteprofile">YES! DELETE my profile</button>
				<button type="submit" id="wlpeProfileCancelButton" name="service" value="doNotDelete">NO! Keep my profile</button>
			</fieldset>
		</form>
	</div>';
	
	$wlpeActivateTpl = '[+wlpe.message+]
	<div id="wlpeActivate">
		<form id="wlpeActivateForm" name="wlpeActivateForm" action="[~[*id*]~]" method="POST">
			<fieldset id="wlpeActivateFieldset">
				<input type="hidden" name="userid" value="[+request.userid+]" />
				<input type="hidden" name="activationkey" value="[+request.activationkey+]" />
				
				<label for="wlpeActivationPassword">Activation password
				<input type="text" id="wlpeActivationPassword" name="activationpassword" />
				</label>
				<label for="wlpeNewPassword">New password
				<input type="password" id="wlpeNewPassword" name="newpassword" />
				</label>
				<label for="wlpeNewPasswordConfirm">New password (Confirm)
				<input type="password" id="wlpeNewPasswordConfirm" name="newpassword.confirm" />
				</label>
				
			</fieldset>
			<fieldset id="wlpeActivateButtonFieldset">
				<button type="submit" id="wlpeActivateButton" name="service" value="activated">Activate</button>
			</fieldset>
		</form>
	</div>';
	
	$wlpeResetTpl = '[+wlpe.message+]
	<div id="wlpeReset">
		<form id="wlpeResetForm" name="wlpeResetForm" action="[~[*id*]~]" method="POST">
			<fieldset id="wlpeResetFieldset">
				<h3 id="wlpeResetInfo">Don\'t Worry, it happens to everyone.</h3>
				<p>Enter your email address in the field below and we will set a temporary password for your account.</p>
				<p>This temporary password will be emailed to you with instructions on how to activate it.</p>
				<label for="wlpeResetEmail">Your Email Address
				<input type="text" id="wlpeResetEmail" name="email" />
				</label>
				
			</fieldset>
			<fieldset id="wlpeResetButtonFieldset">
				<button type="submit" id="wlpeResetButton" name="service" value="resetpassword">Send Password</button>
				<button type="submit" id="wlpeResetCancelButton" name="service" value="cancel">Cancel</button>
			</fieldset>
		</form>
	</div>';
	
	$wlpeRegisterVerifyTpl = '[+wlpe.message+]
	<div id="wlpeRegister">
		<form id="wlpeRegisterForm" name="wlpeRegisterForm" action="[~[*id*]~]" method="POST">
			<fieldset id="wlpeRegisterFieldset">
				<p class="wlpeRegisterInfo">Enter your email address, your name, and your desired username in the fields below.</p>
				<p class="wlpeRegisterInfo">A password will be emailed to you with instructions on how to activate Your account.</p>
				
				<label for="wlpeRegisterEmail">Your Email Address
				<input type="text" id="wlpeRegisterEmail" name="email" />
				</label>
				
				<label for="wlpeRegisterFullName">Your Full Name
				<input type="text" id="wlpeRegisterFullName" name="fullname" />
				</label>
				
				<label for="wlpeRegisterUserName">Your desired username
				<input type="text" id="wlpeRegisterUserName" name="username" />
				</label>
				
			</fieldset>
			<fieldset id="wlpeRegisterButtonFieldset">
				<button type="submit" id="wlpeRegisterButton" name="service" value="register">Register</button>
				<button type="submit" id="wlpeRegisterCancelButton" name="service" value="cancel">Cancel</button>
			</fieldset>
		</form>
	</div>';
		
	$wlpeTos = '<h2>Web Site Terms and Conditions of Use</h2>
	<h3>1. Terms</h3>
	<p>By accessing this web site, you are agreeing to be bound by these web site Terms and Conditions of Use, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials contained in this web site are protected by applicable copyright and trade mark law.</p>
	<h3>2. Use License</h3>

	<ol type="a">
		<li>Permission is granted to temporarily download one copy of the materials (information or software) on [(site_name)]\'s web site for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:
			<ol type="i">
				<li>modify or copy the materials;</li>
				<li>use the materials for any commercial purpose, or for any public display (commercial or non-commercial);</li>
				<li>attempt to decompile or reverse engineer any software contained on [(site_name)]\'s web site;</li>
				<li>remove any copyright or other proprietary notations from the materials; or</li>
				<li>transfer the materials to another person or "mirror" the materials on any other server.</li>
			</ol>
		</li>
		<li>This license shall automatically terminate if you violate any of these restrictions and may be terminated by [(site_name)] at any time. Upon terminating your viewing of these materials or upon the termination of this license, you must destroy any downloaded materials in your possession whether in electronic or printed format.</li>
	</ol>

	<h3>3. Disclaimer</h3>

	<ol type="a">
		<li>The materials on [(site_name)]\'s web site are provided "as is". [(site_name)] makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties, including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights. Further, [(site_name)] does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its Internet web site or otherwise relating to such materials or on any sites linked to this site.</li>
	</ol>

	<h3>4. Limitations</h3>
	<p>In no event shall [(site_name)] or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption,) arising out of the use or inability to use the materials on [(site_name)]\'s Internet site, even if [(site_name)] or a [(site_name)] authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.</p>
	<h3>5. Revisions and Errata</h3>
	<p>The materials appearing on [(site_name)]\'s web site could include technical, typographical, or photographic errors. [(site_name)] does not warrant that any of the materials on its web site are accurate, complete, or current. [(site_name)] may make changes to the materials contained on its web site at any time without notice. [(site_name)] does not, however, make any commitment to update the materials.</p>
	<h3>6. Links</h3>
	<p>[(site_name)] has not reviewed all of the sites linked to its Internet web site and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by [(site_name)] of the site. Use of any such linked web site is at the user\'s own risk.</p>
	<h3>7. Site Terms of Use Modifications</h3>
	<p>[(site_name)] may revise these terms of use for its web site at any time without notice. By using this web site you are agreeing to be bound by the then current version of these Terms and Conditions of Use.</p>
	<h3>8. Governing Law</h3>
	<p>Any claim relating to [(site_name)]\'s web site shall be governed by the laws of the State of New York without regard to its conflict of law provisions.</p>
	<p>General Terms and Conditions applicable to Use of a Web Site.</p>
	<h2>Privacy Policy</h2>
	<p>Your privacy is very important to us. Accordingly, we have developed this Policy in order for you to understand how we collect, use, communicate and disclose and make use of personal information. The following outlines our privacy policy.</p>

	<ul>
		<li>Before or at the time of collecting personal information, we will identify the purposes for which information is being collected.</li>
		<li>We will collect and use of personal information solely with the objective of fulfilling those purposes specified by us and for other compatible purposes, unless we obtain the consent of the individual concerned or as required by law.</li>
		<li>We will only retain personal information as long as necessary for the fulfillment of those purposes.</li>
		<li>We will collect personal information by lawful and fair means and, where appropriate, with the knowledge or consent of the individual concerned.</li>
		<li>Personal data should be relevant to the purposes for which it is to be used, and, to the extent necessary for those purposes, should be accurate, complete, and up-to-date.</li>
		<li>We will protect personal information by reasonable security safeguards against loss or theft, as well as unauthorized access, disclosure, copying, use or modification.</li>
		<li>We will make readily available to customers information about our policies and practices relating to the management of personal information.</li>
	</ul>

	<p>We are committed to conducting our business in accordance with these principles in order to ensure that the confidentiality of personal information is protected and maintained.</p>';
		
	$wlpeRegisterInstantTpl = '[+wlpe.message+]
	<div id="wlpeNewUser">
		<form id="wlpeUserRegisterForm" action="[~[*id*]~]" method="POST" enctype="multipart/form-data">
			<fieldset id="wlpeUserRegisterInput">
				<div id="wlpeNewUserInfo">
					<p id="wlpeRegisterInfo">Use this form to register for a new user account.<br />
						<span class="info">Filds marked with <span class="required">*</span> are required.</span>
					</p>
				</div>
				<legend>Register for a new user account</legend>
				<label for="wlpeUserRegisterEmail"><span class="required">*</span> Email
				<input id="wlpeUserRegisterEmail" type="text" name="email" value="[+post.email+]" />
				</label>

				<label for="wlpeUserRegisterUserName"><span class="required">*</span> Desired User Name
				<input id="wlpeUserRegisterUserName" type="text" name="username" value="[+post.username+]" />
				</label>

				<label for="wlpeUserRegisterFullName"><span class="required">*</span> Full Name
				<input id="wlpeUserRegisterFullName" type="text" name="fullname" value="[+post.fullname+]" />
				</label>

				<label for="wlpeUserRegisterPassword"><span class="required">*</span> Password
				<input id="wlpeUserRegisterPassword" type="password" name="password" value="[+post.password+]" />
				</label>

				<label for="wlpeUserRegisterPasswordConfirm"><span class="required">*</span> Password (confirm)
				<input id="wlpeUserRegisterPasswordConfirm" type="password" name="password.confirm" value="[+post.password.confirm+]" />
				</label>

				<label for="wlpeUserRegisterPhone">Phone number
				<input id="wlpeUserRegisterPhone" type="text" name="phone" />
				</label>

				<label for="wlpeUserRegisterMobile">Mobile number
				<input id="wlpeUserRegisterMobile" type="text" name="mobilephone" value="[+post.mobilephone+]" />
				</label>

				<label for="wlpeUserRegisterFax">Fax number
				<input id="wlpeUserRegisterFax" type="text" name="fax" value="[+post.fax+]" />
				</label>

				<label for="wlpeUserRegisterState">State
				<input id="wlpeUserRegisterState" type="text" name="state" value="[+post.state+]" />
				</label>

				<label for="wlpeUserRegisterZip">Zip Code
				<input id="wlpeUserRegisterZip" type="text" name="zip" value="[+post.zip+]" />
				</label>

				[+form.country+]

				<label for="wlpeUserRegisterDob">Date of birth <span class="info">(DD-MM-YYYY)</span>
				<input id="wlpeUserRegisterDob" type="text" name="dob" value="[+post.dob+]" />
				</label>

				[+form.gender+]
				
				<img id="wlpeUserDefaultImage" src="[+user.defaultphoto+]" alt="Default User Image" title="Default User Image" height="100" width="100" />
				
				<label for="wlpeUserProfilePhoto" id="photolabel">User Photo
				<input id="wlpeUserProfilePhoto" type="file" name="photo" />
				</label>
				<p id="wlpeUserProfilePhotoInfo" class="info">No bigger than 100kb. will be resized to 100 x 100.</p>
				
				<label for="wlpeUserRegisterComment">Comment/Signature
				<textarea id="wlpeUserRegisterComment" name="comment">[+post.comment+]</textarea>
				</label>
				
				<img id="wlpeCaptchaImage" src="manager/includes/veriword.php" width="148" height="60" alt="If you have trouble reading the code, click on the code itself to generate a new random code." />
				
				<label for="wlpeUserRegisterCaptcha" id="wlpeCaptchaLabel"><span class="required">*</span>Please enter the code in the image.
				<input type="text" id="wlpeUserRegisterCaptcha" name="formcode" >
				</label>
				
				<p id="wlpeTermsOfServiceLabel">Terms of Service/Privacy Policy</p>
				<div id="wlpeTermsOfService">[+tos+]</div>
				
				<label for="wlpeTosCheckbox" id="wlpeTosCheckboxLabel"><span class="required">*</span>I accept the Terms of Service
					<input type="checkbox" id="wlpeTosCheckbox" name="tos" />
				</label>
				
			</fieldset>
			<fieldset id="wlpeUserRegisterButtons">
				<button type="submit" id="wlpeSaveRegisterButton" name="service" value="register">Register</button>
				<button type="submit" id="wlpeCancelRegisterButton" name="service" value="cancel">Cancel</button>
			</fieldset>
		</form>
	</div>';
	
	$wlpeViewProfileTpl = '[+wlpe.message+]
	<table id="wlpeViewProfileTable">
		<tr>
			<td colspan=2 class="wlpeViewProfileHeader"><h3>Viewing the profile of "[+view.username+]":</h3></td>
		</tr>
		<tr>
			<td class="wlpeViewAttribute">Username:</td>
			<td class="wlpeViewAttributeValue">[+view.username+]</td>
		</tr>
		<tr>
			<td class="wlpeViewAttribute">Full Name:</td>
			<td class="wlpeViewAttributeValue">[+view.fullname+]</td>
		</tr>
		<tr>
			<td class="wlpeViewAttribute">Email:</td>
			<td class="wlpeViewAttributeValue">[+view.email+]</td>
		</tr>
		<tr>
			<td class="wlpeViewAttribute">Current Status:</td>
			<td class="wlpeViewAttributeValue">[+view.status+]</td>
		</tr>
		<tr>
			<td class="wlpeViewAttribute">Country:</td>
			<td class="wlpeViewAttributeValue">[+view.country+]</td>
		</tr>
		<tr>
			<td class="wlpeViewAttribute">Age:</td>
			<td class="wlpeViewAttributeValue">[+view.age+]</td>
		</tr>
		<tr>
			<td class="wlpeViewAttribute">Gender:</td>
			<td class="wlpeViewAttributeValue">[+view.gender+]</td>
		</tr>
		<tr>
			<td class="wlpeViewAttribute">Signature:</td>
			<td class="wlpeViewAttributeValue">[+view.comment+]</td>
		</tr>
		<tr>
			<td class="wlpeViewAttribute">Photo:</td>
			<td class="wlpeViewAttributeValue"><img src="[+view.photo+]" alt="[+view.photo+]" title="[+view.username+]" /></td>
		</tr>
		<tr>
			<td colspan="2" class="wlpeViewContact">
				<form name="wlpeViewContactForm" method="post" action="[~[*id*]~]">
					<fieldset id="wlpeViewContactFormFieldset">
						<h4>Contact [+view.username+]</h4>
						<input type="hidden" id="wlpeMe" name="me" value="[+user.internalKey+]" />
						<input type="hidden" id="wlpeYou" name="you" value="[+view.internalKey+]" />
						<label for"wlpeViewContactSubject" id="wlpeViewContactSubjectLabel">Subject:
							<input type="text" id="wlpeViewContactSubject" name="subject" />
						</label>
						<label for"wlpeViewContactMessage" id="wlpeViewContactMessageLabel">Message:
							<textarea id="wlpeViewContactMessage" name="message"></textarea>
						</label>
					</fieldset>
					<fieldset id="wlpeViewContactButtons">
						<button type="submit" id="wlpeContactSend" name="service" value="messageuser">Send Message to [+view.username+]</button>
					</fieldset>
				</form>
			</td>
		</tr>
	</table>';
	
	$wlpeUsersOuterTpl = '[+wlpe.message+]
	<div class="wlpeUsersList">
		<h3>[+view.title+]</h3>
		[+view.list+]
	</div>';
	
	$wlpeUsersTpl = '<div class="wlpeUserPage">
		<div class="wlpeUserPagePhoto">
			<a href = "[~[*id*]~]?service=viewprofile&username=[+view.username+]">
				<img src="[+view.photo+]" alt="[+view.photo+]" title="[+view.username+]" height="100" width="100" />
			</a>
			<a href = "[~[*id*]~]?service=viewprofile&username=[+view.username+]">
				<p class="wlpeUserPageUsername">[+view.username+]</p>
			</a>
		</div>
		<div class="wlpeUserPageUserContent">           
			<p class="wlpeUserPageAttrUsername"><span class="wlpeViewUsersUsername">Username</span>: [+view.username+]</p>
			<p class="wlpeUserPageAttrAge"><span class="wlpeViewUsersAge">Age</span>: [+view.age+]</p>
			<p class="wlpeUserPageAttrLastLogin"><span class="wlpeViewUsersLastLogin">Current Status</span>: [+view.status+]</p>
			<blockquote class="wlpeUserPageAttrComment">[+view.comment+]</blockquote>
		</div>
	</div>';
	
	$wlpeManageProfileTpl = '[+wlpe.message+]
	<div id="wlpeUser">
		<form enctype="multipart/form-data" id="wlpeUserProfileForm" action="[~[*id*]~]" method="POST">
			<fieldset id="wlpeUserProfileInput">
				<div id="wlpeUserInfo">
					<h3 id="wlpeProfileWelcome">Editing the profile of [+view.username+] ([+view.fullname+])!</h3>
					<p id="wlpeProfileInfo" class="info">Use this form to edit [+view.username+]\'s profile information</p>
				</div>

				<legend>[+view.username+]\'s User Profile</legend>
				
				<!-- These hidden fields are IMPORTANT! -->
				<input type="hidden" name="internalKey" value="[+view.internalKey+]" />
				<input type="hidden" name="username" value="[+view.username+]" />
				
				<label for="wlpeUserProfileFullName">Full Name
				<input id="wlpeUserProfileFullName" type="text" name="fullname" value="[+view.fullname+]" />
				</label>

				<label for="wlpeUserProfileEmail">Email
				<input id="wlpeUserProfileEmail" type="text" name="email" value="[+view.email+]" />
				</label>

				<label for="wlpeUserProfilePhone">Phone number
				<input id="wlpeUserProfilePhone" type="text" name="phone" value="[+view.phone+]" />
				</label>

				<label for="wlpeUserProfileMobile">Mobile number
				<input id="wlpeUserProfileMobile" type="text" name="mobilephone" value="[+view.mobilephone+]" />
				</label>

				<label for="wlpeUserProfileFax">Fax number
				<input id="wlpeUserProfileFax" type="text" name="fax" value="[+view.fax+]" />
				</label>

				<label for="wlpeUserProfileState">State
				<input id="wlpeUserProfileState" type="text" name="state" value="[+view.state+]" />
				</label>

				<label for="wlpeUserProfileZip">Zip Code
				<input id="wlpeUserProfileZip" type="text" name="zip" value="[+view.zip+]" />
				</label>

				[+form.country+]

				<label for="wlpeUserProfileDob">Date of birth <span class="info">(DD-MM-YYYY)</span>
				<input id="wlpeUserProfileDob" type="text" name="dob" value="[+view.dob+]" />
				</label>

				[+form.gender+]

				<label for="wlpeUserProfileComment">Comment/Signature
				<textarea id="wlpeUserProfileComment" name="comment">[+view.comment+]</textarea>
				</label>

				<img id="wlpeUserProfilePhotoImg" src="[+view.photo+]" alt="[+view.username+]" title="[+view.fullname+]" height="100" width="100" />

				<label for="wlpeUserProfilePhoto" id="wlpeUserPhotoLabel">User Photo
				<input type="hidden" id="wlpeUserHiddenPhoto" name="userphoto" value="[+view.photo+]" />
				<input id="wlpeUserProfilePhoto" type="file" name="photo" value="" />
				</label>

				<p id="wlpeUserProfilePhotoInfo" class="info">No bigger than 100kb. will be resized to 100 x 100.</p>

				<fieldset id="wlpeNewPasswordArea">
					<legend id="wlpeNewPasswordAreaLegend">Change your password</legend>
					<p id="wlpeNewPasswordInfo">Change your password <br /><span class="info">(leave blank if you do not want to set a new password for this user).</span></p>

					<label for="wlpeUserProfilePassword">New Password
					<input id="wlpeUserProfilePassword" type="password" name="password" value="" />
					</label>

					<label for="wlpeUserProfilePasswordConfirm">New Password (confirm)
					<input id="wlpeUserProfilePasswordConfirm" type="password" name="password.confirm" value="" />
					</label>
				</fieldset>

			</fieldset>
			<fieldset id="wlpeUserProfileButtons">
				<button type="submit" id="wlpeSaveProfileButton" name="service" value="saveuserprofile">Save</button>
				<button type="submit" id="wlpeProfileDoneButton" name="service" value="cancel">Done</button>
				<button type="submit" id="wlpeProfileDeleteButton" name="service" value="deleteuserprofile">Delete This Profile</button>
			</fieldset>
		</form>
	</div>';
	
	$wlpeManageTpl = '<form class="wlpeManageUsersForm" action="[~[*id*]~]" method="POST">
		<div class="wlpeUserPage">
			<div class="wlpeUserPagePhoto">
				<img src="[+view.photo+]" alt="[+view.photo+]" title="[+view.username+]" height="100" width="100" />
				<p class="wlpeUserPageUsername">[+view.username+]</p>
				<!-- These hidden fields are IMPORTANT! -->
				<input type="hidden" name="internalKey" value="[+view.internalKey+]" />
				<input type="hidden" name="username" value="[+view.username+]" />
			</div>
			<div class="wlpeUserPageUserContent">           
				<p class="wlpeUserPageAttrUsername"><span class="wlpeViewUsersUsername">Username</span>: [+view.username+]</p>
				<p class="wlpeUserPageAttrAge"><span class="wlpeViewUsersAge">Age</span>: [+view.age+]</p>
				<p class="wlpeUserPageAttrLastLogin"><span class="wlpeViewUsersLastLogin">Current Status</span>: [+view.status+]</p>
				
				<div class="wlpeMangeUsersButtons">
					<button class="wlpeEditButton" name="service" value="editprofile">Edit [+view.username+]</button>
					<button class="wlpeDeleteButton" name="service" value="deleteuser">Delete [+view.username+]</button>
				</div>
			</div>
		</div>
	</form>';
	
	$wlpeManageDeleteTpl = '[+wlpe.message+]
	<div id="wlpeProfileDelete">
		<form id="wlpeProfileDeleteForm" name="profileDelteForm" action="[~[*id*]~]" method="POST">
			<fieldset id="wlpeProfileDeleteFieldset">
				<legend id="wlpeProfileDeleteFieldsetLegend">Delete User Profile</legend>
				<h1 id="wlpeProfileDeleteWarning" class="warning">WARNING!</h1>
				<p>You are about to delete the profile of &quot;[+post.username+]&quot;. Are you sure you want to continue?</p>

			</fieldset>
			<fieldset id="wlpeProfileDeleteButtonsFieldset">
				<button type="submit" id="wlpeProfileDeleteButton" name="service" value="confirmdeleteuser">YES! DELETE [+post.username+]\'s profile</button>
				<button type="submit" id="wlpeProfileCancelButton" name="service" value="doNotDelete">NO! Keep this profile</button>
			</fieldset>
		</form>
	</div>';
	
	$wlpeMessageTpl = '<div class="wlpeMessage"><p class="wlpeMessageText">[+wlpe.message.text+]</p></div>';
	
	$wlpeNotifyTpl = 'Hello, my name is [+ufn+] and I just signed up at [+sname+] as "[+uid+]" using WebLoginPE.'."\n\n".' My email address is [+uem+].'."\n\n".'P.S. This message was auto generated by WebLoginPE and PHPMailer.';

?>