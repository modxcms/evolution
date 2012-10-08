<?php
/**
 * WebLoginPE
 * A progressively enhanced (PE) user management and login snippet for MODx
 * v1.3.1 Bugfix by Soshite @ MODx CMS Forums & Various Other Forum Members
 *
 * Extra bugfixes not in the repos added by TS. See comments and forum links.
 *
 * @package WebLoginPE
 * @author Scotty Delicious scottydelicious@gmail.com * @version 1.3.1
 * @access public
 * @copyright ©2007-2008 Scotty Delicious http://scottydelicious.com
 **/
class WebLoginPE
{
	/**
	 * An array of language specific phrases.
	 *
	 * @var array
	 * @access public
	 * @see __construct()
	 */
	var $LanguageArray;
	
	/**
	 * Holds a message if one was generated.
	 *
	 * @var string
	 * @access public
	 * @see FormatMessage()
	 */
	var $Report;
	
	/**
	 * A comma separated list of MODx document IDs to attempt to redirect the user to after login.
	 *
	 * @var string
	 * @access public
	 * @see login()
	 * @see LoginHomePage()
	 */
	var $liHomeId;
	
	/**
	 * The MODx document ID to redirect the user to after logout.
	 *
	 * @var string
	 * @access public
	 * @see logout()
	 * @see LogoutHomePage()
	 */
	var $loHomeId;
	
	/**
	 * the type of WebLoginPE (simple, register, profile, or taconite)
	 *
	 * @var string
	 * @access protected
	 */
	var $Type;
	
	/**
	 * Value of $_POST['username'].
	 *
	 * @var string
	 * @access protected
	 */
	var $Username;
	
	/**
	 * Value of $_POST['password'].
	 *
	 * @var string
	 * @access protected
	 */
	var $Password;
	
	/**
	 * The user object assembled from data queried from web_users and web_user_attributes tables
	 *
	 * @var array
	 * @access protected
	 * @see QueryDbForUser()
	 */
	var $User;
	
	/**
	 * Dimensions for the user image
	 *
	 * @var string
	 * @access protected
	 * @see CreateUserImage
	 */
	var $UserImageSettings;
	
	/**
	 * Template for messages returned by WebLoginPE
	 *
	 * @var string;
	 * @access public
	 * @see FormatMessage;
	 */
	var $MessageTemplate;
	
	/**
	 * Number of failed logins
	 *
	 * @var string
	 * @access protected
	 * @see Authenticate
	 */
	var $LoginErrorCount;
	
	/**
	 * Full table name of the custom extended user attributes table.
	 *
	 * @var string
	 * @access protected
	 * @see CustomTable
	 */
	var $CustomTable;
	
	/**
	 * An array of column names for the extended user attributes table.
	 *
	 * @var array
	 * @access protected
	 * @see CustomTable
	 */
	var $CustomFields;
	
	/**
	 * PHP strftime() format for dates in placeholders
	 *
	 * @var string
	 * @access protected
	 * @see PlaceHolders
	 */
	var $DateFormat;
	
