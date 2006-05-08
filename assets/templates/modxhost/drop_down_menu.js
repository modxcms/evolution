/*
Written by: Adam Crownoble (adam@bryan.edu)
Date: April 3 2006
License: LGPL (http://www.gnu.org/copyleft/lesser.html)
*/

var DropDownMenu = Class.create();
DropDownMenu.prototype = {

 initialize: function(menuElement) {

  this.menu = menuElement;
  this.id = menuElement.id;
  this.duration = 250;

  this.buttons = $A(this.menu.getElementsByTagName('li')).findAll(
   function(li) {
    return (li.parentNode == menuElement);
   }
  );
  this.submenus = $A(this.menu.getElementsByTagName('ul'));

  this.submenus.each(
   function(submenu) {
    Element.show(submenu);
    submenu.originalHeight = Element.getHeight(submenu);
    submenu.effect = new fx.Height(submenu, { duration: this.duration });
    submenu.effect.hide();
   }.bind(this)
  );

  this.buttons.each(
   function(button) {
    Event.observe(button, 'mouseover',this.expand.bindAsEventListener(this));
    Event.observe(button, 'mouseout', this.collapse.bindAsEventListener(this));
   }.bind(this)
  );

 },

 findButton: function(element) {
  var button = false;
  while(element.parentNode) {
   if(this.buttons.include(element)) { button = element; }
   element = element.parentNode;
  }
  return button;
 },

 findSubmenu: function(element) {
  var button = this.findButton(element);
  var submenu = button.getElementsByTagName('ul')[0];
  return submenu;
 },

 expand: function(event) {
  var submenu = this.findSubmenu(Event.element(event));
  submenu.effect.clearTimer();
  submenu.effect.custom(submenu.effect.now, submenu.originalHeight);
 },

 collapse: function(event) {
  var submenu = this.findSubmenu(Event.element(event));
  submenu.effect.clearTimer();
  submenu.effect.custom(submenu.effect.now, 0);
 }

};
