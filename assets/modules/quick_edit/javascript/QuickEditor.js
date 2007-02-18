/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/19/2006
 *  For: MODx cms (modxcms.com)
 *  Description: Javascript for the QuickEditor
 */

var QuickEditor = new Class({

initialize: function(form) {

 // Pseudo constants
 this.heightAdjustment = 30; // adjust extra window height in pixels
 this.widthAdjustment = 10; // adjust extra window width in pixels

 this.form = $(form);
 this.info = $('info')
 this.data = this.getData();

 this.assignEffects();
 this.assignEvents();
 this.resize();

},

assignEffects: function() {
 var desc = $('description');
 desc.effect = new Fx.Style('description','height');
 desc.effect.originalHeight = desc.getSize().size.y;
 desc.effect.start(0);
},

assignEvents: function() {

 this.form.onsubmit = function() {
  this.apply();
  return false;
 }.bind(this)

},

resize: function() {
 window.resizeTo(Window.getScrollWidth()+this.widthAdjustment, Window.getScrollHeight()+this.heightAdjustment);
},

getData: function() {
 return this.form.toQueryString();
},

save: function(data,reload) {

 if($chk(reload)) {
  var complete = function() { opener.window.location.reload(); self.window.location.reload(); }
 } else {
  var complete = function() { opener.window.location.reload(); }
 }

 new Ajax('index.php', {
  method:'post',
  postBody:data,
  onComplete: complete
 }).request();

},

apply: function() {
 this.save(this.getData());
},

revert: function() {
 this.save(this.data,true);
},

showDescription: function() {
 var desc = $('description');
 desc.effect.start(desc.effect.now > 0 ? 0 : desc.effect.originalHeight);
}

});
