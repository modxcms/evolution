
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

 // Get user info including a hash unique to this user, password, and day
 function getUser($user_id=0, $username='', $email='', $hash='') {

  global $modx, $_lang;
 
  $user_id = $modx->db->escape($user_id);
  $username = $modx->db->escape($username);
  $email = $modx->db->escape($email);
  $emaail = $modx->db->escape($hash);
  
  $pre = $modx->db->config['table_prefix'];
  $site_id = $modx->config['site_id'];
  $today = date('Yz'); // Year and day of the year
  $wheres = array();
  $where = '';
  $user = array('id'=>0, 'username'=>'', 'email'=>'', 'hash'=>'');
 
  if(!empty($user_id)) { $wheres[] = "id = '{$user_id}'"; }
  if(!empty($username)) { $wheres[] = "username = '{$username}'"; }
  if(!empty($email)) { $wheres[] = "email = '{$email}'"; }
  if(!empty($hash)) { $wheres[] = "MD5(CONCAT(usr.username,usr.password,'{$site_id}','{$today}')) = '{$hash}'"; }
 
  if($wheres) {
  
   $where = ' WHERE '.implode(' AND ',$wheres);
   $sql = "SELECT usr.id, usr.username, attr.email, MD5(CONCAT(usr.username,usr.password,'{$site_id}','{$today}')) AS hash
           FROM {$pre}manager_users usr
           INNER JOIN {$pre}user_attributes attr ON usr.id = attr.internalKey
           {$where};";
   
   if($result = $modx->db->query($sql)){
    if($modx->db->getRecordCount($result)==1) {
     $user = $modx->db->getRow($result);
    }
   }
   
  }
  
  if(!$user['id']) { $this->errors[] = $_lang['could_not_find_user']; }

  return $user;

 }

 // Send an email with a link to login
 function sendEmail($to) {

  global $modx, $_lang;

  $subject = $_lang['password_change_request'];
  $headers  = "MIME-Version: 1.0\n".
              "Content-type: text/html; charset=iso-8859-1\n".
              "From: MODx\n".
              "Reply-To: no-reply@{$_SERVER['HTTP_HOST']}\n".
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
  
  $modx->db->update(array('blocked'=>'', 'blockeduntil'=>''), "{$pre}user_attributes", "internalKey = '{$user_id}'");
  
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
 
  $outptut = '';
 
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
$username = (empty($_GET['username']) ? '' : $_GET['username']);
$to = (empty($_GET['email']) ? '' : $_GET['email']);
$hash = (empty($_GET['hash']) ? '' : $_GET['hash']);
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

 }
 
 if($forgot->errors) { $output = $forgot->getErrorOutput() . $forgot->getLink(); }
 
}

if($event_name == 'OnBeforeManagerLogin') {
 $user = $forgot->getUser(0, $username, '', $hash);
 if($user['id'] && !$forgot->errors) {
  $forgot->unblockUser($user['id']);
 }
}

if($event_name == 'OnManagerAuthentication' && $hash) {
 $user = $forgot->getUser(0, '', '', $hash);
 $output = ($user['id'] > 0 && !$forgot->errors);
}

$modx->Event->output($output);
