<?php

/*
 * Title: Default Templates
 * Purpose: 
 * 		Default templates for Reflect
*/

// ---------------------------------------------------
//  Default Templates
// ---------------------------------------------------

// Variable: $defaultTemplates
// Holds the default templates
$defaultTemplates = array('tpl','year','year_inner','month','month_inner','item');

$defaultTemplates['tpl'] = <<<TPL
<h3>Archives</h3>
<div class="reflect_archive_list">
	<ul class="reflect_archive">
	[+wrapper+]
	</ul>
</div>
TPL;

$defaultTemplates['year'] = <<<TPL
	<li class="reflect_year">
		<a href="[+url+]" title="[+year+]" class="reflect_year_link">[+year+]</a>
			[+wrapper+]
	</li>
TPL;

$defaultTemplates['year_inner'] = <<<TPL
		<ul class="reflect_months">
		[+wrapper+]
		</ul>
TPL;

$defaultTemplates['month'] = <<<TPL
			<li class="reflect_month">
				<a href="[+url+]" title="[+month+] [+year+]" class="reflect_month_link">[+month+]</a>
				[+wrapper+]
			</li>
TPL;

$defaultTemplates['month_inner'] = <<<TPL
				<ul class="reflect_items">
				[+wrapper+]
				</ul>
TPL;

$defaultTemplates['item'] = <<<TPL
					<li class="reflect_item">
						<a href="[~[+id+]~]" title="[+pagetitle+]" class="reflect_item_link">[+pagetitle+]</a> (<span class="reflect_date">[+date+]</span>)
					</li>
TPL;

?>