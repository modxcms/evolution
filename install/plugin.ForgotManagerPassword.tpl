// TODO Add translation support

if(!class_exists('ForgotManagerPassword')) {
class ForgotManagerPassword{

 function ForgotManagerPassword(){
 
  $this->errors = array();
 
  $this->checkLang();

 }

 function getLink() {
 
$link = <<<EOD
<a href="index.php?action=show_form">Forgot your password?</a>
EOD;

  return $link;

 }

 function getForm() {

$form = <<<EOD
<label for="email">Account email:</label>
<input id="email" type="text" />
<button type="button" onclick="window.location = 'index.php?action=send_email&email='+document.getElementById('email').value;">Send</button>
EOD;

  return $form;

 }

 // Get user info including a hash unique to this user, password, and day
 function getUser($user_id=0, $username='', $email='', $hash='') {

  global $modx;
 
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
  
  if(!$user['id']) { $this->errors[] = 'Could not find user'; }

  return $user;

 }

 // Send an email with a link to login
 function sendEmail($to) {

  global $modx;

  $subject = 'Password reset request';
  $headers = '';
  
  $user = $this->getUser(0, '', $to);

  if($user['username']) {

$body = <<<EOD
A request has been made to reset the password on your account. To complete the process go to {$modx->config['site_url']}manager/processors/login.processor.php?username={$user['username']}&hash={$user['hash']}

From there you will be able to change your password from the My Account menu.

* The URL above will expire once you change your password or after today.
EOD;

   $mail = mail($to, $subject, $body, $headers);
 
   if(!$mail) { $this->errors[] = 'Error sending email'; }
 
   return $mail;
   
  }

 }
 
 function unblockUser($user_id) {
  
  global $modx;
  $pre = $modx->db->config['table_prefix'];
  
  $modx->db->update(array('blocked'=>'', 'blockeduntil'=>''), "{$pre}user_attributes", "internalKey = '{$user_id}'");
  
  if(!$modx->db->getAffectedRows()) { $this->errors[] = 'User does not exist'; return; }
  
  return true;
  
 }
 
 function checkLang() {
  // TODO handle missing language elements
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
   if($forgot->sendEmail($to)) { $output = 'Email sent'; }
   break;
  
  default:
   $output = $forgot->getLink();

 }
 
 if($forgot->errors) { $output = $forgot->getErrorOutput(); }
 
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