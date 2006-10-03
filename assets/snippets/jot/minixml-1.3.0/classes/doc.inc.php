<?php


/***************************************************************************************************
****************************************************************************************************
*****
*****      MiniXML - PHP class library for generating and parsing XML.
*****                                            
*****      Copyright (C) 2002-2005 Patrick Deegan, Psychogenic.com
*****      All rights reserved.
*****
*****      http://minixml.psychogenic.com    
*****                                                       
*****   This program is free software; you can redistribute 
*****   it and/or modify it under the terms of the GNU      
*****   General Public License as published by the Free     
*****   Software Foundation; either version 2 of the        
*****   License, or (at your option) any later version.     
*****                                                       
*****   This program is distributed in the hope that it will
*****   be useful, but WITHOUT ANY WARRANTY; without even   
*****   the implied warranty of MERCHANTABILITY or FITNESS  
*****   FOR A PARTICULAR PURPOSE.  See the GNU General      
*****   Public License for more details.                    
*****                                                       
*****   You should have received a copy of the GNU General  
*****   Public License along with this program; if not,     
*****   write to the Free Software Foundation, Inc., 675    
*****   Mass Ave, Cambridge, MA 02139, USA.
*****
*****
*****   You may contact the author, Pat Deegan, through the     
*****   contact section at http://www.psychogenic.com
*****
*****   Much more information on using this API can be found on the
*****   official MiniXML website - http://minixml.psychogenic.com
*****	or within the Perl version (XML::Mini) available through CPAN
*****
****************************************************************************************************
***************************************************************************************************/


/*

#define("MINIXML_COMPLETE_REGEX",'/<\s*([^\s>]+)([^>]+)?>(.*?)<\s*\/\\1\s*>\s*([^<]+)?(.*)|\s*<!--(.+?)-->\s*|^\s*<\s*([^\s>]+)([^>]*)\/\s*>\s*([^<>]+)?|<!\[CDATA\s*\[(.*?)\]\]\s*>|<!DOCTYPE\s*([^\[]*)\[(.*?)\]\s*>|<!ENTITY\s*([^"\'>]+)\s*(["\'])([^\14]+)\14\s*>|^([^<]+)(.*)/smi');
*/

define("MINIXML_COMPLETE_REGEX",'/^\s*<\s*([^\s>]+)(\s+[^>]+)?>(.*?)<\s*\/\1\s*>\s*([^<]+)?(.*)|^\s*<!--(.+?)-->\s*(.*)|^\s*<\s*([^\s>]+)([^>]+)\/\s*>\s*(.*)|^\s*<!\[CDATA\s*\[(.*?)\]\]\s*>\s*(.*)|^\s*<!DOCTYPE\s*([^\[]*)\[(.*?)\]\s*>\s*(.*)|^\s*<!ENTITY\s*([^"\'>]+)\s*(["\'])([^\17]+)\17\s*>\s*(.*)|^([^<]+)(.*)/smi');

/*
#define("MINIXML_SIMPLE_REGEX",
# //         1         2      3                    4       5          6                   7          8             9          #10     11
#'/\s*<\s*([^\s>]+)([^>]+)?>(.*?)<\s*\/\\1\s*>\s*([^<]+)?(.*)|\s*<!--(.+?)-->\s*|\s*<\s*([^\s>]+)([^>]*)\/\s*>\s*([^<>]+)?|^([^<]+)(.*)/smi');

*/
define("MINIXML_SIMPLE_REGEX",'/^\s*<\s*([^\s>]+)(\s+[^>]+)?>(.*?)<\s*\/\1\s*>\s*([^<]+)?(.*)|^\s*<!--(.+?)-->\s*(.*)|^\s*<\s*([^\s>]+)([^>]+)\/\s*>\s*(.*)|^([^<]+)(.*)/smi');


require_once(MINIXML_CLASSDIR . "/element.inc.php");

/***************************************************************************************************
****************************************************************************************************
*****
*****					  MiniXMLDoc
*****
****************************************************************************************************
***************************************************************************************************/

/* MiniXMLDoc class
**
** The MiniXMLDoc class is the programmer's handle to MiniXML functionality.
**
** A MiniXMLDoc instance is created in every program that uses MiniXML.
** With the MiniXMLDoc object, you can access the root MiniXMLElement, 
** find/fetch/create elements and read in or output XML strings.
**/
class MiniXMLDoc {
	var $xxmlDoc;
	var $xuseSimpleRegex;
	var $xRegexIndex;
	
