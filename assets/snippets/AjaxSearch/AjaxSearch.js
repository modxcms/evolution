//AjaxSearch.js
//Created by: KyleJ (kjaebker@muddydogpaws.com)
//Created on: 03/14/06
//Description: This code is used to setup the ajax search request.
//My thanks to Steve Smith of orderlist.com for sharing his code on how he did this
//Live Search by Thomas (Shadock)

//Updated: 09/18/06 - Added user permissions to searching

//set the loading image to the correct location for you
//set the close image to the correct location for you
//set the ajax call to the correct location of ajaxSearch.php

//From Thomas : vars for live search
var _oldInputFieldValue="";
var _currentInputFieldValue="";
var _timeoutAdjustment=0;

function activateSearch() {
    if ($('ajaxSearch_form')) {
        var o = document.createElement('div'); // Old search results div
        var n = document.createElement('div'); // New search results div
        var i = document.createElement('img');
        i.src = 'assets/snippets/AjaxSearch/indicator.white.gif'; //Loading Image
        Element.toggle(i);
        i.id = 'indicator';

        var c = document.createElement('img');
        c.src = 'assets/snippets/AjaxSearch/close.png'; //Close Image
        c.id = 'searchClose';
        c.onclick = function() { closeSearch(); };

        if (liveSearch) {
            c.style.position = 'absolute';
            c.style.top = '1px';
            c.style.right = '1px';
        } else {
            Element.toggle(c);
        }

        $('ajaxSearch_form').onsubmit = function() { doSearch(); return false; };
        var s = $('ajaxSearch_output');
        var f = $('ajaxSearch_form');
        o.id = 'old-search-results';
        n.id = 'current-search-results';
        if (liveSearch) {s.appendChild(c);}
        s.appendChild(n);
        s.appendChild(o);
        f.appendChild(i);
        if (!liveSearch) {f.appendChild(c);}
        o.style.display = 'none';
        n.style.display = 'none';
        is_searching = false;
        search_open = false;
        if (liveSearch) {
            Event.observe($('ajaxSearch_input'), 'keyup', mainLoop, false);
            $('ajaxSearch_submit').style.display = 'none'; 
        }
    }
}

//From Thomas : loop for monitoring input change and call doSearch
function recalculateTimeout(Mb){
  var H=100;
  for(var o=1; o<=(Mb-2)/2; o++){
    H=H*2
  }
  H=H+50;
  return H
}

function mainLoop() {
  _currentInputFieldValue = $F('ajaxSearch_input');
  if(_oldInputFieldValue!=_currentInputFieldValue){
    if (_currentInputFieldValue.length>2) {
      if (doSearch())
        _oldInputFieldValue=_currentInputFieldValue;
    }
    _timeoutAdjustment++;
    $('ajaxSearch_input').focus();

  }

  setTimeout("mainLoop()",recalculateTimeout(_timeoutAdjustment));
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
    if (!liveSearch) {if (!search_open) {Element.toggle($('searchClose'));}}
    search_open = true;
    b = $('ajaxSearch_submit');
    b.disabled = true;
    o.innerHTML = c.innerHTML;
    c.style.display = 'none';
    o.style.display = 'block';
    // Setup the parameters and make the ajax call
    pars = 'search=' + encodeURIComponent(s) + '&maxResults=' + maxResults + '&stripHtml=' + stripHtml + '&stripSnip=' + stripSnip + '&stripSnippets=' + stripSnippets + '&useAllWords=' + useAllWords + '&searchStyle=' + escape(searchStyle) + '&minChars=' + minChars + '&showMoreResults=' + showMoreResults + '&moreResultsPage=' + moreResultsPage + '&moreResultsText=' + encodeURIComponent(moreResultsText) + '&resultsIntroFailure=' + encodeURIComponent(resultsIntroFailure) + '&extract=' + extract + '&docgrp=' + escape(docgrp);

    //Make sure this points to the correct location.
    var myAjax = new Ajax.Request('index-ajax.php?q=assets/snippets/AjaxSearch/AjaxSearch.php',
              {method: 'get', parameters: pars, onComplete:doSearchResponse, onFailure: callBackAjaxFailure, onException: callBackAjaxExc}); //From Thomas : Added error checking
    return true;
}

function doSearchResponse(response) {
    var o = $('ajaxSearch_output');
    o.style.display = 'block';
    $('current-search-results').innerHTML = decodeURIComponent(response.responseText);
    //new Effect.Fade('ajaxSearch_intro',{duration:.3, queue: 'front'});
    if (liveSearch) {
        new Effect.Fade('old-search-results',{duration:.8, queue: 'front'});
        new Effect.Appear('current-search-results',{duration:.8, queue: 'end', afterFinish:resetForm});
    } else {
        new Effect.BlindUp('old-search-results',{duration:.8, queue: 'front'});
        new Effect.BlindDown('current-search-results',{duration:.8, queue: 'end', afterFinish:resetForm});
    }
}

//From Thomas : a small ajax error handling
function callBackAjaxFailure(request, e) {
  alert('FAILURE AJAX : '+e.message+'\n'+e.stack);
  resetForm();
}

function callBackAjaxExc(request, e) {
  alert('EXCEPTION AJAX : '+e.message+'\n'+e.stack);
  resetForm();
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
    if (!liveSearch) {Element.toggle($('searchClose'));}
    search_open = false;
    $('old-search-results').innerHTML = '';
    $('current-search-results').innerHTML = ''
    new Effect.Fade('ajaxSearch_output',{duration:.5});
    $('ajaxSearch_input').value="";
}

Event.observe(window, 'load', activateSearch, false);