	/**
	 * WebLoginPE Class Constructor
	 *
	 * @param array $LanguageArray An array of language specific strings.
	 * @return void
	 * @author Scotty Delicious
	 */
	function __construct($LanguageArray, $dateFormat = '%A %B %d, %Y at %I:%M %p', $UserImageSettings = '105000,100,100', $type = 'simple')
	{
		require_once 'manager/includes/controls/class.phpmailer.php';
		$this->LanguageArray = $LanguageArray;
		$this->DateFormat = $dateFormat;
		$this->UserImageSettings = $UserImageSettings;
		$this->Type = $type;
	}
	
	
	/**
	 * Reference to the construct method (for PHP4 compatibility)
	 *
	 * @see __construct
	 */
	function WebLoginPE($LanguageArray, $dateFormat = '%A %B %d, %Y at %I:%M %p', $UserImageSettings = '105000,100,100', $type = 'simple')
	{
		$this->__construct($LanguageArray, $dateFormat, $UserImageSettings, $type);
	}
	
	
	/**
	 * FormatMessage
	 * Sets a value for $this->Report which is returned to the page if there is an error.
	 * This function is public and can be used to format a message for the calling script.
	 *
	 * @param string $message 
	 * @return void
	 * @author Scotty Delicious
	 */
	function FormatMessage($message = 'There was an error')
	{
		global $modx;
		
		unset($this->Report);
		$messageTemplate = str_replace('[+wlpe.message.text+]', $message, $this->MessageTemplate);
		$this->Report = $messageTemplate;
		$modx->setPlaceholder('wlpe.message', $messageTemplate);
		unset ($messageTemplate);
		return;
	}
	
	
	/**
	 * login
	 * Perform all the necessary functions to establish a secure user session with permissions
	 *
	 * @param string $type If type = 'taconite' do not call $this->LoginHomePage().
	 * @param string $liHomeId Comma separated list of MODx document ID's to attempt to redirect to after login.
	 * @return void
	 * @author Scotty Delicious
	 */
	function Login($type, $liHomeId = '')
	{
		global $modx;
		
		$this->Type = $type;
		$this->liHomeId = $liHomeId;
		
		$this->Username = $modx->db->escape(strip_tags($_POST['username']));
		$this->Password = $modx->db->escape(strip_tags($_POST['password']));
		if ($this->Username == '' || $this->Password == '')
		{
			$this->FormatMessage($this->LanguageArray[5]);
			return;
		}
		$_SESSION['groups'] = array('Registered Users', 'Fans');
		$this->OnBeforeWebLogin();
		$this->User = $this->QueryDbForUser($this->Username);

		if ($this->User == false)
		{
			$this->FormatMessage($this->LanguageArray[21]);
			return;
		}
		else
		{
			$this->Username = $this->User['username']; // (TS) Can login in any case, but ensure case is correct in session
		}
		
		$this->UserIsBlocked();
		$this->Authenticate();
		$this->SessionHandler('start');
		$this->OnWebLogin();
		$this->ActiveUsers();
		$this->UserDocumentGroups();
		if ($type !== 'taconite')
		{
			$this->LoginHomePage();
		}
	}
	
	
	/**
	 * AutoLogin checks for a user cookie and logs the user in automatically
	 *
	 * @return void
	 * @author Scotty Delicious
	 */
	function AutoLogin()
	{
		global $modx;
		
		$cookieName = 'WebLoginPE';
		
		$cookie = explode('|', $_COOKIE[$cookieName]);
		$this->Username = $cookie[0];
		$this->Password = $cookie[1];
		
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		
		$query = "SELECT * FROM ".$web_users.", ".$web_user_attributes." WHERE MD5(".$web_users.".`username`) = '".$this->Username."' AND ".$web_user_attributes.".`internalKey` = ".$web_users.".`id`";
		$dataSource = $modx->db->query($query);
		$limit = $modx->db->getRecordCount($dataSource);
		if ($limit == 0 || $limit > 1)
		{
			$this->User = NULL;
			return false;
		}
		else
		{
			$this->User = $modx->db->getRow($dataSource);
			$this->Username = $this->User['username'];
		}
		
		if ($this->User['password'] !== $this->Password){
			return false;
		}
		
		$this->UserIsBlocked();
		$this->Authenticate();
		$this->SessionHandler('start');
		$this->UserDocumentGroups();
		if ($type !== 'taconite')
		{
			$this->LoginHomePage();
		}
	}
	
	
	/**
	 * logout
	 * Destroy the user session and redirect or refresh.
	 *
	 * @param string $type If type = 'taconite' do not call $this->LogoutHomePage().
	 * @param int $loHomeId MODx document ID to redirect to after logout.
	 * @return void
	 * @author Scotty Delicious
	 */
	function Logout($type, $loHomeId = '')
	{
		$logoutparameters = array(
				'username'		=> $_SESSION['webShortname'],
				'internalKey'		=> $_SESSION['webInternalKey'],
				'userid'		=> $_SESSION['webInternalKey'] // Bugfix by TS
				); // SMF connector fix http://modxcms.com/forums/index.php?topic=26565.0 c/o tazzydemon
				
		$this->Type = $type;
		$this->loHomeId = $loHomeId;
		
		$this->OnBeforeWebLogout(); // TS Bug fix
		$this->StatusToOffline();
		$this->SessionHandler('destroy');
		$this->OnWebLogout($logoutparameters); // SMF connector fix http://modxcms.com/forums/index.php?topic=26565.0 c/o tazzydemon and binary_trust
		if ($type !== 'taconite')
		{
			$this->LogoutHomePage();
		}
	}
	
	
	/**
	 * Custom table checks for the specified extended user attributes table and creates it if it does not exist.
	 * It also checks for custom column names and inserts them into the extended user attributes table if they do not exist.
	 *
	 * @param string $table The name of the custom table (Default is "web_user_attributes_extended")
	 * @param string $fields A comma separated list of column names for the custom table.
	 * @return void
	 * @author Scotty Delicious
	 */
	function CustomTable($table, $fields, $prefixTable = 1, $tableCheck = 1)
	{
		global $modx;
		
		$allTables = array();
		
		if ($prefixTable == 0)
		{
			$tableFull = '`'.$table.'`';
			$table = $table;
		}
		else
		{
			$tableFull = $modx->getFullTableName($table);
			$table = explode('.', $tableFull);
			$table = str_replace('`', '', $table[1]);
		}
		
		if ($fields !== '')
		{
			$fields = explode(',', str_replace(', ', ',', $fields));
		}
		
		$this->CustomTable = $tableFull;
		$this->CustomFields = $fields;
		
		if ($tableCheck == 1)
		{
			// Check if custom table exists. If it does not, create it with default values.
			$tableNames = $modx->db->query("SHOW TABLES");
			while ($eachTable = $modx->db->getRow($tableNames, 'num'))
	 		{
	 			$allTables[] = $eachTable[0];
	 		}
	 		if (!in_array($table, $allTables))
	 		{
				$createTable = $modx->db->query("CREATE TABLE IF NOT EXISTS ".$this->CustomTable." (id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY, internalKey INT(10) NOT NULL)");
				if (!$createTable)
				{
					return $this->FormatMessage($modx->db->getLastError);
				}
				$addIndex = $modx->db->query("ALTER TABLE ".$this->CustomTable." ADD INDEX `userid` ( `internalKey` )");
	 		}
			
			// Create the additional MODx events if they do not exist
			$system_eventnames = $modx->getFullTableName('system_eventnames');
			$newEvents = array('OnBeforeWebSaveUser', 'OnBeforeAddToGroup', 'OnViewUserProfile');
			foreach ($newEvents as $aNewEvent)
			{
				$findEvent = $modx->db->query("SELECT * FROM ".$system_eventnames." WHERE `name` = '".$aNewEvent."'");
				$limit = $modx->db->getRecordCount($findEvent);
				if ($limit == 0)
				{
					$addEvent = $modx->db->query("INSERT INTO ".$system_eventnames." (`name`,`service`) VALUES ('".$aNewEvent."', 3)");
				}
			}
		}
		
		// Check if custom fields exist in custom table. If they do not, create them.
		if ($this->CustomFields !== '')
		{
			$columns = $modx->db->query("SELECT * FROM ".$this->CustomTable);
			$columnNames = $modx->db->getColumnNames($columns);
			foreach ($this->CustomFields as $field)
			{
				if (!in_array($field, $columnNames))
				{
					$addColumn = $modx->db->query("ALTER TABLE ".$this->CustomTable." ADD (`".$field."` VARCHAR(255) NOT NULL)");
				}
			}
		}		
	}
	
	
	/**
	 * register
	 * Inserts a new user into web_users and web_user_attributes.
	 *
	 * @param string $regType 'instant' or 'verify'
	 * @param string $groups which webgroup('s) should the new user be added to.
	 * @param string $regRequired Comma separated list of required fields.
	 * @param string $notify Comma separated list of emails to notify of new registrations.
	 * @param string $notifyTpl Template for email notification message.
	 * @param string $notifySubject Subject line for email notification.
	 * @return void
	 * @author Raymond Irving
	 * @author Scotty Delicious
	 */
	function Register($regType, $groups, $regRequired, $notify, $notifyTpl, $notifySubject)
	{
		global $modx;
		
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		$web_groups = $modx->getFullTableName('web_groups');
		$webgroup_names = $modx->getFullTableName('webgroup_names');
		
		$username = $_POST['username'];
		$this->Username = $username;
	    $password = $modx->db->escape($modx->stripTags($_POST['password']));
		$passwordConfirm = $modx->db->escape($modx->stripTags($_POST['password_confirm']));
	    $fullname = $modx->db->escape($modx->stripTags($_POST['fullname']));
	    $email = $modx->db->escape($modx->stripTags($_POST['email']));
	    $phone = $modx->db->escape($modx->stripTags($_POST['phone']));
		$mobilephone = $modx->db->escape($modx->stripTags($_POST['mobilephone']));
		$fax = $modx->db->escape($modx->stripTags($_POST['fax']));
		$dob = $modx->db->escape($modx->stripTags($_POST['dob']));
		$gender = $modx->db->escape($modx->stripTags($_POST['gender']));
	    $country = $modx->db->escape($modx->stripTags($_POST['country']));
	    $state = $modx->db->escape($modx->stripTags($_POST['state']));
	    $zip = $modx->db->escape($modx->stripTags($_POST['zip']));
		$comment = $modx->db->escape($modx->stripTags($_POST['comment']));
		$cachepwd = time();
		
		// Check for required fields.
if ($_POST['username'] == '' || empty($_POST['username']) || trim($_POST['username']) == '' ) // pixelchutes
		{			
			return $this->FormatMessage($this->LanguageArray[0]);
		}
		if ( strlen($_POST['email']) > 0 ) // pixelchutes
		{
			// Validate the email address.
			$this->ValidateEmail($_POST['email']);
			if (!empty($this->Report))
			{
				return $this->report;
			}
		}
		if ($regRequired !== '')
		{
			$requiredFields = explode(',', str_replace(' ,', ',', $regRequired));
			foreach ($requiredFields as $field)
			{
				if ($field == 'formcode')
				{
					$formcode = $_POST['formcode'];
					if ($_SESSION['veriword'] !== $formcode)
					{
						return $this->FormatMessage($this->LanguageArray[6]);
					}
				}
				
				if ($field == 'email')
				{
					// Validate the email address.
					$this->ValidateEmail($_POST['email']);
					if (!empty($this->Report))
					{
						return $this->report;
					}
				}
				
				if ($_POST[$field] == '' || empty($_POST[$field]))
				{
					if ($field == 'tos')
					{
						return $this->FormatMessage($this->LanguageArray[34]);
					}
					
					return $this->FormatMessage($this->LanguageArray[0]);
				}
			}
		}
		
		// Check username for invalid characters
		if (!ctype_alnum($username)) // (TS)
		{
			return $this->FormatMessage($this->LanguageArray[32]);
		}
		
		// Check username length 
		if (strlen($username) > 100)
		{
			return $this->FormatMessage($this->LanguageArray[1]);
		}
		
		// Check for arrays and that "confirm" fields match.
		foreach ($_POST as $field => $value)
		{
			if (is_array($_POST[$field]))
		
			{
				$_POST[$field] = implode('||', $_POST[$field]);
			}
			
			$confirm = $field.'_confirm';
			if (isset($_POST[$confirm]))
			{
				if ($_POST[$field] !== $_POST[$confirm])
				{
					$error = $this->LanguageArray[2].' <br />';
					$fieldMessage .= str_replace('[+000+]', '"'.$field.'"', $error);
				}
			}
		}
		
		// If confirm fields were mismatched, throw this error:
		if (!empty($fieldMessage))
		{
			$err = $fieldMessage;
			unset($fieldMessage);
			return $this->FormatMessage($err);
		}
		
		// Check Password locally
		if ($regType == 'instant')
		{
			if (strlen($password) < 6)
			{
				return $this->FormatMessage($this->LanguageArray[3]);
			}
			
			if (empty($password) || $password == '')
			{
				return $this->FormatMessage($this->LanguageArray[3]);
			}
			
			if (md5($password) !== md5($_POST['password']))
			{
				return $this->FormatMessage($this->LanguageArray[4]);
			}
		}
		
		$checkUsername = $modx->db->query("SELECT `id` FROM ".$web_users." WHERE `username`='".$username."'");
		$limit = $modx->recordCount($checkUsername);
		
		if ($limit > 0)
		{
			return $this->FormatMessage($this->LanguageArray[7]);
		}
		
		$lowercase = strtolower(str_replace(' ', '_', $username));
		if ($lowercase == 'default_user')
		{
			return $this->FormatMessage($this->LanguageArray[7]);
		}
		
		$checkEmail = $modx->db->query("SELECT * FROM ".$web_user_attributes." WHERE `email`='".$email."'");
		$limit = $modx->recordCount($checkEmail);
		
		if ($limit > 0)
		{
			return $this->FormatMessage($this->LanguageArray[8]);
		}
		
		// If you want to verify your users email address before letting them log in, this generates a random password.
		if ($regType == 'verify')
		{
			$password = $this->GeneratePassword(10);
		}
		
		// Create the user image if necessary.
		if (!empty($_FILES['photo']['name']))
		{
			$photo = $this->CreateUserImage();
			if (!empty($this->Report))
			{
				return;
			}
		}
		else
		{
			$photo = $modx->config['site_url'].'assets/snippets/webusers/userimages/default_user.jpg';
		}
		
		// EVENT: OnBeforeWebSaveUser
		foreach ($_POST as $name => $value)
		{
			$NewUser[$name] = $value;
		}
		$this->OnBeforeWebSaveUser($NewUser, array());
		
		// If all that crap checks out, now we can create the account.
		$newUser = "INSERT INTO ".$web_users." (`username`, `password`, `cachepwd`) VALUES ('".$username."', '".md5($password)."', '".$cachepwd."')";
		$createNewUser = $modx->db->query($newUser);
		
		if (!$createNewUser)
		{
			return $this->FormatMessage($this->LanguageArray[9]);
		} 
		
		$key = $modx->db->getInsertId();
		$NewUser['internalKey'] = $key; // pixelchutes
		
		$newUserAttr = "INSERT INTO ".$web_user_attributes.
		" (internalKey, fullname, email, phone, mobilephone, dob, gender, country, state, zip, fax, photo, comment) VALUES".
		" ('".$key."', '".$fullname."', '".$email."', '".$phone."', '".$mobilephone."', '".$dob."', '".$gender."', '".$country."', '".$state."', '".$zip."', '".$fax."', '".$photo."', '".$comment."')";
		$insertUserAttr = $modx->db->query($newUserAttr);
				
		if (!$insertUserAttr)
		{
			return $this->FormatMessage($this->LanguageArray[10]);
		}
		
		if (!empty($this->CustomFields) && $this->CustomFields !== '')
		{
			$extendedFieldValues = array();
			foreach ($this->CustomFields as $field)
			{
				$extendedFieldValues[$field] = $modx->db->escape($_POST[$field]);
			}
			$extendedFieldValues = implode("', '", $extendedFieldValues);
			$extendedFields = implode('`, `', $this->CustomFields);
			$extendedUserAttr = "INSERT INTO ".$this->CustomTable." (`internalKey`, `".$extendedFields."`) VALUES ('".$key."', '".$extendedFieldValues."')";
			$insertExtendedAttr = $modx->db->query($extendedUserAttr);
		
			if (!$insertExtendedAttr)
			{
				return $this->FormatMessage($this->LanguageArray[10]);
			}
		}
		
		$groups = str_replace(', ', ',', $groups);
		$GLOBALS['groupsArray'] = explode(',', $groups);
		
		// EVENT: OnBeforeAddToGroup
		$this->OnBeforeAddToGroup($GLOBALS['groupsArray']);
		if (count($groupArray > 0))
		{
			$groupsList = "'".implode("','", $GLOBALS['groupsArray'])."'";
			$groupNames = $modx->db->query("SELECT `id` FROM ".$webgroup_names." WHERE `name` IN (".$groupsList.")");
			if (!$groupNames)
			{
				return $this->FormatMessage($this->LanguageArray[11]);
			}
			else
			{
				while ($row = $modx->db->getRow($groupNames))
				{
					$webGroupId = $row['id'];
					$modx->db->query("REPLACE INTO ".$web_groups." (`webgroup`, `webuser`) VALUES ('".$webGroupId."', '".$key."')");
				}
			}
		}
		
		// EVENT: OnWebSaveUser
		$this->OnWebSaveUser('new', $NewUser);
		
		// Replace some placeholders in the Config websignupemail message.
		$messageTpl = $modx->config['websignupemail_message'];
		$myEmail = $modx->config['emailsender'];
        $emailSubject = $modx->config['emailsubject'];
		$siteName = $modx->config['site_name'];
		$siteURL = $modx->config['site_url'];
		
		$message = str_replace('[+uid+]', $username, $messageTpl);
        $message = str_replace('[+pwd+]', $password, $message);
        $message = str_replace('[+ufn+]', $fullname, $message);
        $message = str_replace('[+sname+]', $siteName, $message);
        $message = str_replace('[+semail+]', $myEmail, $message);
        $message = str_replace('[+surl+]', $siteURL, $message);
		foreach ($_POST as $name => $value)
		{
			$toReplace = '[+post.'.$name.'+]';
			$message = str_replace($toReplace, $value, $message);
		}

		// Bring in php mailer!
		$Register = new PHPMailer();
		$Register->CharSet = $modx->config['modx_charset'];
		$Register->From = $myEmail;
		$Register->FromName = $siteName;
		$Register->Subject = $emailSubject;
		$Register->Body = $message;
		$Register->AddAddress($email, $fullname);
		
		if (!$Register->Send())
		{
			return $this->FormatMessage($this->LanguageArray[12]);
		}
		
		// Add the list of administrators to be notified on new registration to a Blind Carbon Copy.
		if (isset($notify) && $notify !== '')
		{
			$emailList = str_replace(', ', ',', $notify);
			$emailArray = explode(',', $emailList);
			
			$notification = str_replace('[+uid+]', $username, $notifyTpl);
	        $notification = str_replace('[+ufn+]', $fullname, $notification);
	        $notification = str_replace('[+sname+]', $siteName, $notification);
	        $notification = str_replace('[+semail+]', $myEmail, $notification);
	        $notification = str_replace('[+surl+]', $siteURL, $notification);
			$notification = str_replace('[+uem+]', $email, $notification);
			foreach ($_POST as $name => $value)
			{
				$toReplace = '[+post.'.$name.'+]';
				$notification = str_replace($toReplace, $value, $notification);
			}
			
			$Notify = new PHPMailer();
			$Notify->CharSet = $modx->config['modx_charset'];
			
			foreach ($emailArray as $address)
			{
				$Notify->From = $email;
				$Notify->FromName = $fullname;
				$Notify->Subject = $notifySubject;
				$Notify->Body = $notification;
				$Notify->AddAddress($address);
				if (!$Notify->Send())
				{
					return $this->FormatMessage($Notify->ErrorInfo);
				}
				$Notify->ClearAddresses();
			}
		}
		$this->SessionHandler('destroy');
		$this->FormatMessage($this->LanguageArray[100].$modx->config['site_name']);
		return 'success';
	}
	
	
	/**
	 * PruneUsers will remove non-activated user accounts older than the number of days specified in $pruneDays.
	 *
	 * @param int $pruneDays The number of days to wait before removing non-activated users.
	 * @return void
	 * @author Scotty Delicious
	 */
	function PruneUsers($pruneDays)
	{
		global $modx;
		
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		$web_groups = $modx->getFullTableName('web_groups');
		
		$findDeadAccounts = $modx->db->query("SELECT * FROM ".$web_users." WHERE `cachepwd` != '' AND CHAR_LENGTH( `cachepwd` ) < 20");
		$deadAccounts = $this->FetchAll($findDeadAccounts);
		
		foreach ($deadAccounts as $user)
		{
			if ($user['cachepwd'] <= (time() - (60 * 60 * 24 * $pruneDays)) && $user['cachepwd'] != 0)
			{
				$deleteUser = $modx->db->query("DELETE FROM ".$web_users." WHERE `id`='".$user['id']."'");
				$deleteAttributes = $modx->db->query("DELETE FROM ".$web_user_attributes." WHERE `internalKey`='".$user['id']."'");
				$deleteFromGroups = $modx->db->query("DELETE FROM ".$web_groups." WHERE `webuser`='".$user['id']."'");
				
				// Email the Web Master regarding pruned accounts.
				$prunedMessage = str_replace('[+000+]', $user['username'], $this->LanguageArray[36]);
				$prunedMessage = str_replace('[+111+]', strftime('%A %B %d, %Y', $user['cachepwd']), $prunedMessage);
				$emailsender = $modx->config['emailsender'];
				
				$Pruned = new PHPMailer();
				$Pruned->CharSet = $modx->config['modx_charset'];
				$Pruned->From = $modx->config['emailsender'];
				$Pruned->FromName = 'WebLoginPE Pruning Agent';
				$Pruned->Subject = $this->LanguageArray[35];
				$Pruned->Body = $prunedMessage;
				$Pruned->AddAddress($emailsender);
				if (!$Pruned->Send())
				{
					return $this->FormatMessage($Pruned->ErrorInfo);
				}
				$Pruned->ClearAddresses();
			}
		}
	}
	
	
	/**
	 * Template takes a template parameter and checks to see if it is a chunk.
	 * If it is a chunk, returns the contents of the chunk, if it is not a chunk,
	 * tries to find a file of that name (or path) and gets its contents. If it
	 * is not a chunk or a file, returns the value passed as the parameter $chunk.
	 *
	 * @param string $chunk 
	 * @return string HTML block.
	 * @author Scotty Delicious
	 */
	function Template($chunk)
	{
		global $modx;
		
		$template = '';
		if ($modx->getChunk($chunk) != '')
		{
			$template = $modx->getChunk($chunk);
		}
		else if (is_file($chunk))
		{
			$template = file_get_contents($chunk);
		}
		else
		{
			$template = $chunk;
		}
		return $template;
	}
	
	
	/**
	 * SaveUserProfile
	 * Updates the web_user_attributes table for a given internalKey.
	 *
	 * @return void
	 * @author Scotty Delicious
	 */
	function SaveUserProfile($internalKey = '')
	{
		global $modx;
		if ($internalKey == '' || empty($internalKey))
		{
			$currentWebUser = $modx->getWebUserInfo($modx->getLoginUserID());
			$internalKey = $currentWebUser['internalKey'];
			$refreshSession = true;
		}
		else
		{
			$currentWebUser = $modx->getWebUserInfo($internalKey);
			$refreshSession = false;
		}
		
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		
		// EVENT: OnBeforeWebSaveUser
		$this->OnBeforeWebSaveUser(array(), array()); // pixelchutes
		if ( !empty($this->Report) ) return; // pixelchutes

		if (!empty($_POST['password']) && isset($_POST['password']) && isset($_POST['password_confirm'])) // pixelchutes
		{
			if ($_POST['password'] === $_POST['password_confirm']) // pixelchutes
			{
				if (md5($_POST['password']) === md5($modx->db->escape(strip_tags($_POST['password']))))
				{
					if (strlen($_POST['password']) > 5)
					{
						$passwordElement = "UPDATE ".$web_users." SET `password`='".md5($modx->db->escape($_POST['password']))."' WHERE `id`='".$internalKey."'";
						$saveMyPassword = $modx->db->query($passwordElement);
					}
					else
					{
						$this->FormatMessage($this->LanguageArray[3]);
						return;
					}
				}
				else
				{
					$this->FormatMessage($this->LanguageArray[4]);
					return;
				}
			}
			else
			{
				$this->FormatMessage($this->LanguageArray[2]);
				return;
			}
		}
		
		// Check for arrays and that "confirm" fields match.
		foreach ($_POST as $field => $value)
		{
			if (is_array($_POST[$field]))
			{
				$_POST[$field] = implode('||', $_POST[$field]);
			}
			
			$confirm = $field.'_confirm';
			if (isset($_POST[$confirm]))
			{
				if ($_POST[$field] !== $_POST[$confirm])
				{
					$error = $this->LanguageArray[2].' <br />';
					$fieldMessage .= str_replace('[+000+]', '"'.$field.'"', $error);
				}
			}
		}
		
		// If confirm fields were mismatched, throw this error:
		if (!empty($fieldMessage))
		{
			$err = $fieldMessage;
			unset($fieldMessage);
			return $this->FormatMessage($err);
		}
		
		$generalElementsArray = array('fullname','email','phone','mobilephone','dob','gender','country','state','zip','fax','photo','comment');
		$generalElementsUpdate = array();
		
		// CREDIT: Guillaume to delete data and for code optimisation
		foreach ($generalElementsArray as $field)
		{
			if ($field == 'photo')
			{
				if ($_FILES['photo']['name'] !== '' && !empty($_FILES['photo']['name']))
				{
					$_POST['photo'] = $this->CreateUserImage();
					if (!empty($this->Report))
					{
						return;
					}
				}
			}
			if ($field == 'dob' && trim($_POST['dob'])!='') // for not format an empty date else date is 0 (01-01-1970)
			{
				$_POST['dob'] = $this->MakeDateForDb($_POST['dob']);
			}
			if ($field!='photo' || ($_FILES['photo']['name'] !== '' && !empty($_FILES['photo']['name']))) // for update db with value and blank value (except if the field is 'photo')
{
	// CREDIT: Mike Reid (aka Pixelchutes) for the string escape code.
	$charset='"'.$modx->config['modx_charset'].'"';
	$generalElementsUpdate[] = " `".$field."` = '".$modx->db->escape(stripslashes(htmlentities(trim($_POST[$field]), ENT_QUOTES, $modx->config['modx_charset'])))."'";
}
		}
		
		if (!empty($this->CustomFields) && $this->CustomFields !== '')
		{
			$checkForExtended = "SELECT * FROM ".$this->CustomTable." WHERE `internalKey` = '".$internalKey."'";
			$isExtended = $modx->db->query($checkForExtended);
			$extendedRows = $modx->db->getRow($isExtended);
			
			if (!empty($extendedRows))
			{
				$extendedFieldValues = array();
				foreach ($this->CustomFields as $field)
				{
					// CREDIT: Mike Reid (aka Pixelchutes) for the string escape code.
					$extendedFieldValues[] = " `".$field."` = '".$modx->db->escape(stripslashes(trim($_POST[$field])))."'";
				}
				$this->OnBeforeWebSaveUser($generalElementsUpdate, $extendedFieldValues);
				
				$extendedUserAttr = "UPDATE ".$this->CustomTable." SET".implode(', ', $extendedFieldValues)." WHERE `internalkey` = '".$internalKey."'";
			}
			else
			{
				$extendedFieldValues = array();
				foreach ($this->CustomFields as $field)
				{
					// CREDIT: Mike Reid (aka Pixelchutes) for the string escape code.
					$charset=$modx->config['modx_charset'];
					$extendedFieldValues[$field] = $modx->db->escape(stripslashes(htmlentities(trim($_POST[$field]), ENT_QUOTES,$charset)));
				}
				$this->OnBeforeWebSaveUser($generalElementsUpdate, $extendedFieldValues);
				
				$extendedFieldValues = implode("', '", $extendedFieldValues);
				$extendedFields = implode('`, `', $this->CustomFields);
				$extendedUserAttr = "INSERT INTO ".$this->CustomTable." (`internalKey`, `".$extendedFields."`) VALUES ('".$internalKey."', '".$extendedFieldValues."')";
			}
		}
		// Prepare the query for General Elements
		$generalElementsSQL = "UPDATE ".$web_user_attributes." SET ".implode(', ', $generalElementsUpdate)." WHERE `internalkey` = '".$internalKey."'";
		
		// Execute the database queries.
		if( count($generalElementsUpdate) > 0 ) $saveMyProfile = $modx->db->query($generalElementsSQL);
		if (!empty($this->CustomFields) && $this->CustomFields !== '')
		{
			$insertExtendedAttr = $modx->db->query($extendedUserAttr);			
		}
		
		$this->User = $this->QueryDbForUser($currentWebUser['username']);
		$this->OnWebSaveUser('upd', $this->User); // Bugfix 7/10/2012 'update' should be 'upd' (see also save_web_user.processor.php)
		
		if ($refreshSession === true)
		{
			$this->SessionHandler('start');
		}
		
		$this->FormatMessage($this->LanguageArray[101]);
	}
	
	
	
