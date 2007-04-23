<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('import_static')) {
    $e->setError(3);
    $e->dumpError();
}

// Files to upload
$allowedfiles = array('html','htm','shtml','xml');

?>

<script type="text/javascript">
    parent.tree.ca = "parent";
    function setParent(pId, pName) {
        document.importFrm.parent.value=pId;
        document.getElementById('parentName').innerHTML = pId + " (" + pName + ")";
    }
    function reloadTree() {
        // redirect to welcome
        document.location.href = "index.php?r=1&a=7";
    }
</script>
<br />
<div class="sectionHeader"><?php echo $_lang['import_site_html']; ?></div><div class="sectionBody">
<?php

if(!isset($_POST['import'])) {
    echo $_lang['import_site_message'];
?>
<p />
<fieldset style="padding:10px"><legend><?php echo $_lang['import_site']; ?></legend>
<form action="index.php" method="post" name="importFrm">
<input type="hidden" name="import" value="import" />
<input type="hidden" name="a" value="95" />
<input type="hidden" name="parent" value="0" />
<table border="0" cellspacing="0" cellpadding="2" width="400">
  <tr>
    <td nowrap="nowrap"><b><?php echo $_lang['import_parent_document']; ?></b></td>
    <td>&nbsp;</td>
    <td><b><span id="parentName">0 (<?php echo $site_name; ?>)</span></b></td>
  </tr>
  <tr>
    <td valign="top"><b><?php echo $_lang['import_site_maxtime']; ?></b></td>
    <td>&nbsp;</td>
    <td><input type="text" name="maxtime" value="30" />
        <br />
        <small><?php echo $_lang['import_site_maxtime_message']; ?></small>
    </td>
  </tr>
</table>
<p />
<table cellpadding="0" cellspacing="0" class="actionButtons">
    <td id="Button1"><a href="#" onclick="document.importFrm.submit();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" align="absmiddle"> <?php echo $_lang["import_site_start"]; ?></a></td>
</table>
</form>
</fieldset>

<?php
} else {

    $maxtime = $_POST['maxtime'];
    if(!is_numeric($maxtime)) {
        $maxtime = 30;
    }

    @set_time_limit($maxtime);
    $mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $importstart = $mtime;

    $parent = $_POST['parent'];
    $filepath = "../assets/import/";
    $filesfound = 0;

    $files = getFiles($filepath);

    // no. of files to import
    printf($_lang['import_files_found'], $filesfound);

    // import files
    if(count($files)>0) {
        importFiles($parent,$filepath,$files);
    }

    $mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $importend = $mtime;
    $totaltime = ($importend - $importstart);
    printf ("<p />".$_lang['import_site_time'], round($totaltime, 3));
?>
<p />
<table cellpadding="0" cellspacing="0" class="actionButtons">
    <td id="Button2"><a href="#" onclick="reloadTree();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" align="absmiddle"> <?php echo $_lang["close"]; ?></a></td>
</table>
<script type="text/javascript">
    parent.tree.ca = "";
</script>
<?php
}

