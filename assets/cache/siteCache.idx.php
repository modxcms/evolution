<?php
$c=&$this->config;
$c['manager_theme'] = "MODxLight";
$c['settings_version'] = "0.9.6.3";
$c['server_offset_time'] = "0";
$c['server_protocol'] = "http";
$c['manager_language'] = "english";
$c['modx_charset'] = "UTF-8";
$c['site_name'] = "My MODx Site";
$c['site_start'] = "1";
$c['error_page'] = "7";
$c['unauthorized_page'] = "4";
$c['site_status'] = "1";
$c['site_unavailable_message'] = "The site is currently unavailable";
$c['track_visitors'] = "0";
$c['resolve_hostnames'] = "0";
$c['top_howmany'] = "10";
$c['default_template'] = "1";
$c['old_template'] = "1";
$c['publish_default'] = "0";
$c['cache_default'] = "1";
$c['search_default'] = "1";
$c['friendly_urls'] = "1";
$c['friendly_url_prefix'] = "";
$c['friendly_url_suffix'] = "";
$c['friendly_alias_urls'] = "1";
$c['use_alias_path'] = "1";
$c['use_udperms'] = "1";
$c['udperms_allowroot'] = "0";
$c['failed_login_attempts'] = "3";
$c['blocked_minutes'] = "60";
$c['use_captcha'] = "0";
$c['captcha_words'] = "MODx,Access,Better,BitCode,Cache,Desc,Design,Excell,Enjoy,URLs,TechView,Gerald,Griff,Humphrey,Holiday,Intel,Integration,Joystick,Join(),Tattoo,Genetic,Light,Likeness,Marit,Maaike,Niche,Netherlands,Ordinance,Oscillo,Parser,Phusion,Query,Question,Regalia,Righteous,Snippet,Sentinel,Template,Thespian,Unity,Enterprise,Verily,Veri,Website,WideWeb,Yap,Yellow,Zebra,Zygote";
$c['emailsender'] = "you@yourdomain.com";
$c['emailsubject'] = "Your login details";
$c['signupemail_message'] = "Hello [+uid+] \r\n\r\nHere are your login details for [+sname+] Content Manager:\r\n\r\nUsername: [+uid+]\r\nPassword: [+pwd+]\r\n\r\nOnce you log into the Content Manager at [+surl+], you can change your password.\r\n\r\nRegards,\r\nSite Administrator";
$c['websignupemail_message'] = "Hello [+uid+] \r\n\r\nHere are your login details for [+sname+]:\r\n\r\nUsername: [+uid+]\r\nPassword: [+pwd+]\r\n\r\nOnce you log into [+sname+] at [+surl+], you can change your password.\r\n\r\nRegards,\r\nSite Administrator";
$c['webpwdreminder_message'] = "Hello [+uid+]\r\n\r\nTo active you new password click the following link:\r\n\r\n[+surl+]\r\n\r\nIf successful you can use the following password to login:\r\n\r\nPassword:[+pwd+]\r\n\r\nIf you did not request this email then please ignore it.\r\n\r\nRegards,\r\nSite Administrator";
$c['number_of_logs'] = "100";
$c['number_of_messages'] = "30";
$c['number_of_results'] = "20";
$c['use_editor'] = "1";
$c['use_browser'] = "1";
$c['rb_base_dir'] = "/Applications/MAMP/htdocs/modx/assets/";
$c['rb_base_url'] = "assets/";
$c['which_editor'] = "none";
$c['fe_editor_lang'] = "english";
$c['fck_editor_toolbar'] = "standard";
$c['fck_editor_autolang'] = "0";
$c['editor_css_path'] = "";
$c['editor_css_selectors'] = "";
$c['strip_image_paths'] = "1";
$c['upload_images'] = "jpg,jpeg,png,gif,psd,ico,bmp";
$c['upload_media'] = "mp3,wav,au,wmv,avi,mpg,mpeg";
$c['upload_flash'] = "swf,fla";
$c['upload_files'] = "txt,php,html,htm,xml,js,css,cache,zip,gz,rar,z,tgz,tar,htaccess,mp3,mp4,aac,wav,au,wmv,avi,mpg,mpeg,pdf,doc,xls,txt";
$c['upload_maxsize'] = "1048576";
$c['new_file_permissions'] = "0644";
$c['new_folder_permissions'] = "0755";
$c['show_preview'] = "0";
$c['filemanager_path'] = "/Applications/MAMP/htdocs/modx/";
$c['theme_refresher'] = "";
$c['manager_layout'] = "4";
$c['custom_contenttype'] = "text/css,text/html,text/javascript,text/plain,text/xml";
$c['auto_menuindex'] = "1";
$c['session.cookie.lifetime'] = "604800";
$c['mail_check_timeperiod'] = "60";
$c['manager_lang_attribute'] = "en";
$c['manager_direction'] = "ltr";
$c['tinymce_editor_theme'] = "editor";
$c['tinymce_custom_plugins'] = "style,advimage,advlink,searchreplace,print,contextmenu,paste,fullscreen,nonbreaking,xhtmlxtras,visualchars,media";
$c['tinymce_custom_buttons1'] = "undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,link,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help";
$c['tinymce_custom_buttons2'] = "bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops";
$c['tree_show_protected'] = "0";
$c['validate_referer'] = "0";
$c['site_id'] = "4952613391056";
$c['xhtml_urls'] = "0";
$c['site_unavailable_page'] = "";
$c['allow_duplicate_alias'] = "1";
$c['automatic_alias'] = "0";
$c['rb_webuser'] = "0";
$c['tinymce_custom_buttons3'] = "";
$c['tinymce_custom_buttons4'] = "";
$c['tinymce_css_selectors'] = "";
$c['rss_url_news'] = "http://feeds.feedburner.com/modx-announce";
$c['rss_url_security'] = "http://feeds.feedburner.com/modxsecurity";
$this->aliasListing = array();
$a = &$this->aliasListing;
$d = &$this->documentListing;
$m = &$this->documentMap;
$d['index'] = 1;
$a[1] = array('id' => 1, 'alias' => 'index', 'path' => '');
$m[] = array('0' => '1');
$d['blog'] = 2;
$a[2] = array('id' => 2, 'alias' => 'blog', 'path' => '');
$m[] = array('0' => '2');
$d['request-an-account'] = 5;
$a[5] = array('id' => 5, 'alias' => 'request-an-account', 'path' => '');
$m[] = array('0' => '5');
$d['doc-not-found'] = 7;
$a[7] = array('id' => 7, 'alias' => 'doc-not-found', 'path' => '');
$m[] = array('0' => '7');
$d['search-results'] = 8;
$a[8] = array('id' => 8, 'alias' => 'search-results', 'path' => '');
$m[] = array('0' => '8');
$d['feed.rss'] = 11;
$a[11] = array('id' => 11, 'alias' => 'feed.rss', 'path' => '');
$m[] = array('0' => '11');
$d['features'] = 15;
$a[15] = array('id' => 15, 'alias' => 'features', 'path' => '');
$m[] = array('0' => '15');
$d['geting-help'] = 33;
$a[33] = array('id' => 33, 'alias' => 'geting-help', 'path' => '');
$m[] = array('0' => '33');
$d['templates'] = 39;
$a[39] = array('id' => 39, 'alias' => 'templates', 'path' => '');
$m[] = array('0' => '39');
$d['design'] = 32;
$a[32] = array('id' => 32, 'alias' => 'design', 'path' => '');
$m[] = array('0' => '32');
$d['login'] = 4;
$a[4] = array('id' => 4, 'alias' => 'login', 'path' => '');
$m[] = array('0' => '4');
$d['blog-login'] = 37;
$a[37] = array('id' => 37, 'alias' => 'blog-login', 'path' => '');
$m[] = array('0' => '37');
$d['thank-you'] = 46;
$a[46] = array('id' => 46, 'alias' => 'thank-you', 'path' => '');
$m[] = array('0' => '46');
$d['contact-us'] = 6;
$a[6] = array('id' => 6, 'alias' => 'contact-us', 'path' => '');
$m[] = array('0' => '6');
$d['blog/article-1126081344'] = 9;
$a[9] = array('id' => 9, 'alias' => 'article-1126081344', 'path' => 'blog');
$m[] = array('2' => '9');
$d['blog/article-1128398162'] = 18;
$a[18] = array('id' => 18, 'alias' => 'article-1128398162', 'path' => 'blog');
$m[] = array('2' => '18');
$d['blog/add-a-blog-entry'] = 3;
$a[3] = array('id' => 3, 'alias' => 'add-a-blog-entry', 'path' => 'blog');
$m[] = array('2' => '3');
$d['features/ajax'] = 16;
$a[16] = array('id' => 16, 'alias' => 'ajax', 'path' => 'features');
$m[] = array('15' => '16');
$d['features/menus'] = 22;
$a[22] = array('id' => 22, 'alias' => 'menus', 'path' => 'features');
$m[] = array('15' => '22');
$d['features/cms'] = 14;
$a[14] = array('id' => 14, 'alias' => 'cms', 'path' => 'features');
$m[] = array('15' => '14');
$d['features/extendable'] = 24;
$a[24] = array('id' => 24, 'alias' => 'extendable', 'path' => 'features');
$m[] = array('15' => '24');
$d['features/prints'] = 47;
$a[47] = array('id' => 47, 'alias' => 'prints', 'path' => 'features');
$m[] = array('15' => '47');
$d['templates/modxhost_tpl'] = 44;
$a[44] = array('id' => 44, 'alias' => 'modxhost_tpl', 'path' => 'templates');
$m[] = array('39' => '44');
$d['templates/modxcss_wide'] = 42;
$a[42] = array('id' => 42, 'alias' => 'modxcss_wide', 'path' => 'templates');
$m[] = array('39' => '42');
$d['templates/modxcss'] = 43;
$a[43] = array('id' => 43, 'alias' => 'modxcss', 'path' => 'templates');
$m[] = array('39' => '43');
$c = &$this->contentTypes;
$c[1] = 'text/html';
$c[2] = 'text/html';
$c[3] = 'text/html';
$c[4] = 'text/html';
$c[5] = 'text/html';
$c[6] = 'text/html';
$c[7] = 'text/html';
$c[8] = 'text/html';
$c[9] = 'text/html';
$c[11] = 'text/xml';
$c[14] = 'text/html';
$c[15] = 'text/html';
$c[16] = 'text/html';
$c[18] = 'text/html';
$c[22] = 'text/html';
$c[24] = 'text/html';
$c[32] = 'text/html';
$c[33] = 'text/html';
$c[37] = 'text/html';
$c[39] = 'text/html';
$c[42] = 'text/html';
$c[43] = 'text/html';
$c[44] = 'text/html';
$c[46] = 'text/html';
$c[47] = 'text/html';
$c = &$this->chunkCache;
$c['WebLoginSideBar'] = '<!-- #declare:separator <hr> --> 
<!-- login form section-->
<form method="post" name="loginfrm" action="[+action+]" style="margin: 0px; padding: 0px;"> 
<input type="hidden" value="[+rememberme+]" name="rememberme"> 
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td><b>User:</b></td>
	<td><input type="text" name="username" tabindex="1" onkeypress="return webLoginEnter(document.loginfrm.password);" size="5" style="width: 100px;" value="[+username+]" /></td>
  </tr>
  <tr>
	<td><b>Password:</b></td>
	<td><input type="password" name="password" tabindex="2" onkeypress="return webLoginEnter(document.loginfrm.cmdweblogin);" size="5" style="width: 100px;" value="" /></td>
  </tr>
  <tr>
	<td><label for="chkbox" style="cursor:pointer">Remember me:&nbsp; </label></td>
	<td>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td valign="top"><input type="checkbox" id="chkbox" name="chkbox" tabindex="4" size="1" value="" [+checkbox+] onClick="webLoginCheckRemember()" /></td>
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
<a href=\'[+action+]\'>[+logouttext+]</a>
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

';
$c['FormBlog'] = '<form name="NewsPublisher" method="post" action="[~[*id*]~]">
    <fieldset>
        <h3>Publishing Details</h3>
        <p>Note: Leaving the Publish Date empty will immediately publish your blog entry.</p>
        <input name="NewsPublisherForm" type="hidden" value="on" />
    	<label for="pagetitle">Page title <abbr title="The title used on the browser window">?</abbr>: <input name="pagetitle" id="pagetitle" type="text" size="40" value="[+pagetitle+]" /></label><br />
    	<label for="longtitle">Headline <abbr title="The title used on the article">?</abbr>: <input name="longtitle" id="longtitle" type="text" size="40" value="[+longtitle+]" /></label><br />

    	<label for="pub_date">Published date: <input name="pub_date" id="pub_date" type="text" value="[+pub_date+]" size="40" readonly="readonly" />
    	<a onclick="nwpub_cal1.popup();" onmouseover="window.status=\'Select date\'; return true;" onmouseout="window.status=\'\'; return true;"><img src="manager/media/style/MODxLight/images/icons/cal.gif" width="16" height="16" alt="Select date" /></a>
    	<a onclick="document.NewsPublisher.pub_date.value=\'\'; return true;" onmouseover="window.status=\'Remove date\'; return true;" onmouseout="window.status=\'\'; return true;"><img src="manager/media/style/MODxLight/images/icons/cal_nodate.gif" width="16" height="16" alt="Remove date" /></a></label>
	</fieldset>
	
	<fieldset>
    	<h3>The Content</h3>
    	<p>The Summary field is optional, but is used as a short version for RSS feeds and summary views on the main blog page.</p>
    	<label for="introtext">Summary (optional, but encouraged):<textarea name="introtext" cols="50" rows="5">[+introtext+]</textarea></label><br />
    	<label for="content">Content:[*blogContent*]</label>
	</fieldset>
	
	<fieldset>
    	<h3>You\'re Done</h3>
		<label>Now... wasn\'t that easy?</label>
    	<input name="send" type="submit" value="Blog it!" class="button" />
	</fieldset>	
</form>
<script language="JavaScript" src="manager/media/script/datefunctions.js"></script>
<script type="text/javascript">
		var elm_txt = {}; // dummy
		var pub = document.forms["NewsPublisher"].elements["pub_date"];
		var nwpub_cal1 = new calendar1(pub,elm_txt);
		nwpub_cal1.path="[+base_url+]manager/media/";
		nwpub_cal1.year_scroll = true;
		nwpub_cal1.time_comp = true;	
</script>';
$c['FormLogin'] = '<!-- #declare:separator <hr> --> 
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
<h4>You\'re already logged in</h4>
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
';
$c['FormSignup'] = '<!-- #declare:separator <hr> --> 
<!-- login form section-->
<form method="post" name="websignupfrm" action="[+action+]">
    <fieldset>
        <h3>User Details</h3>
        <p>Items marked by * are required</p>
		<label for="username">User name:* <input type="text" name="username" id="username" class="inputBox" size="20" maxlength="30" value="[+username+]" /></label>
        <label for="fullname">Full name: <input type="text" name="fullname" id="fullname" class="inputBox" size="20" maxlength="100" value="[+fullname+]" /></label>
		<label for="email">Email address:* <input type="text" name="email" id="email" class="inputBox" size="20" value="[+email+]" /></label>
	</fieldset>
	
	<fieldset>
	    <h3>Password</h3>
	    <label for="password">Password:* <input type="password" name="password" id="password" class="inputBox" size="20" /></label>
	    <label for="confirmpassword">Confirm password:* <input type="password" name="confirmpassword" id="confirmpassword" class="inputBox" size="20" /></label>
	</fieldset>
	
	<fieldset>
		<h3>Optional Account Profile Info</h3>
		<label for="country">Country:</label>
		<select size="1" name="country" id="country">
			<option value="" selected="selected">&nbsp;</option>
			<option value="1">Afghanistan</option>
			<option value="2">Albania</option>
			<option value="3">Algeria</option>
			<option value="4">American Samoa</option>
			<option value="5">Andorra</option>
			<option value="6">Angola</option>
			<option value="7">Anguilla</option>
			<option value="8">Antarctica</option>
			<option value="9">Antigua and Barbuda</option>
			<option value="10">Argentina</option>
			<option value="11">Armenia</option>
			<option value="12">Aruba</option>
			<option value="13">Australia</option>
			<option value="14">Austria</option>
			<option value="15">Azerbaijan</option>
			<option value="16">Bahamas</option>
			<option value="17">Bahrain</option>
			<option value="18">Bangladesh</option>
			<option value="19">Barbados</option>
			<option value="20">Belarus</option>
			<option value="21">Belgium</option>
			<option value="22">Belize</option>
			<option value="23">Benin</option>
			<option value="24">Bermuda</option>
			<option value="25">Bhutan</option>
			<option value="26">Bolivia</option>
			<option value="27">Bosnia and Herzegowina</option>
			<option value="28">Botswana</option>
			<option value="29">Bouvet Island</option>
			<option value="30">Brazil</option>
			<option value="31">British Indian Ocean Territory</option>
			<option value="32">Brunei Darussalam</option>
			<option value="33">Bulgaria</option>
			<option value="34">Burkina Faso</option>
			<option value="35">Burundi</option>
			<option value="36">Cambodia</option>
			<option value="37">Cameroon</option>
			<option value="38">Canada</option>
			<option value="39">Cape Verde</option>
			<option value="40">Cayman Islands</option>
			<option value="41">Central African Republic</option>
			<option value="42">Chad</option>
			<option value="43">Chile</option>
			<option value="44">China</option>
			<option value="45">Christmas Island</option>
			<option value="46">Cocos (Keeling) Islands</option>
			<option value="47">Colombia</option>
			<option value="48">Comoros</option>
			<option value="49">Congo</option>
			<option value="50">Cook Islands</option>
			<option value="51">Costa Rica</option>
			<option value="52">Cote D&#39;Ivoire</option>
			<option value="53">Croatia</option>
			<option value="54">Cuba</option>
			<option value="55">Cyprus</option>
			<option value="56">Czech Republic</option>
			<option value="57">Denmark</option>
			<option value="58">Djibouti</option>
			<option value="59">Dominica</option>
			<option value="60">Dominican Republic</option>
			<option value="61">East Timor</option>
			<option value="62">Ecuador</option>
			<option value="63">Egypt</option>
			<option value="64">El Salvador</option>
			<option value="65">Equatorial Guinea</option>
			<option value="66">Eritrea</option>
			<option value="67">Estonia</option>
			<option value="68">Ethiopia</option>
			<option value="69">Falkland Islands (Malvinas)</option>
			<option value="70">Faroe Islands</option>
			<option value="71">Fiji</option>
			<option value="72">Finland</option>
			<option value="73">France</option>
			<option value="74">France, Metropolitan</option>
			<option value="75">French Guiana</option>
			<option value="76">French Polynesia</option>
			<option value="77">French Southern Territories</option>
			<option value="78">Gabon</option>
			<option value="79">Gambia</option>
			<option value="80">Georgia</option>
			<option value="81">Germany</option>
			<option value="82">Ghana</option>
			<option value="83">Gibraltar</option>
			<option value="84">Greece</option>
			<option value="85">Greenland</option>
			<option value="86">Grenada</option>
			<option value="87">Guadeloupe</option>
			<option value="88">Guam</option>
			<option value="89">Guatemala</option>
			<option value="90">Guinea</option>
			<option value="91">Guinea-bissau</option>
			<option value="92">Guyana</option>
			<option value="93">Haiti</option>
			<option value="94">Heard and Mc Donald Islands</option>
			<option value="95">Honduras</option>
			<option value="96">Hong Kong</option>
			<option value="97">Hungary</option>
			<option value="98">Iceland</option>
			<option value="99">India</option>
			<option value="100">Indonesia</option>
			<option value="101">Iran (Islamic Republic of)</option>
			<option value="102">Iraq</option>
			<option value="103">Ireland</option>
			<option value="104">Israel</option>
			<option value="105">Italy</option>
			<option value="106">Jamaica</option>
			<option value="107">Japan</option>
			<option value="108">Jordan</option>
			<option value="109">Kazakhstan</option>
			<option value="110">Kenya</option>
			<option value="111">Kiribati</option>
			<option value="112">Korea, Democratic People&#39;s Republic of</option>
			<option value="113">Korea, Republic of</option>
			<option value="114">Kuwait</option>
			<option value="115">Kyrgyzstan</option>
			<option value="116">Lao People&#39;s Democratic Republic</option>
			<option value="117">Latvia</option>
			<option value="118">Lebanon</option>
			<option value="119">Lesotho</option>
			<option value="120">Liberia</option>
			<option value="121">Libyan Arab Jamahiriya</option>
			<option value="122">Liechtenstein</option>
			<option value="123">Lithuania</option>
			<option value="124">Luxembourg</option>
			<option value="125">Macau</option>
			<option value="126">Macedonia, The Former Yugoslav Republic of</option>
			<option value="127">Madagascar</option>
			<option value="128">Malawi</option>
			<option value="129">Malaysia</option>
			<option value="130">Maldives</option>
			<option value="131">Mali</option>
			<option value="132">Malta</option>
			<option value="133">Marshall Islands</option>
			<option value="134">Martinique</option>
			<option value="135">Mauritania</option>
			<option value="136">Mauritius</option>
			<option value="137">Mayotte</option>
			<option value="138">Mexico</option>
			<option value="139">Micronesia, Federated States of</option>
			<option value="140">Moldova, Republic of</option>
			<option value="141">Monaco</option>
			<option value="142">Mongolia</option>
			<option value="143">Montserrat</option>
			<option value="144">Morocco</option>
			<option value="145">Mozambique</option>
			<option value="146">Myanmar</option>
			<option value="147">Namibia</option>
			<option value="148">Nauru</option>
			<option value="149">Nepal</option>
			<option value="150">Netherlands</option>
			<option value="151">Netherlands Antilles</option>
			<option value="152">New Caledonia</option>
			<option value="153">New Zealand</option>
			<option value="154">Nicaragua</option>
			<option value="155">Niger</option>
			<option value="156">Nigeria</option>
			<option value="157">Niue</option>
			<option value="158">Norfolk Island</option>
			<option value="159">Northern Mariana Islands</option>
			<option value="160">Norway</option>
			<option value="161">Oman</option>
			<option value="162">Pakistan</option>
			<option value="163">Palau</option>
			<option value="164">Panama</option>
			<option value="165">Papua New Guinea</option>
			<option value="166">Paraguay</option>
			<option value="167">Peru</option>
			<option value="168">Philippines</option>
			<option value="169">Pitcairn</option>
			<option value="170">Poland</option>
			<option value="171">Portugal</option>
			<option value="172">Puerto Rico</option>
			<option value="173">Qatar</option>
			<option value="174">Reunion</option>
			<option value="175">Romania</option>
			<option value="176">Russian Federation</option>
			<option value="177">Rwanda</option>
			<option value="178">Saint Kitts and Nevis</option>
			<option value="179">Saint Lucia</option>
			<option value="180">Saint Vincent and the Grenadines</option>
			<option value="181">Samoa</option>
			<option value="182">San Marino</option>
			<option value="183">Sao Tome and Principe</option>
			<option value="184">Saudi Arabia</option>
			<option value="185">Senegal</option>
			<option value="186">Seychelles</option>
			<option value="187">Sierra Leone</option>
			<option value="188">Singapore</option>
			<option value="189">Slovakia (Slovak Republic)</option>
			<option value="190">Slovenia</option>
			<option value="191">Solomon Islands</option>
			<option value="192">Somalia</option>
			<option value="193">South Africa</option>
			<option value="194">South Georgia and the South Sandwich Islands</option>
			<option value="195">Spain</option>
			<option value="196">Sri Lanka</option>
			<option value="197">St. Helena</option>
			<option value="198">St. Pierre and Miquelon</option>
			<option value="199">Sudan</option>
			<option value="200">Suriname</option>
			<option value="201">Svalbard and Jan Mayen Islands</option>
			<option value="202">Swaziland</option>
			<option value="203">Sweden</option>
			<option value="204">Switzerland</option>
			<option value="205">Syrian Arab Republic</option>
			<option value="206">Taiwan</option>
			<option value="207">Tajikistan</option>
			<option value="208">Tanzania, United Republic of</option>
			<option value="209">Thailand</option>
			<option value="210">Togo</option>
			<option value="211">Tokelau</option>
			<option value="212">Tonga</option>
			<option value="213">Trinidad and Tobago</option>
			<option value="214">Tunisia</option>
			<option value="215">Turkey</option>
			<option value="216">Turkmenistan</option>
			<option value="217">Turks and Caicos Islands</option>
			<option value="218">Tuvalu</option>
			<option value="219">Uganda</option>
			<option value="220">Ukraine</option>
			<option value="221">United Arab Emirates</option>
			<option value="222">United Kingdom</option>
			<option value="223">United States</option>
			<option value="224">United States Minor Outlying Islands</option>
			<option value="225">Uruguay</option>
			<option value="226">Uzbekistan</option>
			<option value="227">Vanuatu</option>
			<option value="228">Vatican City State (Holy See)</option>
			<option value="229">Venezuela</option>
			<option value="230">Viet Nam</option>
			<option value="231">Virgin Islands (British)</option>
			<option value="232">Virgin Islands (U.S.)</option>
			<option value="233">Wallis and Futuna Islands</option>
			<option value="234">Western Sahara</option>
			<option value="235">Yemen</option>
			<option value="236">Yugoslavia</option>
			<option value="237">Zaire</option>
			<option value="238">Zambia</option>
			<option value="239">Zimbabwe</option>
			</select>
        </fieldset>
        
        <fieldset>
            <h3>Bot-Patrol</h3>
            <p>Enter the word/number combination shown in the image below.</p>
            <label>Form code:* 
            <input type="text" name="formcode" class="inputBox" size="20" /></label>
            <a href="[+action+]"><img align="top" src="manager/includes/veriword.php" width="148" height="60" alt="If you have trouble reading the code, click on the code itself to generate a new random code." style="border: 1px solid #039" /></a>
        </fieldset>
        
        <fieldset>
            <input type="submit" value="Submit" name="cmdwebsignup" />
	</fieldset>
</form>

<script language="javascript" type="text/javascript"> 
	var id = "[+country+]";
	var f = document.websignupfrm;
	var i = parseInt(id);	
	if (!isNaN(i)) f.country.options[i].selected = true;
