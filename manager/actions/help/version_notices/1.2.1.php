<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
?>
<p></p>
<h1>Server-Configuration</h1>
<ul>
    <li><strong>Turn off PHP directive "register_globals"</strong>
        <p>In case your server has "register_globals" set to ON for whatever reason, protect.inc.php will stop further script execution.</p>
    </li>
</ul>
<h1>New Plugin-Event</h1>
<ul>
    <li><strong>OnBeforeParseParams</strong>
        <p>Will get invoked before parsing a snippet-params string like <code>&amp;param1=`value`</code> to allow replacing custom-placeholders like EvoBabel´s <code>[%ph%]</code> before parsing a params-string. Example:<br/><br/><code>[[Wayfinder? &amp;startId=`[%lang%]` ..]]</code></p>
    </li>
</ul>
<h1>Debug-Infos</h1>
<ul>
    <li><strong>Improved Debug-Infos for <code>$modx->dumpSnippets</code> and <code>$modx->dumpPlugins</code></strong>
        <p>To display infos for plugins/snippets create a plugin with the following code and activate event "OnWebPageInit":</p>
        <pre>$e = & $modx->Event;
if ( $e->name == "OnWebPageInit" ) {
	$modx->dumpSnippets=true;
	$modx->dumpPlugins=true;
}</pre>
    </li>
</ul>
