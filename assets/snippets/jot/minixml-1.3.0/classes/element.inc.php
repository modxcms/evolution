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
require_once(MINIXML_CLASSDIR . "/node.inc.php");

/***************************************************************************************************
****************************************************************************************************
*****
*****					  MiniXMLElement
*****
****************************************************************************************************
***************************************************************************************************/


/* class MiniXMLElement (MiniXMLTreeComponent)
**
** Although the main handle to the xml document is the MiniXMLDoc object,
** much of the functionality and manipulation involves interaction with
** MiniXMLElement objects.
**
** A MiniXMLElement 
** has:
** - a name
** - a list of 0 or more attributes (which have a name and a value)
** - a list of 0 or more children (MiniXMLElement or MiniXMLNode objects)
** - a parent (optional, only if MINIXML_AUTOSETPARENT > 0)
**/

class MiniXMLElement extends MiniXMLTreeComponent {
	
	
	var $xname;
	var $xattributes;
	var $xchildren;
	var $xnumChildren;
	var $xnumElementChildren;

	var $xavoidLoops = MINIXML_AVOIDLOOPS;
	
	
	/* MiniXMLElement NAME
	** Creates and inits a new MiniXMLElement
	*/
	function MiniXMLElement ($name=NULL)
	{
		$this->MiniXMLTreeComponent();
		$this->xname = NULL;
		$this->xattributes = array();
		$this->xchildren = array();
		$this->xnumChildren = 0;
		$this->xnumElementChildren = 0;
		if ($name)
		{
			$this->name($name);
		} else {
			return _MiniXMLError("MiniXMLElement Constructor: must pass a name to constructor");
		}
	} /* end method MiniXMLElement */
	
	
	/**************** Get/set methods for MiniXMLElement data *****************/
	
	
	/* name [NEWNAME]
	**
	** If a NEWNAME string is passed, the MiniXMLElement's name is set 
	** to NEWNAME.
	**
	** Returns the element's name.
	*/
	function name ($setTo=NULL)
	{
		if (! is_null($setTo))
		{
			if (! is_string($setTo))
			{
				return _MiniXMLError("MiniXMLElement::name() Must pass a STRING to method to set name");
			}
			
			$this->xname = $setTo;
		}
		
		return $this->xname;
		
	} /* end method name */
	
	
	
	/* attribute NAME [SETTO [SETTOALT]]
	**
	** The attribute() method is used to get and set the 
	** MiniXMLElement's attributes (ie the name/value pairs contained
	** within the tag, <tagname attrib1="value1" attrib2="value2">)
	**
	** If SETTO is passed, the attribute's value is set to SETTO.
	**
	** If the optional SETTOALT is passed and SETTO is false, the 
	** attribute's value is set to SETTOALT.  This is usefull in cases
	** when you wish to set the attribute to a default value if no SETTO is
	** present, eg $myelement->attribute('href', $theHref, 'http://psychogenic.com')
	** will default to 'http://psychogenic.com'.
	**
	** Note: if the MINIXML_LOWERCASEATTRIBUTES define is > 0, all attribute names
	** will be lowercased (while setting and during retrieval)
	**
	** Returns the value associated with attribute NAME.
	**
	*/
	function attribute ($name, $primValue=NULL, $altValue=NULL)
	{
		$value = (is_null($primValue) ? $altValue : $primValue );


		if (MINIXML_UPPERCASEATTRIBUTES > 0)
		{
			$name = strtoupper($name);
		} elseif (MINIXML_LOWERCASEATTRIBUTES > 0)
		{
			$name = strtolower($name);
		}
		
		if (! is_null($value))
		{
			
			$this->xattributes[$name] = $value;
		}
		
		if (! is_null($this->xattributes[$name]))
		{
			return $this->xattributes[$name];
		} else {
			return NULL;
		}
		
	} /* end method attribute */
	

	/* text [SETTO [SETTOALT]]
	**
	** The text() method is used to get or append text data to this
	** element (it is appended to the child list as a new MiniXMLNode object).
	**
	** If SETTO is passed, a new node is created, filled with SETTO 
	** and appended to the list of this element's children.
	**
	** If the optional SETTOALT is passed and SETTO is false, the 
	** new node's value is set to SETTOALT.  See the attribute() method
	** for an example use.
	** 
	** Returns a string composed of all child MiniXMLNodes' contents.
	**
	** Note: all the children MiniXMLNodes' contents - including numeric 
	** nodes are included in the return string.
	*/
	function text ($setToPrimary = NULL, $setToAlternate=NULL)
	{
		$setTo = ($setToPrimary ? $setToPrimary : $setToAlternate);
		
		if (! is_null($setTo))
		{
			$this->createNode($setTo);
		}
		
		$retString = '';
		
		/* Extract text from all child nodes */
		for($i=0; $i< $this->xnumChildren; $i++)
		{
			if ($this->isNode($this->xchildren[$i]))
			{
				$nodeTxt = $this->xchildren[$i]->getValue();
				if (! is_null($nodeTxt))
				{
					$retString .= "$nodeTxt ";
					
				} /* end if text returned */
				
			} /* end if this is a MiniXMLNode */
			
		} /* end loop over all children */
		
		return $retString;
		
	}  /* end method text */
	
	
	
