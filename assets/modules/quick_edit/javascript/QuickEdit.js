/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 4/19/2006
 *  For: MODx cms (modxcms.com)
 *  Description: Javascript for QuickEdit toolbar and links
 *  
 *  Modified by: Jaroslav Sidorkin
 *  Modification date: 9/28/2006
 *  Modification purpose: fixes IE6 bug
 */

var QuickEdit = Class.create();
QuickEdit.prototype = {

 initialize: function(moduleID, pageID, managerPath, modulePath, toolbar) {
 
  // Pseudo Constants //
  this.moduleActionID = 112;
  this.linkClassName = 'QE_Link';
  this.parentClassName = 'QE_Parent';
  this.windowSettings = 'width=400,height=300,toolbar=0,menubar=0,status=0,resizable=1,alwaysRaised=1,dependent=1';
  this.effectDuration = 250;
  
  this.moduleID = moduleID; // TODO get via AJAX, possibly
  this.pageID = pageID;
  this.managerPath = managerPath; // Any way to automatically get this?
  this.modulePath = modulePath; // TODO get automatically
  this.moduleURL = managerPath+'index.php?a='+this.moduleActionID+'&id='+this.moduleID;
  this.linksShown = (Cookie.get('QE_linksShown')==0 ? 0 : 1);
  this.position = Cookie.get('QE_position');
  this.xPosition = (this.position ? this.position.split('/')[0] : 0);
  this.yPosition = (this.position ? this.position.split('/')[1] : 0);
  this.cookieExpiration = new Date(new Date().getTime()+31536000000); // One year from now

  this.toolbar = toolbar;
  this.title = toolbar.getElementsByTagName('h1')[0];
  this.menu = this.toolbar.getElementsByTagName('ul')[0];
  this.buttons = $A(this.menu.getElementsByTagName('li')).findAll(
   function(li) {
    return (li.parentNode==this.menu);
   }.bind(this)
  );
  this.menus = $A(this.menu.getElementsByTagName('ul'));
  this.openMenu = null;
  this.links = $A(document.getElementsByClassName(this.linkClassName));

  this.assignEffects();
  this.assignEvents();
  
 },

 assignEffects: function() {
 
  // Contextual Links
  this.links.each(
   function(link) {
    link.effect = new fx.Opacity(link);
    if(!this.linksShown) { link.hide(); }
    Element.show(link);
   }
  )

  this.showLinks(this.linksShown);
  
  // Toolbar menus
  this.menus.each(
   function(menu) {
    menu.originalHeight = Element.getHeight(menu);
    menu.effect = new fx.Height(menu, { duration: this.effectDuration });
    menu.effect.hide();
   }.bind(this)
  );
  
  // Draggable toolbar title
  Drag.init(this.title,this.toolbar);
  this.toolbar.onDragEnd = function(x,y) {
   Cookie.set('QE_position', x+'/'+y, this.cookieExpiration);
  }.bind(this);
  
  // Reposition toolbar
  this.toolbar.style.left = this.xPosition+'px';
  this.toolbar.style.top = this.yPosition+'px';
  Element.show(this.toolbar);
 
 },

 assignEvents: function() {
 
  this.buttons.each(
   function(button) {
    Event.observe(button, 'click',this.menuClick.bindAsEventListener(this))
   }.bind(this)
  );
  
 },

 menuClick: function(event) {
 
  var clicked = Event.element(event);
  var menu = clicked.getElementsByTagName('ul')[0];
  
  this.menus.each(
   function(menu) {
    this.collapse(menu);
   }.bind(this)
  )
  
  this.expand(menu);
  if(this.openMenu==menu) { this.openMenu = null; }
  
 },

 expand: function(menu) {
  if(menu!=this.openMenu) {
   menu.effect.custom(menu.effect.now,menu.originalHeight);
   this.openMenu = menu;
  }
 },

 collapse: function(menu) {
  if(menu.effect.now > 0) {
   menu.effect.custom(menu.effect.now,0);
  }
 },

 toggleLinks: function() {
  this.showLinks((!this.linksShown)); 
 },

 showLinks: function(show) {
 
  this.linksShown = (show ? 1 : 0);
  Cookie.set('QE_linksShown', this.linksShown, this.cookieExpiration);
  
  Element.removeClassName('QE_ShowLinks',(this.linksShown ? 'unchecked' : 'checked'));
  Element.addClassName('QE_ShowLinks',(this.linksShown ? 'checked' : 'unchecked'));
  
  this.links.each(
   function(link) { Element[this.linksShown ? 'show' : 'hide'](link); }.bind(this)
  );
  
 },

 open: function(contentID) {
  var url = this.moduleURL+'&doc='+this.pageID+'&var='+contentID;
  var name = 'QuickEditor_'+this.pageID+'_'+contentID;
  window.open(url, name, this.windowSettings); 
 },
 
 ajaxSave: function(contentID, contentName, value) {
 
  var url = this.managerPath+'index.php?a='+this.moduleActionID+'&id='+this.moduleID+'&doc='+this.pageID+'&ajax=1';
  var params = new Array();
  params['var'] = contentID;
  params['tv'+contentName] = value;
  params['save'] = 1;
  var param_string = $H(params).toQueryString();
  
  new Ajax.Request(url, {
   method:'post',
   postBody:param_string,
   onSuccess: function() { window.location.reload(); },
   onFailure: function(request) { alert(request.responseText); }
  } ); 
  
 }

}