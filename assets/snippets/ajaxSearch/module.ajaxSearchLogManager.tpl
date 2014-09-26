/**
* AjaxSearch Log manager
*
* Display the content of the AjaxSearch Log table in the manager
*
* @category module
* @version 	1.10.1
* @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
* @internal	@modx_category Search
* @author   Coroico
* @date     05/06/2014
*/

Define('NB_LINES',100); // number of rows displayed
Define('AJAXSEARCH_LOG', 'ajaxsearch_log'); // ajaxSearch table log. 

function getLogs($tb,$nb){
    global $modx;
    $db = $modx->db->config['dbase'];
    $name = $modx->db->config['table_prefix'] . $tb;
    $show = "SHOW TABLES FROM {$db} LIKE '{$name}'";
    $query = $modx->db->query($show);
    if ($modx->db->getRecordCount($query)) {
        $rs = $modx->db->select("searchstring,nb_results,results,comment,as_call,date,ip", $modx->getFullTableName($tb), '', 'id DESC', "0, $nb");
        return $rs;
    }
    else return(0);
}

$opcode = isset($_POST['opcode']) ? $_POST['opcode'] : '';

// action directive

switch($opcode) {

    default: // display module page
    $output = <<<EOD
<html>
<head>
<link rel="stylesheet" type="text/css" href="media/style/{$modx->config['manager_theme']}/style.css" />
<style type="text/css">
body { 
  color: #222; 
  font-family: "Helvetica Neue", Arial, Helvetica, sans-serif;
  font-size: 75%;
}
tbody, thead, tr {
	font-size: 82.5%;
}
.subtitle {
    margin: 0 0 0 20px;
}
.infos {
    margin: 10px 0 0 20px;
}
</style>
</head>
<body>
<script language="JavaScript" type="text/javascript">
    function postForm(opcode){
        document.module.opcode.value=opcode;
        document.module.submit();
    }
</script>
<form name="module" method="post">
<input name="opcode" type="hidden" value="" />
<h1>AjaxSearch Logs Manager</h1>
<h3 class="subtitle">Follow up searches ...</h3>
<p class="infos">One row is listed by group of results. The user comment is set on the last group of results.</p>
<hr />
EOD;
    echo $output;
    $rs = getLogs(AJAXSEARCH_LOG,NB_LINES);

    $output = <<<EOD
<table class="grid">
<thead class="gridHeader">
<tr>
    <th>Search Terms</th>
    <th>Nb results</th>
    <th>Doc ids</th>
    <th style="width:15%">Comment</th>
    <th>Snippet Call</th>
    <th>Date</th>
    <th>Ip</th>
</tr>
</thead>
<tbody>
EOD;
    echo $output;
    if ($rs) {
        $i=0;
        while($row = $modx->db->getRow($rs)){
            $vclass = (($i % 2) == 0) ? 'gridItem' : 'gridAltItem';
            $i += 1;
            echo '<tr class="' . $vclass . '"><td>'.$row['searchstring'].'</td><td>'.$row['nb_results'].'</td>';
            echo '<td>'.$row['results'].'</td><td>'.$row['comment'].'</td><td>'.$row['as_call'].'</td>';
            echo '<td>'.$row['date'].'</td><td>'.$row['ip'].'</td></tr>';
        }
    }
    $output = <<<EOD
</tbody>
</table>
EOD;
    echo $output;
    if (!$rs) {
        $msg = '<br /><p class="infos">The '.AJAXSEARCH_LOG.' table doesn\'t exist. This table is set up at the first run of AjaxSearch when asLog is set. </p>';
        $msg .= '<p class="infos">By default asLog = 0, so you need to initialize this parameter to create the table.</p>';
        echo $msg;
    }

    $output = <<<EOD
</form>
</body>
</html>
EOD;
    echo $output;
    break;
}
