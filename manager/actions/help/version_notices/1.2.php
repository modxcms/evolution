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
		<p>You can quick-access elements, files and images now directly from ressource-tree. Use Shift-Mouseclick to open multiple windows/elements. Permission is granted using new roles "assets_images" and "assets_files".</p>
	</li>
	<li><strong>Remember last sort-options (<a href="https://github.com/modxcms/evolution/issues/618" target="_blank">#618</a>, <a href="https://github.com/modxcms/evolution/issues/636" target="_blank">#636</a>)</strong>
		<p>The ressource-tree stores now the last set sort-options per user to database (Sort by, Asc/Desc, Display-Name). At manager log-in, last settings of each user get restored.</p>
	</li>
	<li><strong>New plugin "ElementsInTree" v1.2.0 (<a href="https://github.com/pmfx/ElementsInTree/" target="_blank">github.com/pmfx</a>)</strong>
		<p>This plugin has been added to default installation. Use Shift-Mouseclick to collapse/expand all categories. Collapsed states per category will be remembered via browser´s localStorage.</p>
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
	<li><strong>Output value of $_GET, $_POST, $_COOKIE, $_SERVER, $_SESSION</strong>
		<pre>[!$_SERVER['REQUEST_TIME']:dateFormat='Y'!]</pre>
	</li>
	<li><strong>New Conditional Tags &lt;@IF&gt; &lt;@ELSEIF&gt; &lt;@ELSE&gt; &lt;@ENDIF&gt; and Modifiers</strong>
		<p>Can be enabled/disabled via Configuration -> "Enable Filters". More examples at <a href="https://github.com/modxcms/evolution/issues/622" target="_blank">#622</a> and <a href="https://github.com/modxcms/evolution/issues/623" target="_blank">#623</a>. <br />
			Performance is good because it does not parse the block which is judged false.<br />
			Example:</p>
		<pre>[*longtitle:ifempty=[*pagetitle*]*]</pre>
		<pre>&lt;@IF:[*id:is('[(site_start)]')*]>
Top page
&lt;@ELSE&gt;
Sub page
&lt;@ENDIF&gt;</pre>
		<p>In combination with $_GET :</p>
		<pre>&lt;@IF:[!$_GET['value']:preg('/^[0-9]+$/')!]>
Value is numeric.
&lt;@ELSE&gt;
Value is not numeric.
&lt;@ENDIF&gt;</pre>
		<p>UltimateParent</p>
		<pre>[[UltimateParent:is=`8`:then=`8`:else=`11`]]
&lt;@IF:[[UltimateParent:is=8]]>
8
&lt;@ELSE&gt;
11
&lt;@ENDIF&gt;</pre>

		<p>Combination with Cross-references (<a href="https://github.com/modxcms/evolution/commit/956c9ae1028535308bdb6039483a20b2d697bee9" target="_blank">modxcms/evolution@956c9ae</a>)</p>
		<pre>&lt;@IF:[*id@ultimateparent:is=8*]>
8
&lt;@ELSE&gt;
11
&lt;@ENDIF&gt;</pre>
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
		<p>Templates can be included via @INCLUDE using external PHP- & HTML-files. More infos at <a href="https://github.com/modxcms/evolution/issues/627" target="_blank">#627</a>. Examples:</p>
		<p>HTML Template:</p>
		<pre>@INCLUDE:assets/templates/mydesign/template.html</pre>
		<p>PHP Template:</p>
		<pre>@INCLUDE:assets/templates/mydesign/template.inc.php</pre>
		<p>template.inc.php :</p>
		<pre>switch($modx->documentIdentifier) {
    case $modx->config['site_start']:
        return file_get_contents('assets/templates/mydesign/top.html');
    default:
        return file_get_contents('assets/templates/mydesign/page.html');
}</pre>
	</li>
	<li><strong>Snippet-calls improved and supporting Modifiers</strong>
		<pre>[[snippetName]]
[[snippet Name]]
[[snippetName?param=`value`]]
[[snippet Name?param=`value`]]
[[snippetName? &amp;param=`value`]]
[[snippetName ? &amp;param=`value`]]
[[snippetName &amp;param=`value`]]
[[snippetName?
    &amp;param=`value`
]]
[[snippetName
    &amp;param=`value`
]]
[[snippet Name?
    &amp;param=`value`
]]
[[snippetName?param]]

[[snippetName:modifier]]
[[snippetName:modifier?param=`value`]]
[[snippetName:modifier ?
    &amp;param=`value`
]]
[[snippetName:modifier
    &amp;param=`value`
]]
[[snippetName:modifier=`option`
    &amp;param=`value`
]]
[[snippetName:modifier(option)
    &amp;param=`value`
]]
[[snippetName:modifier('option')
    &amp;param=`value`
]]
[[snippetName:modifier("option")
    &amp;param=`value`
]]
[[snippetName:modifier(`option`)
    &amp;param=`value`
]]</pre>
	</li>
    <li><strong>Wayfinder Debug-Mode</strong>
        <p>More infos at <a href="https://github.com/modxcms/evolution/issues/719" target="_blank">#719</a></p>
        <pre>[[Wayfinder?debug]]</pre>
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

<h1>Other Important Details for Developers</h1>
<ul>
	<li><strong>jQuery updated to v3.1 and loaded into manager by default.</strong><u>Known issues:</u> MultiTV 2.0.8 has problems with row-reordering and requires an update. Meanwhile a workaround can be found <a href="https://github.com/Deesen/multiTvLayout/blob/master/assets/tvs/multitv/js/jquery-dataTables.rowReordering-1.1.0.js.BUGFIX" target="_blank">here</a>.</li>
	<li><strong>Language Overrides</strong>Can be implemented by adding files to /manager/includes/lang/override/. Files in this directory will never get altered by future updates.</li>
</ul>
