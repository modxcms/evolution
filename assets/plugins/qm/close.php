<?php
/**
 * Qm+ — QuickManager+ clearer page
 *  
 * @author      Mikko Lammi, www.maagit.fi
 * @license     GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @version     1.3.4 updated 7/10/2009                
 */

// Get parameters
if (isset($_GET['id'])) $id = intval($_GET['id']);
else $id = '';

print <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title></title>
<script type="text/javascript">
function getCookie(cookieName)
{
    var results = document.cookie.match ( "(^|;) ?" + cookieName + "=([^;]*)(;|$)" );

    if (results) return (unescape(results[2]));
    else return null;
}

function getUrl()
{
    var protocol = window.location.protocol;
    var host = window.location.host;
    var baseUrl = getCookie("baseUrlQM");
    
    return protocol + "//" + host + baseUrl + "index.php?id={$id}"; 
}

</script>

</head>
<body onload="javascript: parent.location.href = getUrl();">
</body>
</html>
HTML;

?>