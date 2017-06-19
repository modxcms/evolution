/**
 * AjaxSearch_tplInput
 *
 * Input-Form for AjaxSearch
 *
 * @category	chunk
 * @internal    @modx_category Demo Content
 * @internal    @installset base, sample
 */
[+as.showInputForm:is=`1`:then=`
<form id="[+as.formId+]" action="[+as.formAction+]" method="post">
    [+as.showAsId:is=`1`:then=`<input type="hidden" name="[+as.asName+]" value="[+as.asId+]" />`+]
    <input type="hidden" name="advsearch" value="[+as.advSearch+]" />
	<div class="input-group">
		<input id="[+as.inputId+]" class="form-control cleardefault" type="text" name="search" value="[+as.inputValue+]"[+as.inputOptions+] />
		[+as.liveSearch:is=`0`:then=`
		<span class="input-group-btn">
			<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>	
		</span>
		`:else=`
		<div class="input-group-addon"><i class="fa fa-search"></i></div>
		`+]		
	</div>
</form>
`+]
[+as.showIntro:is=`1`:then=`
<p class="ajaxSearch_intro" id="ajaxSearch_intro">[+as.introMessage+]</p>
`+]