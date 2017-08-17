<br />
<br />
<h4><i class="fa fa-user" aria-hidden="true"></i> [+lang.DM_adjust_authors_header+]</h4>
<p>[+lang.DM_adjust_authors_desc+]</p>

<form name="authors" method="post" action="">
    <label for="author_createdby">[+lang.DM_adjust_authors_createdby+]</label>
    <select name="author_createdby" size="1">
        <option value="0">[+lang.DM_adjust_authors_noselection+]</option>
        [+changeauthors.options+]
    </select>
    <br /><br />
    <label for="author_editedby">[+lang.DM_adjust_authors_editedby+]</label>
    <select name="author_editedby" size="1">
        <option value="0">[+lang.DM_adjust_authors_noselection+]</option>
        [+changeauthors.options+]
    </select>
</form>