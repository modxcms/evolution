<?php
class DBAPI {
	var $conn;
	var $config;
	var $isConnected;

	function DBAPI($host='', $dbase='', $uid='', $pwd='', $pre=NULL, $charset='', $connection_method='SET CHARACTER SET') {
		$this->config['host'] = $host ? $host : $GLOBALS['database_server'];
		$this->config['dbase'] = $dbase ? $dbase : $GLOBALS['dbase'];
		$this->config['user'] = $uid ? $uid : $GLOBALS['database_user'];
		$this->config['pass'] = $pwd ? $pwd : $GLOBALS['database_password'];
		$this->config['charset'] = $charset ? $charset : $GLOBALS['database_connection_charset'];
		$this->config['connection_method'] =  $this->_dbconnectionmethod = (isset($GLOBALS['database_connection_method']) ? $GLOBALS['database_connection_method'] : $connection_method);
		$this->config['table_prefix'] = ($pre !== NULL) ? $pre : $GLOBALS['table_prefix'];
	}

	function connect($host = '', $dbase = '', $uid = '', $pwd = '', $tmp = 0) {
		global $modx;
		$uid     = $uid   ? $uid   : $this->config['user'];
		$pwd     = $pwd   ? $pwd   : $this->config['pass'];
		$host    = $host  ? $host  : $this->config['host'];
		$dbase   = $dbase ? $dbase : $this->config['dbase'];
		$dbase   = trim($dbase, '`'); // remove the `` chars
		$charset = $this->config['charset'];
		$connection_method = $this->config['connection_method'];
		$tstart = $modx->getMicroTime();
		$safe_count = 0;
		do {
			$this->conn = new mysqli($host, $uid, $pwd, $dbase);
			if ($this->conn->connect_error) {
				$this->conn = null;
				if (isset($modx->config['send_errormail']) && $modx->config['send_errormail'] !== '0') {
					if ($modx->config['send_errormail'] <= 2) {
						$logtitle    = 'Failed to create the database connection!';
						$request_uri = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES);
						$ua          = htmlspecialchars($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES);
						$referer     = htmlspecialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES);
						$modx->sendmail(array(
							'subject' => 'Missing to create the database connection! from ' . $modx->config['site_name'],
							'body' => "{$logtitle}\n{$request_uri}\n{$ua}\n{$referer}",
							'type' => 'text'
						));
					}
				}
				sleep(1);
				$safe_count++;
			}
		} while (!$this->conn && $safe_count<3);
		if (!$this->conn) {
			$modx->messageQuit("Failed to create the database connection!");
			exit;
		} else {
			$this->conn->query("{$connection_method} {$charset}");
			$tend = $modx->getMicroTime();
			$totaltime = $tend - $tstart;
			if ($modx->dumpSQL) {
				$modx->queryCode .= "<fieldset style='text-align:left'><legend>Database connection</legend>" . sprintf("Database connection was created in %2.4f s", $totaltime) . "</fieldset><br />";
			}
			$this->conn->set_charset($this->config['charset']);
			$this->isConnected = true;
			$modx->queryTime += $totaltime;
		}
	}

	function disconnect() {
		$this->conn->close();
		$this->conn = null;
		$this->isConnected = false;
	}

	function escape($s, $safecount=0) {
		$safecount++;
		if (1000<$safecount) exit("Too many loops '{$safecount}'");
		if (empty ($this->conn) || !is_object($this->conn)) {
			$this->connect();
		}
		if (is_array($s)) {
			if (count($s) === 0) {
				$s = '';
			} else {
				foreach ($s as $i=>$v) {
					$s[$i] = $this->escape($v, $safecount);
				}
			}
		} else {
			$s = $this->conn->escape_string($s);
		}
		return $s;
	}

	function query($sql) {
		global $modx;
		if (empty ($this->conn) || !is_object($this->conn)) {
			$this->connect();
		}
		$tstart = $modx->getMicroTime();
		if (!($result = $this->conn->query($sql))) {
			$modx->messageQuit("Execution of a query to the database failed - " . $this->getLastError(), $sql);
		} else {
			$tend = $modx->getMicroTime();
			$totaltime = $tend - $tstart;
			$modx->queryTime = $modx->queryTime + $totaltime;
			if ($modx->dumpSQL) {
				$debug = debug_backtrace();
				array_shift($debug);	
				$debug_path = array();
				foreach ($debug as $line) $debug_path[] = $line['function'];
				$debug_path = implode(' > ', array_reverse($debug_path));
				$modx->queryCode .= "<fieldset style='text-align:left'><legend>Query " . ($modx->executedQueries + 1) . " - " . sprintf("%2.2f ms", $totaltime*1000) . "</legend>";
				$modx->queryCode .= $sql . '<br><br>';
				if ($modx->event->name) $modx->queryCode .= 'Current Event  => ' . $modx->event->name . '<br>';
				if ($modx->event->activePlugin) $modx->queryCode .= 'Current Plugin => ' . $modx->event->activePlugin . '<br>';
				if ($modx->currentSnippet) $modx->queryCode .= 'Current Snippet => ' . $modx->currentSnippet . '<br>';
				if (stripos($sql, 'select')===0) $modx->queryCode .= 'Record Count => ' . $this->getRecordCount($result) . '<br>';
				else $modx->queryCode .= 'Affected Rows => ' . $this->getAffectedRows() . '<br>';
				$modx->queryCode .= 'Functions Path => ' . $debug_path . '<br>';
				$modx->queryCode .= "</fieldset><br />";
			}
			$modx->executedQueries = $modx->executedQueries + 1;
			return $result;
		}
	}

	function delete($from, $where='', $orderby='', $limit = '') {
		global $modx;
		if (!$from) {
			$modx->messageQuit("Empty \$from parameters in DBAPI::delete().");
		} else {
			$from = $this->replaceFullTableName($from);
			$where   = !empty($where)   ? (strpos(ltrim($where),   "WHERE")!==0    ? "WHERE {$where}"      : $where)   : '';
			$orderby = !empty($orderby) ? (strpos(ltrim($orderby), "ORDER BY")!==0 ? "ORDER BY {$orderby}" : $orderby) : '';
			$limit   = !empty($limit)   ? (strpos(ltrim($limit),   "LIMIT")!==0    ? "LIMIT {$limit}"      : $limit)   : '';
			return $this->query("DELETE FROM {$from} {$where} {$orderby} {$limit}");
		}
	}

	function select($fields = "*", $from = "", $where = "", $orderby = "", $limit = "") {
		global $modx;
		if (!$from) {
			$modx->messageQuit("Empty \$from parameters in DBAPI::select().");
		} else {
			$from = $this->replaceFullTableName($from);
			$where   = !empty($where)   ? (strpos(ltrim($where),   "WHERE")!==0    ? "WHERE {$where}"      : $where)   : '';
			$orderby = !empty($orderby) ? (strpos(ltrim($orderby), "ORDER BY")!==0 ? "ORDER BY {$orderby}" : $orderby) : '';
			$limit   = !empty($limit)   ? (strpos(ltrim($limit),   "LIMIT")!==0    ? "LIMIT {$limit}"      : $limit)   : '';
			return $this->query("SELECT {$fields} FROM {$from} {$where} {$orderby} {$limit}");
		}
	}

	function update($fields, $table, $where = "") {
		global $modx;
		if (!$table) {
			$modx->messageQuit("Empty \$table parameter in DBAPI::update().");
		} else {
			$table = $this->replaceFullTableName($table);
			if (is_array($fields)) {
				foreach ($fields as $key => $value) {
					$fields[$key] = "`{$key}` = '{$value}'";
				}
				$fields = implode(",", $fields);
			}
			$where = !empty($where) ? (strpos(ltrim($where), "WHERE")!==0 ? "WHERE {$where}" : $where) : '';
			return $this->query("UPDATE {$table} SET {$fields} {$where}");
		}
	}

	function insert($fields, $intotable, $fromfields = "*", $fromtable = "", $where = "", $limit = "") {
		global $modx;
		if (!$intotable) {
			$modx->messageQuit("Empty \$intotable parameters in DBAPI::insert().");
		} else {
			$intotable = $this->replaceFullTableName($intotable);
			if (!is_array($fields)) {
				$this->query("INSERT INTO {$intotable} {$fields}");
			} else {
				if (empty($fromtable)) {
					$fields = "(`".implode("`, `", array_keys($fields))."`) VALUES('".implode("', '", array_values($fields))."')";
					$rt = $this->query("INSERT INTO {$intotable} {$fields}");
				} else {
					$fromtable = $this->replaceFullTableName($fromtable);
					$fields = "(".implode(",", array_keys($fields)).")";
					$where = !empty($where) ? (strpos(ltrim($where), "WHERE")!==0 ? "WHERE {$where}" : $where) : '';
					$limit = !empty($limit) ? (strpos(ltrim($limit), "LIMIT")!==0 ? "LIMIT {$limit}" : $limit) : '';
					$rt = $this->query("INSERT INTO {$intotable} {$fields} SELECT {$fromfields} FROM {$fromtable} {$where} {$limit}");
				}
			}
			if (($lid = $this->getInsertId())===false) $modx->messageQuit("Couldn't get last insert key!");
			return $lid;
		}
	}
	
	function isResult($rs) {
		return is_object($rs);
	}

	function freeResult($rs) {
		$rs->free_result();
	}

	function numFields($rs) {
		return $rs->field_count;
	}

	function fieldName($rs,$col=0) {
		$field = $rs->fetch_field_direct($col);
		return $field->name;
	}

	function selectDb($name) {
		$this->conn->select_db($name);
	}


	function getInsertId($conn=NULL) {
		if (!is_object($conn)) $conn =& $this->conn;
		return $conn->insert_id;
	}

	function getAffectedRows($conn=NULL) {
		if (!is_object($conn)) $conn =& $this->conn;
		return $conn->affected_rows;
	}

	function getLastError($conn=NULL) {
		if (!is_object($conn)) $conn =& $this->conn;
		return $conn->error;
	}

	function getRecordCount($ds) {
		return (is_object($ds)) ? $ds->num_rows : 0;
	}

	function getRow($ds, $mode = 'assoc') {
		if (is_object($ds)) {
			if ($mode == 'assoc') {
				return $ds->fetch_assoc();
			} elseif ($mode == 'num') {
				return $ds->fetch_row();
			} elseif ($mode == 'object') {
				return $ds->fetch_object();
			} elseif ($mode == 'both') {
				return $ds->fetch_array(MYSQLI_BOTH);
			} else {
				global $modx;
				$modx->messageQuit("Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num' or 'both'.");
			}
		}
	}

	function getColumn($name, $dsq) {
		if (!is_object($dsq)) {
			$dsq = $this->query($dsq);
		}
		if ($dsq) {
			$col = array ();
			while ($row = $this->getRow($dsq)) {
				$col[] = $row[$name];
			}
			return $col;
		}
	}

	function getColumnNames($dsq) {
		if (!is_object($dsq)) {
			$dsq = $this->query($dsq);
		}
		if ($dsq) {
			$names = array ();
			$limit = $this->numFields($dsq);
			for ($i = 0; $i < $limit; $i++) {
				$names[] = $this->fieldName($dsq, $i);
			}
			return $names;
		}
	}

	function getValue($dsq) {
		if (!is_object($dsq)) {
			$dsq = $this->query($dsq);
		}
		if ($dsq) {
			$r = $this->getRow($dsq, "num");
			return $r[0];
		}
	}

	function getXML($dsq) {
		if (!is_object($dsq)) {
			$dsq = $this->query($dsq);
		}
		$xmldata = "<xml>\r\n<recordset>\r\n";
		while ($row = $this->getRow($dsq, "both")) {
			$xmldata .= "<item>\r\n";
			for ($j = 0; $line = each($row); $j++) {
				if ($j % 2) {
					$xmldata .= "<$line[0]>$line[1]</$line[0]>\r\n";
				}
			}
			$xmldata .= "</item>\r\n";
		}
		$xmldata .= "</recordset>\r\n</xml>";
		return $xmldata;
	}

	function getTableMetaData($table) {
		$metadata = false;
		if (!empty ($table)) {
			$sql = "SHOW FIELDS FROM $table";
			if ($ds = $this->query($sql)) {
				while ($row = $this->getRow($ds)) {
					$fieldName = $row['Field'];
					$metadata[$fieldName] = $row;
				}
			}
		}
		return $metadata;
	}

	function prepareDate($timestamp, $fieldType = 'DATETIME') {
		$date = '';
		if (!$timestamp === false && $timestamp > 0) {
			switch ($fieldType) {
				case 'DATE' :
					$date = date('Y-m-d', $timestamp);
					break;
				case 'TIME' :
					$date = date('H:i:s', $timestamp);
					break;
				case 'YEAR' :
					$date = date('Y', $timestamp);
					break;
				default :
					$date = date('Y-m-d H:i:s', $timestamp);
					break;
			}
		}
		return $date;
	}

	function getHTMLGrid($dsq, $params) {
		if (!is_object($dsq)) {
			$dsq = $this->query($dsq);
		}
		if ($dsq) {
			include_once MODX_MANAGER_PATH . 'includes/controls/datagrid.class.php';
			$grd = new DataGrid('', $dsq);

			$grd->noRecordMsg = $params['noRecordMsg'];

			$grd->columnHeaderClass = $params['columnHeaderClass'];
			$grd->cssClass = $params['cssClass'];
			$grd->itemClass = $params['itemClass'];
			$grd->altItemClass = $params['altItemClass'];

			$grd->columnHeaderStyle = $params['columnHeaderStyle'];
			$grd->cssStyle = $params['cssStyle'];
			$grd->itemStyle = $params['itemStyle'];
			$grd->altItemStyle = $params['altItemStyle'];

			$grd->columns = $params['columns'];
			$grd->fields = $params['fields'];
			$grd->colWidths = $params['colWidths'];
			$grd->colAligns = $params['colAligns'];
			$grd->colColors = $params['colColors'];
			$grd->colTypes = $params['colTypes'];
			$grd->colWraps = $params['colWraps'];

			$grd->cellPadding = $params['cellPadding'];
			$grd->cellSpacing = $params['cellSpacing'];
			$grd->header = $params['header'];
			$grd->footer = $params['footer'];
			$grd->pageSize = $params['pageSize'];
			$grd->pagerLocation = $params['pagerLocation'];
			$grd->pagerClass = $params['pagerClass'];
			$grd->pagerStyle = $params['pagerStyle'];
			return $grd->render();
		}
	}

	function makeArray($rs=''){
		if (!$rs) return false;
		$rsArray = array();
		while ($row = $this->getRow($rs)) {
			$rsArray[] = $row;
		}
		return $rsArray;
	}

	function getVersion() {
		return $this->conn->server_info;
	}

	function replaceFullTableName($str,$force=null) {
		$str = trim($str);
		$dbase  = trim($this->config['dbase'],'`');
		$prefix = $this->config['table_prefix'];
		if (!empty($force)) {
			$result = "`{$dbase}`.`{$prefix}{$str}`";
		} elseif (strpos($str,'[+prefix+]')!==false) {
			$result = preg_replace('@\[\+prefix\+\]([0-9a-zA-Z_]+)@', "`{$dbase}`.`{$prefix}$1`", $str);
		} else {
			$result = $str;
		}
		return $result;
	}

	function optimize($table_name) {
		$rs = $this->query("OPTIMIZE TABLE {$table_name}");
		if ($rs) {
			$rs = $this->query("ALTER TABLE {$table_name}");
		}
		return $rs;
	}

	function truncate($table_name) {
		$rs = $this->query("TRUNCATE {$table_name}");
		return $rs;
	}
}
?>