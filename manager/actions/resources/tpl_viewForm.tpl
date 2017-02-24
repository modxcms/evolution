<li><a href="#" class="switchform-btn" data-target="switchForm_[+cssId+]">[%btn_view_options%]</a></li>
<div class="clearfix"></div>
<form id="switchForm_[+cssId+]" class="switchForm" data-target="[+cssId+]" style="display:none">
    <h3>[%viewopts_title%]</h3>
    <label><input type="checkbox" name="cb_buttons" value="buttons"> [%viewopts_cb_buttons%]</label>
    <label><input type="checkbox" name="cb_description" value="description"> [%viewopts_cb_descriptions%]</label>
    <label><input type="checkbox" name="cb_icons" value="icons"> [%viewopts_cb_icons%]</label>
    <br/>
    <label><input type="radio" name="view" value="list"> [%viewopts_radio_list%]</label>
    <label><input type="radio" name="view" value="inline"> [%viewopts_radio_inline%]</label>
    <label><input type="radio" name="view" value="flex"> [%viewopts_radio_flex%]</label>
    <label><input type="number" placeholder="[%%]" name="columns" class="columns" value="3"></label>
    <br/>
    <label>[%viewopts_fontsize%] <input type="number" placeholder="" name="fontsize" class="fontsize" value="10"></label>
    <hr/>
    <label><input type="checkbox" class="cb_all" name="cb_all" value="all"> [%viewopts_cb_alltabs%]</label>
    <a href="#" class="btn_reset"> [%reset%]</a>
</form>