	/* numeric [SETTO [SETTOALT]]
	**
	** The numeric() method is used to get or append numeric data to
	** this element (it is appended to the child list as a MiniXMLNode object).
	**
	** If SETTO is passed, a new node is created, filled with SETTO 
	** and appended to the list of this element's children.
	**
	** If the optional SETTOALT is passed and SETTO is false, the 
	** new node's value is set to SETTOALT.  See the attribute() method
	** for an example use.
	** 
	** Returns a space seperated string composed all child MiniXMLNodes' 
	** numeric contents.
	**
	** Note: ONLY numerical contents are included from the list of child MiniXMLNodes.
	**
	*/
	function numeric ($setToPrimary = NULL, $setToAlternate=NULL)
	{
		$setTo = (is_null($setToPrimary) ? $setToAlternate : $setToPrimary);
		
		if (! is_null($setTo))
		{
			$this->createNode($setTo);
		}
		
	} /* end method numeric */
	
	
	/* comment CONTENTS
	**
	** The comment() method allows you to add a new MiniXMLElementComment to this
	** element's list of children.
	**
	** Comments will return a <!-- CONTENTS --> string when the element's toString()
	** method is called.
	**
	** Returns a reference to the newly appended MiniXMLElementComment
	**
	*/
	function & comment ($contents)
	{
		$newEl = new MiniXMLElementComment();
		
		$appendedComment =& $this->appendChild($newEl);
		$appendedComment->text($contents);
		
		return $appendedComment;
		
	} /* end method comment */
		
	
	
	
		
		
	
	/*
	** docType DEFINITION
	**
	** Append a new <!DOCTYPE DEFINITION [ ...]> element as a child of this 
	** element.
	** 
	** Returns the appended DOCTYPE element. You will normally use the returned
	** element to add ENTITY elements, like
	
	** $newDocType =& $xmlRoot->docType('spec SYSTEM "spec.dtd"');
	** $newDocType->entity('doc.audience', 'public review and discussion');
	*/
	
	function & docType ($definition)
	{
		
		$newElement = new MiniXMLElementDocType($definition);
		$appendedElement =& $this->appendChild($newElement);
		
		return $appendedElement;
	}
	/*
	** entity NAME VALUE
	**
	** Append a new <!ENTITY NAME "VALUE"> element as a child of this 
	** element.
	
	** Returns the appended ENTITY element.
	*/
	function & entity ($name,$value)
	{
		
		$newElement = new MiniXMLElementEntity($name, $value);
		$appendedEl =& $this->appendChild($newElement);
		
		return $appendedEl;
	}
	
	
	/* 
	** cdata CONTENTS
	** 
	** Append a new <![CDATA[ CONTENTS ]]> element as a child of this element.
	** Returns the appended CDATA element.
	** 
	*/
	
	function & cdata ($contents)
	{
		$newElement = new MiniXMLElementCData($contents);
		$appendedChild =& $this->appendChild($newElement);
		
		return $appendedChild;
	}
		
		
	/* getValue
	**
	** Returns a string containing the value of all the element's
	** child MiniXMLNodes (and all the MiniXMLNodes contained within 
	** it's child MiniXMLElements, recursively).
	**
	** Note: the seperator parameter remains officially undocumented
	** since I'm not sure it will remain part of the API
	*/
	function getValue ($seperator=' ')
	{
		$retStr = '';
		$valArray = array();
		for($i=0; $i < $this->xnumChildren; $i++)
		{
			$value = $this->xchildren[$i]->getValue();
			if (! is_null($value))
			{
				array_push($valArray, $value);
			}
		}
		if (count($valArray))
		{
			$retStr = implode($seperator, $valArray);
		}
		return $retStr;
		
	} /* end method getValue */
	
	
	
