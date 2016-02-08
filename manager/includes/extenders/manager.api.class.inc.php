<?php
/*
 * MODX Manager API Class
 * Written by Raymond Irving 2005
 *
 */

global $_PAGE; // page view state object. Usage $_PAGE['vs']['propertyname'] = $value;

// Content manager wrapper class
class ManagerAPI {
	
	var $action; // action directive

	function __construct(){
		global $action;
		$this->action = $action; // set action directive
	}
	
	function initPageViewState($id=0){
		global $_PAGE;
		$vsid = isset($_SESSION["mgrPageViewSID"]) ? $_SESSION["mgrPageViewSID"] : '';
		if($vsid!=$this->action) {
			$_SESSION["mgrPageViewSDATA"] = array(); // new view state
			$_SESSION["mgrPageViewSID"] = $id>0 ? $id:$this->action; // set id
		}
		$_PAGE['vs'] = &$_SESSION["mgrPageViewSDATA"]; // restore viewstate
	}

	// save page view state - not really necessary,
	function savePageViewState($id=0){
		global $_PAGE;
		$_SESSION["mgrPageViewSDATA"] = $_PAGE['vs'];
		$_SESSION["mgrPageViewSID"] = $id>0 ? $id:$this->action;
	}
	
	// check for saved form
	function hasFormValues() {
		if(isset($_SESSION["mgrFormValueId"])) {		
			if($this->action==$_SESSION["mgrFormValueId"]) {
				return true;
			}
			else {
				$this->clearSavedFormValues();
			}
		}
		return false;
	}	
	// saved form post from $_POST
	function saveFormValues($id=0){
		$_SESSION["mgrFormValues"] = $_POST;
		$_SESSION["mgrFormValueId"] = $id>0 ? $id:$this->action;
	}		
	// load saved form values into $_POST
	function loadFormValues(){
		if($this->hasFormValues()) {
			$p = $_SESSION["mgrFormValues"];
			foreach($p as $k=>$v) $_POST[$k]=$v;
			$this->clearSavedFormValues();
		}
	}
	// clear form post
	function clearSavedFormValues(){
		unset($_SESSION["mgrFormValues"]);
		unset($_SESSION["mgrFormValueId"]);	
	}
	
	function genHash($password, $seed='1')
	{ // $seed is user_id basically
		global $modx;
		
		if(isset($modx->config['pwd_hash_algo']) && !empty($modx->config['pwd_hash_algo']))
			$algorithm = $modx->config['pwd_hash_algo'];
		else $algorithm = 'UNCRYPT';
		
		$salt = md5($password . $seed);
		
		switch($algorithm)
		{
			case 'BLOWFISH_Y':
				$salt = '$2y$07$' . substr($salt,0,22);
				break;
			case 'BLOWFISH_A':
				$salt = '$2a$07$' . substr($salt,0,22);
				break;
			case 'SHA512':
				$salt = '$6$' . substr($salt,0,16);
				break;
			case 'SHA256':
				$salt = '$5$' . substr($salt,0,16);
				break;
			case 'MD5':
				$salt = '$1$' . substr($salt,0,8);
				break;
		}
		
		if($algorithm!=='UNCRYPT')
		{
			$password = sha1($password) . crypt($password,$salt);
		}
		else $password = sha1($salt.$password);
		
		$result = strtolower($algorithm) . '>' . md5($salt.$password) . substr(md5($salt),0,8);
		
		return $result;
	}
	
	function getUserHashAlgorithm($uid)
	{
		global $modx;
		$tbl_manager_users = $modx->getFullTableName('manager_users');
		$rs = $modx->db->select('password',$tbl_manager_users,"id='{$uid}'");
		$password = $modx->db->getValue($rs);
		
		if(strpos($password,'>')===false) $algo = 'NOSALT';
		else
		{
			$algo = substr($password,0,strpos($password,'>'));
		}
		return strtoupper($algo);
	}
	
	function checkHashAlgorithm($algorithm='')
	{
		$result = false;
		if (!empty($algorithm))
		{
			switch ($algorithm)
			{
				case 'BLOWFISH_Y':
					if (defined('CRYPT_BLOWFISH') && CRYPT_BLOWFISH == 1)
					{
						if (version_compare('5.3.7', PHP_VERSION) <= 0) $result = true;
					}
					break;
				case 'BLOWFISH_A':
					if (defined('CRYPT_BLOWFISH') && CRYPT_BLOWFISH == 1) $result = true;
					break;
				case 'SHA512':
					if (defined('CRYPT_SHA512') && CRYPT_SHA512 == 1) $result = true;
					break;
				case 'SHA256':
					if (defined('CRYPT_SHA256') && CRYPT_SHA256 == 1) $result = true;
					break;
				case 'MD5':
					if (defined('CRYPT_MD5') && CRYPT_MD5 == 1 && PHP_VERSION != '5.3.7') $result = true;
					break;
				case 'UNCRYPT':
					$result = true;
					break;
			}
		}
		return $result;
	}

	function getSystemChecksum($check_files) {
		$_ = array();
		$check_files = trim($check_files);
		$check_files = explode("\n", $check_files);
		foreach($check_files as $file) {
			$file = trim($file);
			$file = MODX_BASE_PATH . $file;
			if(!is_file($file)) continue;
			$_[$file]= md5_file($file);
		}
		return serialize($_);
	}
	
	function setSystemChecksum($checksum) {
		global $modx;
		$tbl_system_settings = $modx->getFullTableName('system_settings');
		$sql = "REPLACE INTO {$tbl_system_settings} (setting_name, setting_value) VALUES ('sys_files_checksum','{$checksum}')";
        $modx->db->query($sql);
	}
	
	function checkSystemChecksum() {
		global $modx;

		if(!isset($modx->config['check_files_onlogin']) || empty($modx->config['check_files_onlogin'])) return '0';
		
		$current = $this->getSystemChecksum($modx->config['check_files_onlogin']);
		if(empty($current)) return '0';
		
		if(!isset($modx->config['sys_files_checksum']) || empty($modx->config['sys_files_checksum']))
		{
			$this->setSystemChecksum($current);
			return '0';
		}
		if($current===$modx->config['sys_files_checksum']) $result = '0';
		else                                               $result = 'modified';

		return $result;
	}
}
