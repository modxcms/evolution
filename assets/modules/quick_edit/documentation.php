<?php

// Automatically get the module ID (thanks Travis)
$id = (!empty($_REQUEST["id"])) ? (int)$_REQUEST["id"] : "[QuickEditModuleId]";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<meta name="description" content="Documentation for the QuickEdit module" />

<title>QuickEdit Documentation</title>

<style type="text/css">

*{
font-family:arial, helvetica, sans-serif;
font-size:10pt;
border-width:1px;
border-color:#003399;
}

body{
background-color:#a4d1f9;
}

h1, h2, h3{
margin-bottom:0;
color:#003399;
}

h1{
margin-top:0;
margin-bottom:20px;
font-size:20pt;
text-align:center;
border-bottom-style:solid;
border-color:#e78900;
}

h2{
font-size:12pt;
}

hr{
color:#ffffff;
border-style:none none solid none;
border-color:#e78900;
}

p{
margin-top:0;
}

ul{
margin:0 0 0 10px;
padding:0;
list-style-type:square;
}

code{
font-family:monospace;
color:#666666;
}

a{
color:#e78900;
text-decoration:none;
}

a:hover{
border-bottom-style:dashed;
}

#qe_logo{
float:right;
margin:0 57px 20px 57px;
}

#qe_toc{
width:200px;
margin:0 0 20px 20px;
padding:20px;
float:right;
clear:right;
border-style:solid;
background-color:#eaf9ff;
}

#qe_toc h1{
margin-top:0;
font-size:12pt;
text-align:center;
}

div.qe_box{
margin:30px;
padding:30px;
border-style:solid;
background-color:#ffffff;
}

div.qe_level_2{
margin-left:20px;
}

.qe_salutation{
margin-left:30px;
}

.qe_signature{
font-family:cursive;
font-size:14pt;
}
</style>

</head>

<body>

<div class="qe_box">

<h1>QuickEdit Documentation</h1>

<img id="qe_logo" src="../<?php echo($mod_path); ?>/images/logo.png" alt="QuickEdit" />

<div id="qe_toc">

<h1>Table of Contents</h1>

<ul>
 <li><a href="#who">Who?</a></li>
 <li><a href="#what">What?</a></li>
 <li><a href="#why">Why?</a></li>
 <li><a href="#how">How?</a>
  <ul>
   <li><a href="#how-tag">Tag method</a></li>
   <li><a href="#how-html">HTML method</a></li>
   <li><a href="#how-links">Custom links method</a></li>
  </ul>
 </li>
 <li><a href="#faq">FAQ</a>
  <ul>
   <li><a href="#custom_styles">Can I use my own styles for the links?</a></li>
   <li><a href="#no_add">Why can't add/publish/delete/move a page?</a></li>
   <li><a href="#highlight">The highlight feature is highlighting more than it should.</a></li>
   <li><a href="#link_cache">Will the edit links get cached?</a></li>
   <li><a href="#not_visible">How do I edit content that's not visible on the page</a></li>
   <li><a href="#link_cache">Can I hide the links from certain people.</a></li>
   <li><a href="#cant_see">I don't see the links what am I doing wrong?</a></li>
  </ul>
 </li>
</ul>

</div>

<div class="qe_level_1">
<a name="who"></a>
<h2>Who's responsible for the QuickEdit module?</h2>
<p>QuickEdit was originally written by <a href="mailto:adam@obledisgn.com">Adam Crownoble</a>. It is now a core module and is supported by the MODx community. If you have questions or comments about the module you can contact <a href="mailto:adam@obledesign.com">Adam</a> directly or use the <a href="http://www.modxcms.com/forums/" target="_blank">MODx forums</a>.</p>
</div>

<div class="qe_level_1">
<a name="what"></a>
<h2>What does QuickEdit do?</h2>
<p>QuickEdit allows you to edit the content of your site right from the page. It's so simple, it makes editing a webpage about as complex as sending an email.</p>
</div>

<div class="qe_level_1">
<a name="why"></a>
<h2>Why not just use the manager?</h2>
<p>The manager is a very powerful tool but sometimes it's overkill for simple edits. Also, the manager can be very intimidating to the not-so-web-savvy.</p>
</div>

<div class="qe_level_1">
<a name="how"></a>
<h2>How do I use QuickEdit?</h2>
<p>There are currently three different methods for using QuickEdit links. Some are designed for ease, others are designed for flexibility. You can use any combination of the three methods in a page.</p>
</div>

