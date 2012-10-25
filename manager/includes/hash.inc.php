<?php
/**
 * Hashing classes
 *
 * - Manages legacy passwords (unsalted MD5) and new Clipper passwords (salted SHA1).
 * - Todo: Extend to use blowfish or similar.
 *
 * @author TimGS
 */

define('CLIPPER_HASH_MD5', 0);	// Legacy MODx hash method
define('CLIPPER_HASH_SHA1', 1);	// Interim hash method until blowfish or similar more widely supported

define('CLIPPER_HASH_PREFERRED', CLIPPER_HASH_SHA1);

define('CLIPPER_SALT_LENGTH_DEFAULT', 12);

class HashHandler {

	private $document_parser;
	private $hashtype;

	/** 
	 * Generate random salt
	 *
	 * @return string
	 */
	private function salt($salt_length)
		{
		return substr(md5(uniqid(rand(), true)), 0, $salt_length);
		}
	
	/**
	 * Constructor. Takes a hashtype (CLIPPER_HASH_MD5 or CLIPPER_HASH_SHA1) and if possible a reference to the DocumentParser.
	 *
	 * @param int $hashtype
	 * @param object &$document_parser Optional
	 * @return void
	 */
	function __construct($hashtype, &$document_parser = null)
		{
		$this->document_parser = $document_parser;

		switch($hashtype)
			{
			case 0:
			case 1:
				$this->hashtype = ($hashtype == CLIPPER_HASH_MD5) ? 0 : 1;
				break;
				
			default:
				if (is_object($this->document_parser) && get_class($this->document_parser) == 'DocumentParser')
					{
					// Use DocumentParser's error reporting if possible.
					$trace = debug_backtrace();
					$this->document_parser->messageQuit('Invalid hash type in '.$trace[0]['file'].' in line '.$trace[0]['line'], null, false);
					}
				else
					{
					exit();
					}
				break;
			}
		}

	/**
	 * Generate password hash
	 *
	 * @param string $plaintext 
	 * @return string Hash
	 */
	function generate($plaintext)
		{
		switch($this->hashtype)
			{
			case 0:
				$salt = null;
				$hash = md5($plaintext);
				break;
			
			case 1:
				$salt = $this->salt(CLIPPER_SALT_LENGTH_DEFAULT);
				$hash = sha1($salt.$plaintext);
				break;
			}
		
		return new Hash($salt, $hash);
		}

	/**
	 * Check password hash
	 *
	 * @param string $plaintext 
	 * @param string $salt. Not used for MD5.
	 * @param string $hash
	 * @return bool
	 * @author TimGS
	 */
	function check($plaintext, $salt, $hash)
		{
		switch($this->hashtype)
			{
			case 0:
				$match = (md5($plaintext) == $hash);
				break;
			
			default:
				$this->error_hashtype(__METHOD__);
				break;
			
			case 1:
				$match = (sha1($salt.$plaintext) == $hash);
				break;
			}

		return $match;
		}
	}

class Hash {

	private $salt, $hash;

	function __construct($salt, $hash)
		{
		$this->salt = $salt;
		$this->hash = $hash;
		}
	
	function __get($name)
		{
		return $this->$name;
		}
	}

