<li><a href="#" class="switchform-btn" data-target="switchForm_[+cssId+]">[%btn_view_options%]</a></li>
<form id="switchForm_[+cssId+]" class="switchForm" data-target="[+cssId+]" style="display:none">
    <h3 class="optionsTitle"><i class="fa fa-check-square" aria-hidden="true"></i> [%viewopts_title%]</h3>
    <div class="optionsFull optionsRadios">
    <label><input type="radio" name="view" value="list"> [%viewopts_radio_list%]</label>
    <label><input type="radio" name="view" value="inline"> [%viewopts_radio_inline%]</label>
    <label><input type="radio" name="view" value="flex"> [%viewopts_radio_flex%]</label>
    <label><input type="number" placeholder="Columns" name="columns" class="columns" value="3"></label>
    </div>
    <div class="optionsClear"></div>
    <div class="optionsLeft optionsChecks">    
    <label><input type="checkbox" name="cb_buttons" value="buttons"><span></span> [%viewopts_cb_buttons%]</label>
    <label><input type="checkbox" name="cb_description" value="description"> [%viewopts_cb_descriptions%]</label>
    <label><input type="checkbox" name="cb_icons" value="icons"> [%viewopts_cb_icons%]</label> 
    </div>  
    <div class="optionsLeft optionsFontsSize">
    <label>[%viewopts_fontsize%]  <input type="number" placeholder="" name="fontsize" class="fontsize" value="10"></label>  
    </div>
    <div class="optionsClear"></div>
    <div class="optionsLeft optionsAllTabs">
    <label><input type="checkbox" class="cb_all" name="cb_all" value="all"> [%viewopts_cb_alltabs%]</label>
    </div>   
    <div class="optionsLeft optionsReset">
    <a href="#" class="btn_reset"> [%reset%]</a>
    </div>
</form>