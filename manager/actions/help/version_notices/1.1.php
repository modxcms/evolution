<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
?>
<p></p>
<h3 style="text-decoration: underline;">Configuration</h3>
<ul>
    <li><strong>New setting "AliasListing"</strong>Can be set on/off in settings (in "Friendly URL" tab) to extend the 10.000 resources limit by creating a much smaller <i>siteCache.idx.php</i> (30.000&nbsp;resources&nbsp;=&nbsp;~400kb). Tested with 1.000.000 resources.</li>
</ul>
