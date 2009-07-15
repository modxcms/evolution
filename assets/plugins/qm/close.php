<?php
/**
 * Qm+ — QuickManager+ clearer page
 *  
 * @author      Mikko Lammi, www.maagit.fi
 * @license     GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @version     1.1 updated 15/04/2009                
 */

// Get parameters
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_GET['baseurl'])) $baseurl = $_GET['baseurl'];
if (isset($_GET['action'])) $action = $_GET['action'];

// Cancel => Remove ThickBox frame
if ($action == "cancel") {
    $body = '<body onload="parent.cb_remove();">';
}

// Save document => Refresh saved document
else {
    $body = '<body onload="parent.location.href = \''.$baseurl.'index.php?id='.$id.'\';">';
}

print <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title></title>
</head>
{$body}
</body>
</html>
HTML;

?>