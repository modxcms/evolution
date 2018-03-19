<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
?>
<p></p>
<h1>Resource-Tree</h1>
<ul>
	<li><strong>Moved "Sort menuindex" from DocManager to Resource-tree (<a href="https://github.com/modxcms/evolution/issues/618" target="_blank">#618</a>, <a href="https://github.com/modxcms/evolution/issues/636" target="_blank">#636</a>)</strong>
		<p><u>Sort Resources in Root:</u> Click Button "Sort menu index" on top of resource-tree<br/>
			<u>Sort Resources of Parent Resource:</u> Right mouse-click on parent, then choose "Sort menu index"
		</p>
	</li>
	<li><strong>New "Manage Elements" Buttons (<a href="https://github.com/modxcms/evolution/issues/669" target="_blank">#669</a>)</strong>
		<p>You can quick-access elements, files and images now directly from ressource-tree. Use Shift-Mouseclick to open multiple windows/elements.</p>
	</li>
	<li><strong>Remember last sort-options (<a href="https://github.com/modxcms/evolution/issues/618" target="_blank">#618</a>, <a href="https://github.com/modxcms/evolution/issues/636" target="_blank">#636</a>)</strong>
		<p>The ressource-tree stores now the last set sort-options per user to database (Sort by, Asc/Desc, Display-Name). At manager log-in, last settings of each user get restored.</p>
	</li>
</ul>

<h1>MODX Tags</h1>
<ul>
	<li><strong>New Modifiers/Filters in Core (PHx)</strong>
		<p>Can be disabled in MODX-configuration. More infos at <a href="https://github.com/modxcms/evolution/issues/623" target="_blank">#623</a></p>
	</li>
	<li><strong>Snippet - Shortcut param = true</strong>
		<p>[[snippetName?param1&amp;param2]] will automatically be handled as [[snippetName?param1=`1`&amp;param2=`1`]] while param=`` will still be handled as empty value.</p>
	</li>
	<li><strong>New Conditional Tags / Modifiers</strong>
		<p>Can be enabled/disabled via Configuration -> "Enable Filters". More examples at <a href="https://github.com/modxcms/evolution/issues/622" target="_blank">#622</a> and <a href="https://github.com/modxcms/evolution/issues/623" target="_blank">#623</a>. Example:</p>
		<pre>[*longtitle:ifempty=[*pagetitle*]*]</pre>
		<pre>&lt;!--@IF:[*id:is('[(site_start)]')*]>
Top page
<@ELSE>
Sub page
<@ENDIF--&gt;</pre>
	</li>

	<li><strong>New Comment Tag</strong>
		<p>Comment-Tags will be completely removed from output. More infos at <a href="https://github.com/modxcms/evolution/issues/680" target="_blank">#680</a>. Example:</p>
		<pre>&lt;!--@- This is a comment -@--&gt;</pre>
		<pre>&lt;!--@- Or HTML-Code / Snippets etc you want to disable temporarily -@--&gt;</pre>
	</li>

	<li><strong>New Chunk Parameters</strong>
		<p>It is possible to pass properties/values to a chunk. More infos at <a href="https://github.com/modxcms/evolution/issues/625" target="_blank">#625</a>. Example:</p>
		<strong>Chunk:</strong>
		<pre>
&lt;h1&gt;[+title+]&lt;/h1&gt;
&lt;p&gt;[+body+]&lt;/p&gt;</pre>
		<strong>Call:</strong>
		<pre>{{chunkName? &title='First post' &body='Hello World!'}}</pre>
	</li>

	<li><strong>File-binded Templates via @INCLUDE</strong>
		<p>Templates can be included via @INCLUDE using external PHP- & HTML-files. More infos at <a href="https://github.com/modxcms/evolution/issues/627" target="_blank">#627</a>. Example:</p>
		<p>MODX-Template:</p>
		<pre>@INCLUDE:assets/templates/mydesign/template.inc.php</pre>
		<p>template.inc.php :</p>
		<pre>switch($modx->documentIdentifier) {
    case $modx->config['site_start']:
        return file_get_contents('assets/templates/mydesign/top.html');
    default:
        return file_get_contents('assets/templates/mydesign/page.html');
}</pre>
	</li>
</ul>

<h1>New Manager Roles</h1>
<ul>
	<li><strong>change_resourcetype</strong>
		<p>A user with this permission can change resource-type (webpage/weblink). More infos at <a href="https://github.com/modxcms/evolution/issues/531" target="_blank">#531</a></p>
	</li>
	<li><strong>assets_images, assets_files</strong>
		<p>Controls the display of 2 new buttons in resource-tree and grants/blocks access to KCFinder. More infos at <a href="https://github.com/modxcms/evolution/issues/681" target="_blank">#681</a></p>
	</li>
</ul>

<h1>Template-Variables</h1>
<ul>
	<li><strong>@BINDINGS providing TV-values</strong>
		<p>[*tv_name*] will be replaced by its value taken from actual resource. Beware of SQL-Errors in case no or wrong value is given (set a reasonable default-value to avoid errors). More infos at <a href="https://github.com/modxcms/evolution/issues/699" target="_blank">#699</a>. Example:</p>
		<pre>@SELECT name,value FROM xxx WHERE yyy = [*tv_name*]</pre>
	</li>
</ul>

<h1>Important Details for Developers</h1>
<ul>
	<li>jQuery updated to v3.1 and loaded into manager by default.<br/><u>Known issues:</u> MultiTV 2.0.8 has problems with row-reordering and requires an update.</li>
</ul>