	/* getElement NAME
	** Searches the element and it's children for an element with name NAME.
	**
	** Returns a reference to the first MiniXMLElement with name NAME,
	** if found, NULL otherwise.
	**
	** NOTE: The search is performed like this, returning the first 
	** 	 element that matches:
	**
	** - Check this element for a match
	** - Check this element's immediate children (in order) for a match.
	** - Ask each immediate child (in order) to MiniXMLElement::getElement()
	**  (each child will then proceed similarly, checking all it's immediate
	**   children in order and then asking them to getElement())
	*/
	function &getElement ($name)
	{
		
		if (MINIXML_DEBUG > 0)
		{
			$elname = $this->name();
			_MiniXMLLog("MiniXMLElement::getElement() called for $name on $elname.");
		}
		if (is_null($name))
		{
			return _MiniXMLError("MiniXMLElement::getElement() Must Pass Element name.");
		}
		
		
		/** Must only check children as checking $this results in an inability to
		*** fetch nested objects with the same name
		*** <tag>
		***  <nested>
		***   <nested>
		***     Can't get here from tag or from the first 'nested'
		***   </nested>
		***  </nested>
		*** </tag>
		if (MINIXML_CASESENSITIVE > 0)
		{
			if (strcmp($this->xname, $name) == 0)
			{
				/* This element is it * /
				return $this;
			}
		} else {
		
			if (strcasecmp($this->xname,$name) == 0)
			{
				return $this;
			}
		}
		
		***** end commented out section ****
		*/
		
		if (! $this->xnumChildren )
		{
			/* Not match here and and no kids - not found... */
			return NULL;
		}
		
		/* Try each child (immediate children take priority) */
		for ($i = 0; $i < $this->xnumChildren; $i++)
		{
			$childname = $this->xchildren[$i]->name();
			if ($childname)
			{
				if (MINIXML_CASESENSITIVE > 0)
				{
					/* case sensitive matches only */
					if (strcmp($name, $childname) == 0)
					{
						return $this->xchildren[$i];
					}
				} else {
					/* case INsensitive matching */
					if (strcasecmp($name, $childname) == 0)
					{
						return $this->xchildren[$i];
					}
				} /* end if case sensitive */
			} /* end if child has a name */
			
		} /* end loop over all my children */
		
		/* Use beautiful recursion, daniel san */
		for ($i = 0; $i < $this->xnumChildren; $i++)
		{
			$theelement =& $this->xchildren[$i]->getElement($name);
			if ($theelement)
			{
				if (MINIXML_DEBUG > 0)
				{
					_MiniXMLLog("MiniXMLElement::getElement() returning element $theelement");
				}
				return $theelement;
			}
		}
		
		/* Not found */
		return NULL;
		
		
	}  /* end method getElement */
	
	
	/* getElementByPath PATH
	** Attempts to return a reference to the (first) element at PATH
	** where PATH is the path in the structure (relative to this element) to
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
	**	$partRate =& $xmlDocument->getElement('partRateRequest');
	**
	** 	$accessid =& $partRate->getElementByPath('vendor/accessid');
	**
	** Will return what you expect (the accessid element with attributes user = "myusername"
	** and password = "mypassword").
	**
	** BUT be careful:
	**	$accessid =& $partRate->getElementByPath('partList/partNum');
	**
	** will return the partNum element with the value "DA42".  Other partNums are 
	** inaccessible by getElementByPath() - Use MiniXMLElement::getAllChildren() instead.
	**
	** Returns the MiniXMLElement reference if found, NULL otherwise.
	*/
	function &getElementByPath($path)
	{
		$names = split ("/", $path);
		
		$element = $this;
		foreach ($names as $elementName)
		{
			if ($element && $elementName) /* Make sure we didn't hit a dead end and that we have a name*/
			{
				/* Ask this element to get the next child in path */
				$element =& $element->getElement($elementName);
			}
		}
		
		return $element;
		
	} /* end method getElementByPath */
	
	
	
	/* numChildren [NAMED]
	** 
	** Returns the number of immediate children for this element
	**
	** If the optional NAMED parameter is passed, returns only the 
	** number of immediate children named NAMED.
	*/
	function numChildren ($named=NULL)
	{
		if (is_null($named))
		{
			return $this->xnumElementChildren;
		}
		
		/* We require only children named '$named' */
		$allkids =& $this->getAllChildren($named);
		
		return count($allkids);
		
		
	}

	
	/* getAllChildren [NAME]
	**
	** Returns a reference to an array of all this element's MiniXMLElement children
	**
	** Note: although the MiniXMLElement may contain MiniXMLNodes as children, these are
	** not part of the returned list.
	**/
	function &getAllChildren ($name=NULL)
	{
		$retArray = array();
		$count = 0;
		
		if (is_null($name))
		{
			/* Return all element children */
			for($i=0; $i < $this->xnumChildren; $i++)
			{
				if (method_exists($this->xchildren[$i], 'MiniXMLElement'))
				{
					$retArray[$count++] =& $this->xchildren[$i];
				}
			}
		} else {
			/* Return only element children with name $name */

			for($i=0; $i < $this->xnumChildren; $i++)
			{
				if (method_exists($this->xchildren[$i], 'MiniXMLElement'))
				{
					if (MINIXML_CASESENSITIVE > 0)
					{
						if ($this->xchildren[$i]->name() == $name)
						{
							$retArray[$count++] =& $this->xchildren[$i];
						}
					} else {
						if (strcasecmp($this->xchildren[$i]->name(), $name) == 0)
						{
							$retArray[$count++] =& $this->xchildren[$i];
						}
					} /* end if case sensitive */
					
				} /* end if child is a MiniXMLElement object */
				
			} /* end loop over all children */
			
		} /* end if specific name was requested */
			
		return $retArray;
		
	} /* end method getAllChildren */
	
		
		
	function &insertChild (&$child, $idx=0)
	{
		
		
		
		if (! $this->_validateChild($child))
		{
			return;
		}
		
		/* Set the parent for the child element to this element if 
		** avoidLoops or MINIXML_AUTOSETPARENT is set
		*/
		if ($this->xavoidLoops || (MINIXML_AUTOSETPARENT > 0) )
		{
			if ($this->xparent == $child)
			{
				
				$cname = $child->name();
				return _MiniXMLError("MiniXMLElement::insertChild() Tryng to append parent $cname as child of " 
							. $this->xname );
			}
			$child->parent($this);
		}
		
		
		$nextIdx = $this->xnumChildren;
		$lastIdx = $nextIdx - 1;
		if ($idx > $lastIdx)
		{
		
			if ($idx > $nextIdx)
			{
				$idx = $lastIdx + 1;
			}
			$this->xchildren[$idx] = $child;
			$this->xnumChildren++;
			if ($this->isElement($child))
			{
				$this->xnumElementChildren++;
			}
			
		} elseif ($idx >= 0)
		{
			
			$removed = array_splice($this->xchildren, $idx);
			array_push($this->xchildren, $child);
			$numRemoved = count($removed);
			
			for($i=0; $i<$numRemoved; $i++)
			{
			
				array_push($this->xchildren, $removed[$i]);
			}
			$this->xnumChildren++;
			if ($this->isElement($child))
			{
				$this->xnumElementChildren++;
			}
			
			
		} else {
			$revIdx = (-1 * $idx) % $this->xnumChildren;
			$newIdx = $this->xnumChildren - $revIdx;
			
			if ($newIdx < 0)
			{
				return _MiniXMLError("Element::insertChild() Ended up with a negative index? ($newIdx)");
			}
			
			return $this->insertChild($child, $newIdx);
		}
			
		return $child;
	}
		

