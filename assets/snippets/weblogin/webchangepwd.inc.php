<?php
# WebChangePwd 1.0
# Created By Raymond Irving April, 2005
#::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

defined('IN_PARSER_MODE') or die();

# load tpl
if(is_numeric($tpl)) $tpl = ($doc=$modx->getDocuments($tpl)) ? $doc['content']:"Document '$tpl' not found.";
else if($tpl) $tpl = ($chunk=$modx->getChunk($tpl)) ? $chunk:"Chunk '$tpl' not found.";
if(!$tpl) $tpl = getWebChangePwdtpl();

// extract declarations
$declare = webLoginExtractDeclarations($tpl);
$tpls = explode((isset($declare["separator"]) ? $declare["separator"]:"<!--tpl_separator-->"),$tpl);

if(!$isPostBack && isset($_SESSION['webValidated'])){
    // display password screen
    $tpl = $tpls[0];
    $tpl = str_replace("[+action+]",$modx->makeUrl($modx->documentIdentifier),$tpl);
    $tpl.="<script type='text/javascript'>
        if (document.changepwdfrm) document.changepwdfrm.oldpassword.focus();
        </script>";
    $output .= $tpl;
} 
else if ($isPostBack && isset($_SESSION['webValidated'])){
    $oldpassword = $_POST['oldpassword'];
    $genpassword = $_POST['newpassword'];
    $passwordgenmethod = $_POST['passwordgenmethod'];
    $passwordnotifymethod = $_POST['passwordnotifymethod'];
    $specifiedpassword = $_POST['specifiedpassword'];
    
    $uid = $modx->getLoginUserID();
    $type = $modx->getLoginUserType();

    // load template
    $tpl = $tpls[0];
    $tpl = str_replace("[+action+]",$modx->makeUrl($modx->documentIdentifier),$tpl);
    $tpl.="<script type='text/javascript'>if (document.changepwdfrm) document.changepwdfrm.oldpassword.focus();</script>";

    // get user record
    if($type=='manager') $ds = $modx->getUserInfo($uid);
    else $ds = $modx->getWebUserInfo($uid);

    // verify password
    if($ds['password']==md5($oldpassword)) {

        // verify password
        if ($passwordgenmethod=="spec" && $_POST['specifiedpassword']!=$_POST['confirmpassword']) {
            $output = webLoginAlert("Password typed is mismatched",1).$tpl;
            return;
        }

        // generate a new password for this user
        if($specifiedpassword!="" && $passwordgenmethod=="spec") {
            if(strlen($specifiedpassword) < 6 ) {
                $output = webLoginAlert("Password is too short!").$tpl;
                return;
            } else {
                $newpassword = $specifiedpassword;
            }            
        } elseif($specifiedpassword=="" && $passwordgenmethod=="spec") {
            $output = webLoginAlert("You didn't specify a password for this user!").$tpl;
            return;        
        } elseif($passwordgenmethod=='g') {
            $newpassword = webLoginGeneratePassword(8);        
        } else {
            $output = webLoginAlert("No password generation method specified!").$tpl;
            return;
        }

        // handle notification
        if($passwordnotifymethod=='e') {
            $rt = webLoginSendNewPassword($ds["email"],$ds["username"],$newpassword,$ds["fullname"]);
            if($rt!==true) { // an error occured
                $output = $rt.$tpl;
                return;
            }
            else {
                $newpassmsg = "A copy of the new password was sent to your email address.";
            }
        }
        else {
            $newpassmsg = "The new password is <b>" . htmlspecialchars($newpassword, ENT_QUOTES) . "</b>.";
        }
        
        // save new password to database
        $rt = $modx->changeWebUserPassword($oldpassword,md5($newpassword));
        if($rt!==true) {
            $output = webLoginAlert("An error occured while saving new password: $rt");
            return;
        }        
        
        // display change notification
        $tpl = $tpls[1];
        $tpl = str_replace("[+newpassmsg+]",$newpassmsg,$tpl);    
        $output .= $tpl;
    }
    else {    
        $output = webLoginAlert("Incorrect password. Please try again.").$tpl;
        return;
    }
}

// Returns Default WebChangePwd tpl
function getWebChangePwdtpl(){
    ob_start();
    ?>
    <!-- #declare:separator <hr> --> 
    <!-- login form section-->
    <form method="post" name="changepwdfrm" action="[+action+]" style="margin: 0px; padding: 0px;">
      <table border="0" cellpadding="1" width="300">
        <tr>
          <td><fieldset style="width:300px">
          <legend><b>Enter your current password</b></legend>
          <table border="0" cellpadding="0" style="margin-left:20px;">
            <tr>
              <td style="padding:0px 0px 0px 0px;">
              <label for="oldpassword" style="width:120px">Current password:</label>
              </td>
              <td style="padding:0px 0px 0px 0px;">
              <input type="password" name="oldpassword" size="20" /><br />
              </td>
            </tr>
          </table>
          </fieldset> <fieldset style="width:300px">
          <legend><b>New password method</b></legend>
          <input type="radio" name="passwordgenmethod" value="g" checked />Let this website 
          generate a password.<br />
          <input type="radio" name="passwordgenmethod" value="spec" />Let me specify 
          the password:<br />
          <div style="padding-left:20px">
            <table border="0" cellpadding="0">
              <tr>
                <td style="padding:0px 0px 0px 0px;">
                <label for="specifiedpassword" style="width:120px">New password:</label>
                </td>
                <td style="padding:0px 0px 0px 0px;">
                <input type="password" name="specifiedpassword" onchange="documentdirty=true;" onkeypress="document.changepwdfrm.passwordgenmethod[1].checked=true;" size="20" /><br />
                </td>
              </tr>
              <tr>
                <td style="padding:0px 0px 0px 0px;">
                <label for="confirmpassword" style="width:120px">Confirm password:</label>
                </td>
                <td style="padding:0px 0px 0px 0px;">
                <input type="password" name="confirmpassword" onchange="documentdirty=true;" onkeypress="document.changepwdfrm.passwordgenmethod[1].checked=true;" size="20" /><br />
                </td>
              </tr>
            </table>
            <small><span class="warning" style="font-weight:normal">The password you 
            specify needs to be at least 6 characters long.</span></small>
          </div>
          </fieldset><br />
          <fieldset style="width:300px">
          <legend><b>Password notification method</b></legend>
          <input type="radio" name="passwordnotifymethod" value="e" />Send the new password 
          by e-mail.<br />
          <input type="radio" name="passwordnotifymethod" value="s" checked />Show the new password 
          on screen.
          </fieldset></td>
        </tr>
        <tr>
          <td align="right"><input type="submit" value="Submit" name="cmdwebchngpwd" />
          <input type="reset" value="Reset" name="cmdreset" />
          </td>
        </tr>
      </table>
    </form>
    <hr>
    <!-- notification section -->
    Your password was successfully changed.<br /><br />
    [+newpassmsg+]
    <?php 
    $t = ob_get_contents();
    ob_end_clean();
    return $t;
}

?>