	/* MiniXMLDoc [XMLSTRING]
	** Constructor, create and init a MiniXMLDoc object.
	**
	** If the optional XMLSTRING is passed, the document will be initialised with
	** a call to fromString using the XMLSTRING.
	**
	*/
	function MiniXMLDoc ($string=NULL)
	{
		/* Set up the root element - note that it's name get's translated to a
		** <? xml version="1.0" ?> string.
		*/
		$this->xxmlDoc = new MiniXMLElement("PSYCHOGENIC_ROOT_ELEMENT");
		$this->xuseSimpleRegex = MINIXML_USE_SIMPLE;
		if (! is_null($string))
		{
			$this->fromString($string);
		}
		
	}
	
	function init ()
	{
		$this->xxmlDoc = new MiniXMLElement("PSYCHOGENIC_ROOT_ELEMENT");
	}
	
	/* getRoot
	** Returns a reference the this document's root element
	** (an instance of MiniXMLElement)
	*/
	function &getRoot ()
	{
		return $this->xxmlDoc;
	}
	
	
	/* setRoot NEWROOT
	** Set the document root to the NEWROOT MiniXMLElement object.
	**/
	function setRoot (&$root)
	{
		if ($this->isElement($root))
		{
			$this->xxmlDoc = $root;
		} else {
			return _MiniXMLError("MiniXMLDoc::setRoot(): Trying to set non-MiniXMLElement as root");
		}
	}
	
	/* isElement ELEMENT
	** Returns a true value if ELEMENT is an instance of MiniXMLElement,
	** false otherwise.
	*/
	function isElement (&$testme)
	{
		if (is_null($testme))
		{
			return 0;
		}
		
		return method_exists($testme, 'MiniXMLElement');
	}
	
	
	/* isNode NODE
	** Returns a true value if NODE is an instance of MiniXMLNode,
	** false otherwise.
	*/
	function isNode (&$testme)
	{
		if (is_null($testme))
		{
			return 0;
		}
		
		return method_exists($testme, 'MiniXMLNode');
	}
	
	
	/* createElement NAME [VALUE]
	** Creates a new MiniXMLElement with name NAME.
	** This element is an orphan (has no assigned parent)
	** and will be lost unless it is appended (MiniXMLElement::appendChild())
	** to an element at some point.
	**
	** If the optional VALUE (string or numeric) parameter is passed,
	** the new element's text/numeric content will be set using VALUE.
	**
	** Returns a reference to the newly created element (use the =& operator)
	*/
	function &createElement ($name=NULL, $value=NULL)
	{
		$newElement = new MiniXMLElement($name);
		
		if (! is_null($value))
		{
			if (is_numeric($value))
			{
				$newElement->numeric($value);
			} elseif (is_string($value))
			{
				$newElement->text($value);
			}
		}
		
		return $newElement;
	}
	
	/* getElement NAME
	** Searches the document for an element with name NAME.
	**
	** Returns a reference to the first MiniXMLElement with name NAME,
	** if found, NULL otherwise.
	**
	** NOTE: The search is performed like this, returning the first 
	** 	 element that matches:
	**
	** - Check the Root Element's immediate children (in order) for a match.
	** - Ask each immediate child (in order) to MiniXMLElement::getElement()
	**  (each child will then proceed similarly, checking all it's immediate
	**   children in order and then asking them to getElement())
	*/
	function &getElement ($name)
	{
	
		$element = $this->xxmlDoc->getElement($name);
		if (MINIXML_DEBUG > 0)
		{
			_MiniXMLLog("MiniXMLDoc::getElement(): Returning element $element");
		}
		
		return $element;
		
	}
	
	
	/* getElementByPath PATH
	** Attempts to return a reference to the (first) element at PATH
	** where PATH is the path in the structure from the root element to
	** the requested element.
	**
	** For example, in the document represented by:
	**
	**	 <partRateRequest>
	**	  <vendor>
	**	   <accessid user="myusername" password="mypassword" />
	**	  </vendor>
	**	  <partList>
	**	   <partNum>
	**	    DA42
	**	   </partNum>
	**	   <partNum>
	**	    D99983FFF
	**	   </partNum>
	**	   <partNum>
	**	    ss-839uent
	**	   </partNum>
	**	  </partList>
	**	 </partRateRequest>
	**
	** 	$accessid =& $xmlDocument->getElementByPath('partRateRequest/vendor/accessid');
	**
	** Will return what you expect (the accessid element with attributes user = "myusername"
	** and password = "mypassword").
	**
	** BUT be careful:
	**	$accessid =& $xmlDocument->getElementByPath('partRateRequest/partList/partNum');
	**
	** will return the partNum element with the value "DA42".  Other partNums are 
	** inaccessible by getElementByPath() - Use MiniXMLElement::getAllChildren() instead.
	**
	** Returns the MiniXMLElement reference if found, NULL otherwise.
	*/
	function &getElementByPath ($path)
	{
	
		$element = $this->xxmlDoc->getElementByPath($path);
		if (MINIXML_DEBUG > 0)
		{
			_MiniXMLLog("Returning element $element");
		}
		
		return $element;
		
	}
	
