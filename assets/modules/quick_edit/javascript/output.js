/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  For: MODx cms (modxcms.com)
 *  Description: Javascript for QuickEdit links
 */

var QE_ParentClassName = 'QE_Parent';

// Opens the frontend editor pop-up window
function QE_OpenEditor(pageId, contentId) {
 var settings = 'width=400, height=300, toolbar=0, menubar=0, status=0, alwaysRaised=1, dependent=1, resizable=1';
 var url = managerPath+'index.php?a=112&id='+modId+'&doc='+pageId+'&var='+contentId;
 var name = 'QuickEditor';
 window.open(url, name, settings);
}

// Assign a new class to the parent
function QE_HighlightContent(editLink) {
 editLink.parentNode.className += ' '+QE_ParentClassName;
}

// Remove the class from the parent
function QE_UnhighlightContent(editLink) {
 var parent = editLink.parentNode;
 var match = new RegExp(QE_ParentClassName);
 parent.className = parent.className.replace(match, '');
}

function QE_ShowHideLinks(change) {

 var key
 var links
 var link
 var hideLinks

 links = document.getElementsByTagName('a');
 hideLinks = getCookie('QuickEditHideLinks');
 if(change == true) {
  hideLinks = (hideLinks == '1' ? '0' : '1');
 }
 setCookie('QuickEditHideLinks', hideLinks);

 if(hideLinks == '1') {
  document.getElementById('QE_ShowHide').innerHTML = QE_show_links;
 } else {
  document.getElementById('QE_ShowHide').innerHTML = QE_hide_links;
 }

 for(var i=0; i<links.length; i++) {

  link = links[i];
 
  if(link.className == 'QE_Link') {
  
   if(hideLinks == '1') {
    link.style.display = 'none';
   } else {
    link.style.display = 'inline';
   }
   
  }
  
 }

}

function QE_HasParent(element, parentElement) {
 if(element != parentElement) {
  while(element = element.parentNode) {
   if(element == parentElement) return true;
  }
 }
}

function QE_Expand(divElement) {
 var className = divElement.className;
 divElement.className = 'expanded';
 if(className == 'collapsed') {
  new Effect.BlindDown(divElement.id, {duration: 0.2});
 }
}

function QE_Collapse(evnt) {

 var parent = document.getElementById('QE_Toolbar');
 var divs = document.getElementsByTagName('div');

 if (!evnt) var evnt = window.event;
 var evnt_target = (window.event) ? evnt.srcElement : evnt.target;

 if(evnt_target.id != 'QE_Collapse_Wrapper') return;

 for(var i=0; i<divs.length; i++) {
  if(QE_HasParent(divs[i], parent)) {
   divs[i].className = 'collapsed';
  }
 }
 
}

function QE_SetPosition(toolbar) {
 setCookie('QE_PositionTop', toolbar.offsetTop);
 setCookie('QE_PositionLeft', toolbar.offsetLeft);
}

function QE_PositionToolbar(toolbar) {

 var top = getCookie('QE_PositionTop');
 var left = getCookie('QE_PositionLeft');
 if(!top) { top = 10; }
 if(!left) { left = 10; }
 
 new Effect.MoveBy(toolbar.id, top, left, {duration:0});
 
}
