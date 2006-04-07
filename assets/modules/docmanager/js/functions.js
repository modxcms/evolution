// Javascript functions for use in the Document Manager
// Author: Garry Nutting/Mark Kaplan - some functions borrowed from other sources ;)
// Date: 24/03/2006 Version: 1

function getCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    } else {
        begin += 2;
    }
    var end = document.cookie.indexOf(";", begin);
    if (end == -1) {
        end = dc.length;
    }
    return unescape(dc.substring(begin + prefix.length, end));
}
		
function getElementsByClass(searchClass,node,tag) { 
	var classElements=''; 
	if ( node == null ) 
		node = document; 
	if ( tag == null ) 
		tag = '*'; 
	var els = node.getElementsByTagName(tag); 
	var elsLen = els.length; 
	var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)"); 
	for (i = 0; i < elsLen; i++) { 
	    if ( pattern.test(els[i].className) && els[i].checked==true) { 
				classElements += els[i].value + ","; 
		} 
	}
	return classElements; 
} 
	 
function getSelectedRadio(buttonGroup) { 
	// returns the array number of the selected radio button or -1 if no button is selected 
	if (buttonGroup[0]) { // if the button group is an array (one button is not an array) 
		for (var i=0; i<buttonGroup.length; i++) { 
	    	if (buttonGroup[i].checked) { 
	            return i 
	        } 
	    } 
	} else { 
	    if (buttonGroup.checked) { return 0; } // if the one button is checked, return zero 
	} 
	// if we get to this point, no radio button is selected 
	return -1; 
} // Ends the "getSelectedRadio" function 
 
function getSelectedRadioValue(buttonGroup) { 
	// returns the value of the selected radio button or "" if no button is selected 
	var i = getSelectedRadio(buttonGroup); 
	if (i == -1) { 
		return ""; 
	} else { 
	    if (buttonGroup[i]) { // Make sure the button group is an array (not just one button) 
	    	return buttonGroup[i].value; 
	    } else { // The button group is just the one button, and it is checked 
	         return buttonGroup.value; 
	    } 
	} 
} // Ends the "getSelectedRadioValue" function 
				
// handles most of the form processing 
function postForm(opcode) {
	tabActiveID = getCookie("webfxtab_docManagerPane");
if (tabActiveID == '0') { //Template tab
	if (opcode=='tree') { 
		
		document.module.opcode.value=opcode; 	
		document.module.tabAction.value='change_template';
        document.module.newvalue.value=getSelectedRadioValue(document.template.id); 
		document.module.pids.value=getElementsByClass('pids',document.subdiv,'input'); 
		document.module.submit(); 
	} else { 
	    document.range.tabAction.value='change_template';
		document.range.newvalue.value=getSelectedRadioValue(document.template.id); 
		document.range.submit(); 
	} 
} else if (tabActiveID == '1') { // Document tab
	if (opcode=='tree') { 
		document.module.opcode.value=opcode;
		if (getSelectedRadioValue(document.docgroups.tabAction)=='pushDocGroup' ) {
		document.module.tabAction.value='pushDocGroup';
		} else {
		document.module.tabAction.value='pullDocGroup';
		}
        document.module.newvalue.value=getSelectedRadioValue(document.template.id); 
		document.module.pids.value=getElementsByClass('pids',document.subdiv,'input'); 
		document.module.submit(); 
	} else {
	    if (getSelectedRadioValue(document.docgroups.tabAction)=='pushDocGroup' ) {
		document.range.tabAction.value='pushDocGroup';
		} else {
		document.range.tabAction.value='pullDocGroup';
		}
	    document.range.tabAction.value=getSelectedRadioValue(document.docgroups.tabAction);
		document.range.newvalue.value=getSelectedRadioValue(document.docgroups.docgroupid); 
		document.range.submit(); 
	} 
} else if (tabActiveID == '2') { // Sort Menu tab
		// handled separately using save() function
} else if (tabActiveID == '3') { // other options
		if (opcode=='tree') {
		document.module.opcode.value=opcode;
		document.module.tabAction.value='changeOther';
		// misc doc settings
		document.module.setoption.value=document.other.misc.value; 
        document.module.newvalue.value=getSelectedRadioValue(document.other.choice); 
		// document dates
		document.module.date_pubdate.value=document.dates.date_pubdate.value; 
		document.module.date_unpubdate.value=document.dates.date_unpubdate.value; 
		document.module.date_createdon.value=document.dates.date_createdon.value; 
		document.module.date_editedon.value=document.dates.date_editedon.value; 
		
		document.module.author_createdby.value=document.authors.author_createdby.value; 
		document.module.author_editedby.value=document.authors.author_editedby.value;
		
		document.module.pids.value=getElementsByClass('pids',document.subdiv,'input'); 
		document.module.submit(); 
	} else {
		document.range.tabAction.value='changeOther';
		// misc doc settings
		document.range.setoption.value=document.other.misc.value; 
        document.range.newvalue.value=getSelectedRadioValue(document.other.choice);
        // document dates
        document.range.date_pubdate.value=document.dates.date_pubdate.value; 
		document.range.date_unpubdate.value=document.dates.date_unpubdate.value; 
		document.range.date_createdon.value=document.dates.date_createdon.value; 
		document.range.date_editedon.value=document.dates.date_editedon.value;
		
		document.range.author_createdby.value=document.authors.author_createdby.value; 
		document.range.author_editedby.value=document.authors.author_editedby.value;
		
		document.range.submit(); 
	} 
}
}

// switches between 'range' and 'tree' view selections
function switchMenu(obj) {
	var el = document.getElementById(obj);
	if ( el.style.display != 'none' ) {
		el.style.display = 'none';
	}
	else {
		el.style.display = '';
	}
}

// Menu Index function
function reset() { 
	document.resetform.submit(); 
} 
					 
// for use in the 'Other' tab - sets the radio button options
function changeOtherLabels() {
   choice1 = document.getElementById('choice_label_1');
   choice2 = document.getElementById('choice_label_2');

   if (document.other.misc.value=='1') {
	   choice1.innerHTML = document.other.option1.value;
	   choice2.innerHTML = document.other.option2.value;
   } else if (document.other.misc.value=='2') {
   	   choice1.innerHTML = document.other.option3.value;
	   choice2.innerHTML = document.other.option4.value;
   } else if (document.other.misc.value=='3') {
   	   choice1.innerHTML = document.other.option5.value;
	   choice2.innerHTML = document.other.option6.value;
   } else if (document.other.misc.value=='4') {
   	   choice1.innerHTML = document.other.option7.value;
	   choice2.innerHTML = document.other.option8.value;
   } else if (document.other.misc.value=='5') {
   	   choice1.innerHTML = document.other.option9.value;
	   choice2.innerHTML = document.other.option10.value;
   } else if (document.other.misc.value=='0') {
   	   choice1.innerHTML = " - ";
	   choice2.innerHTML = " - ";
	}
}