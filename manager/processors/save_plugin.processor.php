<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if($_SESSION['permissions']['save_plugin']!=1 && $_REQUEST['a']==103) {	
	$e->setError(3);
	$e->dumpError();	
}

function isNumber($var) {
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
$plugincode = mysql_escape_string($_POST['post']);
$properties = mysql_escape_string($_POST['properties']);
$disabled = $_POST['disabled']=="on" ? 1 : 0;
$sysevents = $_POST['sysevents'];

if($name=="") $name = "Untitled plugin";

switch ($_POST['mode']) {
    case '101':

		// invoke OnBeforePluginFormSave event
		$modx->invokeEvent("OnBeforePluginFormSave",
								array(
									"mode"	=> "new",
									"id"	=> $id
								));
    
		//do stuff to save the new plugin
		$sql = "INSERT INTO $dbase.".$table_prefix."site_plugins (name, description, plugincode, disabled, locked, properties) VALUES('".$name."', '".$description."', '".$plugincode."', '".$disabled."', '".$locked."', '".$properties."');";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! New plugin not saved!";
		} else {	
			// get the id
			if(!$newid=mysql_insert_id()) {
				echo "Couldn't get last insert key!";
				exit;
			}
			
			// save event listeners
			saveEventListeners($newid,$sysevents);	
			
			// invoke OnPluginFormSave event
			$modx->invokeEvent("OnPluginFormSave",
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
			// finished emptying cache - redirect
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "102&id=$newid":"101";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=76&r=2";
				header($header);
			}
		}		
        break;
    case '102':

		// invoke OnBeforePluginFormSave event
		$modx->invokeEvent("OnBeforePluginFormSave",
								array(
									"mode"	=> "upd",
									"id"	=> $id
								));
     
		//do stuff to save the edited plugin	
		$sql = "UPDATE $dbase.".$table_prefix."site_plugins SET name='".$name."', description='".$description."', plugincode='".$plugincode."', disabled='".$disabled."', locked='".$locked."', properties='".$properties."'  WHERE id='".$id."';";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! Edited plugin not saved!";
		} 
		else {		
			// save event listeners
			saveEventListeners($id,$sysevents);	

			// invoke OnPluginFormSave event
			$modx->invokeEvent("OnPluginFormSave",
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
			// finished emptying cache - redirect	
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "102&id=$id":"101";
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


# Save Plugin Event Listeners
function saveEventListeners($id,$sysevents) {
	global $dbase, $table_prefix;
	// save selected system events
	mysql_query("DELETE FROM $dbase.".$table_prefix."site_plugin_events WHERE pluginid='".$id."';");
	$sql = "INSERT INTO $dbase.".$table_prefix."site_plugin_events (pluginid,evtid) VALUES ";
	for($i=0;$i<count($sysevents);$i++){
		if($i>0) $sql.=",";
		$sql.= "('".$id."','".$sysevents[$i]."')";	
	}
	if (count($sysevents)>0) mysql_query($sql);
}

?>