	/* appendChild CHILDELEMENT
	**
	** appendChild is used to append an existing MiniXMLElement object to
	** this element's list.
	**
	** Returns a reference to the appended child element.
	**
	** NOTE: Be careful not to create loops in the hierarchy, eg
	** $parent->appendChild($child);
	** $child->appendChild($subChild);
	** $subChild->appendChild($parent);
	**
	** If you want to be sure to avoid loops, set the MINIXML_AVOIDLOOPS define
	** to 1 or use the avoidLoops() method (will apply to all children added with createChild())
	*/
	function &appendChild (&$child)
	{
		
		if (! $this->_validateChild($child))
		{
			_MiniXMLLog("MiniXMLElement::appendChild() Could not validate child, aborting append");
			return NULL;
		}
		
		/* Set the parent for the child element to this element if 
		** avoidLoops or MINIXML_AUTOSETPARENT is set
		*/
		if ($this->xavoidLoops || (MINIXML_AUTOSETPARENT > 0) )
		{
			if ($this->xparent == $child)
			{
				
				$cname = $child->name();
				return _MiniXMLError("MiniXMLElement::appendChild() Tryng to append parent $cname as child of " 
							. $this->xname );
			}
			$child->parent($this);
		}
		
		
		$this->xnumElementChildren++; /* Note that we're addind a MiniXMLElement child */
		
		/* Add the child to the list */
		$idx = $this->xnumChildren++;
		$this->xchildren[$idx] =& $child;
		
		return $this->xchildren[$idx];
		
	} /* end method appendChild */
	
	
	/* prependChild CHILDELEMENT
	**
	** prependChild is used to prepend an existing MiniXMLElement object to
	** this element's list.  The child will be positioned at the begining of 
	** the elements child list, thus it will be output first in the resulting XML.
	**
	** Returns a reference to the prepended child element.
	*/
	function &prependChild ($child)
	{
		
		
		if (! $this->_validateChild($child))
		{
			_MiniXMLLog("MiniXMLElement::prependChild - Could not validate child, aborting.");
			return NULL;
		}
		
		/* Set the parent for the child element to this element if 
		** avoidLoops or MINIXML_AUTOSETPARENT is set
		*/
		if ($this->xavoidLoops || (MINIXML_AUTOSETPARENT > 0) )
		{
			if ($this->xparent == $child)
			{
				
				$cname = $child->name();
				return _MiniXMLError("MiniXMLElement::prependChild() Tryng to append parent $cname as child of " 
							. $this->xname );
			}
			$child->parent($this);
		}
		
		
		$this->xnumElementChildren++; /* Note that we're adding a MiniXMLElement child */
		
		/* Add the child to the list */
		$idx = $this->xnumChildren++;
		array_unshift($this->xchildren, $child);
		return $this->xchildren[0];
		
	} /* end method prependChild */
	
	function _validateChild (&$child)
	{
	
		if (is_null($child))
		{
			return  _MiniXMLError("MiniXMLElement::_validateChild() need to pass a non-NULL MiniXMLElement child.");
		}
		
		if (! method_exists($child, 'MiniXMLElement'))
		{
			return _MiniXMLError("MiniXMLElement::_validateChild() must pass a MiniXMLElement object to _validateChild.");
		}
		
		/* Make sure element is named */
		$cname = $child->name();
		if (is_null($cname))
		{
			_MiniXMLLog("MiniXMLElement::_validateChild() children must be named");
			return 0;
		}
		
		
		/* Check for loops */
		if ($child == $this)
		{
			_MiniXMLLog("MiniXMLElement::_validateChild() Trying to append self as own child!");
			return 0;
		} elseif ( $this->xavoidLoops && $child->parent())
		{
			_MiniXMLLog("MiniXMLElement::_validateChild() Trying to append a child ($cname) that already has a parent set "
						. "while avoidLoops is on - aborting");
			return 0;
		}
		
		return 1;
	}
	/* createChild ELEMENTNAME [VALUE]
	** 
	** Creates a new MiniXMLElement instance and appends it to the list
	** of this element's children.
	** The new child element's name is set to ELEMENTNAME.
	**
	** If the optional VALUE (string or numeric) parameter is passed,
	** the new element's text/numeric content will be set using VALUE.
	**
	** Returns a reference to the new child element
	**
	** Note: don't forget to use the =& (reference assignment) operator
	** when calling createChild:
	**
	**	$newChild =& $myElement->createChild('newChildName');
	**
	*/
	function & createChild ($name, $value=NULL)
	{
		if (! $name)
		{
			return _MiniXMLError("MiniXMLElement::createChild() Must pass a NAME to createChild.");
		}
		
		if (! is_string($name))
		{
			return _MiniXMLError("MiniXMLElement::createChild() Name of child must be a STRING");
		}
		
		$child = new MiniXMLElement($name);
		
		$appendedChild =& $this->appendChild($child);
		
		if (! $appendedChild )
		{
			_MiniXMLLog("MiniXMLElement::createChild() '$name' child NOT appended.");
			return NULL;
		}

		if (! is_null($value))
		{
			if (is_numeric($value))
			{
				$appendedChild->numeric($value);
			} elseif (is_string($value))
			{
				$appendedChild->text($value);
			}
		}
		
		$appendedChild->avoidLoops($this->xavoidLoops);
		
		return $appendedChild;
		
	} /* end method createChild */
	
	
	
