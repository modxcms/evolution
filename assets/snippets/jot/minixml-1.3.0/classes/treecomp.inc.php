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



/***************************************************************************************************
****************************************************************************************************
*****
*****					  MiniXMLTreeComponent
*****
****************************************************************************************************
***************************************************************************************************/



/* MiniXMLTreeComponent class 
** This class is only to be used as a base class
** for others.
**
** It presents the minimal interface we can expect
** from any component in the XML hierarchy.
**
** All methods of this base class 
** simply return NULL except a little default functionality
** included in the parent() method.
**
** Warning: This class is not to be instatiated.
** Derive and override.
**
*/

class MiniXMLTreeComponent {
	
	var $xparent;
	
	/*  MiniXMLTreeComponent
	** Constructor.  Creates a new MiniXMLTreeComponent object.
	**
	*/
	function MiniXMLTreeComponent ()
	{
		$this->xparent = NULL;
	} /* end MiniXMLTreeComponent constructor */
	
	
	/* Get set function for the element name
	*/
	function name ($setTo=NULL)
	{
		return NULL;
	}
	
	/* Function to fetch an element */
	function & getElement ($name)
	{
		return NULL;
	}
	
	/* Function that returns the value of this 
	component and its children */
	function getValue ()
	{
		return NULL;
	}
	
	/* parent NEWPARENT
	**
	** The parent() method is used to get/set the element's parent.
	**
	** If the NEWPARENT parameter is passed, sets the parent to NEWPARENT
	** (NEWPARENT must be an instance of a class derived from MiniXMLTreeComponent)
	**
	** Returns a reference to the parent MiniXMLTreeComponent if set, NULL otherwise.
	*/
	function &parent (&$setParent)
	{	
		if (! is_null($setParent))
		{
			/* Parents can only be MiniXMLElement objects */
			if (! method_exists($setParent, 'MiniXMLTreeComponent'))
			{
				return _MiniXMLError("MiniXMLTreeComponent::parent(): Must pass an instance derived from "
							. "MiniXMLTreeComponent to set.");
			}
			$this->xparent = $setParent;
		}
		
		return $this->xparent;
		
		
	}
	
	/* Return a stringified version of the XML representing
	this component and all sub-components */
	function toString ($depth=0)
	{
		return NULL;
	}

	/* dump
	** Debugging aid, dump returns a nicely formatted dump of the current structure of the
	** MiniXMLTreeComponent-derived object.
	*/
	function dump ()
	{
		return var_dump($this);
	}
	
	/* helper class that everybody loves */
	function _spaceStr ($numSpaces)
	{
		$retStr = '';
		if ($numSpaces < 0)
		{
			return $retStr;
		}
			
		for($i = 0; $i < $numSpaces; $i++)
		{
			$retStr .= ' ';
		}
		
		return $retStr;
	}
	
	/* Destructor to keep things clean -- patch by Ilya */
	function __destruct()
	{
		$this->xparent = null;
	}
	
} /* end class definition */


?>