	function fromFile ($filename)
	{
		$modified = stat($filename);
		if (! is_array($modified))
		{
			_MiniXMLError("Can't stat '$filename'");
			return NULL;
		}
		
		if (MINIXML_USEFROMFILECACHING > 0)
		{
			
			$tmpName = MINIXML_FROMFILECACHEDIR . '/' . 'minixml-' . md5($filename);
			if (MINIXML_DEBUG > 0) 
			{
					_MiniXMLLog("Trying to open cach file $tmpName (for '$filename')");
			}
			$cacheFileStat = stat($tmpName);
			
			if (is_array($cacheFileStat) && $cacheFileStat[9] > $modified[9])
			{
			
				$fp = @fopen($tmpName,"r");
				if ($fp)
				{
					if (MINIXML_DEBUG > 0) 
					{
						_MiniXMLLog("Reading file '$filename' from object cache instead ($tmpName)");
					}
					$tmpFileSize = filesize($tmpName);
					$tmpFileContents = fread($fp, $tmpFileSize);
					
					$serializedObj = unserialize($tmpFileContents);
					
					$sRoot =& $serializedObj->getRoot();
					if ($sRoot)
					{
						if (MINIXML_DEBUG > 0)
						{
							_MiniXMLLog("Restoring object from cache file $tmpName");
						}
						$this->setRoot($sRoot);
						
						/* Return immediately, such that we don't refresh the cache */
						return $this->xxmlDoc->numChildren();
						
					} /* end if we got a root element from unserialized object */
					
				} /* end if we sucessfully opened the file */
				
				
			} /* end if cache file exists and is more recent */
		}
		
		
		ob_start();
		readfile($filename);
		$filecontents = ob_get_contents();
		ob_end_clean();
		
		$retVal = $this->fromString($filecontents);
		
		if (MINIXML_USEFROMFILECACHING > 0)
		{
			$this->saveToCache($filename);
		}
		
		return $retVal;
			
		
	}
	
	function saveToCache ($filename)
	{
		$tmpName = MINIXML_FROMFILECACHEDIR . '/' . 'minixml-' . md5($filename);
		
		$fp = @fopen($tmpName, "w");
		
		if (MINIXML_DEBUG > 0)
		{
			_MiniXMLLog("Saving object to cache as '$tmpName'");
		}
		
		if ($fp)
		{
			
			$serialized = serialize($this);
			fwrite($fp, $serialized);
			
			fclose($fp);
		} else {
			_MiniXMLError("Could not open $tmpName for write in MiniXMLDoc::saveToCache()");
		}
		
	}
	
