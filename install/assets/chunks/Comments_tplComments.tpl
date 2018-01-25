/**
 * Comments_tplComments
 *
 * Comments (Jot) Form-Template
 *
 * @category	chunk
 * @internal    @modx_category Demo Content
 * @internal    @installset base, sample
 */
<a name="jc[+jot.link.id+][+comment.id+]"></a>
<div class="panel panel-[+chunk.rowclass:ne=``:then=`primary`:else=`info`+] [+comment.published:is=`0`:then=`jot-row-up`+]">
	<div class="panel-heading"><span class="jot-subject">[+comment.title:limit:esc+]<span class="pull-right">
		[+phx:userinfo=`lastlogin`:ifempty=`9999999999`:lt=`[+comment.createdon+]`:then=`
		<i class="fa fa-fw fa-comment-o" aria-hidden="true"></i>
		`:else=`
		<i class="fa fa-fw fa-commenting-o" aria-hidden="true"></i>
		`:strip+]
		</span></span>
	</div>
	<div class="panel-body">
		<div class="jot-comment">
			<div class="jot-user">
				[+comment.createdby:isnt=`0`:then=`<b>`+][+comment.createdby:userinfo=`username`:ifempty=`[+comment.custom.name:ifempty=`[+jot.guestname+]`:esc+]`+]
				[+comment.createdby:isnt=`0`:then=`</b>`+]
				<br>Posts: [+comment.userpostcount+]
			</div>
			<div class="jot-content">
				<div class="pull-right btn-group">
					[+jot.moderation.enabled:is=`1`:then=`
					<a class="btn btn-xs btn-danger" href="[+jot.link.delete:esc+][+jot.querykey.id+]=[+comment.id+]#jotmod[+jot.link.id+]" onclick="return confirm('Are you sure you wish to delete this comment?')" title="Delete Comment"><i class="fa fa-fw fa-trash" aria-hidden="true"></i></a> 
					[+comment.published:is=`0`:then=`
					<a class="btn btn-xs btn-info"href="[+jot.link.publish:esc+][+jot.querykey.id+]=[+comment.id+]#jotmod[+jot.link.id+]" onclick="return confirm('Are you sure you wish to publish this comment?')" title="Publish Comment"><i class="fa fa-fw fa-arrow-up" aria-hidden="true"></i></a> 
					`+]
					[+comment.published:is=`1`:then=`
					<a class="btn btn-xs btn-warning" href="[+jot.link.unpublish:esc+][+jot.querykey.id+]=[+comment.id+]#jotmod[+jot.link.id+]" onclick="return confirm('Are you sure you wish to unpublish this comment?')" title="Unpublish Comment"><i class="fa fa-fw fa-arrow-down" aria-hidden="true"></i></a> 
					`+]
					`:strip+]
					[+jot.user.canedit:is=`1`:and:if=`[+comment.createdby+]`:is=`[+jot.user.id+]`:or:if=`[+jot.moderation.enabled+]`:is=`1`:then=`
					<a class="btn btn-xs btn-success" href="[+jot.link.edit:esc+][+jot.querykey.id+]=[+comment.id+]#jf[+jot.link.id+]" onclick="return confirm('Are you sure you wish to edit this comment?')" title="Edit Comment"><i class="fa fa-fw fa-pencil-square-o" aria-hidden="true"></i></a>
					`:strip+]
				</div>
				<span class="jot-poster"><b>Reply #[+comment.postnumber+] on :</b> [+comment.createdon:date=`%a %B %d, %Y, %H:%M:%S`+]</span>
				<hr>
				<div class="jot-message">[+comment.content:wordwrap:esc:nl2br+]</div>
				<div class="jot-extra">
					[+comment.editedon:isnt=`0`:then=`
					<span class="jot-editby">Last Edit: [+comment.editedon:date=`%B %d, %Y, %H:%M:%S`+] by [+comment.editedby:userinfo=`username`:ifempty=` * `+]</span>
					&nbsp;`+] [+jot.moderation.enabled:is=`1`:then=`<a target="_blank" href="http://www.ripe.net/perl/whois?searchtext=[+comment.secip+]">[+comment.secip+]</a>`+]
				</div>
			</div>
		</div>
	</div>
</div>