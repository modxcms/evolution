/**
 * AjaxSearch_tplAjaxResults
 *
 * Results Tpl for AjaxSearch
 *
 * @category	chunk
 * @internal    @modx_category Demo Content
 * @internal    @installset base, sample
 */
<div id="search_results" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Search Results</h3>
      </div>
      <div class="modal-body">
        [+as.noResults:is=`1`:then=`
		  <div class="[+as.noResultClass+]">
			[+as.noResultText+]
		  </div>
		`:else=`
		<p class="AS_ajax_resultsInfos">[+as.resultsFoundText+]<span class="AS_ajax_resultsDisplayed">[+as.resultsDisplayedText+]</span></p>
		[+as.listGrpResults+]
		`+]
		[+as.moreResults:is=`1`:then=`
		  <div class="[+as.moreClass+]">
			<a href="[+as.moreLink+]" title="[+as.moreTitle+]">[+as.moreText+]</a>
		  </div>
		`+]
		[+as.showCmt:is=`1`:then=`
		[+as.comment+]
		`+]
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>$('#search_results').modal('show')</script>