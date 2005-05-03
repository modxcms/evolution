<?php

// MySQL Dump Parser
// SNUFFKIN/ Alex 2004

class SqlParser {
	var $host, $dbname, $prefix, $user, $password, $mysqlErrors;
	var $conn, $installFailed, $sitename, $adminname, $adminpass;
	var $mode, $fileManagerPath, $imgPath, $imgUrl;
	var $dbVersion;

	function SqlParser($host, $user, $password, $db, $prefix='modx_', $adminname, $adminpass) {
		$this->host = $host;
		$this->dbname = $db;
		$this->prefix = $prefix;
		$this->user = $user;
		$this->password = $password;
		$this->adminpass = $adminpass;
		$this->adminname = $adminname;
		$this->ignoreDuplicateErrors = false;
	}

	function connect() {
		$this->conn = mysql_connect($this->host, $this->user, $this->password);
		mysql_select_db($this->dbname, $this->conn);

		$this->dbVersion = 3.23; // assume version 3.23
		if(function_exists("mysql_get_server_info")) {
			$ver = mysql_get_server_info();
			$this->dbMODx 	 = version_compare($ver,"4.0.2");
			$this->dbVersion = doubleval($ver);
		}
	}
	
	function process($filename) {
		// check to make sure file exists
		if (!file_exists($filename)) {
			$this->mysqlErrors[] = array("error" => "File '$filename' not found");
			$this->installFailed = true ;
			return false;
		}
		
		$fh = fopen($filename, 'r');
		$idata = '';
		
		while (!feof($fh)) {
			$idata .= fread($fh, 1024);
		}

		fclose($fh);
		$idata = str_replace("\r", '', $idata);

		// check if in upgrade mode
		if ($this->mode=="upd") {
			// remove non-upgradeable parts
			$s = strpos($idata,"non-upgrade-able[[");
			$e = strpos($idata,"]]non-upgrade-able")+17;
			$idata = str_replace(substr($idata,$s,$e-$s)," Removed non upgradeable items",$idata);  
		}
		
		// replace {} tags
		$idata = str_replace('{PREFIX}', $this->prefix, $idata);
		$idata = str_replace('{ADMIN}', $this->adminname, $idata);
		$idata = str_replace('{ADMINPASS}', $this->adminpass, $idata);
		$idata = str_replace('{IMAGEPATH}', $this->imagePath, $idata);
		$idata = str_replace('{IMAGEURL}', $this->imageUrl, $idata);
		$idata = str_replace('{FILEMANAGERPATH}', $this->fileManagerPath, $idata);
		

		$sql_array = split("\n\n", $idata);


		$num = 0;
		foreach($sql_array as $sql_entry) {
			$sql_do = trim($sql_entry, "\r\n; ");
			//$sql_do = str_replace('{PREFIX}', $this->prefix, $sql_do);
			//$sql_do = str_replace('{ADMIN}', $this->adminname, $sql_do);
			//$sql_do = str_replace('{ADMINPASS}', $this->adminpass, $sql_do);

			if (ereg('^\#', $sql_do)) continue;
			
			// strip out comments and \n for mysql 3.x
			if ($this->dbVersion <4.0) {
				$sql_do = preg_replace("~COMMENT.*[^']?'.*[^']?'~","",$sql_do);
				$sql_do = str_replace('\r', "", $sql_do);
				$sql_do = str_replace('\n', "", $sql_do);
			}
			
			
			$num = $num + 1;
			if ($sql_do) mysql_query($sql_do, $this->conn);
			if(mysql_error()) {
				// Ignore duplicate errors - Raymond 
				if ($this->ignoreDuplicateErrors){
					if (eregi('^duplicate key', mysql_error()) || (eregi('^alter', $sql_do) && eregi('^duplicate', mysql_error()))) continue;
				}
				// End Ignore duplicate
				$this->mysqlErrors[] = array("error" => mysql_error(), "sql" => $sql_do);
				$this->installFailed = true;
			}
		}
	}

	function close() {
		mysql_close($this->conn);
	}
}

?>