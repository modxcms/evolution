<?php

	if(IN_ETOMITE_SYSTEM!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the Etomite Manager instead of accessing this file directly.");
	$id = $_REQUEST['id'];

	$sql = "SELECT * FROM $dbase.".$table_prefix."site_snippets WHERE $dbase.".$table_prefix."site_snippets.id = $id;";
	$rs = mysql_query($sql);
	$limit = mysql_num_rows($rs);
	if($limit>1) {
		echo " Internal System Error...<p>";
		print "More results returned than expected. <p>Aborting.";
		exit;
	}
	$content = mysql_fetch_assoc($rs);
?>

<div class="subTitle">
	<span class="right"><img src="media/images/_tx_.gif" width="1" height="5"><br /><?php echo $site_name ;?> - <?php echo $_lang["plugins"]; ?></span>
</div>

<div class="sectionHeader"><img src='media/images/misc/dot.gif' alt="." />&nbsp;
<?php echo $content['name']; ?>
</div>
<div class="sectionBody" id="lyr1">
<?php
	// Executable Snippet
	$doc = $content['name'];
	if($doc){
		define("SNIPPET_INTERACTIVE_MODE","true");
		include_once "etomite.class.inc.php";		
		$etomite = new etomite;	// initiate a new document parser

		// set some options
		$etomite->snippetParsePasses = 3;
		$etomite->nonCachedSnippetParsePasses = 2;
		$etomite->documentIdentifier = $content['id'];

		$doc = $etomite->mergeSettingsContent("[[$doc]]");
		$doc = $etomite->mergeHTMLSnippetsContent($doc);

		$etomite->documentContent = $etomite->evalSnippets($doc);
		$etomite->outputContent();
	}
?>
</div>

