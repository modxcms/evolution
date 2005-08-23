/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  For: MODx cms (modxcms.com)
 *  Description: Javascript for QuickEdit links
 */

var parentClassName = 'QuickEditParent';

// Opens the frontend editor pop-up window
function openEditor(pageId, contentId) {
 var settings = 'width=525, height=300, toolbar=0, menubar=0, status=0, alwaysRaised=1, dependent=1, resizable=1';
 var url = managerPath+'index.php?a=112&id='+modId+'&doc='+pageId+'&var='+contentId;
 var name = 'QuickEditor';
 window.open(url, name, settings);
}

// Assign a new class to the parent
function highlightContent(editLink) {
 editLink.parentNode.className += ' '+parentClassName;
}

// Remove the class from the parent
function unhighlightContent(editLink) {
 var parent = editLink.parentNode;
 var match = new RegExp(parentClassName);
 parent.className = parent.className.replace(match, '');
}
