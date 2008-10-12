//This code come from http://www.yourhtmlsource.com/forms/clearingdefaulttext.html
//Description: when the user clicks on the box, the default text is wiped away so
//that they can begin typing. If they click away from the box, without typing anything in, 
//we will add the default text back so that they don’t forget what was meant to be typed
// The input text should have the class “cleardefault”


function addEventAS(element, eventType, lamdaFunction, useCapture) {
  if (element.addEventListener) {
    element.addEventListener(eventType, lamdaFunction, useCapture);
    return true;
  } else if (element.attachEvent) {
    var r = element.attachEvent('on' + eventType, lamdaFunction);
    return r;
  } else {
    return false;
  }
}

addEventAS(window, 'load', clearFormFields, false);	
	
function clearFormFields() {
    var formInputs = document.getElementsByTagName('input');
    for (var i = 0; i < formInputs.length; i++) {
        var theInput = formInputs[i];
        
        if (theInput.type == 'text' && theInput.className.match(/\bcleardefault\b/)) {  
            /* Add event handlers */          
            addEventAS(theInput, 'focus', clearDefaultText, false);
            addEventAS(theInput, 'blur', replaceDefaultText, false);
            /* Save the current value */
            if (theInput.value != '') {
                theInput.defaultText = theInput.value;
            }
        }
    }
}

function clearDefaultText(e) {
    var target = window.event ? window.event.srcElement : e ? e.target : null;
    if (!target) return;
    
    if (target.value == target.defaultText) {
        target.value = '';
    }
}

function replaceDefaultText(e) {
    var target = window.event ? window.event.srcElement : e ? e.target : null;
    if (!target) return;
    
    if (target.value == '' && target.defaultText) {
        target.value = target.defaultText;
    }
}
