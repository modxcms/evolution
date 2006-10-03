<?php
header("Content-type: text/html");

// Use the MiniXML library
require_once('minixml.inc.php');


?>

<HTML>
<HEAD>
<TITLE>MiniXML: Overview</TITLE>
<STYLE>
<!--
body,table,tr,td,p,
	{font-family:verdana,tahoma,arial,helvetica;font-size:12px}

pre {font-family:verdana,tahoma,arial,helvetica;font-size:12px}

code {font-size:11px}
-->
</STYLE>
</HEAD>

<BODY>

<TABLE WIDTH="80%" CELLPADDING="4" ALIGN="CENTER">
<TR>
<TD>


<H2>MiniXML API Overview and Demo</H3>
<P>MiniXML allows you to easily parse and generate XML using PHP.  Here is an overview of the API and, below that, a demonstration.  I must warn you that the demo program proceeds in a rather roundabout manner as this is example code.  And don't worry, it's not really that long, it's just that I'm quite verbose in the comments.</P>


<H2>Overview</H2>
<div align="center">
<TABLE BGCOLOR="#EEEEEE" CELLPADDING="9">
<TR>
<TD>
<PRE>
<div align="center">
<B>The Basics</B>
</div>
All your interactions with MiniXML begin with a MiniXMLDoc object.      
To create one, simply:  

	$xmlDoc = <font color="#ee3333">new</font> MiniXMLDoc();

Now that we have an object, we can use it to parse (read in) existing
XML and/or to create new XML.

<B>Creating XML.</B>
To generate XML, you get hold of an element (an instance of 
MiniXMLElement) and set attributes, add content or child elements.  
Since our document is empty, the only available element is the 
&quot;root&quot; element.

	$xmlRoot =& $xmlDoc-&gt;<font color="#ee3333">getRoot</font>();

Notice the reference assignment operator, <B>=&amp;</B>.  This is to ensure
that we work with the element itself instead of a mere copy.

Now we can add elements to our document root by creating children:

	$childElement =&amp; $xmlRoot-&gt;<font color="#ee3333">createChild</font>('achild');
	
