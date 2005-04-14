/*
 *	GetStats 
 *	Fetches the visitor statistics totals from the database
 *
 */
 

$tmpArray = $modx->getSiteStats();

$output = "
<table width='100%' cellspacing='1' bgcolor='#003399'>
    <tr class='fancyRow'>
        <td width='25%'>&nbsp;</td>
        <td width='25%'><b>Page Impressions</b></td>
        <td width='25%'><b>Visits</b></td>
        <td width='25%'><b>Visitors</b></td>
    </tr>
    <tr>
        <td class='fancyRow2'><b>Today</b></td>
        <td bgcolor='white'>".number_format($tmpArray['piDay'])."</td>
        <td bgcolor='white'>".number_format($tmpArray['viDay'])."</td>
        <td bgcolor='white'>".number_format($tmpArray['visDay'])."</td>
    </tr>
    <tr>
        <td class='fancyRow2'><b>This Month</b></td>
        <td bgcolor='white'>".number_format($tmpArray['piMonth'])."</td>
        <td bgcolor='white'>".number_format($tmpArray['viMonth'])."</td>
        <td bgcolor='white'>".number_format($tmpArray['visMonth'])."</td>
    </tr>
    <tr>
        <td class='fancyRow2'><b>All Time</b></td>
        <td bgcolor='white'>".number_format($tmpArray['piAll'])."</td>
        <td bgcolor='white'>".number_format($tmpArray['viAll'])."</td>
        <td bgcolor='white'>".number_format($tmpArray['visAll'])."</td>
    </tr>
</table>
";

return $output;