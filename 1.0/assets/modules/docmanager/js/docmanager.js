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

function getSelectedRadio(buttonGroup) { 
    if (buttonGroup[0]) {
        for (var i=0; i<buttonGroup.length; i++) { 
            if (buttonGroup[i].checked) { 
                return i; 
            } 
        } 
    } else { 
        if (buttonGroup.checked) {
            return 0;
        }
    } 
    return -1; 
}

function getSelectedRadioValue(buttonGroup) { 
    var i = getSelectedRadio(buttonGroup); 
    if (i == -1) { 
        return ''; 
    } else { 
        if (buttonGroup[i]) {
            return buttonGroup[i].value; 
        } else {
             return buttonGroup.value; 
        } 
    } 
}

function changeOtherLabels() {
   var choice1 = $('choice_label_1');
   var choice2 = $('choice_label_2');

   if ($('misc').value=='1') {
       choice1.innerHTML = $('option1').value;
       choice2.innerHTML = $('option2').value;
   } else if ($('misc').value=='2') {
       choice1.innerHTML = $('option3').value;
       choice2.innerHTML = $('option4').value;
   } else if ($('misc').value=='3') {
       choice1.innerHTML = $('option5').value;
       choice2.innerHTML = $('option6').value;
   } else if ($('misc').value=='4') {
       choice1.innerHTML = $('option7').value;
       choice2.innerHTML = $('option8').value;
   } else if ($('misc').value=='5') {
       choice1.innerHTML = $('option9').value;
       choice2.innerHTML = $('option10').value;
   } else if ($('misc').value=='6') {
       choice1.innerHTML = $('option11').value;
       choice2.innerHTML = $('option12').value;
   } else if ($('misc').value=='0') {
       choice1.innerHTML = " - ";
       choice2.innerHTML = " - ";
    }
}

function postForm() {
    var tabActiveID = getCookie("webfxtab_docManagerPane");
	
	if (tabActiveID == '0' || tabActiveID == null) {
		$('tabaction').value = 'changeTemplate';
		$('newvalue').value = getSelectedRadioValue(document.template.id); 
		
		document.range.submit(); 
	} else if (tabActiveID == '1') {
	    $('pids_tv').value = $('pids').value;
	    $('template_id').value = getSelectedRadioValue(document.templatevariables.tid);
	    
	    document.templatevariables.submit();
	} else if (tabActiveID == '2') {		
		$('tabaction').value = getSelectedRadioValue(document.docgroups.tabAction);
		$('newvalue').value = getSelectedRadioValue(document.docgroups.docgroupid); 
		
		document.range.submit(); 
	} else if (tabActiveID == '3') {
	   /* handled separately using save() function */
	} else if (tabActiveID == '4') {	
		$('tabaction').value = 'changeOther';

		$('setoption').value = document.other.misc.value; 
		$('newvalue').value = getSelectedRadioValue(document.other.choice);

		$('pubdate').value = document.dates.date_pubdate.value; 
		$('unpubdate').value = document.dates.date_unpubdate.value; 
		$('createdon').value = document.dates.date_createdon.value; 
		$('editedon').value = document.dates.date_editedon.value;
		
		$('author_createdby').value = document.authors.author_createdby.value; 
		$('author_editedby').value = document.authors.author_editedby.value;
		
		document.range.submit(); 
    }
}

function hideInteraction() {
    var tabActiveID = getCookie("webfxtab_docManagerPane");
    if (tabActiveID == '1') { 
        $('tvloading').style.display = 'none';
    }
    if (tabActiveID == '3') {
        if ($('interaction')) {
            $('interaction').style.display = 'none';
        }
        parent.tree.ca = 'move';
    } else {
        $('interaction').style.display = '';
        parent.tree.ca = '';
    }
    
    return true;
}

window.addEvent('domready', hideInteraction);
document.addEvent('click', hideInteraction);