/**
 * AjaxSearch_tplAjaxResult
 *
 * Result Tpl for AjaxSearch
 *
 * @category	chunk
 * @internal    @modx_category Demo Content
 * @internal    @installset base, sample
 */
<div class="[+as.resultClass+]">
  <strong><a class="[+as.resultLinkClass+]" href="[+as.resultLink+]" title="[+as.longtitle+]">[+as.pagetitle+]</a></strong>
[+as.descriptionShow:is=`1`:then=`
  <small><span class="[+as.descriptionClass+]">[+as.description+]</span></small>
`+]
[+as.extractShow:is=`1`:then=`
  <div class="[+as.extractClass+]"><p>[+as.extract+]</p></div>
`+]
[+as.breadcrumbsShow:is=`1`:then=`
  <span class="[+as.breadcrumbsClass+]">[+as.breadcrumbs+]</span>
`+]
</div>