	function RemoveProfile($internalKey)
	{
		global $modx;
		$deletedUser = $modx->getWebUserInfo($internalKey);
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		$web_groups = $modx->getFullTableName('web_groups');
		$active_users = $modx->getFullTableName('active_users');
		
		$deleteUser = $modx->db->query("DELETE FROM ".$web_users." WHERE `id`='".$internalKey."'");
		$deleteAttributes = $modx->db->query("DELETE FROM ".$web_user_attributes." WHERE `internalKey`='".$internalKey."'");
		$deleteFromGroups = $modx->db->query("DELETE FROM ".$web_groups." WHERE `webuser`='".$internalKey."'");
		$deleteFromActiveUsers = $modx->db->query("DELETE FROM ".$active_users." WHERE `internalKey`='-".$internalKey."'");
		
		if (!$deleteUser || !$deleteAttributes || !$deleteFromGroups || !$deleteFromActiveUsers)
		{
			return $this->FormatMessage($this->LanguageArray[13]);
		}
		$this->OnWebDeleteUser($internalKey, $deleteUser['username']);
		return;
	}
	
	
	/**
	 * RemoveUserProfile
	 * Deletes the table entries in web_users, web_user_attributes, and web_groups for a given internalKey.
	 *
	 * @return void
	 * @author Scotty Delicious
	 */
	function RemoveUserProfile()
	{
		global $modx;
		
		$currentWebUser = $modx->getWebUserInfo($modx->getLoginUserID());
		$internalKey = $currentWebUser['internalKey'];
		$this->RemoveProfile($internalKey);
		$this->SessionHandler('destroy');
		$this->FormatMessage($this->LanguageArray[102]);
		return;
	}
	
	
	function RemoveUserProfileManager($internalKey)
	{
		$this->RemoveProfile($internalKey);
		$this->FormatMessage($this->LanguageArray[102]);
		return;
	}
	
	
	/**
	 * View all users stored in the web_users table.
	 *
	 * @param string $userTemplate HTML template to display each web user.
	 * @return string HTML block containing all the users
	 * @author Scotty Delicious
	 */
	function ViewAllUsers($userTemplate, $outerTemplate, $listUsers)
	{
		global $modx;
		
		$web_users = $modx->getFullTableName('web_users');
		$fetchUsers = $modx->db->query("SELECT `username` FROM ".$web_users);
		$allUsers = $this->FetchAll($fetchUsers);
		
		if ($listUsers == '')
		{
			$listUsers = '[(site_name)] Members:default:default:username:ASC:';
		}

		if ($listUsers !== '')
		{
			$eachList = explode('||', $listUsers);
			foreach ($eachList as $eachListFormat)
			{
				$format = explode(':', $eachListFormat);
				$listName = $format[0];
				
				$listOuterTemplate = $format[1];
				if ($listOuterTemplate == 'default')
				{
					$listOuterTemplate = $outerTemplate;
				}
				else
				{
					$listOuterTemplate = $this->Template($listOuterTemplate);
				}
				//return $listOuterTemplate;
				
				$listTemplate = $format[2];
				if ($listTemplate == 'default')
				{
					$listTemplate = $userTemplate;
				}
				else
				{
					$listTemplate = $this->Template($listTemplate);
				}
				
				$listSortBy = $format[3];
				$listSortOrder = $format[4];
				if ($listSortOrder == 'DESC')
				{
					$listSortOrder = SORT_DESC;
				}
				else
				{
					$listSortOrder = SORT_ASC;
				}
				
				// Filters.
				if ($format[5] == '')
				{
					$format[5] = 'username()';
				}
				
				$CompleteUserList = array();
				foreach ($allUsers as $user)
				{
					$username = $user['username'];
					$CompleteUserList[$username] = $this->QueryDbForUser($username);
				}
				
				$allFilters = explode(',', str_replace(', ', ',', $format[5]));
				foreach ($allFilters as $theFilter)
				{
					$filters = explode('(', $theFilter);
					$filterBy = $filters[0];
					
					$sortNumerics = array('dob','lastlogin','thislogin','internalKey','logincount','blocked','blockeduntil','blockedafter','failedlogincount','gender');
					if (in_array($filterBy, $sortNumerics))
					{
						$typeFlag = SORT_NUMERIC;
					}
					else
					{
						$typeFlag = SORT_STRING;
					}
					
					$filterValue = str_replace(')', '', $filters[1]);
					if ($filterValue == '') unset($filterValue);
					
					foreach ($CompleteUserList as $theUser)
					{
						if ($filterBy == 'webgroup')
						{
							$web_groups = $modx->getFullTableName('web_groups');
							$webgroup_names = $modx->getFullTableName('webgroup_names');
							$findWebGroup = $modx->db->query("SELECT `id` FROM ".$webgroup_names." WHERE `name` = '".$filterValue."'");
							$limit = $modx->db->getRecordCount($findWebGroup);
							if ($limit == 0)
							{
								print 'There is no webgroup by the name "'.$filterValue.'"';
							}
							$webGroupIdSearch = $modx->db->getRow($findWebGroup);
							$webGroupId = $webGroupIdSearch['id'];
							
							$groupQuery = "SELECT * FROM ".$web_groups." WHERE `webgroup` = '".$webGroupId."' AND `webuser` = '".$theUser['internalKey']."'";
							$isMember = $modx->db->query($groupQuery);
							$limit = $modx->db->getRecordCount($isMember);
							if ($limit == 0)
							{
								$username = $theUser['username'];
								unset($CompleteUserList[$username]);
							}
						}
						else if ($filterBy == 'online')
						{
							$active_users = $modx->getFullTableName('active_users');
							$activityCheck = "SELECT * FROM ".$active_users." WHERE `internalKey` = '-".$theUser['internalKey']."'";
							$lastActive = $modx->db->query($activityCheck);
							$limit = $modx->db->getRecordCount($lastActive);
							if ($limit !== 0)
							{
								$userStatus = $modx->db->getRow($lastActive);
								if ($userStatus['lasthit'] >= time() - (60 * 15))
								{
									// Good, User is online and active
								}
								else
								{
									$username = $theUser['username'];
									unset($CompleteUserList[$username]);
								}
							}
							else
							{
								$username = $theUser['username'];
								unset($CompleteUserList[$username]);
							}
						}
						else
						{
							foreach ($theUser as $attribute => $value)
							{
								if (empty($theUser[$filterBy]) || $theUser[$filterBy] == '' && $filterBy !== 'webgroup')
								{
									$username = $theUser['username'];
									unset($CompleteUserList[$username]);
								}

								if (isset($filterValue) && $filterBy !== 'webgroup')
								{
									if ($theUser[$filterBy] !== '' && !empty($theUser[$filterBy]))
									{
										$isValue = strpos(strtolower($filterValue), strtolower($theUser[$filterBy]));
										$isValueAlt = strpos(strtolower($theUser[$filterBy]), strtolower($filterValue));
										if ($isValue === false && $isValueAlt === false)
										{
											$username = $theUser['username'];
											unset($CompleteUserList[$username]);
										}
									}
								}
							}
						}
					}
				}
				// SORT ARRAY
				$sortArray = array();
			    foreach($CompleteUserList as $username => $attributes)
				{
			        foreach($attributes as $field => $value)
					{
			            $sortArray[$field][$username] = $value;
			        }
			    }
				//List here
				if (is_array($sortArray[$listSortBy]))
				{
					$arrayMap = array_map('strtolower', $sortArray[$listSortBy]);
					array_multisort($arrayMap, $listSortOrder, $typeFlag, $CompleteUserList);
					
					foreach ($CompleteUserList as $theUser)
					{
						$user = $this->QueryDbForUser($theUser['username']);

						$active_users = $modx->getFullTableName('active_users');
						$activityCheck = "SELECT * FROM ".$active_users." WHERE `internalKey` = '-".$theUser['internalKey']."'";
						$lastActive = $modx->db->query($activityCheck);
						$limit = $modx->db->getRecordCount($lastActive);
						if ($limit !== 0)
						{
							$userStatus = $modx->db->getRow($lastActive);
							if ($userStatus['lasthit'] >= time() - (60 * 30))
							{
								$user['status'] = $this->LanguageArray[41];
							}
							else
							{
								$user['status'] = $this->LanguageArray[42];
							}
						}
						else
						{
							$user['status'] = $this->LanguageArray[42];
						}

						$eachProfile = $listTemplate;
						foreach ($user as $field => $value)
						{
							// $value = html_entity_decode($value);
							$needToSplit = strpos($value, '||');
							if ($needToSplit > 0)
							{
								$user[$field] = str_replace('||', ', ', $value);
							}

							$placeholder = '[+view.'.$field.'+]';

							if ($field == 'dob')
							{
								if ($value == 0)
								{
									$value = $this->LanguageArray[33];
									$eachProfile = str_replace('[+view.age+]', $value, $eachProfile);
								}
								else
								{
									$ageDecimal = ((time() - $value) / (60 * 60 * 24 * 365));
									$age = substr($ageDecimal, 0, strpos($ageDecimal, "."));
									$value = strftime('%m-%d-%Y', $value);
									$eachProfile = str_replace('[+view.age+]', $age, $eachProfile);
								}
							}
							else if ($field == 'lastlogin' || $field == 'thislogin')
							{
								if ($value == 0)
								{
									$value = $this->LanguageArray[33];
								}
								else
								{
									$value = strftime($this->DateFormat, $value);
								}
							}


							$eachProfile = str_replace($placeholder, $value, $eachProfile);
						}
						$displayUserTemplate .= $eachProfile;
					}
					$CombinedList = str_replace('[+view.title+]', $listName, $listOuterTemplate);
					$CombinedList = str_replace('[+view.list+]', $displayUserTemplate, $CombinedList);
					$displayUserTemplate = NULL;
				}
				$FinalDisplay .= $CombinedList;
			}
		}
		return $FinalDisplay;
	}
	
	
	/**
	 * ViewUserProfile displays sets the placeholders for the attributes of another site user
	 *
	 * @param string $username The username of the other user's profile to view
	 * @return void
	 * @author Scotty Delicious
	 */
	function ViewUserProfile($username,$inputHandler=array())
	{
		global $modx;
		
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		
		$findID = $modx->db->query("SELECT * FROM ".$web_users." WHERE `username` = '".$username."'");
		$userID = $modx->db->getRow($findID);
		$userID = $userID['id'];
		$viewUser = $modx->getWebUserInfo($userID);
		$modx->setPlaceholder('view.username', $viewUser['username']);
		$allFields = $modx->db->query("SELECT * FROM ".$web_user_attributes.", ".$this->CustomTable." WHERE ".$web_user_attributes.".`internalKey` = '".$userID."' AND ".$this->CustomTable.".`internalKey` = '".$userID."'");
		$limit = $modx->db->getRecordCount($allFields);
		
		if ($limit == 0)
		{
			$allFields = $modx->db->query("SELECT * FROM ".$web_user_attributes." WHERE ".$web_user_attributes.".`internalKey` = '".$userID."'");
		}
		
		$viewUser = $modx->db->getRow($allFields);
		
		$active_users = $modx->getFullTableName('active_users');
		$activityCheck = "SELECT * FROM ".$active_users." WHERE `internalKey` = '-".$viewUser['internalKey']."'";
		$lastActive = $modx->db->query($activityCheck);
		$limit = $modx->db->getRecordCount($lastActive);
		if ($limit !== 0)
		{
			$userStatus = $modx->db->getRow($lastActive);
			if ($userStatus['lasthit'] >= time() - (60 * 30))
			{
				$viewUser['status'] = $this->LanguageArray[41];
			}
			else
			{
				$viewUser['status'] = $this->LanguageArray[42];
			}
		}
		else
		{
			$viewUser['status'] = $this->LanguageArray[42];
		}
		
		foreach ($viewUser as $column => $setting)
		{
			// $setting = html_entity_decode($setting);
			$needToSplit = strpos($setting, '||');
			if ($needToSplit > 0)
			{
				$viewUser[$column] = str_replace('||', ', ', $setting);
			}
			
			$modx->setPlaceholder('view.'.$column, stripslashes($viewUser[$column]));
			
			if ($column == 'dob')
			{
				if ($setting == 0)
				{
					if ($this->Type !== 'manager')
					{
						$modx->setPlaceholder('view.dob', $this->LanguageArray[33]);
					}
					else
					{
						$modx->setPlaceholder('view.dob', '');
					}
					$modx->setPlaceholder('view.age', $this->LanguageArray[33]);
				}
				else
				{
					$ageDecimal = ((time() - $setting) / (60 * 60 * 24 * 365));
					$age = substr($ageDecimal, 0, strpos($ageDecimal, "."));
					$modx->setPlaceholder('view.dob', strftime('%m-%d-%Y', $viewUser['dob']));
					$modx->setPlaceholder('view.age', $age);
				}
			}
			if ($column == 'lastlogin')
			{
				$modx->setPlaceholder('view.lastlogin', strftime($this->DateFormat, $viewUser['lastlogin']));
			}
			if ($column == 'thislogin')
			{
				$modx->setPlaceholder('view.thislogin', strftime($this->DateFormat, $viewUser['thislogin']));
			}
			
			if ($this->Type !== 'manager')
			{
				$private = strpos($column, 'private');
				if ($private > 0)
				{
					if ($setting == 'on')
					{
						$fieldToReplace = str_replace('private', '', $column);
						$viewUser[$fieldToReplace] = $this->LanguageArray[38];
						$modx->setPlaceholder('view.'.$fieldToReplace, $this->LanguageArray[38]);
					}
				}// end private
			}
		}
		$modx->setPlaceholder('view.gender', $this->StringForGenderInt($viewUser['gender']));
		$modx->setPlaceholder('view.country', $this->StringForCountryInt($viewUser['country']));
		
		// Handle Special input placeholders.
		include 'assets/snippets/webusers/Default Forms/countryCodes.php';
		$inputHandler[9998] = str_replace('[+COUNTRYLABEL+]', $this->LanguageArray[39], $countryCodes);
		$inputHandler[9999] = str_replace('[+GENDERLABEL+]', $this->LanguageArray[40], $genderCodes);

		foreach ($inputHandler as $value)
		{
			$dataType = explode(':', $value);
			$label = $dataType[0];
			$DOMid = $dataType[1];
			$name = $dataType[2];
			$type = $dataType[3];
			$values = $dataType[4];

			if ($type == 'select multiple' || $type == 'select')
			{
				$ph = '';
				$ph .= '<label for="'.$DOMid.'" id="'.$DOMid.'Label">'.$label."\n";
				if ($type == 'select multiple')
				{
					$ph .= '<'.$type.' id="'.$DOMid.'" name="'.$name.'[]">'."\n";
				}
				else
				{
					$ph .= '<'.$type.' id="'.$DOMid.'" name="'.$name.'">'."\n";
				}
				$options = explode(',', $values);
				foreach ($options as $eachOption)
				{
					$option = explode('(', $eachOption);
					$option = str_replace(')', '', $option);
					
					if (isset($viewUser[$name]))
					{
						if (is_array($viewUser[$name]))
						{
							if (in_array($option[1], $viewUser[$name]))
							{
								$ph .= "\t".'<option selected="selected" value ="'.$option[1].'">'.$option[0].'</option>'."\n";

							}
							else
							{
								$ph .= "\t".'<option value ="'.$option[1].'">'.$option[0].'</option>'."\n";
							}
						}
						
						
						else 
						{
							if ($option[1] == $_POST[$name])
							{
								$ph .= "\t".'<option selected="selected" value ="'.$option[1].'">'.$option[0].'</option>'."\n";
							}
							
							else if ($option[1] == $viewUser[$name])
							{
								$ph .= "\t".'<option selected="selected" value ="'.$option[1].'">'.$option[0].'</option>'."\n";
							}

							else
							{
								$ph .= "\t".'<option value ="'.$option[1].'">'.$option[0].'</option>'."\n";
							}
						}
					}
					else
					{
						$ph .= "\t".'<option value ="'.$option[1].'">'.$option[0].'</option>'."\n";
					}
				}
				$ph .= '</select>'."\n";
				$ph .= '</label>'."\n";
				// Set the Placeholder
				$modx->setPlaceholder('form.'.$name, $ph);
			}

			if ($type == 'radio')
			{
				$ph = '';
				$ph .= '<label for="'.$DOMid.'" id="'.$DOMid.'Label">'.$label."\n";
				$ph .= '<div id="'.$DOMid.'Div">'."\n";
				$options = explode(',', $values);
				foreach ($options as $eachOption)
				{
					$option = explode('(', $eachOption);
					$option = str_replace(')', '', $option);
					if (isset($viewUser[$name]))
					{
						if ($option[1] == $viewUser[$name])
						{
							$ph .= '</span><input type="radio" id="'.$DOMid.$option[0].'" name="'.$name.'" value="'.$option[1].'" checked="checked" /><span class="'.$DOMid.'Span">'.$option[0].'</span>'."\n";
						}
						else
						{
							$ph .= '<input type="radio" id="'.$DOMid.$option[0].'" name="'.$name.'" value="'.$option[1].'" /><span class="'.$DOMid.'Span">'.$option[0].'</span>'."\n";
						}
					}
					else
					{
						$ph .= '<input type="radio" id="'.$DOMid.$option[0].'" name="'.$name.'" value="'.$option[1].'" /><span class="'.$DOMid.'Span">'.$option[0].'</span>'."\n";
					}
				}
				$ph .= '</div>'."\n";
				$ph .= '</label>'."\n";
				// Set the Placeholder
				$modx->setPlaceholder('form.'.$name, $ph);
			}

			if ($type == 'checkbox')
			{
				$ph = '';
				$ph .= '<label for="'.$DOMid.'" id="'.$DOMid.'Label">'.$label."\n";
				$options = explode(',', $values);
				foreach ($options as $eachOption)
				{
					$option = explode('(', $eachOption);
					$option = str_replace(')', '', $option);
					if (isset($viewUser[$name]))
					{
						if ($viewUser[$name] == 'on')
						{
							$ph .= $option[0].' <input type="checkbox" id="'.$DOMid.'" name="'.$name.'" checked="checked" />'."\n";
						}
						else
						{
							$ph .= $option[0].' <input type="checkbox" id="'.$DOMid.'" name="'.$name.'" />'."\n";
						}
					}
					else
					{
						$ph .= $option[0].' <input type="checkbox" id="'.$DOMid.'" name="'.$name.'" />'."\n";
					}
				}
				$ph .= '</label>'."\n";
				// Set the Placeholder
				$modx->setPlaceholder('form.'.$name, $ph);
			}
		}
		//$modx->setPlaceholder('form.gender', 'YOU FUckTard!');
		
		$viewUser = '';
	}
	
	
	/**
	 * SendMessageToUser allows site users to send email messages to each other.
	 *
	 * @return void.
	 * @author Scotty Delicious
	 */
	function SendMessageToUser()
	{
		global $modx;
		
		$me = $modx->getWebUserInfo($modx->db->escape($_POST['me']));
		$you = $modx->getWebUserInfo($modx->db->escape($_POST['you']));
		$subject = $modx->db->escape($_POST['subject']);
		$message = stripslashes(strip_tags($_POST['message']))."\n\n".$modx->config['site_name'];
		
		if (empty($subject) || $subject == '' || empty($message) || $message == '')
		{
			$this->FormatMessage($this->LanguageArray[0]);
			$this->ViewUserProfile($you['username']);
			return;
		}
		
		$EmailMessage = new PHPMailer();
		$EmailMessage->CharSet = $modx->config['modx_charset'];
		$EmailMessage->From = $me['email'];
		$EmailMessage->FromName = $me['fullname']." (".$me['username'].")";
		$EmailMessage->Subject = $subject;
		$EmailMessage->Body = $message;
		$EmailMessage->AddAddress($you['email'], $you['fullname']);

		if (!$EmailMessage->Send())
		{
			$this->FormatMessage($EmailMessage->ErrorInfo);
			$this->ViewUserProfile($you['username']);
			return;
		}
		$this->FormatMessage($this->LanguageArray[37].' "'.$you['username'].'"');
		$this->ViewUserProfile($you['username']);
		return;
	}
	
