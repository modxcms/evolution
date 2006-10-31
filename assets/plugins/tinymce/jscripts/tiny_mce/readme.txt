TinyMCE Compressor PHP 1.08
---------------------------

TinyMCE Compressor gzips all javascript files in TinyMCE to a single streamable file.
This makes the overall download sice 75% smaller and all requests are merged into a few requests.

To enable this compressor simply place the tiny_mce_gzip.php in the tiny_mce directory where tiny_mce.js is located and switch your scripts form:

<script language="javascript" type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>

to

<script language="javascript" type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce_gzip.php"></script>

Visit the TinyMCE forum for help with the TinyMCE Gzip Compressor.


Author:		Moxiecode Systems
Version: 	1.08

Copyright © 2005-2006, Moxiecode Systems AB, All rights reserved.
