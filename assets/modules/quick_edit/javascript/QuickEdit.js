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

var QuickEdit = new Class({

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
  this.linksShown = (Cookie.get('QE_linksShown')==='0' ? false : true);
  this.position = Cookie.get('QE_position');
  this.xPosition = (this.position ? this.position.split('/')[0] : 0);
  this.yPosition = (this.position ? this.position.split('/')[1] : 0);
  this.cookieDuration = 365; // days

  this.toolbar = toolbar;
  this.menu = $E('ul',this.toolbar);
  this.title = $E('h1',this.toolbar);
  this.buttons = $ES('li','QE_Toolbar');
  this.buttons.each(function(button,i) {
   if(!button.parentNode==this.menu) { this.buttons.remove(this.buttons[i]); }
  },this);
  this.menus = this.menu.getElements('ul');
  this.openMenu = null;
  this.links = $$('.'+this.linkClassName);

  this.assignEffects();
  this.assignEvents();

 },

 assignEffects: function() {
 
  this.toolbar.setStyle('display','block');

  // Contextual link effects
  this.links.each(
   function(link) {
    link.effect = new Fx.Style(link,'opacity',{duration:100});
    link.effect.set(0);
    link.setStyle('display','block');
   }
  )
  this.showLinks(this.linksShown);
  
  // Toolbar menus effects
  this.menus.each(
   function(menu) {
    menu.originalHeight = $(menu).getSize().size.y;
    menu.effect = new Fx.Style(menu,'height',{ duration: this.effectDuration });
    menu.effect.set(0);
   }.bind(this)
  );

  // Draggable toolbar title
  this.toolbar.makeDraggable({
   handle: $E('h1',this.toolbar),
   onComplete: function(x,y) {Cookie.set('QE_position', this.toolbar.getLeft()+'/'+this.toolbar.getTop(), {duration:this.cookieDuration}); }.bind(this)
  });

  // Reposition toolbar
  this.toolbar.style.left = this.xPosition+'px';
  this.toolbar.style.top = this.yPosition+'px';

 },

 assignEvents: function() {
 
  this.buttons.each(
   function(button) {
    button.addEvent('click',this.menuClick.bind(this));
   }.bind(this)
  );
  
 },

 menuClick: function(event) {

  var event = new Event(event);
 
  var clicked = event.target;
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
   menu.effect.start(menu.originalHeight);
   this.openMenu = menu;
  }
 },

 collapse: function(menu) {
  if(menu.effect.now > 0) {
   menu.effect.start(0);
  }
 },

 toggleLinks: function() {
  this.showLinks((!this.linksShown)); 
 },

 showLinks: function(show) {
 
  this.linksShown = (show ? true : false);
  Cookie.set('QE_linksShown', (this.linksShown ? '1' : '0'), {duration:this.cookieDuration});
  
  $('QE_ShowLinks')[this.linksShown ? 'addClass' : 'removeClass']('checked');
  
  this.links.each(
   function(link) { link.effect.start(this.linksShown ? 100 : 0); }.bind(this)
  );
  
 },

 open: function(contentID) {
  var url = this.moduleURL+'&doc='+this.pageID+'&var='+contentID;
  var name = 'QuickEditor_'+this.pageID+'_'+contentID;
  window.open(url, name, this.windowSettings); 
 },
 
 ajaxSave: function(contentID, contentName, value) {
 
  var url = this.managerPath+'index.php?a='+this.moduleActionID+'&id='+this.moduleID+'&doc='+this.pageID+'&ajax=1';
  var params = [];
  params['var'] = contentID;
  params['tv'+contentName] = value;
  params['save'] = 1;
  
  new Ajax(url, {
   method:'post',
   postBody:Object.toQueryString(params),
   onComplete: function() { window.location.reload(); }
  }).request(); 
  
 }

});
