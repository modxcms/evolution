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




require_once(MINIXML_CLASSDIR . "/treecomp.inc.php");

/***************************************************************************************************
****************************************************************************************************
*****
*****					  MiniXMLNode
*****
****************************************************************************************************
***************************************************************************************************/


/* class MiniXMLNode
** MiniXMLNodes are used as atomic containers for numerical and text data
** and act as leaves in the XML tree.
**
** They have no name or children.
**
** They always exist as children of MiniXMLElements.
** For example, 
** <B>this text is bold</B>
** Would be represented as a MiniXMLElement named 'B' with a single
** child, a MiniXMLNode object which contains the string 'this text 
** is bold'.
**
** a MiniXMLNode has
** - a parent
** - data (text OR numeric)
*/

class MiniXMLNode extends MiniXMLTreeComponent {
	
	
	var $xtext;
	var $xnumeric;

	/* MiniXMLNode [CONTENTS]
	** Constructor.  Creates a new MiniXMLNode object.
	**
	*/
	function MiniXMLNode ($value=NULL, $escapeEntities=NULL)
	{
		$this->MiniXMLTreeComponent();
		$this->xtext = NULL;
		$this->xnumeric = NULL;
		
		/* If we were passed a value, save it as the 
		** appropriate type
		*/
		if (! is_null($value))
		{
			if (is_numeric($value))
			{
				if (MINIXML_DEBUG > 0)
				{
					_MiniXMLLog("Setting numeric value of node to '$value'");
				}
			
				$this->xnumeric = $value;
			} else {
				if (MINIXML_IGNOREWHITESPACES > 0)
				{
					$value = trim($value);
					$value = rtrim($value);
				}
				
				if (! is_null($escapeEntities))
				{
					if ($escapeEntities)
					{
						$value = htmlentities($value);
					}
				} elseif (MINIXML_AUTOESCAPE_ENTITIES > 0) {
					$value = htmlentities($value);
				} 
				
				if (MINIXML_DEBUG > 0)
				{
					_MiniXMLLog("Setting text value of node to '$value'");
				}
				
				$this->xtext = $value;
			
				
			} /* end if value numeric */
			
		} /* end if value passed */
			
	} /* end MiniXMLNode constructor */
	
	/* getValue
	** 
	** Returns the text or numeric value of this Node.
	*/
	function getValue ()
	{
		$retStr = NULL;
		if (! is_null($this->xtext) )
		{
			$retStr = $this->xtext;
		} elseif (! is_null($this->xnumeric))
		{
			$retStr = "$this->xnumeric";
		}
		
		if (MINIXML_DEBUG > 0)
		{
			_MiniXMLLog("MiniXMLNode::getValue returning '$retStr'");
		}
		
		return $retStr;
	}
	
	
	/* text [SETTO [SETTOALT]]
	**
	** The text() method is used to get or set text data for this node.
	**
	** If SETTO is passed, the node's content is set to the SETTO string.
	**
	** If the optional SETTOALT is passed and SETTO is false, the 
	** node's value is set to SETTOALT.  
	**
	** Returns this node's text, if set or NULL 
	**
	*/
	function text ($setToPrimary = NULL, $setToAlternate=NULL)
	{
		$setTo = ($setToPrimary ? $setToPrimary : $setToAlternate);
		
		if (! is_null($setTo))
		{
			if (! is_null($this->xnumeric) ) 
			{
				return _MiniXMLError("MiniXMLNode::text() Can't set text for element with numeric set.");
				
			} elseif (! is_string($setTo) && ! is_numeric($setTo) ) {
			
				return _MiniXMLError("MiniXMLNode::text() Must pass a STRING value to set text for element ('$setTo').");
			}
			
			if (MINIXML_IGNOREWHITESPACES > 0)
			{
				$setTo = trim($setTo);
				$setTo = rtrim($setTo);
			}
			
			
			if (MINIXML_AUTOESCAPE_ENTITIES > 0)
			{
				$setTo = htmlentities($setTo);
			} 
			
			
			if (MINIXML_DEBUG > 0)
			{
				_MiniXMLLog("Setting text value of node to '$setTo'");
			}
			
			$this->xtext = $setTo;
			
		}
		
		return $this->xtext;
	}
	
	/* numeric [SETTO [SETTOALT]]
	**
	** The numeric() method is used to get or set numerical data for this node.
	**
	** If SETTO is passed, the node's content is set to the SETTO string.
	**
	** If the optional SETTOALT is passed and SETTO is NULL, the 
	** node's value is set to SETTOALT.  
	**
	** Returns this node's text, if set or NULL 
	**
	*/
	function numeric ($setToPrim = NULL, $setToAlt = NULL)
	{
		$setTo = is_null($setToPrim) ? $setToAlt : $setToPrim;
		
		if (! is_null($setTo))
		{
			if (! is_null($this->xtext)) {
			
				return _MiniXMLError("MiniXMLElement::numeric() Can't set numeric for element with text.");
			
			} elseif (! is_numeric($setTo))
			{
				return _MiniXMLError("MiniXMLElement::numeric() Must pass a NUMERIC value to set numeric for element.");
			}
			
			if (MINIXML_DEBUG > 0)
			{
				_MiniXMLLog("Setting numeric value of node to '$setTo'");
			}
			$this->xnumeric = $setTo;
		}
		
		return $this->xnumeric;
	}
	
	
	
	/* toString [DEPTH]
	**
	** Returns this node's contents as a string.
	**
	**
	** Note: Nodes have only a single value, no children.  It is 
	** therefore pointless to use the same toString() method split as 
	** in the MiniXMLElement class.
	**
	*/
	
	function toString ($depth=0)
	{
		if ($depth == MINIXML_NOWHITESPACES)
		{
			return $this->toStringNoWhiteSpaces();
		}
		
		if (MINIXML_DEBUG > 0)
		{
			_MiniXMLLog("MiniXMLNode::toString() call with depth $depth");
		}
		
		$spaces = $this->_spaceStr($depth);
		$retStr = $spaces;
		
		if (! is_null($this->xtext) )
		{
			/* a text element */
			$retStr .= $this->xtext;
		} elseif (! is_null($this->xnumeric)) {
			/* a numeric element */
			$retStr .=  $this->xnumeric;
		} 
		
		/* indent all parts of the string correctly */
		$retStr = preg_replace("/\n\s*/sm", "\n$spaces", $retStr);
		
		return $retStr;
	}
	
	
	function toStringWithWhiteSpaces ($depth=0)
	{
		return $this->toString($depth);
	}
	
	function toStringNoWhiteSpaces ()
	{
	
		if (MINIXML_DEBUG > 0)
		{
			_MiniXMLLog("MiniXMLNode::toStringNoWhiteSpaces() call with depth $depth");
		}
		
		if (! is_null($this->xtext) )
		{
			/* a text element */
			$retStr = $this->xtext;
		} elseif (! is_null($this->xnumeric)) {
			/* a numeric element */
			$retStr =  $this->xnumeric;
		}
		
		return $retStr;
	}
	
	
} /* end class definition */



?>
