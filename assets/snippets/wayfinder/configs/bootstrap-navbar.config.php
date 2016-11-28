<?php
$startId  = 0;
$level    = 1;
$outerClass = 'nav navbar-nav navbar-right';
$outerTpl='@CODE:
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#gnavi">
  <span class="sr-only">Menu</span>
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="[(site_url)]">[(site_name)]</a>
</div>
<div id="gnavi" class="collapse navbar-collapse">
  <ul [+wf.classes+]>[+wf.wrapper+]</ul>
</div>
';