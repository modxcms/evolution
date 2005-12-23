/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  For: MODx cms (modxcms.com)
 *  Description: Javascript for QuickEdit links
 */

var QE_ParentClassName = 'QE_Parent';
var QE_ModuleActionId = 112;
var QE_Today = new Date();
var QE_CookieExpiration = new Date(QE_Today.getFullYear()+1, QE_Today.getMonth(), QE_Today.getDate()) // One year from now

// Opens the frontend editor pop-up window
function QE_OpenEditor(pageId, contentId) {
 var settings = 'width=400, height=300, toolbar=0, menubar=0, status=0, alwaysRaised=1, dependent=1, resizable=1';
 var url = managerPath+'index.php?a='+QE_ModuleActionId+'&id='+modId+'&doc='+pageId+'&var='+contentId;
 var name = 'QuickEditor';
 window.open(url, name, settings);
}

// Sends an AJAX request to the QE module
function QE_SendAjax(vars, successHandler, errorHandler) {
new Ajax.Request(managerPath+'index.php?a='+QE_ModuleActionId+'&id='+modId+'&ajax=1', {method:'post', postBody:vars, onSuccess:successHandler, onFailure:errorHandler} );
}

// Assign a new class to the parent
function QE_HighlightContent(editLink) {
 new Element.addClassName(editLink.parentNode, QE_ParentClassName);
}

// Remove the class from the parent
function QE_UnhighlightContent(editLink) {
 new Element.removeClassName(editLink.parentNode, QE_ParentClassName);
}

function QE_ToggleLinks(change) {

 var links
 var hideLinks

 links = document.getElementsByClassName('QE_Link');

 hideLinks = getCookie('QuickEditHideLinks');
 if(change == true) { hideLinks = (hideLinks == '1' ? '0' : '1'); }
 setCookie('QuickEditHideLinks', hideLinks, QE_CookieExpiration);

 for(var i=0; i<links.length; i++) {
  if(hideLinks=='1') {
   new Effect.Fade(links[i], {duration:1});
  } else {
   new Effect.Appear(links[i], {duration:1});
  }
 }

 $('QE_ShowLinks_check').src = modPath+'/images/'+(hideLinks=='1' ? 'un' : '')+'checked.gif';

}

function QE_SetPosition(toolbar) {
 setCookie('QE_PositionTop', toolbar.offsetTop, QE_CookieExpiration);
 setCookie('QE_PositionLeft', toolbar.offsetLeft, QE_CookieExpiration);
}

function QE_PositionToolbar(toolbar) {

 var top = getCookie('QE_PositionTop');
 var left = getCookie('QE_PositionLeft');
 if(!top) { top = 0; }
 if(!left) { left = 0; }
 
 new Effect.MoveBy(toolbar.id, top, left, {duration:0, onComplete: new Effect.Appear(toolbar, {duration:1 }) });

}

function QE_HideAll() {

 var buttons = document.getElementsByClassName('QE_Button_Opened');
 var menus = document.getElementsByClassName('QE_Menu');

 for(var i=0; i<buttons.length; i++) {
  Element.removeClassName(buttons[i], 'QE_Button_Opened');
 }

 for(var i=0; i<menus.length; i++) {
  if(Element.visible(menus[i])) {

   // Optional effects (only uncomment one)
   // new Effect.SlideUp(menus[i], {duration:0.5}); // Best effect - IE doesn't like it much thought
   Element.hide(menus[i]); // Most reliable

  }
 }

}

function QE_ToggleMenu(menu_name) {

 var button = $('QE_Button_'+menu_name);
 var menu = $('QE_Menu_'+menu_name);
 var hide = Element.visible(menu);

 if(hide) {

  Element.removeClassName(button,'QE_Button_Opened');

  // Optional effects (only uncomment one)
  // new Effect.SlideUp(menu, {duration:0.5}); // Best effect - IE doesn't like it much though
  // Element.hide(menu) // Fastest, most reliable
  new Effect.Fade(menu, {duration:0.25}); // Best compromise of reliability and effect

 } else {

  QE_HideAll(); // Hide any open menus
  Element.addClassName(button,'QE_Button_Opened'); // Make button look like tab

  // Optional effects (only uncomment one)
  // new Effect.SlideDown(menu, {duration:0.5}); // Best effect - IE doesn't like it much though
  // Element.show(menu);                         // Fastest, most reliable
  new Effect.Appear(menu, {duration:0.25});        // Best compromise of reliability and effect

 }

}
