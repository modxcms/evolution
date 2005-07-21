<?php
/*	Datbase API object of MySQL
 *	Written by Raymond Irving June, 2005
 *
 */

class DBAPI {
	
	var $conn;
	var $config;
	var $isConnected;
	
	/**
	 *	@name:	DBAPI
	 *	 
	 */
	function DBAPI() {
		$this->config['host'] = $GLOBALS['database_server'];
		$this->config['dbase'] = $GLOBALS['dbase'];
		$this->config['user'] = $GLOBALS['database_user'];
		$this->config['pass'] = $GLOBALS['database_password'];
		$this->config['table_prefix'] = $GLOBALS['table_prefix'];		
	}

	/**
	 *	@name:	connect
	 *	 
	 */
	function connect($host='',$dbase='', $uid='',$pwd=''){
		global $modx;
		$uid = $uid ? $host:$this->config['user'];
		$pwd = $pwd ? $host:$this->config['pass'];
		$host = $host ? $host:$this->config['host'];
		$dbase = $host ? $host:$this->config['dbase'];
		$tstart = $modx->getMicroTime(); 
		if(@!$this->conn = mysql_connect($host, $uid, $pwd)) {
			$modx->messageQuit("Failed to create the database connection!");
			exit;
		} else {
			mysql_select_db($dbase);
			$tend = $modx->getMicroTime(); 
			$totaltime = $tend-$tstart;
			if($modx->dumpSQL) {
				$modx->queryCode .= "<fieldset style='text-align:left'><legend>Database connection</legend>".sprintf("Database connection was created in %2.4f s", $totaltime)."</fieldset><br />";
			}
			$this->isConnected = true;
			$this->queryTime = $this->queryTime+$totaltime;
		}	
	}

	
	/**
	 *	@name:	disconnect
	 *	 
	 */
	function disconnect() {
		@mysql_close($this->conn);
	}
	

	/**
	 *	@name:	query
	 *	@desc:	Mainly for internal use. 
	 *			Developers should use select, update, insert, delete where possible
	 */
	function query($sql) {
		global $modx;
		if(empty($this->conn)||!is_resource($this->conn)) {
			$this->connect();
		}
		$tstart = $modx->getMicroTime(); 
		if(!$result = @mysql_query($sql, $this->conn)) {
			$modx->messageQuit("Execution of a query to the database failed - ".$this->getLastError(), $sql);
		} else {
			$tend = $modx->getMicroTime(); 
			$totaltime = $tend-$tstart;
			$modx->queryTime = $modx->queryTime+$totaltime;
			if($modx->dumpSQL) {
				$modx->queryCode .= "<fieldset style='text-align:left'><legend>Query ".($this->executedQueries+1)." - ".sprintf("%2.4f s", $totaltime)."</legend>".$sql."</fieldset><br />";
			}
			$modx->executedQueries = $modx->executedQueries+1;
			return $result;
		}
	}

	
	/**
	 *	@name:	delete
	 *	 
	 */
	function delete($from, $where = "") {
		if(!$from) return false;
		else {
			$table = $from;
			$where = ($where != "") ? "WHERE $where" : "";
			return $this->query("DELETE FROM $table $where;");			
		}
	}


	/**
	 *	@name:	select
	 *	 
	 */
	function select($fields="*", $from="", $where="", $orderby="", $limit="") {
		if(!$from) return false;
		else {
			$table = $from;
			$where = ($where != "") ? "WHERE $where" : "";
			$orderby = ($orderby != "") ? "ORDER BY $orderby " : "";
			$limit = ($limit != "") ? "LIMIT $limit" : "";
			return $this->query("SELECT $fields FROM $table $where $orderby $limit;");	
		}
	}


	/**
	 *	@name:	update
	 *	 
	 */
	function update($fields, $table, $where="") {
		if(!$table) return false;
		else {
			if(!is_array($fields)) $flds = $fields;
			else {
				foreach($fields as $key=>$value) {
					if($flds) $flds .=",";
					$flds .= $key."=";
					$flds .= "'".$value."'";
				}
			}
			$where = ($where != "") ? "WHERE $where" : "";
			return $this->query("UPDATE $table SET $flds $where;");	
		}	
	}


	/**
	 *	@name:	insert
	 *	@desc:	returns either last id inserted or the result from the query
	 */
	function insert($fields, $intotable, $fromfields="*", $fromtable="", $where="", $limit="") {
		if(!$intotable) return false;
		else {
			if(!is_array($fields)) $flds = $fields;
			else {
				$keys = array_keys($fields);
				$values = array_values($fields);
				$flds = "(".implode(",",$keys).") ".
						(!$fromtable && $values ? "VALUES('".implode("','",$values)."')":"");
				if($fromtable) {
					$where = ($where != "") ? "WHERE $where" : "";
					$limit = ($limit != "") ? "LIMIT $limit" : "";
					$sql="SELECT $fromfields FROM $fromtable $where $limit";	
				}
			}
			$rt = $this->query("INSERT INTO $intotable $flds $sql;");	
			$lid = mysql_insert_id();
			return $lid ? $lid:$rt;
		}	
	}


	/**
	 *	@name:	getInsertId
	 *	 
	 */
	function getInsertId($conn='') {
		if ($conn) return mysql_insert_id($conn);
		else return mysql_insert_id($this->conn);
	}


