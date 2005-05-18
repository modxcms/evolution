/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2004 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: fcktoolbarcombo.js
 * 	FCKToolbarCombo Class: represents a combo in the toolbar.
 * 
 * Version:  2.0 RC3
 * Modified: 2004-11-10 17:14:48
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

var FCKToolbarCombo = function( commandName, label, itemsValues, itemsNames, tooltip, style, firstIsBlank, itemsSeparator, sourceView )
{
	this.Command	= FCKCommands.GetCommand( commandName ) ;
	
	this.Label		= label ? label : commandName ;
	this.Tooltip	= tooltip ? tooltip : ( label ? label : commandName) ;
	this.Style		= style ? style : FCK_TOOLBARITEM_ICONTEXT ;
	this.SourceView	= sourceView ? true : false ;
	this.State		= FCK_UNKNOWN ;
	
	this.ItemsValues	= itemsValues ;
	this.ItemsNames		= itemsNames ? itemsNames : itemsValues ;
	this.ItemsSeparator	= itemsSeparator ? itemsSeparator : ';' ;
	
	this.FirstIsBlank	= firstIsBlank != null ? firstIsBlank : true ;
}

FCKToolbarCombo.prototype.CreateInstance = function( parentToolbar )
{
/*
	<td class="TB_Combo_Disabled" unselectable="on">
		<table class="ButtonType_IconText" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td class="TB_Text" unselectable="on">Style</td>
				<td><select title="Style"><option>Style 1</option><option>Style 2</option></select></td>
			</tr>
		</table>
	</td>
*/	
	this.DOMDiv = document.createElement( 'div' ) ;
	this.DOMDiv.className		= 'TB_Combo_Off' ;

	// Gets the correct CSS class to use for the specified style (param).
	var sClass ;
	switch ( this.Style )
	{
		case FCK_TOOLBARITEM_ONLYICON :
			sClass = 'TB_ButtonType_Icon' ;
			break ;
		case FCK_TOOLBARITEM_ONLYTEXT :
			sClass = 'TB_ButtonType_Text' ;
			break ;
		case FCK_TOOLBARITEM_ICONTEXT :
			sClass = '' ;
			break ;
	}

	this.DOMDiv.innerHTML = 
		'<table class="' + sClass + '" cellspacing="0" cellpadding="0" border="0" unselectable="on">' +
			'<tr>' +
				'<td class="TB_Text" unselectable="on" nowrap>' + this.Label + '</td>' +
				'<td unselectable="on"><select title="' + this.Tooltip + '"></select></td>' +
			'</tr>' +
		'</table>' ;

	// Gets the SELECT element.
	this.SelectElement = this.DOMDiv.firstChild.firstChild.firstChild.childNodes.item(1).firstChild ;
	
	this.SelectElement.FCKToolbarCombo = this ;

	this.SelectElement.onchange = function()
	{
		this.FCKToolbarCombo.Command.Execute( this.value ) ;
		return false ;
	}

	var oCell = parentToolbar.DOMRow.insertCell(-1) ;
	oCell.appendChild( this.DOMDiv ) ;

	// Loads all combo items.
	this.RefreshItems() ;
	
	// Sets its initial state (probably disabled).
	this.RefreshState() ;
}

FCKToolbarCombo.prototype.RefreshItems = function()
{
	// Create the empty arrays of items to add (names and values)
	var aNames	= FCKTools.GetResultingArray( this.ItemsNames, this.ItemsSeparator ) ;
	var aValues	= FCKTools.GetResultingArray( this.ItemsValues, this.ItemsSeparator ) ;
	
	// Clean up the combo.
	FCKTools.RemoveAllSelectOptions( this.SelectElement ) ;
	
	// Verifies if the first item in the combo must be blank.
	if ( this.FirstIsBlank )
		FCKTools.AddSelectOption( document, this.SelectElement, '', '' ) ;
	
	// Add all items to the combo.
	for ( var i = 0 ; i < aValues.length ; i++ )
	{
		FCKTools.AddSelectOption( document, this.SelectElement, aNames[i], aValues[i] ) ;
	}
}

FCKToolbarCombo.prototype.RefreshState = function()
{
	// Gets the actual state.
	var eState ;
	
	if ( FCK.EditMode == FCK_EDITMODE_SOURCE && ! this.SourceView )
	{
		eState = FCK_TRISTATE_DISABLED ;
		
		// Cleans the actual selection.
		this.SelectElement.value = '' ;
	}
	else
	{
		var sValue = this.Command.GetState() ;

		// Sets the combo value.
		FCKTools.SelectNoCase( this.SelectElement, sValue ? sValue : '', '' ) ;

		// Gets the actual state.
		eState = sValue == null ? FCK_TRISTATE_DISABLED : FCK_TRISTATE_ON ;
	}

	// If there are no state changes then do nothing and return.
	if ( eState == this.State ) return ;
	
	// Sets the actual state.
	this.State = eState ;

	// Updates the graphical state.	
	this.DOMDiv.className		= ( eState == FCK_TRISTATE_ON ? 'TB_Combo_Off' : 'TB_Combo_Disabled' ) ;
	this.SelectElement.disabled	= ( eState == FCK_TRISTATE_DISABLED ) ;	
}

