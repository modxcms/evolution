<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_chunk')) {
	$e->setError(3);
	$e->dumpError();	
}
?>
<?php

$id = intval($_POST['id']);
$snippet = mysql_escape_string($_POST['post']);
$name = mysql_escape_string($_POST['name']);
$description = mysql_escape_string($_POST['description']);
$locked = $_POST['locked']=='on' ? 1 : 0 ;

//Kyle Jaebker - added category support
if (empty($_POST['newcategory']) && $_POST['categoryid'] > 0) {
    $categoryid = mysql_escape_string($_POST['categoryid']);
} elseif (empty($_POST['newcategory']) && $_POST['categoryid'] <= 0) {
    $categoryid = 0;
} else {
    include_once "categories.inc.php";
    $catCheck = checkCategory(mysql_escape_string($_POST['newcategory']));
    if ($catCheck) {
        $categoryid = $catCheck;
    } else {
        $categoryid = newCategory(mysql_escape_string($_POST['newcategory']));
    }
}

if($name=="") $name = "Untitled chunk";

switch ($_POST['mode']) {
    case '77':

		// invoke OnBeforeChunkFormSave event
		$modx->invokeEvent("OnBeforeChunkFormSave",
								array(
									"mode"	=> "new",
									"id"	=> $id
								));

		//do stuff to save the new doc
		$sql = "INSERT INTO $dbase.`".$table_prefix."site_htmlsnippets` (name, description, snippet, locked, category) VALUES('".$name."', '".$description."', '".$snippet."', '".$locked."', ".$categoryid.");";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! New Chunk not saved!";
		} else {	
			// get the id
			if(!$newid=mysql_insert_id()) {
				echo "Couldn't get last insert key!";
				exit;
			}

			// invoke OnChunkFormSave event
			$modx->invokeEvent("OnChunkFormSave",
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
				$a = ($_POST['stay']=='2') ? "78&id=$newid":"77";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=76&r=2";
				header($header);
			}
		}		
        break;
    case '78':

		// invoke OnBeforeChunkFormSave event
		$modx->invokeEvent("OnBeforeChunkFormSave",
								array(
									"mode"	=> "upd",
									"id"	=> $id
								));
		
		//do stuff to save the edited doc
		$sql = "UPDATE $dbase.`".$table_prefix."site_htmlsnippets` SET name='".$name."', description='".$description."', snippet='".$snippet."', locked='".$locked."', category=".$categoryid." WHERE id='".$id."';";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! Edited htmlsnippet not saved!";
		} else {		
			// invoke OnChunkFormSave event
			$modx->invokeEvent("OnChunkFormSave",
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
				$a = ($_POST['stay']=='2') ? "78&id=$id":"77";
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
?>
