<?php if (!class_exists("PDO_PGSQL")) {
/** File PDO_pgsql.class.php		*
 *(C) Andrea Giammarchi [2005/10/13]	*/

// Requires PDOStatement_pgsql.class.php , drived by PDO.class.php file
#<builtin>#require_once('PDOStatement_pgsql.class.php');

/**
 * Class PDO_pgsql
 * 	This class is used from class PDO to manage a PostgreSQL database.
 *      Look at PDO.clas.php file comments to know more about PostgreSQL connection.
 * ---------------------------------------------
 * @Compatibility	>= PHP 4
 * @Dependencies	PDO.class.php
 * 			PDOStatement_pgsql.class.php
 * @Author		Andrea Giammarchi
 * @Site		http://www.devpro.it/
 * @Mail		andrea [ at ] 3site [ dot ] it
 * @Date		2005/10/13
 * @LastModified	2005/10/14 12:30
 * @Version		0.1 - tested
 */ 
class PDO_pgsql {
	
	/**
	 * 'Private' variables:
	 *	__connection:Resource		Database connection
         *	__dbinfo:String			Database connection params
         *      __persistent:Boolean		Connection mode, is true on persistent, false on normal (deafult) connection
         *      __errorCode:String		Last error code
         *      __errorInfo:Array		Detailed errors
	 *      __result:Resource		Last query resource
	 */
	var $__connection;
	var $__dbinfo;
	var $__persistent = false;
	var $__errorCode = '';
	var $__errorInfo = Array('');
	var $__result = null;
	
	/**
	 * Public constructor:
	 *	Checks connection and database selection
         *       	new PDO_pgsql( &$host:String, &$db:String, &$user:String, &$pass:String )
	 * @Param	String		Database connection params
	 */
	function PDO_pgsql(&$string_dsn) {
		if(!@$this->__connection = &pg_connect($string_dsn))
			$this->__setErrors('DBCON', true);
		else
			$this->__dbinfo = &$string_dsn;
	}
	
	/** NOT NATIVE BUT MAYBE USEFULL FOR PHP < 5.1 PDO DRIVER
	 * Public method
         * Calls pg_close function.
	 *	this->close( Void ):Boolean
         * @Return	Boolean		True on success, false otherwise
	 */
	function close() {
		$result = is_resource($this->__connection);
		if($result)
			pg_close($this->__connection);
		return $result;
	}
	
	/**
	 * Public method:
	 *	Returns a code rappresentation of an error
         *       	this->errorCode( void ):String
         * @Return	String		String rappresentation of the error
	 */
	function errorCode() {
		return $this->__errorCode;
	}
	
	/**
	 * Public method:
	 *	Returns an array with error informations
         *       	this->errorInfo( void ):Array
         * @Return	Array		Array with 3 keys:
         * 				0 => error code
         *                              1 => error number
         *                              2 => error string
	 */
	function errorInfo() {
		return $this->__errorInfo;
	}
	
	/**
	 * Public method:
	 *	Excecutes a query and returns affected rows
         *       	this->exec( $query:String ):Mixed
         * @Param	String		query to execute
         * @Return	Mixed		Number of affected rows or false on bad query.
	 */
	function exec($query) {
		$result = 0;
		$this->__uquery($query);
		if(!is_null($this->__result))
			$result = pg_affected_rows($this->__result);
		if(is_null($result))
			$result = false;
		return $result;
	}
	
	/** NOT REALLY SUPPORTED, returned value is not last inserted id
	 * Public method:
	 *	Returns pg_last_oid function
         *       	this->lastInsertId( void ):String
         * @Return	String		OID returned from Postgre
	 */
	function lastInsertId() {
		$result = 0;
		if(!is_null($this->__result))
			$result =  pg_last_oid($this->__result);
		return $result;
	}
	
	/**
	 * Public method:
	 *	Returns a new PDOStatement
         *       	this->prepare( $query:String, $array:Array ):PDOStatement
         * @Param	String		query to prepare
         * @Param	Array		this variable is not used but respects PDO original accepted parameters
         * @Return	PDOStatement	new PDOStatement to manage
	 */
	function prepare($query, $array = Array()) {
		return new PDOStatement_pgsql($query, $this->__connection, $this->__dbinfo);
	}
	
	/**
	 * Public method:
	 *	Executes directly a query and returns an array with result or false on bad query
         *       	this->query( $query:String ):Mixed
         * @Param	String		query to execute
         * @Return	Mixed		false on error, array with all info on success
	 */
	function query($query) {
		$query = pg_prepare($this->__connection, "__pdo_query__", $query);
		$query = pg_execute($this->__connection, "__pdo_query__");
		$this->__errorCode = &$query->state;
		if($query) {
			$result = Array();
			while($r = pg_fetch_assoc($query))
				array_push($result, $r);
		}
		else {
			$result = false;
			$this->__setErrors('SQLER');
		}
		return $result;
	}
	
	/**
	 * Public method:
	 *	Quotes correctly a string for this database
         *       	this->quote( $string:String ):String
         * @Param	String		string to quote
         * @Return	String		a correctly quoted string
	 */
	function quote($string) {
		return ("'".pg_escape_string($string)."'");
	}
	
	
	// NOT TOTALLY SUPPORTED PUBLIC METHODS
        /**
	 * Public method:
	 *	Quotes correctly a string for this database
         *       	this->getAttribute( $attribute:Integer ):Mixed
         * @Param	Integer		a constant [	PDO_ATTR_SERVER_INFO,
         * 						PDO_ATTR_SERVER_VERSION,
         *                                              PDO_ATTR_CLIENT_VERSION,
         *                                              PDO_ATTR_PERSISTENT	]
         * @Return	Mixed		correct information or false
	 */
	function getAttribute($attribute) {
		$result = false;
		switch($attribute) {
			case PDO_ATTR_SERVER_INFO:
				$result = pg_parameter_status($this->__connection, 'server_encoding');
				break;
			case PDO_ATTR_SERVER_VERSION:
				$result = pg_parameter_status($this->__connection, 'server_version');
				break;
			case PDO_ATTR_CLIENT_VERSION:
				$result = pg_parameter_status($this->__connection, 'server_version');
				$result .= ' '.pg_parameter_status($this->__connection, 'client_encoding');
				break;
			case PDO_ATTR_PERSISTENT:
				$result = $this->__persistent;
				break;
		}
		return $result;
	}
	
	/**
	 * Public method:
	 *	Sets database attributes, in this version only connection mode.
         *       	this->setAttribute( $attribute:Integer, $mixed:Mixed ):Boolean
         * @Param	Integer		PDO_* constant, in this case only PDO_ATTR_PERSISTENT
         * @Param	Mixed		value for PDO_* constant, in this case a Boolean value
         * 				true for permanent connection, false for default not permament connection
         * @Return	Boolean		true on change, false otherwise
	 */
	function setAttribute($attribute, $mixed) {
		$result = false;
		if($attribute === PDO_ATTR_PERSISTENT && $mixed != $this->__persistent) {
			$result = true;
			$this->__persistent = (boolean) $mixed;
			pg_close($this->__connection);
			if($this->__persistent === true)
				$this->__connection = &pg_connect($this->__dbinfo);
			else
				$this->__connection = &pg_pconnect($this->__dbinfo);
		}
		return $result;
	}
	
	
	// UNSUPPORTED PUBLIC METHODS
	function beginTransaction() {
		return false;
	}
	
	function commit() {
		return false;
	}
	
	function rollBack() {
		return false;
	}
	
	
	// PRIVATE METHODS [ UNCOMMENTED ]
	function __setErrors($er) {
		if(!is_string($this->__errorCode))
			$errno = $this->__errorCode;
		if(!is_resource($this->__connection)) {
			$errno = 1;
			$errst = pg_last_error();
		}
		else {
			$errno = 1;
			$errst = pg_last_error($this->__connection);
		}
		$this->__errorCode = &$er;
		$this->__errorInfo = Array($this->__errorCode, $errno, $errst);
	}
	
	function __uquery(&$query) {
		if(!@$this->__result = pg_query($this->__connection, $query)) {
			$this->__setErrors('SQLER');
			$this->__result = null;
		}
		return $this->__result;
	}
}
?><?php
/** File PDOStatement_pgsql.class.php	*
 *(C) Andrea Giammarchi [2005/10/13]	*/

/**
 * Class PDOStatement_pgsql
 * 	This class is used from class PDO_pgsql to manage a PostgreSQL database.
 *      Look at PDO.clas.php file comments to know more about PostgreSQL connection.
 * ---------------------------------------------
 * @Compatibility	>= PHP 4
 * @Dependencies	PDO.class.php
 * 			PDO_pgsql.class.php
 * @Author		Andrea Giammarchi
 * @Site		http://www.devpro.it/
 * @Mail		andrea [ at ] 3site [ dot ] it
 * @Date		2005/10/19
 * @LastModified	2006/01/29 09:30 [fixed execute bug]
 * @Version		0.1b - tested
 */ 
class PDOStatement_pgsql {
	
	/**
	 * 'Private' variables:
	 *	__connection:Resource		Database connection
         *	__dbinfo:Array			Array with 4 elements used to manage connection
         *      __persistent:Boolean		Connection mode, is true on persistent, false on normal (deafult) connection
         *      __query:String			Last query used
         *      __result:Resource		Last result from last query
         *      __fetchmode:Integer		constant PDO_FETCH_* result mode
         *      __errorCode:String		Last error string code
         *      __errorInfo:Array		Last error informations, code, number, details
         *      __boundParams:Array		Stored bindParam
	 */
	var $__connection;
	var $__dbinfo;
	var $__persistent = false;
	var $__query = '';
	var $__result = null;
	var $__fetchmode = PDO::FETCH_BOTH;
	var $__errorCode = '';
	var $__errorInfo = Array('');
	var $__boundParams = Array();
	
	/**
	 * Public constructor:
	 *	Called from PDO to create a PDOStatement for this database
         *       	new PDOStatement_pgsql( &$__query:String, &$__connection:Resource, $__dbinfo:String )
	 * @Param	String		query to prepare
         * @Param	Resource	database connection
         * @Param	String		database file name
	 */
	function PDOStatement_pgsql(&$__query, &$__connection, &$__dbinfo) {
		$this->__query = &$__query;
		$this->__connection = &$__connection;
		$this->__dbinfo = &$__dbinfo;
	}
	
	/**
	 * Public method:
	 *	Replace ? or :named values to execute prepared query
         *       	this->bindParam( $mixed:Mixed, &$variable:Mixed, $type:Integer, $lenght:Integer ):Void
         * @Param	Mixed		Integer or String to replace prepared value
         * @Param	Mixed		variable to replace
         * @Param	Integer		this variable is not used but respects PDO original accepted parameters
         * @Param	Integer		this variable is not used but respects PDO original accepted parameters
	 */
	function bindParam($mixed, &$variable, $type = null, $lenght = null) {
		if(is_string($mixed))
			$this->__boundParams[$mixed] = $variable;
		else
			array_push($this->__boundParams, $variable);
	}
	
	/**
	 * Public method:
	 *	Checks if query was valid and returns how may fields returns
         *       	this->columnCount( void ):Void
	 */
	function columnCount() {
		$result = 0;
		if(!is_null($this->__result))
			$result = pg_num_fields($this->__result);
		return $result; 
	}
	
	/**
	 * Public method:
	 *	Returns a code rappresentation of an error
         *       	this->errorCode( void ):String
         * @Return	String		String rappresentation of the error
	 */
	function errorCode() {
		return $this->__errorCode;
	}
	
	/**
	 * Public method:
	 *	Returns an array with error informations
         *       	this->errorInfo( void ):Array
         * @Return	Array		Array with 3 keys:
         * 				0 => error code
         *                              1 => error number
         *                              2 => error string
	 */
	function errorInfo() {
		return $this->__errorInfo;
	}
	
	/**
	 * Public method:
	 *	Excecutes a query and returns true on success or false.
         *       	this->exec( $array:Array ):Boolean
         * @Param	Array		If present, it should contain all replacements for prepared query
         * @Return	Boolean		true if query has been done without errors, false otherwise
	 */
	function execute($array = Array()) {
		if(count($this->__boundParams) > 0)
			$array = &$this->__boundParams;
		$__query = $this->__query;
		if(count($array) > 0) {
			foreach($array as $k => $v) {
				if(!is_int($k) || substr($k, 0, 1) === ':') {
					if(!isset($tempf))
						$tempf = $tempr = array();
					array_push($tempf, $k);
					array_push($tempr, "'".pg_escape_string($v)."'");
				}
				else {
					$parse = create_function('$v', 'return "\'".pg_escape_string($v)."\'";');
					$__query = preg_replace("/(\?)/e", '$parse($array[$k++]);', $__query);
					break;
				}
			}
			if(isset($tempf))
				$__query = str_replace($tempf, $tempr, $__query);
		}
		if(is_null($this->__result = &$this->__uquery($__query)))
			$keyvars = false;
		else
			$keyvars = true;
		$this->__boundParams = array();
		return $keyvars;
	}
	
	/**
	 * Public method:
	 *	Returns, if present, next row of executed query or false.
         *       	this->fetch( $mode:Integer, $cursor:Integer, $offset:Integer ):Mixed
         * @Param	Integer		PDO_FETCH_* constant to know how to read next row, default PDO_FETCH_BOTH
         * 				NOTE: if $mode is omitted is used default setted mode, PDO_FETCH_BOTH
         * @Param	Integer		this variable is not used but respects PDO original accepted parameters
         * @Param	Integer		this variable is not used but respects PDO original accepted parameters
         * @Return	Mixed		Next row of executed query or false if there is nomore.
	 */
	function fetch($mode = PDO_FETCH_BOTH, $cursor = null, $offset = null) {
		if(func_num_args() == 0)
			$mode = &$this->__fetchmode;
		$result = false;
		if(!is_null($this->__result)) {
			switch($mode) {
				case PDO_FETCH_NUM:
					$result = pg_fetch_row($this->__result);
					break;
				case PDO_FETCH_ASSOC:
					$result = pg_fetch_assoc($this->__result);
					break;
				case PDO_FETCH_OBJ:
					$result = pg_fetch_object($this->__result);
					break;
				case PDO_FETCH_BOTH:
				default:
					$result = pg_fetch_array($this->__result);
					break;
			}
		}
		if(!$result)
			$this->__result = null;
		return $result;
	}
	
	/**
	 * Public method:
	 *	Returns an array with all rows of executed query.
         *       	this->fetchAll( $mode:Integer ):Array
         * @Param	Integer		PDO_FETCH_* constant to know how to read all rows, default PDO_FETCH_BOTH
         * 				NOTE: this doesn't work as fetch method, then it will use always PDO_FETCH_BOTH
         *                                    if this param is omitted
         * @Return	Array		An array with all fetched rows
	 */
	function fetchAll($mode = PDO_FETCH_BOTH) {
		$result = array();
		if(!is_null($this->__result)) {
			switch($mode) {
				case PDO_FETCH_NUM:
					while($r = pg_fetch_row($this->__result))
						array_push($result, $r);
					break;
				case PDO_FETCH_ASSOC:
					while($r = pg_fetch_assoc($this->__result))
						array_push($result, $r);
					break;
				case PDO_FETCH_OBJ:
					while($r = pg_fetch_object($this->__result))
						array_push($result, $r);
					break;
				case PDO_FETCH_BOTH:
				default:
					while($r = pg_fetch_array($this->__result))
						array_push($result, $r);
					break;
			}
		}
		$this->__result = null;
		return $result;
	}
	
	/**
	 * Public method:
	 *	Returns, if present, first column of next row of executed query
         *       	this->fetchSingle( void ):Mixed
         * @Return	Mixed		Null or next row's first column
	 */
	function fetchSingle() {
		$result = null;
		if(!is_null($this->__result)) {
			$result = @pg_fetch_row($this->__result);
			if($result)
				$result = $result[0];
			else
				$this->__result = null;
		}
		return $result;
	}
	
	/**
	 * Public method:
	 *	Returns number of last affected database rows
         *       	this->rowCount( void ):Integer
         * @Return	Integer		number of last affected rows
         * 				NOTE: works with INSERT, UPDATE and DELETE query type
	 */
	function rowCount() {
		$result = 0;
		if(!is_null($this->__result))
			$result = pg_affected_rows($this->__result);
		return $result;
	}
	
	
	// NOT TOTALLY SUPPORTED PUBLIC METHODS	
	/**
	 * Public method:
	 *	Quotes correctly a string for this database
         *       	this->getAttribute( $attribute:Integer ):Mixed
         * @Param	Integer		a constant [	PDO_ATTR_SERVER_INFO,
         * 						PDO_ATTR_SERVER_VERSION,
         *                                              PDO_ATTR_CLIENT_VERSION,
         *                                              PDO_ATTR_PERSISTENT	]
         * @Return	Mixed		correct information or false
	 */
	function getAttribute($attribute) {
		$result = false;
		switch($attribute) {
			case PDO_ATTR_SERVER_INFO:
				$result = pg_parameter_status($this->__connection, 'server_encoding');
				break;
			case PDO_ATTR_SERVER_VERSION:
				$result = pg_parameter_status($this->__connection, 'server_version');
				break;
			case PDO_ATTR_CLIENT_VERSION:
				$result = pg_parameter_status($this->__connection, 'server_version');
				$result .= ' '.pg_parameter_status($this->__connection, 'client_encoding');
				break;
			case PDO_ATTR_PERSISTENT:
				$result = $this->__persistent;
				break;
		}
		return $result;
	}
	
	/**
	 * Public method:
	 *	Sets database attributes, in this version only connection mode.
         *       	this->setAttribute( $attribute:Integer, $mixed:Mixed ):Boolean
         * @Param	Integer		PDO_* constant, in this case only PDO_ATTR_PERSISTENT
         * @Param	Mixed		value for PDO_* constant, in this case a Boolean value
         * 				true for permanent connection, false for default not permament connection
         * @Return	Boolean		true on change, false otherwise
	 */
	function setAttribute($attribute, $mixed) {
		$result = false;
		if($attribute === PDO_ATTR_PERSISTENT && $mixed != $this->__persistent) {
			$result = true;
			$this->__persistent = (boolean) $mixed;
			pg_close($this->__connection);
			if($this->__persistent === true)
				$this->__connection = &pg_connect($this->__dbinfo);
			else
				$this->__connection = &pg_pconnect($this->__dbinfo);
		}
		return $result;
	}
	
	/**
	 * Public method:
	 *	Sets default fetch mode to use with this->fetch() method.
         *       	this->setFetchMode( $mode:Integer ):Boolean
         * @Param	Integer		PDO_FETCH_* constant to use while reading an execute query with fetch() method.
         * 				NOTE: PDO_FETCH_LAZY and PDO_FETCH_BOUND are not supported
         * @Return	Boolean		true on change, false otherwise
	 */
	function setFetchMode($mode) {
		$result = false;
		switch($mode) {
			case PDO_FETCH_NUM:
			case PDO_FETCH_ASSOC:
			case PDO_FETCH_OBJ:
			case PDO_FETCH_BOTH:
				$result = true;
				$this->__fetchmode = &$mode;
				break;
		}
		return $result;
	}
	
	
	// UNSUPPORTED PUBLIC METHODS
        function bindColumn($mixewd, &$param, $type = null, $max_length = null, $driver_option = null) {
		return false;
	}
	
	function __setErrors($er) {
		if(!is_string($this->__errorCode))
			$errno = $this->__errorCode;
		if(!is_resource($this->__connection)) {
			$errno = 1;
			$errst = pg_last_error();
		}
		else {
			$errno = 1;
			$errst = pg_last_error($this->__connection);
		}
		$this->__errorCode = &$er;
		$this->__errorInfo = Array($this->__errorCode, $errno, $errst);
	}
	
	function __uquery(&$query) {
		if(!@$query = pg_query($this->__connection, $query)) {
			$this->__setErrors('SQLER');
			$query = null;
		}
		return $query;
	}
	
}


}//if!class_exists
?>