	/* removeChild CHILD
	** Removes CHILD from this element's list of children.
	**
	** Returns the removed child, if found, NULL otherwise.
	*/
		
	function &removeChild (&$child)
	{
		if (! $this->xnumChildren)
		{
			if (MINIXML_DEBUG > 0)
			{
				_MiniXMLLog("Element::removeChild() called for element without any children.") ;
			}
			return NULL;
		}
		
		$foundChild = NULL;
		$idx = 0;
		while ($idx < $this->xnumChildren && ! $foundChild)
		{
			if ($this->xchildren[$idx] == $child)
			{
				$foundChild =& $this->xchildren[$idx];
			} else {
				$idx++;
			}
		}
		
		if (! $foundChild)
		{
			if (MINIXML_DEBUG > 0)
			{
				_MiniXMLLog("Element::removeChild() No matching child found.") ;
			}
			return NULL;
		}
		
		array_splice($this->xchildren, $idx, 1);
		
		$this->xnumChildren--;
		if ($this->isElement($foundChild))
		{
			$this->xnumElementChildren--;
		}
		
		unset ($foundChild->xparent) ;
		return $foundChild;
	}
	
	
	/* removeAllChildren
	** Removes all children of this element.
	**
	** Returns an array of the removed children (which may be empty)
	*/
	function &removeAllChildren ()
	{
		$emptyArray = array();
		
		if (! $this->xnumChildren)
		{
			return $emptyArray;
		}
		
		$retList =& $this->xchildren;
		
		$idx = 0;
		while ($idx < $this->xnumChildren)
		{
			unset ($retList[$idx++]->xparent);
		}
		
		$this->xchildren = array();
		$this->xnumElementChildren = 0;
		$this->xnumChildren = 0;
		
		
		return $retList;
	}
	
		
	function & remove ()
	{
		$parent =& $this->parent();
		
		if (!$parent)
		{
			_MiniXMLLog("XML::Mini::Element::remove() called for element with no parent set.  Aborting.");
			return NULL;
		}
		
		$removed =& $parent->removeChild($this);
		
		return $removed;
	}
		
	
	
	/* parent NEWPARENT
	**
	** The parent() method is used to get/set the element's parent.
	**
	** If the NEWPARENT parameter is passed, sets the parent to NEWPARENT
	** (NEWPARENT must be an instance of MiniXMLElement)
	**
	** Returns a reference to the parent MiniXMLElement if set, NULL otherwise.
	**
	** Note: This method is mainly used internally and you wouldn't normally need
	** to use it.
	** It get's called on element appends when MINIXML_AUTOSETPARENT or 
	** MINIXML_AVOIDLOOPS or avoidLoops() > 1
	**
	*/ 
	function &parent (&$setParent)
	{
		if (! is_null($setParent))
		{
			/* Parents can only be MiniXMLElement objects */
			if (! $this->isElement($setParent))
			{
				return _MiniXMLError("MiniXMLElement::parent(): Must pass an instance of MiniXMLElement to set.");
			}
			$this->xparent = $setParent;
		}
		
		return $this->xparent;
		
	} /* end method parent */
	
	
	/* avoidLoops SETTO
	**
	** The avoidLoops() method is used to get or set the avoidLoops flag for this element.
	**
	** When avoidLoops is true, children with parents already set can NOT be appended to any
	** other elements.  This is overkill but it is a quick and easy way to avoid infinite loops
	** in the heirarchy.
	**
	** The avoidLoops default behavior is configured with the MINIXML_AVOIDLOOPS define but can be
	** set on individual elements (and automagically all the element's children) with the 
	** avoidLoops() method.
	**
	** Returns the current value of the avoidLoops flag for the element.
	**
	*/
	function avoidLoops ($setTo = NULL)
	{
		if (! is_null($setTo))
		{
			$this->xavoidLoops = $setTo;
		}
		
		return $this->xavoidLoops;
	}
	
	
	/* toString [SPACEOFFSET]
	** 
	** toString returns an XML string based on the element's attributes,
	** and content (recursively doing the same for all children)
	**
	** The optional SPACEOFFSET parameter sets the number of spaces to use
	** after newlines for elements at this level (adding 1 space per level in
	** depth).  SPACEOFFSET defaults to 0.
	**
	** If SPACEOFFSET is passed as MINIXML_NOWHITESPACES.  
	** no \n or whitespaces will be inserted in the xml string
	** (ie it will all be on a single line with no spaces between the tags.
	**
	** Returns the XML string.
	**
	**
	** Note: Since the toString() method recurses into child elements and because
	** of the MINIXML_NOWHITESPACES and our desire to avoid testing for this value
	** on every element (as it does not change), here we split up the toString method
	** into 2 subs: toStringWithWhiteSpaces(DEPTH) and toStringNoWhiteSpaces().
	**
	** Each of these methods, which are to be considered private (?), in turn recurses
	** calling the appropriate With/No WhiteSpaces toString on it's children - thereby
	** avoiding the test on SPACEOFFSET
	*/
	