	/* fromString XMLSTRING
	** 
	** Initialise the MiniXMLDoc (and it's root MiniXMLElement) using the 
	** XML string XMLSTRING.
	**
	** Returns the number of immediate children the root MiniXMLElement now
	** has.
	*/
	function fromString (&$XMLString)
	{
		$useSimpleFlag = $this->xuseSimpleRegex;
		
		
		if ($this->xuseSimpleRegex || ! preg_match('/<!DOCTYPE|<!ENTITY|<!\[CDATA/smi', $XMLString))
		{
			$this->xuseSimpleRegex = 1;
			
			$this->xRegexIndex = array(
							'biname'	=> 1,
							'biattr'	=> 2,
							'biencl'	=> 3,
							'biendtxt'	=> 4,
							'birest'	=> 5,
							'comment'	=> 6,
							'uname'		=> 8,
							'uattr'		=> 9,
							'plaintxt'	=> 11,
							'plainrest'	=> 12
				);
			$regex = MINIXML_SIMPLE_REGEX;
			
		} else {

			$this->xRegexIndex = array(
							'biname'	=> 1,
							'biattr'	=> 2,
							'biencl'	=> 3,
							'biendtxt'	=> 4,
							'birest'	=> 5,
							'comment'	=> 6,
							'uname'		=> 8,
							'uattr'		=> 9,
							'cdata'		=> 11,
							'doctypedef'	=> 13,
							'doctypecont'	=> 14,
							'entityname'	=> 16,
							'entitydef'	=> 18,
							'plaintxt'	=> 20,
							'plainrest'	=> 21
				);
			$regex = MINIXML_COMPLETE_REGEX;
		}
			
		$this->fromSubString($this->xxmlDoc, $XMLString, $regex);
		
		$this->xuseSimpleRegex = $useSimpleFlag;
		
		return $this->xxmlDoc->numChildren();
		
	}
	
	
	function fromArray (&$init, $params=NULL)
	{
		
		$this->init();
		
		
		if (! is_array($init) )
		{
			
			return _MiniXMLError("MiniXMLDoc::fromArray(): Must Pass an ARRAY to initialize from");
		}
		
		if (! is_array($params) )
		{
			$params = array();
		}
		
		if ( $params["attributes"] && is_array($params["attributes"]) )
		{
			
			$attribs = array();
			foreach ($params["attributes"] as $attribName => $value)
			{
				if (! (array_key_exists($attribName, $attribs) && is_array($attribs[$attribName]) ) )
				{
					$attribs[$attribName] = array();
				}
				
				if (is_array($value))
				{
					foreach ($value as $v)
					{
						if (array_key_exists($v, $attribs[$attribName]))
						{
							$attribs[$attribName][$v]++;
						} else {
							$attribs[$attribName][$v] = 1;
						}
					}
				} else {
					if (array_key_exists($value,$attribs[$attribName]))
					{
						$attribs[$attribName][$value]++;
					} else {
						$attribs[$attribName][$value] = 1;
					}
				}
			}
			
			// completely replace old attributes by our optimized array
			$params["attributes"] = $attribs;
		} else {
			$params["attributes"] = array();
		}
		
		foreach ($init as $keyname => $value)
		{
			$sub = $this->_fromArray_getExtractSub($value);
			
		
			$this->$sub($keyname, $value, $this->xxmlDoc, $params);
		
		}
		
		
		return $this->xxmlDoc->numChildren();
		
	}
	
	function _fromArray_getExtractSub ($v)
	{
		// is it a string, a numerical array or an associative array?
		$sub = "_fromArray_extract";
		if (is_array($v))
		{
			if (_MiniXML_NumKeyArray($v))
			{
				// All numeric - assume it is a "straight" array
				$sub .= "ARRAY";
			} else {
				$sub .= "AssociativeARRAY";
			}
			
		} else {
			$sub .= "STRING";
		}
		
	
		return $sub;
	}
	
	
	
	
		
	function _fromArray_extractAssociativeARRAY ($name, &$value, &$parent, &$params)
	{
		
		$thisElement =& $parent->createChild($name);
		
		foreach ($value as $key => $val)
		{
		
			$sub = $this->_fromArray_getExtractSub($val);
			
		
			$this->$sub($key, $val, $thisElement, $params);
		
		}
		
		return;
	}

	function _fromArray_extractARRAY ($name, &$value, &$parent, &$params)
	{
		
		foreach ($value as $val)
		{
			$sub = $this->_fromArray_getExtractSub($val);
			
		
			$this->$sub($name, $val, $parent, $params);
			
		}
		
		return;
	}
		

	function _fromArray_extractSTRING ($name, $value="", &$parent, &$params)
	{
		
		$pname = $parent->name();
		
		if ( 
			( array_key_exists($pname, $params['attributes']) && is_array($params['attributes'][$pname])
			  && array_key_exists($name, $params['attributes'][$pname]) && $params['attributes'][$pname][$name])
		     || ( 
		     	  array_key_exists('-all', $params['attributes']) && is_array($params['attributes']['-all']) 
			  && array_key_exists($name, $params['attributes']['-all']) && $params['attributes']['-all'][$name])
		   )
		{
			$parent->attribute($name, $value);
		} elseif ($name == '-content') {
		
			$parent->text($value);
		} else {
			$parent->createChild($name, $value);
		}
		
		return;
	}

	
	
