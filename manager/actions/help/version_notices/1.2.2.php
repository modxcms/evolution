<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
?>
<p></p>
<h1>TinyMCE4</h1>
<ul>
    <li><strong>New checkbox "Use the global setting"</strong>
        <p>There were issues setting "Custom Plugins", "Custom Buttons" and "Block Formats" via MODX-configuration globally for all users. Therefore a new checkbox has been added, which active by default.</p>
    </li>
</ul>

<h1>Developer-Infos</h1>
<ul>
    <li><strong>New <code>$modx->configGlobal</code></strong>
        <p>Params from MODX-configuration which get overwritten by user-specific settings will be stored inside <code>$modx->configGlobal</code> to be available as fallback.</p>
    </li>
</ul>