	/**
	 * ResetPassword
	 * Sets a random password | random key in the web_users.cachepwd field,
	 * then sends an email to the user with instructions and a URL to activate.
	 *
	 * @return void
	 * @author Raymond Irving
	 * @author Scotty Delicious
	 */
	function ResetPassword()
	{
		global $modx;
		
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		
		$email = $modx->db->escape(trim($_POST['email'])); // pixelchutes
		if ( empty($email) ) return $this->FormatMessage($this->LanguageArray[0]); // pixelchutes        
        $webpwdreminder_message = $modx->config['webpwdreminder_message'];
        $emailsubject = $modx->config['emailsubject'];
		$site_name = $modx->config['site_name'];
		$emailsender = $modx->config['emailsender'];
		
		$findUser = "SELECT * FROM ".$web_user_attributes.", ".$web_users." WHERE `email`='".$email."' AND `internalKey`=".$web_users.".`id`";
		$userInfo = $modx->db->query($findUser);
		$limit = $modx->recordCount($userInfo);
		
		if ($limit == 1)
		{
			// Reset the password and fire off an email to the user
			$newPassword = $this->GeneratePassword(10);
			$newPasswordKey = $this->GeneratePassword(10);
			$this->User = $modx->db->getRow($userInfo);
			$insertNewPassword = "UPDATE ".$web_users." SET cachepwd='".$newPassword."|".$newPasswordKey."' WHERE id='".$this->User['internalKey']."'";
			$setCachePassword = $modx->db->query($insertNewPassword);
			
			// build activation url
            if($_SERVER['SERVER_PORT']!='80')
			{
				$url = $modx->config['server_protocol'].'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$modx->makeURL($modx->documentIdentifier,'',"&service=activate&userid=".$this->User['id']."&activationkey=".$newPasswordKey);
			}
			else
			{
				//$url = $modx->config['server_protocol'].'://'.$_SERVER['SERVER_NAME'].$modx->makeURL($modx->documentIdentifier,'',"&service=activate&userid=".$this->User['id']."&activationkey=".$newPasswordKey);
				$url = $_SERVER['HTTP_REFERER']."&service=activate&userid=".$this->User['id']."&activationkey=".$newPasswordKey;
            }
			
			$message = str_replace("[+uid+]", $this->User['username'], $webpwdreminder_message);
            $message = str_replace("[+pwd+]", $newPassword, $message);
            $message = str_replace("[+ufn+]", $this->User['fullname'], $message);
            $message = str_replace("[+sname+]", $site_name, $message);
            $message = str_replace("[+semail+]", $emailsender, $message);
            $message = str_replace("[+surl+]", $url, $message);

			$Reset = new PHPMailer();
			$Reset->CharSet = $modx->config['modx_charset'];
			$Reset->From = $emailsender;
			$Reset->FromName = $site_name;
			$Reset->Subject = $emailsubject;
			$Reset->Body = $message;
			$Reset->AddAddress($email, $this->User['fullname']);

			if (!$Reset->Send())
			{
				return $this->FormatMessage($this->LanguageArray[12]);
			}
		}
		else
		{
			return $this->FormatMessage($this->LanguageArray[14]);
		}
		$this->FormatMessage($this->LanguageArray[103]);
		return;
	}
	
	
	/**
	 * ActivateUser
	 * Activates the user after they have requested to have their password reset.
	 *
	 * @return void
	 * @author Raymond Irving
	 * @author Scotty Delicious
	 */
	function ActivateUser()
	{
		global $modx;
		
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		
		$userid = $modx->db->escape($_REQUEST['userid']);
		$activationKey = $modx->db->escape($_REQUEST['activationkey']);
		$passwordKey = $modx->db->escape($_POST['activationpassword']);
		$newPassword = $modx->db->escape($_POST['newpassword']);
		$newPasswordConfirm = $modx->db->escape($_POST['newpassword_confirm']); // pixelchutes 1:55 AM 9/19/2007
		
		$findUser = "SELECT * FROM ".$web_users." WHERE id='".$userid."'";
		$userInfo = $modx->db->query($findUser);
        $limit = $modx->recordCount($userInfo);
		
		if ($limit !==1)
		{
			return $this->FormatMessage($this->LanguageArray[15]);
		}
		
		$this->User = $modx->db->getRow($userInfo);
		list($cachePassword, $cacheKey) = explode("|",$this->User['cachepwd']);
		
		if (($passwordKey !== $cachePassword) || ($activationKey !== $cacheKey))
		{
			return $this->FormatMessage($this->LanguageArray[16]);
		}
		
		if (!empty($newPassword) && isset($newPassword) && isset($newPasswordConfirm))
		{
			if ($newPassword === $newPasswordConfirm)
			{
				if (md5($newPassword) === md5($modx->db->escape($newPassword)))
				{
					if (strlen($newPassword) > 5)
					{
						$passwordElement = "UPDATE ".$web_users." SET `password`='".md5($modx->db->escape($newPassword))."', cachepwd='' WHERE `id`='".$this->User['id']."'";
						$saveMyPassword = $modx->db->query($passwordElement);
						
						$blocks = "UPDATE ".$web_user_attributes." SET `blocked`='0', `blockeduntil`='0' WHERE `internalKey`='".$this->User['id']."'";
						$unblockUser = $modx->db->query($blocks);
						
						// EVENT: OnWebChangePassword
						$this->OnWebChangePassword($this->User['id'], $this->User['username'], $newPassword);
					}
					else
					{
						return $this->FormatMessage($this->LanguageArray[3]);
					}
				}
				else
				{
					return $this->FormatMessage($this->LanguageArray[4]);
				}
			}
			else
			{
				return $this->FormatMessage($this->LanguageArray[2]);
			}
		}
		if(!$saveMyPassword || !$unblockUser)
		{ 
			return $this->FormatMessage($this->LanguageArray[17]);
		}
		$this->FormatMessage($this->LanguageArray[104]);
		return;
	}
	
	
	/**
	 * PlaceHolders
	 * Sets place holders using the MODx method setPlaceholder() for fields in web_user_attributes.
	 *
	 * @param string $dateFormat The strftime() format set in the calling script.
	 * @param array $inputHandler An array of inputs to... uhh... handle?
	 * @param string $UserImageSettings The specifications for the user image.
	 * @param string $MessageTemplate The template for $this->Report.
	 * @return void
	 * @author Scotty Delicious
	 */
	function PlaceHolders($inputHandler, $MessageTemplate = '[+wlpe.message.text+]')
	{
		global $modx;

		$this->MessageTemplate = $MessageTemplate;
		$CurrentUser = $modx->getWebUserInfo($modx->getLoginUserID());
		$modx->setPlaceholder('user.username', $CurrentUser['username']);
	
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		
		$extraFields = $modx->db->query("SELECT * FROM ".$this->CustomTable.", ".$web_user_attributes." WHERE ".$web_user_attributes.".`internalKey` = '".$modx->getLoginUserID()."' AND ".$this->CustomTable.".`internalKey` = '".$modx->getLoginUserID()."'");
		$limit = $modx->db->getRecordCount($extraFields);
		
		if ($limit == 0)
		{
			$extraFields = $modx->db->query("SELECT * FROM ".$web_user_attributes." WHERE ".$web_user_attributes.".`internalKey` = '".$modx->getLoginUserID()."'");
		}
		
		if ($modx->getLoginUserID() && $extraFields)
		{
			$CurrentUser = $modx->db->getRow($extraFields);
			if (!empty($CurrentUser))
			{
				foreach ($CurrentUser as $key => $value)
				{
					// $value = html_entity_decode($value);
					if ($key == 'id')
					{
						// Do Nothing, we don't need that shit in the placeholders.
					}
					else if ($key == 'dob')
					{
						// CREDIT : Guillaume for not format an empty date
						$value==0?'':$modx->setPlaceholder('user.'.$key, strftime('%m-%d-%Y', $value));
						$modx->setPlaceholder('user.age', strftime('%Y', time() - $value));
						
					}
					else if ($key == 'thislogin' || $key == 'lastlogin')
					{
						if ($value == 0)
						{
							$modx->setPlaceholder('user.'.$key, $this->LanguageArray[33]);
						}
						else
						{
							$modx->setPlaceholder('user.'.$key, strftime($this->DateFormat, $value));
						}
					}
					else if ($key == 'country')
					{
						$modx->setPlaceholder('user.country.integer', stripslashes($value));
						$modx->setPlaceholder('user.country', $this->StringForCountryInt($value));
					}
					else if ($key == 'gender')
					{
						$modx->setPlaceholder('user.gender.integer', stripslashes($value));
						$modx->setPlaceholder('user.gender', $this->StringForGenderInt($value));
					}
					else
					{
						$modx->setPlaceholder('user.'.$key, stripslashes($value));						
					}
					
					$needToSplit = strpos($value, '||');
					if ($needToSplit > 0)
					{
						$CurrentUser[$key] = explode('||', $value);
					}
				}
			}
		}
		$modx->setPlaceholder('user.defaultphoto', 'assets/snippets/webusers/userimages/default_user.jpg');
		$modx->setPlaceholder('request.userid', $_REQUEST['userid']);
		$modx->setPlaceholder('request.activationkey', $_REQUEST['activationkey']);
		$modx->setPlaceholder('form.captcha', 'manager/includes/veriword.php');
		
		// Handle Special input placeholders.
		include_once 'assets/snippets/webusers/Default Forms/countryCodes.php';
		$inputHandler[9998] = str_replace('[+COUNTRYLABEL+]', $this->LanguageArray[39], $countryCodes);
		$inputHandler[9999] = str_replace('[+GENDERLABEL+]', $this->LanguageArray[40], $genderCodes);

		foreach ($inputHandler as $value)
		{
			$dataType = explode(':', $value);
			$label = $dataType[0];
			$DOMid = $dataType[1];
			$name = $dataType[2];
			$type = $dataType[3];
			$values = $dataType[4];

			if ($type == 'select multiple' || $type == 'select')
			{
				$ph = '';
				$ph .= '<label for="'.$DOMid.'" id="'.$DOMid.'Label">'.$label."\n";
				if ($type == 'select multiple')
				{
					$ph .= '<'.$type.' id="'.$DOMid.'" name="'.$name.'[]">'."\n";
				}
				else
				{
					$ph .= '<'.$type.' id="'.$DOMid.'" name="'.$name.'">'."\n";
				}
				$options = explode(',', $values);
				foreach ($options as $eachOption)
				{
					$option = explode('(', $eachOption);
					$option = str_replace(')', '', $option);
					if (isset($CurrentUser[$name]))
					{
						if (is_array($CurrentUser[$name]))
						{
							if (in_array($option[1], $CurrentUser[$name]))
							{
								$ph .= "\t".'<option selected="selected" value ="'.$option[1].'">'.$option[0].'</option>'."\n";

							}
							else
							{
								$ph .= "\t".'<option value ="'.$option[1].'">'.$option[0].'</option>'."\n";
							}
						}
						else 
						{
							if ($option[1] == $_POST[$name])
							{
								$ph .= "\t".'<option selected="selected" value ="'.$option[1].'">'.$option[0].'</option>'."\n";
							}

							else if ($option[1] == $CurrentUser[$name])
							{
								$ph .= "\t".'<option selected="selected" value ="'.$option[1].'">'.$option[0].'</option>'."\n";
							}

							else
							{
								$ph .= "\t".'<option value ="'.$option[1].'">'.$option[0].'</option>'."\n";
							}
						}
					}
					else
					{
						$ph .= "\t".'<option value ="'.$option[1].'">'.$option[0].'</option>'."\n";
					}
				}
				$ph .= '</select>'."\n";
				$ph .= '</label>'."\n";
				// Set the Placeholder
				$modx->setPlaceholder('form.'.$name, $ph);
			}

			if ($type == 'radio')
			{
				$ph = '';
				$ph .= '<label for="'.$DOMid.'" id="'.$DOMid.'Label">'.$label."\n";
				$ph .= '<div id="'.$DOMid.'Div">'."\n";
				$options = explode(',', $values);
				foreach ($options as $eachOption)
				{
					$option = explode('(', $eachOption);
					$option = str_replace(')', '', $option);
					if (isset($CurrentUser[$name]))
					{
						if ($option[1] == $CurrentUser[$name])
						{
							$ph .= '</span><input type="radio" id="'.$DOMid.$option[0].'" name="'.$name.'" value="'.$option[1].'" checked="checked" /><span class="'.$DOMid.'Span">'.$option[0].'</span>'."\n";
						}
						else
						{
							$ph .= '<input type="radio" id="'.$DOMid.$option[0].'" name="'.$name.'" value="'.$option[1].'" /><span class="'.$DOMid.'Span">'.$option[0].'</span>'."\n";
						}
					}
					else
					{
						$ph .= '<input type="radio" id="'.$DOMid.$option[0].'" name="'.$name.'" value="'.$option[1].'" /><span class="'.$DOMid.'Span">'.$option[0].'</span>'."\n";
					}
				}
				$ph .= '</div>'."\n";
				$ph .= '</label>'."\n";
				// Set the Placeholder
				$modx->setPlaceholder('form.'.$name, $ph);
			}

			if ($type == 'checkbox')
			{
				$ph = '';
				$ph .= '<label for="'.$DOMid.'" id="'.$DOMid.'Label">'.$label."\n";
				$options = explode(',', $values);
				foreach ($options as $eachOption)
				{
					$option = explode('(', $eachOption);
					$option = str_replace(')', '', $option);
					if (isset($CurrentUser[$name]))
					{
						if ($CurrentUser[$name] == 'on')
						{
							$ph .= $option[0].' <input type="checkbox" id="'.$DOMid.'" name="'.$name.'" checked="checked" />'."\n";
						}
						else
						{
							$ph .= $option[0].' <input type="checkbox" id="'.$DOMid.'" name="'.$name.'" />'."\n";
						}
					}
					else
					{
						$ph .= $option[0].' <input type="checkbox" id="'.$DOMid.'" name="'.$name.'" />'."\n";
					}
				}
				$ph .= '</label>'."\n";
				// Set the Placeholder
				$modx->setPlaceholder('form.'.$name, $ph);
			}
		}
		
		if (!empty($_POST))
		{
			foreach ($_POST as $key => $value)
			{
				$modx->setPlaceholder('post.'.$key, $value);
			}
		}
	}                               
	
	
	/**
	 * RegisterScripts
	 * Uses the MODx regClientStartupScript() method to load the jQuery scripts for taconite.
	 * Optionally, it can load a custom js file (passed as a parameter.) if needed.
	 *
	 * @param string $customJs URL to a custom javascript file to be loaded.
	 * @return void
	 * @author Scotty Delicious
	 */
	function RegisterScripts($customJs = '')
	{
		global $modx;

		$modx->regClientJquery();
		$modx->regClientJqueryPlugin('form', 'jquery.form.js');
		$modx->regClientJqueryPlugin('taconite', 'jquery.taconite.js');

		if (isset($customJs))
		{
			$modx->regClientStartupScript($customJs);
		}
	}
	
	
	/**
	 * Authenticate
	 * Authenticates the user or sets failure counts on error.
	 *
	 * @return void
	 * @author Scotty Delicious
	 */
	function Authenticate()
	{
		global $modx;
		if (!empty($this->Report))
		{
			return; //There was an error in the last step
		}
		
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		
		$authenticate = $this->OnWebAuthentication();
		// check if there is a plugin to authenticate user and that said plugin authenticated the user
		// else use a simple authentication scheme comparing MD5 of password to database password.
	    if (!$authenticate || (is_array($authenticate) && !in_array(TRUE, $authenticate)))
		{
	        // check user password - local authentication
	        if ($this->User['password'] != md5($this->Password))
			{
				// in the case of a persistent login the password will already be a MD5 checksum.
				if ($this->User['password'] != $this->Password)
				{
					$this->LoginErrorCount = 1;
				}
	        }
	    }
		
		if ($this->LoginErrorCount == 1)
		{
	        $this->User['failedlogincount'] += $this->LoginErrorCount;
			
	        if ($this->User['failedlogincount'] >= $modx->config['failed_login_attempts'])
			{ //increment the failed login counter, and block!
	            $sql = "UPDATE ".$web_user_attributes." SET `failedlogincount`='0', `blockeduntil`='".(time() + ($modx->config['blocked_minutes'] * 60))."' WHERE `internalKey`='".$this->User['internalKey']."'";
	            $failLoginAndBlockUser = $modx->db->query($sql);
				$anError = str_replace('[+000+]', $modx->config['blocked_minutes'], $this->LanguageArray[19]);
				$this->FormatMessage($anError);
	        	return;
			}
			else
			{ //increment the failed login counter
	            $sql = "UPDATE ".$web_user_attributes." SET failedlogincount='".$this->User['failedlogincount']."' WHERE internalKey='".$this->User['internalKey']."'";
	            $updateFailedLoginCount = $modx->db->query($sql);
				
				// Get a fresh copy of the user attributes.
				$this->User = $this->QueryDbForUser($this->User['username']);
				
				$failedLoginCount = $this->User['failedlogincount'];
				
				$anError = $this->LanguageArray[20];
				$anError = str_replace('[+000+]', $failedLoginCount, $anError);
				$anError = str_replace('[+111+]', $modx->config['blocked_minutes'], $anError);
				$anError = str_replace('[+222+]', $modx->config['failed_login_attempts'], $anError);
				
				$this->LoginErrorCount = 0;
				return $this->FormatMessage($anError);
			}
			$this->SessionHandler('destroy');
	        return;
	    }
		
		$CurrentSessionID = session_id();

	    if(!isset($_SESSION['webValidated']))
		{
	        $isNowWebValidated = $modx->db->query("UPDATE ".$web_user_attributes." SET `failedlogincount` = 0, `logincount` = `logincount` + 1, `lastlogin` = `thislogin`, `thislogin` = ".time().", `sessionid` = '".$CurrentSessionID."' where internalKey='".$this->User['internalKey']."'");
	    }
		// Flag the account as "Activated" by deleting the timestamp in `cachepwd`
		$cacheTimestamp = $modx->db->query("UPDATE ".$web_users." SET `cachepwd`='' WHERE `id`='".$this->User['internalKey']."'");
 	}
	
	
	/**
	 * UserDocumentGroups
	 * Find the document groups that this user is a member of.
	 *
	 * @return void
	 * @author Raymond Irving
	 * @author Scotty Delicious
	 */
	function UserDocumentGroups()
	{
		global $modx;
		
		if (!empty($this->Report))
		{
			return; //There was an error in the last step
		}
		
		$web_groups = $modx->getFullTableName('web_groups');
		$webgroup_access = $modx->getFullTableName('webgroup_access');
		
		$documentGroups = '';
		$i = 0;
	    $sql = "SELECT uga.documentgroup FROM ".$web_groups." ug INNER JOIN ".$webgroup_access." uga ON uga.webgroup=ug.webgroup WHERE ug.webuser =".$this->User['internalKey'];
	    $currentUsersGroups = $modx->db->query($sql); 
	    while ($row = $modx->db->getRow($currentUsersGroups,'num')) $documentGroups[$i++] = $row[0];
	    $_SESSION['webDocgroups'] = $documentGroups;
	}
	