<div class="qe_level_2">
<a name="how-tag"></a>
<h3>Tag Method</h3>
<p>This is the simplest method off all is therefore the recommended method.</p>
<p>To use the tag method for just replace the typical <code>[**]</code> style tags with <code>[*#*]</code> style tags (Example: <code>[*#content*]</code>). That's all there is to it. The module will take care of the rest.</p>
</div>

<div class="qe_level_2">
<a name="how-html"></a>
<h3>HTML Method</h3>
<p>The HTML method allows you to place the edit links wherever you like while still allowing QuickEdit to show or hide the links based on user permissions.</p>

<p>To use the HTML method, simply insert custom &lt;quickedit&gt; tags wherever you want the links to be. The tags must be in the format <code>&lt;quickedit:content /&gt;</code>. Replace <code>content</code> with the name or ID of the template variable you want to edit.</p>
</div>

<div class="qe_level_2">
<a name="how-links"></a>
<h3>Custom Links Method</h3>
<p>This method is for advanced users only. We do not suggest that you use the custom links method but if you have very specific needs it may be the best choice for your situation.</p>

<p>To use the custom links method just insert normal links anywhere in your template. Two example links are provided for you below. If you use this method you must realize that the links are like any other link and will be visible to any user or visitor.</p>

<p>
Javascript (recommended): <code>window.open('index.php?a=112&amp;id=<?php echo $id; ?>&amp;doc=[*id*]&amp;var=content', 'QuickEditor', 'width=525, height=300, toolbar=0, menubar=0, status=0, alwaysRaised=1, dependent=1');</code><br />
Link Tag: <code>&lt; href="index.php?a=112&amp;id=<?php echo $id; ?>&amp;doc=[*id*]&amp;var=content" target="_blank"&gt;Edit&lt;/a&gt;</code>
</p>

<?php
if (! is_integer($id)) {
	echo "<p>Make sure to replace <code>[QuickEditModuleId]</code> with the id of your QuickEdit module.</p>";
};
?>
</div>

<div class="qe_level_1">
<a name="faq"></a>
<h2>Frequently Asked Questions</h2>
</div>

<div class="qe_level_2">
<a name="custom_styles"></a>
<h3>Can I use my own styles for the links?</h3>
<p>Sure. QuickEdit uses the styles in the output.css file in the quick_edit module folder. To apply your own styles just replace the styles in the output.css file with your own styles. If you'd rather store the styles somewhere else you can always just delete the content of output.css and use your own style sheets. QuickEdit uses two style classes to do it's styling: <code>QuickEditLink</code> and <code>QuickEditParent</code>. <code>QuickEditLink</code> defines the actual edit link while <code>QuickEditParent</code> defines the styles that are applied to the editable area when you hover over the links.</p>
</div>

<div class="qe_level_2">
<a name="no_add"></a>
<h3>Why can't I add/publish/delete/move a page?</h3>
<p>QuickEdit does not support anything other than simple edits at this point. We are planning on incorporating these and other features in future releases.</p>
</div>

<div class="qe_level_2">
<a name="highlight"></a>
<h3>The highlight feature is highlighting more than it should.</h3>
<p>Try putting the template variable in a span or div. Example: <code>&lt;div&gt;[*#longtitle*]&lt;/div&gt;</code></p>
</div>

<div class="qe_level_2">
<a name="not_visible"></a>
<h3>How do I edit content that's not visible on the page</h3>
<p>You'll have to use the <a href="#how-html">HTML Method</a> or <a href="#how-links">Custom Link Method</a> to create these sort of links.</p>
</div>

<div class="qe_level_2">
<a name="hide_links"></a>
<h3>Can I hide the links from certain users.</h3>
<p>Of course, this is done dynamically just set the permissions on the Template Variables like normal.</p>
</div>

<div class="qe_level_2">
<a name="link_cache"></a>
<h3>Will the edit links get cached?</h3>
<p>No they won't. A visitor to the site who hasn't logged in will see a version of the page that should be no different than the way it would look without QuickEdit.</p>
</div>

<div class="qe_level_2">
<a name="cant_see"></a>
<h3>I don't see the links what am I doing wrong?</h3>
<p>Did you follow the <a href="#how-tag">Tag method</a>? Give this a try, it is the simplest way to get started.</p>
<p>If the tag method didn't do it, click the <strong>Refresh site</strong> link from the manager to clear your cache. Cached pages can be an issue if they were cached before installing or enabling the QuickEdit plugin, from that point on the cache shouldn't be an issue.</p>
<p>If you still don't see them then try to login as the admin user. Do you see them? Then your problem is with permissions. QuickEdit permissions can be tricky because there are a lot of things to check. First of all make sure that the user has Edit Documents, Save Documents, and Run module rights. Then check that he has rights to the page. Then check the users rights to the QuickEdit module and to any Template Variables on the page.</p>
<p>Still no luck? go to the <a href="http://www.modxcms.com/forums" target="_blank">MODx community forums</a> and do a quick search to see if your problem has already been addressed. If you don't find anything then post a new topic in the <a href="http://modxcms.com/forums/index.php/board,10.0.html" target="_blank">General Support</a> discussion board.</p>
</div>

<hr />

<p>Thanks for your interest in QuickEdit. I look forward developing it further in the future. If you have any comments, questions or feature requests , I'd love to hear them.</p>

<p class="qe_salutation"><span class="qe_signature">Adam Crownoble</span><br />
<strong>MODx Code Team Member</strong></p>

</div>

</body>
</html>
