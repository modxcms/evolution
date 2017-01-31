<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

function renderViewSwitchButtons($cssId) {
	global $_lang;
	return '
			<li><a href="#" class="switchform-btn" data-target="switchForm_'.$cssId.'">'.$_lang['view_options'].'</a></li>
			<form id="switchForm_'.$cssId.'" class="switchForm" data-target="'.$cssId.'" style="display:none">
				<label><input type="checkbox" name="cb_buttons" value="buttons"> Buttons</label>
				<label><input type="checkbox" name="cb_description" value="description"> Description</label>
				<label><input type="checkbox" name="cb_icons" value="icons"> Icons</label>
				<br/>
				<label><input type="radio" name="view" value="list"> List</label>
				<label><input type="radio" name="view" value="inline"> Inline</label>
				<label><input type="radio" name="view" value="flex"> Flex</label>
				<label><input type="number" placeholder="Columns" name="columns" class="columns" value="3"></label>
				<br/>
				<label>Font-Size <input type="number" placeholder="" name="fontsize" class="fontsize" value="10"></label>
				<hr/>
				<label><input type="checkbox" class="cb_all" name="cb_all" value="all"> All Tabs</label>
				<a href="#" class="btn_reset"> Reset</a>
			</form>';
}