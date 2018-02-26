<?php
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}
if(!class_exists('ForgotManagerPassword')) {
    class ForgotManagerPassword{
        function __construct(){
            $this->errors = array();
            $this->checkLang();
        }

        function getLink() {
            global $_lang;

            $link = <<<EOD
<a id="ForgotManagerPassword-show_form" href="index.php?action=show_form">{$_lang['forgot_your_password']}</a>
EOD;

            return $link;
        }

        function getForm() {
            global $_lang;

            $form = <<<EOD
<label id="FMP-email_label" for="FMP_email">{$_lang['account_email']}:</label>
<input id="FMP-email" type="text" />
<button id="FMP-email_button" type="button" onclick="window.location = 'index.php?action=send_email&email='+encodeURIComponent(document.getElementById('FMP-email').value);">{$_lang['send']}</button>
EOD;

            return $form;
        }

        /* Get user info including a hash unique to this user, password, and day */
        function getUser($user_id=false, $username='', $email='', $hash='') {
            global $modx, $_lang;

            $user_id = $user_id == false ? false : $modx->db->escape($user_id);
            $username = $modx->db->escape($username);
            $email = $modx->db->escape($email);
            $hash = $modx->db->escape($hash);
            $tbl_manager_users   = $modx->getFullTableName('manager_users');
            $tbl_user_attributes = $modx->getFullTableName('user_attributes');

            // $site_id = $modx->config['site_id'];
            $today = date('Yz'); // Year and day of the year
            $wheres = array();
            $user = null;

            if($user_id !== false) { $wheres[] = "usr.id='{$user_id}'"; }
            if(!empty($username))  { $wheres[] = "usr.username='{$username}'"; }
            if(!empty($email))     { $wheres[] = "attr.email='{$email}'"; }
            if(!empty($hash))      { $wheres[] = "MD5(CONCAT('{$today}',attr.lastlogin,usr.password))='{$hash}'"; }
            $wheres[] = "attr.lastlogin > 0";
            
            if($wheres) {
                $result = $modx->db->select(
                    "usr.id, usr.username, attr.email, attr.blocked, MD5(CONCAT('{$today}',attr.lastlogin,usr.password)) AS hash",
                    "{$tbl_manager_users} usr
                        INNER JOIN {$tbl_user_attributes} attr ON usr.id=attr.internalKey",
                    implode(' AND ',$wheres),
                    "",
                    1
                );
                $user = $modx->db->getRow($result);
            }

            if($user == null) { $this->errors[] = $_lang['could_not_find_user']; }

            return $user;
        }



        /* Send an email with a link to login */
        function sendEmail($to) {
            global $modx, $_lang;

            $user = $this->getUser(0, '', $to);
            if ($user['blocked']) {
                $this->errors[] = $_lang['user_is_blocked'];
                return false;
            }
            if($modx->config['use_captcha']==='1') $captcha = '&captcha_code=ignore';

            if($user['username']) {
                $body = <<<EOD
<p>{$_lang['forgot_password_email_intro']} <a href="{$modx->config['site_manager_url']}/processors/login.processor.php?username={$user['username']}&hash={$user['hash']}{$captcha}">{$_lang['forgot_password_email_link']}</a></p>
<p>{$_lang['forgot_password_email_instructions']}</p>
<p><small>{$_lang['forgot_password_email_fine_print']}</small></p>
EOD;

                $param = array();
                $param['from']    = "{$modx->config['site_name']}<{$modx->config['emailsender']}>";
                $param['to']      = "{$user['username']}<{$to}>";
                $param['subject'] = $_lang['password_change_request'];
                $param['body']    = $body;
                $rs = $modx->sendmail($param); //ignore mail errors in this case

                if(!$rs) $this->errors[] = $_lang['error_sending_email'];

                return $rs;
            }
        }

        function unblockUser($user_id) {
            global $modx, $_lang;

            $modx->db->update(array('blockeduntil' => 0, 'failedlogincount' => 0), $modx->getFullTableName('user_attributes'), "internalKey = '{$user_id}'");

            if(!$modx->db->getAffectedRows()) { $this->errors[] = $_lang['user_doesnt_exist']; return; }

            return true;
        }

        function checkLang() {
            global $_lang;

            $eng = array();
            $eng['forgot_your_password'] = 'Forgot your password?';
            $eng['account_email'] = 'Account email';
            $eng['send'] = 'Send';
            $eng['password_change_request'] = 'Password change request';
            $eng['forgot_password_email_intro'] = 'A request has been made to change the password on your account.';
            $eng['forgot_password_email_link'] = 'Click here to complete the process.';
            $eng['forgot_password_email_instructions'] = 'From there you will be able to change your password from the My Account menu.';
            $eng['forgot_password_email_fine_print'] = '* The URL above will expire once you change your password or after today.';
            $eng['error_sending_email'] = 'Error sending email';
            $eng['could_not_find_user'] = 'Could not find user';
            $eng['user_doesnt_exist'] = 'User does not exist';
            $eng['user_is_blocked'] = 'This User is blocked!';
            $eng['email_sent'] = 'Email sent';

            foreach($eng as $key=>$value) {
                if(empty($_lang[$key])) { $_lang[$key] = $value; }
            }
        }

        function getErrorOutput() {
            $output = '';

            if($this->errors) {
                $output = '<span class="error">'.implode('</span><span class="errors">', $this->errors).'</span>';
            }

            return $output;
        }
    }
}

global $_lang;

$output = '';
$event_name = $modx->Event->name;
$action = (empty($_GET['action']) ? '' : (is_string($_GET['action']) ? $_GET['action'] : ''));
$username = (empty($_GET['username']) ? false : (is_string($_GET['username']) ? $_GET['username'] : ''));
$to = (empty($_GET['email']) ? '' : (is_string($_GET['email']) ? $_GET['email'] : ''));
$hash = (empty($_GET['hash']) ? false : (is_string($_GET['hash']) ? $_GET['hash'] : ''));
$forgot = new ForgotManagerPassword();

if($event_name == 'OnManagerLoginFormRender') {
    switch($action) {
        case 'show_form':
            $output = $forgot->getForm();
            break;
        case 'send_email':
            if($forgot->sendEmail($to)) { $output = '<p><b>'.$_lang['email_sent'].'</b></p>'; }
            break;
        default:
            $output = $forgot->getLink();
            break;
    }

    if($forgot->errors) { $output = $forgot->getErrorOutput() . $forgot->getLink(); }
}

if($event_name == 'OnBeforeManagerLogin' && $hash && $username) {
    $user = $forgot->getUser(false, $username, '', $hash);
    if($user && is_array($user) && !$forgot->errors) {
        $forgot->unblockUser($user['id']);
    }
}

if($event_name == 'OnManagerAuthentication' && $hash && $username) {
    $user = $forgot->getUser(false, $username, '', $hash);
	if($user !== null && count($forgot->errors) == 0) {
		if(isset($_REQUEST['captcha_code']) && !empty($_REQUEST['captcha_code']))
			$_SESSION['veriword'] = $_REQUEST['captcha_code'];
		$output = true;
		$_SESSION['onLoginForwardToAction'] = 28; // action "change password"
	}
	else $output = false;
}

$modx->Event->output($output);
