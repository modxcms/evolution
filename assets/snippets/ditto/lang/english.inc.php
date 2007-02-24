<?php

/*
 * Title: Class
 * Purpose:
 *  	Default English language file for Ditto
 *  	
 * Note:
 * 		New language keys should added at the bottom of this page
 */

$_lang['language'] = "english";

$_lang['abbr_lang'] = "en";

$_lang['file_does_not_exist'] = "does not exist. Please check the file.";

$_lang['default_template'] = <<<TPL

    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">by <strong>[+author+]</strong> on [+createdon:date=`%d-%b-%y %H:%M`+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>

TPL;

$_lang["bad_tpl"] = "<p>[+tpl+] either does not contain any placeholders or is an invalid chunk name, code block, or filename. Please check it.</p>";

$_lang['no_documents'] = '<p>No documents found.</p>';

$_lang['resource_array_error'] = 'Resource Array Error';
 
$_lang['prev'] = "&lt; Previous";

$_lang['next'] = "Next &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2006";

$_lang['invalid_class'] = "The Ditto class is invalid. Please check it.";

$_lang['none'] = "None";

$_lang['edit'] = "Edit";

$_lang['yes'] = "Yes";

$_lang['no'] = "No";

$_lang['params'] = "Parameters";

$_lang['debug_head'] = "
<h2>Ditto Version [+version+]</h2>
<h3>Debug Info</h3>
Number of documents supposed to summarized : [+summarize+]<br />
Number of documents collected from the database: [+recordCount+]<br />
Sort by : [+sortBy+]<br />
Sort direction: [+sortDir+]<br />
Start at item [+start+] and stop at [+stop+] out of [+total+]<br />
Prefetch: [+prefetch+]<br />
<h3>IDs</h3>
[+ids+]<br />
<h3>Snippet Parameters</h3>
[+call+]<br />
<h3>Filter</h3>
[+filter+]<br />
<h3>Fields</h3>
<div class='ditto_dbg_fields'>
[+fields+]
</div><br />
<h3>Document Data</h3>
";

$_lang["debug_styles"] = "
<style>
  .debug {
  	border: 1px solid #888;
  	border-left: 5px solid #888;
  	background-color: white;
  	padding: 3px !important;
  	margin: 5px 3px !important;
  }        

 	table { border: 1px solid #888; margin: 0; padding: 0;}
	table td table {border: 0;}
	table th td {border: 1px solid #888;}
	table td { background-color:#FFFFFF; padding: 2px;}
	table th { background-color:#888; padding: 2px; border: 1px solid #888;}
	table td th { background-color:#008CBA; color: white; border: 1px solid #888; }
	table td {vertical-align: top !important;}
	.ditto_dbg_fields table table table { width: 60px; display: block}
	.ditto_dbg_fields table table table td{display: block; float: left;}
	table table tr td tr td{border: 1px solid #888; }
	
</style>
";

$_lang['debug_item'] = "[+pagetitle+] ([+id+])";

?>