Elements can have attributes and content (data or child elements).  Here we 
set a few attributes and content.

	$childElement-&gt;<font color="#ee3333">attribute</font>('name', 'annie');
	$childElement-&gt;<font color="#ee3333">attribute</font>('eyes', '#0000FF');
	$childElement-&gt;<font color="#ee3333">attribute</font>('hair', '#FF0000');
	
	$childElement-&gt;<font color="#ee3333">text</font>('This element has attributes 
				and children, such as this');
	
	  $image =&amp; $childElement-&gt;<font color="#ee3333">createChild</font>('image');
	  $image-&gt;<font color="#ee3333">attribute</font>('location', 
	  		'http://psychogenic.com/image.png');
	
	$childElement-&gt;<font color="#ee3333">text</font>('image and little');
	
You can also create orphan elements (that have no assigned parents), using the 
MiniXMLDoc object:

	$orphan =& $xmlDoc-&gt;<font color="#ee3333">createElement</font>('song');
	$orphan-&gt;<font color="#ee3333">text</font>('tomorrow, tomorrow');
	
When you are done, make sure to link the orphan to some parent element (or it
will be lost forever).

	$childElement-&gt;<font color="#ee3333">appendChild</font>($orphan);



To have a look at the current structure, we can convert our document to an XML
string with

	print $xmlDoc-&gt;<font color="#ee3333">toString</font>();
	
Which will display:<div align="center"><TABLE BGCOLOR="#EEEEEE" CELLPADDING="9"><TR><TD><PRE><CODE><?php


	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$childElement =& $xmlRoot->createChild('achild');
	$childElement->attribute('name', 'annie');
	$childElement->attribute('eyes', '#0000FF');
	$childElement->attribute('hair', '#FF0000');
	
	$childElement->text('This element has attributes and children, such as this');
	
	  $image =& $childElement->createChild('image');
	  $image->attribute('location', 'http://psychogenic.com/image.png');
	
	$childElement->text('image and little');
	 $orphan =& $xmlDoc->createElement('song');
	 $orphan->text('tomorrow, tomorrow');
	 $childElement->appendChild($orphan);
	 
	print htmlentities($xmlDoc->toString());
	
?></CODE></PRE></TD></TR></TABLE></div><B>Parsing XML and accessing data</B>
Parsing XML with MiniXML is easy.  When you've got a string of XML, you can 
initialise a MiniXMLDoc from that string using the aptly named <font color="#ee3333">fromString</font>()
method.

Create a MiniXMLDoc object:

	$parsedDoc = <font color="#ee3333">new</font> MiniXMLDoc();

Call fromString()

	$parsedDoc-&gt;<font color="#ee3333">fromString</font>($stringOfXML);
	
That's all.  Now we can access the elements and their data using appropriate methods.  
To access elements, start by getting the root element.

	$rootEl =&amp; $parsedDoc-&gt;<font color="#ee3333">getRoot</font>();
	
Then use any of these methods:


<font color="#ee3333">getElement</font>(NAME)
Will return the first child (or subchild) found with name NAME.

	$returnedElement =& $rootEl-&gt;<font color="#ee3333">getElement</font>('elementName');

<font color="#ee3333">getElementByPath</font>(PATH)
If you wish to access a particular sub element, <font color="#ee3333">getElementByPath</font>() 
will return the matching element if found.  For instance, with the example 
XML generated above we could access the image element from the root element by 
calling $returnedElement =& $rootEl-&gt;<font color="#ee3333">getElementByPath</font>('achild/image');

	$returnedElement =& $rootEl-&gt;<font color="#ee3333">getElementByPath</font>('rel/path/to/elementName');


<font color="#ee3333">getAllChildren</font>([NAME])	
In cases where an element has many children with the same name and path, you can 
access an array of all <i>immediate</i> children, eg 


	$elChildren =& $rootEl-&gt;<font color="#ee3333">getAllChildren</font>();
	
	for($i = 0; $i < $rootEl-&gt;<font color="#ee3333">numChildren</font>(); $i++)
	{
		if ($elChildren[$i]-&gt;<font color="#ee3333">name</font>() == 'aname')
		{
			/* We've found a child we're looking for */
			/* do stuff... */
			$itsValue = $elChildren[$i]-&gt;<font color="#ee3333">getValue()</font>;
			print "$itsValue\n";
			/* ... */
		}
	}

The optional NAME parameter will return only children with name NAME, 
so the if name() == 'aname' step could be skipped by calling 

$rootEl-&gt;<font color="#ee3333">getAllChildren</font>('aname');

</PRE>
</TD>
</TR>
</TABLE>
</div>


<H2>Example code</H2>

<H3>The mission</H3>
<P>You need to communicate with xmlpartsserver.com to query for part numbers and prices.  They have set up
a daemon that will accept TCP/IP connections and talks XML.</P>


<H3>Preparing a request</H3>
<P>Now you use MiniXML to prepare this request:</P>

<div align="center">
<TABLE BGCOLOR="#EEEEEE"  CELLPADDING="9">
<TR>
<TD>
<CODE>
<PRE>
// Use the MiniXML library
require_once('minixml.inc.php');


/* In this example, these are the part numbers we are interested in. */
$partNumbers = array('DA42', 'D99983FFF', 'ss-839uent');

/*  Create a new MiniXMLDoc object.  This document will be your 
**  interface to all the XML elements. 
*/
$xmlDoc = <font color="#ee3333">new</font> MiniXMLDoc();

/* XML is created in a hierarchical manner, like a tree.  To start
** creating our request, we need this tree's root.
**
** <B>NOTICE:</B> That weirdo '=&amp;' is not an ordinary assignment - it's PHP's
**		way of asking that $xmlRoot be a <I>REFERENCE</I> to the Root Element.
**		If you don't use =&amp;, you'll be working on a copy and you'll need
**		to $xmlDoc-&gt;<font color="#ee3333">setRoot</font>($xmlRoot) when you're done.
**
*/

$xmlRoot =&amp; $xmlDoc-&gt;<font color="#ee3333">getRoot</font>();

/* I've imagined a fictitious structure for this request but 
** they're usually something like this... 
**
** Let's start by adding a partRateRequest element (as a 
** child of the root element) and then we'll create some
** children of it's own.
**
** Again, note the use of the '=&amp;'.  The alternative is to
** use '=' and the $parent-&gt;<font color="#ee3333">appendChild</font>($child) but be 
** careful as the append is easy to forget...
*/
$rateReq =&amp; $xmlRoot-&gt;<font color="#ee3333">createChild</font>('partRateRequest');

 /* Now we'll create a vendor and a parts list element for the
 ** request and fill those up.
 */

 $vendor =&amp; $rateReq-&gt;<font color="#ee3333">createChild</font>('vendor');
   
   $accessid =&amp; $vendor-&gt;<font color="#ee3333">createChild</font>('accessid');
   /* Set up a few attributes for this element.
   ** notice that accessid will have attributes but no
   ** content (text or whatever) or children.
   */
   $accessid-&gt;<font color="#ee3333">attribute</font>('user', 'myusername');
   $accessid-&gt;<font color="#ee3333">attribute</font>('password', 'mypassword');
 
 /* Now we list the parts we are interested.  This element is
 ** directly under the partRateRequest element.
 */
 $partList =&amp; $rateReq-&gt;<font color="#ee3333">createChild</font>('partList');
 
 /* Now, we add a &lt;partnum&gt;XXX&lt;/partnum&gt; element for 
 ** each part in our array.  
 **
 ** Just for fun, here I'm using the createElement/appendChild
 ** method, instead of $partList-&gt;<font color="#ee3333">createChild</font>()
 */
 for($i=0; $i &lt; count($partNumbers); $i++)
 {
 	/* using MiniXMLDoc's createElement to create
	** an element with no parent 
	*/
 	$aPart = $xmlDoc-&gt;<font color="#ee3333">createElement</font>('partNum');
	
	/* Set a text value to this element */
	$aPart-&gt;<font color="#ee3333">text</font>($partNumbers[$i]);
	
	/* Now, don't forget to append this element to a parent
	** or it will simply dissappear
	*/
	$partList-&gt;<font color="#ee3333">appendChild</font>($aPart);
}


/* OK, we have our request in the xmlDoc.  To pass it along to the
** server, we stringify it with:
*/

$xmlString = $xmlDoc-&gt;<font color="#ee3333">toString</font>();

</PRE>
</CODE>
</TD>
</TR>
</TABLE>
</div>

<P> The preceding code will produce an XML document that looks like this:
</P>

<?php
/************************ PHP *************************/




/* In this example, these are the part numbers we are interested in. */
$partNumbers = array('DA42', 'D99983FFF', 'ss-839uent');

/*  Create a new MiniXMLDoc object.  This document will be your 
**  interface to all the XML elements. 
*/
$xmlDoc = new MiniXMLDoc();

/* XML is created in a hierarchical manner, like a tree.  To start
** creating our request, we need this tree's root.
**
** NOTICE: 	That weirdo '=&' is not an ordinary assignment - it's PHP's
**		way of asking that $xmlRoot be a REFERENCE to the Root Element.
**		If you don't use =&, you'll be working on a copy and you'll need
**		to $xmlDoc->setRoot($xmlRoot) when you're done.
**
*/

$xmlRoot =& $xmlDoc->getRoot();

/* I've imagined a fictitious structure for this request but 
** they're usually something like this... 
**
** Let's start by adding a partRateRequest element (as a 
** child of the root element) and then we'll create it's
** own children.
**
** Again, note the use of the '=&'.  The alternative is to
** use '=' and the $parent->appendChild($child) but the 
** append is easy to forget...
*/
$rateReq =& $xmlRoot->createChild('partRateRequest');

 /* Now we'll create a vendor and a parts list element for the
 ** request and fill those up.
 */

 $vendor =& $rateReq->createChild('vendor');
   
   $accessid =& $vendor->createChild('accessid');
   /* Set up a few attributes for this element.
   ** notice that accessid will have attributes but no
   ** content (text or whatever) or children.
   */
   $accessid->attribute('user', 'myusername');
   $accessid->attribute('password', 'mypassword');
 
 /* Now we list the parts we are interested.  This element is
 ** directly under the partRateRequest element.
 */
 $partList =& $rateReq->createChild('partList');
 
 /* Now, we add a <partnum>XXX</partnum> element for 
 ** each part in our array.  
 **
 ** Just for fun, here I'm using the createElement/appendChild
 ** method, instead of $partList->createChild()
 */
 for($i=0; $i<count($partNumbers); $i++)
 {
 	/* using MiniXMLDoc's createElement to create
	** an element with no parent 
	*/
 	$aPart = $xmlDoc->createElement('partNum');
	
	/* Set a text value to this element */
	$aPart->text($partNumbers[$i]);
	
	/* Now, don't forget to append this element to a parent
	** or it will simply dissappear
	*/
	$partList->appendChild($aPart);
}


/* OK, we have our request in the xmlDoc.  To pass it along to the
** server, we stringify it with:
*/

$xmlString = $xmlDoc->toString();

/* Here is the output from the toString() call:
*/
?>

<div align="center">
<TABLE BGCOLOR="#EEEEEE"  CELLPADDING="9">
<TR>
<TD><PRE><div align="center"><B>MiniXML toString() output</B></div>
<CODE><?php

print htmlentities($xmlString);


?></CODE></PRE>	
</TD>
</TR>
</TABLE>
</div>

<H3>Parsing the response</H3>

<P>
Now let's assume we've recieved a valid but messed up reply from the server.  It looks like:
</P>
<div align="center">
<TABLE BGCOLOR="#EEEEEE" CELLPADDING="9">
<TR>
<TD>
<PRE>
<div align="center"><B>Ugly XML response from server</B></div>
<CODE>
<?php 

/* Now we assume we've recieved a valid but messed up reply from the server,
** something like:
*/

$xmlString = '	<partsRateReply> <status><code>1	</code>		<message>
	OK
</message></status>		<partList><part num="DA42" models
= 
			"LS AR DF HG KJ" 
		update="2001-11-22"><name
			         >Camshaft end bearing 
		retention circlip
</name><image drawing=
 		"RR98-dh37" type=                                   "SVG" 
 	x="476" 
			y="226"/><
maker id
			="RQ778">
			Ringtown Fasteners Ltd</maker>
			<price>
	<currency>
		USD		</currency>
	                 <amount>                 $389.99  
	</amount>
				   </price>
					<notes>
  Angle-nosed insertion tool 
<tool id="GH25"/> 
is required for the removal and replacement of this item.
	</notes>
		</part>
		
		<part num="D99983FFF" 
models
=	"HG KJ" 
><name>Thingamajig for guys with mustaches
</name><image drawing="RR9ou8-aoao92" type=                                   "SVG" 
 	x="3556"  y="33"/><
maker id ="PQSMSM8"> RingWorm Nose Fasteners Inc</maker>
		 <price>
	<currency>
		USD		</currency>
	                 <amount>  $292.00  
	</amount>
				   </price>
		</part>
		
		
		</partList>
   </partsRateReply>';

print htmlentities($xmlString);

?></CODE></PRE>
</TD>
</TR>
</TABLE>
</div>

<P> We start by creating a new MiniXMLDoc and setting it up by using <font color="#ee3333">fromString</font>() with the received string. 
We will then be able to see a cleaned up version (by calling <font color="#ee3333">toString</font>() on the document) and will try out the methods associated with fetching elements and their data.
</P>


<div align="center">
<TABLE BGCOLOR="#EEEEEE"  CELLPADDING="9">
<TR>
<TD>
<CODE>
<PRE>


/* We create a new MiniXMLDoc object */
$returnedXMLDoc = new MiniXMLDoc();

/* And parse the returned string (assume it was stored in
** $xmlString
*/
$returnedXMLDoc-&gt;<font color="ee3333">fromString</font>($xmlString);

/* Now to verify that the document has been initialised from 
** the string, we use toString() to output an XML document.
*/

print $returnedXMLDoc-&gt;<font color="ee3333">toString</font>();

/* We can also get the data from the XML using the <font color="ee3333">getValue</font>() 
** method.  This will return all contents but no meta-data
** (ie strip the tags).
*/

print $returnedXMLDoc-&gt;<font color="ee3333">getValue</font>();



/* We can now query the document, fetching elements by name 
** (and path) or as a list of children.
**
** Let's start by fetching the partsRateReply element.
**
** <B>WARNING:</B>
** Normally, you'd be verifying that the element was found
** but in order to keep this light and to make sure nobody 
** gets into the habit of good error checking, I'll skip
** it.
**
**
** getElement() returns the first element with a matching name.
** if there are multiple elements with the same name, use one
** of the methods below.
*/

/* Note: the '=&amp;' operator here is optional: since we won't
** be modifying the response it doesn't matter if we work on
** a copy.  
*/
$rateResp =&amp; $returnedXMLDoc-&gt;<font color="ee3333">getElement</font>('partsRateReply');

/* We can now use the rateResponse element to get access 
** to it's children.
*/
$status =&amp; $rateResp-&gt;<font color="ee3333">getElement</font>('status');

$statusMessage =&amp; $status-&gt;<font color="ee3333">getElement</font>('message');

print "Status message is " . $statusMessage-&gt;<font color="ee3333">getValue</font>() ;


/* We can also access the elements by 'path' (if it is unique)
** To do so, use the getElementByPath method on an element.  The parameter
** is a path relative to this element (ie you may only access this element
** and any of it children/subchildren).
**
**
** Here we use the MiniXMLDoc object (thus the root element), so we pass
** the 'full path' as a parameter.
*/
$statusCode = $returnedXMLDoc-&gt;<font color="ee3333">getElementByPath</font>('partsRateReply/status/code');

print "Status Code is " . $statusCode-&gt;<font color="ee3333">getValue</font>();

/* Sometimes, for instance when you have an element with 
** multiple children with the same name like partNum, it's 
** best to simply have access to all the children at once.
*/

/* First get the element */
$partList =&amp; $returnedXMLDoc-&gt;<font color="ee3333">getElementByPath</font>('partsRateReply/partList');

/* Now get the list of children */
$partListChildren = $partList-&gt;<font color="ee3333">getAllChildren</font>();

/* we can use the returned array to play with all the children */
for($i=0; $i &lt; $partList-&gt;<font color="ee3333">numChildren</font>(); $i++)
{
	print "+++++++++++++++++++++++++++++++++++\n";
	
	$name = $partListChildren[$i]-&gt;<font color="ee3333">getElement</font>('name');
	$maker = $partListChildren[$i]-&gt;<font color="ee3333">getElement</font>('maker');
	$priceAmmount = $partListChildren[$i]-&gt;<font color="ee3333">getElementByPath</font>('price/amount');
	
	$partNum = $partListChildren[$i]-&gt;<font color="ee3333">attribute</font>('num');
	
	print "Part $partNum Name: " . $name-&gt;<font color="ee3333">getValue</font>();
	print "Part $partNum Maker:(" . $maker-&gt;<font color="ee3333">attribute</font>('id') . ")  " . $maker-&gt;<font color="ee3333">getValue</font>() ;
	print "Part $partNum Cost: " . $priceAmmount-&gt;<font color="ee3333">getValue</font>() ;
	print "\n\n";
	
}
</PRE></CODE>
</TD>
</TR>
</TABLE>
</div>




<P> The output from this code is included below.</P>






<div align="center">
<TABLE BGCOLOR="#EEEEEE" CELLPADDING="9">
<TR>
<TD>
<PRE>
<div ALIGN="CENTER"><B>MiniXML output</B></div><CODE>

<?php


/* We create a new MiniXMLDoc object */
$returnedXMLDoc = new MiniXMLDoc();

/* And parse the returned string (assume it was stored in
** $xmlString
*/
$returnedXMLDoc->fromString($xmlString);

/* Now to verify that the document has been initialised from 
** the string, we use toString() to output an XML document.
*/

print "Call to : \$returnedXMLDoc-&gt;<font color=\"ee3333\">toString</font>() returns:\n";

print htmlentities($returnedXMLDoc->toString());

print "Call to : \$returnedXMLDoc-&gt;<font color=\"ee3333\">getValue</font>() returns:";
print "\n\n</CODE></PRE><FONT SIZE=\"-2\">" . $returnedXMLDoc->getValue() . "</FONT><PRE><CODE>";

/* We can now query the document, fetching elements by name 
** (and path) or as a list of children.
**
** Let's start by fetching the partsRateReply element.
** Normally, you'd be verifying that the element was found
** but in order to keep this light and keep everybody from 
** getting into the habit of good error checking, I'll skip
** it.
**
** getElement() returns the first element with a matching name.
** if there are multiple elements with the same name, use one
** of the methods below.
*/

/* Note: the '=&' operator here is optional: since we won't
** be modifying the response it doesn't matter if we work on
** a copy.  
*/
$rateResp =& $returnedXMLDoc->getElement('partsRateReply');

/* We can now use the rateResponse element to get access 
** to it's children.
*/
$status =& $rateResp->getElement('status');

$statusMessage =& $status->getElement('message');

print "<BR>Status message is " . $statusMessage->getValue() . "<BR>\n";


/* We can also access the elements by 'path' (if it is unique)
** To do so, use the getElementByPath method on an element.  The parameter
** is a path relative to this element (ie you may only access this element
** and any of it children/subchildren).
**
**
** Here we use the MiniXMLDoc object (thus the root element), so we pass
** the 'full path' as a parameter.
*/
$statusCode = $returnedXMLDoc->getElementByPath('partsRateReply/status/code');

print "<BR>Status Code is " . $statusCode->getValue(). "<BR>\n";

/* Sometimes, for instance when you have an element with 
** multiple children with the same name like partNum, it's 
** best to simply have access to all the children at once.
*/

/* First get the element */
$partList =& $returnedXMLDoc->getElementByPath('partsRateReply/partList');

/* Now get the list of children */
$partListChildren = $partList->getAllChildren('part');

/* we can use the returned array to play with all the children */
for($i=0; $i < $partList->numChildren(); $i++)
{
	print "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++<BR>\n";
	
	$name = $partListChildren[$i]->getElement('name');
	$maker = $partListChildren[$i]->getElement('maker');
	$priceAmmount = $partListChildren[$i]->getElementByPath('price/amount');
	
	$partNum = $partListChildren[$i]->attribute('num');
	
	print "Part $partNum Name: " . $name->getValue() . "<BR>\n";
	print "Part $partNum Maker:(" . $maker->attribute('id') . ")  " . $maker->getValue() . "<BR>\n";
	print "Part $partNum Cost: " . $priceAmmount->getValue() . "<BR>\n";
	print "\n\n<BR><BR>";
	
}

?></CODE></PRE>
</TD>
</TR>
</TABLE>

</BODY>
</HTML>
