/**
 *	FirstHit 
 *	Fetches the first ever recorded page impression from the database.
 *
 */
 
$sql = "SELECT MIN(timestamp) AS first FROM ".$modx->dbConfig['dbase'].".".$modx->dbConfig['table_prefix']."log_access";
$rs = $modx->dbQuery($sql);
$tmp = $modx->fetchRow($rs);

return strftime("%d-%m-%Y %H:%M:%S", $tmp['first']);