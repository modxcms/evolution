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
*****					      CONFIGURATION
*****
*****  Please see the http://minixml.psychogenic.com website for details on these configuration
*****  options.
*****
****************************************************************************************************
***************************************************************************************************/


/* All config options can be set to 0 (off) or 1 (on) */

define("MINIXML_CASESENSITIVE", 0); /* Set to 1 to use case sensitive element name comparisons */

define("MINIXML_AUTOESCAPE_ENTITIES", 1); /* Set to 1 to autoescape stuff like > and < and & in text, 0 to turn it off */



define("MINIXML_AUTOSETPARENT", 0); /* Set to 1 to automatically register parents elements with children */

define("MINIXML_AVOIDLOOPS", 0); /* Set to 1 to set the default behavior of 'avoidLoops' to ON, 0 otherwise */

define("MINIXML_IGNOREWHITESPACES", 1); /* Set to 1 to eliminate leading and trailing whitespaces from strings */


/* Lower/upper case attribute names.  Choose UPPER or LOWER or neither - not both... UPPER takes precedence */
define("MINIXML_UPPERCASEATTRIBUTES", 0); /* Set to 1 to UPPERCASE all attributes, 0 otherwise */
define("MINIXML_LOWERCASEATTRIBUTES", 0); /* Set to 1 to lowercase all attributes, 0 otherwise */


/* fromFile cache.
** If you are using lots of $xmlDoc->fromFile('path/to/file.xml') calls, it is possible to use
** a caching mechanism.  This cache will read the file, store a serialized version of the resulting
** object and read in the serialize object on subsequent calls.
**
** If the original XML file is updated, the cache will automatically be refreshed.
**
** To use caching, set MINIXML_USEFROMFILECACHING to 1 and set the
** MINIXML_FROMFILECACHEDIR to a suitable directory in which the cache files will 
** be stored (eg, "/tmp")
**/
define("MINIXML_USEFROMFILECACHING", 0);
define("MINIXML_FROMFILECACHEDIR", "/tmp");


define("MINIXML_DEBUG", 0); /* Set Debug to 1 for more verbose output, 0 otherwise */


/*****************************************  end Configuration ***************************************/

define("MINIXML_USE_SIMPLE", 0);

define("MINIXML_VERSION", "1.3.0"); /* Version information */

define("MINIXML_NOWHITESPACES", -999); /* Flag that may be passed to the toString() methods */



$MiniXMLLocation = dirname(__FILE__);
define("MINIXML_CLASSDIR", "$MiniXMLLocation/classes");
require_once(MINIXML_CLASSDIR . "/doc.inc.php");


/***************************************************************************************************
****************************************************************************************************
*****
*****			           Global Helper functions 
*****
****************************************************************************************************
***************************************************************************************************/


function _MiniXMLLog ($message)
{
	error_log("MiniXML LOG MESSAGE:\n$message\n");
}





function _MiniXMLError ($message)
{
	error_log("MiniXML ERROR:\n$message\n");
	
	return NULL;
	
}


function _MiniXML_NumKeyArray (&$v)
{
	if (! is_array($v))
	{
		return NULL;
	}
	
	
	$arrayKeys = array_keys($v);
	$numKeys = count($arrayKeys);
	$totalNumeric = 0;
	for($i=0; $i<$numKeys; $i++)
	{
		if (is_numeric($arrayKeys[$i]) && $arrayKeys[$i] == $i)
		{
			$totalNumeric++;
		} else {
			return 0;
		}
	}
	
	if ($totalNumeric == $numKeys)
	{
		// All numeric - assume it is a "straight" array
		return 1;
	} else {
		return 0;
	}
}







?>
