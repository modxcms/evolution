<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
?>
<p></p>
<ul>
    <li>ElementsInTree restyled for new theme, version 1.5.8 (Piotr Matysiak)</li>
    <li>Fix - <@IF><@ELSEIF><@ELSE><@ENDIF> (yamamoto)</li>
    <li>Fix Bugs in Ditto with date and placeholders from version 1.3.4 (yamamoto, Dmi3yy)</li>
    <li>Fix tinymce params underfined bug on frontend (dmi3yy)</li>
    <li>Fix 65 Plugin parameters are lost after update to the new version (dmi3yy)</li>
    <li>no need update.php in extras module (dmi3yy)</li>
    <li>Fix variable documentDirty (64j)</li>
    <li>Make sortable list more condensed (Piotr Matysiak)</li>
    <li>#187 Ditto is missing placeholders when built-in filters are enabled (yamamoto)</li>
    <li>Fix saved roles users #130 (64j)</li>
    <li>Fix #192 evo.checkConnectionToServer function (64j)</li>
</ul>
