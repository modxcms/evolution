/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: fck_1.js
 * 	This is the first part of the "FCK" object creation. This is the main
 * 	object that represents an editor instance.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

FCK.Events	= new FCKEvents( FCK ) ;
FCK.Toolbar	= null ;

FCK.TempBaseTag = FCKConfig.BaseHref.length > 0 ? '<base href="' + FCKConfig.BaseHref + '" _fcktemp="true"></base>' : '' ;

FCK.StartEditor = function()
{
	// Get the editor's window and document (DOM)
	this.EditorWindow	= window.frames[ 'eEditorArea' ] ;
	this.EditorDocument	= this.EditorWindow.document ;

	// TODO: Wait stable version and remove the following commented lines.
	// The Base Path of the editor is saved to rebuild relative URL (IE issue).
//	this.BaseUrl = this.EditorDocument.location.protocol + '//' + this.EditorDocument.location.host ;

//	if ( FCKBrowserInfo.IsGecko )
//		this.MakeEditable() ;

	// Set the editor's startup contents
	this.SetHTML( FCKTools.GetLinkedFieldValue() ) ;

	// Attach the editor to the form onsubmit event
	FCKTools.AttachToLinkedFieldFormSubmit( this.UpdateLinkedField ) ;

	FCKUndo.SaveUndoStep() ;

	this.SetStatus( FCK_STATUS_ACTIVE ) ;
}

function Window_OnFocus()
{
	FCK.Focus() ;
}

FCK.SetStatus = function( newStatus )
{
	this.Status = newStatus ;

	if ( newStatus == FCK_STATUS_ACTIVE )
	{
		// Force the focus in the window to go to the editor.
		window.onfocus = window.document.body.onfocus = Window_OnFocus ;

		// Force the focus in the editor.
		if ( FCKConfig.StartupFocus )
			FCK.Focus() ;

		// @Packager.Compactor.Remove.Start
		var sBrowserSuffix = FCKBrowserInfo.IsIE ? "ie" : "gecko" ;

		FCKScriptLoader.AddScript( '_source/internals/fck_2.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fck_2_' + sBrowserSuffix + '.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fckselection.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fckselection_' + sBrowserSuffix + '.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fckpanel_' + sBrowserSuffix + '.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fcktablehandler.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fcktablehandler_' + sBrowserSuffix + '.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fckxml_' + sBrowserSuffix + '.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fckstyledef.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fckstyledef_' + sBrowserSuffix + '.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fckstylesloader.js' ) ;

		FCKScriptLoader.AddScript( '_source/commandclasses/fcknamedcommand.js' ) ;
		FCKScriptLoader.AddScript( '_source/commandclasses/fck_othercommands.js' ) ;
		FCKScriptLoader.AddScript( '_source/commandclasses/fckspellcheckcommand_' + sBrowserSuffix + '.js' ) ;
		FCKScriptLoader.AddScript( '_source/commandclasses/fcktextcolorcommand.js' ) ;
		FCKScriptLoader.AddScript( '_source/commandclasses/fckpasteplaintextcommand.js' ) ;
		FCKScriptLoader.AddScript( '_source/commandclasses/fckpastewordcommand.js' ) ;
		FCKScriptLoader.AddScript( '_source/commandclasses/fcktablecommand.js' ) ;
		FCKScriptLoader.AddScript( '_source/commandclasses/fckstylecommand.js' ) ;

		FCKScriptLoader.AddScript( '_source/internals/fckcommands.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fcktoolbarbutton.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fckspecialcombo.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fcktoolbarspecialcombo.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fcktoolbarfontscombo.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fcktoolbarfontsizecombo.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fcktoolbarfontformatcombo.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fcktoolbarstylecombo.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fcktoolbarpanelbutton.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fcktoolbaritems.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fcktoolbar.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fcktoolbarbreak_' + sBrowserSuffix + '.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fcktoolbarset.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fckdialog.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fckdialog_' + sBrowserSuffix + '.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fckcontextmenuitem.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fckcontextmenuseparator.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fckcontextmenugroup.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fckcontextmenu.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fckcontextmenu_' + sBrowserSuffix + '.js' ) ;
		FCKScriptLoader.AddScript( '_source/classes/fckplugin.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fckplugins.js' ) ;
		FCKScriptLoader.AddScript( '_source/internals/fck_last.js' ) ;
		// @Packager.Compactor.Remove.End

		/* @Packager.Compactor.RemoveLine

		if ( FCKBrowserInfo.IsIE )
			FCKScriptLoader.AddScript( 'js/fckeditorcode_ie_2.js' ) ;
		else
			FCKScriptLoader.AddScript( 'js/fckeditorcode_gecko_2.js' ) ;

		@Packager.Compactor.RemoveLine */
	}

	this.Events.FireEvent( 'OnStatusChange', newStatus ) ;
}