	function time ($msg)
	{
		error_log("\nMiniXML msg '$msg', time: ". time() . "\n");
	}
	// fromSubString PARENTMINIXMLELEMENT XMLSUBSTRING
	// private method, called recursively to parse the XMLString in little sub-chunks.
	function fromSubString (&$parentElement, &$XMLString, &$regex)
	{
		//$this->time('fromSubStr');
		
		if (is_null($parentElement) || empty($XMLString) || preg_match('/^\s*$/', $XMLString))
		{
			return;
		}
		if (MINIXML_DEBUG > 0) 
		{
			_MiniXMLLog("Called fromSubString() with parent '" . $parentElement->name() . "'\n");
		}
		
		$matches = array();
		if (preg_match_all(  $regex, $XMLString, $matches))
		{
			// $this->time('a match');
		
			$mcp = $matches;
			
			$numMatches = count($mcp[0]);
			
			for($i=0; $i < $numMatches; $i++)
			{
				if (MINIXML_DEBUG > 1)
				{
					_MiniXMLLog ("Got $numMatches CHECKING: ". $mcp[0][$i] . "\n"); 
				}
		
				$uname = $mcp[$this->xRegexIndex['uname']][$i];
				$comment = $mcp[$this->xRegexIndex['comment']][$i];
				if ($this->xuseSimpleRegex)
				{
					$cdata = NULL;
					$doctypecont = NULL;
					$entityname = NULL;
					
					$tailEndIndexes = array(5, 7, 10, 12);
				} else {
				
					$cdata = $mcp[$this->xRegexIndex['cdata']][$i];
					$doctypecont = $mcp[$this->xRegexIndex['doctypecont']][$i];
					$entityname = $mcp[$this->xRegexIndex['entityname']][$i];
					
					$tailEndIndexes = array(5, 7, 10, 12, 15, 19, 21);
				}
				
				$plaintext = $mcp[$this->xRegexIndex['plaintxt']][$i];
				
				// check all the 'tailend' (i.e. rest of string) matches for more content
				$moreContent = '';
				$idx = 0;
				while (empty($moreContent) && ($idx < count($tailEndIndexes)))
				{
					if (! empty($mcp[$tailEndIndexes[$idx]][$i]))
					{
						$moreContent = $mcp[$tailEndIndexes[$idx]][$i];
					}
					
					$idx++;
				}
				
				
				
				if ($uname)
				{
					// _MiniXMLLog ("Got UNARY $uname");
					$newElement =& $parentElement->createChild($uname);
					$this->_extractAttributesFromString($newElement, $mcp[$this->xRegexIndex['uattr']][$i]);
	
				} elseif ($comment) {
					//_MiniXMLLog ("Got comment $comment");
					$parentElement->comment($comment);
					
				} elseif ($cdata) {
					//_MiniXMLLog ("Got cdata $cdata");
					$newElement = new MiniXMLElementCData($cdata);
					$parentElement->appendChild($newElement);
				} elseif ($doctypecont) {
					//_MiniXMLLog ("Got doctype $doctypedef '" . $mcp[11][$i] . "'");
					$newElement = new MiniXMLElementDocType($mcp[$this->xRegexIndex['doctypedef']][$i]);
					$appendedChild =& $parentElement->appendChild($newElement);
					$this->fromSubString($appendedChild, $doctypecont, $regex);
					
				} elseif ($entityname ) {
					//_MiniXMLLog ("Got entity $entityname");
					$newElement = new MiniXMLElementEntity ($entityname, $mcp[$this->xRegexIndex['entitydef']][$i]);
					$parentElement->appendChild($newElement);
					
				} elseif ($plaintext) {
				
					if (! preg_match('/^\s+$/', $plaintext))
					{
						$parentElement->createNode($plaintext);
					}
					
				} elseif($mcp[$this->xRegexIndex['biname']]) {
				
					// _MiniXMLLog("Got BIN NAME: " . $mcp[$this->xRegexIndex['biname']][$i]);
					
					$nencl = $mcp[$this->xRegexIndex['biencl']][$i];
					$finaltxt = $mcp[$this->xRegexIndex['biendtxt']][$i];
					
					$newElement =& $parentElement->createChild($mcp[$this->xRegexIndex['biname']][$i]);
					$this->_extractAttributesFromString($newElement, $mcp[$this->xRegexIndex['biattr']][$i]);
					
					
					
					$plaintxtMatches = array();
					if (preg_match("/^\s*([^\s<][^<]*)/", $nencl, $plaintxtMatches))
					{
						$txt = $plaintxtMatches[1];
						$newElement->createNode($txt);
						
						$nencl = preg_replace("/^\s*([^<]+)/", "", $nencl);
					}
					

					if ($nencl && !preg_match('/^\s*$/', $nencl))
					{
						$this->fromSubString($newElement, $nencl, $regex);
					}
					
					if ($finaltxt)
					{
						$parentElement->createNode($finaltxt);
					}
					
					
				} /* end switch over type of match */
				
				if (! empty($moreContent))
				{
					$this->fromSubString($parentElement, $moreContent, $regex);
				}
			
				
			} /* end loop over all matches */
			
			
		} /* end if there was a match */
		
	} /* end method fromSubString */
		
	
	/* toString [DEPTH]
	** Converts this MiniXMLDoc object to a string and returns it.
	**
	** The optional DEPTH may be passed to set the space offset for the
	** first element.
	**
	** If the optional DEPTH is set to MINIXML_NOWHITESPACES.  
	** When it is, no \n or whitespaces will be inserted in the xml string
	** (ie it will all be on a single line with no spaces between the tags.
	**
	** Returns a string of XML representing the document.
	*/
	function toString ($depth=0)
	{
		$retString = $this->xxmlDoc->toString($depth);
		
		if ($depth == MINIXML_NOWHITESPACES)
		{
			$xmlhead = "<?xml version=\"1.0\"\\1?>";
		} else {
			$xmlhead = "<?xml version=\"1.0\"\\1?>\n ";
		}
		$search = array("/<PSYCHOGENIC_ROOT_ELEMENT([^>]*)>\s*/smi",
				"/<\/PSYCHOGENIC_ROOT_ELEMENT>/smi");
		$replace = array($xmlhead,
				"");
		$retString = preg_replace($search, $replace, $retString);
		
		
		if (MINIXML_DEBUG > 0)
		{
			_MiniXMLLog("MiniXML::toString() Returning XML:\n$retString\n\n");
		}
		
		
		return $retString;
	}
	
	
	/* toArray
	**
	** Transforms the XML structure currently represented by the MiniXML Document object 
	** into an array.
	** 
	** More docs to come - for the moment, use var_dump($miniXMLDoc->toArray()) to see 
	** what's going on :)
	*/
	
