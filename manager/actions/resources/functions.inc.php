<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

function renderViewSwitchButtons($cssId) {
	return '
			<form class="switchForm" data-target="'.$cssId.'">
				<label><input type="checkbox" name="cb_buttons" value="buttons"> Buttons</label>
				<label><input type="checkbox" name="cb_description" value="description"> Description</label>
				<label><input type="checkbox" name="cb_icons" value="icons"> Icons</label>
				<label><input type="checkbox" name="cb_small" value="small"> Small Font</label>
				<br/>
				<label><input type="radio" name="view" value="list"> List</label>
				<label><input type="radio" name="view" value="inline"> Inline</label>
				<label><input type="radio" name="view" value="flex"> Flex</label>
				<label><input type="number" placeholder="Columns" name="columns" value="3"></label>
			</form>';
}