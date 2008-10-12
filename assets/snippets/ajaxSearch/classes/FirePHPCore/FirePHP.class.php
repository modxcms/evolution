<?php
/**
 * *** BEGIN LICENSE BLOCK *****
 *  
 * This file is part of FirePHP (http://www.firephp.org/).
 * 
 * Software License Agreement (New BSD License)
 * 
 * Copyright (c) 2006-2008, Christoph Dorn
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * 
 *     * Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 * 
 *     * Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 * 
 *     * Neither the name of Christoph Dorn nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 * ***** END LICENSE BLOCK *****
 * 
 * @copyright   Copyright (C) 2007-2008 Christoph Dorn
 * @author      Christoph Dorn <christoph@christophdorn.com>
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @package     FirePHP
 */
 
 
/**
 * Sends the given data to the FirePHP Firefox Extension.
 * The data can be displayed in the Firebug Console or in the
 * "Server" request tab.
 * 
 * For more information see: http://www.firephp.org/
 * 
 * @copyright   Copyright (C) 2007-2008 Christoph Dorn
 * @author      Christoph Dorn <christoph@christophdorn.com>
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @package     FirePHP
 */
class FirePHP {
  
  /**
   * FirePHP version
   *
   * @var string
   */
  const VERSION = '0.2.b.2';
  
  /**
   * Firebug LOG level
   *
   * Logs a message to firebug console.
   * 
   * @var string
   */
  const LOG = 'LOG';
  
  /**
   * Firebug INFO level
   *
   * Logs a message to firebug console and displays an info icon before the message.
   * 
   * @var string
   */
  const INFO = 'INFO';
  
  /**
   * Firebug WARN level
   *
   * Logs a message to firebug console, displays an warning icon before the message and colors the line turquoise.
   * 
   * @var string
   */
  const WARN = 'WARN';
  
  /**
   * Firebug ERROR level
   *
   * Logs a message to firebug console, displays an error icon before the message and colors the line yellow. Also increments the firebug error count.
   * 
   * @var string
   */
  const ERROR = 'ERROR';
  
  /**
   * Dumps a variable to firebug's server panel
   *
   * @var string
   */
  const DUMP = 'DUMP';
  
  /**
   * Displays a stack trace in firebug console
   *
   * @var string
   */
  const TRACE = 'TRACE';
  
  /**
   * Displays an exception in firebug console
   * 
   * Increments the firebug error count.
   *
   * @var string
   */
  const EXCEPTION = 'EXCEPTION';
  
  /**
   * Displays an table in firebug console
   *
   * @var string
   */
  const TABLE = 'TABLE';
  
  /**
   * Starts a group in firebug console
   * 
   * @var string
   */
  const GROUP_START = 'GROUP_START';
  
  /**
   * Ends a group in firebug console
   * 
   * @var string
   */
  const GROUP_END = 'GROUP_END';
  
  /**
   * Singleton instance of FirePHP
   *
   * @var FirePHP
   */
  protected static $instance = null;
  
  /**
   * Wildfire protocol message index
   *
   * @var int
   */
  protected $messageIndex = 1;
  
  /**
   * Gets singleton instance of FirePHP
   *
   * @param boolean $AutoCreate
   * @return FirePHP
   */
  public static function getInstance($AutoCreate=false) {
    if($AutoCreate===true && !self::$instance) {
      self::init();
    }
    return self::$instance;
  }
   
  /**
   * Creates FirePHP object and stores it for singleton access
   *
   * @return FirePHP
   */
  public static function init() {
    return self::$instance = new self();
  } 
  
  /**
   * Register FirePHP as your error handler
   * 
   * Will throw exceptions for each php error.
   */
  public function registerErrorHandler()
  {
    //NOTE: The following errors will not be caught by this error handler:
    //      E_ERROR, E_PARSE, E_CORE_ERROR,
    //      E_CORE_WARNING, E_COMPILE_ERROR,
    //      E_COMPILE_WARNING, E_STRICT
    
    set_error_handler(array($this,'errorHandler'));     
  }

