//ajaxSearch-mootools1.2.js
//Version: 1.8.4 - refactored by coroico
//20/10/2009 - mootools 1.2 version of ajaxSearch.js

//set the loading and the close image to the correct location for you
//set the folder location to the correct location of ajaxSearch.php
//set the default parameters as default.config.inc.php

// AjaxSearch Snippet folder location
var _base = 'assets/snippets/ajaxSearch/';

// AjaxSearch default snippet parameter values
var _version = '1.8.4';
var opacity = 1.;
var liveSearch = 0;
var minChars = 3;

var newToggle;
var is_searching = false;
var liveTimeout = null;

function activateSearch() {
  if (as_version != _version) {
    alert("AjaxSearch version obsolete. Empty your browser cache and check the version of AjaxSearch.js file");
    return;
  }

  res = ucfg.match(/&opacity=`([^`]*)`/);
  if (res != null) opacity = parseFloat(res[1]); 

  res = ucfg.match(/&liveSearch=`([^`]*)`/);
  if (res != null) liveSearch = parseInt(res[1]);

  res = ucfg.match(/&minChars=`([^`]*)`/);
  if (res != null) minChars = parseInt(res[1]);

  var asf = $('ajaxSearch_form');

  var aso = $('ajaxSearch_output');
  aso.setStyle('opacity', '0');

  if (asf) {
    asf.addEvent('submit', function(e) { e.stop(); doSearch(); });

    var c = new Element('img'); //Close Image
    c.setProperties({
       src: _base + 'images/cross.png', 
       alt: 'close search',
       id: 'searchClose'
    });
    c.addEvent('click', function(){closeSearch();});
    toggleImage(c);
    asf.appendChild(c);
    
    var i = new Element('img');
    i.setProperties({
       src: _base + 'images/indicator.white.gif', //Loading Image
       alt: 'loading',
       id: 'indicator'
    });
    toggleImage(i);
    asf.appendChild(i);

    var n = new Element('div'); // New search results div
    n.setProperty('id', 'current-search-results');
    n.setStyle('opacity', '1');
    aso.appendChild(n);
    newToggle = new Fx.Slide('current-search-results', {duration: 600,transition: Fx.Transitions.Quint.easeIn}).hide();
    newToggle.isDisplayed = function() {
      return this.wrapper['offset'+this.layout.capitalize()] > 0;
    }

    is_searching = false;
    search_open = false;
    if (liveSearch) {
      $('ajaxSearch_input').addEvent('keyup', liveSearchReq);
      $('ajaxSearch_submit').setStyle('opacity', '0');         
    }
  }
}

function liveSearchReq() {
  if (liveTimeout) {
    window.clearTimeout(liveTimeout);
  }
  liveTimeout = window.setTimeout("doSearch()",600);
}

function doSearch() {
  // If we're already loading, don't do anything
  if (is_searching) return false;

  // get the input searchstring from select or from input
  if (ss = $('ajaxSearch_select')) {
    selected = new Array();
    for (var i = 0; i < ss.options.length; i++) if (ss.options[ i ].selected) selected.push(ss.options[ i ].value);
    s = selected.join(" ");
  }
  else {
    s = $('ajaxSearch_input').value;
  }
  // Same if the searchstring is blank
  if (s == '') return false;
  if (liveSearch && s.length < minChars) return false;
  is_searching = true;
  c = $('current-search-results');

  toggleImage($('indicator'));
  if (!search_open) {toggleImage($('searchClose'));}
  search_open = true;
  b = $('ajaxSearch_submit');
  b.disabled = true;

  if (newToggle.isDisplayed()) {
    newToggle.toggle(); 
  }

  // update the advSearch value from radio button if they exists
  setAdvSearch('radio_oneword');
  setAdvSearch('radio_allwords');
  setAdvSearch('radio_exactphrase');
  setAdvSearch('radio_nowords');

  // update the subSearch value from radio button if they exists
  sbsname = '';
  for (var i = 1; i < subSearch+1; i++) {
    if (sbs = $('subSearch'+i)) {
      if (sbs.checked == true) sbsname = sbs.value;
    }
  }
  subSearch = sbsname;

  // Setup the parameters and make the ajax call to the popup window
  var pars = Hash.toQueryString({
    q: _base + 'ajaxSearchPopup.php',
    search: s,
    as_version: as_version,
    advSearch: encodeURI(advSearch),
    subSearch: encodeURI(subSearch),
    ucfg: ucfg
  });

  var ajaxSearchReq = new Request({url: 'index-ajax.php', method: 'post', data: pars, onComplete: doSearchResponse});
  if (newToggle.isDisplayed()) {
  newToggle.toggle();
    ajaxSearchReq.send.delay(600, ajaxSearchReq);
  } else {
      ajaxSearchReq.send();
  }
  return true;
}

function doSearchResponse(request) {
  var o = $('ajaxSearch_output');
  o.setStyle('opacity', opacity);  // set of opacity parameter
  $('current-search-results').set('html', request);
  newToggle.toggle();
  is_searching = false;
  setTimeout('resetForm()',600);
}

function resetForm() {
  s = $('ajaxSearch_submit');
  s.disabled = false;
  toggleImage($('indicator'));
}

function closeSearch() {
  newToggle.toggle();
  setTimeout('clearSearch()',600);
}

function clearSearch() {
  toggleImage($('searchClose'));
  search_open = false;
  $('current-search-results').innerHTML = '';
  var o = $('ajaxSearch_output');
  o.setStyle('opacity', '0');
  $('ajaxSearch_input').value="";
  $('ajaxSearch_input').focus();
}

function setAdvSearch(id) {
  if (r = $(id)) {
    if (r.checked == true) advSearch = r.value;
  }
}

function toggleImage(imgElement) {
  imgStyle = imgElement.getStyle('opacity');
  if (imgStyle == '0') {
    imgElement.setStyle('opacity', '1');
  } else {
    imgElement.setStyle('opacity', '0');
  }
}

window.addEvent('domready', function(){
   activateSearch();
})
