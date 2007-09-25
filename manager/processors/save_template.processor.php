<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_template')) {	
	$e->setError(3);
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
$template = mysql_escape_string($_POST['post']);
$templatename = mysql_escape_string($_POST['templatename']);
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

if($templatename=="") $templatename = "Untitled template";

switch ($_POST['mode']) {
    case '19':
    
		// invoke OnBeforeTempFormSave event
		$modx->invokeEvent("OnBeforeTempFormSave",
								array(
									"mode"	=> "new",
									"id"	=> $id
							));	
							
		//do stuff to save the new doc
		$sql = "INSERT INTO $dbase.`".$table_prefix."site_templates` (templatename, description, content, locked, category) VALUES('$templatename', '$description', '$template', '$locked', ".$categoryid.");";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! New template not saved!";
		} else {
			// get the id
			if(!$newid=mysql_insert_id()) {
				echo "Couldn't get last insert key!";
				exit;
			}

			// invoke OnTempFormSave event
			$modx->invokeEvent("OnTempFormSave",
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
				$a = ($_POST['stay']=='2') ? "16&id=$newid":"19";
				$header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
				header($header);
			} else {
				$header="Location: index.php?a=76&r=2";
				header($header);
			}
		}		
        break;
    case '16':

		// invoke OnBeforeTempFormSave event
		$modx->invokeEvent("OnBeforeTempFormSave",
								array(
									"mode"	=> "upd",
									"id"	=> $id
							));	   
							
		//do stuff to save the edited doc
		$sql = "UPDATE $dbase.`".$table_prefix."site_templates` SET templatename='$templatename', description='$description', content='$template', locked='$locked', category=".$categoryid." WHERE id=$id;";
		$rs = mysql_query($sql);
		if(!$rs){
			echo "\$rs not set! Edited template not saved!";
		} else {

			// invoke OnTempFormSave event
			$modx->invokeEvent("OnTempFormSave",
									array(
										"mode"	=> "upd",
										"id"	=> $id
								));	    		

			// first empty the cache		
			include_once "cache_sync.class.processor.php";
			$sync = new synccache();
			$sync->setCachepath("../assets/cache/");
			$sync->setReport(false);
			$sync->emptyCache(); 		
			// finished emptying cache - redirect	
			if($_POST['stay']!='') {
				$a = ($_POST['stay']=='2') ? "16&id=$id":"19";
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