	/**
	 *	@name:	getAffectedRows
	 *	 
	 */
	function getAffectedRows($conn) {
		if ($conn) return mysql_affected_rows($conn);
		else return mysql_affected_rows($this->conn);
	}
		
		
	/**
	 *	@name:	getLastError
	 *	 
	 */
	function getLastError(){
		return mysql_error();
	}


	/**
	 *	@name:	getRecordCount
	 *	 
	 */
	function getRecordCount($ds) {
		return mysql_num_rows($ds);
	}
	

	/**
	 *	@name:	getRow
	 *	@desc:	returns an array of column values		
	 *	@param:	$dsq - dataset
	 *	 
	 */
	function getRow($ds,$mode='assoc'){
		if($ds) {
			if($mode=='assoc') {
				return mysql_fetch_assoc($ds);
			}
			elseif($mode=='num') {
				return mysql_fetch_row($ds);
			}
			elseif($mode=='both') {
				return mysql_fetch_array($ds, MYSQL_BOTH);		
			}
			else {
				global $modx;
				$modx->messageQuit("Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num' or 'both'.");
			}
		}
	}
	

	/**
	 *	@name:	getColumn
	 *	@desc:	returns an array of the values found on colun $name
	 *	@param:	$dsq - dataset or query string	 
	 */
	function getColumn($name,$dsq){
		if(!is_resource($dsq)) $dsq = $this->query($dsq);
		if($dsq) {
			$col = array();
			while($row = $this->getRow($dsq)) {
				$col[] = $row[$name];
			}
			return $col;
		}	
	}

	/**
	 *	@name:	getColumnNames
	 *	@desc:	returns an array containing the column $name
	 *	@param:	$dsq - dataset or query string	 
	 */
	function getColumnNames($dsq){
		if(!is_resource($dsq)) $dsq = $this->query($dsq);
		if($dsq) {
			$names = array();
			$limit = mysql_num_fields($dsq);
			for($i=0;$i<$limit;$i++) {
				$names[] = mysql_field_name($dsq, $i);
			}
			return $col;
		}	
	}


	/**
	 *	@name:	getValue
	 *	@desc:	returns the value from the first column in the set
	 *	@param:	$dsq - dataset or query string	 
	 */
	function getValue($dsq){
		if(!is_resource($dsq)) $dsq = $this->query($dsq);
		if($dsq){
			$r = $this->getRow($dsq,"num");
			return $r[0];
		}
	}

	
	/**
	 *	@name:	getXML
	 *	@desc:	returns an XML formay of the dataset $ds
	 */
	function getXML($dsq){
		if(!is_resource($dsq)) $dsq = $this->query($dsq);
	 	$xmldata = "<xml>\r\n<recordset>\r\n";
		while($row = $this->getRow($dsq,"both")) { 
			$xmldata .= "<item>\r\n"; 
			for($j=0;$line=each($row);$j++) { 
				if($j%2) { 
					$xmldata .= "<$line[0]>$line[1]</$line[0]>\r\n"; 
				} 
			} 
			$xmldata .= "</item>\r\n"; 
		} 	
		$xmldata .= "</recordset>\r\n</xml>";
		return $xmldata;
	}

	/**
	 *	@name:	getHTMLGrid
	 *	@param:	$params: Data grid parameters 
	 *				columnHeaderClass
	 *				tableClass
	 *				itemClass
	 *				altItemClass
	 *				columnHeaderStyle
	 *				tableStyle
	 *				itemStyle
	 *				altItemStyle
	 *				columns
	 *				fields
	 *				colWidths
	 *				colAligns
	 *				colColors
	 *				colTypes
	 *				cellPadding
	 *				cellSpacing
	 *				header
	 *				footer
	 *				pageSize
	 *				pagerLocation
	 *				pagerClass
	 *				pagerStyle
	 * 
	 */
	function getHTMLGrid($dsq,$params){
		global $base_path;
		if(!is_resource($dsq)) $dsq = $this->query($dsq);
		if($dsq) {
			include_once $base_path."/manager/includes/controls/datagrid.class.php";
			$grd = new DataGrid('',$dsq);

			$grd->noRecordMsg		=$params['noRecordMsg'];

			$grd->columnHeaderClass	=$params['columnHeaderClass'];
			$grd->tableClass		=$params['tableClass'];
			$grd->itemClass			=$params['itemClass'];
			$grd->altItemClass		=$params['altItemClass'];

			$grd->columnHeaderStyle	=$params['columnHeaderStyle'];
			$grd->tableStyle		=$params['tableStyle'];
			$grd->itemStyle			=$params['itemStyle'];
			$grd->altItemStyle		=$params['altItemStyle'];

			$grd->columns			=$params['columns'];
			$grd->fields			=$params['fields'];
			$grd->colWidths			=$params['colWidths'];
			$grd->colAligns			=$params['colAligns'];
			$grd->colColors			=$params['colColors'];
			$grd->colTypes			=$params['colTypes'];

			$grd->cellPadding		=$params['cellPadding'];
			$grd->cellSpacing		=$params['cellSpacing'];
			$grd->header			=$params['header'];
			$grd->footer			=$params['footer'];
			$grd->pageSize			=$params['pageSize'];
			$grd->pagerLocation		=$params['pagerLocation'];
			$grd->pagerClass		=$params['pagerClass'];
			$grd->pagerStyle		=$params['pagerStyle'];
			return $grd->render();			
		}	
	}	
}

?>