	function toString ($depth=0)
	{
		if ($depth == MINIXML_NOWHITESPACES)
		{
			return $this->toStringNoWhiteSpaces();
		} else {
			return $this->toStringWithWhiteSpaces($depth);
		}
	}
	
	function toStringWithWhiteSpaces ($depth=0)
	{
		$attribString = '';
		$elementName = $this->xname;
		$spaces = $this->_spaceStr($depth) ;
		
		$retString = "$spaces<$elementName";
		
		
		foreach ($this->xattributes as $attrname => $attrvalue)
		{
			$attribString .= "$attrname=\"$attrvalue\" ";
		}
		
		
		if ($attribString)
		{
			$attribString = rtrim($attribString);
			$retString .= " $attribString";
		}
		
		if (! $this->xnumChildren)
		{
			/* No kids -> no sub-elements, no text, nothing - consider a <unary/> element */
			$retString .= " />\n";
			
			return $retString;
		} 
		
		
		
		/* If we've gotten this far, the element has
		** kids or text - consider a <binary>otherstuff</binary> element 
		*/
		
		$onlyTxtChild = 0;
		if ($this->xnumChildren == 1 && ! $this->xnumElementChildren)
		{
			$onlyTxtChild = 1;
		}
		
		
		
		if ($onlyTxtChild)
		{
			$nextDepth = 0;
			$retString .= "> ";
		} else {
			$nextDepth = $depth+1;
			$retString .= ">\n";
		}
		
		
		
		for ($i=0; $i < $this->xnumChildren ; $i++)
		{
			if (method_exists($this->xchildren[$i], 'toStringWithWhiteSpaces') )
			{
			
				$newStr = $this->xchildren[$i]->toStringWithWhiteSpaces($nextDepth);
				
					
				if (! is_null($newStr))
				{
					if (! ( preg_match("/\n\$/", $newStr) || $onlyTxtChild) )
					{
						$newStr .= "\n";
					}
				
					$retString .= $newStr;
				}
				
			} else {
				_MiniXMLLog("Invalid child found in $elementName ". $this->xchildren[$i]->name() );
				
			} /* end if has a toString method */
			
		} /* end loop over all children */
		
		/* add the indented closing tag */
		if ($onlyTxtChild)
		{
			$retString .= " </$elementName>\n";
		} else {
			$retString .= "$spaces</$elementName>\n";
		}
		return $retString;
		
	} /* end method toString */
	
	
	
	
	function toStringNoWhiteSpaces ()
	{
		$retString = '';
		$attribString = '';
		$elementName = $this->xname;
		
		foreach ($this->xattributes as $attrname => $attrvalue)
		{
			$attribString .= "$attrname=\"$attrvalue\" ";
		}
		
		$retString = "<$elementName";
		
		
		if ($attribString)
		{
			$attribString = rtrim($attribString);
			$retString .= " $attribString";
		}
		
		if (! $this->xnumChildren)
		{
			/* No kids -> no sub-elements, no text, nothing - consider a <unary/> element */
			
			$retString .= " />";
			return $retString;
		}
		
		
		/* If we've gotten this far, the element has
		** kids or text - consider a <binary>otherstuff</binary> element 
		*/
		$retString .= ">";
		
		/* Loop over all kids, getting associated strings */
		for ($i=0; $i < $this->xnumChildren ; $i++)
		{
			if (method_exists($this->xchildren[$i], 'toStringNoWhiteSpaces') )
			{
				$newStr = $this->xchildren[$i]->toStringNoWhiteSpaces();
					
				if (! is_null($newStr))
				{
					$retString .= $newStr;
				}
				
			} else {
				_MiniXMLLog("Invalid child found in $elementName");
				
			} /* end if has a toString method */
			
		} /* end loop over all children */
		
		/* add the indented closing tag */
		$retString .= "</$elementName>";
		
		return $retString;
		
	} /* end method toStringNoWhiteSpaces */
	
	
	/* toStructure
	**
	** Converts an element to a structure - either an array or a simple string.
	** 
	** This method is used by MiniXML documents to perform their toArray() magic.
	*/
	function & toStructure ()
	{
	
		$retHash = array();
		$contents = "";
		$numAdded = 0;
		
		
		
		for($i=0; $i< $this->xnumChildren; $i++)
		{
			if ($this->isElement($this->xchildren[$i]))
			{
				$name = $this->xchildren[$i]->name();
				
				if (array_key_exists($name, $retHash))
				
				{
					if (! (is_array($retHash[$name]) && array_key_exists('_num', $retHash[$name])) )
					{
						$retHash[$name] = array($retHash[$name],
									 $this->xchildren[$i]->toStructure());
									 
						$retHash[$name]['_num'] = 2;
					} else {
						array_push($retHash[$name], $this->xchildren[$i]->toStructure() );
						
						$retHash[$name]['_num']++;
					}
				} else {
					$retHash[$name] = $this->xchildren[$i]->toStructure();
				}
			
				$numAdded++;
			} else {
				$contents .= $this->xchildren[$i]->getValue();
			}
			
		
		}
		
		
		foreach ($this->xattributes as $attrname => $attrvalue)
		{
			#array_push($retHash, array($attrname => $attrvalue));
			$retHash["_attributes"][$attrname] = $attrvalue;
			$numAdded++;
		}
		
		
		if ($numAdded)
		{
			if (! empty($contents))
			{
				$retHash['_content'] = $contents;
			}
			
			return $retHash;
		} else {
			return $contents;
		}
		
	} // end toStructure() method
	
	
	
	
	
