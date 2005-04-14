<?php
#:: Module Installer 1.0 (Beta 2)
#::	Written By Raymond Irving - Dec 2004
#:::::::::::::::::::::::::::::::::::::::::
#:: Installs Modules, Plugins, Snippets, Chunks, Templates and TVs

	session_start();
	
	// session loop-back tester
	if(!isset($_GET['s'])) {
		$_SESSION['session_test'] = 1;
		header("Location:./index.php?s=set");		
	}

	$moduleName 		= "Module Installation";
	$moduleVersion 		= "1.0 ";
	$moduleSQLBaseFile 	= "setup.sql";
	$moduleSQLDataFile 	= "setup.data.sql";
	$moduleWhatsNewFile = "setup.whatsnew.html";
	$moduleWhatsNewTitle= "What's New";
	
	$moduleWelcomeMessage= "";
	$moduleLicenseMessage= "";

	$moduleChunks 	 	= array(); // chunks - array : name, description, type - 0:file or 1:content, file or content
	$modulePlugins		= array(); // plugins - array : name, description, plugin - 0:disabled or 1:enabled, type - 0:file or 1:content, file or content,properties
	$moduleSnippets 	= array(); // snippets - array : name, description, plugin - 0:disabled or 1:enabled, type - 0:file or 1:content, file or content,properties
	$moduleTemplates 	= array(); // templates - array : name, description, plugin - 0:disabled or 1:enabled, type - 0:file or 1:content, file or content,properties
	$moduleTVs		 	= array(); // template variables - array : name, description, plugin - 0:disabled or 1:enabled, type - 0:file or 1:content, file or content,properties

	# function to call after setup
	$callBackFnc =""; 			
	
	# load setup information file
	$setupPath = dirname(__FILE__);
	include_once "$setupPath/setup.info.php";
	
	$errors = 0;
	$upgradeable = file_exists("../manager/includes/config.inc.php") ? 1:0;
	
	$isPostBack = count($_POST);
	if($isPostBack) {
		ob_start();
		include_once "$setupPath/instprocessor.php";
		$moduleWelcomeMessage = ob_get_contents();
		ob_end_clean();
	}
	
	// build Welcome Screen
	function buildWelcomeScreen() {
		global $moduleName;
		global $moduleWelcomeMessage;
		if ($moduleWelcomeMessage) return $moduleWelcomeMessage;
		else {
			ob_start();
			?>
				<table width="100%">
				<tr>
				<td>
					<img src="img_splash.gif" />
				</td>
				<td valign="top">
					<p class='title'>Welcome to the <?php echo $moduleName; ?> installation program.</p>
					<p>This program will guide you throug the rest of the installation.</p>
					<p><strong>IMPORTANT NOTE:</strong> If you are planning to perform an upgrade installation from the technology preview release of MODx, please backup all plugin work you've done (which is probably none) and delete the <em>prefix_</em>site_plugins table from your database. You've been warned! ;)</p>
					<p>Please select 'Next' button to continue:</p>
				</td>
				</tr>
				</table>
				
			<?php
			$o = ob_get_contents();
			ob_end_clean();
			return $o;
		}		
	}

	// build License Screen
	function buildLicenseScreen() {
		global $moduleName;
		global $moduleLicenseMessage;
		if ($moduleLicenseMessage) return $moduleLicenseMessage;
		else {
			ob_start();
			?>
				<div style="padding-right:10px;"><span class='title'><?php echo $moduleName; ?> License Agreement.</span>
				<hr align="left" size="1" width="90%"><br/>
					<p><H4>You must agree to the License before continuing installation.</H4>
					Usage of this software is subject to the GPL license. To help you understand 
					what the GPL licence is and how it affects your ability to use the software, we 
					have provided the following summary:</P>
					<H4>The GNU General Public License is a Free Software license.</H4>Like any Free 
					Software license, it grants to you the four following freedoms: 
					<UL style="TEXT-ALIGN: justify">
					<LI>The freedom to run the program for any purpose. 
					<LI>The freedom to study how the program works and adapt it to your needs. 
					<LI>The freedom to redistribute copies so you can help your neighbor. 
					<LI>The freedom to improve the program and release your improvements to the 
					public, so that the whole community benefits. </LI></UL>
					<P>You may exercise the freedoms specified here provided that you comply with 
					the express conditions of this license. The principal conditions are:</P>
					<UL style="TEXT-ALIGN: justify">
					<LI>You must conspicuously and appropriately publish on each copy distributed an 
					appropriate copyright notice and disclaimer of warranty and keep intact all the 
					notices that refer to this License and to the absence of any warranty; and give 
					any other recipients of the Program a copy of the GNU General Public License 
					along with the Program. Any translation of the GNU General Public License must 
					be accompanied by the GNU General Public License. 
					<LI>If you modify your copy or copies of the program or any portion of it, or 
					develop a program based upon it, you may distribute the resulting work provided 
					you do so under the GNU General Public License. Any translation of the GNU 
					General Public License must be accompanied by the GNU General Public License. 
					<LI>If you copy or distribute the program, you must accompany it with the 
					complete corresponding machine-readable source code or with a written offer, 
					valid for at least three years, to furnish the complete corresponding 
					machine-readable source code. 
					<LI>Any of these conditions can be waived if you get permission from the 
					copyright holder. 
					<LI>Your fair use and other rights are in no way affected by the above. 
					</LI></UL>
					<P>The above is a summary of the GNU General Public License. By proceeding, you 
					are agreeing to the GNU General Public Licence, not the above. The above is 
					simply a summary of the GNU General Public Licence, and its accuracy is not 
					guaranteed. It is strongly recommended you read the <A 
					href="http://www.gnu.org/copyleft/gpl.html" target=_blank>GNU General Public 
					License</A> in full before proceeding, which can also be found in the LICENCE 
					file distributed with this package.</p><p />&nbsp;</div>
			<?php
			$o = ob_get_contents();
			ob_end_clean();
			return $o;
		}		
	}

	// build install mode Screen
	function buildInstallModeScreen() {
		global $upgradeable;
		global $moduleName;
		ob_start();
		?>
			<table border="0" width="100%">
			  <tr>
				<td nowrap valign="top" width="37%">
				<input type="radio" name="installmode" value="new" onclick="setInstallMode(0);" <?php echo !$upgradeable ? "checked='checked'":"" ?> />New Installation</td>
				<td width="61%">This will install a new copy of the <?php echo $moduleName; ?> software on your web site. Please not that this option may overwrite any data inside your database.</td>
			  </tr>
			  <tr>
				<td nowrap valign="top" width="37%">&nbsp;</td>
				<td width="61%">&nbsp;</td>
			  </tr>
			  <tr>
				<td nowrap valign="top" width="37%">
				<input type="radio" name="installmode" value="upd" onclick="setInstallMode(1);" <?php echo !$upgradeable ? "disabled='diabled'":"" ?> />Upgrade Installation</td>
				<td width="61%">Select this option to upgrade your current files and 
				database.</td>
			  </tr>
			</table>
		<?php
		$o = ob_get_contents();
		ob_end_clean();
		return $o;		
	}

	// build Connection Screen
	function buildConnectionScreen() {
		ob_start();
		?>
			<p><span class="title">Connection Information</span><br />
			Database connection and login information<br /><br />
			Please enter the name of the database you&#39;ve created for MODX. If you haven&#39;t 
			created<br>
			a database yet, we will attempt to do so for you, but this may fail depending on 
			the <br>
			MySQL setup your host uses.</p>
			<div class="labelHolder"><label for="databasename">Database name:</label></div>
			<input id="databasename" style="WIDTH: 200px" value="modx" name="databasename"><br>
			<div class="labelHolder"><label for="tableprefix">Table prefix:</label></div>
			<input id="tableprefix" style="WIDTH: 200px" value="modx_" name="tableprefix">
			<p></p>
			<p>Now please enter the login data for your database.</p>
			<p></p>
			<div class="labelHolder"><label for="databasehost">Database host:</label></div>
			<input id="databasehost" style="WIDTH: 200px" value="localhost" name="databasehost"><br>
			<div class="labelHolder"><label for="databaseloginname">Database login name:</label></div>
			<input id="databaseloginname" style="WIDTH: 200px" name="databaseloginname"><br>
			<div class="labelHolder"><label for="databaseloginpassword">Database password:</label></div>
			<input id="databaseloginpassword" style="WIDTH: 200px" type="password" value name="databaseloginpassword"><br>
			<p></p>
			<p>Now you&#39;ll need to enter some details for the main administrator account.<br>
			You can fill in your own name here, and a password you&#39;re not likely to forget.
			<br>
			You&#39;ll need these to log into Admin once setup is complete.</p>
			<p></p>
			<div class="labelHolder"><label for="cmsadmin">Administrator username:</label></div>
			<input id="cmsadmin" style="WIDTH: 200px" value="admin" name="cmsadmin"><br>
			<div class="labelHolder"><label for="cmspassword">Administrator password:</label></div>
			<input id="cmspassword" style="WIDTH: 200px" type="password" value name="cmspassword"><br>
			<div class="labelHolder"><label for="cmspasswordconfirm">Confirm password:</label></div>
			<input id="cmspasswordconfirm" style="WIDTH: 200px" type="password" value name="cmspasswordconfirm"><br>
			<p />&nbsp;
		<?php
		$o = ob_get_contents();
		ob_end_clean();
		return $o;		
	}
	
	// build Options Screen
	function buildOptionsScreen() {
		global $moduleChunks,$moduleSnippets;
		ob_start();	
		echo "<p><span class='title'>Optional Items</span><br />Please choose your installation options and click Install:</p>";
		// display chunks
		$limit = count($moduleChunks);
		if ($limit>0) echo "<h1>Chunks</h1>";
		for ($i=0;$i<$limit;$i++) {
			echo "&nbsp;<input type='checkbox' name='chunk[]' value='$i' checked>Install/Update <span class='comname'>".$moduleChunks[$i][0]."</span> - ".$moduleChunks[$i][1]."<hr size='1' style='border:1px dotted silver;' />";
		}

		// display snippets
		$limit = count($moduleSnippets);
		if ($limit>0) echo "<h1>Snippets</h1>";
		for ($i=0;$i<$limit;$i++) {
			echo "&nbsp;<input type='checkbox' name='snippet[]' value='$i' checked>Install/Update <span class='comname'>".$moduleSnippets[$i][0]."</span> - ".$moduleSnippets[$i][1]."<hr size='1' style='border:1px dotted silver;' />";
		}
		$o = ob_get_contents();
		ob_end_clean();
		return $o;
	}

	// build Summary Screen
	function buildSummaryScreen() {
		global $errors;
		ob_start();
		echo "<p>Setup has carried out a number of checks to see if everything's ready to start the setup.<br />";
		$errors = 0;
		// check PHP version
		echo "<br />Checking PHP version:<b> ";
		$php_ver_comp =  version_compare(phpversion(), "4.1.0");
		$php_ver_comp2 =  version_compare(phpversion(), "4.3.8");
		// -1 if left is less, 0 if equal, +1 if left is higher
		if($php_ver_comp < 0) {
			echo "<span class='notok'>Failed!</span> - You are running on PHP ".phpversion().", and ModX requires PHP 4.1.0";
			$errors += 1;
		} else {
			echo "<span class='ok'>OK!</span>";
			if($php_ver_comp2 < 0) {
			   echo "</b><fieldset><legend>Security notice</legend>While ModX will work on your PHP version (".phpversion()."), usage of ModX on this version is not recommended. Your version of PHP is vulnerable to numerous security holes. As of typing, the latest PHP version is 4.3.8, which patches these holes. It is recommended you upgrade to this version for the security of your own website.</fieldset>";	
			}
		}
		// check sessions
		echo "</b><br />Checking if sessions are properly configured:<b> ";
		if($_SESSION['session_test']!=1 ) {
			echo "<span class='notok'>Failed!</span>";
			$errors += 1;
		} else {
			echo "<span class='ok'>OK!</span>";
		}
		// check directories
		// cache exists?
		echo "</b><br />Checking if <span class='mono'>assets/cache</span> directory exists:<b> ";
		if(!file_exists("../assets/cache")) {
			echo "<span class='notok'>Failed!</span>";
			$errors += 1;
		} else {
			echo "<span class='ok'>OK!</span>";
		}
		// cache writable?
		echo "</b><br />Checking if <span class='mono'>assets/cache</span> directory is writable:<b> ";
		if(!is_writable("../assets/cache")) {
			echo "<span class='notok'>Failed!</span>";
			$errors += 1;
		} else {
			echo "<span class='ok'>OK!</span>";
		}
		// images exists?
		echo "</b><br />Checking if <span class='mono'>assets/images</span> directory exists:<b> ";
		if(!file_exists("../assets/images")) {
			echo "<span class='notok'>Failed!</span>";
			$errors += 1;
		} else {
			echo "<span class='ok'>OK!</span>";
		}
		// images writable?
		echo "</b><br />Checking if <span class='mono'>assets/images</span> directory is writable:<b> ";
		if(!is_writable("../assets/images")) {
			echo "<span class='notok'>Failed!</span>";
			$errors += 1;
		} else {
			echo "<span class='ok'>OK!</span>";
		}
		// export exists?
		echo "</b><br />Checking if <span class='mono'>assets/export</span> directory exists:<b> ";
		if(!file_exists("../assets/export")) {
			echo "<span class='notok'>Failed!</span>";
			$errors += 1;
		} else {
			echo "<span class='ok'>OK!</span>";
		}
		// export writable?
		echo "</b><br />Checking if <span class='mono'>assets/export</span> directory is writable:<b> ";
		if(!is_writable("../assets/export")) {
			echo "<span class='notok'>Failed!</span>";
			$errors += 1;
		} else {
			echo "<span class='ok'>OK!</span>";
		}
		// config.inc.php writable?
		echo "</b><br />Checking if <span class='mono'>manager/includes/config.inc.php</span> is writable:<b> ";
		if(!file_exists("../manager/includes/config.inc.php")) {
			// make an attempt to create file
			@$hnd=fopen("../manager/includes/config.inc.php", 'w');
			@fwrite($hnd,"<?php //MODx configuration file ?>");
			@fclose($hnd);
		}
		if(!is_writable("../manager/includes/config.inc.php")) {
			echo "<span class='notok'>Failed!</span></b>";
			$errors += 1;
		} else {
			echo "<span class='ok'>OK!</span></b>";
		}

		if($errors>0) {
		?>
			<br /><br />
			Unfortunately, Setup cannot continue at the moment, due to the above <?php echo $errors > 1 ? $errors." " : "" ; ?>error<?php echo $errors > 1 ? "s" : "" ; ?>. Please correct the error<?php echo $errors > 1 ? "s" : "" ; ?>, and try again. If you need help figuring out how to fix the problem<?php echo $errors > 1 ? "s" : "" ; ?>, visit the <a href="http://vertexworks.com/forums/" target="_blank">Operation MODx Forums</a>.
			<br />
		<?php
		}
		$o = ob_get_contents();
		ob_end_clean();
		return $o;		
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo $moduleName; ?> &raquo; Install</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
             @import url('./style.css');
             .comname {
             	font-weight:bold;
            	color:#808080;}
        </style>
	<script type="text/javascript" language="JavaScript" src="webelm.js"></script>
    <script>
    	
    	var cursrc = 1;
		var installMode = <?php echo !$upgradeable ? "0":"-1" ?>; // -1 - not set, 0 - new, 1 - upgrade
		var sidebar = "<a href='<?php echo $moduleWhatsNewFile; ?>' target='_blank'><?php echo $moduleWhatsNewTitle; ?></a>&nbsp;<p /><img src='img_install.gif' width='48' height='48' />";		

		// set I Agree
		function setIAgree(){
			var btnnext = document.install.cmdnext;
			var chkagree = document.install.chkagree;
			if(!chkagree.checked) btnnext.disabled="disabled";
			else btnnext.disabled = "";					
		}
		
		// set install mode
		function setInstallMode(n) {
			var btnnext = document.install.cmdnext;
			installMode=n;
			btnnext.disabled = "";			
		}
		
		// jumpTo
		function jumpTo(n) {
			cursrc = n;
			for(i=1;i<=5;i++) {
				o = document.getElementById("screen"+i);
				if (o) {
					if(i==cursrc) o.style.display="block";
					else o.style.display="none";
				}
			}
		}
			
		// change screen
		function changeScreen(n){
			var o;
			var viewer = document.getElementById("viewer");
			var agreebox = document.getElementById("iagreebox");
			var btnback = document.install.cmdback;
			var btnnext = document.install.cmdnext;
			
			viewer.scrollTop = 0;
			// set default values
			btnback.value = "Back";
			btnnext.value = "Next";
			agreebox.style.display="none";

			if(n==1) cursrc += 1;
			else cursrc -= 1;
			if(cursrc>7) cursrc = 7;
			if(cursrc<1) cursrc = 1;
			switch (cursrc) {
				case 1:
					btnnext.disabled = "";
					btnback.style.display="none";
					break;
				case 2:
					var chkagree = document.install.chkagree;
					if(!chkagree.checked) btnnext.disabled="disabled";
					else btnnext.disabled = "";
					btnback.style.display="block";
					agreebox.style.display="block";
					break;
				case 3:
					if(installMode==-1) btnnext.disabled="disabled";
					btnback.style.display="block";
					break;
				case 4:
					btnnext.disabled = "";
					if(installMode==1 && n==1) {
						jumpTo(5);
					}
					else if(installMode==1 && n==-1) {
						jumpTo(3);
					}
					else {
						btnback.style.display="block";
						agreebox.style.display="none";
					}
					break;
				case 5:			
					if(installMode==0 && !validate()) {
						cursrc=4;
						return;
					}
					else {
						btnnext.disabled = "";
						btnback.style.display="block";
					}
					break;
				case 6:
					btnnext.value = "Install now";
					btnback.style.display="none";
					if(errors>0) {
						btnnext.disabled="disabled";
					}
						
					break;
				case 7:
					btnnext.value = "Close";
					btnback.style.display="none";
					document.install.submit();
					btnback.disabled = "disabled";
					btnnext.disabled = "disabled";
					break;
			}
			for(i=1;i<=7;i++) {
				o = document.getElementById("screen"+i);
				if (o) {
					if(i==cursrc) o.style.display="block";
					else o.style.display="none";
				}
			}
		}
		
		// validate
		function validate() {
			var f = document.install;
			if(f.databasename.value=="") {
				alert('You need to enter a value for database name!');
				f.databasename.focus();
				return false;
			}
			if(f.databasehost.value=="") {
				alert('You need to enter a value for database host!');
				f.databasehost.focus();
				return false;
			}
			if(f.databaseloginname.value=="") {
				alert('You need to enter your database login name!');
				f.databaseloginname.focus();
				return false;
			}
			if(f.cmsadmin.value=="") {
				alert('You need to enter a username for the system admin account!');
				f.cmsadmin.focus();
				return false;
			}
			if(f.cmspassword.value=="") {
				alert('You need to a password for the system admin account!');
				f.cmspassword.focus();
				return false;
			}
			if(f.cmspassword.value!=f.cmspasswordconfirm.value) {
				alert('The administrator password and the confirmation don\'t match!');
				f.cmspassword.focus();
				return false;
			}
			return true;
		}
		
		function closepage(){			
			window.location.href = "../manager/";
		}
		
    </script>
</head>	

<body>
<table border="0" cellpadding="0" cellspacing="0" class="mainTable" style="width: 100%;">
<tr>
    <td colspan="2">
		<blockquote>
		  <p><br /><b><img border="0" src="img_banner.gif"></p>
		</blockquote>
    </td>
  </tr>
  <tr class="fancyRow2">
    <td colspan="2" class="border-top-bottom smallText" align="right"><?php echo $moduleName; ?> </b>&nbsp;<i>version <?php echo $moduleVersion; ?></i></td>
  </tr>
  <tr align="left" valign="top">
    <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="1">
      <tr align="left" valign="top">
        <td class="pad" id="content" colspan="2">
			<table border="0" width="100%">
			<tr>
			<td valign="top" nowrap="nowrap"><div id="sidebar" class="sidebar"><script>document.write(sidebar);</script></div></td>
			<td style="border-left:1px dotted silver;padding-left:30px;padding-right:20px;">
			<form name="install" action="index.php?s=set" method="post">
			<div id="viewer" class="viewer">
				<div id="screen1" style="display:block"><?php echo buildWelcomeScreen(); ?></div>
				<?php if(!$isPostBack) { ?>
					<div id="screen2" style="display:none"><?php echo buildLicenseScreen(); ?></div>
					<div id="screen3" style="display:none"><?php echo buildInstallModeScreen(); ?></div>
					<div id="screen4" style="display:none"><?php echo buildConnectionScreen(); ?></div>
					<div id="screen5" style="display:none"><?php echo buildOptionsScreen(); ?></div>
					<div id="screen6" style="display:none"><?php echo buildSummaryScreen(); ?></div>
					<div id="screen7" style="display:none"><p /><br /><h1>Running setup script... please wait</h1></div>
				<?php } ?>
			</div>
			<br />
			<div id="navbar">
				<?php if($isPostBack) { ?>
					<input type='button' value='Close' name='cmdclose' style='float:right;width:100px;' onclick="closepage();" />
				<?php } else {?>
					<input type='button' value='Next' name='cmdnext' style='float:right;width:100px;' onclick="changeScreen(1);" />
					<span style="float:right">&nbsp;</span>
					<input type='button' value='Back' name='cmdback' style='float:right;width:100px;' onclick="changeScreen(-1);" />
					<span id="iagreebox" style='float:left;background-color:#eeeeee'><input type='checkbox' value='1' name='chkagree' onclick="setIAgree()" /> I agree to the terms set out in this license. &nbsp;</span>
				<?php } ?>
			</div>
			</form>
            </td>
            </tr>
            </table>
		</td>
      </tr>
    </table></td>
  </tr>
  <tr class="fancyRow2">
    <td class="border-top-bottom smallText" colspan="2"> 
    &nbsp;</td>
  </tr>
</table>
<script>
	var errors = <?php echo $errors; ?>;
</script>

<?php if(!$isPostBack) { ?>
	<script>
		var agreebox = document.getElementById("iagreebox");
		var btnback = document.install.cmdback;
		agreebox.style.display="none";
		btnback.style.display="none";
	</script>
<?php } ?>
</body>
</html>