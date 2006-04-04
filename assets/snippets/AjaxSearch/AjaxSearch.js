//ajaxSearch.js
//Created by: KyleJ (kjaebker@muddydogpaws.com)
//Created on: 03/14/06
//Description: This code is used to setup the ajax search request.
//My thanks to Steve Smith of orderlist.com for sharing his code on how he did this

//set the loading image to the correct location for you
//set the close image to the correct location for you
//set the ajax call to the correct location of ajaxSearch.php

function activateSearch() {
    if ($('ajaxSearch_form')) {
        var o = document.createElement('div'); // Old search results div
        var n = document.createElement('div'); // New search results div
        var i = document.createElement('img');
        i.src = 'assets/snippets/AjaxSearch/indicator.white.gif'; //Loading Image
        Element.toggle(i);
        i.id = 'indicator';
        var c = document.createElement('img');
        c.src = 'assets/snippets/AjaxSearch/close.png';
        Element.toggle(c);
        c.id = 'searchClose';
        c.onclick = function() { closeSearch(); };
        $('ajaxSearch_form').onsubmit = function() { doSearch(); return false; };
        var s = $('ajaxSearch_output');
        var f = $('ajaxSearch_form');
        o.id = 'old-search-results';
        n.id = 'current-search-results';
        s.appendChild(n);
        s.appendChild(o);
        f.appendChild(i);
        f.appendChild(c);
        o.style.display = 'none';
        n.style.display = 'none';
        is_searching = false;
        search_open = false;
    }
}

function doSearch() {
    // If we're already loading, don't do anything
    if (is_searching) return false;
    s = $F('ajaxSearch_input');
    // Same if the search is blank
    if (s == '') return false;
    is_searching = true;
    c = $('current-search-results');
    o = $('old-search-results');
    Element.toggle($('indicator'));
    if (!search_open) Element.toggle($('searchClose'));
    search_open = true;
    b = $('ajaxSearch_submit');
    b.disabled = true;
    o.innerHTML = c.innerHTML;
    c.style.display = 'none';
    o.style.display = 'block';
    // Setup the parameters and make the ajax call
    pars = 'search=' + escape(s) + '&maxResults=' + maxResults + '&stripHtml=' + stripHtml + '&stripSnip=' + stripSnip + '&stripSnippets=' + stripSnippets + '&useAllWords=' + useAllWords + '&searchStyle=' + escape(searchStyle) + '&minChars=' + minChars + '&showMoreResults=' + showMoreResults + '&moreResultsPage=' + moreResultsPage + '&moreResultsText=' + escape(moreResultsText) + '&resultsIntroFailure=' + escape(resultsIntroFailure);

    //Make sure this points to the correct location.
    var myAjax = new Ajax.Request('index-ajax.php?q=assets/snippets/AjaxSearch/AjaxSearch.php', 
              {method: 'get', parameters: pars, onComplete:doSearchResponse});
}

function doSearchResponse(response) {
    var o = $('ajaxSearch_output');
    o.style.display = 'block';
    $('current-search-results').innerHTML = response.responseText;
    new Effect.BlindUp('old-search-results',{duration:.8});
    new Effect.BlindDown('current-search-results',{duration:.8, afterFinish:resetForm});
}

function resetForm() {
    s = $('ajaxSearch_submit');
    s.disabled = false;
    Element.toggle($('indicator'));
    is_searching = false;
}

function closeSearch() {
    new Effect.BlindUp('current-search-results',{duration:.8, afterFinish:clearSearch});
    new Effect.BlindUp('old-search-results',{duration:.8});
}

function clearSearch() {
    Element.toggle($('searchClose'));
    search_open = false;
    $('old-search-results').innerHTML = '';
    $('current-search-results').innerHTML = ''
    // get rid of the results return box
    new Effect.Fade('ajaxSearch_output',{duration:.15});
}

Event.observe(window, 'load', activateSearch, false);