	/* isElement ELEMENT
	** Returns a true value if ELEMENT is an instance of MiniXMLElement,
	** false otherwise.
	**
	** Note: Used internally.
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
	**
	** Note: used internally.
	*/
	function isNode (&$testme)
	{
		if (is_null($testme))
		{
			return 0;
		}
		
		return method_exists($testme, 'MiniXMLNode');
	}
	
	
	/* createNode NODEVALUE [ESCAPEENTITIES]
	**
	** Private (?)
	** 
	** Creates a new MiniXMLNode instance and appends it to the list
	** of this element's children.
	** The new child node's value is set to NODEVALUE.
	**
	** Returns a reference to the new child node.
	**
	** Note: You don't need to use this method normally - it is used
	** internally when appending text() and such data.
	**
	*/
	function & createNode (&$value, $escapeEntities=NULL)
	{
		
		$newNode = new MiniXMLNode($value, $escapeEntities);
		
		$appendedNode =& $this->appendNode($newNode);
		
		return $appendedNode;
	}
		
	
	/* appendNode CHILDNODE
	**
	** appendNode is used to append an existing MiniXMLNode object to
	** this element's list.
	**
	** Returns a reference to the appended child node.
	**
	**
	** Note: You don't need to use this method normally - it is used
	** internally when appending text() and such data.
	*/
	function &appendNode (&$node)
	{
		if (is_null($node))
		{
			return  _MiniXMLError("MiniXMLElement::appendNode() need to pass a non-NULL MiniXMLNode.");
		}
		
		
		if (! method_exists($node, 'MiniXMLNode'))
		{
			return _MiniXMLError("MiniXMLElement::appendNode() must pass a MiniXMLNode object to appendNode.");
		}
		
		if (MINIXML_AUTOSETPARENT)
		{
			if ($this->xparent == $node)
			{
				return _MiniXMLError("MiniXMLElement::appendnode() Tryng to append parent $cname as node of " 
							. $this->xname );
			}
			$node->parent($this);
		}
		
		
		$idx = $this->xnumChildren++;
		$this->xchildren[$idx] = $node;
		
		return $this->xchildren[$idx];
		
		
	}
	
	/* Destructor to keep things clean -- patch by Ilya */
	function __destruct()
	{
		for ($i = 0; $i < count($this->xchildren); ++$i)
			$this->xchildren[$i]->xparent = null;
	}
	
	
} /* end MiniXMLElement class definition */






/***************************************************************************************************
****************************************************************************************************
*****
*****					  MiniXMLElementComment
*****
****************************************************************************************************
***************************************************************************************************/

/* The MiniXMLElementComment class is a specific extension of the MiniXMLElement class.
**
** It is used to create the special <!-- comment --> tags and an instance in created when calling
** $elementObject->comment('this is a comment');
**
** It's methods are the same as for MiniXMLElement - see those for documentation.
**/

class MiniXMLElementComment extends MiniXMLElement {

	function MiniXMLElementComment ($name=NULL)
	{
		$this->MiniXMLElement('!--');
	}
	
	
	function toString ($depth=0)
	{
		if ($depth == MINIXML_NOWHITESPACES)
		{
			return $this->toStringNoWhiteSpaces();
		} else {
			return $this->toStringWithWhiteSpaces($depth);
		}
	}
	
		
	function toStringWithWhiteSpaces ($depth=0)
	{

		$spaces = $this->_spaceStr($depth) ;
		
		$retString = "$spaces<!-- \n";
		
		if (! $this->xnumChildren)
		{
			/* No kids, no text - consider a <unary/> element */
			$retString .= " -->\n";
			
			return $retString;
		}
		
		/* If we get here, the element does have children... get their contents */
		
		$nextDepth = $depth+1;
		
		for ($i=0; $i < $this->xnumChildren ; $i++)
		{
			$retString .= $this->xchildren[$i]->toStringWithWhiteSpaces($nextDepth);
		}
		
		$retString .= "\n$spaces -->\n";
		
		
		return $retString;
	}
	
	
	function toStringNoWhiteSpaces ()
	{
		$retString = '';
		
		$retString = "<!-- ";
		
		if (! $this->xnumChildren)
		{
			/* No kids, no text - consider a <unary/> element */
			$retString .= " -->";
			return $retString;
		}
		
		
		/* If we get here, the element does have children... get their contents */
		for ($i=0; $i < $this->xnumChildren ; $i++)
		{
			$retString .= $this->xchildren[$i]->toStringNoWhiteSpaces();
		}
		
		$retString .= " -->";
		
		
		return $retString;
	}
		
	
}




/***************************************************************************************************
****************************************************************************************************
*****
*****					  MiniXMLElementCData
*****
****************************************************************************************************
***************************************************************************************************/