</script>
<hr>
<!-- notification section -->
<p class="message">Signup completed successfully!<br />
Your account was created. A copy of your signup information was sent to your email address.</p>
';
$c['FormBlogComments'] = '<a name="comments"></a>
<p style="margin-top: 1em;font-weight:bold">Enter your comments in the space below (registered site users only):</p>
[!UserComments? &canpost=`Registered Users, Site Admins` &makefolder=`0` &postcss=`comment` &titlecss=`commentTitle` &numbercss=`commentNum` &altrowcss=`commentAlt` &authorcss=`commentAuthor` &ownercss=`commentMe` &sortorder=`0`!]';
$c['nl_sidebar'] = '<strong><a href="[~[+id+]~]" title="[+title+]">[+title+]</a></strong><br />
[+longtitle+]<br /><br />';
$c['styles'] = '<div id="modxhost">The CSS Themes can only be used on the MODxCSS and MODxCSSW Layouts</div>
<script type="text/javascript">$(\'modxhost\').style.display=\'none\';</script>
<ul class="links">
<li><a href="#" onclick="setActiveStyleSheet(\'Trend\'); return false;">Trend (Default)</a></li>
<li><a href="#" onclick="setActiveStyleSheet(\'Trend (Alternate)\'); return false;" >Trend (Alternate)</a></li>
<li><a href="#" onclick="setActiveStyleSheet(\'ZiX\'); return false;" >ZiX (Clean)</a></li>
<li><a href="#" onclick="setActiveStyleSheet(\'ZiX Background\'); return false;" >ZiX (Background)</a></li>
<li><a href="#" onclick="setActiveStyleSheet(\'Light\'); return false;" >Light</a></li>
<li><a href="#" onclick="setActiveStyleSheet(\'Light Green\'); return false;" >Light Green</a></li>
<li><a href="#" onclick="setActiveStyleSheet(\'Dark\'); return false;" >Dark</a></li>
    </ul>';
$c['ditto_blog'] = '<div class="ditto_summaryPost">
  <h3><a href="[~[+id+]~]" title="[+title+]">[+title+]</a></h3>
  <div class="ditto_info" >By <strong>[+author+]</strong> on [+date+]. <a  href="[~[+id+]~]#commentsAnchor">Comments
  ([!Jot?&docid=`[+id+]`&action=`count-comments`!])</a></div><div class="ditto_tags">Tags: [+tagLinks+]</div>
  [+summary+]
  <p class="ditto_link">[+link+]</p>
</div>';
$c['footer'] = '[(site_name)] is powered by <a href="http://modxcms.com/" title="Powered by MODx, Do more with less.">MODx CMS</a> |
      <span id="andreas">Design by <a href="http://andreasviklund.com/">Andreas Viklund</a></span>
<span id="zi" style="display: none">Designed by <a href="http://ziworks.com/" target="_blank" title="E-Business &amp; webdesign solutions">ziworks</a></span>

<!-- the modx icon -->

<div id="modxicon"><h6><a href="http://modxcms.com" title="MODx - The XHTML, CSS and Ajax CMS and PHP Application Framework" id="modxicon32">MODx - The XHTML, CSS and Ajax CMS and PHP Application Framework</a></h6></div>';
$c['meta'] = '<p><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></p>                	<p><a href="http://jigsaw.w3.org/css-validator/check/referer" title="This page uses valid Cascading Stylesheets" rel="external">Valid <abbr title="W3C Cascading Stylesheets">css</abbr></a></p>				    <p><a href="http://modxcms.com/" title="Powered by MODx, Do more with less.">MOD<strong>x</strong></a></p>';
$c['mh.InnerRowTpl'] = '<li[+wf.classes+]><a href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]</a>[+wf.wrapper+]</li>';
$c['mh.InnerTpl'] = '<ul style="display:none">
  [+wf.wrapper+]
</ul>';
$c['mh.OuterTpl'] = '  <ul id="myajaxmenu">
    [+wf.wrapper+]
  </ul>';
$c['mh.RowTpl'] = '<li class="category [+wf.classnames+]"><a href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]</a>[+wf.wrapper+]</li>';
$c['Comments'] = '<div id="commentsAnchor">
[!Jot? &customfields=`name,email` &subscribe=`1` &pagination=`4` &badwords=`dotNet` &canmoderate=`Site Admins` !]
</div>';
$c['ContactForm'] = '<p class="error">[+validationmessage+]</p>

<form method="post" action="[~[*id*]~]" id="EmailForm" name="EmailForm">

	<fieldset>
		<h3> Contact Form</h3>

		<input name="formid" type="hidden" value="ContactForm" />

		<label for="cfName">Your name:
		<input name="name" id="cfName" class="text" type="text" eform="Your Name::1:" /> </label>

		<label for="cfEmail">Your Email Address:
		<input name="email" id="cfEmail" class="text" type="text" eform="Email Address:email:1" /> </label>

		<label for="cfRegarding">Regarding:</label>
		<select name="subject" id="cfRegarding" eform="Form Subject::1">
			<option value="General Inquiries">General Inquiries</option>
			<option value="Press">Press or Interview Request</option>
			<option value="Partnering">Partnering Opportunities</option>
		</select>

		<label for="cfMessage">Message: 
		<textarea name="message" id="cfMessage" rows="4" cols="20" eform="Message:textarea:1"></textarea>
		</label>

		<label>&nbsp;</label><input type="submit" name="contact" id="cfContact" class="button" value="Send This Message" />

	</fieldset>

</form>
';
$c['ContactFormReport'] = '<p>This is a response sent by <b>[+name+]</b> using the feedback form on the website. The details of the message follow below:</p>


<p>Name: [+name+]</p>
<p>Email: [+email+]</p>
<p>Regarding: [+subject+]</p>
<p>comments:<br />[+message+]</p>

<p>You can use this link to reply: <a href="mailto:[+email+]?subject=RE: [+subject+]">[+email+]</a></p>
';
$c['reflect_month_tpl'] = '<a href="[+url+]" title="[+month+] [+year+]" class="reflect_month_link">[+month+] [+year+]</a>';
$c['ContactStyles'] = '<style type="text/css">
div.errors{ color:#F00; }
#EmailForm .invalidValue{ background: #FFDFDF; border:1px solid #F00; }
#EmailForm .requiredValue{ background: #FFFFDF; border:1px solid #F00; }
</style>';
$s = &$this->snippetCache;
$s['AjaxSearch'] = '/* -----------------------------------------------------------------------------
:: Snippet: AjaxSearch
--------------------------------------------------------------------------------
  Short Description: 
        Ajax-driven & Flexible Search form

  Version:
        1.8.1

  Date: 02/10/2008
  
  Created by:
	    Coroico (coroico@wangba.fr)
	    Jason Coward (opengeek - jason@opengeek.com)
	    Kyle Jaebker (kylej - kjaebker@muddydogpaws.com)
	    Ryan Thrash  (rthrash - ryan@vertexworks.com)
	    
	    Live Search by Thomas (Shadock)
	    Fixes & Additions by identity/Perrine/mikkelwe
	    Document selection from Ditto by Mark Kaplan
	    
  Copyright & Licencing:
  ----------------------
  GNU General Public License (GPL) (http://www.gnu.org/copyleft/gpl.html)

  Originally based on the FlexSearchForm snippet created by jaredc (jaredc@honeydewdesign.com)

--------------------------------------------------------------------------------
:: Description
--------------------------------------------------------------------------------

    The AjaxSearch snippet is an enhanced version of the original FlexSearchForm
    snippet for MODx. This snippet adds AJAX functionality on top of the robust 
    content searching.
    
    - search in title, description, content and TVs of documents
    - search in a subset of documents
    - highlighting of searchword in the results returned

    It could works in two modes:

    ajaxSearch mode : 
    - Search results displayed in current page through AJAX request
    - Multiple search options including live search and non-AJAX option
    - Available link to view all results in a new page (FSF) when only a subset is retuned
    - Customize the number of results returned
    - Uses the MooTools js library for AJAX and visual effects

    non-ajaxSearch mode (FSF) :
    - Search results displayed in a new page
    - customize the paginating of results
    - works without JS enabled as FlexSearchForm
    - designed to load only the required FSF code


MORE : See the ajaxSearch.readme.txt file for more informations

----------------------------------------------------------------------------- */
global $modx;

// ajaxSearch version being executed
define(\'AS_VERSION\', \'1.8.1\');

// Path where ajaxSearch is installed
define(\'AS_SPATH\', \'assets/snippets/ajaxSearch/\');

//include snippet file
define (\'AS_PATH\', $modx->config[\'base_path\'].AS_SPATH);

//------------------------------------------------------------------------------
// Configure - general AjaxSearch snippet setup options
//------------------------------------------------------------------------------
$cfg = array();

// &config [config_name | "default"] (optional)
// Load a custom configuration
// config_name - Other configs installed in the configs folder or in any folder within the MODx base path via @FILE
// Configuration files are named in the form: <config_name>.config.php
$config = (isset($config)) ? $config : "default";
$cfg[\'config\'] = $config;
$as_config = (substr($config, 0, 5) != "@FILE") ? AS_PATH."configs/$config.config.php" : $modx->config[\'base_path\'].trim(substr($config, 5));
if (file_exists($as_config)) include $as_config;
else return  "<h3>" .$as_config . " not found !<br />Check your config parameter or your config file name!</h3>";

// ajax Search version - Don\'t change!
$cfg[\'version\'] = AS_VERSION;

// &debug = [ 0 | 1 | 2 | 3 | -1 | -2 | -3 ]
// by default: 0 - no logs
// 1,2,3 : File mode - Output logged into a file named ajaxSearch_log.txt in the ajaxSearch folder
// -1,-2,-3 : FireBug mode. The trace is logged into the Firebug console of Mozilla.
$cfg[\'debug\'] = isset($debug)? $debug : (isset($__debug)? $__debug : 0);

// &language [ language_name | manager_language ] (optional)
// with manager_language = $modx->config[\'manager_language\'] by default 
$cfg[\'language\'] = isset($language) ? $language : (isset($__language) ? $__language : $modx->config[\'manager_language\']);

// &ajaxSearch [1 | 0] (as passed in snippet variable ONLY)
// Use this to display the search results using ajax You must include the Mootools library in your template
$cfg[\'ajaxSearch\'] = isset($ajaxSearch) ? $ajaxSearch : (isset($__ajaxSearch) ? $__ajaxSearch : 1);

// &advSearch [ \'exactphrase\' | \'allwords\' | \'nowords\' | \'oneword\' ]
// Advanced search    
// - exactphrase : provides the documents which contain the exact phrase 
// - allwords : provides the documents which contain all the words
// - nowords : provides the documents which do not contain the words
// - oneword : provides the document which contain at least one word [default]
$cfg[\'advSearch\'] = isset($advSearch) ? $advSearch : (isset($__advSearch) ? $__advSearch : \'oneword\');

// &whereSearch     
// Define where should occur the search
// a separated list of keywords describing the tables where to search
// keywords allowed : 
// "content" for site_content, "tv" for site_tmplvar_contentvalues, "jot" for jot_content, "maxigallery" for maxigallery
// you could add your own keywords. But the keyword should be a user function which describes the tables to use
// by default all the text fields are searchable but you could specify the fields like this:
// whereSearch=`content:pagetitle,introtext,content|tv:tv_value|maxigallery:gal_title`
$cfg[\'whereSearch\'] = isset($whereSearch) ? $whereSearch : (isset($__whereSearch) ? $__whereSearch : \'content|tv\');

// &subSearch  [ int , int ]
// Define the maximum number of choice and the default choice selected
// by default 5 choices and default choice 1 selected
$cfg[\'subSearch\'] = isset($subSearch) ? $subSearch : (isset($__subSearch) ? $__subSearch : \'5,1\');

// &withTvs - Define which Tvs are used for the search in Tvs
// a comma separated list of TV names
// by default all TVs are used (empty list)
$cfg[\'withTvs\'] = isset($withTvs) ? $withTvs : (isset($__withTvs) ? $__withTvs : \'\');

// &order - Define the sort order of results
// Comma separated list of fields defined as searchable in the table definition
// by default : \'pub_date,pagetitle\'
// to suppress the sorting, use &order=``
$cfg[\'order\'] = isset($order) ? $order : (isset($__order) ? $__order : \'publishedon,pagetitle\');

// &rank - Define the rank of search results. Results are sorted by rank value
// Comma separated list of fields with optionally user defined weight
// by default : \'pagetitle:100,extract\'
// to suppress the rank sorting, use &rank=``; 
// &rank sort occurs after the &order sort
$cfg[\'rank\'] = isset($rank) ? $rank : (isset($__rank) ? $__rank : \'pagetitle:100,extract\');

// &minChars [ int ]
// Minimum number of characters to require for a word to be valid for searching.
// MySQL will typically NOT search for words with less than 4 characters (relevance mode). 
// If you have $advSearch = \'allwords\', \'oneword\' or \'nowords\' and a three or 
// fewer letter words appears in the search string, the results will always be 0. 
// Setting this drops those words from the search in THAT CIRCUMSTANCE ONLY 
// (relevance mode, advsearch = \'allwords\', \'oneword\' or \'nowords\')
$cfg[\'minChars\'] = isset($minChars) ? intval($minChars) : (isset($__minChars) ? intval($__minChars) : 3);

// &AS_showForm [0 | 1]
// If you would like to turn off the search form when showing results you can set this to false.(1=true, 0=false)
$cfg[\'AS_showForm\'] = isset($AS_showForm ) ? $AS_showForm : (isset($__AS_showForm ) ? $__AS_showForm : 1);

// &resultsPage [int]
// The default behavior is to show the results on the current page, but you may define the results page any way you like. The priority is:
// 1- snippet variable - set in page template like this: [[AjaxSearch? AS_landing=int]]
//    where int is the page id number of the page you want your results on
// 2- querystring variable AS_form
// 3- variable set here
// 4- use current page
// This is VERY handy when you want to put the search form in a discrete and/or small place on your page- like a side column, but don\'t want all your results to show up there!
// Set to results page or leave 0 as default
$cfg[\'resultsPage\'] = 0;

// &grabMax [ int ]
// Set to the max number of records you would like on each page. Set to 0 if unlimited.
$cfg[\'grabMax\'] = isset($grabMax)? intval($grabMax) : (isset($__grabMax)? intval($__grabMax) : 10);

// &extract [ n:searchable fields list | 1:content,description,introtext,tv_content]
// show the search terms highlighted in a little extract
// n : maximum number of extracts displayed
// ordered searchable fields list : separated list of fields define as searchable in the table definition
// by default : 1:content,description,introtext,tv_content - One extract from content then description,introtext,tv_content 
$cfg[\'extract\'] = isset($extract) ? $extract : (isset($__extract) ? $__extract : \'1:content,description,introtext,tv_content\');

// &extractLength [int]
// Length of extract around the search words found - between 50 and 800 characters
$cfg[\'extractLength\'] = isset($extractLength) ? intval($extractLength) : (isset($__extractLength) ? intval($__extractLength) : 200);

// &extractEllips [ string ]
// Ellipside to mark the star and the end of  an extract when the sentence is cutting
// by default : \'...\'
$cfg[\'extractEllips\'] = isset($extractEllips) ? $extractEllips : (isset($__extractEllips) ? $__extractEllips : \'...\');

// &extractSeparator [ string ]
// Any html tag to mark the separation between extracts
// by default : \'<br />\' but you could also choose for instance \'<hr />\'
$cfg[\'extractSeparator\'] = isset($extractSeparator) ? $extractSeparator : (isset($__extractSeparator) ? $__extractSeparator : \'<br />\');

// &formatDate [ string ]
// The format of outputted dates. See http://www.php.net/manual/en/function.date.php
// by default : "d/m/y : H:i:s" e.g: 21/01/08 : 23:09:22
$cfg[\'formatDate\'] = isset($formatDate) ? $formatDate : (isset($__formatDate) ? $__formatDate : "d/m/y : H:i:s");

// &highlightResult [1 | 0]
// create links so that search terms will be highlighted when linked page clicked
$cfg[\'highlightResult\'] = isset($highlightResult) ? $highlightResult : (isset($__highlightResult) ? $__highlightResult : 1);

// &pageLinkSeparator [ string ]
// What you want, if anything, between your page link numbers
$cfg[\'pageLinkSeparator\'] = isset($pageLinkSeparator) ? $pageLinkSeparator : (isset($__pageLinkSeparator) ? $__pageLinkSeparator : " | ");

// &AS_landing  [int] set the page to show the results page (non Ajax search)
$cfg[\'AS_landing\'] = isset($AS_landing) ? $AS_landing : (isset($__AS_landing) ? $__AS_landing : false);

// &AS_showResults  [1 | 0]  establish whether to show the results or not
$cfg[\'AS_showResults\'] = isset($AS_showResults) ? $AS_showResults : (isset($__AS_showResults) ? $__AS_showResults : true);

// type of IDs - (INTERNAL USE)
$cfg[\'idType\'] = isset($documents) ? "documents" : "parents";

// &parents [ comma separated list of IDs | \'\' ]  
// IDs of documents to retrieve their children to &depth depth  where to do the search - - empty list by default
$cfg[\'parents\'] = isset($parents) ? $parents : (isset($__parents) ? $__parents : \'\');

// &documents [ comma separated list of IDs | \'\' ]  
// IDs of documents where to do the search - empty list by default
$cfg[\'documents\'] = isset($documents) ? $documents : (isset($__documents) ? $__documents : \'\');

// &depth [ int | 10 ] Number of levels deep to retrieve documents
$cfg[\'depth\'] = isset($depth) ? intval($depth): (isset($__depth) ? intval($__depth) : 10);

// &hideMenu [0 | 1| 2]  Search in hidden documents from menu.
// 0 - search only in documents visible from menu
// 1 - search only in documents hidden from menu
// 2 - search in hidden or visible documents from menu [default]
$cfg[\'hideMenu\'] = isset($hideMenu) ? $hideMenu : (isset($__hideMenu) ? $__hideMenu : 2);

// &hideLink [0 | 1 ]   Search in content of type reference (link) 
// 0 - search only in content of type document
// 1 - search in content of type document AND reference (default)
$cfg[\'hideLink\'] = isset($hideLink) ? $hideLink : (isset($__hideLink) ? $__hideLink : 1);

// &filter - Basic filtering : remove unwanted documents that meets the criteria of the filter
// See Ditto 2 Basic filtering for more information : http://ditto.modxcms.com/tutorials/basic_filtering.html
$cfg[\'filter\'] = isset($filter) ? $filter : (isset($__filter) ? $__filter : \'\');

// &tplLayout - Chunk to style the ajaxSearch input form and layout
$cfg[\'tplLayout\'] = isset($tplLayout) ? $tplLayout : (isset($__tplLayout) ? $__tplLayout : "@FILE:".AS_SPATH.\'templates/layout.tpl.html\');

// &tplResults - Chunk to style the non-ajax output results outer
$cfg[\'tplResults\'] = isset($tplResults) ? $tplResults : (isset($__tplResults) ? $__tplResults : "@FILE:".AS_SPATH.\'templates/results.tpl.html\');

// &tplResult - Chunk to style each output result
$cfg[\'tplResult\'] = isset($tplResult) ? $tplResult : (isset($__tplResult) ? $__tplResult : "@FILE:".AS_SPATH.\'templates/result.tpl.html\');

// &tplPaging - Chunk to style the paging links
$cfg[\'tplPaging\'] = isset($tplPaging) ? $tplPaging : (isset($__tplPaging) ? $__tplPaging : "@FILE:".AS_SPATH.\'templates/paging.tpl.html\');

// &stripInput - stripInput user function name
$cfg[\'stripInput\'] = isset($stripInput) ? $stripInput : (isset($__stripInput) ? $__stripInput : \'defaultStripInput\');

// &stripOutput - stripOutput user function name
$cfg[\'stripOutput\'] = isset($stripOutput) ? $stripOutput : (isset($__stripOutput) ? $__stripOutput : \'defaultStripOutput\');

// &searchWordList - searchWordList user function name
// [user_function_name,params] where params is an optional array of parameters
$cfg[\'searchWordList\'] = isset($searchWordList) ? $searchWordList : (isset($__searchWordList) ? $__searchWordList : \'\');

// &breadcrumbs
// 0 : disallow the breadcrumbs link
// Name of the breadcrumbs function : allow the breadcrumbs link
// The function name could be followed by some parameter initialization
// e.g: &breadcrumbs=`Breadcrumbs,showHomeCrumb:0,showCrumbsAtHome:1`
$cfg[\'breadcrumbs\'] = isset($breadcrumbs) ? $breadcrumbs : (isset($__breadcrumbs) ? $__breadcrumbs : 0);

// &tvPhx - Set placeHolders for TV (template variables)
// 0 : disallow the feature (default)
// \'tv:displayTV\' : set up a placeholder named [+as.tvName.+] for each TV (named tvName) linked to the documents found
// displayTV is a provided ajaxSearch function which render the TV output
// tvPhx could also be used with custom tables (see examples on www.modx.wangba.fr)
$cfg[\'tvPhx\'] = isset($tvPhx) ? $tvPhx : (isset($__tvPhx) ? $__tvPhx : 0);

// &jsClearDefault - Clearing default text
// Set this to 1 if you would like to include the clear default js function
// add the class "cleardefault" to your input text form and set this parameter
$cfg[\'clearDefault\'] = isset($clearDefault) ? $clearDefault : (isset($__clearDefault) ? $__clearDefault : 0);

// &jsSearchInput - Location of the js library
// mandatory to protect the site against JS cross scripting attacks
$cfg[\'jsClearDefault\'] = AS_SPATH . \'js/clearDefault.js\';


//------------------------------------------------------------------------------
// Configure - Ajax mode snippet setup options
//------------------------------------------------------------------------------

if ($cfg[\'ajaxSearch\']){  // ajax mode
    // $ajaxSearchType [1 | 0] (as passed in snippet variable ONLY)
    // Use this to display the search results using ajax
    // Set this to 1 if you would like to use the live search (i.e. results as you type)
    $cfg[\'ajaxSearchType\'] = isset($ajaxSearchType) ? $ajaxSearchType : (isset($__ajaxSearchType) ? $__ajaxSearchType : 0);
    
    // &ajaxMax [int] - The maximum number of results to show for the ajaxsearch
    $cfg[\'ajaxMax\'] = isset($ajaxMax) ? $ajaxMax : (isset($__ajaxMax) ? $__ajaxMax : 6);
    
    // &showMoreResults [1 | 0]
    // Set this to 1 if you would like a link to show all of the search results
    $cfg[\'showMoreResults\'] = isset($showMoreResults) ? $showMoreResults : (isset($__showMoreResults) ? $__showMoreResults : 0);
    
    // &moreResultsPage [int]
    // The document id of the page you want the more results link to point to
    $cfg[\'moreResultsPage\'] = isset($moreResultsPage ) ? $moreResultsPage : (isset($__moreResultsPage ) ? $__moreResultsPage : 0);
    
    // &opacity - set the opacity of the div ajaxSearch_output 
    $cfg[\'opacity\'] = isset($opacity) ? $opacity : (isset($__opacity) ? $__opacity : 1.);
    
    // &tplAjaxResults - Chunk to style the ajax output results outer
    $cfg[\'tplAjaxResults\'] = isset($tplAjaxResults) ? $tplAjaxResults : (isset($__tplAjaxResults) ? $__tplAjaxResults : \'\');
    
    // &tplAjaxResult - Chunk to style each output result
    $cfg[\'tplAjaxResult\'] = isset($tplAjaxResult) ? $tplAjaxResult : (isset($__tplAjaxResult) ? $__tplAjaxResult : \'\');

    // &jScript [\'jquery\'|\'mootools\']
    // Set this to jquery if you would like use the jquery library
    // Default: mootools
    $cfg[\'jscript\'] = isset($jscript ) ? $jscript : (isset($__jscript ) ? $__jscript : \'mootools\');
    
    // &addJscript [1 | 0]
    // Set this to 1 if you would like to include or not the mootool/jquery library
    // in the header of your pages automatically.
    $cfg[\'addJscript\'] = isset($addJscript ) ? $addJscript : (isset($__addJscript ) ? $__addJscript : 1);
    
    // &jsMootools - Location of the mootools javascript library
    $cfg[\'jsMooTools\'] = \'manager/media/script/mootools/mootools.js\';
        
    // &jsQuery - Location of the jquery javascript library
    $cfg[\'jsJquery\'] = AS_SPATH . \'js/jQuery/jquery.js\';
}

include_once AS_PATH."classes/ajaxSearch.class.inc.php";
  
if (class_exists(\'AjaxSearch\')) {
  $as = new ajaxSearch($cfg);
  //Process ajaxSearch
  $output = $as->run();
} else {
  $output = "<h3>error: AjaxSearch class not found</h3>";
}
return $output;';
$s['Breadcrumbs'] = '/*
 * Breadcrumbs
 *
 * This snippet shows the path through the various levels of site structure. It
 * is NOT necessarily the path the user took to arrive at a given page.
 * Version: 1.0.1
 */

/* -----------------------------------------------------------------------------
 * CONFIGURATION
 * -----------------------------------------------------------------------------
 * This section contains brief explanations of the available parameters.
 */

/* General setup
 * -----------------------------------------------------------------------------
 */

/* $maxCrumbs [ integer ]
 * Max number of elemetns to have in a breadcrumb path. The default 100 is an
 * arbitrarily high number that will essentially include everything. If you were
 * to set it to 2, and you were 5 levels deep, it would appear like:
 * HOME > ... > Level 3 > Level 4 > CURRENT PAGE
 * It should be noted that the "home" link, and the current page do not count as
 * they are managed by their own configuration settings.
 */
( isset($maxCrumbs) ) ? $maxCrumbs : $maxCrumbs = 100;

/* $pathThruUnPub [ 1 | 0 ]
 * When your path includes an unpublished folder, setting this to 1 (true) will
 * show all documents in path EXCEPT the unpublished. When set to 0 (false), the
 * path will not go "through" that unpublished folder and will stop there.
 */
( isset($pathThruUnPub) ) ? $pathThruUnPub : $pathThruUnPub = 1;

/* $respectHidemenu [ 0 | 1 ]
 * Setting this to 1 (true) will respect the hidemenu setting of the document
 * and not include it in trail.
 */
( isset($respectHidemenu) ) ? (int)$respectHidemenu : $respectHidemenu = 1;

/* $showCurrentCrumb [ 1 | 0 ]
 * Include the current page at the end of the trail. On by default.
 */
( isset($showCurrentCrumb) ) ? $showCurrentCrumb : $showCurrentCrumb = 1;

/* $currentAsLink [ 1 | 0 ]
 * If the current page is included, this parameter will show it as a link (1) or
 * just plain text (0).
 */
( $currentAsLink ) ? $currentAsLink : $currentAsLink = 0;

/* $linkTextField [ string ]
 * Prioritized list of fields to use as link text. Options are: pagetitle,
 * longtitle, description, menutitle. The first of these fields that has a value
 * will be the title.
 */
( isset($linkTextField) ) ? $linkTextField : $linkTextField = \'menutitle,pagetitle,longtitle\';

/* $linkDescField [ string ]
 * Prioritized list of fields to use as link title text. Options are: pagetitle,
 * longtitle, description, menutitle. The first of these fields that has a value
 * will be the title.
 */
( isset($linkDescField) ) ? $linkDescField : $linkDescField = \'description,longtitle,pagetitle,menutitle\';

/* $showCrumbsAsLinks [ 1 | 0 ]
 * If for some reason you want breadcrumbs to be text and not links, set to 0
 * (false).
 */
( isset($showCrumbsAsLinks) ) ? $showCrumbsAsLinks : $showCrumbsAsLinks = 1;

/* $templateSet [ string ]
 * The set of templates you\'d like to use. (Templates are defined below.) It
 * will default to defaultString which replicates the output of previous
 * versions.
 */
( isset($templateSet) ) ? $templateSet : $templateSet = \'defaultString\';

/* $crumbGap [ string ]
 * String to be shown to represent gap if there are more crumbs in trail than
 * can be shown. Note: if you would like to use an image, the entire image tag
 * must be provided. When making a snippet call, you cannot use "=", so use "||"
 * instead and it will be converted for you.
 */
( isset($crumbGap) ) ? $crumbGap : $crumbGap = \'...\';

/* $stylePrefix [ string ]
 * Breadcrumbs will add style classes to various parts of the trail. To avoid
 * class name conflicts, you can determine your own prefix. The following
 * classes will be attached:
 * crumbBox: Span that surrounds all crumb output
 * hideCrumb: Span that surrounds the "..." if there are more crumbs than will
 * be shown
 * currentCrumb: Span or A tag surrounding the current crumb
 * firstCrumb: Span that will be applied to first crumb, whether it is "home" or
 * not
 * lastCrumb: Span surrounding last crumb, whether it is the current page or
 * not
 * crumb: Class given to each A tag surrounding the intermediate crumbs (not
 * "home", "current", or "hide")
 * homeCrumb: Class given to the home crumb
 */
( isset($stylePrefix) ) ? $stylePrefix : $stylePrefix = \'B_\';



/* Home link parameters
 * -----------------------------------------------------------------------------
 * The home link is unique. It is a link that can be placed at the head of the
 * breadcrumb trail, even if it is not truly in the hierarchy.
 */

/* $showHomeCrumb [ 1 | 0 ]
 * This toggles the "home" crumb to be added to the beginning of your trail.
 */
( isset($showHomeCrumb) ) ? $showHomeCrumb : $showHomeCrumb = 1;

/* $homeId [ integer ]
 * Usually the page designated as "site start" in MODx configuration is
 * considered the home page. But if you would like to use some other document,
 * you may explicitly define it.
 */
( isset($homeId) ) ? (int)$homeId : $homeId = $modx->config[\'site_start\'];

/* $homeCrumbTitle [ string ]
 * If you\'d like to use something other than the menutitle (or pagetitle) for
 * the home link.
 */
( isset($homeCrumbTitle) ) ? $homeCrumbTitle : $homeCrumbTitle = \'\';

/* $homeCrumbDescription [ string ]
 * If you\'d like to use a custom description (link title) on the home link. If
 * left blank, the title will follow the title order set in $titleField.
 */
( isset($homeCrumbDescription) ) ? $homeCrumbDescription : $homeCrumbDescription = \'\';


/* Custom behaviors
 * -----------------------------------------------------------------------------
 * The following parameters will alter the behavior of the Breadcrumbs based on
 * the page it is on.
 */

/* $showCrumbsAtHome [ 1 | 0 ]
 * You can turn off Breadcrumbs all together on the home page by setting this to
 * 1 (true);
 */
( isset($showCrumbsAtHome) ) ? $showCrumbsAtHome : $showCrumbsAtHome = 0;

/* $hideOn [ string ]
 * Comma separated list of documents you don\'t want Breadcrumbs on at all. If
 * you have a LOT of pages like this, you might try $hideUnder or use another
 * template. This parameter is best for those rare odd balls - otherwise it will
 * become a pain to manage.
 */
( isset($hideOn) ) ? $hideOn : $hideOn = \'\';

/* $hideUnder [ string ]
 * Comma separated list of parent documents, whose CHILDREN you don\'t want
 * Breadcrumbs to appear on at all. This enables you to hide Breadcrumbs on a
 * whole folders worth of documents by specifying the parent only. The PARENT
 * will not have Breadcrumbs hidden however. If you wanted to hide the parent
 * and the children, put the parent ID in hideUnder AND hideOn.
 */
( isset($hideUnder) ) ? $hideUnder : $hideUnder = \'\';

/* $stopIds [ string ]
 * Comma separated list of document IDs that when reached, stops Breadcrumbs
 * from going any further. This is useful in situations like where you have
 * language branches, and you don\'t want the Breadcrumbs going past the "home"
 * of the language you\'re in.
 */
( isset($stopIds) ) ? $stopIds : $stopIds = \'\';

/* $ignoreIds [ string ]
 * Comma separated list of document IDs to explicitly ignore.
 */
( isset($ignoreIds) ) ? $ignoreids : \'\';

/* Templates
 * -----------------------------------------------------------------------------
 * In an effort to keep the MODx chunks manager from getting mired down in lots
 * of templates, Breadcrumbs templates are included here. Two sets are provided
 * prefixed with defaultString, and defaultList. You can create as many more as
 * you like, each set with it\'s own prefix
 */
$templates = array(
    \'defaultString\' => array(
        \'crumb\' => \'[+crumb+]\',
        \'separator\' => \' &raquo; \',
        \'crumbContainer\' => \'<span class="[+crumbBoxClass+]">[+crumbs+]</span>\',
        \'lastCrumbWrapper\' => \'<span class="[+lastCrumbClass+]">[+lastCrumbSpanA+]</span>\',
        \'firstCrumbWrapper\' => \'<span class="[+firstCrumbClass+]">[+firstCrumbSpanA+]</span>\'
    ),
    \'defaultList\' => array(
        \'crumb\' => \'<li>[+crumb+]</li>\',
        \'separator\' => \'\',
        \'crumbContainer\' => \'<ul class="[+crumbBoxClass+]">[+crumbs+]</ul>\',
        \'lastCrumbWrapper\' => \'<span class="[+lastCrumbClass+]">[+lastCrumbSpanA+]</span>\',
        \'firstCrumbWrapper\' => \'<span class="[+firstCrumbClass+]">[+firstCrumbSpanA+]</span>\'
    ),
);


/* -----------------------------------------------------------------------------
 * END CONFIGURATION
 * -----------------------------------------------------------------------------
 */

// Return blank if necessary: on home page
if ( !$showCrumbsAtHome && $homeId == $modx->documentObject[\'id\'] )
{
    return \'\';
}
// Return blank if necessary: specified pages
if ( $hideOn || $hideUnder )
{
    // Create array of hide pages
    $hideOn = str_replace(\' \',\'\',$hideOn);
    $hideOn = explode(\',\',$hideOn);

    // Get more hide pages based on parents if needed
    if ( $hideUnder )
    {
        $hiddenKids = array();
        // Get child pages to hide
        $hideKidsQuery = $modx->db->select(\'id\',$modx->getFullTableName("site_content"),"parent IN ($hideUnder)");
        while ( $hideKid = $modx->db->getRow($hideKidsQuery) )
        {
            $hiddenKids[] = $hideKid[\'id\'];
        }
        // Merge with hideOn pages
        $hideOn = array_merge($hideOn,$hiddenKids);
    }

    if ( in_array($modx->documentObject[\'id\'],$hideOn) )
    {
        return \'\';
    }

}


// Initialize ------------------------------------------------------------------

// Put certain parameters in arrays
$stopIds = str_replace(\' \',\'\',$stopIds);
$stopIds = explode(\',\',$stopIds);
$linkTextField = str_replace(\' \',\'\',$linkTextField);
$linkTextField = explode(\',\',$linkTextField);
$linkDescField = str_replace(\' \',\'\',$linkDescField);
$linkDescField = explode(\',\',$linkDescField);
$ignoreIds = str_replace(\' \',\'\',$ignoreIds);
$ignoreIds = explode(\',\',$ignoreIds);

/* $crumbs
 * Crumb elements are: id, parent, pagetitle, longtitle, menutitle, description,
 * published, hidemenu
 */
$crumbs = array();
$parent = $modx->documentObject[\'parent\'];
$output = \'\';
$maxCrumbs += ($showCurrentCrumb) ? 1 : 0;

// Replace || in snippet parameters that accept them with =
$crumbGap = str_replace(\'||\',\'=\',$crumbGap);

// Curent crumb ----------------------------------------------------------------

// Decide if current page is to be a crumb
if ( $showCurrentCrumb )
{
    $crumbs[] = array(
        \'id\' => $modx->documentObject[\'id\'],
        \'parent\' => $modx->documentObject[\'parent\'],
        \'pagetitle\' => $modx->documentObject[\'pagetitle\'],
        \'longtitle\' => $modx->documentObject[\'longtitle\'],
        \'menutitle\' => $modx->documentObject[\'menutitle\'],
        \'description\' => $modx->documentObject[\'description\']);
}

// Intermediate crumbs ---------------------------------------------------------


// Iterate through parents till we hit root or a reason to stop
$loopSafety = 0;
while ( $parent && $loopSafety < 1000 )
{
    // Get next crumb
    $tempCrumb = $modx->getPageInfo($parent,0,"id,parent,pagetitle,longtitle,menutitle,description,published,hidemenu");

    // Check for include conditions & add to crumbs
    if (
        $tempCrumb[\'published\'] &&
        ( !$tempCrumb[\'hidemenu\'] || !$respectHidemenu ) &&
        !in_array($tempCrumb[\'id\'],$ignoreIds)
    )
    {
        // Add crumb
        $crumbs[] = array(
        \'id\' => $tempCrumb[\'id\'],
        \'parent\' => $tempCrumb[\'parent\'],
        \'pagetitle\' => $tempCrumb[\'pagetitle\'],
        \'longtitle\' => $tempCrumb[\'longtitle\'],
        \'menutitle\' => $tempCrumb[\'menutitle\'],
        \'description\' => $tempCrumb[\'description\']);
    }

    // Check stop conditions
    if (
        in_array($tempCrumb[\'id\'],$stopIds) ||  // Is one of the stop IDs
        !$tempCrumb[\'parent\'] || // At root
        ( !$tempCrumb[\'published\'] && !$pathThruUnPub ) // Unpublished
    )
    {
        // Halt making crumbs
        break;
    }

    // Reset parent
    $parent = $tempCrumb[\'parent\'];

    // Increment loop safety
    $loopSafety++;
}

// Home crumb ------------------------------------------------------------------

if ( $showHomeCrumb && $homeCrumb = $modx->getPageInfo($homeId,0,"id,parent,pagetitle,longtitle,menutitle,description,published,hidemenu") )
{
    $crumbs[] = array(
    \'id\' => $homeCrumb[\'id\'],
    \'parent\' => $homeCrumb[\'parent\'],
    \'pagetitle\' => $homeCrumb[\'pagetitle\'],
    \'longtitle\' => $homeCrumb[\'longtitle\'],
    \'menutitle\' => $homeCrumb[\'menutitle\'],
    \'description\' => $homeCrumb[\'description\']);
}


// Process each crumb ----------------------------------------------------------
$pretemplateCrumbs = array();

foreach ( $crumbs as $c )
{

    // Skip if we\'ve exceeded our crumb limit but we\'re waiting to get to home
    if ( count($pretemplateCrumbs) > $maxCrumbs && $c[\'id\'] != $homeId )
    {
        continue;
    }

    $text = \'\';
    $title = \'\';
    $pretemplateCrumb = \'\';

    // Determine appropriate span/link text: home link specified
    if ( $c[\'id\'] == $homeId && $homeCrumbTitle )
    {
        $text = $homeCrumbTitle;
    }
    else
    // Determine appropriate span/link text: home link not specified
    {
        for ($i = 0; !$text && $i < count($linkTextField); $i++)
        {
            if ( $c[$linkTextField[$i]] )
            {
                $text = $c[$linkTextField[$i]];
            }
        }
    }

    // Determine link/span class(es)
    if ( $c[\'id\'] == $homeId )
    {
        $crumbClass = $stylePrefix.\'homeCrumb\';
    }
    else if ( $modx->documentObject[\'id\'] == $c[\'id\'] )
    {
        $crumbClass = $stylePrefix.\'currentCrumb\';
    }
    else
    {
        $crumbClass = $stylePrefix.\'crumb\';
    }

    // Make link
    if (
        ( $c[\'id\'] != $modx->documentObject[\'id\'] && $showCrumbsAsLinks ) ||
        ( $c[\'id\'] == $modx->documentObject[\'id\'] && $currentAsLink )
    )
    {
        // Determine appropriate title for link: home link specified
        if ( $c[\'id\'] == $homeId && $homeCrumbDescription )
        {
            $title = htmlspecialchars($homeCrumbDescription);
        }
        else
        // Determine appropriate title for link: home link not specified
        {
            for ($i = 0; !$title && $i < count($linkDescField); $i++)
            {
                if ( $c[$linkDescField[$i]] )
                {
                    $title = htmlspecialchars($c[$linkDescField[$i]]);
                }
            }
        }


        $pretemplateCrumb .= \'<a class="\'.$crumbClass.\'" href="\'.$modx->makeUrl($c[\'id\']).\'" title="\'.$title.\'">\'.$text.\'</a>\';
    }
    else
    // Make a span instead of a link
    {
       $pretemplateCrumb .= \'<span class="\'.$crumbClass.\'">\'.$text.\'</span>\';
    }

    // Add crumb to pretemplate crumb array
    $pretemplateCrumbs[] = $pretemplateCrumb;

    // If we have hit the crumb limit
    if ( count($pretemplateCrumbs) == $maxCrumbs )
    {
        if ( count($crumbs) > ($maxCrumbs + (($showHomeCrumb) ? 1 : 0)) )
        {
            // Add gap
            $pretemplateCrumbs[] = \'<span class="\'.$stylePrefix.\'hideCrumb\'.\'">\'.$crumbGap.\'</span>\';
        }

        // Stop here if we\'re not looking for the home crumb
        if ( !$showHomeCrumb )
        {
            break;
        }
    }
}

// Put in correct order for output
$pretemplateCrumbs = array_reverse($pretemplateCrumbs);

// Wrap first/last spans
$pretemplateCrumbs[0] = str_replace(
    array(\'[+firstCrumbClass+]\',\'[+firstCrumbSpanA+]\'),
    array($stylePrefix.\'firstCrumb\',$pretemplateCrumbs[0]),
    $templates[$templateSet][\'firstCrumbWrapper\']
);
$pretemplateCrumbs[(count($pretemplateCrumbs)-1)] = str_replace(
    array(\'[+lastCrumbClass+]\',\'[+lastCrumbSpanA+]\'),
    array($stylePrefix.\'lastCrumb\',$pretemplateCrumbs[(count($pretemplateCrumbs)-1)]),
    $templates[$templateSet][\'lastCrumbWrapper\']
);

// Insert crumbs into crumb template
$processedCrumbs = array();
foreach ( $pretemplateCrumbs as $pc )
{
    $processedCrumbs[] = str_replace(\'[+crumb+]\',$pc,$templates[$templateSet][\'crumb\']);
}

// Combine crumbs together into one string with separator
$processedCrumbs = implode($templates[$templateSet][\'separator\'],$processedCrumbs);

// Put crumbs into crumb container template
$container = str_replace(
    array(\'[+crumbBoxClass+]\',\'[+crumbs+]\'),
    array($stylePrefix.\'crumbBox\',$processedCrumbs),
    $templates[$templateSet][\'crumbContainer\']
    );

// Return crumbs
return $container;';
$s['Ditto'] = '/*
 * Title: Ditto Snippet
 * 
 * Description:
 *  	Aggregates documents to create blogs, article/news
 *  	collections, and more,with full support for templating.
 * 
 * Author: 
 * 		Mark Kaplan for MODx CMF
 * 
 * Version: 
 * 		2.1.0
*/

//---Core Settings---------------------------------------------------- //

$ditto_version = "2.1.0";
	// Ditto version being executed

$ditto_base = isset($ditto_base) ? $modx->config[\'base_path\'].$ditto_base : $modx->config[\'base_path\']."assets/snippets/ditto/";
/*
	Param: ditto_base
	
	Purpose:
	Location of Ditto files

	Options:
	Any valid folder location containing the Ditto source code with a trailing slash

	Default:
	[(base_path)]assets/snippets/ditto/
*/
$dittoID = (!isset($id)) ? "" : $id."_";
$GLOBALS["dittoID"] = $dittoID;
/*
	Param: id

	Purpose:
	Unique ID for this Ditto instance for connection with other scripts (like Reflect) and unique URL parameters

	Options:
	Any combination of characters a-z, underscores, and numbers 0-9
	
	Note:
	This is case sensitive

	Default:
	"" - blank
*/		
$language = (isset($language))? $language : "english";
/*
	Param: language

	Purpose:
	language for defaults, debug, and error messages

	Options:
	Any language name with a corresponding file in the &ditto_base/lang folder

	Default:
	"english"
*/
$format = (isset($format)) ? strtolower($format) : "html" ;
/*
	Param: format

	Purpose:
 	Output format to use

	Options:
	- "html"
	- "json"
	- "xml"
	- "atom"
	- "rss"

	Default:
	"html"
*/
$config = (isset($config)) ? $config : "default";
/*
	Param: config

	Purpose:
 	Load a custom configuration

	Options:
	"default" - default blank config file
	CONFIG_NAME - Other configs installed in the configs folder or in any folder within the MODx base path via @FILE

	Default:
	"default"
	
	Related:
	- <extenders>
*/
$debug = isset($debug)? $debug : 0;
/*
	Param: debug

	Purpose:
 	Output debugging information

	Options:
	0 - off
	1 - on
	
	Default:
	0 - off
	
	Related:
	- <debug>
*/
$phx = (isset($phx))? $phx : 1;
/*
	Param: phx

	Purpose:
 	Use PHx formatting

	Options:
	0 - off
	1 - on
	
	Default:
	1 - on
*/		
$extenders = isset($extenders) ? explode(",",$extenders) : array();
/*
	Param: extenders

	Purpose:
 	Load an extender which adds functionality to Ditto

	Options:
	Any extender in the extenders folder or in any folder within the MODx base path via @FILE

	Default:
	[NULL]

	Related:
	- <config>
*/
	// Variable: extenders
	// Array that can be added to by configs or formats to load that extender
	
$placeholders = array();
	// Variable: placeholders
	// Initialize custom placeholders array for configs or extenders to add to

$filters = array("custom"=>array(),"parsed"=>array());
	// Variable: filters
	// Holds both the custom filters array for configs or extenders to add to 
	// and the parsed filters array. To add to this array, use the following format
	// (code)
	// $filters["parsed"][] = array("name" => array("source"=>$source,"value"=>$value,"mode"=>$mode));
	// $filters["custom"][] = array("source","callback_function");

$orderBy = array(\'parsed\'=>array(),\'custom\'=>array(),\'unparsed\'=>$orderBy);
	// Variable: orderBy
	// An array that holds all criteria to sort the result set by. 
	// Note that using a custom sort will disable all other sorting.
	// (code)
	// $orderBy["parsed"][] = array("sortBy","sortDir");
	// $orderBy["custom"][] = array("sortBy","callback_function");
		
//---Includes-------------------------------------------------------- //

$files = array (
	"base_language" => $ditto_base."lang/english.inc.php",
	"language" => $ditto_base."lang/$language.inc.php",
	"main_class" => $ditto_base."classes/ditto.class.inc.php",
	"template_class" => $ditto_base."classes/template.class.inc.php",
	"filter_class" => $ditto_base."classes/filter.class.inc.php",
	"format" => $ditto_base."formats/$format.format.inc.php",
	"config" => $ditto_base."configs/default.config.php",
	"user_config" => (substr($config, 0, 5) != "@FILE") ? $ditto_base."configs/$config.config.php" : $modx->config[\'base_path\'].trim(substr($config, 5))
);

if ($phx == 1) {
	$files["prePHx_class"] = $ditto_base."classes/phx.pre.class.inc.php";
}
if (isset($randomize)) {
	$files["randomize_class"] = $ditto_base."classes/random.class.inc.php";
}
if ($debug == 1) {
	$files["modx_debug_class"] = $ditto_base."debug/modxDebugConsole.class.php";
	$files["debug_class"] = $ditto_base."classes/debug.class.inc.php";
	$files["debug_templates"] = $ditto_base."debug/debug.templates.php";
}

$files = array_unique($files);
foreach ($files as $filename => $filevalue) {
	if (file_exists($filevalue) && strpos($filename,"class")) {
		include_once($filevalue);
	} else if (file_exists($filevalue)) {
		include($filevalue);
	} else if ($filename == "language") {
		$modx->logEvent(1, 3, "Language file does not exist Please check: " . $filevalue, "Ditto " . $ditto_version);
		return "Language file does not exist Please check: " . $filevalue;
	} else {
		$modx->logEvent(1, 3, $filevalue . " " . $_lang[\'file_does_not_exist\'], "Ditto " . $ditto_version);
		return $filevalue . " " . $_lang[\'file_does_not_exist\'];
	}
}

//---Initiate Class-------------------------------------------------- //
if (class_exists(\'ditto\')) {
	$ditto = new ditto($dittoID,$format,$_lang,$dbg_templates);
		// create a new Ditto instance in the specified format and language with the requested debug level
} else {
	$modx->logEvent(1,3,$_lang[\'invalid_class\'],"Ditto ".$ditto_version);
	return $_lang[\'invalid_class\'];
}

//---Initiate Extenders---------------------------------------------- //
if (isset($tagData)) {
	$extenders[] = "tagging";
}
if(count($extenders) > 0) {
	$extenders = array_unique($extenders);
	foreach ($extenders as $extender) {
			if(substr($extender, 0, 5) != "@FILE") {
				$extender_path = $ditto_base."extenders/".$extender.".extender.inc.php";				
			} else {
				$extender_path = $modx->config[\'base_path\'].trim(substr($extender, 5));
			}
			
			if (file_exists($extender_path)){
				include($extender_path);
			} else {
				$modx->logEvent(1, 3, $extender . " " . $_lang[\'extender_does_not_exist\'], "Ditto ".$ditto_version);
				return $extender . " " . $_lang[\'extender_does_not_exist\'];
			}		
	}	
}

//---Parameters------------------------------------------------------- /*
if (isset($startID)) {$parents = $startID;}
if (isset($summarize)) {$display = $summarize;}
if (isset($limit)) {$queryLimit = $limit;}
if (isset($sortBy) || isset($sortDir) || is_null($orderBy[\'unparsed\'])) {
	$sortDir = isset($sortDir) ? strtoupper($sortDir) : \'DESC\';
	$sortBy = isset($sortBy) ? $sortBy : "createdon";
	$orderBy[\'parsed\'][]=array($sortBy,$sortDir);
}
	// Allow backwards compatibility

$idType = isset($documents) ? "documents" : "parents";
	// Variable: idType
	// type of IDs provided; can be either parents or documents

$parents = isset($parents) ? $ditto->cleanIDs($parents) : $modx->documentIdentifier;

/*
	Param: parents

	Purpose:
	IDs of containers for Ditto to retrieve their children to &depth depth

	Options:
	Any valid MODx document marked as a container

	Default:
	Current MODx Document

	Related:
	- <documents>
	- <depth>
*/
$documents = isset($documents) ? $ditto->cleanIDs($documents) : false;
/*
	Param: documents

	Purpose:
	IDs of documents for Ditto to retrieve

	Options:
	Any valid MODx document marked as a container

	Default:
	None

	Related:
	- <parents>
*/

$IDs = ($idType == "parents") ? $parents : $documents;
	// Variable: IDs
	// Internal variable which holds the set of IDs for Ditto to fetch

$depth = isset($depth) ? $depth : 1;
/*
	Param: depth

	Purpose:
	Number of levels deep to retrieve documents

	Options:
	Any number greater than or equal to 1
	0 - infinite depth

	Default:
	1

	Related:
	- <seeThruUnpub>
*/
$paginate = isset($paginate)? $paginate : 0;
/*
	Param: paginate

	Purpose:
	Paginate the results set into pages of &display length.
	Use &total to limit the number of documents retreived.

	Options:
	0 - off
	1 - on
	
	Default:
	0 - off
	
	Related:
	- <paginateAlwaysShowLinks>
	- <paginateSplitterCharacter>
	- <display>
*/
$dateSource = isset($dateSource) ? $dateSource : "createdon";
/*
	Param: dateSource

	Purpose:
	Source of the [+date+] placeholder

	Options:
	# - Any UNIX timestamp from MODx fields or TVs such as createdon, pub_date, or editedon
	
	Default:
	"createdon"
	
	Related:
	- <dateFormat>
*/
$dateFormat = isset($dateFormat)? $dateFormat : $_lang["dateFormat"];
/*
	Param: dateFormat

	Purpose:
	Format the [+date+] placeholder in human readable form

	Options:
	Any PHP valid strftime option

	Default:
	[LANG]
	
	Related:
	- <dateSource>
*/
$display = isset($display) ? $display : "all";
/*
	Param: display

	Purpose:
	Number of documents to display in the results

	Options:
	# - Any number
	"all" - All documents found

	Default:
	"all"
	
	Related:
	- <queryLimit>
	- <total>
*/
$total = isset($total) ? $total : "all";
/*
	Param: total

	Purpose:
	Number of documents to retrieve
	
	Options:
	# - Any number
	"all" - All documents found

	Default:
	"all" - All documents found
	
	Related:
	- <display>
	- <queryLimit>
*/
$showPublishedOnly = isset($showPublishedOnly) ? $showPublishedOnly : 1;
/*
	Param: showPublishedOnly

	Purpose:
	Show only published documents

	Options:
	0 - show only unpublished documents
	1 - show both published and unpublished documents
	
	Default:
	1 - show both published and unpublished documents
	
	Related:
	- <seeThruUnpub>
	- <hideFolders>
	- <showPublishedOnly>
	- <where>
*/
$showInMenuOnly = isset($showInMenuOnly) ? $showInMenuOnly : 0;
/*
	Param: showInMenuOnly

	Purpose:
	Show only documents visible in the menu

	Options:
	0 - show all documents
	1 - show only documents with the show in menu flag checked
	
	Default:
	0 - show all documents
	
	Related:
	- <seeThruUnpub>
	- <hideFolders>
	- <where>
*/
$hideFolders = isset($hideFolders)? $hideFolders : 0;
/*
	Param: hideFolders

	Purpose:
	Don\'t show folders in the returned results

	Options:
	0 - keep folders
	1 - remove folders
	
	Default:
	0 - keep folders
	
	Related:
	- <seeThruUnpub>
	- <showInMenuOnly>
	- <where>
*/
$hidePrivate = isset($hidePrivate)? $hidePrivate : 1;
/*
	Param: hidePrivate

	Purpose:
	Don\'t show documents the guest or user does not have permission to see

	Options:
	0 - show private documents
	1 - hide private documents
	
	Default:
	1 - hide private documents
	
	Related:
	- <seeThruUnpub>
	- <showInMenuOnly>
	- <where>
*/
$seeThruUnpub = (isset($seeThruUnpub))? $seeThruUnpub : 1 ;
/*
	Param: seeThruUnpub

	Purpose:
	See through unpublished folders to retrive their children
	Used when depth is greater than 1

	Options:
	0 - off
	1 - on
	
	Default:
	0 - off
	
	Related:
	- <hideFolders>
	- <showInMenuOnly>
	- <where>
*/
$queryLimit = (isset($queryLimit))? $queryLimit : 0;
/*
	Param: queryLimit

	Purpose:
	Number of documents to retrieve from the database, same as MySQL LIMIT

	Options:
	# - Any number
	0 - automatic

	Default:
	0 - automatic
	
	Related:
	- <where>
*/
$where = (isset($where))? $where : "";
/*
	Param: where

	Purpose:
	Custom MySQL WHERE statement

	Options:
	A valid MySQL WHERE statement using only document object items (no TVs)

	Default:
	[NULL]
	
	Related:
	- <queryLimit>
*/
$noResults = isset($noResults)? $ditto->getParam($noResults,"no_documents") : $_lang[\'no_documents\'];
/*
	Param: noResults

	Purpose:
	Text or chunk to display when there are no results

	Options:
	Any valid chunk name or text

	Default:
	[LANG]
*/
$removeChunk = isset($removeChunk) ? explode(",",$removeChunk) : false;
/*
	Param: removeChunk

	Purpose:
 	Name of chunks to be stripped from content separated by commas
	- Commonly used to remove comments

	Options:
	Any valid chunkname that appears in the output

	Default:
	[NULL]
*/
$hiddenFields = isset($hiddenFields) ? explode(",",$hiddenFields) : false;
/*
	Param: hiddenFields

	Purpose:
	Allow Ditto to retrieve fields its template parser cannot handle such as nested placeholders and [*fields*]

	Options:
	Any valid MODx fieldnames or TVs comma separated

	Default:
	[NULL]
*/
$offset = isset($start) ? $start : 0;
$start = (isset($_GET[$dittoID.\'start\'])) ? intval($_GET[$dittoID.\'start\']) : 0;
/*
	Param: start

	Purpose:
 	Number of documents to skip in the results
	
	Options:
	Any number

	Default:
	0
*/
$globalFilterDelimiter = isset($globalFilterDelimiter) ? $globalFilterDelimiter : "|";
/*
	Param: globalFilterDelimiter

	Purpose:
 	Filter delimiter used to separate filters in the filter string
	
	Options:
	Any character not used in the filters

	Default:
	"|"
	
	Related:
	- <localFilterDelimiter>
	- <filter>
	- <parseFilters>
*/
	
$localFilterDelimiter = isset($localFilterDelimiter) ? $localFilterDelimiter : ",";
/*
	Param: localFilterDelimiter

	Purpose:
	Delimiter used to separate individual parameters within each filter string
	
	Options:
	Any character not used in the filter itself

	Default:
	","
	
	Related:
	- <globalFilterDelimiter>
	- <filter>
	- <parseFilters>
*/
$filters["custom"] = isset($cFilters) ? array_merge($filters["custom"],$cFilters) : $filters["custom"];
$filters["parsed"] = isset($parsedFilters) ? array_merge($filters["parsed"],$parsedFilters) : $filters["parsed"];
	// handle 2.0.0 compatibility
$filter = (isset($filter) || ($filters["custom"] != false) || ($filters["parsed"] != false)) ? $ditto->parseFilters($filter,$filters["custom"],$filters["parsed"],$globalFilterDelimiter,$localFilterDelimiter) : false;
/*
	Param: filter

	Purpose:
	Removes items not meeting a critera. Thus, if pagetitle == joe then it will be removed.
	Use in the format field,criteria,mode with the comma being the local delimiter

	*Mode* *Meaning*
	
	1 - !=
	2 - ==
	3 - <
	4 - >
	5 - <=
	6 - >=
	7 - Text not in field value
	8 - Text in field value
	9 - case insenstive version of #7
	10 - case insenstive version of #8
	11 - checks leading character of the field
	
	@EVAL:
		@EVAL in filters works the same as it does in MODx exect it can only be used 
		with basic filtering, not custom filtering (tagging, etc). Make sure that
		you return the value you wish Ditto to filter by and that the code is valid PHP.

	Default:
	[NULL]
	
	Related:
	- <localFilterDelimiter>
	- <globalFilterDelimiter>
	- <parseFilters>
*/
$keywords = (isset($keywords))? $keywords : 0;
/*	
	Param: keywords
	
	Purpose: 
	Enable fetching of associated keywords for each document
	Can be used as [+keywords+] or as a tagData source
	
	Options:
	0 - off
	1 - on
	
	Default:
	0 - off
*/

$randomize = (isset($randomize))? $randomize : 0;
/*	
	Param: randomize
	
	Purpose: 
	Randomize the order of the output
	
	Options:
	0 - off
	1 - on
	Any MODx field or TV for weighted random
	
	Default:
	0 - off
*/
$save = (isset($save))? $save : 0;
/*
	Param: save

	Purpose:
	Saves the ditto object and results set to placeholders
	for use by other snippets

	Options:
	0 - off; returns output
	1 - remaining; returns output
	2 - all;
	3 - all; returns ph only

	Default:
		0 - off; returns output
*/
$templates = array(
	"default" => "@CODE".$_lang[\'default_template\'],
	"base" => $tpl,
	"alt" => $tplAlt,
	"first" => $tplFirst,
	"last" => $tplLast,
	"current" => $tplCurrentDocument
);
/*
	Param: tpl

	Purpose:
	User defined chunk to format the documents 

	Options:
	- Any valid chunk name
	- Code via @CODE
	- File via @FILE

	Default:
	[LANG]
*/
/*
	Param: tplAlt

	Purpose:
	User defined chunk to format every other document

	Options:
	- Any valid chunk name
	- Code via @CODE
	- File via @FILE

	Default:
	&tpl
*/
/*
	Param: tplFirst

	Purpose:
	User defined chunk to format the first document 

	Options:
	- Any valid chunk name
	- Code via @CODE
	- File via @FILE

	Default:
	&tpl
*/
/*
	Param: tplLast

	Purpose:
	User defined chunk to format the last document 

	Options:
	- Any valid chunk name
	- Code via @CODE
	- File via @FILE

	Default:
	&tpl
*/
/*
	Param: tplCurrentDocument

	Purpose:
	User defined chunk to format the current document

	Options:
	- Any valid chunk name
	- Code via @CODE
	- File via @FILE

	Default:
	&tpl
*/
$orderBy = $ditto->parseOrderBy($orderBy,$randomize);
/*
	Param: orderBy

	Purpose:
	Sort the result set

	Options:
	Any valid MySQL style orderBy statement

	Default:
	createdon DESC
*/
//-------------------------------------------------------------------- */
$templates = $ditto->template->process($templates);
	// parse the templates for TV\'s and store them for later use

$ditto->setDisplayFields($ditto->template->fields,$hiddenFields);
	// parse hidden fields
	
$ditto->parseFields($placeholders,$seeThruUnpub,$dateSource,$randomize);
	// parse the fields into the field array
	
$documentIDs = $ditto->determineIDs($IDs, $idType, $ditto->fields["backend"]["tv"], $orderBy, $depth, $showPublishedOnly, $seeThruUnpub, $hideFolders, $hidePrivate, $showInMenuOnly, $where, $keywords, $dateSource, $queryLimit, $display, $filter,$paginate, $randomize);
	// retrieves a list of document IDs that meet the criteria and populates the $resources array with them
$count = count($documentIDs);
	// count the number of documents to be retrieved
$count = $count-$offset;
	// handle the offset

if ($count > 0) {
	// if documents are returned continue with execution
	
	$total = ($total == "all") ? $count : min($total,$count);
		// set total equal to count if all documents are to be included
	
	$display = ($display == "all") ? min($count,$total) : min($display,$total);
		// allow show to use all option

	$stop = ($save != "1") ? min($total-$start,$display) : min($count,$total);
		// set initial stop count

	if($paginate == 1) {
		$paginateAlwaysShowLinks = isset($paginateAlwaysShowLinks)? $paginateAlwaysShowLinks : 0;
		/*
			Param: paginateAlwaysShowLinks

			Purpose:
			Determine whether or not to always show previous next links

			Options:
			0 - off
			1 - on

			Default:
			0 - off
		
			Related:
			- <paginate>
			- <paginateSplitterCharacter>
		*/
		$paginateSplitterCharacter = isset($paginateSplitterCharacter)? $paginateSplitterCharacter : $_lang[\'button_splitter\'];
		/*
			Param: paginateSplitterCharacter

			Purpose:
			Splitter to use if always show is disabled

			Options:
			Any valid character

			Default:
			[LANG]
		
			Related:
			- <paginate>
			- <paginateSplitterCharacter>
		*/
		$tplPaginatePrevious = isset($tplPaginatePrevious)? $ditto->template->fetch($tplPaginatePrevious) : "<a href=\'[+url+]\' class=\'ditto_previous_link\'>[+lang:previous+]</a>";
		/*
			Param: tplPaginatePrevious

			Purpose:
			Template for the previous link

			Options:
			- Any valid chunk name
			- Code via @CODE
			- File via @FILE

			Placeholders:
			url - URL for the previous link
			lang:previous - value of \'prev\' from the language file
		
			Related:
			- <tplPaginateNext>
			- <paginateSplitterCharacter>
		*/
		$tplPaginateNext = isset($tplPaginateNext)? $ditto->template->fetch($tplPaginateNext) : "<a href=\'[+url+]\' class=\'ditto_next_link\'>[+lang:next+]</a>";
		/*
			Param: tplPaginateNext

			Purpose:
			Template for the next link

			Options:
			- Any valid chunk name
			- Code via @CODE
			- File via @FILE

			Placeholders:
			url - URL for the next link
			lang:next - value of \'next\' from the language file
		
			Related:
			- <tplPaginatePrevious>
			- <paginateSplitterCharacter>
		*/
		$tplPaginateNextOff = isset($tplPaginateNextOff)? $ditto->template->fetch($tplPaginateNextOff) : "<span class=\'ditto_next_off ditto_off\'>[+lang:next+]</span>";
		/*
			Param: tplPaginateNextOff

			Purpose:
			Template for the inside of the next link

			Options:
			- Any valid chunk name
			- Code via @CODE
			- File via @FILE

			Placeholders:
			lang:next - value of \'next\' from the language file
		
			Related:
			- <tplPaginatePrevious>
			- <paginateSplitterCharacter>
		*/
		$tplPaginatePreviousOff = isset($tplPaginatePreviousOff)? $ditto->template->fetch($tplPaginatePreviousOff) : "<span class=\'ditto_previous_off ditto_off\'>[+lang:previous+]</span>";
		/*
			Param: tplPaginatePreviousOff

			Purpose:
			Template for the previous link when it is off

			Options:
			- Any valid chunk name
			- Code via @CODE
			- File via @FILE

			Placeholders:
			lang:previous - value of \'prev\' from the language file
	
			Related:
			- <tplPaginatePrevious>
			- <paginateSplitterCharacter>
		*/
		$tplPaginatePage = isset($tplPaginatePage)? $ditto->template->fetch($tplPaginatePage) : "<a class=\'ditto_page\' href=\'[+url+]\'>[+page+]</a>";
		/*
			Param: tplPaginatePage

			Purpose:
			Template for the page link

			Options:
			- Any valid chunk name
			- Code via @CODE
			- File via @FILE

			Placeholders:
			url - url for the page
			page - number of the page
	
			Related:
			- <tplPaginatePrevious>
			- <paginateSplitterCharacter>
		*/
		$tplPaginateCurrentPage = isset($tplPaginateCurrentPage)? $ditto->template->fetch($tplPaginateCurrentPage) : "<span class=\'ditto_currentpage\'>[+page+]</span>";
		/*
			Param: tplPaginateCurrentPage

			Purpose:
			Template for the current page link

			Options:
			- Any valid chunk name
			- Code via @CODE
			- File via @FILE

			Placeholders:
			page - number of the page
	
			Related:
			- <tplPaginatePrevious>
			- <paginateSplitterCharacter>
		*/
		
		$ditto->paginate($start, $stop, $total, $display, $tplPaginateNext, $tplPaginatePrevious, $tplPaginateNextOff, $tplPaginatePreviousOff, $tplPaginatePage, $tplPaginateCurrentPage, $paginateAlwaysShowLinks, $paginateSplitterCharacter);
			// generate the pagination placeholders
	}

	$dbFields = $ditto->fields["display"]["db"];
		// get the database fields
	$TVs = $ditto->fields["display"]["tv"];
		// get the TVs
	
	switch($orderBy[\'parsed\'][0][1]) {
		case "DESC":
			$stop = ($ditto->prefetch === false) ? $stop + $start + $offset : $stop + $offset; 
			$start += $offset;
		break;
		case "ASC":
			$start += $offset;
			$stop += $start;
		break;
	}

	if ($ditto->prefetch !== false) {
		$documentIDs = array_slice($documentIDs,$start,$stop);
			// set the document IDs equal to the trimmed array
		$dbFields = array_diff($dbFields,$ditto->prefetch["fields"]["db"]);
			// calculate the difference between the database fields and those already prefetched
		$dbFields[] = "id";
			// append id to the db fields array
		$TVs = array_diff($TVs,$ditto->prefetch["fields"]["tv"]);
			// calculate the difference between the tv fields and those already prefetched
		$start = 0;
		$stop = min($display,($queryLimit != 0) ? $queryLimit : $display,count($documentIDs));
	} else {
		$queryLimit = ($queryLimit == 0) ? "" : $queryLimit;
	}
	
	$resource = $ditto->getDocuments($documentIDs, $dbFields, $TVs, $orderBy, $showPublishedOnly, 0, $hidePrivate, $where, $queryLimit, $keywords, $randomize, $dateSource);
		// retrieves documents
	$output = $header;
		// initialize the output variable and send the header

	if ($resource) {
		if ($randomize != "0" && $randomize != "1") {
			$resource = $ditto->weightedRandom($resource,$randomize,$stop);
				// randomize the documents
		}
		
		$resource = array_values($resource);

		for ($x=$start;$x<$stop;$x++) {
			$template = $ditto->template->determine($templates,$x,0,$stop,$resource[$x]["id"]);
				// choose the template to use and set the code of that template to the template variable
			$renderedOutput = $ditto->render($resource[$x], $template, $removeChunk, $dateSource, $dateFormat, $placeholders,$phx,abs($start-$x));
				// render the output using the correct template, in the correct format and language
			$modx->setPlaceholder($dittoID."item[".abs($start-$x)."]",$renderedOutput);
			/*
				Placeholder: item[x]

				Content:
				Individual items rendered output
			*/
			$output .= $renderedOutput;
				// send the rendered output to the buffer
		}
	} else {
		$output .= $ditto->noResults($noResults,$paginate);
			// if no documents are found return a no documents found string
	}
	$output .= $footer;
		// send the footer

	// ---------------------------------------------------
	// Save Object
	// ---------------------------------------------------

	if($save) {
		$modx->setPlaceholder($dittoID."ditto_object", $ditto);
		$modx->setPlaceholder($dittoID."ditto_resource", ($save == "1") ? array_slice($resource,$display) : $resource);
	}
} else {
	$output = $header.$ditto->noResults($noResults,$paginate).$footer;
}
// ---------------------------------------------------
// Handle Debugging
// ---------------------------------------------------

if ($debug == 1) {
	$ditto_params = func_get_args();
	if (!isset($_GET["ditto_".$dittoID."debug"])) {
	$_SESSION["ditto_debug_$dittoID"] = $ditto->debug->render_popup($ditto, $ditto_base, $ditto_version, $ditto_params[1], $documentIDs, array("db"=>$dbFields,"tv"=>$TVs), $display, $templates, $orderBy, $start, $stop, $total,$filter,$resource);
	}
	if (isset($_GET["ditto_".$dittoID."debug"])) {
		switch ($_GET["ditto_".$dittoID."debug"]) {
			case "open" :
				exit($_SESSION["ditto_debug_$dittoID"]);
			break;
			case "save" :
				$ditto->debug->save($_SESSION["ditto_debug_$dittoID"],"ditto".strtolower($ditto_version)."_debug_doc".$modx->documentIdentifier.".html");
			break;
		}
	} else {
		$output = $ditto->debug->render_link($dittoID,$ditto_base).$output;
	}
}

return ($save != 3) ? $output : "";';
$s['eForm'] = '# eForm 1.4.4.5 - Electronic Form Snippet
# Original created by Raymond Irving 15-Dec-2004.
# Version 1.3+ extended by Jelle Jager (TobyL) September 2006
# -----------------------------------------------------
# local version: $Id: eform.snippet.tpl,v 1.2 2006/11/22 14:53:38 jelle Exp $
# Captcha image support - thanks to Djamoer
# Multi checkbox, radio, select support - thanks to Djamoer
# Form Parser and extened validation - by Jelle Jager
#
# see eform/docs/eform.htm for history, usage and examples
#

# Set Snippet Paths
$snipFolder = isset($snipFolder)?$snipFolder:\'eform\';
$snipPath = $modx->config["base_path"].\'assets/snippets/\'.$snipFolder.\'/\';


# check if inside manager
if ($modx->isBackend()) {
return \'\'; # don\'t go any further when inside manager
}

//tidying up some casing errors in parameters
if(isset($eformOnValidate)) $eFormOnValidate = $eformOnValidate;
if(isset($eformOnBeforeMailSent)) $eFormOnBeforeMailSent = $eformOnBeforeMailSent;
if(isset($eformOnMailSent)) $eFormOnMailSent = $eformOnMailSent;
if(isset($eformOnValidate)) $eFormOnValidate = $eformOnValidate;
if(isset($eformOnBeforeFormMerge)) $eFormOnBeforeFormMerge = $eformOnBeforeFormMerge;
if(isset($eformOnBeforeFormParse)) $eFormOnBeforeFormParse = $eformOnBeforeFormParse;
//for sottwell :)
if(isset($eFormCSS)) $cssStyle = $eFormCSS;

# Snippet customize settings
$params = array (
   // Snippet Path
   \'snipPath\' => $snipPath, //includes $snipFolder
     \'snipFolder\' => $snipFolder,

// eForm Params
   \'vericode\' => isset($vericode)? $vericode:"",
   \'formid\' => isset($formid)? $formid:"",
   \'from\' => isset($from)? $from:$modx->config[\'emailsender\'],
   \'fromname\' => isset($fromname)? $fromname:$modx->config[\'site_name\'],
   \'to\' => isset($to)? $to:$modx->config[\'emailsender\'],
   \'cc\' => isset($cc)? $cc:"",
   \'bcc\' => isset($bcc)? $bcc:"",
   \'subject\' => isset($subject)? $subject:"",
   \'ccsender\' => isset($ccsender)?$ccsender:0,
   \'sendirect\' => isset($sendirect)? $sendirect:0,
   \'mselector\' => isset($mailselector)? $mailselector:0,
   \'mobile\' => isset($mobile)? $mobile:\'\',
   \'mobiletext\' => isset($mobiletext)? $mobiletext:\'\',
   \'autosender\' => isset($autosender)? $autosender:$from,
   \'autotext\' => isset($automessage)? $automessage:"",
   \'category\' => isset($category)? $category:0,
   \'keywords\' => isset($keywords)? $keywords:"",
   \'gid\' => isset($gotoid)? $gotoid:$modx->documentIdentifier,
   \'noemail\' => isset($noemail)? ($noemail):false,
   \'saveform\' => isset($saveform)? ($saveform? true:false):true,
   \'tpl\' => isset($tpl)? $tpl:"",
   \'report\' => isset($report)? $report:"",
   \'allowhtml\' => isset($allowhtml)? $allowhtml:0,
   //Added by JJ
   \'replyto\' => isset($replyto)? $replyto:"",
   \'language\' => isset($language)? $language:$modx->config[\'manager_language\'],
   \'thankyou\' => isset($thankyou)? $thankyou:"",
   \'isDebug\' => isset($debug)? $debug:0,
   \'reportAbuse\' => isset($reportAbuse)? $reportAbuse:false,
   \'disclaimer\' => isset($disclaimer)?$disclaimer:\'\',
   \'sendAsHtml\' => isset($sendAsHtml)?$sendAsHtml:false,
   \'sendAsText\' => isset($sendAsText)?$sendAsText:false,
   \'sessionVars\' => isset($sessionVars)?$sessionVars:false,
   \'postOverides\' => isset($postOverides)?$postOverides:0,
   \'eFormOnBeforeMailSent\' => isset($eFormOnBeforeMailSent)?$eFormOnBeforeMailSent:\'\',
   \'eFormOnMailSent\' => isset($eFormOnMailSent)?$eFormOnMailSent:\'\',
   \'eFormOnValidate\' => isset($eFormOnValidate)?$eFormOnValidate:\'\',
   \'eFormOnBeforeFormMerge\' => isset($eFormOnBeforeFormMerge)?$eFormOnBeforeFormMerge:\'\',
   \'eFormOnBeforeFormParse\' => isset($eFormOnBeforeFormParse)?$eFormOnBeforeFormParse:\'\',
   \'cssStyle\' => isset($cssStyle)?$cssStyle:\'\',
   \'jScript\' => isset($jScript)?$jScript:\'\',
   \'submitLimit\' => (isset($submitLimit) &&  is_numeric($submitLimit))?$submitLimit*60:0,
   \'protectSubmit\' => isset($protectSubmit)?$protectSubmit:1,
   \'requiredClass\' => isset($requiredClass)?$requiredClass:"required",
   \'invalidClass\' => isset($invalidClass)?$invalidClass:"invalid",
   \'runSnippet\' => ( isset($runSnippet) && !is_numeric($runSnippet) )?$runSnippet:\'\',
   \'autoSenderName\' => isset($autoSenderName)?$autoSenderName:\'\',
   \'version\' => \'1.4.4\'
);

# Start processing

include_once ($snipPath."eform.inc.php");

$output = eForm($modx,$params);

# Return
return $output;';
$s['FirstChildRedirect'] = '/**
 * @name FirstChildRedirect
 * @author Jason Coward <jason@opengeek.com>
 * @modified-by Ryan Thrash <ryan@vertexworks.com>
 * @license Public Domain
 * @version 1.0
 * 
 * This snippet redirects to the first child document of a folder in which this
 * snippet is included within the content (e.g. [!FirstChildRedirect!]).  This
 * allows MODx folders to emulate the behavior of real folders since MODx
 * usually treats folders as actual documents with their own content.
 * 
 * Modified to make Doc ID a required parameter... now defaults to the current 
 * Page/Folder you call the snippet from.
 * 
 * &docid=`12` 
 * Use the docid parameter to have this snippet redirect to the
 * first child document of the specified document.
 */

$docid = (isset($docid))? $docid: $modx->documentIdentifier;

$children= $modx->getActiveChildren($docid, \'menuindex\', \'ASC\');
if (!$children === false) {
    $firstChild= $children[0];
    $firstChildUrl= $modx->makeUrl($firstChild[\'id\']);
} else {
    $firstChildUrl= $modx->makeUrl($modx->config[\'site_start\']);
}
return $modx->sendRedirect($firstChildUrl);
';
$s['Jot'] = '/*####
#
#   Name: Jot
#   Version: 1.1.4
#   Author: Armand "bS" Pondman (apondman@zerobarrier.nl)
#   Date: Aug 04, 2008
#
# Latest Version: http://modxcms.com/Jot-998.html
# Jot Demo Site: http://projects.zerobarrier.nl/modx/
# Documentation: http://wiki.modxcms.com/index.php/Jot (wiki)
#
####*/

$jotPath = $modx->config[\'base_path\'] . \'assets/snippets/jot/\';
include_once($jotPath.\'jot.class.inc.php\');

$Jot = new CJot;
$Jot->VersionCheck("1.1.4");
$Jot->Set("path",$jotPath);
$Jot->Set("action", $action);
$Jot->Set("postdelay", $postdelay);
$Jot->Set("docid", $docid);
$Jot->Set("tagid", $tagid);
$Jot->Set("subscribe", $subscribe);
$Jot->Set("moderated", $moderated);
$Jot->Set("captcha", $captcha);
$Jot->Set("badwords", $badwords);
$Jot->Set("bw", $bw);
$Jot->Set("sortby", $sortby);
$Jot->Set("numdir", $numdir);
$Jot->Set("customfields", $customfields);
$Jot->Set("guestname", $guestname);
$Jot->Set("canpost", $canpost);
$Jot->Set("canview", $canview);
$Jot->Set("canedit", $canedit);
$Jot->Set("canmoderate", $canmoderate);
$Jot->Set("trusted", $trusted);
$Jot->Set("pagination", $pagination);
$Jot->Set("placeholders", $placeholders);
$Jot->Set("subjectSubscribe", $subjectSubscribe);
$Jot->Set("subjectModerate", $subjectModerate);
$Jot->Set("subjectAuthor", $subjectAuthor);
$Jot->Set("notify", $notify);
$Jot->Set("notifyAuthor", $notifyAuthor);
$Jot->Set("validate", $validate);
$Jot->Set("title", $title);
$Jot->Set("authorid", $authorid);
$Jot->Set("css", $css);
$Jot->Set("cssFile", $cssFile);
$Jot->Set("cssRowAlt", $cssRowAlt);
$Jot->Set("cssRowMe", $cssRowMe);
$Jot->Set("cssRowAuthor", $cssRowAuthor);
$Jot->Set("tplForm", $tplForm);
$Jot->Set("tplComments", $tplComments);
$Jot->Set("tplModerate", $tplModerate);
$Jot->Set("tplNav", $tplNav);
$Jot->Set("tplNotify", $tplNotify);
$Jot->Set("tplNotifyModerator", $tplNotifyModerator);
$Jot->Set("tplNotifyAuthor", $tplNotifyAuthor);
$Jot->Set("tplSubscribe", $tplSubscribe);
$Jot->Set("debug", $debug);
$Jot->Set("output", $output);
return $Jot->Run();';
$s['ListIndexer'] = '// --------------------
// Snippet: ListIndexer
// --------------------
// Version: 1.0.1
//
// added in 1.0.1: hidePrivate (hide items from unauthorized users)
//
// Derived from ListIndex 0.6j by jaredc@honeydewdesign.com
// Now supports Show In Menu
//
// This snippet was designed to be a VERY flexible way
// of showing what has been recently added. You can use
// this snippet to show news from one folder, or what has
// been recently added site wide. You can even show what\'s
// new in a \'section\' (everything under a defined folder)!
//

// Configuration Settings

   // Set the following snippet defaults the way you would normally
   // use this snippet, then use snippet variables in other situations
   // to override the default behaviors.

   // GENERAL OPTIONS

   // $rootFolder [ NULL | string (comma separated page id\'s) ]
   // Decide which folder to pull recent documents from.
   // If you want to specify a few folders, comma separate them.
   // The default NULL will set current page as root. Using 0
   // would search site wide. Can be set with snippet parameter
   // $LIn_root like:
   // [[ListIndexer?LIn_root=3,6,88]] 
   $rootFolder = NULL;

   // $descendentDepth [ int ]
   // How many levels below the root folder(s) do you want to
   // include? Can be overridden in snippet call with LIn_depth:
   // [[ListIndexer?LIn_depth=2]]
   // Uncomment one of these common two or create your own:
   // $descendentDepth = 1; // just children of root folder(s)
   $descendentDepth = 0; // all decendents of root folder(s)

   // $seeThruUnpub [ true | false ]
   // When using descendents, do you want to consider files below
   // an unpublished (hidden) folder? Usually no. But you decide.
   // Snippet parameter: LIn_seeThru
   // [[ListIndexer?LIn_seeThru=1]]
   $seeThruUnpub = false;

   // $seeShowInMenu [ true | false ]
   // When using descendents, do you want to consider files flagged
   // to be hidden from the menus? Usually no. But you decide.
   // Snippet parameter: LIn_seeShowInMenu
   // [[ListIndexer?LIn_seeShowInMenu=1]]
   $seeShowInMenu = false;
   
   // $hidePrivate [ true | false ]
   // Hide items from users that don\'t have appropriate
   // rights to view. Usually true. But you decide.
   // Snippet parameter: LIn_hidePrivate
   // [[ListIndexer?LIn_hidePrivate=0]]
   $hidePrivate = true;

   // $mode [ \'short\' | \'full\' ]
   // Defines whether this list should be a full, paged
   // list of all documents, or a short list of the most
   // recent few (how many will be defined next). Can be
   // overridden in snippet call with $LIn_mode:
   // [[ListIndexer?LIn_mode=full]]
   $mode = \'short\';
   
   // $sortBy [ \'alpha\' | \'date\' | \'menuindex\' ]
   // The default date will sort by most recent items first, but
   // by using the \'alpha\' option, and using full mode, you could
   // use this to create an index, or directory.
   // Settable with snippet call $LIn_sort:
   // [[ListIndexer?LIn_sort=alpha]]
   $sortBy = \'date\';
   
   // $sortDir [ \'ASC\' | \'DESC\' ]
   // Sort direction ascending or descending. Is applied to whatever $sortBy
   // field you have chosen above. If left blank, menuindex and alpha will sort
   // ASC and date DESC.
   // $LIn_dir in snippet call:
   // [[ListIndexer?LIn_dir=ASC&LIn_sortBy=menuindex]]
   $sortDir = \'\';

   // WHAT TO DISPLAY

   // $defaultTitle [ string ]
   // If you want a default title for your list
   // you can declare it here. Or use an empty
   // string to leave this off. This can be overridden
   // in the snippet call with the variable $LIn_title:
   // [[ListIndexer?LIn_title=Some new title]]
   $defaultTitle = \'\';

   // $shortDesc [ true | false ]
   // Show the description on the short list, or not. Snippet
   // parameter $LIn_sDesc:
   // [[ListIndexer?LIn_sDesc=0]]
   $shortDesc = true;

   // $fullDesc [ true | false ]
   // Show the description on the full list, or not. Snippet
   // parameter $LIn_fDesc:
   // [[ListIndexer?LIn_fDesc=0]]
   $fullDesc = true;

   // $linkToIndex [ int ]
   // If you have a page set up as an \'index\' for all the 
   // documents in this list, you can link to it by specifying 
   // its id- can also be set in snippet call with LIn_link:
   // [[ListIndexer?LIn_link=8]]
   // The default 0 will eliminate this link
   $linkToIndex = 0;

   // $indexText [ string ]
   // If you want a link to an index (probably a page running this
   // snippet in "full" mode), you can declare what you want that
   // link to say here. Or in the snippet call with LIn_indexText:
   // [[ListIndexer?LIn_indexText=Financial News Index]]
   $indexText = \'Index\';

   // $showCreationDate [ true | false ]
   // Decide to include date of creation in output or not. From
   // snippet call $LIn_showDate use 1 (true) or 0 (false)
   // [[ListIndexer?LIn_showDate=1]]
   $showCreationDate = true;

   // $dateFormat [ string ]
   // Used to define how date will be displayed (if using date)
   // Y = 4 digit year     y = 2 digit year
   // M = Jan - Dec        m = 01 - 12
   // D = Sun - Sat        d = 01 -31
   // Other standard PHP characters may be used
   $dateFormat = \'Y.m.d\';

   // $shortQty [ int ]
   // Number of entries to list in the short list. Can be
   // overridden in snippet call with $LIn_sQty:
   //[[ListIndexer?LIn_sQty=3]]
   $shortQty = 3;

   // PAGING

   // $fullQty [ int ]
   // Number of entries PER PAGE to list in the full list
   // Can be overridden in snippet call with $LIn_fQty:
   // [[ListIndexer?LIn_fQty=20]]
   // To show all set to 0 here or in snippet call
   $fullQty = 10;
   
   // $pageSeparator [ string ]
   // What you want your page number links to be separated by.
   // You NEED to include spaces if you want them. They are NOT
   // created automatically to facilitate styling ability.
   // For instance, " | " will render links like:
   // 1 | 2 | 3 | 4
   $pageSeparator = " | ";
   
   // $pgPosition [ \'top\' | \'bottom\' | \'both\']
   // Pick where you want your pagination links to appear.
   $pgPosition = \'both\';

   // PERFORMANCE

   // $useFastUrls [ true | false ]
   // IMPORTANT- using fast urls will reduce database queries
   // and improve performance WHEN IN FULL MODE ONLY and 
   // should NOT be used when multiple instances of this snippet
   // appear on the same page. With snippet call LIn_fast use 1
   // (true) or 0 (false)
   // [[ListIndexer?LIn_fast=0]]
   $useFastUrls = false;

   // $newLinesForLists [ true | false ]
   // Depending on how you want to style your list, you may
   // or may not want your <li>s on new lines. Generally, if you
   // are displaying then inline (horizontal, you do not want new
   // lines, but standard vertical block styling you do. This is
   // for IE, real browsers don\'t care.
   $newLinesForLists = true;

// Styles
//
// The following are the styles included in this snippet. It is up
// to you to include these styles in your stylesheet to get them to
// look the way you want.

   // div.LIn_title {}          List title div
   // ul.LIn_fullMode {}        UL class
   // ul.LIn_shortMode {}       UL class
   // span.LIn_date {}          Span surrounding pub/created date
   // span.LIn_desc {}          Span surrounding description
   // div.LIn_pagination        Div surrounding pagination links
   // span.LIn_currentPage {}   Span surrounding current page of
   //                           pagination (which wouldn\'t be css-able
   //                           by virtue of its <a> tag)


// **********************************************************************
// END CONFIG SETTINGS
// THE REST SHOULD TAKE CARE OF ITSELF
// **********************************************************************

// Take care of IE list issue
$ie = ($newLinesForLists)? "\\n" : \'\' ;

// Use snippet call defined variables if set
$activeTitle = (isset($LIn_title))? $LIn_title : $defaultTitle ;
$mode = (isset($LIn_mode))? $LIn_mode : $mode ;
$descendentDepth = (isset($LIn_depth))? $LIn_depth : $descendentDepth ;
$seeThruUnpub = (isset($LIn_seeThru))? $LIn_seeThru : $seeThruUnpub ;
$seeShowInMenu = (isset($LIn_seeShowInMenu))? $LIn_seeShowInMenu : $seeShowInMenu ;
$hidePrivate = (isset($LIn_hidePrivate))? $LIn_hidePrivate : $hidePrivate;
$linkToIndex = (isset($LIn_link))? $LIn_link : $linkToIndex ;
$rootFolder = (isset($LIn_root))? $LIn_root : $rootFolder ;
$shortQty = (isset($LIn_sQty))? $LIn_sQty : $shortQty ;
$fullQty = (isset($LIn_fQty))? $LIn_fQty : $fullQty ;
$showCreationDate = (isset($LIn_showDate))? $LIn_showDate : $showCreationDate ;
$indexText = (isset($LIn_indexText))? $LIn_indexText : $indexText ;
$useFastUrls = (isset($LIn_fast))? $LIn_fast : $useFastUrls ;
$sortBy = (isset($LIn_sort))? $LIn_sort : $sortBy;
$shortDesc = (isset($LIn_sDesc))? $LIn_sDesc : $shortDesc ;
$fullDesc = (isset($LIn_fDesc))? $LIn_fDesc : $fullDesc ;
$sortDir = (isset($LIn_dir))? $LIn_dir : $sortDir ;
if ($sortDir == \'\') $sortDir = ($sortBy == \'date\')? \'DESC\' : \'ASC\' ;


// Make useful variable shortcut for the content table
//$tbl = $modx->dbConfig[\'dbase\'] . "." . $modx->dbConfig[\'table_prefix\'] . "site_content";
$tblsc = $modx->getFullTableName("site_content");
$tbldg = $modx->getFullTableName("document_groups");

// Initialize output
$output = \'\';

// ---------------------------------------------------
// ---------------------------------------------------
// Query db for parent folders, or not. First check to
// see if a querystring cheat has been provided- this
// should speed things up considerably when using this
// in full mode. (a.k.a. fastUrls)
// ---------------------------------------------------
// ---------------------------------------------------
$inFolder= isset($_GET[\'LIn_f\'])? $_GET[\'LIn_f\']: 0;
if ((!$inFolder && $useFastUrls) || !$useFastUrls ){
  // Only run all the database queries if we don\'t already
  // know the folders AND fastUrls are desired.

  // ---------------------------------------------------
  // Seed list of viable parents
  // ---------------------------------------------------

  if ($rootFolder == NULL){
    $rootFolder = $modx->documentIdentifier;
  }
  // Set root level parent array
  $seedArray = explode(\',\',$rootFolder);
  $parentsArray = array();
  foreach($seedArray AS $seed){
    $parentsArray[\'level_0\'][] = $seed;
  }

  // ---------------------------------------------------
  // Make array of all allowed parents
  // ---------------------------------------------------

  // Process valid parents
  $levelCounter = 1;

  while (((count($parentsArray) < $descendentDepth) || ($descendentDepth == 0)) && ($levelCounter <= count($parentsArray)) && ($levelCounter < 10)){

    // Find all decendant parents for this level
    $pLevel = \'level_\'.($levelCounter - 1);
    $tempLevelArray = $parentsArray[$pLevel];

    foreach($tempLevelArray AS $p){

      // Get children who are parents (isfolder = 1)
      $validParentSql = "";
      $validParentSql .= "SELECT id FROM $tblsc sc WHERE ";
      $validParentSql .= "isfolder = 1 AND parent = $p ";
      $validParentSql .= "AND sc.deleted=0 ";
      $validParentSql .= ($seeThruUnpub)? ";" : "AND sc.published = 1;";

      // Run statement
      $rsTempParents = $modx->dbQuery($validParentSql);
      // Get number of results
      $countTempParents = $modx->recordCount($rsTempParents);

      // If there are results, put them in an array
      $tempValidArray = false;
      if ($countTempParents){
        for ($i=0;$i<$countTempParents;$i++){
          $tempId = $modx->fetchRow($rsTempParents);
          $tempValidArray[] = $tempId[\'id\'];
        } // end while
      } // end if

	  // populate next level of array 
	  if ($tempValidArray){
	    foreach($tempValidArray AS $kid){
	      $kidLevel = \'level_\'.$levelCounter;
	      $parentsArray[$kidLevel][] = $kid;
	    } // end foreach

	  } // end if
    } // end foreach

    // Do next level
    $levelCounter++;

  } // end while

  // Finalize list of parents
  $validParents = \'\';
  foreach ($parentsArray AS $level){
    foreach ($level AS $validP){
      $validParents .= $validP . \',\';
    }
  }

  // Remove trailing comma
  $validParents = substr($validParents,0,strlen($validParents)-1);

} else {
  $validParents = $_GET[\'LIn_f\'];
}

// ---------------------------------------------------
// Make appropriate SQL statement to pull recent items
// ---------------------------------------------------

// get document groups for current user
if($docgrp = $modx->getUserDocGroups()) $docgrp = implode(",",$docgrp);

$access = " (".($modx->isFrontend() ? "sc.privateweb=0":"1=\'".$_SESSION[\'mgrRole\']."\' OR sc.privatemgr=0").
          (!$docgrp ? "":" OR dg.document_group IN ($docgrp)").") AND ";

// Initialize
$recentSql = "";
$recentSql .= "SELECT sc.id, pagetitle, description";
// Include pub_date or createdon date if date is desired
$recentSql .= ($showCreationDate)? ", IF(pub_date > 0, pub_date, createdon) AS pubDate ": " " ;
$recentSql .= "FROM $tblsc sc LEFT JOIN $tbldg dg on dg.document = sc.id ";
$recentSql .= "WHERE ";
$recentSql .= ($hidePrivate)? $access:"";
// Look everywhere, or just under valid parents
$recentSql .= (($rootFolder == 0) && $seeThruUnpub && ($descendentDepth == 0))? "" : "parent IN ($validParents) AND " ;
// Published
$recentSql .= "sc.published = 1 ";
// Show In Menu
$recentSql .= ($seeShowInMenu)? " " : " AND sc.hidemenu=0 " ;
// Not deleted
$recentSql .= "AND sc.deleted=0 ";
// Choose sort method
switch ($sortBy){
  case \'alpha\':
    $recentSql .= "ORDER BY pagetitle ";
    break;
  case \'menuindex\':
    $recentSql .= "ORDER BY menuindex ";
    break;
  default:
    $recentSql .= "ORDER BY IF(pub_date>0, pub_date, createdon) ";
    break;
}
// Provide a sort direction
$recentSql .= $sortDir;

// If this is a short list, just pull a limited number
$recentSql .= ($mode == \'short\')? " LIMIT $shortQty;" : ";" ;

// Run statement
$rsRecent = $modx->dbQuery($recentSql);
// Count records
$recentLimit = $modx->recordCount($rsRecent);

// ---------------------------------------------------
// Generate pagination string if needed
// ---------------------------------------------------
$offsetParam = isset($_GET[\'LIn_o\'])? $_GET[\'LIn_o\']: 0;
$offset = ($offsetParam && ($mode == \'full\'))? $offsetParam : 0 ;
$pagination = \'\';

// Don\'t bother unless there are enough records to justify it
if ( ($mode == \'full\') && ($recentLimit > $fullQty) && ($fullQty) ){
  $fullUrl = $_SERVER[\'REQUEST_URI\'];
  $urlPieces = parse_url($fullUrl);
  $urlPath = $urlPieces[\'path\'];
  $otherQs = \'\';

  if ($urlPieces[\'query\']){
    foreach($_GET AS $qsKey=>$qsValue){
	  if (($qsKey != \'LIn_o\') && ($qsKey != \'LIn_f\')){
	    $otherQs .= \'&\'.$qsKey.\'=\'.$qsValue;
	  }
	}
  } 
  
  $fastUrl = $urlPath.\'?LIn_f=\'.$validParents.$otherQs;

  // Determine number of pages needed to show results
  $totalPages = ceil($recentLimit/$fullQty);
  
  // Make links
  for ($j = 0 ; $j < $totalPages; $j++){
    // only include links to OTHER pages, not current page
    if($offset == $j*$fullQty){
	  $pagination .= \'<span class="LIn_currentPage">\'.($j+1) .\'</span>\';
	} else {
      $pagination .= \'<a href="\'.$fastUrl.\'&LIn_o=\'.($j*$fullQty).\'" title="\'.($j+1).\'">\'.($j+1) .\'</a>\';
	}
	if ($j < $totalPages-1){
	  $pagination .= $pageSeparator;
	}
  }
  
  // Make final pagination link set in it\'s own div
  $pagination = \'<div class="LIn_pagination">\'."\\n".$pagination."\\n</div>\\n";
  
}


// ---------------------------------------------------
// Create title if wanted
// ---------------------------------------------------

if ($activeTitle){
  $output .= \'<div class="LIn_title">\'.$activeTitle.\'</div>\'."\\n";
}

// ---------------------------------------------------
// Create list of recent items
// ---------------------------------------------------

// Include pagination
$output .= ($pgPosition == \'top\' || $pgPosition == \'both\')? $pagination : \'\' ;

$output .= \'<ul class="LIn_\'.$mode.\'Mode">\' . $ie;

$recentCounter = $offset;
if ($mode == \'short\') {
  $recentCounterLimit = min($shortQty,$recentLimit);
} else {
  $recentCounterLimit = ($fullQty)? min(($fullQty+$offset),$recentLimit) : $recentLimit ;
}

while (($recentCounter < $recentCounterLimit) && $rsRecent && ($recentLimit > 0)){
  mysql_data_seek($rsRecent,$recentCounter);
  $recentRecord = $modx->fetchRow($rsRecent);
  $output .= \'<li>\';
  // Link to page
  $output .= \'<a href="[~\'.$recentRecord[\'id\'].\'~]" title="\'.strip_tags($recentRecord[\'pagetitle\']).\'">\'.$recentRecord[\'pagetitle\'].\'</a> \';
  // Date if desired
  if ($showCreationDate){
    $output .= \'<span class="LIn_date">\'.date($dateFormat,$recentRecord[\'pubDate\']).\'</span> \';
  }
  // Description if desired
  if ((($mode == \'short\') && ($shortDesc)) || (($mode == \'full\') && ($fullDesc))){
   $output .= \'<span class="LIn_desc">\'.$recentRecord[\'description\'].\'</span>\';
  }
  // wrap it up
  $output .= \'</li>\' . $ie;
  $recentCounter ++;
}

$output .= \'</ul>\' . $ie;

$output .= ($pgPosition == \'bottom\' || $pgPosition == \'both\')? $pagination : \'\' ;

// ---------------------------------------------------
// Link to index
// ---------------------------------------------------

if ($linkToIndex) {

  $output .= \'<div class="LIn_index">\';
  $output .= \'<a href="[~\'.$linkToIndex.\'~]" title="\'.$indexText.\'">\'.$indexText.\'</a>\';
  $output .= \'</div>\';

}

// ---------------------------------------------------
// Send to browser
// ---------------------------------------------------

return $output;';
$s['MemberCheck'] = '#::::::::::::::::::::::::::::::::::::::::
# Snippet name: MemberCheck 
# Short Desc: checks logged in groups and displays a chunk
# Version: 1.0
# Created By Ryan Thrash (vertexworks.com)
# Sanitized By Jason Coward (opengeek.com)
#
# Date: November 29, 2005
#
# Changelog: 
# Nov 29, 05 -- initial release
# Jul 13, 06 -- adjusted Singleton to work under PHP4, added placeholder code (by: garryn)
#
#::::::::::::::::::::::::::::::::::::::::
# Description: 	
#	Checks to see if users belong to a certain group and 
#	displays the specified chunk if they do. Performs several
#	sanity checks and allows to be used multiple times on a page.
#
# Params:
#	&groups [array] (REQUIRED)
#		array of webuser group-names to check against
#
#	&chunk [string] (REQUIRED)
#		name of the chunk to use if passes the check
#
#	&ph [string] (optional)
#		name of the placeholder to set instead of directly retuning chunk
#
#	&debug [boolean] (optional | false) 
#		turn on debug mode for extra troubleshooting
#
# Example Usage:
#
#	[[MemberCheck? &groups=`siteadmin, registered users` &chunk=`privateSiteNav` &ph=`MemberMenu` &debug=`true`]]
#
#	This would place the \'members-only\' navigation store in the chunk \'privateSiteNav\'
#	into a placeholder (called \'MemberMenu\'). It will only do this as long as the user 
#	is logged in as a webuser and is a member of the \'siteadmin\' or the \'registered users\'
#	groups. The optional debug parameter can be used to display informative error messages 
#	when configuring this snippet for your site. For example, if the developer had 
#	mistakenly typed \'siteowners\' for the first group, and none existed with debug mode on, 
#	it would have returned the error message: The group siteowners could not be found....
#
#::::::::::::::::::::::::::::::::::::::::

# debug parameter
$debug = isset ($debug) ? $debug : false;

# check if inside manager
if ($m = $modx->insideManager()) {
	return \'\'; # don\'t go any further when inside manager
}

if (!isset ($groups)) {
	return $debug ? \'<p>Error: No Group Specified</p>\' : \'\';
}

if (!isset ($chunk)) {
	return $debug ? \'<p>Error: No Chunk Specified</p>\' : \'\';
}

# turn comma-delimited list of groups into an array
$groups = explode(\',\', $groups);

if (!class_exists(\'MemberCheck\')) {
	class MemberCheck {
		var $allGroups = NULL;
		var $debug;

		function getInstance($debug) {
			static $instance;
			if (!isset ($instance)) {
				$instance = new MemberCheck($debug);
			}
			return $instance;
		}

		function MemberCheck($debug = false) {
			global $modx;

			$this->debug = $debug;
			if ($debug) {
				$this->allGroups = array ();
				$tableName = $modx->getFullTableName(\'webgroup_names\');
				$sql = "SELECT name FROM $tableName";
				if ($rs = $modx->db->query($sql)) {
					while ($row = $modx->db->getRow($rs)) {
						array_push($this->allGroups, stripslashes($row[\'name\']));
					}
				}
			}
		}

		function isValidGroup($groupName) {
			$isValid = !(array_search($groupName, $this->allGroups) === false);
			return $isValid;
		}

		function getMemberChunk(& $groups, $chunk) {
			global $modx;
			$o = \'\';
			if (is_array($groups)) {
				for ($i = 0; $i < count($groups); $i++) {
					$groups[$i] = trim($groups[$i]);
					if ($this->debug) {
						if (!$this->isValidGroup($groups[$i])) {
							return "<p>The group <strong>" . $groups[$i] . "</strong> could not be found...</p>";
						}
					}
				}

				$check = $modx->isMemberOfWebGroup($groups);

				$chunkcheck = $modx->getChunk($chunk);

				$o .= ($check && $chunkcheck) ? $chunkcheck : \'\';
				if (!$chunkcheck)
					$o .= $this->debug ? "<p>The chunk <strong>$chunk</strong> not found...</p>" : \'\';
			} else {
				$o .= "<p>No valid group names were specified!</p>";
			}

			return $o;
		}
	}
}

$memberCheck = MemberCheck :: getInstance($debug);

if (!isset ($ph)) {
	return $memberCheck->getMemberChunk($groups, $chunk);
} else {
	$modx->setPlaceholder($ph, $memberCheck->getMemberChunk($groups, $chunk));
	return \'\';
}';
$s['NewsPublisher'] = '#::::::::::::::::::::::::::::::::::::::::
#
#  Snippet Name: NewsPublisher 
#  Short Desc: Create articles directly from front end (news, blogs, PR, etc.)
#  Created By: Raymond Irving (xwisdom@yahoo.com), August 2005
#
#  Version: 1.4
#  Modified: December 13, 2005
#
#  Changelog: 
#    Mar 05, 06 -- modx_ prefix removed [Mark]
#    Dec 13, 05 -- Now inherrits web/manager docgroups thanks to Jared Carlow
#
#::::::::::::::::::::::::::::::::::::::::
#  Description:     
#    Checks to see if users belong to a certain group and 
#    displays the specified chunk if they do. Performs several
#    sanity checks and allows to be used multiple times on a page.
#    Only meant to be used once per page.
#::::::::::::::::::::::::::::::::::::::::
#  
#  Parameters:
#    &folder      - folder id where comments are stored
#    &makefolder  - set to 1 to automatically convert the parent document to a folder. Defaults to 0
#    &postid      - document id to load after posting news item. Defaults to the page created
#    &canpost     - comma delimitted web groups that can post comments. leave blank for public posting
#    &badwords    - comma delimited list of words not allowed in post
#    &template    - name of template to use for news post
#    &headertpl   - header template (chunk name) to be inserted at the begining of the news content
#    &footertpl   - footer template (chunk name) to be inserted at the end of the news content
#    &formtpl     - form template (chunk name)
#    &rtcontent   - name of a richtext content form field 
#    &rtsummary   - name of a richtext summary form field 
#    &showinmenu  - sets the flag to true or false (1|0) as to whether or not it shows in the menu. defaults to false (0)
#    &aliastitle  - set to 1 to use page title as alias suffix. Defaults to 0 - date created.
#    &clearcache  - when set to 1 the system will automatically clear the site cache after publishing an article.
#  
#::::::::::::::::::::::::::::::::::::::::

// get user groups that can post articles
$postgrp = isset($canpost) ? explode(",",$canpost):array();
$allowAnyPost = count($postgrp)==0 ? true : false;

// get clear cache
$clearcache  = isset($clearcache) ? 1:0;

// get alias title
$aliastitle  = isset($aliastitle) ? 1:0;

// get folder id where we should store articles
// else store in current document
$folder = isset($folder) ? intval($folder):$modx->documentIdentifier;

// set rich text content field
$rtcontent = isset($rtcontent) ? $rtcontent:\'content\';

// set rich text summary field
$rtsummary = isset($rtsummary) ? $rtsummary:\'introtext\';

// get header
$header = isset($headertpl) ? "{{".$headertpl."}}":\'\';

// get footer
$footer = isset($footertpl) ? "{{".$footertpl."}}":\'\';

// get postback status
$isPostBack = isset($_POST[\'NewsPublisherForm\']) ? true:false;

// get badwords
if(isset($badwords)) {
    $badwords = str_replace(\' \',\'\', $badwords);
    $badwords = "/".str_replace(\',\',\'|\', $badwords)."/i";
}

// get menu status
$hidemenu = isset($showinmenu) && $showinmenu==1 ? 0 : 1;

// get template
$template = isset($template) ? $modx->db->getValue(\'SELECT id FROM \'.$modx->getFullTableName(\'site_templates\').\' WHERE templatename=\\\'\'.mysql_escape_string($template).\'\\\'\'):$modx->config[\'default_template\'];

$message = \'\';

// get form template
if(isset($formtpl)) $formTpl = $modx->getChunk($formtpl);
if(empty($formTpl)) $formTpl = \'
    <form name="NewsPublisher" method="post">
        <input name="NewsPublisherForm" type="hidden" value="on" />
        Page title:<br /><input name="pagetitle" type="text" size="40" value="[+pagetitle+]" /><br />
        Long title:<br /><input name="longtitle" type="text" size="40" value="[+longtitle+]" /><br />
        Description:<br /><input name="description" type="text" size="40" value="[+description+]" /><br />
        Published date:<br /><input name="pub_date" type="text" value="[+pub_date+]" size="40" readonly="readonly" />
        <a onClick="nwpub_cal1.popup();" onMouseover="window.status=\\\'Select date\\\'; return true;" onMouseout="window.status=\\\'\\\'; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="manager/media/style/MODxLight/images/icons/cal.gif" width="16" height="16" border="0" alt="Select date" /></a>
        <a onClick="document.NewsPublisher.pub_date.value=\\\'\\\'; return true;" onMouseover="window.status=\\\'Remove date\\\'; return true;" onMouseout="window.status=\\\'\\\'; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="manager/media/style/MODxLight/images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="Remove date"></a><br />
        Unpublished date:<br /><input name="unpub_date" type="text" value="[+unpub_date+]" size="40" readonly="readonly" />
        <a onClick="nwpub_cal2.popup();" onMouseover="window.status=\\\'Select date\\\'; return true;" onMouseout="window.status=\\\'\\\'; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="manager/media/style/MODxLight/images/icons/cal.gif" width="16" height="16" border="0" alt="Select date" /></a>
        <a onClick="document.NewsPublisher.unpub_date.value=\\\'\\\'; return true;" onMouseover="window.status=\\\'Remove date\\\'; return true;" onMouseout="window.status=\\\'\\\'; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="manager/media/style/MODxLight/images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="Remove date"></a><br />
        Summary:<br /><textarea name="introtext" cols="50" rows="5">[+introtext+]</textarea><br />
        Content:<br /><textarea name="content" cols="50" rows="8">[+content+]</textarea><br />
        <input name="send" type="submit" value="Submit" />
    </form>
    <script language="JavaScript" src="manager/media/script/datefunctions.js"></script>
    <script type="text/javascript">
        var elm_txt = {}; // dummy
        var pub = document.forms["NewsPublisher"].elements["pub_date"];
        var nwpub_cal1 = new calendar1(pub,elm_txt);
        nwpub_cal1.path="[(base_url)]manager/media/";
        nwpub_cal1.year_scroll = true;
        nwpub_cal1.time_comp = true;    

        var unpub = document.forms["NewsPublisher"].elements["unpub_date"];
        var nwpub_cal2 = new calendar1(unpub,elm_txt);
        nwpub_cal2.path="[(base_url)]manager/media/";
        nwpub_cal2.year_scroll = true;
        nwpub_cal2.time_comp = true;    
    </script>\';


// switch block
switch ($isPostBack) {
    case true:
        // process post back
        // remove magic quotes from POST
        if(get_magic_quotes_gpc()){
            $_POST = array_map("stripslashes", $_POST);
        }   
        if(trim($_POST[\'pagetitle\'])==\'\') $modx->webAlert(\'Missing page title.\');
        elseif($_POST[$rtcontent]==\'\') $modx->webAlert(\'Missing news content.\');
        else {
            // get created date
            $createdon = time();

            // set alias name of document used to store articles
            if(!$aliastitle) $alias = \'article-\'.$createdon;
            else {
                $alias = $modx->stripTags($_POST[\'pagetitle\']);
                $alias = strtolower($alias);
                $alias = preg_replace(\'/&.+?;/\', \'\', $alias); // kill entities
                $alias = preg_replace(\'/[^\\.%a-z0-9 _-]/\', \'\', $alias);
                $alias = preg_replace(\'/\\s+/\', \'-\', $alias);
                $alias = preg_replace(\'|-+|\', \'-\', $alias);
                $alias = trim($alias, \'-\');         
                $alias = \'article-\'.$modx->db->escape($alias);
            }

            $user = $modx->getLoginUserName();
            $userid = $modx->getLoginUserID();
            if(!$user && $allowAnyPost) $user = \'anonymous\';

            // check if user has rights
            if(!$allowAnyPost && !$modx->isMemberOfWebGroup($postgrp)) {
                return \'You are not allowed to publish articles\';
            }

            $allowedTags = \'<p><br><a><i><em><b><strong><pre><table><th><td><tr><img><span><div><h1><h2><h3><h4><h5><font><ul><ol><li><dl><dt><dd>\';

            // format content
            $content = $modx->stripTags($_POST[$rtcontent],$allowedTags);
            $content = str_replace(\'[+user+]\',$user,$content);


            $content = str_replace(\'[+createdon+]\',strftime(\'%d-%b-%Y %H:%M\',$createdon),$content);
            foreach($_POST as $n=>$v) {
                if(!empty($badwords)) $v = preg_replace($badwords,\'[Filtered]\',$v); // remove badwords
                $v = $modx->stripTags(htmlspecialchars($v));
                $v = str_replace("\\n",\'<br />\',$v);
                $content = str_replace(\'[+\'.$n.\'+]\',$v,$content);
            }

            $title = mysql_escape_string($modx->stripTags($_POST[\'pagetitle\']));
            $longtitle = mysql_escape_string($modx->stripTags($_POST[\'longtitle\']));
            $description = mysql_escape_string($modx->stripTags($_POST[\'description\']));
            $introtext = mysql_escape_string($modx->stripTags($_POST[$rtsummary],$allowedTags));
            $pub_date = $_POST[\'pub_date\'];
            $unpub_date = $_POST[\'unpub_date\'];
            $published = 1;

            // check published date
            if($pub_date=="") {
                $pub_date="0";
            } else {
                list($d, $m, $Y, $H, $M, $S) = sscanf($pub_date, "%2d-%2d-%4d %2d:%2d:%2d");
                $pub_date = strtotime("$m/$d/$Y $H:$M:$S");

                if($pub_date < $createdon) {
                    $published = 1;
                }    elseif($pub_date > $createdon) {
                    $published = 0; 
                }
            }

            // check unpublished date
            if($unpub_date=="") {
                $unpub_date="0";
            } else {
                list($d, $m, $Y, $H, $M, $S) = sscanf($unpub_date, "%2d-%2d-%4d %2d:%2d:%2d");
                $unpub_date = strtotime("$m/$d/$Y $H:$M:$S");
                if($unpub_date < $createdon) {
                    $published = 0;
                }
            }

            // set menu index
            $mnuidx = $modx->db->getValue(\'SELECT MAX(menuindex)+1 as \\\'mnuidx\\\' FROM \'.$modx->getFullTableName(\'site_content\').\' WHERE parent=\\\'\'.$folder.\'\\\'\');
            if($mnuidx<1) $mnuidx = 0;

            // post news content
            $flds = array(
                \'pagetitle\'     => $title,
                \'longtitle\'     => $longtitle,
                \'description\' => $description,
                \'introtext\'     => $introtext,
                \'alias\'             => $alias,
                \'parent\'            => $folder, 
                \'createdon\'     => $createdon,
                \'createdby\'     => ($userid>0 ? $userid * -1:0),
                \'editedon\'        => \'0\',
                \'editedby\'        => \'0\',
                \'published\'     => $published,
                \'pub_date\'        => $pub_date,
                \'unpub_date\'    => $unpub_date,
                \'deleted\'         => \'0\',
                \'hidemenu\'        => $hidemenu,
                \'menuindex\'     => $mnuidx,
                \'template\'        => $template,
                \'content\'         => mysql_escape_string($header.$content.$footer)
            );
            $redirectid = $modx->db->insert($flds,$modx->getFullTableName(\'site_content\'));

            // Doc group thing
            // look in save_content.processor.php for tips (or below)
            $lastInsertId = $modx->db->getInsertId();

            // Get doc groups based on $folder (parent id)
            $parentDocGroupsSql = "SELECT * FROM " . $modx->getFullTableName(\'document_groups\'). " where document=".$folder;
            $parentDocGroupsRs = $modx->db->query($parentDocGroupsSql);
            $parentDocGroupsLimit = $modx->db->getRecordCount($parentDocGroupsRs);
            for ($pdgi = 0; $pdgi < $parentDocGroupsLimit; $pdgi++) { 
                $currentDocGroup = $modx->db->getRow($parentDocGroupsRs);
                $parentDocGroupsArray[$pdgi] = $currentDocGroup[\'document_group\'];
            }

            // put the document in the document_groups it should be in
            // check that up_perms are switched on!
            if($modx->config[\'use_udperms\']==1) {
                if(is_array($parentDocGroupsArray)) {
                    foreach ($parentDocGroupsArray as $dgKey=>$dgValue) {
                        $insertDocSql = "INSERT INTO ".$modx->getFullTableName(\'document_groups\')."(document_group, document) values(".stripslashes($dgValue).", $lastInsertId)";
                        $insertDocRs = $modx->db->query($insertDocSql);
                        if(!$insertDocRs){
                            exit;
                        }
                    } // end foreach
                } // end if doc group array exists
            } // end if perms are used

            // Handle privateweb
            $modx->db->query("UPDATE ".$modx->getFullTableName("site_content")." SET privateweb = 0 WHERE id=\'$lastInsertId\';");
            $privatewebSql =    "
                SELECT DISTINCT ".$modx->getFullTableName(\'document_groups\').".document_group 
                FROM ".$modx->getFullTableName(\'document_groups\').", ".$modx->getFullTableName(\'webgroup_access\')." 
                WHERE 
                ".$modx->getFullTableName(\'document_groups\').".document_group = ".$modx->getFullTableName(\'webgroup_access\').".documentgroup 
                AND 
                ".$modx->getFullTableName(\'document_groups\').".document = $lastInsertId;";
                $privatewebIds = $modx->db->getColumn("document_group",$privatewebSql);
                if(count($privatewebIds)>0) {
                    $modx->db->query("UPDATE ".$modx->getFullTableName("site_content")." SET privateweb = 1 WHERE id = $lastInsertId;");    
                }

                // And privatemgr
                $modx->db->query("UPDATE ".$modx->getFullTableName("site_content")." SET privatemgr = 0 WHERE id=\'$lastInsertId\';");
                $privatemgrSql =    "
                    SELECT DISTINCT ".$modx->getFullTableName(\'document_groups\').".document_group 
                    FROM ".$modx->getFullTableName(\'document_groups\').", ".$modx->getFullTableName(\'membergroup_access\')." 
                    WHERE 
                    ".$modx->getFullTableName(\'document_groups\').".document_group = ".$modx->getFullTableName(\'membergroup_access\')." .documentgroup 
                    AND 
                    ".$modx->getFullTableName(\'document_groups\').".document = $lastInsertId;";
                    $privatemgrIds = $modx->db->getColumn("document_group",$privatemgrSql);
                    if(count($privatemgrIds)>0) {
                        $modx->db->query("UPDATE ".$modx->getFullTableName("site_content")." SET privatemgr = 1 WHERE id = $lastInsertId;");    
                    }
            // end of document_groups stuff!

            if(!empty($makefolder)) {
                // convert parent into folder
                $modx->db->update(array(\'isfolder\'=>\'1\'),$modx->getFullTableName(\'site_content\'),\'id=\\\'\'.$folder.\'\\\'\');
            }

            // empty cache
            if($clearcache==1){
                include_once $modx->config[\'base_path\']."manager/processors/cache_sync.class.processor.php";
                $sync = new synccache();
                $sync->setCachepath("assets/cache/");
                $sync->setReport(false);
                $sync->emptyCache(); // first empty the cache       
            }

            // get redirect/post id
            $redirectid = $modx->db->getValue(\'SELECT id as \\\'redirectid\\\' FROM \'.$modx->getFullTableName(\'site_content\').\' WHERE createdon=\\\'\'.$createdon.\'\\\'\');
            $postid = isset($postid) ? $postid:$redirectid;

            // redirect to post id
            $modx->sendRedirect($modx->makeUrl($postid));
        }

    default: 
        // display news form
        // check if user has rights to post comments
        if(!$allowAnyPost && !$modx->isMemberOfWebGroup($postgrp)) {
            $formTpl = \'\';
        } else {
            foreach($_POST as $n=>$v) {
                $formTpl = str_replace(\'[+\'.$n.\'+]\',$v,$formTpl);
            }
        }
        $formTpl = str_replace(\'[+base_url+]\', $modx->config[\'base_url\'], $formTpl);
        // return form
        return $message.$formTpl;
        break;
}';
$s['Personalize'] = '#::::::::::::::::::::::::::::::::::::::::
# Snippet Name: Personalize 
# Short Desc: calls a chunk if the user is logged in, otherwise calls another
# Version: 2.0
# Created By: 	Ryan Thrash (modx@vertexworks.com), and then
#		powered up by kudo (kudo@kudolink.com)
#
# Date: Aug 03, 2006
#
# Changelog: 
# Dec 01, 05 -- initial release
# Jun 19, 06 -- updated description
# Jul 19, 06 -- hacked by kudo to output chunks
# Aug 03, 06 -- added placeholder for username
#
#::::::::::::::::::::::::::::::::::::::::
# Description: 	
#	Checks to see if webusers are logged in and displays yesChunk if the user
#	is logged or noChunk if user is not logged. Insert only the chunk name as
#	param, without {{}}. Can use a placeholder to output the username.
#	TESTED: can be used more than once per page.
#	TESTED: chunks can contain snippets.
#	
#	
# Params:
#	&yesChunk [string] [REQUIRED]
#		Output for LOGGED users
#
#	&noChunk [string] [REQUIRED] 
#		Output for NOT logged users
#
#	&ph [string] (optional) 
#		Placeholder for placing the username
#		ATTENTION!: place this ph only in yesChunk!
#	
#
# Example Usage:
#
#	[[LoggedOrNot? &yesChunk=`Link` &noChunk=`Register` &ph=`name`]]
#
#	Having Chunks named {{Link}} and another {{Register}}, the first will be
#	published to registered user, the second to non-registered users.
#
#::::::::::::::::::::::::::::::::::::::::

# prepare params and variables
$o = \'\';
$yesChunk = (isset($yesChunk))? $yesChunk : \'\';
$noChunk = (isset($noChunk))? $noChunk : \'\';

# do the work
$test = $modx->getLoginUserName();
if ($test) {
    $o = $modx->getChunk($yesChunk);
  } else {
    $o = $modx->getChunk($noChunk);
}

if (isset($ph)) {
	$modx->setPlaceholder($ph,$test);
	return $o;
} else {
	return $o;
}
';
$s['Reflect'] = '/*
 * Title: Reflect Snippet
 * 
 * Description: 
 * 		Generates date based archives using Ditto
 * 
 * Author: 
 * 		Mark Kaplan for MODx CMF
 * 
 * Version: 
 * 		2.1.0
 * 
 * Note: 
 * 		If Reflect is not retrieving its own documents, make sure that the
 *			Ditto call feeding it has all of the fields in it that you plan on
 *       calling in your Reflect template. Furthermore, Reflect will ONLY
 *			show what is currently in the Ditto result set.
 *       Thus, if pagination is on it will ONLY show that page\'s items.
*/
 

// ---------------------------------------------------
//  Includes
// ---------------------------------------------------

$reflect_base = isset($reflect_base) ? $modx->config[\'base_path\'].$reflect_base : $modx->config[\'base_path\']."assets/snippets/reflect/";
/*
	Param: ditto_base
	
	Purpose:
	Location of Ditto files

	Options:
	Any valid folder location containing the Ditto source code with a trailing slash

	Default:
	[(base_path)]assets/snippets/ditto/
*/

$config = (isset($config)) ? $config : "default";
/*
	Param: config

	Purpose:
 	Load a custom configuration

	Options:
	"default" - default blank config file
	CONFIG_NAME - Other configs installed in the configs folder or in any folder within the MODx base path via @FILE

	Default:
	"default"
	
	Related:
	- <extenders>
*/

require($reflect_base."configs/default.config.php");
require($reflect_base."default.templates.php");
if ($config != "default") {
	require((substr($config, 0, 5) != "@FILE") ? $reflect_base."configs/$config.config.php" : $modx->config[\'base_path\'].trim(substr($config, 5)));
}

// ---------------------------------------------------
//  Parameters
// ---------------------------------------------------

$id = isset($id) ? $id."_" : false;
/*
	Param: id

	Purpose:
	Unique ID for this Ditto instance for connection with other scripts (like Reflect) and unique URL parameters

	Options:
	Any valid folder location containing the Ditto source code with a trailing slash

	Default:
	"" - blank
*/
$getDocuments = isset($getDocuments) ? $getDocuments : 0;
/*
	Param: getDocuments

	Purpose:
 	Force Reflect to get documents

	Options:
	0 - off
	1 - on
	
	Default:
	0 - off
*/
$showItems = isset($showItems) ? $showItems : 1;
/*
	Param: showItems

	Purpose:
 	Show individual items in the archive

	Options:
	0 - off
	1 - on
	
	Default:
	1 - on
*/
$groupByYears = isset($groupByYears)? $groupByYears : 1;
/*
	Param: groupByYears

	Purpose:
 	Group the archive by years

	Options:
	0 - off
	1 - on
	
	Default:
	1 - on
*/
$targetID = isset($targetID) ? $targetID : $modx->documentObject[\'id\'];
/*
	Param: targetID

	Purpose:
 	ID for archive links to point to

	Options:
	Any MODx document with a Ditto call setup with extenders=`dateFilter`
	
	Default:
	Current MODx Document
*/
$dateSource = isset($dateSource) ? $dateSource : "createdon";
/*
	Param: dateSource

	Purpose:
	Date source to display for archive items

	Options:
	# - Any UNIX timestamp from MODx fields or TVs such as createdon, pub_date, or editedon
	
	Default:
	"createdon"
	
	Related:
	- <dateFormat>
*/
$dateFormat = isset($dateFormat) ? $dateFormat : "%d-%b-%y %H:%M";	
/*
	Param: dateFormat

	Purpose:
	Format the [+date+] placeholder in human readable form

	Options:
	Any PHP valid strftime option

	Default:
	"%d-%b-%y %H:%M"
	
	Related:
	- <dateSource>
*/
$yearSortDir = isset($yearSortDir) ? $yearSortDir : "DESC";
/*
	Param: yearSortDir

	Purpose:
 	Direction to sort documents

	Options:
	ASC - ascending
	DESC - descending

	Default:
	"DESC"
	
	Related:
	- <monthSortDir>
*/
$monthSortDir = isset($monthSortDir) ? $monthSortDir : "ASC";
/*
	Param: monthSortDir

	Purpose:
 	Direction to sort the months

	Options:
	ASC - ascending
	DESC - descending

	Default:
	"ASC"
	
	Related:
	- <yearSortDir>
*/
$start = isset($start)? intval($start) : 0;
/*
	Param: start

	Purpose:
 	Number of documents to skip in the results
	
	Options:
	Any number

	Default:
	0
*/	
$phx = (isset($phx))? $phx : 1;
/*
	Param: phx

	Purpose:
 	Use PHx formatting

	Options:
	0 - off
	1 - on
	
	Default:
	1 - on
*/

// ---------------------------------------------------
//  Initialize Ditto
// ---------------------------------------------------
$placeholder = ($id != false && $getDocuments == 0) ? true : false;
if ($placeholder === false) {
	$rID = "reflect_".rand(1,1000);
	$itemTemplate = isset($tplItem) ? $tplItem: "@CODE:".$defaultTemplates[\'item\'];
	$dParams = array(
		"id" => "$rID",
		"save" => "3",	
		"summarize" => "all",
		"tpl" => $itemTemplate,
	);
	
	$source = $dittoSnippetName;
	$params = $dittoSnippetParameters;
		// TODO: Remove after 3.0
		
	if (isset($params)) {
		$givenParams = explode("|",$params);
		foreach ($givenParams as $parameter) {
			$p = explode(":",$parameter);
			$dParams[$p[0]] = $p[1];
		}
	}
	/*
		Param: params

		Purpose:
	 	Pass parameters to the Ditto instance used to retreive the documents

		Options:
		Any valid ditto parameters in the format name:value 
		with multiple parameters separated by a pipe (|)
		
		Note:
		This parameter is only needed for config, start, and phx as you can
		now simply use the parameter as if Reflect was Ditto

		Default:
		[NULL]
	*/
	
	$allParameters = func_get_args();
	$reflectParameters = array(\'reflect_base\',\'config\',\'id\',\'getDocuments\',\'showItems\',\'groupByYears\',\'targetID\',\'yearSortDir\',\'monthSortDir\',\'start\',\'phx\',\'tplContainer\',\'tplYear\',\'tplMonth\',\'tplMonthInner\',\'tplItem\',\'save\');
	foreach ($allParameters[1] as $param=>$value) {
		if (!in_array($param,$reflectParameters) && substr($param,-3) != \'tpl\') {
			$dParams[$param] = $value;
		}
	}

	$source = isset($source) ? $source : "Ditto";
	/*
		Param: source

		Purpose:
		Name of the Ditto snippet to use

		Options:
		Any valid snippet name

		Default:
		"Ditto"
	*/
	$snippetOutput = $modx->runSnippet($source,$dParams);
	$ditto = $modx->getPlaceholder($rID."_ditto_object");
	$resource = $modx->getPlaceholder($rID."_ditto_resource");
} else {
	$ditto = $modx->getPlaceholder($id."ditto_object");
	$resource = $modx->getPlaceholder($id."ditto_resource");
}
if (!is_object($ditto) || !isset($ditto) || !isset($resource)) {
	return !empty($snippetOutput) ? $snippetOutput : "The Ditto object is invalid. Please check it.";
}

// ---------------------------------------------------
//  Templates
// ---------------------------------------------------

$templates[\'tpl\'] = isset($tplContainer) ? $ditto->template->fetch($tplContainer): $defaultTemplates[\'tpl\'];
/*
	Param: tplContainer

	Purpose:
	Container template for the archive

	Options:
	- Any valid chunk name
	- Code via @CODE:
	- File via @FILE:

	Default:
	See default.tempates.php
*/
$templates[\'year\'] = isset($tplYear) ? $ditto->template->fetch($tplYear): $defaultTemplates[\'year\'];
/*
	Param: tplYear

	Purpose:
	Template for the year item

	Options:
	- Any valid chunk name
	- Code via @CODE:
	- File via @FILE:

	Default:
	See default.tempates.php
*/
$templates[\'year_inner\'] = isset($tplYearInner) ? $ditto->template->fetch($tplYearInner): $defaultTemplates[\'year_inner\'];
/*
	Param: tplYearInner

	Purpose:
	Template for the year item (the ul to hold the year template)

	Options:
	- Any valid chunk name
	- Code via @CODE:
	- File via @FILE:

	Default:
	See default.tempates.php
*/
$templates[\'month\'] = isset($tplMonth) ? $ditto->template->fetch($tplMonth): $defaultTemplates[\'month\'];
/*
	Param: tplMonth

	Purpose:
	Template for the month item

	Options:
	- Any valid chunk name
	- Code via @CODE:
	- File via @FILE:

	Default:
	See default.tempates.php
*/
$templates[\'month_inner\'] = isset($tplMonthInner) ? $ditto->template->fetch($tplMonthInner): $defaultTemplates[\'month_inner\'];
/*
	Param: tplMonthInner

	Purpose:
	Template for the month item  (the ul to hold the month template)

	Options:
	- Any valid chunk name
	- Code via @CODE:
	- File via @FILE:

	Default:
	See default.tempates.php
*/
$templates[\'item\'] = isset($tplItem) ? $ditto->template->fetch($tplItem): $defaultTemplates[\'item\'];
/*
	Param: tplItem

	Purpose:
	Template for the individual item

	Options:
	- Any valid chunk name
	- Code via @CODE:
	- File via @FILE:

	Default:
	See default.tempates.php
*/

$ditto->addField("date","display","custom");
	// force add the date field if receiving data from a Ditto instance

// ---------------------------------------------------
//  Reflect
// ---------------------------------------------------

if (function_exists("reflect") === FALSE) {
function reflect($templatesDocumentID, $showItems, $groupByYears, $resource, $templatesDateSource, $dateFormat, $ditto, $templates,$id,$start,$yearSortDir,$monthSortDir) {
	global $modx;
	$cal = array();
	$output = \'\';
	$ph = array(\'year\'=>\'\',\'month\'=>\'\',\'item\'=>\'\',\'out\'=>\'\');
	$build = array();
	$stop = count($resource);

	// loop and fetch all the results
	for ($i = $start; $i < $stop; $i++) {
		$date = getdate($resource[$i][$templatesDateSource]);
		$year = $date["year"];
		$month = $date["mon"];
		$cal[$year][$month][] = $resource[$i];
	}
	if ($yearSortDir == "DESC") {
		krsort($cal);
	} else {
		ksort($cal);
	}
	foreach ($cal as $year=>$months) {
		if ($monthSortDir == "ASC") {
			ksort($months);
		} else {
			krsort($months);
		}
		$build[$year] = $months;
	}
	
	foreach ($build as $year=>$months) {
		$r_year = \'\';
		$r_month = \'\';
		$r_month_2 = \'\';
		$year_count = 0;
		$items = array();
		
		foreach ($months as $mon=>$month) {
			$month_text = strftime("%B", mktime(10, 10, 10, $mon, 10, $year));
			$month_url = $ditto->buildURL("month=".$mon."&year=".$year."&day=false&start=0",$templatesDocumentID,$id);
			$month_count = count($month);
			$year_count += $month_count;
			$r_month = $ditto->template->replace(array("year"=>$year,"month"=>$month_text,"url"=>$month_url,"count"=>$month_count),$templates[\'month\']);
			if ($showItems) {
				foreach ($month as $item) {
					$items[$year][$mon][\'items\'][] = $ditto->render($item, $templates[\'item\'], false, $templatesDateSource, $dateFormat, array(),$phx);
				}
				$r_month_2 = $ditto->template->replace(array(\'wrapper\' => implode(\'\',$items[$year][$mon][\'items\'])),$templates[\'month_inner\']);
				$items[$year][$mon] = $ditto->template->replace(array(\'wrapper\' => $r_month_2),$r_month);
			} else {
				$items[$year][$mon] = $r_month;
			}
		}
		if ($groupByYears) {
			$year_url = $ditto->buildURL("year=".$year."&month=false&day=false&start=0",$templatesDocumentID,$id);
			$r_year =  $ditto->template->replace(array("year"=>$year,"url"=>$year_url,"count"=>$year_count),$templates[\'year\']);
			$var = $ditto->template->replace(array(\'wrapper\'=>implode(\'\',$items[$year])),$templates[\'year_inner\']);
			$output .= $ditto->template->replace(array(\'wrapper\'=>$var),$r_year);
		} else {
			$output .= implode(\'\',$items[$year]);
		}
	}

	$output = $ditto->template->replace(array(\'wrapper\'=>$output),$templates[\'tpl\']);
	$modx->setPlaceholder($id.\'reset\',$ditto->buildURL(\'year=false&month=false&day=false\',$templatesDocumentID,$id));

return $output;
	
}
}

return reflect($targetID, $showItems, $groupByYears, $resource, $dateSource, $dateFormat, $ditto, $templates,$id,$start,$yearSortDir,$monthSortDir);';
$s['UltimateParent'] = '/**
 * @name UltimateParent
 * @version 2.0 beta (requires MODx 0.9.5+)
 * @author Jason Coward <modx@opengeek.com>
 * 
 * @param &id The id of the document whose parent you want to find.
 * @param &top The top node for the search.
 * @param &topLevel The top level node for the search (root = level 1)
 * 
 * @license Public Domain, use as you like.
 * 
 * @example [[UltimateParent? &id=`45` &top=`6`]] 
 * Will find the ultimate parent of document 45 if it is a child of document 6;
 * otherwise it will return 45.
 * 
 * @example [[UltimateParent? &topLevel=`2`]]
 * Will find the ultimate parent of the current document at a depth of 2 levels
 * in the document hierarchy, with the root level being level 1.
 * 
 * This snippet travels up the document tree from a specified document and
 * returns the "ultimate" parent.  Version 2.0 was rewritten to use the new
 * getParentIds function features available only in MODx 0.9.5 or later.
 * 
 * Based on the original UltimateParent 1.x snippet by Susan Ottwell
 * <sottwell@sottwell.com>.  The topLevel parameter was introduced by staed and
 * adopted here.
 */
$top= isset ($top) && intval($top) ? $top : 0;
$id= isset ($id) && intval($id) ? intval($id) : $modx->documentIdentifier;
$topLevel= isset ($topLevel) && intval($topLevel) ? intval($topLevel) : 0;
if ($id && $id != $top) {
    $pid= $id;
    if (!$topLevel || count($modx->getParentIds($id)) >= $topLevel) {
        while ($parentIds= $modx->getParentIds($id, 1)) {
            $pid= array_pop($parentIds);
            if ($pid == $top) {
                break;
            }
            $id= $pid;
            if ($topLevel && count($modx->getParentIds($id)) < $topLevel) {
                break;
            }
        }
    }
}
return $id;';
$s['Wayfinder'] = '/*
::::::::::::::::::::::::::::::::::::::::
 Snippet name: Wayfinder
 Short Desc: builds site navigation
 Version: 2.0
 Authors: 
	Kyle Jaebker (muddydogpaws.com)
	Ryan Thrash (vertexworks.com)
 Date: February 27, 2006
::::::::::::::::::::::::::::::::::::::::
Description:
    Totally refactored from original DropMenu nav builder to make it easier to
    create custom navigation by using chunks as output templates. By using templates,
    many of the paramaters are no longer needed for flexible output including tables,
    unordered- or ordered-lists (ULs or OLs), definition lists (DLs) or in any other
    format you desire.
::::::::::::::::::::::::::::::::::::::::
Example Usage:
    [[Wayfinder? &startId=`0`]]
::::::::::::::::::::::::::::::::::::::::
*/

$wayfinder_base = $modx->config[\'base_path\']."assets/snippets/wayfinder/";

//Include a custom config file if specified
$config = (isset($config)) ? "{$wayfinder_base}configs/{$config}.config.php" : "{$wayfinder_base}configs/default.config.php";
if (file_exists($config)) {
	include_once("$config");
}

include_once("{$wayfinder_base}wayfinder.inc.php");

if (class_exists(\'Wayfinder\')) {
   $wf = new Wayfinder();
} else {
    return \'error: Wayfinder class not found\';
}

$wf->_config = array(
	\'id\' => isset($startId) ? $startId : $modx->documentIdentifier,
	\'level\' => isset($level) ? $level : 0,
	\'includeDocs\' => isset($includeDocs) ? $includeDocs : 0,
	\'excludeDocs\' => isset($excludeDocs) ? $excludeDocs : 0,
	\'ph\' => isset($ph) ? $ph : FALSE,
	\'debug\' => isset($debug) ? TRUE : FALSE,
	\'ignoreHidden\' => isset($ignoreHidden) ? $ignoreHidden : FALSE,
	\'hideSubMenus\' => isset($hideSubMenus) ? $hideSubMenus : FALSE,
	\'useWeblinkUrl\' => isset($useWeblinkUrl) ? $useWeblinkUrl : TRUE,
	\'fullLink\' => isset($fullLink) ? $fullLink : FALSE,
	\'nl\' => isset($removeNewLines) ? \'\' : "\\n",
	\'sortOrder\' => isset($sortOrder) ? strtoupper($sortOrder) : \'ASC\',
	\'sortBy\' => isset($sortBy) ? $sortBy : \'menuindex\',
	\'limit\' => isset($limit) ? $limit : 0,
	\'cssTpl\' => isset($cssTpl) ? $cssTpl : FALSE,
	\'jsTpl\' => isset($jsTpl) ? $jsTpl : FALSE,
	\'rowIdPrefix\' => isset($rowIdPrefix) ? $rowIdPrefix : FALSE,
	\'textOfLinks\' => isset($textOfLinks) ? $textOfLinks : \'menutitle\',
	\'titleOfLinks\' => isset($titleOfLinks) ? $titleOfLinks : \'pagetitle\',
	\'displayStart\' => isset($displayStart) ? $displayStart : FALSE,
);

//get user class definitions
$wf->_css = array(
	\'first\' => isset($firstClass) ? $firstClass : \'\',
	\'last\' => isset($lastClass) ? $lastClass : \'last\',
	\'here\' => isset($hereClass) ? $hereClass : \'active\',
	\'parent\' => isset($parentClass) ? $parentClass : \'\',
	\'row\' => isset($rowClass) ? $rowClass : \'\',
	\'outer\' => isset($outerClass) ? $outerClass : \'\',
	\'inner\' => isset($innerClass) ? $innerClass : \'\',
	\'level\' => isset($levelClass) ? $levelClass: \'\',
	\'self\' => isset($selfClass) ? $selfClass : \'\',
	\'weblink\' => isset($webLinkClass) ? $webLinkClass : \'\',
);

//get user templates
$wf->_templates = array(
	\'outerTpl\' => isset($outerTpl) ? $outerTpl : \'\',
	\'rowTpl\' => isset($rowTpl) ? $rowTpl : \'\',
	\'parentRowTpl\' => isset($parentRowTpl) ? $parentRowTpl : \'\',
	\'parentRowHereTpl\' => isset($parentRowHereTpl) ? $parentRowHereTpl : \'\',
	\'hereTpl\' => isset($hereTpl) ? $hereTpl : \'\',
	\'innerTpl\' => isset($innerTpl) ? $innerTpl : \'\',
	\'innerRowTpl\' => isset($innerRowTpl) ? $innerRowTpl : \'\',
	\'innerHereTpl\' => isset($innerHereTpl) ? $innerHereTpl : \'\',
	\'activeParentRowTpl\' => isset($activeParentRowTpl) ? $activeParentRowTpl : \'\',
	\'categoryFoldersTpl\' => isset($categoryFoldersTpl) ? $categoryFoldersTpl : \'\',
	\'startItemTpl\' => isset($startItemTpl) ? $startItemTpl : \'\',
);

//Process Wayfinder
$output = $wf->run();

if ($wf->_config[\'debug\']) {
	$output .= $wf->renderDebugOutput();
}

//Ouput Results
if ($wf->_config[\'ph\']) {
    $modx->setPlaceholder($wf->_config[\'ph\'],$output);
} else {
    return $output;
}';
$s['WebChangePwd'] = '# WebChangePwd 1.0
# Created By Raymond Irving April, 2005
#::::::::::::::::::::::::::::::::::::::::
# Usage: 	
#	Allows a web user to change his/her password from the website
#
# Params:	
#
#	&tpl			- (Optional)
#		Chunk name or document id to use as a template
#				  
#	Note: Templats design:
#			section 1: change pwd template
#			section 2: notification template 
#
# Examples:
#
#	[[WebChangePwd? &tpl=`ChangePwd`]] 

# Set Snippet Paths 
$snipPath  = (($modx->insideManager())? "../":"");
$snipPath .= "assets/snippets/";

# check if inside manager
if ($m = $modx->insideManager()) {
	return \'\'; # don\'t go any further when inside manager
}


# Snippet customize settings
$tpl		= isset($tpl)? $tpl:"";

# System settings
$isPostBack		= count($_POST) && isset($_POST[\'cmdwebchngpwd\']);

# Start processing
include_once $snipPath."weblogin/weblogin.common.inc.php";
include_once $snipPath."weblogin/webchangepwd.inc.php";

# Return
return $output;



';
$s['WebChangePwdProps'] = '&tpl=Template;string;  ';
$s['WebLogin'] = '# WebLogin 1.0
# Created By Raymond Irving 2004
#::::::::::::::::::::::::::::::::::::::::
# Usage: 	
#	Allows a web user to login to the website
#
# Params:	
#	&loginhomeid 	- (Optional)
#		redirects the user to first authorized page in the list.
#		If no id was specified then the login home page id or 
#		the current document id will be used
#
#	&logouthomeid 	- (Optional)
#		document id to load when user logs out	
#
#	&pwdreqid 	- (Optional)
#		document id to load after the user has submited
#		a request for a new password
#
#	&pwdactid 	- (Optional)
#		document id to load when the after the user has activated
#		their new password
#
#	&logintext		- (Optional) 
#		Text to be displayed inside login button (for built-in form)
#
#	&logouttext 	- (Optional)
#		Text to be displayed inside logout link (for built-in form)
#	
#	&tpl			- (Optional)
#		Chunk name or document id to as a template
#				  
#	Note: Templats design:
#			section 1: login template
#			section 2: logout template 
#			section 3: password reminder template 
#
#			See weblogin.tpl for more information
#
# Examples:
#
#	[[WebLogin? &loginhomeid=`8` &logouthomeid=`1`]] 
#
#	[[WebLogin? &loginhomeid=`8,18,7,5` &tpl=`Login`]] 

# Set Snippet Paths 
$snipPath = $modx->config[\'base_path\'] . "assets/snippets/";

# check if inside manager
if ($m = $modx->insideManager()) {
	return \'\'; # don\'t go any further when inside manager
}

# deprecated params - only for backward compatibility
if(isset($loginid)) $loginhomeid=$loginid;
if(isset($logoutid)) $logouthomeid = $logoutid;
if(isset($template)) $tpl = $template;

# Snippet customize settings
$liHomeId	= isset($loginhomeid)? explode(",",$loginhomeid):array($modx->config[\'login_home\'],$modx->documentIdentifier);
$loHomeId	= isset($logouthomeid)? $logouthomeid:$modx->documentIdentifier;
$pwdReqId	= isset($pwdreqid)? $pwdreqid:0;
$pwdActId	= isset($pwdactid)? $pwdactid:0;
$loginText	= isset($logintext)? $logintext:\'Login\';
$logoutText	= isset($logouttext)? $logouttext:\'Logout\';
$tpl		= isset($tpl)? $tpl:"";

# System settings
$webLoginMode = isset($_REQUEST[\'webloginmode\'])? $_REQUEST[\'webloginmode\']: \'\';
$isLogOut		= $webLoginMode==\'lo\' ? 1:0;
$isPWDActivate	= $webLoginMode==\'actp\' ? 1:0;
$isPostBack		= count($_POST) && (isset($_POST[\'cmdweblogin\']) || isset($_POST[\'cmdweblogin_x\']));
$txtPwdRem 		= isset($_REQUEST[\'txtpwdrem\'])? $_REQUEST[\'txtpwdrem\']: 0;
$isPWDReminder	= $isPostBack && $txtPwdRem==\'1\' ? 1:0;

$site_id = isset($site_id)? $site_id: \'\';
$cookieKey = substr(md5($site_id."Web-User"),0,15);

# Start processing
include_once $snipPath."weblogin/weblogin.common.inc.php";
include_once ($modx->config[\'base_path\'] . "manager/includes/crypt.class.inc.php");

if ($isPWDActivate || $isPWDReminder || $isLogOut || $isPostBack) {
	# include the logger class
	include_once $modx->config[\'base_path\'] . "manager/includes/log.class.inc.php";
	include_once $snipPath."weblogin/weblogin.processor.inc.php";
}

include_once $snipPath."weblogin/weblogin.inc.php";

# Return
return $output;
';
$s['WebLoginProps'] = '&loginhomeid=Login Home Id;string; &logouthomeid=Logout Home Id;string; &logintext=Login Button Text;string; &logouttext=Logout Button Text;string; &tpl=Template;string;  ';
$s['WebSignup'] = '# WebSignup 1.1
# Created By Raymond Irving April, 2005
#::::::::::::::::::::::::::::::::::::::::
# Usage:     
#    Allows a web user to signup for a new web account from the website
#    This snippet provides a basic set of form fields for the signup form
#    You can customize this snippet to create your own signup form
#
# Params:    
#
#    &tpl        - (Optional) Chunk name or document id to use as a template
#    &groups     - Web users groups to be assigned to users
#    &useCaptcha - (Optional) Determine to use (1) or not to use (0) captcha
#                  on signup form - if not defined, will default to system
#                  setting. GD is required for this feature. If GD is not 
#                  available, useCaptcha will automatically be set to false;
#                  
#    Note: Templats design:
#        section 1: signup template
#        section 2: notification template 
#
# Examples:
#
#    [[WebSignup? &tpl=`SignupForm` &groups=`NewsReaders,WebUsers`]] 

# Set Snippet Paths 
$snipPath = $modx->config[\'base_path\'] . "assets/snippets/";

# check if inside manager
if ($m = $modx->insideManager()) {
    return \'\'; # don\'t go any further when inside manager
}


# Snippet customize settings
$tpl = isset($tpl)? $tpl:"";
$useCaptcha = isset($useCaptcha)? $useCaptcha : $modx->config[\'use_captcha\'] ;
// Override captcha if no GD
if ($useCaptcha && !gd_info()) $useCaptcha = 0;

# setup web groups
$groups = isset($groups) ? explode(\',\',$groups):array();
for($i=0;$i<count($groups);$i++) $groups[$i] = trim($groups[$i]);

# System settings
$isPostBack        = count($_POST) && isset($_POST[\'cmdwebsignup\']);

$output = \'\';

# Start processing
include_once $snipPath."weblogin/weblogin.common.inc.php";
include_once $snipPath."weblogin/websignup.inc.php";

# Return
return $output;';
$s['WebSignupProps'] = '&tpl=Template;string;  ';
$p = &$this->pluginCache;
$p['Bottom Button Bar'] = '/* BottomButtonBar v1.0 (by Mitch)
 *
 * I just got tired of scrolling up after editing a document/snippet/etc... 
 * to save it. The GoUp plugin improved it a bit, but I was not completely 
 * satisfied. So I wrote this plugin that will add the complete buttonbar 
 * at the bottom of the edit screen. Also there is a GoUp link in the bottombar.
 *
 * To use the plugin you have to enable the following events:
 *
 *   OnChunkFormRender
 *   OnDocFormRender
 *   OnModFormRender
 *   OnPluginFormRender
 *   OnSnipFormRender
 *   OnTVFormRender
 *   OnTempFormRender
 *   OnUserFormRender
 *   OnWUsrFormRender
 *
 * Hope you like it as much as I do.
 * 
 */

// I know the code looks messy, but that is mainly because of the copy/pasting.

global $_lang;

//Get Manager Theme - added by garryn
$manager_theme = $modx->config[\'manager_theme\'] ? $modx->config[\'manager_theme\'] : \'\';

// Get a reference to the event
$e = & $modx->Event;

// For every form basicially the code is just copied from the appropriate
// mutate_XXXXXX.dynamic.action.php file. Then the CSS id\'s are updated so they
// are unique. I just added __ after it.

switch ($e->name) {

//-------------------------------------------------------------------
   case "OnDocFormRender":

// From mutate_content.dynamic.action.php
ob_start();
?>
<div class="subTitle" style="width:100%">
	<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5" /><br />"<a href=\'javascript:scroll(0,0);\'><?php echo $_lang[\'scroll_up\']; ?></a>"</span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<tr>
			<td id="Button1__"><a href="#" onclick="documentDirty=false; document.mutate.save.click();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang[\'save\']; ?></a></td>
			<td id="Button2__"><a href="#" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle" /> <?php echo $_lang[\'delete\']; ?></span></a></td>
				<?php if($_REQUEST[\'a\']==\'4\' || $_REQUEST[\'a\']==72) { ?><script>document.getElementById("Button2__").className=\'disabled\';</script><?php } ?>
			<td id="Button5__"><a href="<?php echo $id==0 ? "index.php?a=2" : "index.php?a=3&id=$id"; ?>"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle" /> <?php echo $_lang[\'cancel\']; ?></a></td>
		</tr>
	</table>
</div>
<?php
$output = ob_get_clean();
    break;


//-------------------------------------------------------------------
   case "OnSnipFormRender":

// From mutate_snippet.dynamic.action.php
ob_start();
?>
<div class="subTitle" style="width:100%">
	<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5" /><br />"<a href=\'javascript:scroll(0,0);\'><?php echo $_lang[\'scroll_up\']; ?></a>"</span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<td id="Button1__"><a href="#" onclick="documentDirty=false; document.mutate.save.click(); saveWait(\'mutate\');"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang[\'save\']; ?></a></td>
<?php if($_GET[\'a\']==\'22\') { ?>
		<td id="Button2__"><a href="#" onclick="duplicaterecord();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/copy.gif" align="absmiddle" /> <?php echo $_lang["duplicate"]; ?></a></td>
		<td id="Button3__"><a href="#" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle" /> <?php echo $_lang[\'delete\']; ?></a></td>
<?php } ?>
		<td id="Button4__"><a href="index.php?a=76"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle" /> <?php echo $_lang[\'cancel\']; ?></a></td>
	</table>
</div>
<?php
$output = ob_get_clean();
    break;

//-------------------------------------------------------------------
   case "OnChunkFormRender":

// From mutate_htmlsnippet.dynamic.action.php
ob_start();
?>
<div class="subTitle" style="width:100%">
	<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5" /><br />"<a href=\'javascript:scroll(0,0);\'><?php echo $_lang[\'scroll_up\']; ?></a>"</span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<td id="Button1__"><a href="#" onclick="documentDirty=false; document.mutate.save.click(); saveWait(\'mutate\');"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang[\'save\']; ?></a></td>
<?php if($_REQUEST[\'a\']==\'78\') { ?>
		<td id="Button2__"><a href="#" onclick="duplicaterecord();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/copy.gif" align="absmiddle" /> <?php echo $_lang["duplicate"]; ?></a></td>
		<td id="Button3__"><a href="#" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle" /> <?php echo $_lang[\'delete\']; ?></a></td>
<?php } ?>
		<td id="Button4__"><a href="index.php?a=76"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle" /> <?php echo $_lang[\'cancel\']; ?></a></td>
	</table>
</div>
<?php
$output = ob_get_clean();
    break;

//-------------------------------------------------------------------
   case "OnModFormRender":

// From mutate_module.dynamic.action.php
ob_start();
?>
<div class="subTitle" style="width:100%">
	<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5"><br />"<a href=\'javascript:scroll(0,0);\'><?php echo $_lang[\'scroll_up\']; ?></a>"</span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<tr>
		<td id="Button1__"><a href="#" onclick="documentDirty=false; document.mutate.save.click(); saveWait(\'mutate\');"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle"> <?php echo $_lang[\'save\']; ?></a></td>
<?php if($_GET[\'a\']==\'108\') { ?>
		<td id="Button2__"><a href="#" onclick="duplicaterecord();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/copy.gif" align="absmiddle"> <?php echo $_lang["duplicate"]; ?></a></td>
		<td id="Button3__"><a href="#" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle"> <?php echo $_lang[\'delete\']; ?></a></td>
<?php } ?>
		<td id="Button4__"><a href="#" onclick="documentDirty=false;document.location.href=\'index.php?a=106\';"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang[\'cancel\']; ?></a></td>
		</tr>
	</table>
</div>
<?php
$output = ob_get_clean();
    break;

//-------------------------------------------------------------------
   case "OnPluginFormRender":

// From mutate_plugin.dynamic.action.php
ob_start();
?>
<div class="subTitle" style="width:100%">
	<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5" /><br />"<a href=\'javascript:scroll(0,0);\'><?php echo $_lang[\'scroll_up\']; ?></a>"</span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<td id="Button1__"><a href="#" onclick="documentDirty=false; document.mutate.save.click(); saveWait(\'mutate\');"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang[\'save\']; ?></a></td>
<?php if($_GET[\'a\']==\'102\') { ?>
		<td id="Button2__"><a href="#" onclick="duplicaterecord();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/copy.gif" align="absmiddle" /> <?php echo $_lang["duplicate"]; ?></a></td>
		<td id="Button3__"><a href="#" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle" /> <?php echo $_lang[\'delete\']; ?></a></td>
<?php } ?>
		<td id="Button4__"><a href="index.php?a=76"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang[\'cancel\']; ?></a></td>
	</table>
</div>
<?php
$output = ob_get_clean();
    break;

//-------------------------------------------------------------------
   case "OnTVFormRender":

// From mutate_tmplvars.dynamic.action.php
ob_start();
?>
<div class="subTitle" style="width:100%">
	<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5" /><br />"<a href=\'javascript:scroll(0,0);\'><?php echo $_lang[\'scroll_up\']; ?></a>"</span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<td id="Button1__"><a href="#" onclick="documentDirty=false; document.mutate.save.click(); saveWait(\'mutate\');"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang[\'save\']; ?></a></td>
<?php if($_GET[\'a\']==\'301\') { ?>
		<td id="Button2__"><a href="#" onclick="duplicaterecord();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/copy.gif" align="absmiddle" /> <?php echo $_lang["duplicate"]; ?></a></td>
		<td id="Button3__"><a href="#" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle" /> <?php echo $_lang[\'delete\']; ?></a></td>
<?php } ?>
		<td id="Button4__"><a href="index.php?a=76"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle" /> <?php echo $_lang[\'cancel\']; ?></a></td>
	</table>
</div>
<?php
$output = ob_get_clean();
    break;

//-------------------------------------------------------------------
   case "OnTempFormRender":

// From mutate_templates.dynamic.action.php
ob_start();
?>
<div class="subTitle" style="width:100%">
	<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5" /><br />"<a href=\'javascript:scroll(0,0);\'><?php echo $_lang[\'scroll_up\']; ?></a>"</span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<td id="Button1__"><a href="#" onclick="documentDirty=false; document.mutate.save.click(); saveWait(\'mutate\');"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang[\'save\']; ?></a></td>
<?php if($_REQUEST[\'a\']==\'16\') { ?>
		<td id="Button2__"><a href="#" onclick="duplicaterecord();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/copy.gif" align="absmiddle" /> <?php echo $_lang["duplicate"]; ?></a></td>
		<td id="Button3__"><a href="#" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle" /> <?php echo $_lang[\'delete\']; ?></a></td>
<?php } ?>
		<td id="Button4__"><a href="index.php?a=76"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle" /> <?php echo $_lang[\'cancel\']; ?></a></td>
	</table>
</div>
<?php
$output = ob_get_clean();
    break;

//-------------------------------------------------------------------
   case "OnWUsrFormRender":

// From mutate_web_user.dynamic.action.php
ob_start();
?>
<div class="subTitle" style="width:100%">
	<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5" /><br />"<a href=\'javascript:scroll(0,0);\'><?php echo $_lang[\'scroll_up\']; ?></a>"</span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<tr>
			<td id="Button1__"><a href="#" onclick="documentDirty=false; document.userform.save.click();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang[\'save\']; ?></a></td>
			<td id="Button2__"><a href="#" onclick="deleteuser();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle" /> <?php echo $_lang[\'delete\']; ?></a></td>
				<?php if($_GET[\'a\']!=\'88\') { ?>
					<script>document.getElementById("Button2__").className=\'disabled\';</script>
				<?php } ?>
			<td id="Button3__"><a href="index.php?a=99"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle" /> <?php echo $_lang[\'cancel\']; ?></a></td>
		</tr>
	</table>
</div>
<?php
$output = ob_get_clean();
    break;

//-------------------------------------------------------------------
   case "OnUserFormRender":

// From mutate_user.dynamic.action.php
ob_start();
?>
<div class="subTitle" style="width:100%">
	<span class="right"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/_tx_.gif" width="1" height="5" /><br />"<a href=\'javascript:scroll(0,0);\'><?php echo $_lang[\'scroll_up\']; ?></a>"</span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<tr>
			<td id="Button1__"><a href="#" onclick="documentDirty=false; document.userform.save.click();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle" /> <?php echo $_lang[\'save\']; ?></a></td>
			<td id="Button2__"><a href="#" onclick="deleteuser();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" align="absmiddle" /> <?php echo $_lang[\'delete\']; ?></a></td>
				<?php if($_GET[\'a\']!=\'12\') { ?>	
					<script>document.getElementById("Button2__").className=\'disabled\';</script>
				<?php } ?>
			<td id="Button3__"><a href="index.php?a=75"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle" /> <?php echo $_lang[\'cancel\']; ?></a></td>
		</tr>
	</table>
</div>
<?php
$output = ob_get_clean();
    break;

}

// Add the new bar to the output
$e->output($output);
';
$p['Forgot Manager Login'] = '
if(!class_exists(\'ForgotManagerPassword\')) {
class ForgotManagerPassword{

 function ForgotManagerPassword(){
 
  $this->errors = array();
 
  $this->checkLang();

 }

 function getLink() {
 
  global $_lang;
 
$link = <<<EOD
<a id="ForgotManagerPassword-show_form" href="index.php?action=show_form">{$_lang[\'forgot_your_password\']}</a>
EOD;

  return $link;

 }

 function getForm() {

  global $_lang;

$form = <<<EOD
<label id="FMP-email_label" for="FMP_email">{$_lang[\'account_email\']}:</label>
<input id="FMP-email" type="text" />
<button id="FMP-email_button" type="button" onclick="window.location = \'index.php?action=send_email&email=\'+document.getElementById(\'FMP-email\').value;">{$_lang[\'send\']}</button>
EOD;

  return $form;

 }

 // Get user info including a hash unique to this user, password, and day
 function getUser($user_id=0, $username=\'\', $email=\'\', $hash=\'\') {

  global $modx, $_lang;
 
  $user_id = $modx->db->escape($user_id);
  $username = $modx->db->escape($username);
  $email = $modx->db->escape($email);
  $emaail = $modx->db->escape($hash);
  
  $pre = $modx->db->config[\'table_prefix\'];
  $site_id = $modx->config[\'site_id\'];
  $today = date(\'Yz\'); // Year and day of the year
  $wheres = array();
  $where = \'\';
  $user = array(\'id\'=>0, \'username\'=>\'\', \'email\'=>\'\', \'hash\'=>\'\');
 
  if(!empty($user_id)) { $wheres[] = "id = \'{$user_id}\'"; }
  if(!empty($username)) { $wheres[] = "username = \'{$username}\'"; }
  if(!empty($email)) { $wheres[] = "email = \'{$email}\'"; }
  if(!empty($hash)) { $wheres[] = "MD5(CONCAT(usr.username,usr.password,\'{$site_id}\',\'{$today}\')) = \'{$hash}\'"; }
 
  if($wheres) {
  
   $where = \' WHERE \'.implode(\' AND \',$wheres);
   $sql = "SELECT usr.id, usr.username, attr.email, MD5(CONCAT(usr.username,usr.password,\'{$site_id}\',\'{$today}\')) AS hash
           FROM `{$pre}manager_users` usr
           INNER JOIN `{$pre}user_attributes` attr ON usr.id = attr.internalKey
           {$where};";
   
   if($result = $modx->db->query($sql)){
    if($modx->db->getRecordCount($result)==1) {
     $user = $modx->db->getRow($result);
    }
   }
   
  }
  
  if(!$user[\'id\']) { $this->errors[] = $_lang[\'could_not_find_user\']; }

  return $user;

 }

 // Send an email with a link to login
 function sendEmail($to) {

  global $modx, $_lang;

  $subject = $_lang[\'password_change_request\'];
  $headers  = "MIME-Version: 1.0\\n".
              "Content-type: text/html; charset=iso-8859-1\\n".
              "From: MODx\\n".
              "Reply-To: no-reply@{$_SERVER[\'HTTP_HOST\']}\\n".
              "X-Mailer: PHP/".phpversion();
  
  $user = $this->getUser(0, \'\', $to);

  if($user[\'username\']) {

$body = <<<EOD
<p>{$_lang[\'forgot_password_email_intro\']} <a href="{$modx->config[\'site_url\']}manager/processors/login.processor.php?username={$user[\'username\']}&hash={$user[\'hash\']}">{$_lang[\'forgot_password_email_link\']}</a></p>

<p>{$_lang[\'forgot_password_email_instructions\']}</p>

<p><small>{$_lang[\'forgot_password_email_fine_print\']}</small></p>
EOD;

   $mail = mail($to, $subject, $body, $headers);
 
   if(!$mail) { $this->errors[] = $_lang[\'error_sending_email\']; }
 
   return $mail;
   
  }

 }
 
 function unblockUser($user_id) {
  
  global $modx, $_lang;
  $pre = $modx->db->config[\'table_prefix\'];
  
  $modx->db->update(array(\'blocked\'=>\'\', \'blockeduntil\'=>\'\'), "`{$pre}user_attributes`", "internalKey = \'{$user_id}\'");
  
  if(!$modx->db->getAffectedRows()) { $this->errors[] = $_lang[\'user_doesnt_exist\']; return; }
  
  return true;
  
 }
 
 function checkLang() {
  
  global $_lang;
  $eng = array();
  
  $eng[\'forgot_your_password\'] = \'Forgot your password?\';
  $eng[\'account_email\'] = \'Account email\';
  $eng[\'send\'] = \'Send\';
  $eng[\'password_change_request\'] = \'Password change request\';
  $eng[\'forgot_password_email_intro\'] = \'A request has been made to change the password on your account.\';
  $eng[\'forgot_password_email_link\'] = \'Click here to complete the process.\';
  $eng[\'forgot_password_email_instructions\'] = \'From there you will be able to change your password from the My Account menu.\';
  $eng[\'forgot_password_email_fine_print\'] = \'* The URL above will expire once you change your password or after today.\';
  $eng[\'error_sending_email\'] = \'Error sending email\';
  $eng[\'could_not_find_user\'] = \'Could not find user\';
  $eng[\'user_doesnt_exist\'] = \'User does not exist\';
  $eng[\'email_sent\'] = \'Email sent\';
  
  foreach($eng as $key=>$value) {
   if(empty($_lang[$key])) { $_lang[$key] = $value; }
  }
  
 }
 
 function getErrorOutput() {
 
  $outptut = \'\';
 
  if($this->errors) {
   $output = \'<span class="error">\'.implode(\'</span><span class="errors">\', $this->errors).\'</span>\';
  }
 
  return $output;
 
 }
 
}
}

global $_lang;

$output = \'\';
$event_name = $modx->Event->name;
$action = (empty($_GET[\'action\']) ? \'\' : $_GET[\'action\']);
$username = (empty($_GET[\'username\']) ? \'\' : $_GET[\'username\']);
$to = (empty($_GET[\'email\']) ? \'\' : $_GET[\'email\']);
$hash = (empty($_GET[\'hash\']) ? \'\' : $_GET[\'hash\']);
$forgot = new ForgotManagerPassword();

if($event_name == \'OnManagerLoginFormRender\') {

 switch($action) {

  case \'show_form\':
   $output = $forgot->getForm();
   break;
  
  case \'send_email\':
   if($forgot->sendEmail($to)) { $output = $_lang[\'email_sent\']; }
   break;
  
  default:
   $output = $forgot->getLink();

 }
 
 if($forgot->errors) { $output = $forgot->getErrorOutput() . $forgot->getLink(); }
 
}

if($event_name == \'OnBeforeManagerLogin\') {
 $user = $forgot->getUser(0, $username, \'\', $hash);
 if($user[\'id\'] && !$forgot->errors) {
  $forgot->unblockUser($user[\'id\']);
 }
}

if($event_name == \'OnManagerAuthentication\' && $hash) {
 $user = $forgot->getUser(0, \'\', \'\', $hash);
 $output = ($user[\'id\'] > 0 && !$forgot->errors);
}

$modx->Event->output($output);
';
$p['Inherit Parent Template'] = '/*
 * Inherit Template from Parent
 * Written By Raymond Irving - 12 Oct 2006
 *
 * Simply results in new documents inherriting the template 
 * of their parent folder upon creating a new document
 *
 * Configuration:
 * check the OnDocFormPrerender event
 *
 * Version 1.0
 *
 */

global $content;
$e = &$modx->Event;

switch($e->name) {
  case \'OnDocFormPrerender\':
    if(($_REQUEST[\'pid\'] > 0) && ($id == 0)) {
      if($parent = $modx->getPageInfo($_REQUEST[\'pid\'],0,\'template\')) {
        $content[\'template\'] = $parent[\'template\'];
      }
    }
    break;

  default:
    return;
    break;
}
';
$p['QuickEdit'] = '/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  Updated: 11/27/2005 - Added support for show Manager & Help links option
 *  Updated: 12/05/2005 - Added support for editable fields as a module configuration option
 *  For: MODx cms (modxcms.com)
 *  Name: QuickEdit
 *  Description: Renders QuickEdit links in the frontend
 *  Shared parameters from: QuickEdit module
 *  Events: OnParseDocument, OnWebPagePrerender
 */

/*
                             License

QuickEdit - A MODx module which allows the editing of content via
            the frontent of the site
Copyright (C) 2005  Adam Crownoble

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// Don\'t do anything if we aren\'t logged in
if(isset($_SESSION[\'mgrValidated\']) && $_SESSION[\'mgrValidated\']) {

 // Set configuration variables if not already set
 if(!isset($mod_path)) { $mod_path = $modx->config[\'base_path\'].\'assets/modules/quick_edit\'; }
 if(!isset($show_manager_link)) { $show_manager_link = 1; }
 if(!isset($show_help_link)) { $show_help_link = 1; }
 if(!isset($editable)) { $editable = \'pagetitle,longtitle,description,content,alias,introtext,menutitle,published,hidemenu,menuindex,searchable,cacheable\'; }

 // If we can\'t find the module files...
 if(!file_exists($mod_path)) {

  // Only log the error if we haven\'t already logged it...
  if(!isset($GLOBALS[\'quick_edit_not_found_sent\'])) {

   // Set a global variable so that we can only log this once
   $GLOBALS[\'quick_edit_not_found_sent\'] = true;

   // Log an error
   $error_message = \'<strong>QuickEdit module not found!</strong></p><p>Edit the QuickEdit module, click the Configuration tab and change the Module Path to point to the module.</p>\';
   $modx->logEvent(0, 3, $error_message, \'QuickEditor\');

  }

 } else {

  // Set globals from QE Module\'s shared paramaters so we can get them from the frontend
  $GLOBALS[\'qe_show_manager_link\'] = $show_manager_link;
  $GLOBALS[\'qe_show_help_link\'] = $show_help_link;
  $GLOBALS[\'qe_editable\'] = $editable;

  // Set the mod_path as a global variable
  $GLOBALS[\'quick_edit_path\'] = $mod_path;
  if (!class_exists(\'Output\')) include_once($mod_path.\'/output.class.inc.php\');

  $outputObject = new Output;

  switch($modx->Event->name) {

   case \'OnParseDocument\' :

    $outputObject->output = $modx->documentOutput;

    // Merge QuickEdit comment into the output
    $outputObject->mergeTags();

    break;

   case \'OnWebPagePrerender\' :

    $outputObject->output = &$modx->documentOutput;

    include_once($mod_path.\'/module.class.inc.php\');
    $module = new Module;
    $module->getIdFromDependentPluginName($modx->Event->activePlugin);

    // Replace QuickEdit comments with QuickEdit links
    $outputObject->mergeLinks($module->id);

    break;

  }

  // Set the event output
  $modx->documentOutput = $outputObject->output;

  // Logout ?
  $qe_logout= (isset($_GET[\'QuickEdit_logout\'])? $_GET[\'QuickEdit_logout\']: \'\');
  if($qe_logout == \'logout\') {
   $_SESSION = array();
  }

 }

}
';
$p['QuickEditProps'] = ' &mod_path=Module Path (from site root);string;assets/modules/quick_edit &show_manager_link=Show Manager Link;int;1 &show_help_link=Show Help Link;int;1 &editable=Editable Fields;string;pagetitle,longtitle,description,content,alias,introtext,menutitle,published,hidemenu,menuindex,searchable,cacheable,template ';
$p['TinyMCE'] = '/*
 * TinyMCE RichText Editor Plugin 
 * Written By Jeff Whitfield - September 9, 2005
 * Modified On - September 24, 2008
 *
 * Version 3.2.0.1
 *
 * Events: OnRichTextEditorInit, OnRichTextEditorRegister, OnInterfaceSettingsRender
 *
 */

// Set the name of the plugin folder
$pluginfolder = "tinymce3201";

include_once $modx->config[\'base_path\'].\'assets/plugins/\'.$pluginfolder.\'/tinymce.lang.php\';
include_once $modx->config[\'base_path\'].\'assets/plugins/\'.$pluginfolder.\'/tinymce.functions.php\';

// Set path and base setting variables
if(!isset($tinyPath)) { 
	global $tinyPath, $tinyURL;
	$tinyPath = $modx->config[\'base_path\'].\'assets/plugins/\'.$pluginfolder; 
	$tinyURL = $modx->config[\'base_url\'].\'assets/plugins/\'.$pluginfolder; 
}
$base_url = $modx->config[\'base_url\'];
$site_url = $modx->config[\'site_url\'];
$displayStyle = ( ($_SESSION[\'browser\']==\'mz\') || ($_SESSION[\'browser\']==\'op\') ) ? "table-row" : "block" ;

// Handle event
$e = &$modx->Event; 
switch ($e->name) { 
	case "OnRichTextEditorRegister": // register only for backend
		$e->output("TinyMCE");
		break;

	case "OnRichTextEditorInit": 
		if($editor=="TinyMCE") {
			$elementList = implode(",", $elements);
			if(isset($forfrontend)||$modx->isFrontend()){
				$frontend = \'true\';
				$frontend_language = isset($modx->config[\'fe_editor_lang\']) ? $modx->config[\'fe_editor_lang\']:"";
				$tinymce_language = getTinyMCELang($frontend_language);
				$webuser = (isset($modx->config[\'rb_webuser\']) ? $modx->config[\'rb_webuser\'] : null);
				$html = getTinyMCEScript($elementList,$webtheme,$width,$height,$tinymce_language,$frontend,$base_url, $site_url, $webPlugins, $webButtons1, $webButtons2, $webButtons3, $webButtons4, $disabledButtons, $tinyFormats, $entity_encoding, $entities, $tinyPathOptions, $tinyCleanup, $tinyResizing, $modx->config[\'editor_css_path\'], $modx->config[\'tinymce_css_selectors\'], $modx->config[\'use_browser\'], $webAlign, null, null, $tinyLinkList, $customparams, $tinyURL, $webuser);
			} else {
				$frontend = \'false\';
				$manager_language = $modx->config[\'manager_language\'];
				$tinymce_language = getTinyMCELang($manager_language);
				$html = getTinyMCEScript($elementList, $modx->config[\'tinymce_editor_theme\'], $width=\'100%\', $height=\'400px\', $tinymce_language, $frontend, $modx->config[\'base_url\'], $modx->config[\'site_url\'], $modx->config[\'tinymce_custom_plugins\'], $modx->config[\'tinymce_custom_buttons1\'], $modx->config[\'tinymce_custom_buttons2\'], $modx->config[\'tinymce_custom_buttons3\'], $modx->config[\'tinymce_custom_buttons4\'], $disabledButtons, $tinyFormats, $entity_encoding, $entities, $tinyPathOptions, $tinyCleanup, $tinyResizing, $modx->config[\'editor_css_path\'], $modx->config[\'tinymce_css_selectors\'], $modx->config[\'use_browser\'], $modx->config[\'manager_direction\'], $advimage_styles, $advlink_styles, $tinyLinkList, $customparams, $tinyURL, null);
			}
			$e->output($html);
		}		
		break;

	case "OnInterfaceSettingsRender":
		global $usersettings,$settings;
		$action = $modx->manager->action;
		switch ($action) {
    		case 11:
        		$tinysettings = "";
        		break;
    		case 12:
        		$tinysettings = $usersettings;
        		break;
    		default:
        		$tinysettings = $settings;
        		break;
    	}
		$tinymce_editor_theme = $tinysettings[\'tinymce_editor_theme\'];
		$tinymce_css_selectors = $tinysettings[\'tinymce_css_selectors\'];
		$tinymce_custom_plugins = $tinysettings[\'tinymce_custom_plugins\'];
		$tinymce_custom_buttons1 = $tinysettings[\'tinymce_custom_buttons1\'];
		$tinymce_custom_buttons2 = $tinysettings[\'tinymce_custom_buttons2\'];
		$tinymce_custom_buttons3 = $tinysettings[\'tinymce_custom_buttons3\'];
		$tinymce_custom_buttons4 = $tinysettings[\'tinymce_custom_buttons4\'];
		$manager_language = $modx->config[\'manager_language\'];
		$html = getTinyMCESettings($_lang, $tinyPath, $modx->config[\'manager_language\'], $modx->config[\'use_editor\'], $tinymce_editor_theme, $tinymce_css_selectors, $tinymce_custom_plugins, $tinymce_custom_buttons1, $tinymce_custom_buttons2, $tinymce_custom_buttons3, $tinymce_custom_buttons4, $displayStyle, $action);
		$e->output($html);
		break;

   default :    
      return; // stop here - this is very important. 
      break; 
}
';
$p['TinyMCEProps'] = '&customparams=Custom Parameters;textarea; &tinyFormats=Block Formats;text;p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre,address &entity_encoding=Entity Encoding;list;named,numeric,raw;named &entities=Entities;text; &tinyPathOptions=Path Options;list;rootrelative,docrelative,fullpathurl;docrelative &tinyCleanup=Cleanup;list;enabled,disabled;enabled &tinyResizing=Advanced Resizing;list;true,false;false &advimage_styles=Advanced Image Styles;text; &advlink_styles=Advanced Link Styles;text; &disabledButtons=Disabled Buttons;text; &tinyLinkList=Link List;list;enabled,disabled;enabled &webtheme=Web Theme;list;simple,advanced,editor,custom;simple &webPlugins=Web Plugins;text;style,advimage,advlink,searchreplace,print,contextmenu,paste,fullscreen,nonbreaking,xhtmlxtras,visualchars,media &webButtons1=Web Buttons 1;text;undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,link,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help &webButtons2=Web Buttons 2;text;bold,italic,underline,strikethrough,sub,sup,separator,separator,blockquote,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops &webButtons3=Web Buttons 3;text; &webButtons4=Web Buttons 4;text; &webAlign=Web Toolbar Alignment;list;ltr,rtl;ltr ';
$p['Search Highlighting'] = '/*
  ------------------------------------------------------------------------
  Plugin: Search_Highlight v1.3
  ------------------------------------------------------------------------
  Changes:
  18/07/08 - advSearch parameter and pcre modifier added
  10/02/08 - Strip_tags added to avoid sql injection and XSS. Use of $_REQUEST 
  01/03/07 - Added fies/updates from forum from users mikkelwe/identity
  (better highlight replacement, additional div around term/removal message)
  ------------------------------------------------------------------------
  Description: When a user clicks on the link from the AjaxSearch results
    the target page will have the terms highlighted.
  ------------------------------------------------------------------------
  Created By:  Susan Ottwell (sottwell@sottwell.com)
               Kyle Jaebker (kjaebker@muddydogpaws.com)
  ------------------------------------------------------------------------
  Based off the the code by Susan Ottwell (www.sottwell.com)
    http://modxcms.com/forums/index.php/topic,1237.0.html
  ------------------------------------------------------------------------
  CSS:
    The classes used for the highlighting are the same as the AjaxSearch
  ------------------------------------------------------------------------
  Notes:
    To add a link to remove the highlighting and to show the searchterms
    put the following on your page where you would like this to appear:
    
      <!--search_terms-->
    
    Example output for this:
    
      Search Terms: the, template
      Remove Highlighting
      
    Set the following variables to change the text:
    
      $termText - the text before the search terms
      $removeText - the text for the remove link
  ------------------------------------------------------------------------
*/

if (isset($_REQUEST[\'searched\']) && isset($_REQUEST[\'highlight\'])) {

  // Set these to customize the text for the highlighting key
  // --------------------------------------------------------
     $termText = \'<div class="searchTerms">Search Terms: \';
     $removeText = \'Remove Highlighting\';
  // --------------------------------------------------------

  $highlightText = $termText;

  $searched = strip_tags(urldecode($_REQUEST[\'searched\']));
  $highlight = strip_tags(urldecode($_REQUEST[\'highlight\']));

  if (isset($_REQUEST[\'advsearch\'])) $advsearch = strip_tags(urldecode($_REQUEST[\'advsearch\']));
  else $advsearch = \'oneword\'; 

  if ($advsearch != \'nowords\') {
  
      $output = $modx->documentOutput; // get the parsed document
   
      $body = explode("<body>", $output); // break out the head
    
      $searchArray = array();      
      if ($advsearch == \'exactphrase\') $searchArray[0] = $searched;
      else $searchArray = explode(\' \', $searched);
    
      $highlightClass = explode(\' \',$highlight); // break out the highlight classes
    
      $i = 0; // for individual class names
      $pcreModifier = ($database_connection_charset == \'utf8\') ? \'iu\' : \'i\';
       
      foreach($searchArray as $word) {
        $i++;
        $class = $highlightClass[0].\' \'.$highlightClass[$i];
    
        $highlightText .= ($i > 1) ? \', \' : \'\';
        $highlightText .= \'<span class="\'.$class.\'">\'.$word.\'</span>\';
    
        $pattern = \'/\' . preg_quote($word, \'/\') . \'(?=[^>]*<)/\' . $pcreModifier;
        $replacement = \'<span class="\' . $class . \'">${0}</span>\';
        $body[1] = preg_replace($pattern, $replacement, $body[1]);
      }
    
      $output = implode("<body>", $body);
    
      $removeUrl = $modx->makeUrl($modx->documentIdentifier);
      $highlightText .= \'<br /><a href="\'.$removeUrl.\'" class="ajaxSearch_removeHighlight">\'.$removeText.\'</a></div>\';
    
      $output = str_replace(\'<!--search_terms-->\',$highlightText,$output);
      $modx->documentOutput = $output;
  }
}';
$p['Image TV Preview'] = '// <?php
//    @name       ShowImageTVs
//    @version    0.2.2, 24 Nov 2008
//
//
//    @author     Brett @ The Man Can!
//                rewritten by Rachael Black, update by pixelchutes
//                now works with MooTools and finds the image tvs itself
//

/* ---------------------------------------------------------------
Instructions:
     Create a new Plugin and tick Documents > OnDocFormRender event.
     Make sure it is set to execute after any other plugin that
     could effect the template, like InheritParentTemplate. To edit
     the plugin execution order, from the manager go to Resources >
     Manage Resources > Plugins > Edit Plugin Execution Order by Event
     link. That\'s it. It should now show images of all image TVs.

     To configure image size, copy the following text (no leading spaces):
       &w=Max width;int;300 &h=Max height;int;100
     into the plugin configuration and change values to suit
     This sets style="max-width: ; max-height: " for the image
     If you don\'t configure w or h, the image will be fullsize but
     you can add a css rule rule div.tvimage img {...} to the Manager Theme
------------------------------------------------------------------- */

global $content;
$template = $content[\'template\'];
$e = &$modx->Event;

if ($e->name == \'OnDocFormRender\' && ($template > 0)) {
	$site = $modx->config[\'site_url\'];

	if (isset($w) || isset($h)) {
		$w = isset($w) ? $w : 300;
		$h = isset($h) ? $h : 100;
		$style = "\'max-width:{$w}px; max-height:{$h}px\'";
	}
	else
		$style = \'\';

		// get list of all image template vars
	$table = $modx->getFullTableName(\'site_tmplvars\');
	$result = $modx->db->select(\'name\', $table, "type=\'image\'");
	$tvs = \'\';
	while ($row = $modx->db->getRow($result))
		$tvs .= ",\'" . $row[\'name\'] . "\'";
	$tvs = substr($tvs, 1);		// remove leading \',\'

	$output = <<< EOT
<!-- ShowImageTVs Plugin :: Start -->

<script type="text/javascript" charset="utf-8">
  var imageNames = [$tvs];
  var pageImages = [];

  function full_url(url)
  {
	new_url = (url != \'\' && url.search(/http:\\/\\//i) == -1) ? (\'$site\' + url) : url;
	return ( ( new_url.search(\'@INHERIT\') == -1 ) ? new_url : new_url.replace( new RegExp(/@INHERIT/ig), \'\' ) ); // Update by pixelchutes
  }

  function checkImages()
  {
    for (var i = 0; i < pageImages.length; i++) {
    	var elem = pageImages[i];
      var url = elem.value;
      if (url != elem.oldUrl) {
     	  elem.thumbnail.setProperty(\'src\', full_url(url));
     	  elem.thumbnail.setStyle(\'display\', url==\'\' ? \'none\': \'inline\');
        elem.oldUrl = url;
      }
    }
  }

	window.onDomReady(function() {
    for (var i = 0; i < imageNames.length; i++) {
    	var elem = $(\'tv\' + imageNames[i]);

  	// Account for TVs with "underscores" in their name (MODx escapes them to %5F)
        // Update by pixelchutes
        if (!elem) {
            newname = imageNames[i].replace(new RegExp(/_/ig),\'%5F\');
            var elem = $(\'tv\' + newname);
        }

      if (elem) {
        var url = elem.value;

        	// create div and img to show thumbnail
        var div = new Element(\'div\').addClass(\'tvimage\');
        var img = new Element(\'img\').setProperty(\'src\', full_url(url)).setStyles($style);
        elem.getParent().adopt(div.adopt(img));

        elem.thumbnail = img;    // direct access for when need to update
        elem.oldUrl = url;   		 // oldUrl so change HTML only when necessary
        pageImages.push(elem);   // save so don\'t have to search each time
      }
    }
    setInterval(checkImages, 1000);
  })
</script>

<!-- ShowImageTVs Plugin :: End -->
EOT;

	$e->output($output);
}

// ?>
';
$p['AliasLinks'] = 'if (!empty($modx->documentListing)) {
    foreach ($modx->documentListing as $linkAlias => $linkDocId) {
        $modx->setPlaceholder("~{$linkAlias}", "{$linkDocId}");
    }
}';
$e = &$this->pluginEvent;
$e['OnBeforeManagerLogin'] = array('Forgot Manager Login');
$e['OnChunkFormRender'] = array('Bottom Button Bar');
$e['OnDocFormPrerender'] = array('Inherit Parent Template');
$e['OnDocFormRender'] = array('Image TV Preview','Bottom Button Bar');
$e['OnManagerAuthentication'] = array('Forgot Manager Login');
$e['OnManagerLoginFormRender'] = array('Forgot Manager Login');
$e['OnModFormRender'] = array('Bottom Button Bar');
$e['OnParseDocument'] = array('QuickEdit');
$e['OnPluginFormRender'] = array('Bottom Button Bar');
$e['OnSnipFormRender'] = array('Bottom Button Bar');
$e['OnTempFormRender'] = array('Bottom Button Bar');
$e['OnTVFormRender'] = array('Bottom Button Bar');
$e['OnUserFormRender'] = array('Bottom Button Bar');
$e['OnWebPageInit'] = array('AliasLinks');
$e['OnWebPagePrerender'] = array('QuickEdit');
$e['OnWUsrFormRender'] = array('Bottom Button Bar');
?>