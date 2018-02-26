<br />
<h4><i class="fa fa-calendar"></i> [+lang.DM_adjust_dates_header+]</h4>
<p>[+lang.DM_adjust_dates_desc+]</p>
<form id="dates" name="dates" method="post" action="">
    <table>
        <tr>
            <td><label for="date_pubdate" id="date_pubdate_label">[+lang.DM_date_pubdate+]</label></td>
            <td>
                <input type="text" id="date_pubdate" class="DatePicker" name="date_pubdate" />
                <a href="javascript:;" title=" [+lang.DM_clear_date+]" onclick="document.forms['dates'].elements['date_pubdate'].value=''; return true;"><i class="fa fa-calendar-o"></i></a>
            </td>
        </tr>
        <tr>
            <td><label for="date_unpubdate" id="date_unpubdate_label">[+lang.DM_date_unpubdate+]</label></td>
            <td>
                <input type="text" id="date_unpubdate" class="DatePicker" name="date_unpubdate" />
                <a href="javascript:;" title=" [+lang.DM_clear_date+]" onclick="document.forms['dates'].elements['date_unpubdate'].value=''; return true;"><i class="fa fa-calendar-o"></i></a>
            </td>
        </tr>
        <tr>
            <td><label for="date_createdon" id="date_createdon_label">[+lang.DM_date_createdon+]</label></td>
            <td>
                <input type="text" id="date_createdon" class="DatePicker" name="date_createdon" />
                <a href="javascript:;" title=" [+lang.DM_clear_date+]" onclick="document.forms['dates'].elements['date_createdon'].value=''; return true;"><i class="fa fa-calendar-o"></i></a>
            </td>
        </tr>
        <tr>
            <td><label for="date_editedon" id="date_editedon_label">[+lang.DM_date_editedon+]</label></td>
            <td>
                <input type="text" id="date_editedon" class="DatePicker" name="date_editedon" />
                <a href="javascript:;" title=" [+lang.DM_clear_date+]" onclick="document.forms['dates'].elements['date_editedon'].value=''; return true;"><i class="fa fa-calendar-o"></i></a>
            </td>
        </tr>
    </table>
</form>

<br />
<br />
<h4><i class="fa fa-sliders"></i> [+lang.DM_other_header+]</h4>
<p>[+lang.DM_misc_desc+]</p>
<form name="other" method="post" action="">
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
    <select id="misc" name="misc" onchange="changeOtherLabels();" size="1">
        <option value="1">[+lang.DM_other_dropdown_publish+]</option>
        <option value="2">[+lang.DM_other_dropdown_show+]</option>
        <option value="3">[+lang.DM_other_dropdown_search+]</option>
        <option value="4">[+lang.DM_other_dropdown_cache+]</option>
        <option value="5">[+lang.DM_other_dropdown_richtext+]</option>
        <option value="6">[+lang.DM_other_dropdown_delete+]</option>
        <option value="0">&nbsp;-</option>
    </select>
    <br /><br />
    <input type="radio" name="choice" value="1" />&nbsp;<label for="choice" id="choice_label_1">[+lang.DM_other_publish_radio1+]</label>
    <input type="radio" name="choice" value="0" />&nbsp;<label for="choice" id="choice_label_2">[+lang.DM_other_publish_radio2+]</label>
</form>