FCK.GetHTML = function( format )
{
	var sHTML ;

	if ( FCK.EditMode == FCK_EDITMODE_WYSIWYG )
	{
		// TODO: Wait stable version and remove the following commented lines.
//		if ( FCKBrowserInfo.IsIE )
//			FCK.CheckRelativeLinks() ;

		if ( FCKBrowserInfo.IsIE )
			sHTML = this.EditorDocument.body.innerHTML.replace( FCKRegexLib.ToReplace, '$1' ) ;
		else
			sHTML = this.EditorDocument.body.innerHTML ;
	}
	else
		sHTML = document.getElementById('eSourceField').value ;

	if ( format )
		return FCKCodeFormatter.Format( sHTML ) ;
	else
		return sHTML ;
}

FCK.GetXHTML = function( format )
{
	var bSource = ( FCK.EditMode == FCK_EDITMODE_SOURCE ) ;

	if ( bSource )
		this.SwitchEditMode() ;

	// TODO: Wait stable version and remove the following commented lines.
//	if ( FCKBrowserInfo.IsIE )
//		FCK.CheckRelativeLinks() ;

	if ( FCKConfig.FullPage )
		var sXHTML = FCKXHtml.GetXHTML( this.EditorDocument.getElementsByTagName( 'html' )[0], true, format ) ;
	else
		var sXHTML = FCKXHtml.GetXHTML( this.EditorDocument.body, false, format ) ;

	if ( bSource )
		this.SwitchEditMode() ;

	if ( FCKBrowserInfo.IsIE )
		sXHTML = sXHTML.replace( FCKRegexLib.ToReplace, '$1' ) ;

	if ( FCK.DocTypeDeclaration && FCK.DocTypeDeclaration.length > 0 )
		sXHTML = FCK.DocTypeDeclaration + '\n' + sXHTML ;

	if ( FCK.XmlDeclaration && FCK.XmlDeclaration.length > 0 )
		sXHTML = FCK.XmlDeclaration + '\n' + sXHTML ;

	return sXHTML ;
}

FCK.UpdateLinkedField = function()
{
	if ( FCKConfig.EnableXHTML )
		FCKTools.SetLinkedFieldValue( FCK.GetXHTML( FCKConfig.FormatOutput ) ) ;
	else
		FCKTools.SetLinkedFieldValue( FCK.GetHTML( FCKConfig.FormatOutput ) ) ;
}

FCK.ShowContextMenu = function( x, y )
{
	if ( this.Status != FCK_STATUS_COMPLETE )
		return ;

	FCKContextMenu.Show( x, y ) ;
	this.Events.FireEvent( "OnContextMenu" ) ;
}

FCK.RegisteredDoubleClickHandlers = new Object() ;

FCK.OnDoubleClick = function( element )
{
	var oHandler = FCK.RegisteredDoubleClickHandlers[ element.tagName ] ;
	if ( oHandler )
		oHandler( element ) ;
}

// Register objects that can handle double click operations.
FCK.RegisterDoubleClickHandler = function( handlerFunction, tag )
{
	FCK.RegisteredDoubleClickHandlers[ tag.toUpperCase() ] = handlerFunction ;
}
