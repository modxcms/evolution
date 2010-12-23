<?php if(!class_exists('PDO')) {
/**
 * title: PDO
 * description: PDO emulation for PHP 5.0 and 4.x, with PDO_MySQL built-in
 * author: Andrea Giammarchi
 * author_url: http://www.devpro.it/
 * license: PHPL 2.02
 * url: http://webscripts.softpedia.com/script/PHP-Clases/PDO-for-PHP-4-12854.html
 * version: 0.1b
 * api: php
 * priority: auto
 * category: database
 *
 *
 * File PDO.class.php			*
 * 	Porting of native PHP 5.1 PDO	*
 *      object usable with PHP 4.X.X	*
 *      and PHP 5.0.X version.		*
 * ------------------------------------ *     
 * (C) Andrea Giammarchi [2005/10/19]	*
 * ____________________________________
 *
 *
 * This package includes the PDO MySQL driver. To also get support for
 * Postgres and SQLite, include pdo.pgsql.php and .sqlite.php as well.
 * Of course it's not a complete implementation, but believed to be
 * compatible to even the early PHP4 versions.
 *
 * IMPORTANT: For compatibility with PHP 5.0 and this emulation, you must
 * use the global PDO constants, and not the static PDO:: class versions.
 * PDO_FETCH_ASSOC, PDO_ATTR_*, ... as seen below.
 *
 */

	
// SUPPORTED STATIC ENVIROMENT VARIABLES
define('PDO_ATTR_SERVER_VERSION', 4);	// server version
define('PDO_ATTR_CLIENT_VERSION', 5);	// client version
define('PDO_ATTR_SERVER_INFO', 6);	// server informations
define('PDO_ATTR_PERSISTENT', 12);	// connection mode, persistent or normal

// SUPPORTED STATIC PDO FETCH MODE VARIABLES
define('PDO_FETCH_ASSOC', 2);		// such mysql_fetch_assoc
define('PDO_FETCH_NUM', 3);		// such mysql_fetch_row
define('PDO_FETCH_BOTH', 4);		// such mysql_fetch_array
define('PDO_FETCH_OBJ', 5);		// such mysql_fetch_object

// UNSUPPORTED STATIC PDO FETCH MODE VARIABLES
define('PDO_FETCH_LAZY', 1);		// usable but not supported, default is PDO_FETCH_BOTH and will be used
define('PDO_FETCH_BOUND', 6);		// usable but not supported, default is PDO_FETCH_BOTH and will be used

/**
 * Class PDO
 * 	PostgreSQL, SQLITE and MYSQL PDO support for PHP 4.X.X or PHP 5.0.X users, compatible with PHP 5.1.0 (RC1).
 *
 * DESCRIPTION [directly from http://us2.php.net/manual/en/ref.pdo.php]
 * 	The PHP Data Objects (PDO) extension defines a lightweight, consistent interface for accessing databases in PHP.
 *      Each database driver that implements the PDO interface can expose database-specific features as regular extension functions.
 *      Note that you cannot perform any database functions using the PDO extension by itself;
 *      you must use a database-specific PDO driver to access a database server.
 *
 * HOW TO USE
 * 	To know how to use PDO driver and all its methods visit php.net wonderful documentation.
 *      http://us2.php.net/manual/en/ref.pdo.php
 *      In this class some methods are not available and actually this porting is only for MySQL, SQLITE and PostgreSQL.
 *
 * LIMITS
 * 	For some reasons ( time and php used version with this class ) some PDO methods are not availables and
 *      someother are not totally supported.
 *      
 *      PDO :: UNSUPPORTED METHODS:
 *      	- beginTransaction 	[ mysql 3 has not transaction and manage them is possible only with a direct BEGIN 
 *              			  or COMMIT query ]
 *              - commit
 *              - rollback
 *              
 *      PDO :: NOT TOTALLY SUPPORTED METHODS:
 *      	- getAttribute		[ accepts only PDO_ATTR_SERVER_INFO, PDO_ATTR_SERVER_VERSION,
 *              			  PDO_ATTR_CLIENT_VERSION and PDO_ATTR_PERSISTENT attributes ]
 *              - setAttribute		[ supports only PDO_ATTR_PERSISTENT modification ]
 *              - lastInsertId		[ only fo PostgreSQL , returns only pg_last_oid ]
 *
 *      - - - - - - - - - - - - - - - - - - - - 
 *              
 *      PDOStatement :: UNSUPPORTED METHODS:
 *      	- bindColumn 		[ is not possible to undeclare a variable and using global scope is not
 *              			  really a good idea ]
 *              
 *      PDOStatement :: NOT TOTALLY SUPPORTED METHODS:
 *      	- getAttribute		[ accepts only PDO_ATTR_SERVER_INFO, PDO_ATTR_SERVER_VERSION,
 *              			  PDO_ATTR_CLIENT_VERSION and PDO_ATTR_PERSISTENT attributes ]
 *              - setAttribute		[ supports only PDO_ATTR_PERSISTENT modification ]
 *              - setFetchMode		[ supports only PDO_FETCH_NUM, PDO_FETCH_ASSOC, PDO_FETCH_OBJ and
 *              			  PDO_FETCH_BOTH database reading mode ]
 * ---------------------------------------------
 * @Compatibility	>= PHP 4
 * @Dependencies	PDO_mysql.class.php
 *                      PDO_sqlite.class.php
 *                      PDOStatement_mysql.class.php
 *                      PDOStatement_sqlite.class.php
 * @Author		Andrea Giammarchi
 * @Site		http://www.devpro.it/
 * @Mail		andrea [ at ] 3site [ dot ] it
 * @Date		2005/10/13
 * @LastModified	2005/12/01 21:40
 * @Version		0.1b - tested, supports only PostgreSQL, MySQL or SQLITE databases
 */ 
class PDO {
	
	/** Modified on 2005/12/01 to support new PDO constants on PHP 5.1.X */
	/*
	--won't work with php4--
	const FETCH_ASSOC = PDO_FETCH_ASSOC;
	const FETCH_NUM = PDO_FETCH_NUM;
	const FETCH_BOTH = PDO_FETCH_BOTH;
	const FETCH_OBJ = PDO_FETCH_OBJ;
	const FETCH_LAZY = PDO_FETCH_LAZY;
	const FETCH_BOUND = PDO_FETCH_BOUND;
	const ATTR_SERVER_VERSION = PDO_ATTR_SERVER_VERSION;
	const ATTR_CLIENT_VERSION = PDO_ATTR_CLIENT_VERSION;
	const ATTR_SERVER_INFO = PDO_ATTR_SERVER_INFO;
	const ATTR_PERSISTENT = PDO_ATTR_PERSISTENT;
	*/
	function FETCH_ASSOC(){return PDO_FETCH_ASSOC;}
	function FETCH_NUM(){return PDO_FETCH_NUM;}
	function FETCH_BOTH(){return PDO_FETCH_BOTH;}
	function FETCH_OBJ(){return PDO_FETCH_OBJ;}
	function FETCH_LAZY(){return PDO_FETCH_LAZY;}
	function FETCH_BOUND(){return PDO_FETCH_BOUND;}
	function ATTR_SERVER_VERSION(){return PDO_ATTR_SERVER_VERSION;}
	function ATTR_CLIENT_VERSION(){return PDO_ATTR_CLIENT_VERSION;}
	function ATTR_SERVER_INFO(){return PDO_ATTR_SERVER_INFO;}
	function ATTR_PERSISTENT(){return PDO_ATTR_PERSISTENT;}
	
	/**
	 * 'Private' variables:
	 *	__driver:PDO_*		Dedicated PDO database class
	 */
	var $__driver;
	
	/**
	 * Public constructor
	 *	http://us2.php.net/manual/en/function.pdo-construct.php
	 */
	function PDO($string_dsn, $string_username = '', $string_password = '', $array_driver_options = null) {
		$con = &$this->__getDNS($string_dsn);
		if($con['dbtype'] === 'mysql') {
			#<builtin>#require_once('PDO_mysql.class.php');
			if(isset($con['port']))
				$con['host'] .= ':'.$con['port'];
			$this->__driver = new PDO_mysql(
				$con['host'],
				$con['dbname'],
				$string_username,
				$string_password
			);
		}
		elseif($con['dbtype'] === 'sqlite2' || $con['dbtype'] === 'sqlite') {
			#<builtin>#require_once('PDO_sqlite.class.php');
			$this->__driver = new PDO_sqlite($con['dbname']);
		}
		elseif($con['dbtype'] === 'pgsql') {
			#<builtin>#require_once('PDO_pgsql.class.php');
			$string_dsn = "host={$con['host']} dbname={$con['dbname']} user={$string_username} password={$string_password}";
			if(isset($con['port']))
				$string_dsn .= " port={$con['port']}";
			$this->__driver = new PDO_pgsql($string_dsn);
		}
	}
	
	/** UNSUPPORTED
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-begintransaction.php
	 */
	function beginTransaction() {
		$this->__driver->beginTransaction();
	}
	
	/** NOT NATIVE BUT MAYBE USEFULL FOR PHP < 5.1 PDO DRIVER
	 * Public method
	 * Calls database_close function.
	 *	this->close( Void ):Boolean
	 * @Return	Boolean		True on success, false otherwise
	 */
	function close() {
		return $this->__driver->close();
	}
	
	/** UNSUPPORTED
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-commit.php
	 */
	function commit() {
		$this->__driver->commit();
	}
	
	/** 
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-exec.php
	 */
	function exec($query) {
		return $this->__driver->exec($query);
	}
	
	/** 
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-errorcode.php
	 */
	function errorCode() {
		return $this->__driver->errorCode();
	}
	
	/** 
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-errorinfo.php
	 */
	function errorInfo() {
		return $this->__driver->errorInfo();
	}
	
	/** NOT TOTALLY UNSUPPORTED
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-getattribute.php
	 */
	function getAttribute($attribute) {
		return $this->__driver->getAttribute($attribute);
	}
	
	/** 
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-lastinsertid.php
	 */
	function lastInsertId() {
		return $this->__driver->lastInsertId();
	}
	
	/** 
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-prepare.php
	 */
	function prepare($query, $array = Array()) {
		return $this->__driver->prepare($query, $array = Array());
	}
	
	/** 
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-query.php
	 */
	function query($query) {
		return $this->__driver->query($query);
	}
	
	/** 
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-quote.php
	 */
	function quote($string) {
		return $this->__driver->quote($string);
	}
	
	/** UNSUPPORTED
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-rollback.php
	 */
	function rollBack() {
		$this->__driver->rollBack();
	}
	
	/** NOT TOTALLY UNSUPPORTED
	 * Public method
	 *	http://us2.php.net/manual/en/function.pdo-setattribute.php
	 */
	function setAttribute($attribute, $mixed) {
		return $this->__driver->setAttribute($attribute, $mixed);
	}
	
	// PRIVATE METHOD [uncommented]
	function __getDNS(&$string) {
		$result = array();
		$pos = strpos($string, ':');
		$parameters = explode(';', substr($string, ($pos + 1)));
		$result['dbtype'] = strtolower(substr($string, 0, $pos));
		for($a = 0, $b = count($parameters); $a < $b; $a++) {
			$tmp = explode('=', $parameters[$a]);
			if(count($tmp) == 2)
				$result[$tmp[0]] = $tmp[1];
			else
				$result['dbname'] = $parameters[$a];
		}
		return $result;
	}
}





?><?php
/** File PDO_mysql.class.php		*
 *(C) Andrea Giammarchi [2005/10/13]	*/

// Requires PDOStatement_mysql.class.php , drived by PDO.class.php file
#<builtin>#require_once('PDOStatement_mysql.class.php');

/**
 * Class PDO_mysql
 * 	This class is used from class PDO to manage a MySQL database.
 *      Look at PDO.clas.php file comments to know more about MySQL connection.
 * ---------------------------------------------
 * @Compatibility	>= PHP 4
 * @Dependencies	PDO.class.php
 * 			PDOStatement_mysql.class.php
 * @Author		Andrea Giammarchi
 * @Site		http://www.devpro.it/
 * @Mail		andrea [ at ] 3site [ dot ] it
 * @Date		2005/10/13
 * @LastModified	2005/18/14 12:30
 * @Version		0.1 - tested
 */ 
class PDO_mysql {
	
	/**
	 * 'Private' variables:
	 *	__connection:Resource		Database connection
         *	__dbinfo:Array			Array with 4 elements used to manage connection
         *      __persistent:Boolean		Connection mode, is true on persistent, false on normal (deafult) connection
         *      __errorCode:String		Last error code
         *      __errorInfo:Array		Detailed errors
	 */
	var $__connection;
	var $__dbinfo;
	var $__persistent = false;
	var $__errorCode = '';
	var $__errorInfo = Array('');
	
	/**
	 * Public constructor:
	 *	Checks connection and database selection
         *       	new PDO_mysql( &$host:String, &$db:String, &$user:String, &$pass:String )
	 * @Param	String		host with or without port info
         * @Param	String		database name
         * @Param	String		database user
         * @Param	String		database password
	 */
	function PDO_mysql(&$host, &$db, &$user, &$pass) {
		if(!@$this->__connection = &mysql_connect($host, $user, $pass))
			$this->__setErrors('DBCON');
		else {
			if(!@mysql_select_db($db, $this->__connection))
				$this->__setErrors('DBER');
			else
				$this->__dbinfo = Array($host, $user, $pass, $db);
		}
	}
	
	/** NOT NATIVE BUT MAYBE USEFULL FOR PHP < 5.1 PDO DRIVER
	 * Public method
         * Calls mysql_close function.
	 *	this->close( Void ):Boolean
         * @Return	Boolean		True on success, false otherwise
	 */
	function close() {
		$result = is_resource($this->__connection);
		if($result) {
			mysql_close($this->__connection);
		}
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
		if(!is_null($this->__uquery($query)))
			$result = mysql_affected_rows($this->__connection);
		if(is_null($result))
			$result = false;
		return $result;
	}
	
	/**
	 * Public method:
	 *	Returns last inserted id
         *       	this->lastInsertId( void ):Number
         * @Return	Number		Last inserted id
	 */
	function lastInsertId() {
		return mysql_insert_id($this->__connection);
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
		return new PDOStatement_mysql($query, $this->__connection, $this->__dbinfo);
	}
	
	/**
	 * Public method:
	 *	Executes directly a query and returns an array with result or false on bad query
         *       	this->query( $query:String ):Mixed
         * @Param	String		query to execute
         * @Return	Mixed		false on error, array with all info on success
	 */
	function query($query) {
		$query = @mysql_unbuffered_query($query, $this->__connection);
		if($query) {
			$result = Array();
			while($r = mysql_fetch_assoc($query))
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
		return ('"'.mysql_escape_string($string).'"');
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
				$result = mysql_get_host_info($this->__connection);
				break;
			case PDO_ATTR_SERVER_VERSION:
				$result = mysql_get_server_info($this->__connection);
				break;
			case PDO_ATTR_CLIENT_VERSION:
				$result = mysql_get_client_info();
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
			mysql_close($this->__connection);
			if($this->__persistent === true)
				$this->__connection = &mysql_pconnect($this->__dbinfo[0], $this->__dbinfo[1], $this->__dbinfo[2]);
			else
				$this->__connection = &mysql_connect($this->__dbinfo[0], $this->__dbinfo[1], $this->__dbinfo[2]);
			mysql_select_db($this->__dbinfo[3], $this->__connection);
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
		if(!is_resource($this->__connection)) {
			$errno = mysql_errno();
			$errst = mysql_error();
		}
		else {
			$errno = mysql_errno($this->__connection);
			$errst = mysql_error($this->__connection);
		}
		$this->__errorCode = &$er;
		$this->__errorInfo = Array($this->__errorCode, $errno, $errst);
	}
	
	function __uquery(&$query) {
		if(!@$query = mysql_query($query, $this->__connection)) {
			$this->__setErrors('SQLER');
			$query = null;
		}
		return $query;
	}
}







?><?php
/** File PDOStatement_mysql.class.php	*
 *(C) Andrea Giammarchi [2005/10/13]	*/

/**
 * Class PDOStatement_mysql
 * 	This class is used from class PDO_mysql to manage a MySQL database.
 *      Look at PDO.clas.php file comments to know more about MySQL connection.
 * ---------------------------------------------
 * @Compatibility	>= PHP 4
 * @Dependencies	PDO.class.php
 * 			PDO_mysql.class.php
 * @Author		Andrea Giammarchi
 * @Site		http://www.devpro.it/
 * @Mail		andrea [ at ] 3site [ dot ] it
 * @Date		2005/10/13
 * @LastModified	2006/01/29 09:30 [fixed execute bug]
 * @Version		0.1b - tested
 */ 
class PDOStatement_mysql {
	
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
         *       	new PDOStatement_sqlite( &$__query:String, &$__connection:Resource, $__dbinfo:Array )
	 * @Param	String		query to prepare
         * @Param	Resource	database connection
         * @Param	Array		4 elements array to manage connection
	 */
	function PDOStatement_mysql(&$__query, &$__connection, &$__dbinfo) {
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
			$result = mysql_num_fields($this->__result);
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
					array_push($tempr, '"'.mysql_escape_string($v).'"');
				}
				else {
					$parse = create_function('$v', 'return \'"\'.mysql_escape_string($v).\'"\';');
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
					$result = mysql_fetch_row($this->__result);
					break;
				case PDO_FETCH_ASSOC:
					$result = mysql_fetch_assoc($this->__result);
					break;
				case PDO_FETCH_OBJ:
					$result = mysql_fetch_object($this->__result);	
					break;
				case PDO_FETCH_BOTH:
				default:
					$result = mysql_fetch_array($this->__result);
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
					while($r = mysql_fetch_row($this->__result))
						array_push($result, $r);
					break;
				case PDO_FETCH_ASSOC:
					while($r = mysql_fetch_assoc($this->__result))
						array_push($result, $r);
					break;
				case PDO_FETCH_OBJ:
					while($r = mysql_fetch_object($this->__result))
						array_push($result, $r);
					break;
				case PDO_FETCH_BOTH:
				default:
					while($r = mysql_fetch_array($this->__result))
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
			$result = @mysql_fetch_row($this->__result);
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
		return mysql_affected_rows($this->__connection);
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
				$result = mysql_get_host_info($this->__connection);
				break;
			case PDO_ATTR_SERVER_VERSION:
				$result = mysql_get_server_info($this->__connection);
				break;
			case PDO_ATTR_CLIENT_VERSION:
				$result = mysql_get_client_info();
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
			mysql_close($this->__connection);
			if($this->__persistent === true)
				$this->__connection = &mysql_pconnect($this->__dbinfo[0], $this->__dbinfo[1], $this->__dbinfo[2]);
			else
				$this->__connection = &mysql_connect($this->__dbinfo[0], $this->__dbinfo[1], $this->__dbinfo[2]);
			mysql_select_db($this->__dbinfo[3], $this->__connection);
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
		if(!is_resource($this->__connection)) {
			$errno = mysql_errno();
			$errst = mysql_error();
		}
		else {
			$errno = mysql_errno($this->__connection);
			$errst = mysql_error($this->__connection);
		}
		$this->__errorCode = &$er;
		$this->__errorInfo = Array($this->__errorCode, $errno, $errst);
		$this->__result = null;
	}
	
	function __uquery(&$query) {
		if(!@$query = mysql_query($query, $this->__connection)) {
			$this->__setErrors('SQLER');
			$query = null;
		}
		return $query;
	}
	
}




}//if!class_exists
?>