//ajaxSearch_jquery.js
//Version: 1.8.3 - refactored by coroico

//08/06/09 - jquery version of ajaxSearch.js (jQuery 1.2.6)

//set the loading image to the correct location for you
//set the close image to the correct location for you
//set the ajax call to the correct location of ajaxSearch.php

// AjaxSearch Snippet folder location
var _base = 'assets/snippets/ajaxSearch/';

// AjaxSearch Snippet parameter values
var _version = '1.8.3';
var opacity = 1.;
var liveSearch = 0;
var minChars = 3;

var is_searching = false;
var liveTimeout = null;

function activateSearch() {
  if (as_version != _version) {
    alert("AjaxSearch version obsolete. Empty your browser cache and check the version of AjaxSearch-jQuery.js file");
    return;
  }

  res = ucfg.match(/&opacity=`([^`]*)`/);
  if (res != null) opacity = parseFloat(res[1]); 

  res = ucfg.match(/&liveSearch=`([^`]*)`/);
  if (res != null) liveSearch = parseInt(res[1]);

  res = ucfg.match(/&minChars=`([^`]*)`/);
  if (res != null) minChars = parseInt(res[1]);

  var asf = $('#ajaxSearch_form');
  var aso = $('#ajaxSearch_output');
  aso.hide();

  if (asf) {
    asf.submit(function() { doSearch(); return false; });

    asf.append('<img src="'+_base + 'images/cross.png" alt="close search" id="searchClose" onclick="closeSearch();" style="display:none" />');
    asf.append('<img src="'+_base + 'images/indicator.white.gif" alt="loading" id="indicator" style="display:none" />');
    aso.append('<div id="current-search-results"></div>');

    is_searching = false;
    search_open = false;
    if (liveSearch) {
      $('#ajaxSearch_input').keyup(liveSearchReq);
      $('#ajaxSearch_submit').css("opacity",0);
    }
  }
}

function liveSearchReq() {
  if (liveTimeout) {
    window.clearTimeout(liveTimeout);
  }
  liveTimeout = window.setTimeout(doSearch,600);
}

function doSearch() {
  // If we're already loading, don't do anything
  if (is_searching) return false;

  // get the input searchstring from select or from input
  ass = $('#ajaxSearch_select');
  if (ass.length) {
    select = new Array();
    $("#ajaxSearch_select option:selected").each(function(i) {select.push(this.value);});
    s = select.join(" ");
  }
  else {
    s = $('#ajaxSearch_input').attr('value');
  }
  // Same if the searchstring is blank
  if (s == '') return false;
  if (s.length < minChars) return false;

  is_searching = true;

  $('#indicator').show();
  if (!search_open) $('#searchClose').show();
  search_open = true;
  $('#ajaxSearch_submit').attr('disabled', 'disabled');

  $('#ajaxSearch_output').show();
  $('#current-search-results').slideUp(600);

  // update the advSearch value from radio button if they exists
  setAdvSearch('radio_oneword');
  setAdvSearch('radio_allwords');
  setAdvSearch('radio_exactphrase');
  setAdvSearch('radio_nowords');

  // update the subSearch value from radio button if they exists
  sbsname = '';
  for (var i = 1; i < subSearch+1; i++) {
  	sbs = $('#subSearch'+i);
    if (sbs.length && sbs.attr('checked') != false) sbsname = sbs.attr('value');
  }
  subSearch = sbsname;

  // Setup the parameters and make the ajax call to the popup window
  var pars = {
    q: _base + 'ajaxSearchPopup.php',
    search: s,
    as_version: as_version,
    advSearch: encodeURI(advSearch),
    subSearch: encodeURI(subSearch),
    ucfg: ucfg
  };

  $('#current-search-results').load('index-ajax.php', pars).slideDown(600);
  $('#ajaxSearch_output').css('opacity', opacity);
  setTimeout('resetForm()',600);
  is_searching = false;

  return true;
}

function resetForm() {
  $('#ajaxSearch_submit').attr('disabled', false);
  $('#indicator').hide();
}

function closeSearch() {
  $('#current-search-results').slideUp(600);
  setTimeout('clearSearch()',600);
}

function clearSearch() {
  $('#searchClose').hide();
  search_open = false;
  $('#current-search-results').html = '';
  $('#ajaxSearch_output').attr('value', '').hide();
}

function setAdvSearch(id) {
  r = $('#'+id);
  if (r.length && r.attr('checked') != false) advSearch = r.attr('value');
}

$(activateSearch);
