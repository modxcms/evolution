<br /><h3><i class="fa fa-user" aria-hidden="true"></i> [+lang.DM_adjust_authors_header+]</h3><br />
<p>[+lang.DM_adjust_authors_desc+]</p><br />

<form name="authors" method="post" action="">
    <label for="author_createdby">[+lang.DM_adjust_authors_createdby+]</label>
    <select name="author_createdby" style="width:50%">
        <option value="0">[+lang.DM_adjust_authors_noselection+]</option>
        [+changeauthors.options+]
    </select>
    <br /><br />

    <label for="author_editedby">[+lang.DM_adjust_authors_editedby+]</label>
        <select name="author_editedby" style="width:50%">
            <option value="0">[+lang.DM_adjust_authors_noselection+]</option>
            [+changeauthors.options+]
        </select>
</form>