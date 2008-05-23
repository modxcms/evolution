<?php
/*
templates.inc.php - for AjaxSearch 1.7.1
Created by: KyleJ (kjaebker@muddydogpaws.com)
Created on: 01/22/07
Description: Templates used to display AjaxSearch form/results
*/
$asTemplates = array(
  //Template for output of all search elements
  'layout' => '
  [+as.form+]
  [+as.intro+]
  [+as.results+]',
  //Form Template
  'form' => '
  <form [+as.formId+]action="[+as.formAction+]" method="post">
      <label for="ajaxSearch_input">
        <input id="ajaxSearch_input" type="text" name="search" value="[+as.inputValue+]"[+as.inputOptions+] />
      </label>
      <label for="ajaxSearch_submit">
        <input id="ajaxSearch_submit" type="submit" name="sub" value="[+as.submitText+]" />
      </label>
    </form>',
  //Template for each search result
  'result' => '
    <div class="[+as.resultClass+]">
      <a class="[+as.resultLinkClass+]" href="[+as.resultLink+]" title="[+as.longtitle+]">[+as.pagetitle+]</a>
      [+as.description+]
      [+as.extract+]
    </div>',
  //Template for results container when ajax search is not enabled or for more results page
  'no_ajax_outer' => '
    [+as.resultInfo+]
    [+as.paging+]
    <div id="ajaxSearch_resultListContainer">
      [+as.results+]
    </div>
    [+as.paging+]',
  //Template for wrapper around result description, placed in template 'result' placeholder '[+as.description+]'
  'descriptionWrapper' => '
    <span class="[+as.descriptionClass+]">[+as.description+]</span>',
  //Template for wrapper around extract, placed in template 'result' placeholder '[+as.extract+]'
  'extractWrapper' => '
    <div class="[+as.extractClass+]"><p>[+as.extract+]</p></div>',
  //Template for more results link with ajax search
  'ajax_more_results' => '
    <div class="[+as.moreClass+]">
      <a href="[+as.moreLink+]" title="[+as.moreTitle+]">[+as.moreText+]</a>
    </div>',
  //Template for no results message
  'noResults' => '
    <div class="[+as.noResultClass+]">
      [+as.noResultText+]
    </div>',
  //Template for wrapper around paging links
  'pagingLinksOuter' => '
    <span class="ajaxSearch_paging">
      [+as.pagingText+]
      [+as.pagingLinks+]
    </span>',
  //Template for each paging link
  'pagingLinks' => '
    <a href="[+as.pagingLink+]">[+as.pagingText+]</a>[+as.pagingSeperator+]',
  //Template for current page paging link
  'pagingLinksCurrent' => '
    [+as.pagingText+][+as.pagingSeperator+]',
  //Template for result info, i.e. 15 results found for 'MODx'
  'resultsInfo' => '
    <p class="ajaxSearch_resultsInfo">[+as.resultInfoText+]</p>',
  //Template for intro message
  'introMessage' => '
    <p class="ajaxSearch_intro" id="ajaxSearch_intro">[+as.introMessage+]</p>',
);

?>