	/**
	 * UserWebGroups
	 * Find the web groups that this user is a member of (NB: contrary to previous documentation/comments the above function UserDocumentGroups() does gets the document groups)
	 *
	 * @return void
	 * @author Tim Spencer
	 */
	function UserWebGroups()
		{
		global $modx;

		$web_groups = $modx->getFullTableName('web_groups');
		$webgroup_names = $modx->getFullTableName('webgroup_names');

		$currentUsersWebGroups = $modx->db->query("SELECT {$webgroup_names}.* FROM {$web_groups}, {$webgroup_names} WHERE {$webgroup_names}.id = {$web_groups}.webgroup AND webuser = ".$this->User['internalKey']);

		$_SESSION['webUserGroupNames'] = array();
		while($row = $modx->db->getRow($currentUsersWebGroups)) $_SESSION['webUserGroupNames'][$row['id']] = $row['name'];
		}			

	
	
	/**
	 * LoginHomePage
	 * Redirect user to specified login page ($this->liHomeId).
	 * $this->liHomeId is an array, each document ID is queried.
	 * The user is redirected to the first page that they have permission to view.
	 * 
	 * If $this->liHomeId is empty, refresh the current page.
	 *
	 * @return void
	 * @author Raymond Irving
	 * @author Scotty Delicious
	 */
	function LoginHomePage()
	{
		global $modx;
		
		if (!empty($this->Report))
		{
			return; //There was an error in the last step
		}
		
		if ($this->Type == 'taconite')
		{
			return;
		}
		
		if (!empty($this->liHomeId))
		{
			if (is_array($this->liHomeId))
			{
				foreach($this->liHomeId as $id)
				{
		            $id = trim($id);
		            if ($modx->getPageInfo($id))
						{
							$url = $modx->makeURL($id);
					        $modx->sendRedirect($url,0,'REDIRECT_HEADER'); // CREDIT: Guillaume to redirect directely
					        return;
						}
		        }
			}
			else 
			{
				$url = $modx->makeURL($this->loHomeId);
		        $modx->sendRedirect($url,0,'REDIRECT_HEADER'); // CREDIT: Guillaume to redirect directely
		        return;
			}
		}
		else
		{
			$url = $modx->makeURL($modx->documentIdentifier);
	        $modx->sendRedirect($url,0,'REDIRECT_HEADER'); // CREDIT: Guillaume to redirect directely
		}
		return;
	}
	
	
	/**
	 * LogoutHomePage
	 * Redirect user to specified logout page ($this->loHomeId).
	 * If $this->loHomeId is empty, refresh the current page.
	 *
	 * @return void
	 * @author Raymond Irving
	 * @author Scotty Delicious
	 */
	function LogoutHomePage()
	{
		global $modx;
		
		if (!empty($this->Report))
		{
			return; //There was an error in the last step
		}
		
		if ($this->Type == 'taconite')
		{
			return;
		}
		
		if (!empty($this->loHomeId))
		{
			$url = $modx->makeURL($this->loHomeId);
	        $modx->sendRedirect($url,0,'REDIRECT_HEADER'); // CREDIT: Guillaume to redirect directely
	        return;
		}
		else
		{
			$url = $modx->makeURL($modx->documentIdentifier);
	        $modx->sendRedirect($url,0,'REDIRECT_HEADER'); // CREDIT: Guillaume to redirect directely
		}
		return;
	}
	
	
	/**
	 * SessionHandler
	 * Starts the user session on login success. Destroys session on error or logout.
	 *
	 * @param string $directive ('start' or 'destroy')
	 * @return void
	 * @author Raymond Irving
	 * @author Scotty Delicious
	 */
	function SessionHandler($directive)
	{
		global $modx;
				
		if (!empty($this->Report))
		{
			return; //There was an error in the last step
		}
		
		if ($directive == 'start') 
		{
			$_SESSION['webShortname'] = $this->Username; 
		    $_SESSION['webFullname'] = $this->User['fullname']; 
		    $_SESSION['webEmail'] = $this->User['email']; 
		    $_SESSION['webValidated'] = 1; 
		    $_SESSION['webInternalKey'] = $this->User['internalKey']; 
		    $_SESSION['webValid'] = base64_encode($this->Password); 
		    $_SESSION['webUser'] = base64_encode($this->Username); 
		    $_SESSION['webFailedlogins'] = $this->User['failedlogincount']; 
		    $_SESSION['webLastlogin'] = $this->User['lastlogin']; 
		    $_SESSION['webnrlogins'] = $this->User['logincount'];
		    $this->UserWebGroups(); // (TS)

			if ($_POST['rememberme'] == 'on')
			{
				$cookieName = 'WebLoginPE';
				$cookieValue = md5($this->User['username']).'|'.$this->User['password'];
				$cookieExpires = time() + (60 * 60 * 24 * 365 * 5); //5 years
				
				setcookie($cookieName, $cookieValue, $cookieExpires, '/', $_SERVER['SERVER_NAME'], 0);
		    }
			
			if (isset($_POST['stayloggedin']) && $_POST['stayloggedin'] !== '')
			{
				$cookieName = 'WebLoginPE';
				$cookieValue = md5($this->User['username']).'|'.$this->User['password'];
				$cookieExpires = time() + $_POST['stayloggedin'];
				
				setcookie($cookieName, $cookieValue, $cookieExpires, '/', $_SERVER['SERVER_NAME'], 0);
			}
		}
		
		if ($directive == 'destroy')
		{
			// if we were launched from the manager do NOT destroy session !!!
	        if (isset($_SESSION['mgrValidated']))
			{
	            unset($_SESSION['webShortname']);
	            unset($_SESSION['webFullname']);
	            unset($_SESSION['webEmail']);
	            unset($_SESSION['webValidated']);
	            unset($_SESSION['webInternalKey']);
	            unset($_SESSION['webValid']);
	            unset($_SESSION['webUser']);
	            unset($_SESSION['webFailedlogins']);
	            unset($_SESSION['webLastlogin']);
	            unset($_SESSION['webnrlogins']);
	            unset($_SESSION['webUsrConfigSet']);
	            unset($_SESSION['webUserGroupNames']);
	            unset($_SESSION['webDocgroups']);   
	
	         	$cookieName = 'WebLoginPE';
				setcookie($cookieName, '', time()-60, '/', $_SERVER['SERVER_NAME'], 0);
	        }
	        else
			{
	            if (isset($_COOKIE[session_name()]))
				{
	                setcookie(session_name(), '', 0, $modx->config['base_url']);
	            }
				
				$cookieName = 'WebLoginPE';
				setcookie($cookieName, '', time()-60, '/', $_SERVER['SERVER_NAME'], 0);
	            session_destroy();
	        }
		}
	}
	
	
	/**
	 * Set timestamp in `active_users`.`lasthit` to current time.
	 *
	 * @return void
	 * @access public
	 * @author Scotty Delicious
	 */
	function ActiveUsers()
	{
		global $modx;
		
		if (!$modx->getLoginUserID() || !empty($this->Report))
		{
			return;
		}
		$CurrentUser = $modx->getWebUserInfo($modx->getLoginUserID());
		
		if ($_SERVER['HTTP_X_FORWARD_FOR'])
		{
			$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		$active_users = $modx->getFullTableName('active_users');
		$activityCheck = "SELECT * FROM ".$active_users." WHERE `internalKey` = '-".$CurrentUser['internalKey']."'";
		$IamActive = $modx->db->query($activityCheck);
		$limit = $modx->db->getRecordCount($IamActive);
		if ($limit == 0)
		{
			$makeMeActive = $modx->db->query("INSERT INTO ".$active_users." (`internalKey`,`username`,`lasthit`,`id`,`action`,`ip`) VALUES ('-".$CurrentUser['internalKey']."','".$CurrentUser['username']."','".time()."','0','998','".$ip."')");
		}
		else
		{
			$updateActivity = $modx->db->query("UPDATE ".$active_users." SET `lasthit` = '".time()."', `ip` = '".$ip."' WHERE `internalKey` = '-".$CurrentUser['internalKey']."'");
		}
		
	}
	
	/**
	 * Set timestamp in `active_users` table to 0.
	 *
	 * @return void
	 * @access protected
	 * @author Scotty Delicious
	 */
	function StatusToOffline()
	{
		global $modx;
		$CurrentUser = $modx->getWebUserInfo($modx->getLoginUserID());
		$active_users = $modx->getFullTableName('active_users');
		$IamOffline = $modx->db->query("UPDATE ".$active_users." SET `lasthit` = '0' WHERE `internalKey` = '-".$CurrentUser['internalKey']."'");
	}
	
	
	/**
	 * QueryDbForUser
	 * Queries the web_users table for $_POST['username'].
	 *
	 * @param string $Username The username of the user to query for.
	 * @return void
	 * @author Raymond Irving
	 * @author Scotty Delicious
	 */
	function QueryDbForUser($Username)
	{
		global $modx;
		
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		
		$query = "SELECT * FROM ".$web_users.", ".$web_user_attributes.", ".$this->CustomTable." WHERE BINARY LOWER(".$web_users.".username) = '".strtolower($Username)."' AND ".$web_user_attributes.".`internalKey` = ".$web_users.".`id` AND ".$this->CustomTable.".`internalKey` = ".$web_users.".`id`";
		$query2 = "SELECT * FROM ".$web_users.", ".$web_user_attributes.", ".$this->CustomTable." WHERE(".$web_users.".username) = '".$Username."' AND ".$web_user_attributes.".`internalKey` = ".$web_users.".`id` AND ".$this->CustomTable.".`internalKey` = ".$web_users.".`id`";
		if (!$limit = $modx->db->getRecordCount($dataSource = $modx->db->query($query))) $limit = $modx->db->getRecordCount($dataSource = $modx->db->query($query2));
		
		if ($limit == 0)
		{
			$query = "SELECT * FROM ".$web_users.", ".$web_user_attributes." WHERE BINARY LOWER(".$web_users.".username) = '".strtolower($Username)."' AND ".$web_user_attributes.".`internalKey` = ".$web_users.".`id`";
			$query2 = "SELECT * FROM ".$web_users.", ".$web_user_attributes." WHERE(".$web_users.".username) = '".$Username."' AND ".$web_user_attributes.".`internalKey` = ".$web_users.".`id`";
			if (!$limit = $modx->db->getRecordCount($dataSource = $modx->db->query($query))) $limit = $modx->db->getRecordCount($dataSource = $modx->db->query($query2));
		}
		
		if ($limit == 0 || $limit > 1)
		{
			$this->User = false;
			return false;
		}
		else
		{
			return $modx->db->getRow($dataSource);
		}
	}
	
	
	/**
	 * UserIsBlocked
	 * Queries the web_user_attributes table to see if this user should
	 * be blocked. If the user IS blocked, prevent them from logging in.
	 *
	 * @return void
	 * @author Raymond Irving
	 * @author Scotty Delicious
	 */
	function UserIsBlocked()
	{
		global $modx;
		
		if (!empty($this->Report))
		{
			return; //There was an error in the last step
		}
		
		$web_users = $modx->getFullTableName('web_users');
		$web_user_attributes = $modx->getFullTableName('web_user_attributes');
		
		if ($this->User['failedlogincount'] >= $modx->config['failed_login_attempts'] && $this->User['blockeduntil'] > time())
		{
	        $this->SessionHandler('destroy');
	        return $this->FormatMessage($this->LanguageArray[22]);
	    }
	
		if ($this->User['failedlogincount'] >= $modx->config['failed_login_attempts'] && $this->User['blockeduntil'] < time())
		{// blocked due to number of login errors, but get to try again
	        $sql = "UPDATE ".$web_user_attributes." SET failedlogincount='0', blockeduntil='".(time()-1)."' where internalKey=".$this->User['internalKey'];
	        $updateFailedLoginCount = $modx->db->query($sql);
			return;
	    }
		
		if ($this->User['blocked'] == "1")
		{ // this user has been blocked by an admin, so no way he's loggin in!
	        $this->SessionHandler('destroy');
	        return $this->FormatMessage($this->LanguageArray[23]);
	    }
			
		if ($this->User['blockeduntil'] >= time())
		{ // this user has a block until date
			$blockedUntilTime = $this->User['blockeduntil'] - time();
			$UserIsBlockedUntil = $blockedUntilTime / 60;
			$blockedMinutes = substr($UserIsBlockedUntil, 0, strpos($UserIsBlockedUntil, "."));
			
			$this->SessionHandler('destroy');
			$anError = str_replace('[+000+]', $blockedMinutes, $this->LanguageArray[24]);
			return $this->FormatMessage($anError);
		}
	
		if($this->User['blockedafter'] > 0 && $this->User['blockedafter'] < time())
		{ // this user has a block after date
	        $this->SessionHandler('destroy');
	        return $this->FormatMessage($this->LanguageArray[23]);
	    }
	
		if (isset($modx->config['allowed_ip']))
		{
	        if (strpos($modx->config['allowed_ip'],$_SERVER['REMOTE_ADDR'])===false)
			{
	            return $this->FormatMessage($this->LanguageArray[25]);
	        }
	    }
	
		if (isset($modx->config['allowed_days']))
		{
	        $date = getdate();
	        $day = $date['wday']+1;
	        if (strpos($modx->config['allowed_days'], $day) === false)
			{
	            return $this->FormatMessage($this->LanguageArray[26]);
	        }        
	    }
		
	}
	
	
	/**
	 * MakeDateForDb
	 * Returns a UNIX timestamp for the string provided.
	 *
	 * @param string $date A date in the format MM-DD-YYY
	 * @return int Returns a UNIX timestamp for the date provided.
	 * @author Scotty Delicious
	 */
	function MakeDateForDb($date)
	{
		// $date is a string like 01-22-1975.
		if (strpos($date, '-'))
		{
			$dateArray = explode('-', $date);
		}
		else if (strpos($date, '/'))
		{
			$dateArray = explode('/', $date);
		}
		else
		{
			return $this->FormatMessage($this->LanguageArray[27]);
		}
		
		$dateArray = explode('-', $date);
		// $dateArray is somethink like [0]=01, [1]=22, [2]=1975
		// make a unix timestamp out of the original date string.
		$timestamp = mktime(0, 0, 0, $dateArray[0], $dateArray[1], $dateArray[2]);
		return $timestamp;
	}
	
	
	/**
	 * CreateUserImage
	 * Creates a 100px by 100px image for the user profile from a user uploaded image.
	 * This image is renamed to the username and moved to the webusers/userimages/ folder.
	 * The URL to this image is returned to be stored in the web_user_attributes table.
	 *
	 * @return string A URL to the user image created.
	 * @author Scotty Delicious
	 */
	function CreateUserImage()
	{
		global $modx;
		
		$imageAttributes = str_replace(', ', ',', $this->UserImageSettings);
		$imageAttributes = explode(',', $imageAttributes);
		
		if ($_FILES['photo']['size'] >= $imageAttributes[0])
		{
			$sizeInKb = round($imageAttributes[0] / 1024);
			$sizeError = str_replace('[+000+]', $sizeInKb, $this->LanguageArray[28]);
			return $this->FormatMessage($sizeError);
		}
		
		$userImage = $modx->config['base_path'].strtolower(str_replace(' ', '-', basename( $_FILES['photo']['name'])));
		if (!move_uploaded_file($_FILES['photo']['tmp_name'], $userImage))
		{
			return $this->FormatMessage($this->LanguageArray[29]);
		}
		
		// License and registration ma'am. I need to se an ID!
		if ($modx->getLoginUserID())
		{
			$currentWebUser = $modx->getWebUserInfo($modx->getLoginUserID());
			if ($this->Type == 'manager')
			{
				$currentWebUser['username'] = $_POST['username'];
			}
		}
		else
		{
			$currentWebUser['username'] = $this->Username;
			if ($this->Username == '' || empty($this->Username))
			{
				$currentWebUser['username'] = $_POST['username'];
			}
		}
		
		// Get dimensions and set new ones.
		list($width, $height) = getimagesize($userImage);
		$new_width = $imageAttributes[1];
		$new_height = $imageAttributes[2];
		
		$wm = $width / $new_width;
		$hm = $height / $new_height;
		if ($wm > 1 || $hm > 1) // (don't magnify a smaller image)
		{
			if ($wm > $hm) $new_height = $height / $wm;
			else $new_width = $width / $hm;
		}
		else { $new_width = $width; $new_height = $height; } // (must set the original size)

		// Resample
		$image_p = imagecreatetruecolor($new_width, $new_height);
		
		switch ($_FILES['photo']['type'])
		{
			case 'image/jpeg':
			case 'image/jpg': // added support for .jpg to the "default" support for .jpeg, so WLPE doesn't give a filetype error
			case 'image/pjpeg': // fix for IE6, which handles the .jpg filetype incorrectly
				$image = imagecreatefromjpeg($userImage);
				$ext = '.jpg';
				break;
				
			case 'image/gif':
				$image = imagecreatefromgif($userImage);
				imageSaveAlpha($image, true);
				imagesavealpha($image_p, true);
				$trans = imagecolorallocatealpha($image_p,255,255,255,127);
				imagefill($image_p,0,0,$trans);
				$ext = '.gif';
				break;
				
			case 'image/png':
				$image = imagecreatefrompng($userImage);
				imageSaveAlpha($image, true);
				imagesavealpha($image_p, true);
				$trans = imagecolorallocatealpha($image_p,255,255,255,127);
				imagefill($image_p,0,0,$trans);
				$ext = '.png';
				break;
				
			default	:
				return $this->FormatMessage($this->LanguageArray[30]);
				break;
		}
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		// Output
		$userImageFilePath = $modx->config['base_path'].'assets/snippets/webusers/userimages/'.str_replace(' ', '_', strtolower($currentWebUser['username'])).$ext;
		//$userImageFileURL = $modx->config['site_url'].'assets/snippets/webusers/userimages/'.str_replace(' ', '_', strtolower($currentWebUser['username'])).$ext;
		$userImageFileURL = 'assets/snippets/webusers/userimages/'.str_replace(' ', '_', strtolower($currentWebUser['username'])).$ext;
		
		switch ($_FILES['photo']['type'])
		{
			case 'image/jpeg':
				imagejpeg($image_p, $userImageFilePath, 100);
				break;
				
			case 'image/gif':
				imagegif($image_p, $userImageFilePath);
				break;
				
			case 'image/png':
				imagepng($image_p, $userImageFilePath, 0);
				break;
				
			default	:
				imagejpeg($image_p, $userImageFilePath, 100);
		}
		
		unlink($userImage);
		
		return $userImageFileURL;
	}
	
	
	/**
	 * StringForGenderInt
	 * Returns a string ('Male', 'Female', or 'Unknown') for the integer $genderInt (integer stored in web_user_attributes).
	 *
	 * @param int $genderInt (0, 1, or 2)
	 * @return string (0 = 'Unknown', 1 = 'Male', 2 = 'Female') 
	 * @author Scotty Delicious
	 */
	function StringForGenderInt($genderInt)
	{
		if ($genderInt == 1)
		{
			return 'Male';
		}
		else if ($genderInt == 2)
		{
			return 'Female';
		}
		else
		{
			return 'Unknown';
		}
		
	}
	
	
	/**
	 * StringForCountryInt
	 * Returns a string (the name of the country) for the integer $countryInt (integer stored in web_user_attributes).
	 *
	 * @param int $countryInt 
	 * @return string The name of the country
	 * @author Scotty Delicious
	 */
	function StringForCountryInt($countryInt)
	{
		switch ($countryInt)
		{
			case "1" : return 'Afghanistan'; break;
            case "2" : return 'Albania'; break;
            case "3" : return 'Algeria'; break;
            case "4" : return 'American Samoa'; break;
            case "5" : return 'Andorra'; break;
            case "6" : return 'Angola'; break;
            case "7" : return 'Anguilla'; break;
            case "8" : return 'Antarctica'; break;
            case "9" : return 'Antigua and Barbuda'; break;
            case "10" : return 'Argentina'; break;
            case "11" : return 'Armenia'; break;
            case "12" : return 'Aruba'; break;
            case "13" : return 'Australia'; break;
            case "14" : return 'Austria'; break;
            case "15" : return 'Azerbaijan'; break;
            case "16" : return 'Bahamas'; break;
            case "17" : return 'Bahrain'; break;
            case "18" : return 'Bangladesh'; break;
            case "19" : return 'Barbados'; break;
            case "20" : return 'Belarus'; break;
            case "21" : return 'Belgium'; break;
            case "22" : return 'Belize'; break;
            case "23" : return 'Benin'; break;
            case "24" : return 'Bermuda'; break;
            case "25" : return 'Bhutan'; break;
            case "26" : return 'Bolivia'; break;
            case "27" : return 'Bosnia and Herzegowina'; break;
            case "28" : return 'Botswana'; break;
            case "29" : return 'Bouvet Island'; break;
            case "30" : return 'Brazil'; break;
            case "31" : return 'British Indian Ocean Territory'; break;
            case "32" : return 'Brunei Darussalam'; break;
            case "33" : return 'Bulgaria'; break;
            case "34" : return 'Burkina Faso'; break;
            case "35" : return 'Burundi'; break;
            case "36" : return 'Cambodia'; break;
            case "37" : return 'Cameroon'; break;
            case "38" : return 'Canada'; break;
            case "39" : return 'Cape Verde'; break;
            case "40" : return 'Cayman Islands'; break;
            case "41" : return 'Central African Republic'; break;
            case "42" : return 'Chad'; break;
            case "43" : return 'Chile'; break;
            case "44" : return 'China'; break;
            case "45" : return 'Christmas Island'; break;
            case "46" : return 'Cocos (Keeling) Islands'; break;
            case "47" : return 'Colombia'; break;
            case "48" : return 'Comoros'; break;
            case "49" : return 'Congo'; break;
            case "50" : return 'Cook Islands'; break;
            case "51" : return 'Costa Rica'; break;
            case "52" : return 'Cote D&#39;Ivoire'; break;
            case "53" : return 'Croatia'; break;
            case "54" : return 'Cuba'; break;
            case "55" : return 'Cyprus'; break;
            case "56" : return 'Czech Republic'; break;
            case "57" : return 'Denmark'; break;
            case "58" : return 'Djibouti'; break;
            case "59" : return 'Dominica'; break;
            case "60" : return 'Dominican Republic'; break;
            case "61" : return 'East Timor'; break;
            case "62" : return 'Ecuador'; break;
            case "63" : return 'Egypt'; break;
            case "64" : return 'El Salvador'; break;
            case "65" : return 'Equatorial Guinea'; break;
            case "66" : return 'Eritrea'; break;
            case "67" : return 'Estonia'; break;
            case "68" : return 'Ethiopia'; break;
            case "69" : return 'Falkland Islands (Malvinas)'; break;
            case "70" : return 'Faroe Islands'; break;
            case "71" : return 'Fiji'; break;
            case "72" : return 'Finland'; break;
            case "73" : return 'France'; break;
            case "74" : return 'France, Metropolitan'; break;
            case "75" : return 'French Guiana'; break;
            case "76" : return 'French Polynesia'; break;
            case "77" : return 'French Southern Territories'; break;
            case "78" : return 'Gabon'; break;
            case "79" : return 'Gambia'; break;
            case "80" : return 'Georgia'; break;
            case "81" : return 'Germany'; break;
            case "82" : return 'Ghana'; break;
            case "83" : return 'Gibraltar'; break;
            case "84" : return 'Greece'; break;
            case "85" : return 'Greenland'; break;
            case "86" : return 'Grenada'; break;
            case "87" : return 'Guadeloupe'; break;
            case "88" : return 'Guam'; break;
            case "89" : return 'Guatemala'; break;
            case "90" : return 'Guinea'; break;
            case "91" : return 'Guinea-bissau'; break;
            case "92" : return 'Guyana'; break;
            case "93" : return 'Haiti'; break;
            case "94" : return 'Heard and Mc Donald Islands'; break;
            case "95" : return 'Honduras'; break;
            case "96" : return 'Hong Kong'; break;
            case "97" : return 'Hungary'; break;
            case "98" : return 'Iceland'; break;
            case "99" : return 'India'; break;
            case "100" : return 'Indonesia'; break;
            case "101" : return 'Iran (Islamic Republic of)'; break;
            case "102" : return 'Iraq'; break;
            case "103" : return 'Ireland'; break;
            case "104" : return 'Israel'; break;
            case "105" : return 'Italy'; break;
            case "106" : return 'Jamaica'; break;
            case "107" : return 'Japan'; break;
            case "108" : return 'Jordan'; break;
            case "109" : return 'Kazakhstan'; break;
            case "110" : return 'Kenya'; break;
            case "111" : return 'Kiribati'; break;
            case "112" : return 'Korea, Democratic People&#39;s Republic of'; break;
            case "113" : return 'Korea, Republic of'; break;
            case "114" : return 'Kuwait'; break;
            case "115" : return 'Kyrgyzstan'; break;
            case "116" : return 'Lao People&#39;s Democratic Republic'; break;
            case "117" : return 'Latvia'; break;
            case "118" : return 'Lebanon'; break;
            case "119" : return 'Lesotho'; break;
            case "120" : return 'Liberia'; break;
            case "121" : return 'Libyan Arab Jamahiriya'; break;
            case "122" : return 'Liechtenstein'; break;
            case "123" : return 'Lithuania'; break;
            case "124" : return 'Luxembourg'; break;
            case "125" : return 'Macau'; break;
            case "126" : return 'Macedonia, The Former Yugoslav Republic of'; break;
            case "127" : return 'Madagascar'; break;
            case "128" : return 'Malawi'; break;
            case "129" : return 'Malaysia'; break;
            case "130" : return 'Maldives'; break;
            case "131" : return 'Mali'; break;
            case "132" : return 'Malta'; break;
            case "133" : return 'Marshall Islands'; break;
            case "134" : return 'Martinique'; break;
            case "135" : return 'Mauritania'; break;
            case "136" : return 'Mauritius'; break;
            case "137" : return 'Mayotte'; break;
            case "138" : return 'Mexico'; break;
            case "139" : return 'Micronesia, Federated States of'; break;
            case "140" : return 'Moldova, Republic of'; break;
            case "141" : return 'Monaco'; break;
            case "142" : return 'Mongolia'; break;
            case "143" : return 'Montserrat'; break;
            case "144" : return 'Morocco'; break;
            case "145" : return 'Mozambique'; break;
            case "146" : return 'Myanmar'; break;
            case "147" : return 'Namibia'; break;
            case "148" : return 'Nauru'; break;
            case "149" : return 'Nepal'; break;
            case "150" : return 'Netherlands'; break;
            case "151" : return 'Netherlands Antilles'; break;
            case "152" : return 'New Caledonia'; break;
            case "153" : return 'New Zealand'; break;
            case "154" : return 'Nicaragua'; break;
            case "155" : return 'Niger'; break;
            case "156" : return 'Nigeria'; break;
            case "157" : return 'Niue'; break;
            case "158" : return 'Norfolk Island'; break;
            case "159" : return 'Northern Mariana Islands'; break;
            case "160" : return 'Norway'; break;
            case "161" : return 'Oman'; break;
            case "162" : return 'Pakistan'; break;
            case "163" : return 'Palau'; break;
            case "164" : return 'Panama'; break;
            case "165" : return 'Papua New Guinea'; break;
            case "166" : return 'Paraguay'; break;
            case "167" : return 'Peru'; break;
            case "168" : return 'Philippines'; break;
            case "169" : return 'Pitcairn'; break;
            case "170" : return 'Poland'; break;
            case "171" : return 'Portugal'; break;
            case "172" : return 'Puerto Rico'; break;
            case "173" : return 'Qatar'; break;
            case "174" : return 'Reunion'; break;
            case "175" : return 'Romania'; break;
            case "176" : return 'Russian Federation'; break;
            case "177" : return 'Rwanda'; break;
            case "178" : return 'Saint Kitts and Nevis'; break;
            case "179" : return 'Saint Lucia'; break;
            case "180" : return 'Saint Vincent and the Grenadines'; break;
            case "181" : return 'Samoa'; break;
            case "182" : return 'San Marino'; break;
            case "183" : return 'Sao Tome and Principe'; break;
            case "184" : return 'Saudi Arabia'; break;
            case "185" : return 'Senegal'; break;
            case "186" : return 'Seychelles'; break;
            case "187" : return 'Sierra Leone'; break;
            case "188" : return 'Singapore'; break;
            case "189" : return 'Slovakia (Slovak Republic)'; break;
            case "190" : return 'Slovenia'; break;
            case "191" : return 'Solomon Islands'; break;
            case "192" : return 'Somalia'; break;
            case "193" : return 'South Africa'; break;
            case "194" : return 'South Georgia and the South Sandwich Islands'; break;
            case "195" : return 'Spain'; break;
            case "196" : return 'Sri Lanka'; break;
            case "197" : return 'St. Helena'; break;
            case "198" : return 'St. Pierre and Miquelon'; break;
            case "199" : return 'Sudan'; break;
            case "200" : return 'Suriname'; break;
            case "201" : return 'Svalbard and Jan Mayen Islands'; break;
            case "202" : return 'Swaziland'; break;
            case "203" : return 'Sweden'; break;
            case "204" : return 'Switzerland'; break;
            case "205" : return 'Syrian Arab Republic'; break;
            case "206" : return 'Taiwan'; break;
            case "207" : return 'Tajikistan'; break;
            case "208" : return 'Tanzania, United Republic of'; break;
            case "209" : return 'Thailand'; break;
            case "210" : return 'Togo'; break;
            case "211" : return 'Tokelau'; break;
            case "212" : return 'Tonga'; break;
            case "213" : return 'Trinidad and Tobago'; break;
            case "214" : return 'Tunisia'; break;
            case "215" : return 'Turkey'; break;
            case "216" : return 'Turkmenistan'; break;
            case "217" : return 'Turks and Caicos Islands'; break;
            case "218" : return 'Tuvalu'; break;
            case "219" : return 'Uganda'; break;
            case "220" : return 'Ukraine'; break;
            case "221" : return 'United Arab Emirates'; break;
            case "222" : return 'United Kingdom'; break;
            case "223" : return 'United States'; break;
            case "224" : return 'United States Minor Outlying Islands'; break;
            case "225" : return 'Uruguay'; break;
            case "226" : return 'Uzbekistan'; break;
            case "227" : return 'Vanuatu'; break;
            case "228" : return 'Vatican City State (Holy See)'; break;
            case "229" : return 'Venezuela'; break;
            case "230" : return 'Viet Nam'; break;
            case "231" : return 'Virgin Islands (British)'; break;
            case "232" : return 'Virgin Islands (U.S.)'; break;
            case "233" : return 'Wallis and Futuna Islands'; break;
            case "234" : return 'Western Sahara'; break;
            case "235" : return 'Yemen'; break;
            case "236" : return 'Yugoslavia'; break;
            case "237" : return 'Zaire'; break;
            case "238" : return 'Zambia'; break;
            case "239" : return 'Zimbabwe'; break;
		}
	}
	
	
	/**
	 * Validate an email address by regex and MX reccord
	 *
	 * @param string $Email An email address.
	 * @return void
	 * @author Scotty Delicious
	 */
	function ValidateEmail($Email)
	{
		// Original: ^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$
         if (!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $Email)) // vhollo
		{ 
			return $this->FormatMessage('The Email address you provided does not appear to be a properly formatted address.');
		}
		
		/*
		TOO MANY PROBLEMS WITH THIS VERIFICATION. I WILL COME BACK TO IT LATER.
		list($Username, $Domain) = split("@", $Email);
		if (getmxrr($Domain, $MXHost))
		{
			$ConnectAddress = $MXHost[0]; 
		}
		else
		{
			$ConnectAddress = $Domain;
		}
		
		if ($Connect = @fsockopen($ConnectAddress, 25, $errno, $errstr, 30))
		{
			if (ereg("^220", $Out = fgets($Connect, 1024)))
			{
				fputs($Connect, "HELO $HTTP_HOST\r\n"); 
				$Out = fgets($Connect, 1024);
				fputs($Connect, "MAIL FROM: <{$Email}>\r\n"); 
				$From = fgets($Connect, 1024);
				fputs($Connect, "RCPT TO: <{$Email}>\r\n"); 
				$To = fgets($Connect, 1024);
				fputs($Connect, "QUIT\r\n"); 
				fclose($Connect);

				if (!ereg("^250", $From) || !ereg("^250", $To))
				{
					return $this->FormatMessage('Server rejected address');
				}
			}
			else
			{
				return $this->FormatMessage('No response from server');
			}
		}
		else
		{
			return $this->FormatMessage('Cannot connect to email server '.$ConnectAddress);
		}*/
		// If we got to this point, the email has passed our tests
		// We could return a message that the email is valid, but 
		// as a true pirate, I will just keep moving on.
	}
	
	
	/**
	 * GeneratePassword
	 * Generate a random password of (int $length). [a-z][A-Z][2-9].
	 *
	 * @param int $length 
	 * @return void
	 * @author Raymond Irving
	 * @author Scotty Delicious
	 */
	function GeneratePassword($length = 10)
	{
        $allowable_characters = "abcdefghjkmnpqrstuvxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";
        $ps_len = strlen($allowable_characters);
        mt_srand((double)microtime()*1000000);
        $pass = "";
        for($i = 0; $i < $length; $i++) {
            $pass .= $allowable_characters[mt_rand(0,$ps_len-1)];
        }
        return $pass;
	}
	
	
	/**
	 * Fetch all rows in a data source recursively
	 *
	 * @param string $ds A data source.
	 * @return array $all An array of the data source
	 * @author Scotty Delicious
	 */
	function FetchAll($ds)
	{
		global $modx;
		
		$all = array();
		while ($all[] = $modx->db->getRow($ds)) {}
		foreach ($all as $key => $value)
		{
			if (empty($all[$key]))
			{
				unset($all[$key]);
			}
		}
		return $all;
	}
	