/* The MiniXMLElementCData class is a specific extension of the MiniXMLElement class.
**
** It is used to create the special <![CDATA [ data ]]> tags and an instance in created when calling
** $elementObject->cdata('data');
**
** It's methods are the same as for MiniXMLElement - see those for documentation.
**/

class MiniXMLElementCData extends MiniXMLElement {

		
	
	
	function MiniXMLElementCData ($contents)
	{
		
		$this->MiniXMLElement('CDATA');
		if (! is_null($contents))
		{
			$this->createNode($contents, 0) ;
		}
	}
	

	function toStringNoWhiteSpaces ()
	{
		return $this->toString(MINIXML_NOWHITESPACES);
	}
	
	function toStringWithWhiteSpaces ($depth=0)
	{
		return $this->toString($depth);
	}
	
	function toString ($depth=0)
	{
		$spaces = '';
		if ($depth != MINIXML_NOWHITESPACES)
		{
			$spaces = $this->_spaceStr($depth);
		}
		
		$retString = "$spaces<![CDATA[ ";
		
		if (! $this->xnumChildren)
		{
			$retString .= "]]>\n";
			return $retString;
		}
		
		for ( $i=0; $i < $this->xnumChildren; $i++)
		{
			$retString .= $this->xchildren[$i]->getValue();
			
		}
		
		$retString .= " ]]>\n";
		
		return $retString;
	}
	


}

/***************************************************************************************************
****************************************************************************************************
*****
*****					  MiniXMLElementDocType
*****
****************************************************************************************************
***************************************************************************************************/

/* The MiniXMLElementDocType class is a specific extension of the MiniXMLElement class.
**
** It is used to create the special <!DOCTYPE def [...]> tags and an instance in created when calling
** $elementObject->comment('');
**
** It's methods are the same as for MiniXMLElement - see those for documentation.
**/

class MiniXMLElementDocType extends MiniXMLElement {

	var $dtattr;
	
	function MiniXMLElementDocType ($attr)
	{
		$this->MiniXMLElement('DOCTYPE');
		$this->dtattr = $attr;
	}
	function toString ($depth)
	{
		if ($depth == MINIXML_NOWHITESPACES)
		{
			return $this->toStringNoWhiteSpaces();
		} else {
			return $this->toStringWithWhiteSpaces($depth);
		}
	}
	
		
	function toStringWithWhiteSpaces ($depth=0)
	{

		$spaces = $this->_spaceStr($depth);
		
		$retString = "$spaces<!DOCTYPE " . $this->dtattr . " [\n";
		
		if (! $this->xnumChildren)
		{
			$retString .= "]>\n";
			return $retString;
		}
		
		$nextDepth = $depth + 1;
		
		for ( $i=0; $i < $this->xnumChildren; $i++)
		{
			
			$retString .= $this->xchildren[$i]->toStringWithWhiteSpaces($nextDepth);
			
		}
		
		$retString .= "\n$spaces]>\n";
		
		return $retString;
	}


	function toStringNoWhiteSpaces ()
	{
	
		$retString = "<!DOCTYPE " . $this->dtattr . " [ ";
		
		if (! $this->xnumChildren)
		{
			$retString .= "]>\n";
			return $retString;
		}
		
		for ( $i=0; $i < $this->xnumChildren; $i++)
		{
			
			$retString .= $this->xchildren[$i]->toStringNoWhiteSpaces();
			
		}
		
		$retString .= " ]>\n";
		
		return $retString;
	}


}


/***************************************************************************************************
****************************************************************************************************
*****
*****					  MiniXMLElementEntity
*****
****************************************************************************************************
***************************************************************************************************/

/* The MiniXMLElementEntity class is a specific extension of the MiniXMLElement class.
**
** It is used to create the special <!ENTITY name "val">  tags and an instance in created when calling
** $elementObject->comment('');
**
** It's methods are the same as for MiniXMLElement - see those for documentation.
**/

class MiniXMLElementEntity extends MiniXMLElement {


	
	function MiniXMLElementEntity  ($name, $value=NULL)
	{
		
		$this->MiniXMLElement($name);
		
		if (! is_null ($value))
		{
			$this->createNode($value, 0);
		}
		
	}
	
	function toString ($depth = 0)
	{
		
		$spaces = '';
		if ($depth != MINIXML_NOWHITESPACES)
		{
			$spaces = $this->_spaceStr($depth);
		} 
		
		$retString = "$spaces<!ENTITY " . $this->name();
		
		if (! $this->xnumChildren)
		{
			$retString .= ">\n";
			return $retString;
		}
		
		 $nextDepth = ($depth == MINIXML_NOWHITESPACES) ? MINIXML_NOWHITESPACES
										: $depth + 1;
		$retString .= '"';
		for ( $i=0; $i < $this->xnumChildren; $i++)
		{
			
			$retString .= $this->xchildren[$i]->toString(MINIXML_NOWHITESPACES);
			
		}
		$retString .= '"';
		$retString .= " >\n";
		
		return $retString;
	}
	
	
	function toStringNoWhiteSpaces ()
	{
		return $this->toString(MINIXML_NOWHITESPACES);
	}
	
	function toStringWithWhiteSpaces ($depth=0)
	{
		return $this->toString($depth);
	}


}


?>
