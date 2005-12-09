/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  For: MODx cms (modxcms.com)
 *  Description: Javascript for the QuickEditor
 */

/* --------- SNAPSHOT CODE -------------- */

var snapshot = '';

function takeSnapshot(formElement) {
snapshot = Form.serialize(formElement);
}

function saveSnapshot() {
QE_SendAjax(snapshot, function() { window.location.reload(); opener.location.reload(); } );
}

// Sends an AJAX request to the QE module
function QE_SendAjax(vars, successHandler, errorHandler) {
new Ajax.Request('index.php', {method:'post', postBody:vars, onSuccess:successHandler, onFailure:errorHandler} );
}

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

function applyChanges(formElement) {

// Update RTE linked fields

// FCKeditor
if(typeof(FCKeditorAPI) != 'undefined') {
var oEditor = FCKeditorAPI.GetInstance(QE_ContentVariableID);
oEditor.UpdateLinkedField();
}

var serializedForm = Form.serialize(formElement);
QE_SendAjax(serializedForm, function() { opener.window.location.reload(); } );

}

function reloadAndClose() {
 opener.window.location.reload();
 self.close();
 return false;
}

var modVariables = [];
function setVariableModified(fieldName){
 var i;
 var isDirty
 var mv = modVariables;
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