	/*********************************************************
	 * System Events
	 ********************************************************/

	function OnBeforeWebLogin()
	{
		global $modx;

		$parameters = array(
			'username'		=> $this->Username,
			'password'		=> $this->Password,
			'rememberme'	=> $_POST['rememberme'],
			'stayloggedin'	=> $_POST['stayloggedin']
			);
		$modx->invokeEvent("OnBeforeWebLogin", $parameters);
	}

	function OnWebLogin()
	{
		global $modx;
		$parameters = array('user' => $this->User, 'username' => $this->User['username']); // SMF connector fix http://modxcms.com/forums/index.php?topic=26565.0 c/o tazzydemon
		$modx->invokeEvent('OnWebLogin', $parameters);
	}

	function OnWebAuthentication()
	{
		global $modx;

		$parameters = array(
		    'internalKey'	=> $this->User['internalKey'],
		    'username'      => $this->Username,
		    'form_password'	=> $this->Password,
		    'db_password'	=> $this->User['password'],
		    'rememberme'    => $_POST['rememberme'],
			'stayloggedin'	=> $_POST['stayloggedin']
		);
		$modx->invokeEvent('OnWebAuthentication', $parameters);
	}

	function OnBeforeWebSaveUser($Attributes = array(), $ExtendedFields = array())
	{
		global $modx;

		$parameters = array(
			'Attributes'	=> $Attributes,
			'ExtendedFields'=> $ExtendedFields
			);
		$modx->invokeEvent('OnBeforeWebSaveUser', $parameters);
	}

