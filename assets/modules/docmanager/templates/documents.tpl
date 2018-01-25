<div id="interaction" class="tab-page">
    <div class="tab-body">
        <div class="tab-section">
            <div class="tab-header">[+lang.DM_range_title+]</div>
            <div class="tab-body">
                <form name="range" id="range" action="" method="post">
                    <input type="hidden" id="newvalue" name="newvalue" value="" />
                    <input type="hidden" id="setoption" name="setoption" value="" />
                    <input type="hidden" id="pubdate" name="pubdate" value="" />
                    <input type="hidden" id="unpubdate" name="unpubdate" value="" />
                    <input type="hidden" id="createdon" name="createdon" value="" />
                    <input type="hidden" id="editedon" name="editedon" value="" />
                    <input type="hidden" id="author_createdby" name="author_createdby" value="" />
                    <input type="hidden" id="author_editedby" name="author_editedby" value="" />
                    <input type="hidden" id="tabaction" name="tabAction" value="" />

                    <div class="input-group">
                        <input id="pids" class="form-control" name="pids" type="text" />
                        <span class="input-group-btn">
                        <input class="btn" type="submit" name="fsubmit" onclick="postForm();return false;" value="[+lang.DM_select_submit+]" />
                    </span>
                    </div>
                </form>
                <br />
                [+lang.DM_select_range_text+]
            </div>
        </div>
    </div>
</div>