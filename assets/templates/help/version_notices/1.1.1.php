<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
?>
<p></p>
<h3 style="text-decoration: underline;">Resource-Tree</h3>
<ul>
	<li><strong>Moved "Sort menuindex" from DocManager to Resource-tree (#618, #636)</strong>
		<p><u>Sort Resources in Root:</u> Click Button "Sort menu index" on top of resource-tree<br/>
			<u>Sort Resources of Parent Resource:</u> Right mouse-click on parent, then choose "Sort menu index"
		</p>
	</li>
	<li><strong>New "Manage Elements" Buttons (#669)</strong>
		<p>You can quick-access elements, files and images now directly from ressource-tree. Use Shift-Mouseclick to open multiple windows/elements.</p>
	</li>
	<li><strong>Remember last sort-options (#618, #636)</strong>
		<p>The ressource-tree stores now the last set sort-options per user to database (Sort by, Asc/Desc, Display-Name). At manager log-in, last settings per user gets restored.</p>
	</li>
</ul>

<h3 style="text-decoration: underline;">MODX Tags</h3>
<ul>
	<li><strong>New Modifiers/Filters in Core (PHx)</strong>
		<p>Can be disabled in MODX-configuration. More infos at <a href="https://github.com/modxcms/evolution/issues/623" target="_blank">Github #623</a></p>
	</li>
	<li><strong>Snippet - Shortcut param = true</strong>
		<p>[[snippetName?param1&amp;param2]] will automatically be handled as [[snippetName?param1=`1`&amp;param2=`1`]] while param=`` will still be handled as empty value.</p>
	</li>
	<li><strong>New Conditional Tags</strong>
		<p>More infos at <a href="https://github.com/modxcms/evolution/issues/622" target="_blank">Github #622</a>. Example:</p>
		<pre>&lt;!--@IF:[*id:is('[(site_start)]')*]>
Top page
<@ELSE>
Sub page
<@ENDIF--&gt;</pre>
	</li>
	<li><strong>New Comment Tag</strong>
		<p>Will be completely removed from output. More infos at <a href="https://github.com/modxcms/evolution/issues/680" target="_blank">Github #680</a>. Example:</p>
		<pre>&lt;!--@- This is a comment -@--&gt;</pre>
	</li>
</ul>