	function OnWebSaveUser($mode = 'new', $user = array())
	{
		global $modx;

		$parameters = array(
			'mode'	=> $mode,
			
			'user'	=> $user, // Use of this parameter is discouraged. It is non-standard.

			'username'		=> $user['username'],  // 1) SMF connector fix http://modxcms.com/forums/index.php?topic=26565.0
			'userpassword'	=> $user['password'],  // 2) Further items to bring this into line with save_web_user.processor.php
			'useremail'		=> $user['email'],
			'userfullname'	=> $user['fullname'],
			'userid'		=> $user['internalKey']
			);
		$modx->invokeEvent('OnWebSaveUser', $parameters);
	}
	
	function OnBeforeAddToGroup($groups = array())
	{
		global $modx;
		$parameters = array('groups' => $groups);
		$modx->invokeEvent('OnBeforeAddToGroup', $parameters);
	}
	
	function OnWebChangePassword($internalKey, $username, $newPassword)
	{
		global $modx; // pixelchutes 1:56 AM 9/19/2007
		$parameters = array(
			'internalKey'	=> $internalKey,
			'username'		=> $username,
			'password'		=> $newPassword
			);
        $modx->invokeEvent('OnWebChangePassword', $parameters);
	}
	
	function OnViewUserProfile($internalKey, $username, $viewerKey, $viewerName)
	{
		global $modx;
		$parameters = array(
			'internalKey'	=> $internalKey,
			'username'		=> $username,
			'viewerKey'		=> $viewerKey,
			'viewername'	=> $viewerName
			);
		$modx->invokeEvent('OnViewProfile', $parameters);
	}
	
	function OnWebDeleteUser($internalKey, $username)
	{
		global $modx;
		$parameters = array(
			'internalKey'	=> $internalKey,
			'username'		=> $username,
			'timestamp'		=> time()
			);
		$modx->invokeEvent('OnWebDeleteUser', $parameters);
	}
	
	function OnBeforeWebLogout()
	{
		global $modx;
		$parameters = array(
			'userid'		=> $_SESSION['webInternalKey'],
			'internalKey'	=> $_SESSION['webInternalKey'],
			'username'		=> $_SESSION['webShortname']
			);
		$modx->invokeEvent('OnBeforeWebLogout', $parameters);
	}
	
	function OnWebLogout($logoutparameters) // SMF connector fix http://modxcms.com/forums/index.php?topic=26565.0 c/o tazzydemon
	{
		global $modx;
		//$paramaters = array(
			//'userid'		=> $_SESSION['webInternalKey'],
			//'internalKey'	=> $_SESSION['webInternalKey'],
			//'username'		=> $_SESSION['webShortname']
		//	);
		$modx->invokeEvent('OnWebLogout', $logoutparameters);
	}
	
	
}
// end WebLoginPE Class

?>