function importFiles($parent,$filepath,$files) {

    global $modx;
    global $_lang, $allowedfiles;
    global $dbase, $table_prefix;
    global $default_template, $search_default, $cache_default, $publish_default;


    $createdon = time();
    $createdby = $modx->getLoginUserID();
    if (!is_array($files)) return;
    foreach($files as $id => $value){
        if(is_array($value)) {
            // create folder
            $alias = !isset($modx->documentListing[$id]) ? $id:$id.'-'.substr(uniqid(''),-3);
            $modx->documentListing[$alias] = true;
            printf($_lang['import_site_importing_document'], $id);
            $sql = "INSERT INTO $dbase.`".$table_prefix."site_content`
                   (type, contentType, pagetitle, alias, published, parent, isfolder, content, template, menuindex, searchable, cacheable, createdby, createdon) VALUES
                   ('document', 'text/html', '".mysql_escape_string($id)."', '".stripAlias($alias)."', ".$publish_default.", '$parent', 1, '', '".$default_template."', 0, ".$search_default.", ".$cache_default.", $createdby, $createdon);";
            $rs = mysql_query($sql);
            if($rs) $new_parent = mysql_insert_id(); // get new parent id
            else {
                echo "A database error occured while trying to clone document: <br /><br />".mysql_error();
                exit;
            }
            echo $_lang['import_site_success']."<br />";
            importFiles($new_parent,$filepath."/$id/",$value);
        }
        else {
            // create dcoument
            $filename = $value;
            $fparts = explode(".",$value);
            $value = $fparts[0];
            $ext = (count($fparts)>1)? $fparts[count($fparts)-1]:"";
            printf($_lang['import_site_importing_document'], $filename);
            $alias = !isset($modx->documentListing[$value]) ? $value:$value.'-'.substr(uniqid(''),-3);
            $modx->documentListing[$alias] = true;
            if(!in_array($ext,$allowedfiles)) echo $_lang['import_site_skip']."<br />";
            else {
                $file = getFileContent("$filepath/$filename");
                if (preg_match("/<title>(.*)<\/title>/i",$file,$matches)) {
                    $pagetitle = $matches[1];
                } else $pagetitle = $value;
                if(!$pagetitle) $pagetitle = $value;
                if (preg_match("/<body[^>]*>(.*)[^<]+<\/body>/is",$file,$matches)) {
                    $content = $matches[1];

                } else $content = $file;
                $sql = "INSERT INTO $dbase.`".$table_prefix."site_content`
                       (type, contentType, pagetitle, alias, published, parent, isfolder, content, template, menuindex, searchable, cacheable, createdby, createdon) VALUES
                       ('document', 'text/html', '".mysql_escape_string($pagetitle)."', '".stripAlias($alias)."', ".$publish_default.", '$parent', 0, '".mysql_escape_string($content)."', '".$default_template."', 0, ".$search_default.", ".$cache_default.", $createdby, $createdon);";
                $rs = mysql_query($sql);
                if(!$rs) {
                    echo $_lang['import_site_failed']."A database error occured while trying to clone document: <br /><br />".mysql_error();
                    exit;
                }
                echo $_lang['import_site_success']."<br />";
            }
        }
    }
}

function getFiles($directory,$listing = array(), $count = 0){
    global $_lang;
    global $filesfound;
    $dummy = $count;
    if (@$handle = opendir($directory)) {
        while ($file = readdir($handle)) {
            if ($file=='.' || $file=='..') continue;
            else if ($h = @opendir($directory.$file."/")) {
                closedir($h);
                $count = -1;
                $listing["$file"] = getFiles($directory.$file."/",array(), $count + 1);
            }
            else {
                $listing[$dummy] = $file;
                $dummy = $dummy + 1;
                $filesfound++;
            }
        }
    }
    else {
        echo $_lang['import_site_failed']." Could not open '$directory'.<br />";
    }
    @closedir($handle);
    return ($listing);
}

function getFileContent($file) {
    global $_lang;
    // get the file
    if(@$handle = fopen($file, "r")) {
        $buffer = "";
        while (!feof ($handle)) {
           $buffer .= fgets($handle, 4096);
        }
        fclose ($handle);
    }
    else {
        echo $_lang['import_site_failed']." Could not retrieve document '$file'.<br />";
    }
    return $buffer;
}

function stripAlias($alias) {
    $alias = strip_tags($alias);
    $alias = strtolower($alias);
    $alias = preg_replace('/&.+?;/', '', $alias); // kill entities
    $alias = preg_replace('/[^\.%a-z0-9 _-]/', '', $alias);
    $alias = preg_replace('/\s+/', '-', $alias);
    $alias = preg_replace('|-+|', '-', $alias);
    $alias = trim($alias, '-');
    return $alias;
}

?>
