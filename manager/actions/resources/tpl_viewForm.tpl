<!--<li><a href="javascript:;" class="switchform-btn" data-target="switchForm_[+cssId+]"><i class="fa fa-bars"></i> <span>[%btn_view_options%]</span></a></li>-->
<form id="switchForm_[+cssId+]" class="form-group form-inline switchForm" data-target="[+cssId+]" style="display:none">
	<!--	<h3 class="optionsTitle"><i class="fa fa-check-square"></i> [%viewopts_title%]</h3>-->
	<div class="form-row">
		<label class="form-check"><input type="radio" name="view" value="list"> [%viewopts_radio_list%]</label>
		<label class="form-check"><input type="radio" name="view" value="inline"> [%viewopts_radio_inline%]</label>
		<label class="form-check"><input type="radio" name="view" value="flex"> [%viewopts_radio_flex%]</label>
		<input type="number" placeholder="Columns" name="columns" class="form-control form-control-sm columns" value="3" size="3" />
	</div>
	<div class="form-row">
		<label class="form-check"><input type="checkbox" name="cb_buttons" value="buttons"><span></span> [%viewopts_cb_buttons%]</label>
		<label class="form-check"><input type="checkbox" name="cb_description" value="description"> [%viewopts_cb_descriptions%]</label>
		<label class="form-check"><input type="checkbox" name="cb_icons" value="icons"> [%viewopts_cb_icons%]</label>
	</div>
	<div class="form-row">
		<label>[%viewopts_fontsize%] <input type="number" placeholder="" name="fontsize" class="form-control form-control-sm fontsize" value="10" /></label>
	</div>
	<div class="form-row">
		<label><input type="checkbox" class="cb_all" name="cb_all" value="all" /> [%viewopts_cb_alltabs%]</label>
	</div>
	<div class="optionsLeft optionsReset">
		<a href="javascript:;" class="btn btn-danger btn-sm btn_reset"> [%reset%]</a>
	</div>
</form>