	function & toArray ()
	{
		
		$retVal = $this->xxmlDoc->toStructure();
	
		if (is_array($retVal))
		{
			return $retVal;
		}
		
		$retArray = array(
					'-content'	=> $retVal,
				);
		
		return $retArray;
	}

	
	
	
	/* getValue()
	** Utility function, call the root MiniXMLElement's getValue()
	*/
	function getValue ()
	{
		return $this->xxmlDoc->getValue();
	}
	
	
	
	/* dump
	** Debugging aid, dump returns a nicely formatted dump of the current structure of the
	** MiniXMLDoc object.
	*/
	function dump ()
	{
		return serialize($this);
	}
	
	
	
	// _extractAttributesFromString
	// private method for extracting and setting the attributs from a
	// ' a="b" c = "d"' string
	function _extractAttributesFromString (&$element, &$attrString)
	{
	
		if (! $attrString)
		{
			return NULL;
		}
		
		$count = 0;
		$attribs = array();
		// Set the attribs 
		preg_match_all('/([^\s]+)\s*=\s*([\'"])([^\2]*?)\2/sm', $attrString, $attribs);
		
		
		for ($i = 0; $i < count($attribs[0]); $i++)
		{
			$attrname = $attribs[1][$i];
			$attrval = $attribs[3][$i];
			
			if ($attrname)
			{
				$element->attribute($attrname, $attrval, '');
				$count++;
			}
		}
		
		return $count;
	}

	/* Destructor to keep things clean -- patch by Ilya */
	function __destruct()
	{
		$this->xxmlDoc = null;
	}
		
	
}



/***************************************************************************************************
****************************************************************************************************
*****
*****					   MiniXML 
*****
****************************************************************************************************
***************************************************************************************************/

/* class MiniXML (MiniXMLDoc)
**
** Avoid using me - I involve needless overhead.
**
** Utility class - this is just an name aliase for the 
** MiniXMLDoc class as I keep repeating the mistake of 
** trying to create
**
** $xml = new MiniXML();
**
*/
class MiniXML extends MiniXMLDoc {
	
	function MiniXML ()
	{
		$this->MiniXMLDoc();
	}
}



?>

