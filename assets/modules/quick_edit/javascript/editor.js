/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  For: MODx cms (modxcms.com)
 *  Description: Javascript for the QuickEditor
 */

function fitWindow() {
 var heightMargin = 50;
 var myForm = document.getElementById('tv_form');
 var h = myForm.offsetHeight;
 var w = myForm.offsetWidth;
 top.resizeTo(w, h+heightMargin);
}

function save() {
 var form = document.getElementById('tv_form');
 form.submit();
}

function cancel() {
 window.self.close();
}

function reloadAndClose() {
 opener.window.location.reload();
 self.close();
 return false;
}

var modVariables = [];
function setVariableModified(fieldName){
 var i, isDirty, mv = modVariables;
 for(i=0;i<mv.length;i++){
  if (mv[i]==fieldName) {
   isDirty=true;
  }
 }
 if (!isDirty) {
  mv[mv.length]=fieldName;
  var f = document.forms['mutate'];
  f.variablesmodified.value=mv.join(",");
 }
}
