/**
 * Comments_tplForm
 *
 * Comments (Jot) Form-Template
 *
 * @category	chunk
 * @internal    @modx_category Demo Content
 * @internal    @installset base, sample
 */
<a name="jf[+jot.link.id+]"></a>
<h2>[+form.edit:is=`1`:then=`Edit comment`:else=`Write a comment`+]</h2>
<div class="jot-list">
<ul>
	<li>Required fields are marked with <b>*</b>.</li>
</ul>
</div>
[+form.error:isnt=`0`:then=`
<div class="jot-err">
[+form.error:select=`
&-3=You are trying to re-submit the same post. You have probably clicked the submit button more than once.
&-2=Your comment has been rejected.
&-1=Your comment has been saved, it will first be reviewed before it is published.
&1=You are trying to re-submit the same post. You have probably clicked the submit button more than once.
&2=The security code you entered was incorrect.
&3=You can only post once each [+jot.postdelay+] seconds.
&4=Your comment has been rejected.
&5=[+form.errormsg:ifempty=`You didn't enter all the required fields`+]
`+]
</div>
`:strip+]
[+form.confirm:isnt=`0`:then=`
<div class="jot-cfm">
[+form.confirm:select=`
&1=Your comment has been published.
&2=Your comment has been saved, it will first be reviewed before it is published.
&3=Comment saved.
`+]
</div>
`:strip+]
<form method="post" action="[+form.action:esc+]#jf[+jot.link.id+]" class="jot-form">
	<fieldset>
	<input name="JotForm" type="hidden" value="[+jot.id+]" />
	<input name="JotNow" type="hidden" value="[+jot.seed+]" />
	<input name="parent" type="hidden" value="[+form.field.parent+]" />
	
	[+form.moderation:is=`1`:then=`
		<div class="jot-row">
			<b>Created on:</b> [+form.field.createdon:date=`%a %B %d, %Y at %H:%M`+]<br />
			<b>Created by:</b> [+form.field.createdby:userinfo=`username`:ifempty=`[+jot.guestname+]`+]<br />
			<b>IP address:</b> [+form.field.secip+]<br />
			<b>Published:</b> [+form.field.published:select=`0=No&1=Yes`+]<br />
			[+form.field.publishedon:gt=`0`:then=`
				<b>Published on:</b> [+form.field.publishedon:date=`%a %B %d, %Y at %H:%M`+]<br />
				<b>Published by:</b> [+form.field.publishedby:userinfo=`username`:ifempty=` - `+]<br />
			`+]
			[+form.field.editedon:gt=`0`:then=`
				<b>Edited on:</b> [+form.field.editedon:date=`%a %B %d, %Y at %H:%M`+]<br />
				<b>Edited by:</b> [+form.field.editedby:userinfo=`username`:ifempty=` -`+]<br />
			`+]
		</div>
	`:strip+]
	
	[+form.guest:is=`1`:then=`
		<div class="form-group">
			<label for="name[+jot.id+]">Name:</label>
			<input tabindex="[+jot.seed:math=`?+1`+]" name="name" class="form-control" type="text" size="40" value="[+form.field.custom.name:esc+]" id="name[+jot.id+]" />
		</div>
		<div class="form-group">
			<label for="email[+jot.id+]">Email:</label>
			<input tabindex="[+jot.seed:math=`?+2`+]" name="email" class="form-control" type="text" size="40" value="[+form.field.custom.email:esc+]" id="email[+jot.id+]"/>
		</div>
	`:strip+]
	<div class="form-group">
		<label for="title[+jot.id+]">Subject:</label>
		<input tabindex="[+jot.seed:math=`?+3`+]" name="title" class="form-control" type="text" size="40" value="[+form.field.title:esc+]" id="title[+jot.id+]"/>
	</div>
	<div class="form-group">
		<label for="content[+jot.id+]">Comment: *</label>
		<textarea tabindex="[+jot.seed:math=`?+4`+]" name="content" class="form-control" rows="8" id="content[+jot.id+]">[+form.field.content:esc+]</textarea>
	</div>
	
[+jot.captcha:is=`1`:then=`
	<div style="width:150px;margin-top: 5px;margin-bottom: 5px;">
		<a href="[+jot.link.current:esc+]">
			<img src="[(modx_manager_url)]includes/veriword.php?rand=[+jot.seed+]" width="148" height="60" alt="If you have trouble reading the code, click on the code itself to generate a new random code." style="border: 1px solid #003399" />
		</a>
	</div>
	<div class="form-group">
		<label for="vericode[+jot.id+]">Help prevent spam - enter security code above:</label>
		<input type="text" name="vericode" style="width:150px;" size="20" id="vericode[+jot.id+]" />
	</div>
`:strip+]

	<input tabindex="[+jot.seed:math=`?+5`+]" name="submit" class="btn btn-primary" type="submit" value="[+form.edit:is=`1`:then=`Save Comment`:else=`Post Comment`+]" />
	[+form.edit:is=`1`:then=`
		<input tabindex="[+jot.seed:math=`?+5`+]" name="submit" class="btn btn-default" type="submit" value="Cancel" onclick="history.go(-1);return false;" />
	`+] 
	</fieldset>
</form>