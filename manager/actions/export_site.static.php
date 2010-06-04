<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('export_static')) {
	$e->setError(3);
	$e->dumpError();
}

// figure out the base of the server, so we know where to get the documents in order to export them
$base = MODX_SITE_URL;


?>

<script type="text/javascript">
function reloadTree() {
	// redirect to welcome
	document.location.href = "index.php?r=1&a=7";
}
</script>

<h1><?php echo $_lang['export_site_html']; ?></h1>

<div class="sectionBody">
<?php

if(!isset($_POST['export'])) {
echo '<p>'.$_lang['export_site_message'].'</p>';
?>

<fieldset style="padding:10px"><legend><?php echo $_lang['export_site']; ?></legend>
<form action="index.php" method="post" name="exportFrm">
<input type="hidden" name="export" value="export" />
<input type="hidden" name="a" value="83" />
<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td valign="top"><b><?php echo $_lang['export_site_cacheable']; ?></b></td>
    <td width="30">&nbsp;</td>
    <td><input type="radio" name="includenoncache" value="1" checked="checked"><?php echo $_lang['yes'];?><br />
		<input type="radio" name="includenoncache" value="0"><?php echo $_lang['no'];?></td>
  </tr>
  <tr>
    <td><b><?php echo $_lang['export_site_prefix']; ?></b></td>
    <td>&nbsp;</td>
    <td><input type="text" name="prefix" value="<?php echo $friendly_url_prefix; ?>" /></td>
  </tr>
  <tr>
    <td><b><?php echo $_lang['export_site_suffix']; ?></b></td>
    <td>&nbsp;</td>
    <td><input type="text" name="suffix" value="<?php echo $friendly_url_suffix; ?>" /></td>
  </tr>
  <tr>
    <td valign="top"><b><?php echo $_lang['export_site_maxtime']; ?></b></td>
    <td>&nbsp;</td>
    <td><input type="text" name="maxtime" value="60" />
		<br />
		<small><?php echo $_lang['export_site_maxtime_message']; ?></small>
	</td>
  </tr>
</table>

<ul class="actionButtons">
	<li><a href="#" onclick="document.exportFrm.submit();"><img src="<?php echo $_style["icons_save"] ?>" /> <?php echo $_lang["export_site_start"]; ?></a></li>
</ul>
</form>
</fieldset>

<?php
} else {

	$maxtime = $_POST['maxtime'];
	if(!is_numeric($maxtime)) {
		$maxtime = 30;
	}

	@set_time_limit($maxtime);
	$mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $exportstart = $mtime;

	$filepath = "../assets/export/";
	if(!is_writable($filepath)) {
		echo $_lang['export_site_target_unwritable'];
		include "footer.inc.php";
		exit;
	}

	$prefix = $_POST['prefix'];
	$suffix = $_POST['suffix'];

	$noncache = $_POST['includenoncache']==1 ? "" : "AND $dbase.`".$table_prefix."site_content`.cacheable=1";

	// Support export alias path  
	function removeDirectoryAll($directory) {
		// if the path has a slash at the end, remove it
		if(substr($directory,-1) == '/') {
			$directory = substr($directory,0,-1);
		}
		// if the path is not valid or is not a directory ...
		if(!file_exists($directory) || !is_dir($directory)) {
			return FALSE;
		} elseif(!is_readable($directory)) {
			return FALSE;
		} else {
			$dh = opendir($directory);
			while (FALSE !== ($file = @readdir($dh))) {
				if($file != '.' && $file != '..') {
					$path = $directory.'/'.$file;
					if(is_dir($path)) {
						// call myself
						removeDirectoryAll($path);
					} else {
						@unlink($path);
					}
				}
			}
			closedir($dh);
		}
		return (@rmdir($directory));
	}

	function writeAPage($baseURL, $docid, $filepath) {
		global $_lang;
		global $base;
		if(@$handle = fopen($baseURL."/index.php?id=".$docid, "r")) {
			$buffer = "";
			while (!feof ($handle)) {
				$buffer .= fgets($handle, 4096);
			}
			fclose ($handle);
			$somecontent = $buffer;
			if (!$handle = fopen($filepath, 'w')) {
				echo '<p><span class="fail">'.$_lang["export_site_failed"]."</span> ".$_lang["export_site_failed_no_open_filepath"].'</p>';
				return FALSE;
			} else {
				// Write $somecontent to our opened file.
				if(fwrite($handle, $somecontent) === FALSE) {
					echo '<p><span class="fail">'.$_lang["export_site_failed"]."</span> ".$_lang["export_site_failed_no_write"].'</p>';
					return FALSE;
				}
				fclose($handle);
				echo '<p class="success">'.$_lang["export_site_success"].'</p>';
			}
		} else {
			echo '<p><span class="fail">'.$_lang["export_site_failed"]."</span> ".$_lang["export_site_failed_no_retrieve"].'</p>';
//			return FALSE;
		}
		return TRUE;
	}

	function getPageName($docid, $alias, $prefix, $suffix) {
		if(empty($alias)) {
			$filename = $prefix.$docid.$suffix;
		} else {
			$pa = pathinfo($alias); // get path info array
			$tsuffix = !empty($pa['extension']) ? '':$suffix;
			$filename = $prefix.$alias.$tsuffix;
		}
		return $filename;
	}

	function scanDirectory($path, $files) {
		// if the path has a slash at the end, remove it
		if(substr($path, -1) == '/') {
			$path = substr($path, 0, -1);
		}
		// if the path is not valid or is not a directory ...
		if(!file_exists($path) || !is_dir($path)) {
			return FALSE;
		} elseif(!is_readable($path)) {
			return FALSE;
		} else {
			$dh = opendir($path);
			while (FALSE !== ($filename = @readdir($dh))) {
				if($filename != '.' && $filename != '..' && substr($filename, 1) != '.') {
					if (!in_array($filename, $files)) {
						$file = $path."/".$filename;
						if (is_dir($file)) {
							removeDirectoryAll($file);
						} else {
							@unlink($file);
						}
					}
				}
			}
			closedir($dh);
			return TRUE;
		}
	}

	function exportDir($dirid, $dirpath, &$i) {
		global $_lang;
		global $base;
		global $modx;
		global $limit;
		global $dbase;
		global $table_prefix;
		global $sqlcond;

		$sql = "SELECT id, alias, pagetitle, isfolder, (content = '' AND template = 0) AS wasNull, editedon FROM $dbase.`".$table_prefix."site_content` WHERE $dbname.`".$table_prefix."site_content`.parent = ".$dirid." AND ".$sqlcond;
		$rs = mysql_query($sql);
		$dircontent = array();
		while($row = mysql_fetch_assoc($rs)) {
			if (!$row['wasNull']) { // needs writing a document
				$docname = getPageName($row['id'], $row['alias'], $modx->config['friendly_url_prefix'], $suffix = $modx->config['friendly_url_suffix']);
				printf($_lang['export_site_exporting_document'], $i++, $limit, $row['pagetitle'], $row['id']);
				$filename = $dirpath.$docname;
				if (is_dir($filename)) {
					removeDirectoryAll($filename);
				}
				if (!file_exists($filename) || (filemtime($filename) < $row['editedon'])) {
					if (!writeAPage($base, $row['id'], $filename)) exit;
				} else {
					echo '<p><span class="success">'.$_lang['export_site_success']."</span> ".$_lang["export_site_success_skip_doc"].'</p>';
				}
				$dircontent[] = $docname;
			}
			if ($row['isfolder']) { // needs making a folder
				$dirname = $dirpath.$row['alias'];
				if (!is_dir($dirname)) {
					if (file_exists($dirname)) @unlink($dirname);
					mkdir($dirname);
					if ($row['wasNull']) {
						printf($_lang['export_site_exporting_document'], $i++, $limit, $row['pagetitle'], $row['id']);
						echo '<p class="success">'.$_lang['export_site_success'].'</p>';
					}
				} else {
					if ($row['wasNull']) {
						printf($_lang['export_site_exporting_document'], $i++, $limit, $row['pagetitle'], $row['id']);
						echo '<p><span class="success">'.$_lang['export_site_success'].$_lang["export_site_success_skip_dir"].'</p>';
					}
				}
				exportDir($row['id'], $dirname."/", $i);
				$dircontent[] = $row['alias'];
			}
		}
		// remove No-MODx files/dirs 
		if (!scanDirectory($dirpath, $dircontent)) exit;
//		print_r ($dircontent);
	}

	if($modx->config['friendly_urls']==1 && $modx->config['use_alias_path']==1) {
		$sqlcond = "$dbase.`".$table_prefix."site_content`.deleted=0 AND (($dbase.`".$table_prefix."site_content`.published=1 AND $dbase.`".$table_prefix."site_content`.type='document') OR ($dbase.`".$table_prefix."site_content`.isfolder=1)) $noncache";
		$sql = "SELECT count(*) as count1 FROM $dbase.`".$table_prefix."site_content` WHERE ".$sqlcond;
		$rs = mysql_query($sql);
		$row = mysql_fetch_row($rs);
		$prefix = $modx->config['friendly_url_prefix'];
		$suffix = $modx->config['friendly_url_suffix'];
		$limit = $row[0];
		printf($_lang['export_site_numberdocs'], $limit);
		$n = 1;
		exportDir(0, $filepath, $n);

	} else {
	// Modified for export alias path  2006/3/24 end
		$sql = "SELECT id, alias, pagetitle FROM $dbase.`".$table_prefix."site_content` WHERE $dbase.`".$table_prefix."site_content`.deleted=0 AND $dbase.`".$table_prefix."site_content`.published=1 AND $dbase.`".$table_prefix."site_content`.type='document' $noncache";
		$rs = mysql_query($sql);
		$limit = mysql_num_rows($rs);
		printf($_lang['export_site_numberdocs'], $limit);

		for($i=0; $i<$limit; $i++) {

			$row=mysql_fetch_assoc($rs);

			$id = $row['id'];
			printf($_lang['export_site_exporting_document'], $i, $limit, $row['pagetitle'], $id);
			$alias = $row['alias'];
		
			if(empty($alias)) {
				$filename = $prefix.$id.$suffix;
			} else {
				$pa = pathinfo($alias); // get path info array
				$tsuffix = !empty($pa[extension]) ? '':$suffix;
				$filename = $prefix.$alias.$tsuffix;
			}
			// get the file
			if(@$handle = fopen("$base/index.php?id=$id", "r")) {
				$buffer = "";
				while (!feof ($handle)) {
					$buffer .= fgets($handle, 4096);
				}
				fclose ($handle);

				// save it
				$filename = "$filepath$filename";
				$somecontent = $buffer;

				if(!$handle = fopen($filename, 'w')) {
					echo '<p><span class="fail">'.$_lang["export_site_failed"]."</span> ".$_lang["export_site_failed_no_open_filename"].'</p>';
					exit;
				} else {
					// Write $somecontent to our opened file.
					if(fwrite($handle, $somecontent) === FALSE) {
						echo '<p><span class="fail">'.$_lang["export_site_failed"]."</span> ".$_lang["export_site_failed_no_writee"].'</p>';
						exit;
					}
					fclose($handle);
					echo '<p class="success">'.$_lang['export_site_success'].'</p>';
				}
			} else {
				echo '<p><span class="fail">'.$_lang["export_site_failed"]."</span> ".$_lang["export_site_failed_no_retrieve"].'</p>';
			}
		}
	}
	$mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $exportend = $mtime;
	$totaltime = ($exportend - $exportstart);
	printf ('<p>'.$_lang["export_site_time"].'</p>', round($totaltime, 3));
?>
<ul class="actionButtons">
	<li><a href="#" onclick="reloadTree();"><img src="<?php echo $_style["icons_cancel"] ?>" /> <?php echo $_lang["close"]; ?></a></li>
</ul>
<?php
}
?>