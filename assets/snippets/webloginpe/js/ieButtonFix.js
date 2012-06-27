//--(Begin)-->fetch value of button via outerHTML
function getButtonTagValue(buttonObj) {
var regexObj = /<[^<]*value="([^"'<>]*)"[^>]*>/;
//'
var match = regexObj.exec(buttonObj.outerHTML);
if (match != null && match.length > 1) {
return match[1];
} else {
return '';
}
}
//--(End)-->fetch value of button via outerHTML

function insertHiddenField(parentObj, name, value) {
var hiddenField = document.createElement('input');
hiddenField.type='hidden';
hiddenField.name=name;
hiddenField.value=value;
parentObj.parentNode.appendChild(hiddenField);
}

function fixIeButtonTagBug(formId) {
if (document.all || 1==1) {
var baseObject=document;
var elms;
var custFunc;
var theForms = []; // pixelchutes

if (formId) baseObject=document.getElementById(formId);
elms=baseObject.getElementsByTagName('button');

if (elms) { 
for (var x=0; x<elms.length; x++)
if (elms[x].tagName=='BUTTON') {
	
	// Add first associated form to theForms array (if needed) -  pixelchutes
	if( theForms.length == 0 ) theForms[0] = elms[x].form;
	// Loop through defined forms and push if needed -  pixelchutes
	for (var f=0; f<theForms.length; f++)
		if( theForms[f].id != elms[x].form.id )
			theForms[f+1] = elms[x].form;
	
	
//this.setAttribute('value', getButtonTagValue(this)); this.setAttribute('name', '"+elms[x].name+"');
custFunc=new Function(
"insertHiddenField(this,'"+elms[x].name+"',getButtonTagValue(this));"+
"return true;"
);

elms[x].onclick=custFunc;
elms[x].name='serviceButtonValue';
}
}

	// Loop through associated forms -  pixelchutes
	for (var f=0; f<theForms.length; f++){
		theForm = theForms[f];
		
		// Loop through theForm's elements -  pixelchutes
		for( el=0; el<theForm.elements.length; el=el+1 )
			// Tie keypress event handler to text fields
            if( ( theForm.elements[el].type == 'text' || theForm.elements[el].type == 'password' ) ){
                 theForm.elements[el].onkeypress = function(e){
                 
                 	// Determine the keyCode
                 	var code;
					if (!e) var e = window.event;
					if (e.keyCode) code = e.keyCode;
					else if (e.which) code = e.which;

					// Handle Enter key
					if( code == 13 ){
						// Grab theForm's Buttons
						theFormEls = this.form.getElementsByTagName('button');

						// Fire first button's onclick
						if (theFormEls[0].tagName=='BUTTON'){
							theFormEls[0].onclick(); // Invoke the first button's onclick event
						}
						else return false; // Last resort: Prevent submit on enter
					}
                 };
                
            }		
	} // pixelchutes

}
}

if (window.addEventListener)
window.addEventListener("load", function() {fixIeButtonTagBug();}, false)
else if (window.attachEvent)
window.attachEvent("onload", function() {fixIeButtonTagBug();})
	