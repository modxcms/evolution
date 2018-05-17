<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
?>
<p></p>
<ul>
    <li>update FormLister to 1.7.8 (dmi3yy)</li>
    <li>update DocLister to 1.3.11 (dmi3yy)</li>
    <li>@FILE for chunks - {{@FILE:assets/chunks/header.html}}</li>
    <li>remove mootols from manage category</li>
    <li>added disable/enable snippets and chuncks #126</li>
    <li>Depricated keywords and metatags (not used by default from 1.0.8 version) use TV for this.</li>
    <li>new settings for group TVs (http://take.ms/OmFYb)</li>
    <li>add header("X-XSS-Protection: 0"); for correct work filamanager.</li>
    <li>Add DLSitemap and DLMenu snippets</li>
    <li>Remove DLBuildMenu</li>
    <li>Refactor manager search and fix permision for this</li>
    <li>remove etomite "compatibility"</li>
    <li>New - $modx->config['enable_cache']</li>
    <li>New - [*value:makeUrl*] modifier</li>
</ul>
