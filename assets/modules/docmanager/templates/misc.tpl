<br /><h3>[+lang.DM_adjust_dates_header+]</h3><br />
<p>[+lang.DM_adjust_dates_desc+]</p><br />
<form style="margin-left:50px;" id="dates" name="dates" method="post" action="">
    <label for="date_pubdate" id="date_pubdate_label">[+lang.DM_date_pubdate+]</label><input type="hidden" id="date_pubdate" name="date_pubdate" />
    <span id="date_pubdate_show"> (not set)</span>
    <a href="#" onclick="caldate1.popup();">[+lang.DM_view_calendar+]</a>&nbsp;&nbsp;
    <a href="#" onclick="document.forms['dates'].elements['date_pubdate'].value='';document.getElementById('date_pubdate_show').innerHTML='(not set)'; return true;">[+lang.DM_clear_date+]</a>
    <br /><br />

    <label for="date_unpubdate" id="date_unpubdate_label">[+lang.DM_date_unpubdate+]</label><input type="hidden" id="date_unpubdate" name="date_unpubdate" />
    <span id="date_unpubdate_show"> (not set)</span>
    <a href="#" onclick="caldate2.popup();">[+lang.DM_view_calendar+]</a>&nbsp;&nbsp;
    <a href="#" onclick="document.forms['dates'].elements['date_unpubdate'].value='';document.getElementById('date_unpubdate_show').innerHTML='(not set)'; return true;">[+lang.DM_clear_date+]</a>
    <br /><br />

    <label for="date" id="date_createdon_label">[+lang.DM_date_createdon+]</label><input type="hidden" id="date_createdon" name="date_createdon" />
    <span id="date_createdon_show"> (not set)</span>
    <a href="#" onclick="caldate3.popup();">[+lang.DM_view_calendar+]</a>&nbsp;&nbsp;
    <a href="#" onclick="document.forms['dates'].elements['date_createdon'].value='';document.getElementById('date_createdon_show').innerHTML='(not set)'; return true;">[+lang.DM_clear_date+]</a>
    <br /><br />

    <label for="date_editedon" id="date_editedon_label">[+lang.DM_date_editedon+]</label><input type="hidden" id="date_editedon" name="date_editedon" />
    <span id="date_editedon_show"> (not set)</span>
    <a href="#" onclick="caldate4.popup();">[+lang.DM_view_calendar+]</a>&nbsp;&nbsp;
    <a href="#" onclick="document.forms['dates'].elements['date_editedon'].value='';document.getElementById('date_editedon_show').innerHTML='(not set)'; return true;">[+lang.DM_clear_date+]</a>
</form>


<br />
<h3>[+lang.DM_other_header+]</h3>
<br />
<p>[+lang.DM_misc_desc+]</p><br />
<form style="margin-left:50px;" name="other" method="post" action="">
    <input type="hidden" id="option1" name="option1" value="[+lang.DM_other_publish_radio1+]" />
    <input type="hidden" id="option2" name="option2" value="[+lang.DM_other_publish_radio2+]" />
    <input type="hidden" id="option3" name="option3" value="[+lang.DM_other_show_radio1+]" />
    <input type="hidden" id="option4" name="option4" value="[+lang.DM_other_show_radio2+]" />
    <input type="hidden" id="option5" name="option5" value="[+lang.DM_other_search_radio1+]" />
    <input type="hidden" id="option6" name="option6" value="[+lang.DM_other_search_radio2+]" />
    <input type="hidden" id="option7" name="option7" value="[+lang.DM_other_cache_radio1+]" />
    <input type="hidden" id="option8" name="option8" value="[+lang.DM_other_cache_radio2+]" />
    <input type="hidden" id="option9" name="option9" value="[+lang.DM_other_richtext_radio1+]" />
    <input type="hidden" id="option10" name="option10" value="[+lang.DM_other_richtext_radio2+]" />
    <input type="hidden" id="option11" name="option11" value="[+lang.DM_other_delete_radio1+]" />
    <input type="hidden" id="option12" name="option12" value="[+lang.DM_other_delete_radio2+]" />
    <label for="misc" id="misc_label">[+lang.DM_misc_label+]</label> 
    <select id="misc" name="misc" onchange="changeOtherLabels();">
		<option value="1">[+lang.DM_other_dropdown_publish+]</option>
		<option value="2">[+lang.DM_other_dropdown_show+]</option>
		<option value="3">[+lang.DM_other_dropdown_search+]</option>
		<option value="4">[+lang.DM_other_dropdown_cache+]</option>
		<option value="5">[+lang.DM_other_dropdown_richtext+]</option>
		<option value="6">[+lang.DM_other_dropdown_delete+]</option>
		<option value="0">&nbsp;-</option>
  </select>
  <br /><br />
  <input type="radio" name="choice" value = "1" />&nbsp;<label for="choice" id="choice_label_1">[+lang.DM_other_publish_radio1+]</label>
  <input type="radio" name="choice" value = "0" />&nbsp;<label for="choice" id="choice_label_2">[+lang.DM_other_publish_radio2+]</label>
</form>

<script type="text/javascript">
    var caldate1 = new calendar1($('date_pubdate'), $("date_pubdate_show"));
    caldate1.path="[+calendar.path+]";
    caldate1.year_scroll = true;
    caldate1.time_comp = true;
    
    var caldate2 = new calendar1($('date_unpubdate'), $("date_unpubdate_show"));
    caldate2.path="[+calendar.path+]";
    caldate2.year_scroll = true;
    caldate2.time_comp = true;
    
    var caldate3 = new calendar1($('date_createdon'), $("date_createdon_show"));
    caldate3.path="[+calendar.path+]";
    caldate3.year_scroll = true;
    caldate3.time_comp = true;
    
    var caldate4 = new calendar1($('date_editedon'), $("date_editedon_show"));
    caldate4.path="[+calendar.path+]";
    caldate4.year_scroll = true;
    caldate4.time_comp = true;
</script>