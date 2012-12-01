//<?php
/**
 * Forgot Manager Login
 * 
 * Resets your manager login when you forget your password via email confirmation
 *
 * @category 	plugin
 * @version 	1.1.4
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@events OnBeforeManagerLogin,OnManagerAuthentication,OnManagerLoginFormRender 
 * @internal	@modx_category Manager and Admin
 * @internal    @installset base
 */

if(!class_exists('ForgotManagerPassword')) {
    class ForgotManagerPassword{
        function ForgotManagerPassword(){
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
<button id="FMP-email_button" type="button" onclick="window.location = 'index.php?action=send_email&email='+document.getElementById('FMP-email').value;">{$_lang['send']}</button>
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

			$pre = $modx->db->config['table_prefix'];
			$site_id = $modx->config['site_id'];
			$today = date('Yz'); // Year and day of the year
			$wheres = array();
			$where = '';
			$user = null;
  
            if($user_id !== false) { $wheres[] = "usr.id = '{$user_id}'"; }
            if(!empty($username)) { $wheres[] = "usr.username = '{$username}'"; }
            if(!empty($email)) { $wheres[] = "attr.email = '{$email}'"; }
            if(!empty($hash)) { $wheres[] = "MD5(CONCAT(usr.username,usr.password,'{$site_id}','{$today}')) = '{$hash}'"; } 

            if($wheres) {
                $where = ' WHERE '.implode(' AND ',$wheres);
                $sql = "SELECT usr.id, usr.username, attr.email, MD5(CONCAT(usr.username,usr.password,'{$site_id}','{$today}')) AS hash
                    FROM `{$pre}manager_users` usr
                    INNER JOIN `{$pre}user_attributes` attr ON usr.id = attr.internalKey
                    {$where}      
                    LIMIT 1;"; 

                if($result = $modx->db->query($sql)){
                    if($modx->db->getRecordCount($result)==1) {
                        $user = $modx->db->getRow($result);
                    }
                }
            }

            if($user == null) { $this->errors[] = $_lang['could_not_find_user']; }

            return $user;
        }



        /* Send an email with a link to login */
        function sendEmail($to) {
            global $modx, $_lang;

            $subject = $_lang['password_change_request'];
            $headers  = "MIME-Version: 1.0\r\n".
                "Content-type: text/html; charset=\"{$modx->config['modx_charset']}\"\r\n".
		"From: MODx <{$modx->config['emailsender']}>\r\n".
                "Reply-To: no-reply@{$_SERVER['HTTP_HOST']}\r\n".
                "X-Mailer: PHP/".phpversion();

            $user = $this->getUser(0, '', $to);
  
            if($user['username']) {
                $body = <<<EOD
<p>{$_lang['forgot_password_email_intro']} <a href="{$modx->config['site_url']}manager/processors/login.processor.php?username={$user['username']}&hash={$user['hash']}">{$_lang['forgot_password_email_link']}</a></p>
<p>{$_lang['forgot_password_email_instructions']}</p>
<p><small>{$_lang['forgot_password_email_fine_print']}</small></p>
EOD;

                $mail = mail($to, $subject, $body, $headers);
                if(!$mail) { $this->errors[] = $_lang['error_sending_email']; }
   
                return $mail;  
            }
        }

        function unblockUser($user_id) {
            global $modx, $_lang;

            $pre = $modx->db->config['table_prefix'];
            $modx->db->update(array('blocked' => 0, 'blockeduntil' => 0, 'failedlogincount' => 0), "`{$pre}user_attributes`", "internalKey = '{$user_id}'");

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
$action = (empty($_GET['action']) ? '' : $_GET['action']);
$username = (empty($_GET['username']) ? false : $_GET['username']);
$to = (empty($_GET['email']) ? '' : $_GET['email']);
$hash = (empty($_GET['hash']) ? false : $_GET['hash']);
$forgot = new ForgotManagerPassword();

if($event_name == 'OnManagerLoginFormRender') {
    switch($action) {
        case 'show_form':
            $output = $forgot->getForm();
            break;
        case 'send_email':
            if($forgot->sendEmail($to)) { $output = $_lang['email_sent']; }
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
    $output = ($user !== null && count($forgot->errors) == 0) ? true : false;
}

$modx->Event->output($output);