  /**
   * FirePHP's error handler
   * 
   * Throws exception for each php error that will occur.
   *
   * @param int $errno
   * @param string $errstr
   * @param string $errfile
   * @param int $errline
   * @param array $errcontext
   */
  public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
  {
    // Don't throw exception if error reporting is switched off
    if (error_reporting() == 0) {
      return;
    }
    // Only throw exceptions for errors we are asking for
    if (error_reporting() & $errno) {
      throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
  }
  
  /**
   * Register FirePHP as your exception handler
   */
  public function registerExceptionHandler()
  {
    set_exception_handler(array($this,'exceptionHandler'));     
  }
  
  /**
   * FirePHP's exception handler
   * 
   * Logs all exceptions to your firebug console and then stops the script.
   *
   * @param Exception $Exception
   * @throws Exception
   */
  function exceptionHandler($Exception) {
    $this->fb($Exception);
  }
  
  /**
   * Set custom processor url for FirePHP
   *
   * @param string $URL
   */    
  public function setProcessorUrl($URL)
  {
    $this->setHeader('X-FirePHP-ProcessorURL', $URL);
  }

  /**
   * Set custom renderer url for FirePHP
   *
   * @param string $URL
   */
  public function setRendererUrl($URL)
  {
    $this->setHeader('X-FirePHP-RendererURL', $URL);
  }
  
  /**
   * Start a group for following messages
   *
   * @param string $Name
   * @return true
   * @throws Exception
   */
  public function group($Name) {
    return $this->fb(null, $Name, FirePHP::GROUP_START);
  }
  
  /**
   * Ends a group you have started before
   *
   * @return true
   * @throws Exception
   */
  public function groupEnd() {
    return $this->fb(null, null, FirePHP::GROUP_END);
  }

  /**
   * Log object with label to firebug console
   *
   * @see FirePHP::LOG
   * @param mixes $Object
   * @param string $Label
   * @return true
   * @throws Exception
   */
  public function log($Object, $Label=null) {
    return $this->fb($Object, $Label, FirePHP::LOG);
  } 

  /**
   * Log object with label to firebug console
   *
   * @see FirePHP::INFO
   * @param mixes $Object
   * @param string $Label
   * @return true
   * @throws Exception
   */
  public function info($Object, $Label=null) {
    return $this->fb($Object, $Label, FirePHP::INFO);
  } 

  /**
   * Log object with label to firebug console
   *
   * @see FirePHP::WARN
   * @param mixes $Object
   * @param string $Label
   * @return true
   * @throws Exception
   */
  public function warn($Object, $Label=null) {
    return $this->fb($Object, $Label, FirePHP::WARN);
  } 

  /**
   * Log object with label to firebug console
   *
   * @see FirePHP::ERROR
   * @param mixes $Object
   * @param string $Label
   * @return true
   * @throws Exception
   */
  public function error($Object, $Label=null) {
    return $this->fb($Object, $Label, FirePHP::ERROR);
  } 

  /**
   * Dumps key and variable to firebug server panel
   *
   * @see FirePHP::DUMP
   * @param string $Key
   * @param mixed $Variable
   * @return true
   * @throws Exception
   */
  public function dump($Key, $Variable) {
    return $this->fb($Variable, $Key, FirePHP::DUMP);
  }
  
  /**
   * Log a trace in the firebug console
   *
   * @see FirePHP::TRACE
   * @param string $Label
   * @return true
   * @throws Exception
   */
  public function trace($Label) {
    return $this->fb($Label, FirePHP::TRACE);
  } 

  /**
   * Log a table in the firebug console
   *
   * @see FirePHP::TABLE
   * @param string $Label
   * @param string $Table
   * @return true
   * @throws Exception
   */
  public function table($Label, $Table) {
    return $this->fb($Table, $Label, FirePHP::TABLE);
  }
  
  /**
   * Check if FirePHP is installed on client
   *
   * @return boolean
   */
  public function detectClientExtension() {
    /* Check if FirePHP is installed on client */
    if(!@preg_match_all('/\sFirePHP\/([\.|\d]*)\s?/si',$this->getUserAgent(),$m) ||
       !version_compare($m[1][0],'0.0.6','>=')) {
      return false;
    }
    return true;    
  }
 
  /**
   * Log object to firebug
   * 
   * @see http://www.firephp.org/Wiki/Reference/Fb
   * @param mixed $Object
   * @return true
   * @throws Exception
   */
  public function fb($Object) {
  
    if (headers_sent($filename, $linenum)) {
        throw $this->newException('Headers already sent in '.$filename.' on line '.$linenum.'. Cannot send log data to FirePHP. You must have Output Buffering enabled via ob_start() or output_buffering ini directive.');
    }
  
    $Type = null;
    $Label = null;
  
    if(func_num_args()==1) {
    } else
    if(func_num_args()==2) {
      switch(func_get_arg(1)) {
        case self::LOG:
        case self::INFO:
        case self::WARN:
        case self::ERROR:
        case self::DUMP:
        case self::TRACE:
        case self::EXCEPTION:
        case self::TABLE:
        case self::GROUP_START:
        case self::GROUP_END:
          $Type = func_get_arg(1);
          break;
        default:
          $Label = func_get_arg(1);
          break;
      }
    } else
    if(func_num_args()==3) {
      $Type = func_get_arg(2);
      $Label = func_get_arg(1);
    } else {
      throw $this->newException('Wrong number of arguments to fb() function!');
    }
  
  
    if(!$this->detectClientExtension()) {
      return false;
    }
  
    if($Object instanceof Exception) {
      
      $trace = $Object->getTrace();
      if($Object instanceof ErrorException
         && isset($trace[0]['function'])
         && $trace[0]['function']=='errorHandler'
         && isset($trace[0]['class'])
         && $trace[0]['class']=='FirePHP') {
           
        $severity = false;
        switch($Object->getSeverity()) {
          case E_WARNING: $severity = 'E_WARNING'; break;
          case E_NOTICE: $severity = 'E_NOTICE'; break;
          case E_USER_ERROR: $severity = 'E_USER_ERROR'; break;
          case E_USER_WARNING: $severity = 'E_USER_WARNING'; break;
          case E_USER_NOTICE: $severity = 'E_USER_NOTICE'; break;
          case E_STRICT: $severity = 'E_STRICT'; break;
          case E_RECOVERABLE_ERROR: $severity = 'E_RECOVERABLE_ERROR'; break;
          case E_DEPRECATED: $severity = 'E_DEPRECATED'; break;
          case E_USER_DEPRECATED: $severity = 'E_USER_DEPRECATED'; break;
        }
           
        $Object = array('Class'=>get_class($Object),
                        'Message'=>$severity.': '.$Object->getMessage(),
                        'File'=>$this->_escapeTraceFile($Object->getFile()),
                        'Line'=>$Object->getLine(),
                        'Type'=>'trigger',
                        'Trace'=>$this->_escapeTrace(array_splice($trace,2)));
      
      } else {
        $Object = array('Class'=>get_class($Object),
                        'Message'=>$Object->getMessage(),
                        'File'=>$this->_escapeTraceFile($Object->getFile()),
                        'Line'=>$Object->getLine(),
                        'Type'=>'throw',
                        'Trace'=>$this->_escapeTrace($trace));
      }
      $Type = self::EXCEPTION;
      
    } else
    if($Type==self::TRACE) {
      
      $trace = debug_backtrace();
      if(!$trace) return false;
      for( $i=0 ; $i<sizeof($trace) ; $i++ ) {

        if(isset($trace[$i]['class'])
           && isset($trace[$i]['file'])
           && ($trace[$i]['class']=='FirePHP'
               || $trace[$i]['class']=='FB')
           && (substr($this->_standardizePath($trace[$i]['file']),-18,18)=='FirePHPCore/fb.php'
               || substr($this->_standardizePath($trace[$i]['file']),-29,29)=='FirePHPCore/FirePHP.class.php')) {
          /* Skip - FB::trace(), FB::send(), $firephp->trace(), $firephp->fb() */
        } else
        if(isset($trace[$i]['class'])
           && isset($trace[$i+1]['file'])
           && $trace[$i]['class']=='FirePHP'
           && substr($this->_standardizePath($trace[$i+1]['file']),-18,18)=='FirePHPCore/fb.php') {
          /* Skip fb() */
        } else
        if($trace[$i]['function']=='fb'
           || $trace[$i]['function']=='trace'
           || $trace[$i]['function']=='send') {
          $Object = array('Class'=>isset($trace[$i]['class'])?$trace[$i]['class']:'',
                          'Type'=>isset($trace[$i]['type'])?$trace[$i]['type']:'',
                          'Function'=>isset($trace[$i]['function'])?$trace[$i]['function']:'',
                          'Message'=>$trace[$i]['args'][0],
                          'File'=>$this->_escapeTraceFile($trace[$i]['file']),
                          'Line'=>$trace[$i]['line'],
                          'Args'=>$trace[$i]['args'],
                          'Trace'=>$this->_escapeTrace(array_splice($trace,$i+1)));
          break;
        }
      }

    } else {
      if($Type===null) {
        $Type = self::LOG;
      }
    }

  	$this->setHeader('X-Wf-Protocol-1','http://meta.wildfirehq.org/Protocol/JsonStream/0.1');
  	$this->setHeader('X-Wf-1-Plugin-1','http://meta.firephp.org/Wildfire/Plugin/FirePHP/Library-FirePHPCore/'.self::VERSION);
 
    $structure_index = 1;
    if($Type==self::DUMP) {
      $structure_index = 2;
    	$this->setHeader('X-Wf-1-Structure-2','http://meta.firephp.org/Wildfire/Structure/FirePHP/Dump/0.1');
    } else {
    	$this->setHeader('X-Wf-1-Structure-1','http://meta.firephp.org/Wildfire/Structure/FirePHP/FirebugConsole/0.1');
    }
  
    if($Type==self::DUMP) {
    	$msg = '{"'.$Label.'":'.$this->json_encode($Object).'}';
    } else {
      $meta = array('Type'=>$Type);
      if($Label!==null) {
        $meta['Label'] = $Label;
      }    
    	$msg = '['.$this->json_encode($meta).','.$this->json_encode($Object).']';
    }

    foreach (explode("\n",chunk_split($msg, 4998, "\n")) as $part) {

        if ($part) {
            
            $this->setHeader('X-Wf-1-'.$structure_index.'-'.'1-'.$this->messageIndex,
                             '|' . $part . '|');
            
            $this->messageIndex++;
            
            if ($this->messageIndex > 99999) {
                throw new Exception('Maximum number (99,999) of messages reached!');             
            }
        }
    }

  	$this->setHeader('X-Wf-1-Index',$this->messageIndex-1);

    return true;
  }
  
  /**
   * Standardizes path for windows systems.
   *
   * @param string $Path
   * @return string
   */
  protected function _standardizePath($Path) {
    return preg_replace('/\\\\+/','/',$Path);    
  }
  
  /**
   * Escape trace path for windows systems
   *
   * @param array $Trace
   * @return array
   */
  protected function _escapeTrace($Trace) {
    if(!$Trace) return $Trace;
    for( $i=0 ; $i<sizeof($Trace) ; $i++ ) {
      if(isset($Trace[$i]['file'])) {
        $Trace[$i]['file'] = $this->_escapeTraceFile($Trace[$i]['file']);
      }
    }
    return $Trace;    
  }
  
  /**
   * Escape file information of trace for windows systems
   *
   * @param string $File
   * @return string
   */
  protected function _escapeTraceFile($File) {
    /* Check if we have a windows filepath */
    if(strpos($File,'\\')) {
      /* First strip down to single \ */
      
      $file = preg_replace('/\\\\+/','\\',$File);
      
      return $file;
    }
    return $File;
  }

  /**
   * Send header
   *
   * @param string $Name
   * @param string_type $Value
   */
  protected function setHeader($Name, $Value) {
    return header($Name.': '.$Value);
  }

  /**
   * Get user agent
   *
   * @return string|false
   */
  protected function getUserAgent() {
    if(!isset($_SERVER['HTTP_USER_AGENT'])) return false;
    return $_SERVER['HTTP_USER_AGENT'];
  }

  /**
   * Returns a new exception
   *
   * @param string $Message
   * @return Exception
   */
  protected function newException($Message) {
    return new Exception($Message);
  }

    
  /**
   * Converts to and from JSON format.
   *
   * JSON (JavaScript Object Notation) is a lightweight data-interchange
   * format. It is easy for humans to read and write. It is easy for machines
   * to parse and generate. It is based on a subset of the JavaScript
   * Programming Language, Standard ECMA-262 3rd Edition - December 1999.
   * This feature can also be found in  Python. JSON is a text format that is
   * completely language independent but uses conventions that are familiar
   * to programmers of the C-family of languages, including C, C++, C#, Java,
   * JavaScript, Perl, TCL, and many others. These properties make JSON an
   * ideal data-interchange language.
   *
   * This package provides a simple encoder and decoder for JSON notation. It
   * is intended for use with client-side Javascript applications that make
   * use of HTTPRequest to perform server communication functions - data can
   * be encoded into JSON notation for use in a client-side javascript, or
   * decoded from incoming Javascript requests. JSON format is native to
   * Javascript, and can be directly eval()'ed with no further parsing
   * overhead
   *
   * All strings should be in ASCII or UTF-8 format!
   *
   * LICENSE: Redistribution and use in source and binary forms, with or
   * without modification, are permitted provided that the following
   * conditions are met: Redistributions of source code must retain the
   * above copyright notice, this list of conditions and the following
   * disclaimer. Redistributions in binary form must reproduce the above
   * copyright notice, this list of conditions and the following disclaimer
   * in the documentation and/or other materials provided with the
   * distribution.
   *
   * THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED
   * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
   * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN
   * NO EVENT SHALL CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
   * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
   * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS
   * OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
   * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
   * TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
   * USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
   * DAMAGE.
   *
   * @category
   * @package     Services_JSON
   * @author      Michal Migurski <mike-json@teczno.com>
   * @author      Matt Knapp <mdknapp[at]gmail[dot]com>
   * @author      Brett Stimmerman <brettstimmerman[at]gmail[dot]com>
   * @author      Christoph Dorn <christoph@christophdorn.com>
   * @copyright   2005 Michal Migurski
   * @version     CVS: $Id: JSON.php,v 1.31 2006/06/28 05:54:17 migurski Exp $
   * @license     http://www.opensource.org/licenses/bsd-license.php
   * @link        http://pear.php.net/pepr/pepr-proposal-show.php?id=198
   */
   
     
  /**
   * Keep a list of objects as we descend into the array so we can detect recursion.
   */
  private $json_objectStack = array();


 /**
  * convert a string from one UTF-8 char to one UTF-16 char
  *
  * Normally should be handled by mb_convert_encoding, but
  * provides a slower PHP-only method for installations
  * that lack the multibye string extension.
  *
  * @param    string  $utf8   UTF-8 character
  * @return   string  UTF-16 character
  * @access   private
  */
  private function json_utf82utf16($utf8)
  {
      // oh please oh please oh please oh please oh please
      if(function_exists('mb_convert_encoding')) {
          return mb_convert_encoding($utf8, 'UTF-16', 'UTF-8');
      }

      switch(strlen($utf8)) {
          case 1:
              // this case should never be reached, because we are in ASCII range
              // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
              return $utf8;

          case 2:
              // return a UTF-16 character from a 2-byte UTF-8 char
              // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
              return chr(0x07 & (ord($utf8{0}) >> 2))
                   . chr((0xC0 & (ord($utf8{0}) << 6))
                       | (0x3F & ord($utf8{1})));

          case 3:
              // return a UTF-16 character from a 3-byte UTF-8 char
              // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
              return chr((0xF0 & (ord($utf8{0}) << 4))
                       | (0x0F & (ord($utf8{1}) >> 2)))
                   . chr((0xC0 & (ord($utf8{1}) << 6))
                       | (0x7F & ord($utf8{2})));
      }

      // ignoring UTF-32 for now, sorry
      return '';
  }

 /**
  * encodes an arbitrary variable into JSON format
  *
  * @param    mixed   $var    any number, boolean, string, array, or object to be encoded.
  *                           see argument 1 to Services_JSON() above for array-parsing behavior.
  *                           if var is a strng, note that encode() always expects it
  *                           to be in ASCII or UTF-8 format!
  *
  * @return   mixed   JSON string representation of input var or an error if a problem occurs
  * @access   public
  */
  private function json_encode($var)
  {
    
    if(is_object($var)) {
      if(in_array($var,$this->json_objectStack)) {
        return '"** Recursion **"';
      }
    }
          
      switch (gettype($var)) {
          case 'boolean':
              return $var ? 'true' : 'false';

          case 'NULL':
              return 'null';

          case 'integer':
              return (int) $var;

          case 'double':
          case 'float':
              return (float) $var;

          case 'string':
              // STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT
              $ascii = '';
              $strlen_var = strlen($var);

             /*
              * Iterate over every character in the string,
              * escaping with a slash or encoding to UTF-8 where necessary
              */
              for ($c = 0; $c < $strlen_var; ++$c) {

                  $ord_var_c = ord($var{$c});

                  switch (true) {
                      case $ord_var_c == 0x08:
                          $ascii .= '\b';
                          break;
                      case $ord_var_c == 0x09:
                          $ascii .= '\t';
                          break;
                      case $ord_var_c == 0x0A:
                          $ascii .= '\n';
                          break;
                      case $ord_var_c == 0x0C:
                          $ascii .= '\f';
                          break;
                      case $ord_var_c == 0x0D:
                          $ascii .= '\r';
                          break;

                      case $ord_var_c == 0x22:
                      case $ord_var_c == 0x2F:
                      case $ord_var_c == 0x5C:
                          // double quote, slash, slosh
                          $ascii .= '\\'.$var{$c};
                          break;

                      case (($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
                          // characters U-00000000 - U-0000007F (same as ASCII)
                          $ascii .= $var{$c};
                          break;

                      case (($ord_var_c & 0xE0) == 0xC0):
                          // characters U-00000080 - U-000007FF, mask 110XXXXX
                          // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                          $char = pack('C*', $ord_var_c, ord($var{$c + 1}));
                          $c += 1;
                          $utf16 = $this->json_utf82utf16($char);
                          $ascii .= sprintf('\u%04s', bin2hex($utf16));
                          break;

                      case (($ord_var_c & 0xF0) == 0xE0):
                          // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                          // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                          $char = pack('C*', $ord_var_c,
                                       ord($var{$c + 1}),
                                       ord($var{$c + 2}));
                          $c += 2;
                          $utf16 = $this->json_utf82utf16($char);
                          $ascii .= sprintf('\u%04s', bin2hex($utf16));
                          break;

                      case (($ord_var_c & 0xF8) == 0xF0):
                          // characters U-00010000 - U-001FFFFF, mask 11110XXX
                          // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                          $char = pack('C*', $ord_var_c,
                                       ord($var{$c + 1}),
                                       ord($var{$c + 2}),
                                       ord($var{$c + 3}));
                          $c += 3;
                          $utf16 = $this->json_utf82utf16($char);
                          $ascii .= sprintf('\u%04s', bin2hex($utf16));
                          break;

                      case (($ord_var_c & 0xFC) == 0xF8):
                          // characters U-00200000 - U-03FFFFFF, mask 111110XX
                          // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                          $char = pack('C*', $ord_var_c,
                                       ord($var{$c + 1}),
                                       ord($var{$c + 2}),
                                       ord($var{$c + 3}),
                                       ord($var{$c + 4}));
                          $c += 4;
                          $utf16 = $this->json_utf82utf16($char);
                          $ascii .= sprintf('\u%04s', bin2hex($utf16));
                          break;

                      case (($ord_var_c & 0xFE) == 0xFC):
                          // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                          // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                          $char = pack('C*', $ord_var_c,
                                       ord($var{$c + 1}),
                                       ord($var{$c + 2}),
                                       ord($var{$c + 3}),
                                       ord($var{$c + 4}),
                                       ord($var{$c + 5}));
                          $c += 5;
                          $utf16 = $this->json_utf82utf16($char);
                          $ascii .= sprintf('\u%04s', bin2hex($utf16));
                          break;
                  }
              }

              return '"'.$ascii.'"';

          case 'array':
             /*
              * As per JSON spec if any array key is not an integer
              * we must treat the the whole array as an object. We
              * also try to catch a sparsely populated associative
              * array with numeric keys here because some JS engines
              * will create an array with empty indexes up to
              * max_index which can cause memory issues and because
              * the keys, which may be relevant, will be remapped
              * otherwise.
              *
              * As per the ECMA and JSON specification an object may
              * have any string as a property. Unfortunately due to
              * a hole in the ECMA specification if the key is a
              * ECMA reserved word or starts with a digit the
              * parameter is only accessible using ECMAScript's
              * bracket notation.
              */

              // treat as a JSON object
              if (is_array($var) && count($var) && (array_keys($var) !== range(0, sizeof($var) - 1))) {
                  
                  $this->json_objectStack[] = $var;

                  $properties = array_map(array($this, 'json_name_value'),
                                          array_keys($var),
                                          array_values($var));

                  array_pop($this->json_objectStack);

                  foreach($properties as $property) {
                      if($property instanceof Exception) {
                          return $property;
                      }
                  }

                  return '{' . join(',', $properties) . '}';
              }

              $this->json_objectStack[] = $var;

              // treat it like a regular array
              $elements = array_map(array($this, 'json_encode'), $var);

              array_pop($this->json_objectStack);

              foreach($elements as $element) {
                  if($element instanceof Exception) {
                      return $element;
                  }
              }

              return '[' . join(',', $elements) . ']';

          case 'object':
              $vars = $this->json_get_object_vars($var);

              $this->json_objectStack[] = $var;

              $properties = array_map(array($this, 'json_name_value'),
                                      array_keys($vars),
                                      array_values($vars));

              array_pop($this->json_objectStack);
              
              foreach($properties as $property) {
                  if($property instanceof Exception) {
                      return $property;
                  }
              }
                     
              return '{' . join(',', $properties) . '}';

          default:
              return null;
      }
  }
  
  /**
   * Obtains all object member values including ones with
   * protected and private visibility
   * 
   * @param Object $variable
   * @return array All members of the object
   */
  private function json_get_object_vars($variable)
  {
    // This is required until everyone is running PHP 5.3 at which
    // point the Reflection API can provide private and protected
    // object member values.
    $code = var_export($variable, true);
    
    if(preg_match_all('/[\s>=]?(\S*?)::__set_state\(/si',$code,$m)) {
      for( $i=0; $i < count($m[0]); $i++ ) {
        $code = preg_replace('/'.preg_quote($m[0][$i],'/').'/',
                                'FirePHP::json_generate_object_member_array(\''.$m[1][$i].'\',',
                                $code);
    	}
    }

    eval('$dump = ' . $code . ';');
    
    return $dump;
  }

  /**
   * Generates an array of object members and includes hints about visibility
   * 
   * @param string $class The class of the object
   * @param array $members All object members
   * @return array All object members with class and visibility hints added
   */
  private static function json_generate_object_member_array($class, $members)
  {    
    $reflection_class = new ReflectionClass($class);  
    
    $props = array();
    foreach( $reflection_class->getProperties() as $property) {
      $props[$property->getName()] = $property;
    }
  
    $dump = array('__className'=>$class);

    foreach( $props as $raw_name => $property ) {

      $name = $raw_name;
      if($property->isStatic()) {
        $name = 'static:'.$name;
      }

      if($property->isPublic()) {
        $name = 'public:'.$name;
      } else
      if($property->isPrivate()) {
        $name = 'private:'.$name;
      } else
      if($property->isProtected()) {
        $name = 'protected:'.$name;
      }
      
      if($members[$raw_name]) {
        $dump[$name] = $members[$raw_name];      
      } else {

        if(method_exists($property,'setAccessible')) {
          $property->setAccessible(true);

          $dump[$name] = $property->getValue();
        } else
        if($property->isPublic()) {
          $dump[$name] = $property->getValue();
        } else {
          $dump[$name] = 'Need PHP 5.3 to get value!';
        }
      }
    }    
    
    foreach( $members as $name => $value ) {
      // Include all members that are not defined in the class
      // but exist in the object
      if(!$props[$name]) {
        $name = 'undeclared:'.$name;
        $dump[$name] = $value;
      }
    }
    return $dump;
  }  

 /**
  * array-walking function for use in generating JSON-formatted name-value pairs
  *
  * @param    string  $name   name of key to use
  * @param    mixed   $value  reference to an array element to be encoded
  *
  * @return   string  JSON-formatted name-value pair, like '"name":value'
  * @access   private
  */
  private function json_name_value($name, $value)
  {
      // Encoding the $GLOBALS PHP array causes an infinite loop
      // if the recursion is not reset here as it contains
      // a reference to itself. This is the only way I have come up
      // with to stop infinite recursion in this case.
      if($name=='GLOBALS'
         && is_array($value)
         && array_key_exists('GLOBALS',$value)) {
        $value['GLOBALS'] = '** Recursion **';
      }
    
      $encoded_value = $this->json_encode($value);

      if($encoded_value instanceof Exception) {
          return $encoded_value;
      }

      return $this->json_encode(strval($name)) . ':' . $encoded_value;
  }
}
