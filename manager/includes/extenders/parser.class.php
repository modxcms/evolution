<?php
/*
 * Name:		Recursive Parser Extension Class
 * Filename:	manager/includes/extender/parser.class.php
 * Function:	This file is is used as an extension to the parser
 * Author:   	Raymond Irving, Jan 2006
 *
 * License:		Lesser General Public License (LGPL)
 *				http://www.fsf.org/licensing/licenses/lgpl.html
 */
  
 // Parser extension class
 class Parser_Extension {

	// Declarations
	var $tagObjects;		 
	var	$currentTag; 		// name of the name being processed
	var $maxTagRecurLevel;	// max number of recursive calls per tag
	var $maxRecursiveCalls;	// total number of recursive calls allowed within a parser cycle
	var $nonCacheItems;	 // an array of items that are not to be parsed in the cached output
	var $enableCompatMode;	// for preivous tag syntax
	var $specialQuotes;
	
	var $_nestLevel;
	var $_curTagID; 		// curent id for tags
	var $_hasMPls;			// has missing placeholders
	
		
	// Constructor
 	function Parser_Extension() {
 		$this->enableCompatMode = true;
 		$this->maxTagRecurLevel = 10;
		$this->maxRecursiveCalls = 200;	 // a total of 200 recursive calls can be made, used to break circular reference
 		$this->_curTagID = 0;
 		$this->specialQuotes = false;
 	}

 	
	/**
	 * Parses $textSource and returns the result a string. 
	 * Note: When $buildCache is set to true $textSource will also be parsed and used as the cache output source. Non-cacheable tags will not be parsed.
	 *
	 * @param string $textSource String to be parsed.
	 * @param string $tagPrefix
	 * @param boolean $buildCache Set to true to have the parser modify $textSource for cache output
	 * @return string Returns fully parsed text as output source
	 */
	function parseText(&$textSource, $tagPrefix = '[[', $buildCache = false) {
		global $modx;
		
		$loops = 0;
		$loopBack = 1; // allows parser to handle unset placeholders
		$this->_hasMPls = false;
		if($tagPrefix=='') $tagPrefix = '[[';
		
		// local parameter settings - this will allow multiple calls the parseText so we don't have to create a new object
		$conf = array(
			'passByRefValue'=> array(),   // pass by ref value
			'cursorEnd'	 	=> 0,		 // indicates the last cursor position inside the text-source
			'buildCache'	=> $buildCache, 
 			'parserCacheOffset' => 0,
			'eofLT'  		=> 0,		 // end of last tag
			'recurLevel'  	=> 0		  
		);

		// make a copy of text source for output
		$outputSource = ''.$textSource; 

		// parse top-level tags and handle MPls with embedded tags
		$rlvl = array();
		while ($loopBack-- && $loops<3) {
			$loops++;
			$st = $conf['eofLT'] = 0;
			// loop through $textSource in search of tag prefix [[		
			while(($st = strpos($outputSource,$tagPrefix,$st))!==false){

				// detect recursion and break circular reference and deadlocks - to be optimized
				if ($st<$conf['eofLT']) {					
					$tagLevel++;
					$conf['recurLevel']++; // increment recurlevel
					
					if($tagLevel>=$this->maxTagRecurLevel) {
						// remove the next recuring tag and continue
						$_dropTag = true;
						$this->processTag($conf,$outputSource,$textSource,$st,$_dropTag,$tagPrefix);					
						$st = $conf['eofLT'];
						$tagLevel=0;continue;
					}
				}
				else $tagLevel = 0;

				// process tag starting at $st
				$_dropTag = ($conf['recurLevel'] == $this->maxRecursiveCalls);
				$newPos = $this->processTag($conf,$outputSource,$textSource,$st,$_dropTag,$tagPrefix);
				if ($newPos) $st = $newPos; // used to prevent looping

				// have we exceeded maxRecursiveCalls?
				if($conf['recurLevel'] >= $this->maxRecursiveCalls) {
					if($loops<=1) {
						// let's loop one more time to clean up
						$conf['recurLevel'] = 0;
						$this->_hasMPls = true;
					}
					break; // break out of parser loop
				}				
			}
			
			// check if MPLs exist
			if($this->_hasMPls) $loopBack++;

			// reset the MPls flag
			$this->_hasMPls = false;
		}

	
 		return $outputSource;
	}	


	/**
	 * Pops $token and returns the parameters as an array or string
	 *
	 * @param string $token
	 * @param boolean $returnAsString
	 * @return mixed Returns an array or string
	 */
	function popParameters(&$token, $returnAsString = false) {
		$parameters = array();
		$paramString = '';
		$st = strpos($token,'?',0);
		if ($st) {
			$splitter = "&";
			$paramString = substr($token, $st+1);			
			$token = substr($token, 0, $st); // remove param string from name
			if(!$returnAsString) {
				// convert &amp; to &
				if (strpos($paramString, "&amp;")>0) {
					$paramString = preg_replace('/&amp;([a-zA-Z0-9_]+\=)/','&\\1',$paramString);
				}
				
				// auto-escape html entities - to be optimized
				if(strPos($paramString,';')!==false) $paramString = preg_replace('/(&[\#a-z0-9]+\;)/','\\\\\1',$paramString);

				// handle escaped \&
				$hasEscape = (strpos($paramString, "\\&")>0);
				if($hasEscape) $paramString = str_replace('\&','^#^',$paramString);
				$params = split($splitter, $paramString);
				if($hasEscape) $params = str_replace('^#^','&',$params);

				// setup params
				$quote = '`';
				$length = count($params);
				for($x=0; $x<$length; $x++) {
					if (strpos($params[$x], '=', 0)) {
						$nv = explode("=", $params[$x], 2);
						$name = $nv[0];
						$value = $nv[1];
						$fp = strpos($value,$quote);
						$lp = strrpos($value,$quote);
						if(!($fp===false && $lp===false)) {
							$value = substr($value,$fp+1,$lp-1); // get param value;
						}else $value = trim($value);						

						// (Pass By Ref Value) - check if value passed is an object
						if (substr($value,0,2)=='##') {
							// it might be an array, resource or object. let's check it!
							if (isset($this->passByRefValue[$value])) $value = &$this->passByRefValue[$value];
						}  

						if(!isset($parameters[$name])) $parameters[$name] = $value;
						else {
							// store multiple values inside an array
							if(is_array($parameters[$name])) $parameters[$name][] = $value;
							else $parameters[$name] = array($parameters[$name],$value);
						}
					}
				} 
			}
		}
		return ($returnAsString) ? $paramString : $parameters;
	}
	
	/**
	 * process a language term and return value as $output
	 *
	 * @param string $token  
	 * @param string $output
	 */
	function processLanguage(&$token,&$output,&$replaceSimilar) {
		global $_lang;
		if(!$token) return '';
		$replaceSimilar = true;
		$output = isset($_lang[$token]) ? $_lang[$token]:'';
	}
	
	/**
	 * process the ouput of a Template Variable and return value as $output
	 *
	 * @param string $token
	 * @param string $output
	 */
	function processTemplateVariable(&$token,&$output) {
		global $modx;
		if(!$token) return '';
		
		$parameters = $this->popParameters($token);
		$this->currentTag = $token; // set current tag

		$path = $modx->config['base_path']."manager/includes/";
		$token = substr($token,0,1)=='#' ? substr($token,1):$token; // remove # for QuickEdit format
		$value = isset($modx->documentObject[$token]) ? $modx->documentObject[$token] : '';
				
		if(is_string($value)) $output = stripslashes($value);
		else {
			$name			= $value[0];
			$widget		 	= $value[2]; // widget (display format)
			$paramstring	= $value[3];
			$tvtype			= $value[4];
			$value	      	= $value[1];
			include_once($path.'tmplvars.format.inc.php');
			include_once($path.'tmplvars.commands.inc.php');

			if($this->enableCompatMode && strpos($value,'+]')!==false) {
				// convert to new format
				$value = str_replace(array('[+','+]'), array('[[+',']]'), $value);
			}

			// insert parameters into tv value 
			if(count($parameters)>0) {
				foreach($parameters as $key => $pValue) 
					$value = str_replace('[[+'.$key.']]', $pValue, $value);
			}
			
			if($widget=='' && $this->_nestLevel>0) {
				$value = ProcessTVCommand($value, $name);
				// Pass By Ref to calling tag if value is not a scalar 
				if($this->_nestLevel>0 && isset($value) && !is_scalar($value)) {
					$key = '##Ref-'.$this->_curTagID;
					$this->passByRefValue[$key] = $value;
					$value = $key;
				}
				$output = $value;
				
			}
			else {
				// handle display format
				$output = getTVDisplayFormat($name,$value,$widget,$paramstring,$tvtype);		  
			}
		}
	}

	/**
	 * process a Placeholder or System Setting and return value as $output
	 *
	 * @param string $token
	 * @param string $output
	 */
	function processPlaceholder(&$token,&$output,&$replaceSimilar) {
		global $modx;
		if(!$token) return '';
		$pre = substr($token,0,1);
		if ($pre=='+') {		
			// handle system settings placeholder
			$value = $modx->config[substr($token,1)]; // remove + for System settings
			$replaceSimilar = true; // replace similar token
		}
		elseif($pre==':') {
			// handle form-field placeholders
			$filters = '';
			$name = substr($token,1);
			if(strpos($name,'|')!==false) {
				$filters = explode('|',$name,2);
				$name = $filters[0];
				$filters = '|'.strtolower($filters[1]).'|';
			}
			if (isset($_POST[$name])) $value = $_POST[$name];
			else $value = isset($_GET[$name]) ? $value = $_GET[$name] : '';
			if(get_magic_quotes_gpc()) $value = stripslashes($value); // remove magic quotes
			// apply filters
			if(strpos($filters,'|raw|')===false)  {
				if(strpos($filters,'|escape|')!==false) $value = $modx->db->escape($value); // escape quotes using db escape
				if(strpos($filters,'|strip|')!==false) $value = $modx->stripTags($value); // stip away tags
				else {
					$value = htmlspecialchars($value);
					$value = str_replace('[','&#91;',$value);
					$value = str_replace(']','&#93;',$value);
				}
			}
			$replaceSimilar = true; // replace similar token		
		}		
		else {
			// handle normal placeholder
			if(isset($modx->placeholders[$token])) 
				$value = $modx->placeholders[$token];
			elseif($this->_nestLevel>0) {
				$value = ''; // return empty string if inside a another tag and value not set
			}
			else {				
				$value = ''; 
				$token = null;  // process empty placeholders later.
				$this->_hasMPls = true; // flag as missing
			}
		}
		$output =  $value;
	}

	/**
	 * process a template and return value as $output
	 *
	 * @param string $token
	 * @param string $output
	 */
	function processTemplate(&$token,&$output) {
		global $modx;
		if(!$token) return '';
		$parameters = $this->popParameters($token);		
		
		if(count($parameters)==0) $output = $modx->getChunk($token);
		else {
			$output = $modx->parseChunk($token,$parameters,'[[+',']]');
		}
	}

	/**
	 * process conditional tags and return value as $output
	 *
	 * @param string $token
	 * @param string $output
	 */
	function processCondition(&$token,&$output) {
		global $modx;
		if(!$token) return '';
		$p = $this->popParameters($token);		
		$then = isset($p['then']) ? $p['then'] : '';
		$else = isset($p['else']) ? $p['else'] : '';
		switch ($token) {
			case 'IF-LOGIN':			
				$interface = isset($p['interface']) ? $p['interface']: 'web';
				if ($interface=='manager' && isset($_SESSION['mgrValidated'])) $output = $then;
				elseif ($interface=='web' && isset($_SESSION['webValidated'])) $output = $then;
				else $output = $else ;
				break;
			case 'IF-ROLES':
				$access = isset($p['roles']) ? $modx->hasPermission(explode(',',$p['roles'])):0;
				$output = $access ? $then : $else ;
				break;
			case 'IF-GROUPS':
				$access = isset($p['groups']) ? $modx->isMemberOfWebGroup(explode(',',$p['groups'])):0;
				$output = $access ? $then : $else ;
				break;
			case 'IF-EMPTY':			
				$output = (!isset($p['value']) || empty($p['value'])) ? $then : $else ;
				break;
			case 'IF-ISSET':			
				$output = (isset($p['value']) && !empty($p['value'])) ? $then : $else ;
				break;
			case 'IF-COMPARE':
				$output = ($p['value']==$p['equal']) ? $then : $else;
				break;
			case 'IF-MATCH':
				$pattern = isset($p['pattern']) ? $p['pattern']:'';
				$value = isset($p['value']) ? $p['value']:'';
				$output =  preg_match($pattern,$value) ? $then : $else ;
				break;
			case 'IF-BROWSER':
				$pattern = isset($p['match']) ? $p['match']:'';
				$value = $_SERVER['HTTP_USER_AGENT'];
				$output =  $pattern && preg_match($pattern,$value) ? $then : $else ;
				break;
			case 'REPEAT-WHILE':
				$src = isset($p['source']) ? $p['source']:'';
				$offset = isset($p['offset']) ? $p['offset'] : '';
				$step = isset($p['step']) ? $p['step'] : '';
				$pop = isset($p['pop']) ? $p['pop'] : '';
				$tpl = isset($p['tpl']) ? $p['tpl'] : '';
				$altTpl = isset($p['altTpl']) ? $p['altTpl'] :'';
				include_once($modx->config['base_path'].'manager/includes/extenders/parser.helper.inc.php');
				$output = parser_RepeatWhile($src,$offset,$step,$pop,$tpl,$altTpl);
				break;
		}
	}
	
	/**
	* process a document link and return value as $output
	*/	 
	function processLink(&$token,&$output,&$replaceSimilar) {
		global $modx;
		if(!$token) return '';
		$replaceSimilar = true; // replace similar token	
		$parameters = $this->popParameters($token,true);
		$permaLink = false;
		if(substr($token,0,1)=='~') {
			$token = substr($token,1); // remove extra ~ for perma link format		
			$permaLink = true; 
		}
		$output = $permaLink ? $this->makePermaLink($token,$parameters) : $modx->makeUrl($token,'',$parameters);
	}	
	// makes a permanent url to the specified document id
	function makePermaLink($id,$params='') {
		global $modx;
		if(!is_numeric($id)) return;
		if ($params) $params ='&'.$params;
		return $modx->config['base_url'].'index.php?id='.$id.$params;
	}
	
	// process the output of Snippet and return value as $output
	// when $_params is an array $token is not parsed
	function processSnippet(&$token, &$output, $_params = '') {
		global $modx;
		if(!$token) return '';
		
		// get parameters
		$defParams = '';
		$parameters = $_params!='' ? $_params : $this->popParameters($token);
		$this->currentTag = $token; // set current tag

		// lookup snippet and return it's function name and default properties
		$this->lookupSnippet($token, $objname, $properties);
		if(is_string($properties) && $properties!='') $defParams = unserialize($properties);
		else $defParams = $properties; 
	
		// merge default properis and custom params
		if ($defParams) $parameters = array_merge($defParams,$parameters);
		
		$modx->event->params = &$parameters; // store params inside event object
		include_once($modx->config['base_path'].'assets/cache/siteSnippets.cache.php');
		$php_errormsg = null;
		ob_start();
			$snip = '';
			if(function_exists($objname)) $snip = $objname($modx,$parameters);
			$msg = ob_get_contents();
		ob_end_clean();
		if ($msg && isset($php_errormsg)) {
			if(!strpos($php_errormsg,'Deprecated')) { // ignore php5 strict errors
				// log error
				$modx->logEvent(1,3,"<b>$php_errormsg</b><br /><br /> $msg",$this->currentSnippet." - Snippet");
				if($modx->isBackend()) $modx->Event->alert("An error occurred while loading. Please see the event log for more information<p />$msg");
			}
		}
		unset($modx->event->params);
		$value = $msg ? $msg.$snip : $snip;			

		// pass by directly to calling tag if value is not a scalar 
		if($this->_nestLevel>0 && isset($value) && !is_scalar($value)) {
			$key = '##Ref-'.$this->_curTagID;
			$this->passByRefValue[$key] = $value;
			$value = $key;
		}	
		// set output value	   
		$output = $value; 
	}	

	
	function processTag(&$conf, &$outputSrc, &$cacheSource, $startpos=0, $_dropTag=false, $_tagPrefix = '[[', $_nest = 0) {
		global $modx;

		if($modx->debugEnabled) $tstart = $modx->getMicroTime();  //debug
		
		$startTag = strpos($outputSrc,$_tagPrefix,$startpos);		// find tag prefix. e.g.  [[		
		if ($startTag !== false) {
			$endTag = strpos($outputSrc,']]',$startTag+2);	// find end ]]
			$nextStartTag = strpos($outputSrc,$_tagPrefix,$startTag+2);	// find nested tag prefix e.g. [[

			// we can have more than one nested variable in a call!
			// loop while nested variable in a tag call!
			if ($nextStartTag!==false) {
				$clvl = 0; $rlvl = array();			
				while($nextStartTag!==false && $nextStartTag<$endTag) {	

					// detect recursion
					if ($nextStartTag<$conf['eofLT']) {
						$tagLevel++;
						$conf['recurLevel']++; // increment recurlevel
						
						if($tagLevel>=$this->maxTagRecurLevel) {
							$_dropTagN = true;
							$this->processTag($conf,$outputSrc, $cacheSource, $startTag+2, $_dropTagN, $_tagPrefix, $_nest+1); // recursive loop for nested [[]]
							$nextStartTag = strpos($outputSrc,$_tagPrefix ,$conf['eofLT']);	// find nested tag prefix [[
							$endTag = strpos($outputSrc,']]', $conf['eofLT']);	// find new ending ]]
							$tagLevel=0;continue;
						}
					}
					else $tagLevel = 0;
					
					// process nested tag
					$_dropTagN = ($conf['recurLevel'] == $this->maxRecursiveCalls) ? true : $_dropTag ;
					$this->processTag($conf,$outputSrc, $cacheSource, $startTag+2, $_dropTagN, $_tagPrefix, $_nest+1); // recursive loop for nested [[]]
					$endTag = strpos($outputSrc,']]', $startTag+2);	// find new ending ]]					

					// have we exceeded maxRecursiveCalls?
					if($conf['recurLevel'] >= $this->maxRecursiveCalls) {
						return 0;
					}


					// traverse on all the snippet variables, and in the end break out of the while loop
					$nextStartTag = strpos($outputSrc,$_tagPrefix ,$startTag+2);	// find nested tag prefix [[
				}	
			}
			
			if($endTag>$startTag) {			
				$value = '';
				$tokenSizeOffset = 4;
				$isCacheable = true;
				$replaceSimilar = false;
				// get token
				$token = substr($outputSrc, $startTag+2, $endTag-$startTag-2);	// get code within [[  ]]
				// remove the non-cache indicator
				if (substr($token,0,1)=='!') {
					$token = substr($token,1);
					$isCacheable = false;
					$tokenSizeOffset += 1; // make sure we add the character that we have removed to the offset
				}
				// strip away the first char (the *, or + , etc.
				$tokenQ = substr($token,1);	
				// remove tag (e.g. if recurlevel > maxrecurlevels in circular reference)
				if ($_dropTag) $value = '';
				else {
				   	$this->_curTagID++; // tag id
				   	$this->_nestLevel = $_nest;	// set nest level
					// determine token type tag, like '[[*' or '[[+' , etc
					$tokenType = '[['.substr($token, 0, 1);
$this->lastTag = $tokenQ;
					switch($tokenType) {
						case '[[^': // ignore timing tags
							if($tokenQ!='rand') unset($tokenQ); 
							else srand(); $value = rand();
							break;
							
						case '[[:': // parse conditional tags
							$this->processCondition($tokenQ,$value);
							break;

						case '[[~': // parse hyper links
							$this->processLink($tokenQ,$value,$replaceSimilar);
							break;
								
						case '[[%': // parse language tag
							$this->processLanguage($tokenQ,$value,$replaceSimilar);
							break;					
							
						case '[[+':	// parse placeholders, settings and form-fields
							$this->processPlaceholder($tokenQ,$value,$replaceSimilar);
							break;

						case '[[$': // parse chunks					
							$this->processTemplate($tokenQ,$value);
							break; 							
							
						case '[[*': // parse TVs/Content Fields
							$this->processTemplateVariable($tokenQ,$value);
							break;
							
						default:	// parse Snippets/Widgets
							$tokenQ = $token;
							$this->processSnippet($tokenQ, $value);
							break;
					}

					// convert old tags to new
					if($this->enableCompatMode) $modx->convertTags($value);
				}
				
				//debug
				if($modx->debugEnabled) {
					$tend = $modx->getMicroTime(); 
					$totaltime = $tend-$tstart;
				}
				
				// insert rendered token
				if(isset($tokenQ)){ // don't replace empty tokens (used by placeholders)
					
					// calculate cache start and end tags
					$updateCache = false;
					$valueSize = strlen($value);
					$conf['eofLT'] = $startTag + $valueSize; // set end of last tag, used to detect circular reference 
					if($conf['buildCache']) {												
						if($startTag < $conf['cursorEnd']) {
							// offset for nested tags inside non-cache content
							$tokenSize = strLen($token) + $tokenSizeOffset;
							$conf['cursorEnd'] += $valueSize - $tokenSize;
							$conf['parserCacheOffset'] += $tokenSize - $valueSize;
						}						
						elseif((isset($this->nonCacheItems[$tokenQ]) ? false : $isCacheable)) {
							$cStartTag = $startTag + $conf['parserCacheOffset'];	// cache tag start
							$cEndTag = $endTag + $conf['parserCacheOffset'];		// cache tag end
							$updateCache = true;
						}
						else {
							// get offset for non-cache content
							$tokenSize = strLen($token) + $tokenSizeOffset;
							$conf['cursorEnd'] = $startTag + $valueSize;
							$conf['parserCacheOffset'] += $tokenSize - $valueSize;
						}					
					}

					// update source and cache
					if($replaceSimilar) {
						// replace similar tokens
						if(!$isCacheable) $token = '!'.$token;						
						$outputSrc =  str_replace('[['.$token.']]', $value, $outputSrc);
						if($updateCache && $conf['buildCache']) {
							$cacheSource = str_replace('[['.$token.']]', $value, $cacheSource);
						}
					}
					else {
						// update text source and cacheSource - replace only this token
						$outputSrc = substr_replace($outputSrc, $value, $startTag, $endTag-$startTag+2);
						if ($updateCache && $conf['buildCache']) {
							$cacheSource = substr_replace($cacheSource,$value,$cStartTag,($cEndTag-$cStartTag+2));
						}
					}

					// debug
					if($modx->debugEnabled) {
						$tag = '<img src="'.$modx->config['base_url'].'manager/media/debug/tag_green.gif" />';
						$level = $_nest>0 ? str_repeat('&nbsp;&nbsp;&nbsp;',$_nest).'<img src="'.$modx->config['base_url'].'manager/media/debug/arrow.gif" />':'';
						$output = ($token !== '') ? 'ok': 'no output';
						$modx->debug('Parser','<div><div style="float:left">'.$level.$tag.' Tag #'.str_pad($this->_curTagID.'',2,'0',STR_PAD_LEFT).': <span style="color:#0000DA"><span style="color:#DA0000">[[</span>'.htmlspecialchars($token).'<span style="color:#DA0000">]]</span></span> <span style="color:#707070">executed in</span> '.sprintf("%2.4f s", $totaltime).'  <span style="color:#800000">Output = </span></div><div align="left" style="float:right;border:1px dotted #e0e0e0;padding:2px">'.$value.'</div></div>');
					}
				}
				else { // ignore token						
					$tokenSize = strLen($token) + $tokenSizeOffset;
					$conf['eofLT'] = $endTag+2;//$startTag + $tokenSize; // set end of last tag, used to detect circular reference 
					return $endTag+2; // return new pointer position
				}			
			}
		}
	}

	function lookupSnippet($name, &$objname, &$properties) {
		global $modx;
		if(isset($modx->snippetCache[$name])) {
			$objname = $modx->snippetCache[$name];
			$properties = isset($modx->snippetCache[$name."Props"]) ? $modx->snippetCache[$name."Props"]:'';
		} 
		else {
			// get from db and store a copy inside cache
			$sql = " SELECT ss.*,sm.properties as 'sharedproperties' ".
					"FROM ".$modx->getFullTableName("site_snippets")." ss ". 
					"LEFT JOIN ".$modx->getFullTableName("site_modules")." sm on sm.guid=ss.moduleguid ". 
					"WHERE ss.name='".$modx->db->escape($name)."';";
			$result = $modx->db->query($sql);
			if($modx->db->getRecordCount($result)==1) {
				$row = $modx->db->getRow($result);
				$fnCode = '$etomite=$modx; if(is_array($params)) extract($params, EXTR_SKIP);'."\n".$row['snippet'];
				$objname = 'snip_0x'.create_function('&$modx,$params',$fnCode);
				$modx->snippetCache[$row['name']] = $objname;
				$props = $this->parseProperties($row['properties'].' '.$row['sharedproperties']);
				if(count($props)>0) {
					$properties = $modx->snippetCache[$row['name']."Props"] = $props;
				}
			} 
			else {
				$objname = '';
				$properties = '';
			}
		}
	}

	// parses a resource property string and returns the result as an array
	function parseProperties($propertyString){
		$parameter = array();
		if($propertyString!='') {
			$tmpParams = explode('&',$propertyString);
			for($x=0; $x<count($tmpParams); $x++) {
				if (strpos($tmpParams[$x], '=', 0)) {
					$pTmp = explode('=', $tmpParams[$x]);
					$pvTmp = explode(';', trim($pTmp[1]));
					if ($pvTmp[1]=='list' && $pvTmp[3]!='') $parameter[trim($pTmp[0])] = $pvTmp[3]; //list default
					else if($pvTmp[1]!='list' && $pvTmp[2]!='') $parameter[trim($pTmp[0])] = $pvTmp[2];
				}
			}
		}
		return $parameter;
	}	

	
 } 
 ?>
