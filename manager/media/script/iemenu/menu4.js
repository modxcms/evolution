/*----------------------------------------------------------------------------\
|                             DHTML Menu 4.28                                 |
|-----------------------------------------------------------------------------|
|                         Created by Erik Arvidsson                           |
|                  (http://webfx.eae.net/contact.html#erik)                   |
|                      For WebFX (http://webfx.eae.net/)                      |
|-----------------------------------------------------------------------------|
| A menu system for Internet Explorer 5.5+ Win32 that allows menus to extend  |
| outside the browser window limits.                                          |
|-----------------------------------------------------------------------------|
|                  Copyright (c) 1999 - 2003 Erik Arvidsson                   |
|-----------------------------------------------------------------------------|
| This software is provided "as is", without warranty of any kind, express or |
| implied, including  but not limited  to the warranties of  merchantability, |
| fitness for a particular purpose and noninfringement. In no event shall the |
| authors or  copyright  holders be  liable for any claim,  damages or  other |
| liability, whether  in an  action of  contract, tort  or otherwise, arising |
| from,  out of  or in  connection with  the software or  the  use  or  other |
| dealings in the software.                                                   |
| - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - |
| This  software is  available under the  three different licenses  mentioned |
| below.  To use this software you must chose, and qualify, for one of those. |
| - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - |
| The WebFX Non-Commercial License          http://webfx.eae.net/license.html |
| Permits  anyone the right to use the  software in a  non-commercial context |
| free of charge.                                                             |
| - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - |
| The WebFX Commercial license           http://webfx.eae.net/commercial.html |
| Permits the  license holder the right to use  the software in a  commercial |
| context. Such license must be specifically obtained, however it's valid for |
| any number of  implementations of the licensed software.                    |
| - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - |
| GPL - The GNU General Public License    http://www.gnu.org/licenses/gpl.txt |
| Permits anyone the right to use and modify the software without limitations |
| as long as proper  credits are given  and the original  and modified source |
| code are included. Requires  that the final product, software derivate from |
| the original  source or any  software  utilizing a GPL  component, such  as |
| this, is also licensed under the GPL license.                               |
|-----------------------------------------------------------------------------|
| 2002-05-28 | First version                                                  |
| 2002-06-07 | Updated default cssFile value to "skins/winclassic.css"        |
|            | instead of "winclassic.css"                                    |
| 2002-06-10 | (4.1) Lots of changes. Rewrote measuring and positioning       |
|            | routines to prevent screen flicker. As well as general code    |
|            | optimization.                                                  |
| 2002-07-03 | getInsetRight and getInsetBottom broke in the last update.     |
|            | Radio and Check box did not check disabled state correctly.    |
| 2002-07-25 | Created a work around for a weird bug that did not show first  |
|            | menu. Disabled browser keyboard shortcuts when menus are open. |
|            | Added workaround for buggy dual monitor drivers.               |
| 2002-09-05 | Fixed cases where the caching of the CSS failed and caused the |
|            | cached menu size to be incorrect.                              |
| 2002-09-05 | Insets were ignored for vertical menus.                        |
| 2002-09-06 | Some properties have been moved to the prototype to make	      |
|            | customizing easier.                                            |
| 2002-09-24 | Minor changes to prevent size errors.                          |
| 2002-10-22 | Added second argument to Menu add.                             |
|            | Added support for Menu cssText.                                |
| 2002-10-29 | (4.2) Lots of work to work around IE memory bugs.              |
| 2002-11-03 | Typo in MenuBar goToNextMenuItem                               |
| 2002-11-23 | The height and width were not correctly limited in show.       |
| 2002-12-04 | Changed to use onunload instead of onbeforeunload.             |
|            | Onbeforeunload was causing troubles with certain links.        |
| 2003-03-07 | Fixed bug in MenuButton toHtml and added MenuBar invalidate    |
|            | also created a clone extension (menu4.clone.js)                |
| 2003-04-01 | added document arguments to MenuBar create and write.          |
|            | Better mnemonic handling when HTML is used                     |
|            | onclose, onshow and onbeforeshow                               |
| 2003-09-12 | Updated mnemonic code and fixed an itemIndex bug when adding   |
|            | items.                                                         |
| 2003-09-23 | The scrollbutton.js still used onbeforeunload                  |
| 2003-10-15 | Add support for keyboardAccelKey2 (defaults to F10). Also      |
|            | fixed so that Esc on last menu correctly goes to the menu bar. |
| 2003-11-24 | Changed the MenuButton constructor to not fail if sub menu is  |
|            | left out. This allows you to set the sub menu later. A sub     |
|            | menu is still needed!                                          |
|-----------------------------------------------------------------------------|
| Dependencies: poslib.js       Used to find positions of elements            |
|               scrollbutton.js	Used for the buttnos that allows the menu     |
|                               to be scrollable                              |
|-----------------------------------------------------------------------------|
| Created 2002-05-28 | All changes are in the log above. | Updated 2003-11-24 |
\----------------------------------------------------------------------------*/


////////////////////////////////////////////////////////////////////////////////////
// menuCache
//

var menuCache = {
	_count:		0,
	_idPrefix:	"-menu-cache-",

	getId:	function () {
		return this._idPrefix + this._count++;
	},

	remove:	function ( o ) {
		delete this[ o.id ];
	}
};

////////////////////////////////////////////////////////////////////////////////////
// Menu
//

function Menu() {
	this.items = [];
	this.parentMenu = null;
	this.parentMenuItem = null;
	this.popup = null;
	this.shownSubMenu = null;
	this._aboutToShowSubMenu = false;

	this.selectedIndex = -1;
	this._drawn = false;
	this._scrollingMode = false;
	this._showTimer = null;
	this._closeTimer = null;

	this._onCloseInterval = null;
	this._closed = true;
	this._closedAt = 0;

	this._cachedSizes = {};
	this._measureInvalid = true;

	this.id = menuCache.getId();
	menuCache[ this.id ] = this;
}

Menu.prototype.cssFile = "skins/winclassic.css";
Menu.prototype.cssText = null;
Menu.prototype.mouseHoverDisabled = true;
Menu.prototype.showTimeout = 250;
Menu.prototype.closeTimeout = 250;

Menu.keyboardAccelKey = 27;				// the keyCode for the key tp activate
Menu.keyboardAccelKey2 = 121;			// the menubar
Menu.keyboardAccelProperty = "ctrlKey";	// when this property is true default
										// actions will be canceled on a menu
// Use -1 to disable keyboard invoke of the menubar
// Use "" to allow all normal keyboard commands inside the menus

Menu.prototype.add = function ( mi, beforeMi ) {
	if ( beforeMi != null ) {
		var items = this.items;
		var l = items.length;
		var i = 0;
		for ( ; i < l; i++ ) {
			if ( items[i] == beforeMi )
				break;
		}
		this.items = items.slice( 0, i ).concat( mi ).concat( items.slice( i, l ) );
		// update itemIndex
		for (var j = i; j < l + 1; j++)
			this.items[j].itemIndex = j;
	}
	else {
		this.items.push( mi );
		mi.itemIndex = this.items.length - 1;
	}

	mi.parentMenu = this;
	if ( mi.subMenu ) {
		mi.subMenu.parentMenu = this;
		mi.subMenu.parentMenuItem = mi;
	}
	return mi;
};

Menu.prototype.remove = function ( mi ) {
	var res = [];
	var items = this.items;
	var l = items.length;
	for (var i = 0; i < l; i++) {
		if ( items[i] != mi ) {
			res.push( items[i] );
			items[i].itemIndex = res.length - 1;
		}
	}
	this.items = res;
	mi.parentMenu = null;
	return mi;
};



Menu.prototype.toHtml = function () {

	var items = this.items;
	var l = items.length
	var itemsHtml = new Array( l );
	for (var i = 0; i < l; i++)
		itemsHtml[i] = items[i].toHtml();

	return  "<html><head>" +
			(this.cssText == null ?
				"<link type=\"text/css\" rel=\"StyleSheet\" href=\"" + this.cssFile + "\" />" :
				"<style type=\"text/css\">" + this.cssText + "</style>") +
			"</head><body class=\"menu-body\">" +
			"<div class=\"outer-border\"><div class=\"inner-border\">" +
			"<table id=\"scroll-up-item\" cellspacing=\"0\" style=\"display: none\">" +
			"<tr class=\"disabled\"><td>" +
			"<span class=\"disabled-container\"><span class=\"disabled-container\">" +
			"5" +
			"</span></span>" + "</td></tr></table>" +
			"<div id=\"scroll-container\">" +
			"<table cellspacing=\"0\">" +

			itemsHtml.join( "" ) +

			"</table>" +
			"</div>" +
			"<table id=\"scroll-down-item\" cellspacing=\"0\" style=\"display: none\">" +
			"<tr><td>" +
			"<span class=\"disabled-container\"><span class=\"disabled-container\">" +
			"6" +
			"</span></span>" +
			"</td></tr></table>" +
			"</div></div>" +
			"</body></html>";
};


Menu.prototype.createPopup = function () {
	var w;
	var pm = this.parentMenu;
	if ( pm == null )
		w = window;
	else
		w = pm.getDocument().parentWindow;

	this.popup = w.createPopup();
};

Menu.prototype.getMeasureDocument = function () {

	if ( this.isShown() && this._drawn )
		return this.getDocument();

	var mf = Menu._measureFrame;
	if ( mf == null ) {
		// should be top document
		mf = Menu._measureFrame = document.createElement("IFRAME");
		var mfs = mf.style;
		mfs.position = "absolute";
		mfs.visibility = "hidden";
		mfs.left = "-100px";
		mfs.top = "-100px";
		mfs.width = "10px";
		mfs.height = "10px";
		mf.frameBorder = 0;
		document.body.appendChild( mf );
	}

	var d = mf.contentWindow.document

	if ( Menu._measureMenu == this && !this._measureInvalid )
		return d;

	d.open( "text/html", "replace" );
	d.write( this.toHtml() );
	d.close();

	Menu._measureMenu = this;
	this._measureInvalid = false;

	return d;
};

Menu.prototype.getDocument = function () {
	if ( this.popup )
		return this.popup.document;
	else
		return null;
};

Menu.prototype.getPopup = function () {
	if ( this.popup == null )
		this.createPopup();
	return this.popup;
};

Menu.prototype.invalidate = function () {
	if ( this._drawn ) {
		// do some memory cleanup
		if ( this._scrollUpButton )
			this._scrollUpButton.destroy();
		if ( this._scrollDownButton )
			this._scrollDownButton.destroy();

		var items = this.items;
		var l = items.length;
		var mi;
		for ( var i = 0; i < l; i++ ) {
			mi = items[i];
			mi._htmlElement_menuItem = null;
			mi._htmlElement = null;
		}

		this.detachEvents();
	}
	this._drawn = false;
	this.resetSizeCache();
	this._measureInvalid = true;
};

Menu.prototype.redrawMenu = function () {
	this.invalidate();
	this.drawMenu();
};

Menu.prototype.drawMenu = function () {

	if ( this._drawn ) return;

	this.getPopup();

	var d = this.getDocument();
	d.open( "text/html", "replace" );
	d.write( this.toHtml() );
	d.close();
	this._drawn = true;

	// set up scroll buttons
	var up = d.getElementById( "scroll-up-item" );
	var down = d.getElementById( "scroll-down-item" );
	var scrollContainer = d.getElementById( "scroll-container" );
	new ScrollButton( up, scrollContainer, 8 );
	new ScrollButton( down, scrollContainer, 2 );

	// bind menu items to the table rows
	var rows = scrollContainer.firstChild.tBodies[0].rows;
	var items = this.items;
	var l = rows.length;
	var mi;
	for ( var i = 0; i < l; i++ ) {
		mi = items[i];
		rows[i]._menuItem = mi;
		mi._htmlElement = rows[i];
	}

	// hook up mouse
	this.hookupMenu( d );
};

Menu.prototype.show = function ( left, top, w, h ) {

	var pm = this.parentMenu;
	if ( pm )
		pm.closeAllSubs( this );

	var wasShown = this.isShown();

	if ( typeof this.onbeforeshow == "function" && !wasShown )
		this.onbeforeshow();

	this.drawMenu();

	if ( left == null ) left = 0;
	if ( top == null ) top = 0;
	w = w || Math.min( window.screen.width, this.getPreferredWidth() );
	h = h || Math.min( window.screen.height, this.getPreferredHeight() );

	this.popup.show( left, top, w, h );

	// work around a bug that sometimes occured with large pages when
	// opening the first menu
	if ( this.getPreferredWidth() == 0 ) {
		this.invalidate();
		this.show( left, top, w, h );
		return;
	}

	this.fixScrollButtons();
	this.fixScrollEnabledState();

	// clear selected item
	if ( this.selectedIndex != -1 ) {
		if ( this.items[ this.selectedIndex ] )
			this.items[ this.selectedIndex ].setSelected( false );
	}

	if ( pm ) {
		pm.shownSubMenu = this;
		pm._aboutToShowSubMenu = false;
	}

	window.clearTimeout( this._showTimer );
	window.clearTimeout( this._closeTimer );

	this._closed = false;
	this._startClosePoll();

	if ( typeof this.onshow == "function" && !wasShown && this.isShown() )
		this.onshow();
};

Menu.prototype.isShown = function () {
	this._checkCloseState();
	return this.popup != null && this.popup.isOpen;
};

Menu.prototype.fixSize = function () {
	var w = Math.min( window.screen.width, this.getPreferredWidth() );
	var h = Math.min( window.screen.height, this.getPreferredHeight() );
	var l = Math.max( 0, this.getLeft() );
	var t = Math.max( 0, this.getTop() );

	this.popup.show( l, t, w, h );
};

Menu.prototype.getWidth = function () {
	var d = this.getDocument();
	if ( d != null )
		return d.body.offsetWidth;
	else
		return 0;
};

Menu.prototype.getHeight = function () {
	var d = this.getDocument();
	if ( d != null )
		return d.body.offsetHeight;
	else
		return 0;
};

Menu.prototype.getPreferredWidth = function () {
	this.updateSizeCache();
	return this._cachedSizes.preferredWidth;
};

Menu.prototype.getPreferredHeight = function () {
	this.updateSizeCache();
	return this._cachedSizes.preferredHeight;
};

Menu.prototype.getLeft = function () {
	var d = this.getDocument();
	if ( d != null )
		return d.parentWindow.screenLeft;
	else
		return 0;
};

Menu.prototype.getTop = function () {
	var d = this.getDocument();
	if ( d != null )
		return d.parentWindow.screenTop;
	else
		return 0;
};


// Depreciated. Use show instead
Menu.prototype.setLeft = function ( l ) {
	throw new Error("Depreciated. Use show instead");
	//var t = this.getTop();
	//this.setLocation( l, t );
};

// Depreciated. Use show instead
Menu.prototype.setTop = function ( t ) {
	throw new Error("Depreciated. Use show instead");
	//var l = this.getLeft();
	//this.setLocation( l, t );
};

// Depreciated. Use show instead
Menu.prototype.setLocation = function ( l, t ) {
	throw new Error("Depreciated. Use show instead");
	//var w = this.getWidth();
	//var h = this.getHeight();
	//this.popup.show( l, t, w, h );
};

// Depreciated. Use show instead
Menu.prototype.setRect = function ( l, t, w, h ) {
	throw new Error("Depreciated. Use show instead");
	//this.popup.show( l, t, w, h );
};

Menu.prototype.getInsetLeft = function () {
	this.updateSizeCache();
	return this._cachedSizes.insetLeft;
};

Menu.prototype.getInsetRight = function () {
	this.updateSizeCache();
	return this._cachedSizes.insetRight;
};

Menu.prototype.getInsetTop = function () {
	this.updateSizeCache();
	return this._cachedSizes.insetTop;
};

Menu.prototype.getInsetBottom = function () {
	this.updateSizeCache();
	return this._cachedSizes.insetBottom;
};


Menu.prototype.areSizesCached = function () {
	var cs = this._cachedSizes;
	return this._drawn &&
		"preferredWidth" in cs &&
		"preferredHeight" in cs &&
		"insetLeft" in cs &&
		"insetRight" in cs &&
		"insetTop" in cs &&
		"insetBottom" in cs;
};





// depreciated
Menu.prototype.cacheSizes = function ( bForce ) {
	return updateSizeCache( bForce );
};

Menu.prototype.resetSizeCache = function () {
	this._cachedSizes = {};
};

Menu.prototype.updateSizeCache = function ( bForce ) {
	if ( this.areSizesCached() && !bForce )
		return;

	var d = this.getMeasureDocument();
	var body = d.body;

	var cs = this._cachedSizes = {};	// reset
	var scrollContainer = d.getElementById( "scroll-container" );

	// preferred width
	cs.preferredWidth = d.body.scrollWidth;

	// preferred height
	scrollContainer.style.overflow = "visible";
	cs.preferredHeight = body.firstChild.offsetHeight; //body.scrollHeight;
	scrollContainer.style.overflow = "hidden";

	// inset left
	cs.insetLeft = posLib.getLeft( scrollContainer );

	// inset right
	cs.insetRight = body.scrollWidth - posLib.getLeft( scrollContainer ) -
					scrollContainer.offsetWidth;

	// inset top
	var up = d.getElementById( "scroll-up-item" );
	if ( up.currentStyle.display == "none" )
		cs.insetTop = posLib.getTop( scrollContainer );
	else
		cs.insetTop = posLib.getTop( up );

	// inset bottom
	var down = d.getElementById( "scroll-down-item" );
	if ( down.currentStyle.display == "none" ) {
		cs.insetBottom = body.scrollHeight - posLib.getTop( scrollContainer ) -
						scrollContainer.offsetHeight;
	}
	else {
		cs.insetBottom = body.scrollHeight - posLib.getTop( down ) -
						down.offsetHeight;
	}
};




Menu.prototype.fixScrollButtons = function () {
	var d = this.getDocument();
	var up = d.getElementById( "scroll-up-item" );
	var down = d.getElementById( "scroll-down-item" );
	var scrollContainer = d.getElementById( "scroll-container" );
	var scs = scrollContainer.style;

	if ( scrollContainer.scrollHeight > this.getHeight() ) {

		up.style.display = "";
		down.style.display = "";

		scs.height = "";
		scs.overflow = "visible";
		scs.height = Math.max( 0, this.getHeight() -
			( d.body.scrollHeight - scrollContainer.offsetHeight ) ) + "px";
		scs.overflow = "hidden";

		this._scrollingMode = true;
	}
	else {
		up.style.display = "none";
		down.style.display = "none";
		scs.overflow = "visible";
		scs.height = "";

		this._scrollingMode = false;
	}
};

Menu.prototype.fixScrollEnabledState = function () {
	var d = this.getDocument();
	var up = d.getElementById( "scroll-up-item" );
	var down = d.getElementById( "scroll-down-item" );
	var scrollContainer = d.getElementById( "scroll-container" );
	var tr;

	tr = up.rows[0];
	if ( scrollContainer.scrollTop == 0 ) {
		if ( tr.className == "hover" || tr.className == "disabled-hover" )
			tr.className = "disabled-hover";
		else
			tr.className = "disabled";
	}
	else {
		if ( tr.className == "disabled-hover" || tr.className == "hover" )
			tr.className = "hover";
		else
			tr.className = "";
	}

	tr = down.rows[0];
	if ( scrollContainer.scrollHeight - scrollContainer.clientHeight <=
												scrollContainer.scrollTop ) {

		if ( tr.className == "hover" || tr.className == "disabled-hover" )
			tr.className = "disabled-hover";
		else
			tr.className = "disabled";
	}
	else {
		if ( tr.className == "disabled-hover" || tr.className == "hover" )
			tr.className = "hover";
		else
			tr.className = "";
	}
};

Menu.prototype.closeAllMenus = function () {
	if ( this.parentMenu )
		this.parentMenu.closeAllMenus();
	else
		this.close();
};

Menu.prototype.close = function () {
	this.closeAllSubs();

	window.clearTimeout( this._showTimer );
	window.clearTimeout( this._closeTimer );

	if ( this.popup )
		this.popup.hide();

	var pm = this.parentMenu;
	if ( pm && pm.shownSubMenu == this )
		pm.shownSubMenu = null;

	this.setSelectedIndex( -1 );
	this._checkCloseState();
};

Menu.prototype.closeAllSubs = function ( oNotThisSub) {
	// go through items and check for sub menus
	var items = this.items;
	var l = items.length;
	for (var i = 0; i < l; i++) {
		if ( items[i].subMenu != null && items[i].subMenu != oNotThisSub )
			items[i].subMenu.close();
	}
};

Menu.prototype.getSelectedIndex = function () {
	return this.selectedIndex;
};

Menu.prototype.setSelectedIndex = function ( nIndex ) {
	if ( this.selectedIndex == nIndex ) return;

	if ( nIndex >= this.items.length )
		nIndex = -1;

	var mi;

	// deselect old
	if ( this.selectedIndex != -1 ) {
		mi = this.items[ this.selectedIndex ];
		mi.setSelected( false );
	}

	this.selectedIndex = nIndex;
	mi = this.items[ this.selectedIndex ];
	if ( mi != null )
		mi.setSelected( true );
};

Menu.prototype.goToNextMenuItem = function () {
	var i = 0;
	var items = this.items;
	var length = items.length;
	var index = this.getSelectedIndex();
	var tmp;

	do {
		if ( index == -1 || index >= length )
			index = 0;
		else
			index++;
		i++;
		tmp = items[index]
	} while ( !( tmp != null && tmp instanceof MenuItem &&
			!(tmp instanceof MenuSeparator) || i >= length ) )

	if ( tmp != null )
		this.setSelectedIndex( index );
};

Menu.prototype.goToPreviousMenuItem = function () {

	var i = 0;
	var items = this.items;
	var length = items.length;
	var index = this.getSelectedIndex();
	var tmp;

	do {
		if ( index == -1 || index >= length )
			index = length - 1;
		else
			index--;
		i++;
		tmp = items[index]
	} while ( !( tmp != null && tmp instanceof MenuItem &&
			!(tmp instanceof MenuSeparator) || i >= length ) )

	if ( tmp != null )
		this.setSelectedIndex( index );
};

Menu.prototype.goToNextMenu = function () {
	var index = this.getSelectedIndex();
	var mi = this.items[ index ];

	if ( mi && mi.subMenu && !mi.disabled ) {
		mi.subMenu.setSelectedIndex( 0 );
		mi.showSubMenu( false );
	}
	else {
		// go up to root and select next
		var mb = this.getMenuBar();
		if ( mb != null )
			mb.goToNextMenuItem();
	}
};

Menu.prototype.goToPreviousMenu = function () {
	if ( this.parentMenuItem && this.parentMenuItem instanceof MenuButton ) {
		this.parentMenu.goToPreviousMenuItem();
	}
	else if ( this.parentMenuItem ) {
		this.close();
	}
};

Menu.prototype.getMenuBar = function () {
	if ( this.parentMenu == null )
		return null;
	return this.parentMenu.getMenuBar();
};

Menu.prototype.makeEventListeners = function () {
	if ( this.eventListeners != null )
		return;

	this.eventListeners = {
		onscroll:			new Function( "eventListeners.menu.onscroll(\"" + this.id + "\")" ),
		onmouseover:		new Function( "eventListeners.menu.onmouseover(\"" + this.id + "\")" ),
		onmouseout:			new Function( "eventListeners.menu.onmouseout(\"" + this.id + "\")" ),
		onmouseup:			new Function( "eventListeners.menu.onmouseup(\"" + this.id + "\")" ),
		onmousewheel:		new Function( "eventListeners.menu.onmousewheel(\"" + this.id + "\")" ),
		onreadystatechange:	new Function( "eventListeners.menu.onreadystatechange(\"" + this.id + "\")" ),
		onkeydown:			new Function( "eventListeners.menu.onkeydown(\"" + this.id + "\")" ),
		oncontextmenu:		new Function( "eventListeners.menu.oncontextmenu(\"" + this.id + "\")" ),
		onunload:			new Function( "eventListeners.menu.onunload(\"" + this.id + "\")" )
	};
};

Menu.prototype.detachEvents = function () {
	if ( this.eventListeners == null )
		return;

	var d = this.getDocument();
	var w = d.parentWindow;
	var scrollContainer = d.getElementById("scroll-container");

	scrollContainer.detachEvent( "onscroll", this.eventListeners.onscroll );
	d.detachEvent( "onmouseover", this.eventListeners.onmouseover );
	d.detachEvent( "onmouseout", this.eventListeners.onmouseout );
	d.detachEvent( "onmouseup", this.eventListeners.onmouseup );
	d.detachEvent( "onmousewheel", this.eventListeners.onmousewheel );
	if (this.cssText == null) {
		var linkEl = d.getElementsByTagName("LINK")[0];
		linkEl.detachEvent( "onreadystatechange", this.eventListeners.onreadystatechange );
	}
	d.detachEvent( "onkeydown", this.eventListeners.onkeydown );
	d.detachEvent( "oncontextmenu", this.eventListeners.oncontextmenu );
	// prevent IE to keep menu open when navigating away
	window.detachEvent( "onunload", this.eventListeners.onunload );
}

Menu.prototype.hookupMenu = function ( d ) {

	this.detachEvents();
	this.makeEventListeners();

	var oThis = this;
	var d = this.getDocument();
	var w = d.parentWindow;
	var scrollContainer = d.getElementById("scroll-container");

	// listen to the onscroll
	scrollContainer.attachEvent( "onscroll", this.eventListeners.onscroll );
	d.attachEvent( "onmouseover", this.eventListeners.onmouseover );
	d.attachEvent( "onmouseout", this.eventListeners.onmouseout );
	d.attachEvent( "onmouseup", this.eventListeners.onmouseup );
	d.attachEvent( "onmousewheel", this.eventListeners.onmousewheel );

	// if css file is not loaded we need to wait for it to load.
	// Once loaded fix the size

	if (this.cssText == null) {
		var linkEl = d.getElementsByTagName("LINK")[0];
		if ( linkEl.readyState != "complete") {
			linkEl.attachEvent( "onreadystatechange", this.eventListeners.onreadystatechange );
		}
	}

	d.attachEvent( "onkeydown", this.eventListeners.onkeydown );
	d.attachEvent( "oncontextmenu", this.eventListeners.oncontextmenu );
	// prevent IE to keep menu open when navigating away
	window.attachEvent( "onunload", this.eventListeners.onunload );

	var all = d.all;
	var l = all.length;
	for ( var i = 0; i < l; i++ )
		all[i].unselectable = "on";
};

Menu.prototype.handleKeyEvent = function ( oEvent ) {

	if ( this.shownSubMenu )
		// sub menu handles key event
		return;

	var nKeyCode = oEvent.keyCode;

	switch ( nKeyCode ) {
		case 40:	// down
			this.goToNextMenuItem();
			break;

		case 38:	// up
			this.goToPreviousMenuItem();
			break;

		case 39:	// right
			this.goToNextMenu();
			break;

		case 37:	// left
			this.goToPreviousMenu();
			break;

		case 13:	// enter
			var mi = this.items[ this.getSelectedIndex() ];
			if ( mi )
				mi.dispatchAction();
			break;

		case 27:	// esc
			this.close();

			// should close menu and go to parent menu item
			break;

		case Menu.keyboardAccelKey:
		case Menu.keyboardAccelKey2:
			this.closeAllMenus();
			break;

		default:
			// find any mnemonic that matches
			var c = String.fromCharCode( nKeyCode ).toLowerCase();
			var items = this.items;
			var l = items.length;
			for ( var i = 0; i < l; i++ ) {
				if ( items[i].mnemonic == c ) {
					items[i].dispatchAction();
					break;
				}
			}
	}

	// cancel default action
	oEvent.returnValue = false;
	oEvent.keyCode = 0;
};

// poll close state and when closed call _onclose
Menu.prototype._startClosePoll = function () {
	var oThis = this;
	window.clearInterval( this._onCloseInterval );
	this._onCloseInterval = window.setInterval(
		"eventListeners.menu.oncloseinterval(\"" + this.id + "\")", 100 );
};

Menu.prototype._checkCloseState = function () {
	var closed = this.popup == null || !this.popup.isOpen;
	if ( closed && this._closed != closed ) {
		this._closed = closed;
		this._closedAt = new Date().valueOf();
		window.clearInterval( this._onCloseInterval );
		if ( typeof this._onclose == "function" ) {
			var e = this.getDocument().parentWindow.event;
			if ( e != null && e.keyCode == 27 )
				this._closeReason = "escape";
			else
				this._closeReason = "unknown";
			this._onclose();
		}
		if ( typeof this.onclose == "function" )
			this.onclose();
	}
};

Menu.prototype._isCssFileLoaded = function () {
	if (this.cssText != null)
		return true;

	var d = this.getMeasureDocument();
	var l = d.getElementsByTagName("LINK")[0];
	return l.readyState == "complete";
};

Menu.prototype.destroy = function () {
	var l = this.items.length;
	for ( var i = l -1; i >= 0; i-- )
		this.items[i].destroy();

	this.detachEvents();
	this.items = [];
	this.parentMenu = null;
	this.parentMenuItem = null;
	this.shownSubMenu = null;
	this._cachedSizes = null;
	this.eventListeners = null;

	if ( this.popup != null ) {
		var d = this.popup.document;
		d.open("text/plain", "replace");
		d.write("");
		d.close();
		this.popup = null;
	}

	if ( Menu._measureMenu == this ) {
		Menu._measureMenu = null;
		var d = Menu._measureFrame.contentWindow.document;
		d.open("text/plain", "replace");
		d.write("");
		d.close();
		Menu._measureFrame.parentNode.removeChild(Menu._measureFrame);
		Menu._measureFrame = null;
	}

	menuCache.remove( this );
};

////////////////////////////////////////////////////////////////////////////////////
// MenuItem
//

function MenuItem( sLabelText, fAction, sIconSrc, oSubMenu ) {
	// public
	this.icon = sIconSrc || "";
	this.text = sLabelText;
	this.action = fAction;

	this.subMenu = oSubMenu;
	this.parentMenu = null;

	// private
	this._selected = false;
	this._useInsets = true;	// should insets be taken into account when showing sub menu

	this.id = menuCache.getId();
	menuCache[ this.id ] = this;
}

MenuItem.prototype.subMenuDirection = "horizontal";
MenuItem.prototype.disabled = false;
MenuItem.prototype.mnemonic = null;
MenuItem.prototype.shortcut = null;
MenuItem.prototype.toolTip = "";
MenuItem.prototype.target = null;
MenuItem.prototype.visible = true;

MenuItem.prototype.toHtml = function () {
	var cssClass = this.getCssClass();
	var toolTip = this.getToolTip();

	return	"<tr" +
			(cssClass != "" ? " class=\"" + cssClass + "\"" : "") +
			(toolTip != "" ? " title=\"" + toolTip + "\"" : "") +
			(!this.visible ? " style=\"display: none\"" : "") +
			">" +
			this.getIconCellHtml() +
			this.getTextCellHtml() +
			this.getShortcutCellHtml() +
			this.getSubMenuArrowCellHtml() +
			"</tr>";
};

MenuItem.prototype.getTextHtml = function () {
	var s = this.text;
	if ( !s || !this.mnemonic )
		return s;

	// replace character with <u> character </u>
	// /^(((<([^>]|MNEMONIC)+>)|[^MNEMONIC])*)(MNEMONIC)/i
	var re = new RegExp( "^(((<([^>]|" + this.mnemonic + ")+>)|[^<" +
						this.mnemonic + "])*)(" + this.mnemonic + ")", "i" );
	re.exec( s );
	if ( RegExp.index != -1 && RegExp.$5 != "" )
		return RegExp.$1 + "<u>" + RegExp.$5 + "</u>" + RegExp.rightContext;
	else
		return s;
};


MenuItem.prototype.getIconHtml = function () {
	return this.icon != "" ? "<img src=\"" + this.icon + "\">" : "<span>&nbsp;</span>";
};

MenuItem.prototype.getTextCellHtml = function () {
	return "<td class=\"label-cell\" nowrap=\"nowrap\">" +
			this.makeDisabledContainer(
				this.getTextHtml()
			) +
			"</td>";
};

MenuItem.prototype.getIconCellHtml = function () {
	return "<td class=\"" +
			(this.icon != "" ? "icon-cell" : "empty-icon-cell") +
			"\">" +
			this.makeDisabledContainer(
				this.getIconHtml()
			) +
			"</td>";
};

MenuItem.prototype.getCssClass = function () {
	if ( this.disabled && this._selected )
		return "disabled-hover";
	else if ( this.disabled )
		return "disabled";
	else if ( this._selected )
		return "hover";

	return "";
};

MenuItem.prototype.getToolTip = function () {
	return this.toolTip;
};

MenuItem.prototype.getShortcutHtml = function () {
	if ( this.shortcut == null )
		return "&nbsp;";

	return this.shortcut;
};

MenuItem.prototype.getShortcutCellHtml = function () {
	return "<td class=\"shortcut-cell\" nowrap=\"nowrap\">" +
			this.makeDisabledContainer(
				this.getShortcutHtml()
			) +
			"</td>";
};

MenuItem.prototype.getSubMenuArrowHtml = function () {
	if ( this.subMenu == null )
		return "&nbsp;";

	return 4;	// right arrow using the marlett (or webdings) font
};

MenuItem.prototype.getSubMenuArrowCellHtml = function () {
	return "<td class=\"arrow-cell\">" +
			this.makeDisabledContainer(
				this.getSubMenuArrowHtml()
			) +
			"</td>";
};

MenuItem.prototype.makeDisabledContainer = function ( s ) {
	if ( this.disabled )
		return	"<span class=\"disabled-container\"><span class=\"disabled-container\">" +
				s + "</span></span>";
	return s;
};

MenuItem.prototype.dispatchAction = function () {
	if ( this.disabled )
		return;

	this.setSelected( true );

	if ( this.subMenu ) {
		if ( !this.subMenu.isShown() )
			this.showSubMenu( false );
		return;
	}

	if ( typeof this.action == "function" ) {
		this.setSelected( false );
		this.parentMenu.closeAllMenus();
		this.action();

	}
	else if ( typeof this.action == "string" ) {	// href
		this.setSelected( false );
		this.parentMenu.closeAllMenus();
		if ( this.target != null )
			window.open( this.action, this.target );
		else
			document.location.href = this.action;
	}
};

MenuItem.prototype.setSelected = function ( bSelected ) {
	if ( this._selected == bSelected )	return;

	this._selected = Boolean( bSelected );

	var tr = this._htmlElement;
	if ( tr )
		tr.className = this.getCssClass();

	if ( !this._selected )
		this.closeSubMenu( true );

	var pm = this.parentMenu;
	if ( bSelected ) {

		pm.setSelectedIndex( this.itemIndex );
		this.scrollIntoView();

		// select item in parent menu as well
		if ( pm.parentMenuItem )
			pm.parentMenuItem.setSelected( true );
	}
	else
		pm.setSelectedIndex( -1 );

	if ( this._selected ) {
		// clear timers for parent menu
		window.clearTimeout( pm._closeTimer );
	}
};


MenuItem.prototype.getSelected = function () {
	return this.itemIndex == this.parentMenu.selectedIndex;
};

MenuItem.prototype.showSubMenu = function ( bDelayed ) {
	var sm = this.subMenu;
	var pm = this.parentMenu;
	if ( sm && !this.disabled ) {

		pm._aboutToShowSubMenu = true;

		window.clearTimeout( sm._showTimer );
		window.clearTimeout( sm._closeTimer );

		var showTimeout = bDelayed ? sm.showTimeout : 0;

		var oThis = this;
		sm._showTimer = window.setTimeout(
			"eventListeners.menuItem.onshowtimer(\"" + this.id + "\")",
			showTimeout );
	}
};

MenuItem.prototype.closeSubMenu = function ( bDelay ) {
	var sm = this.subMenu;
	if ( sm ) {
		window.clearTimeout( sm._showTimer );
		window.clearTimeout( sm._closeTimer );

		if ( sm.popup ) {
			if ( !bDelay )
				sm.close();
			else {
				var oThis = this;
				sm._closeTimer = window.setTimeout(
					"eventListeners.menuItem.onclosetimer(\"" + this.id + "\")",
					sm.closeTimeout );
			}
		}
	}
};

MenuItem.prototype.scrollIntoView = function () {
	if ( this.parentMenu._scrollingMode ) {
		var d = this.parentMenu.getDocument();
		var sc = d.getElementById( "scroll-container" );
		var scrollTop = sc.scrollTop;
		var clientHeight = sc.clientHeight;
		var offsetTop = this._htmlElement.offsetTop;
		var offsetHeight = this._htmlElement.offsetHeight;

		if ( offsetTop < scrollTop )
			sc.scrollTop = offsetTop;
		else if ( offsetTop + offsetHeight > scrollTop + clientHeight )
			sc.scrollTop = offsetTop + offsetHeight - clientHeight;
	}
};



MenuItem.prototype.positionSubMenu = function () {
	var dir = this.subMenuDirection;
	var el = this._htmlElement;
	var useInsets = this._useInsets;
	var sm = this.subMenu;

	var oThis = this;

	if ( !sm._isCssFileLoaded() ) {
		window.setTimeout(
			"eventListeners.menuItem.onpositionsubmenutimer(\"" + this.id + "\")",
			1 );
		return;
	}

	// find parent item rectangle
	var rect = {
		left:	posLib.getScreenLeft( el ),
		top:	posLib.getScreenTop( el ),
		width:	el.offsetWidth,
		height:	el.offsetHeight
	};

	var menuRect = {
		left:		sm.getLeft(),
		top:		sm.getTop(),
		width:		sm.getPreferredWidth(),
		height:		sm.getPreferredHeight(),
		insetLeft:		useInsets ? sm.getInsetLeft() : 0,
		insetRight:		useInsets ? sm.getInsetRight() : 0,
		insetTop:		useInsets ? sm.getInsetTop() : 0,
		insetBottom:	useInsets ? sm.getInsetBottom() : 0
	};

	// work around for buggy graphics drivers that screw up the screen.left
	var screenWidth = screen.width;
	var screenHeight = screen.height;
	while ( rect.left > screenWidth )
		screenWidth += screen.width;
	while ( rect.top > screenHeight )
		screenHeight += screen.height;

	var left, top, width = menuRect.width, height = menuRect.height;

	if ( dir == "vertical" ) {
		if ( rect.left + menuRect.width - menuRect.insetLeft <= screenWidth )
			left = rect.left - menuRect.insetLeft;
		else if ( screenWidth >= menuRect.width )
			left = screenWidth - menuRect.width;
		else
			left = 0;

		if ( rect.top + rect.height + menuRect.height - menuRect.insetTop <= screenHeight )
			top = rect.top + rect.height - menuRect.insetTop;
		else if ( rect.top - menuRect.height + menuRect.insetBottom >= 0 )
			top = rect.top - menuRect.height + menuRect.insetBottom;
		else {	// use largest and resize
			var sizeAbove = rect.top + menuRect.insetBottom;
			var sizeBelow = screenHeight - rect.top - rect.height + menuRect.insetTop;
			if ( sizeBelow >= sizeAbove ) {
				top = rect.top + rect.height - menuRect.insetTop;
				height = sizeBelow;
			}
			else {
				top = 0;
				height = sizeAbove;
			}
		}
	}
	else {
		if ( rect.top + menuRect.height - menuRect.insetTop <= screenHeight )
			top = rect.top - menuRect.insetTop;
		else if ( rect.top + rect.height - menuRect.height + menuRect.insetBottom >= 0)
			top = rect.top + rect.height - menuRect.height + menuRect.insetBottom;
		else if ( screenHeight >= menuRect.height )
			top = screenHeight - menuRect.height;
		else {
			top = 0;
			height = screenHeight
		}

		if ( rect.left + rect.width + menuRect.width - menuRect.insetLeft <= screenWidth )
			left = rect.left + rect.width - menuRect.insetLeft;
		else if ( rect.left - menuRect.width + menuRect.insetRight >= 0 )
			left = rect.left - menuRect.width + menuRect.insetRight;
		else if ( screenWidth >= menuRect.width )
			left = screenWidth - menuRect.width;
		else
			left = 0;
	}

	var scrollBefore = sm._scrollingMode;
	sm.show( left, top, width, height );
	if ( sm._scrollingMode != scrollBefore )
		this.positionSubMenu();
};


MenuItem.prototype.destroy = function () {
	if ( this.subMenu != null )
		this.subMenu.destroy();

	this.subMenu = null;
	this.parentMenu = null;
	var el = this._htmlElement
	if ( el != null )
		el._menuItem = null;
	this._htmlElement = null;

	menuCache.remove( this );
};


///////////////////////////////////////////////////////////////////////////////
// CheckBoxMenuItem extends MenuItem
//
function CheckBoxMenuItem( sLabelText, bChecked, fAction, oSubMenu ) {

	this.MenuItem = MenuItem;
	this.MenuItem( sLabelText, fAction, null, oSubMenu);

	// public
	this.checked = bChecked;
}

CheckBoxMenuItem.prototype = new MenuItem;

CheckBoxMenuItem.prototype.getIconHtml = function () {
	return "<span class=\"check-box\">" +
		(this.checked ? "a" : "&nbsp;") +
		"</span>";
};

CheckBoxMenuItem.prototype.getIconCellHtml = function () {
	return "<td class=\"icon-cell\">" +
			this.makeDisabledContainer(
				this.getIconHtml()
			) +
			"</td>";
};

CheckBoxMenuItem.prototype.getCssClass = function () {
	var s = (this.checked ? " checked" : "");
	if ( this.disabled && this._selected )
		return "disabled-hover" + s;
	else if ( this.disabled )
		return "disabled" + s;
	else if ( this._selected )
		return "hover" + s;

	return s;
};

CheckBoxMenuItem.prototype._menuItem_dispatchAction =
	MenuItem.prototype.dispatchAction;
CheckBoxMenuItem.prototype.dispatchAction = function () {
	if (!this.disabled) {
		this.checked = !this.checked;
		this._menuItem_dispatchAction();
		this.parentMenu.invalidate();
		this.parentMenu.closeAllMenus();
	}
};


///////////////////////////////////////////////////////////////////////////////
// RadioButtonMenuItem extends MenuItem
//
function RadioButtonMenuItem( sLabelText, bChecked, sRadioGroupName, fAction, oSubMenu ) {
	this.MenuItem = MenuItem;
	this.MenuItem( sLabelText, fAction, null, oSubMenu );

	// public
	this.checked = bChecked;
	this.radioGroupName = sRadioGroupName;
}

RadioButtonMenuItem.prototype = new MenuItem;

RadioButtonMenuItem.prototype.getIconHtml = function () {
	return "<span class=\"radio-button\">" +
		(this.checked ? "n" : "&nbsp;") +
		"</span>";
};

RadioButtonMenuItem.prototype.getIconCellHtml = function () {
	return "<td class=\"icon-cell\">" +
			this.makeDisabledContainer(
				this.getIconHtml()
			) +
			"</td>";
};

RadioButtonMenuItem.prototype.getCssClass = function () {
	var s = (this.checked ? " checked" : "");
	if ( this.disabled && this._selected )
		return "disabled-hover" + s;
	else if ( this.disabled )
		return "disabled" + s;
	else if ( this._selected )
		return "hover" + s;

	return s;
};

RadioButtonMenuItem.prototype._menuItem_dispatchAction =
	MenuItem.prototype.dispatchAction;
RadioButtonMenuItem.prototype.dispatchAction = function () {
	if (!this.disabled) {
		if ( !this.checked ) {
			// loop through items in parent menu
			var items = this.parentMenu.items;
			var l = items.length;
			for ( var i = 0; i < l; i++ ) {
				if ( items[i] instanceof RadioButtonMenuItem ) {
					if ( items[i].radioGroupName == this.radioGroupName ) {
						items[i].checked = items[i] == this;
					}
				}
			}
			this.parentMenu.invalidate();
		}

		this._menuItem_dispatchAction();
		this.parentMenu.closeAllMenus();
	}
};


///////////////////////////////////////////////////////////////////////////////
// MenuSeparator extends MenuItem
//
function MenuSeparator() {
	this.MenuItem = MenuItem;
	this.MenuItem();
}

MenuSeparator.prototype = new MenuItem;

MenuSeparator.prototype.toHtml = function () {
	return "<tr class=\"" + this.getCssClass() + "\"" +
			(!this.visible ? " style=\"display: none\"" : "") +
			"><td colspan=\"4\">" +
			"<div class=\"separator-line\"></div>" +
			"</td></tr>";
};

MenuSeparator.prototype.getCssClass = function () {
	return "separator";
};


////////////////////////////////////////////////////////////////////////////////////
// MenuBar extends Menu
//
function MenuBar() {
	this.items = [];
	this.parentMenu = null;
	this.parentMenuItem = null;
	this.shownSubMenu = null;
	this._aboutToShowSubMenu = false;

	this.active = false;
	this.id = menuCache.getId();
	menuCache[ this.id ] = this;
}
MenuBar.prototype = new Menu;

MenuBar.prototype._document = null;

MenuBar.leftMouseButton = 1;

MenuBar.prototype.toHtml = function () {
	var items = this.items;
	var l = items.length;
	var itemsHtml = new Array( l );
	for (var i = 0; i < l; i++ )
		itemsHtml[i] = items[i].toHtml();

	return "<div class=\"menu-bar\" id=\"" + this.id + "\">" +
		itemsHtml.join( "" ) +
		"</div>";
};

MenuBar.prototype.invalidate = function () {
	if (this._htmlElement) {
		this.detachEvents();
		var oldEl = this._htmlElement;
		var newEl = this.create(this._document);
		oldEl.parentNode.replaceChild(newEl, oldEl);
	}
};

MenuBar.prototype.createPopup = function () {};
MenuBar.prototype.getPopup= function () {};
MenuBar.prototype.drawMenu = function () {};

MenuBar.prototype.getDocument = function () {
	return this._document;
};

MenuBar.prototype.show = function ( left, top, w, h ) {};
MenuBar.prototype.isShown = function () { return true; };
MenuBar.prototype.fixSize = function () {}

MenuBar.prototype.getWidth = function () {
	return this._htmlElement.offsetWidth;
};

MenuBar.prototype.getHeight = function () {
	return this._htmlElement.offsetHeight;
};

MenuBar.prototype.getPreferredWidth = function () {
	var el = this._htmlElement;
	el.runtimStyle.whiteSpace = "nowrap";
	var sw = el.scrollWidth;
	el.runtimStyle.whiteSpace = "";
	return sw + parseInt( el.currentStyle.borderLeftWidth ) +
				parseInt( el.currentStyle.borderRightWidth );
};

MenuBar.prototype.getPreferredHeight = function () {
	var el = this._htmlElement;
	el.runtimStyle.whiteSpace = "nowrap";
	var sw = el.scrollHeight;
	el.runtimStyle.whiteSpace = "";
	return sw + parseInt( el.currentStyle.borderTopWidth ) +
				parseInt( el.currentStyle.borderBottomWidth );
};

MenuBar.prototype.getLeft = function () {
	return posLib.getScreenLeft( this._htmlElement );
};
MenuBar.prototype.getTop = function () {
	return posLib.getScreenLeft( this._htmlElement );
};
MenuBar.prototype.setLeft = function ( l ) {};
MenuBar.prototype.setTop = function ( t ) {};
MenuBar.prototype.setLocation = function ( l, t ) {};
MenuBar.prototype.setRect = function ( l, t, w, h ) {};
MenuBar.prototype.getInsetLeft = function () {
	return parseInt( this._htmlElement.currentStyle.borderLeftWidth );
};
MenuBar.prototype.getInsetRight = function () {
	return parseInt( this._htmlElement.currentStyle.borderRightWidth );
};
MenuBar.prototype.getInsetTop = function () {
	return parseInt( this._htmlElement.currentStyle.borderTopWidth );
};
MenuBar.prototype.getInsetBottom = function () {
	return parseInt( this._htmlElement.currentStyle.borderBottomWidth );
};
MenuBar.prototype.fixScrollButtons = function () {};
MenuBar.prototype.fixScrollEnabledState = function () {};

MenuBar.prototype.makeEventListeners = function () {
	if ( this.eventListeners != null )
		return;

	this.eventListeners = {
		onmouseover:		new Function( "eventListeners.menuBar.onmouseover(\"" + this.id + "\")" ),
		onmouseout:			new Function( "eventListeners.menuBar.onmouseout(\"" + this.id + "\")" ),
		onmousedown:		new Function( "eventListeners.menuBar.onmousedown(\"" + this.id + "\")" ),
		onkeydown:			new Function( "eventListeners.menuBar.onkeydown(\"" + this.id + "\")" ),
		onunload:			new Function( "eventListeners.menuBar.onunload(\"" + this.id + "\")" )
	};
};

MenuBar.prototype.detachEvents = function () {
	if ( this.eventListeners == null )
		return;

	this._htmlElement.detachEvent( "onmouseover",	this.eventListeners.onmouseover );
	this._htmlElement.detachEvent( "onmouseout", this.eventListeners.onmouseout );
	this._htmlElement.detachEvent( "onmousedown", this.eventListeners.onmousedown );
	this._document.detachEvent( "onkeydown", this.eventListeners.onkeydown );
	window.detachEvent( "onunload", this.eventListeners.onunload );
}

MenuBar.prototype.hookupMenu = function ( element ) {
	if ( !this._document )
		this._document = element.document;

	this.detachEvents();
	this.makeEventListeners();

	// create shortcut to html element
	this._htmlElement = element;
	element.unselectable = "on";

	// and same for menu buttons
	var cs = element.childNodes;
	var items = this.items;
	var l = cs.length;
	for ( var i = 0; i < l; i++ ) {
		items[i]._htmlElement = cs[i];
		cs[i]._menuItem = items[i];
	}

	// hook up events
	element.attachEvent( "onmouseover",	this.eventListeners.onmouseover );
	element.attachEvent( "onmouseout", this.eventListeners.onmouseout );
	element.attachEvent( "onmousedown", this.eventListeners.onmousedown );
	this._document.attachEvent( "onkeydown", this.eventListeners.onkeydown );
	window.attachEvent( "onunload", this.eventListeners.onunload );
};

function getMenuItemElement( el ) {
	while ( el != null && el._menuItem == null)
		el = el.parentNode;
	return el;
}

function getTrElement( el ) {
	while ( el != null && el.tagName != "TR" )
		el = el.parentNode;
	return el;
}

MenuBar.prototype.write = function (oDocument) {
	this._document = oDocument || document;
	this._document.write( this.toHtml() );
	var el = this._document.getElementById( this.id );
	this.hookupMenu( el );
};

MenuBar.prototype.create = function (oDocument) {
	this._document = oDocument || document;
	var dummyDiv = this._document.createElement( "DIV" );
	dummyDiv.innerHTML = this.toHtml();
	var el = dummyDiv.removeChild( dummyDiv.firstChild );
	this.hookupMenu( el );
	return el;
};

MenuBar.prototype.handleKeyEvent = function ( e ) {
	if ( this.getActiveState() == "open" )
		return;

	var nKeyCode = e.keyCode;

	if ( this.active && e[ Menu.keyboardAccelProperty ] ) {
		e.returnValue = false;
		e.keyCode = 0;
	}

	if ( nKeyCode == Menu.keyboardAccelKey || nKeyCode == Menu.keyboardAccelKey2 ) {
		if ( !e.repeat ) {
			this.toggleActive();
		}
		e.returnValue = false;
		e.keyCode = 0;
		return;
	}

	if ( !this.active ) {
		// do not set return value to true
		return;
	}

	switch ( nKeyCode ) {
		case 39:	// right
			this.goToNextMenuItem();
			e.returnValue = false;
			break;

		case 37:	// left
			this.goToPreviousMenuItem();
			e.returnValue = false;
			break;

		case 40:	// down
		case 38:	// up
		case 13:	// enter
			var mi = this.items[ this.getSelectedIndex() ];
			if ( mi ) {
				mi.dispatchAction();
				if ( mi.subMenu )
					mi.subMenu.setSelectedIndex( 0 );
			}
			e.returnValue = false;
			break;

		case 27:	// esc
			// we need to make sure that the menu bar looses its current
			// keyboard activation state

			this.setActive( false );
			e.returnValue = false;
			break;

		default:
			// find any mnemonic that matches
			var c = String.fromCharCode( nKeyCode ).toLowerCase();
			var items = this.items;
			var l = items.length;
			for ( var i = 0; i < l; i++ ) {
				if ( items[i].mnemonic == c ) {
					items[i].dispatchAction();
					e.returnValue = false;
					break;
				}
			}
	}
};

MenuBar.prototype.getMenuBar = function () {
	return this;
};

MenuBar.prototype._menu_goToNextMenuItem = Menu.prototype.goToNextMenuItem;
MenuBar.prototype.goToNextMenuItem = function () {
	var expand = this.getActiveState() == "open";
	this._menu_goToNextMenuItem();
	var mi = this.items[ this.getSelectedIndex() ];
	if ( expand && mi != null ) {
		window.setTimeout(
			"eventListeners.menuBar.ongotonextmenuitem(\"" + this.id + "\")",
			1 );
	}
};

MenuBar.prototype._menu_goToPreviousMenuItem = Menu.prototype.goToPreviousMenuItem;
MenuBar.prototype.goToPreviousMenuItem = function () {
	var expand = this.getActiveState() == "open";
	this._menu_goToPreviousMenuItem();
	var mi = this.items[ this.getSelectedIndex() ];
	if ( expand && mi != null ) {
		window.setTimeout(
			"eventListeners.menuBar.ongotopreviousmenuitem(\"" + this.id + "\")",
			1 );
	}
};

MenuBar.prototype._menu_setSelectedIndex = Menu.prototype.setSelectedIndex;
MenuBar.prototype.setSelectedIndex = function ( nIndex ) {
	this._menu_setSelectedIndex( nIndex );
	this.active = nIndex != -1;
};

MenuBar.prototype.setActive = function ( bActive ) {
	if ( this.active != bActive ) {
		this.active = Boolean( bActive );
		if ( this.active ) {
			this.setSelectedIndex( 0 );
			this.backupFocused();
			window.focus();
		}
		else {
			this.setSelectedIndex( -1 );
			this.restoreFocused();
		}
	}
};

MenuBar.prototype.toggleActive = function () {
	if ( this.getActiveState() == "active" )
		this.setActive( false );
	else if ( this.getActiveState() == "inactive" )
		this.setActive( true );
};

// returns active, inactive or open
MenuBar.prototype.getActiveState = function () {
	if ( this.shownSubMenu != null || this._aboutToShowSubMenu)
		return "open";
	else if ( this.active )
		return "active";
	else
		return "inactive";
};

MenuBar.prototype.backupFocused = function () {
	this._activeElement = this._document.activeElement;
};

MenuBar.prototype.restoreFocused = function () {
	try {
		this._activeElement.focus();
	}
	catch (ex) {}
	delete this._activeElement;

};

MenuBar.prototype.destroy = function () {
	var l = this.items.length;
	for ( var i = l -1; i >= 0; i-- )
		this.items[i].destroy();

	this.detachEvents();
	this._activeElement = null;
	this._htmlElement = null;
	this._document = null;
	this.items = [];
	this.shownSubMenu = null;
	this.eventListeners = null;

	menuCache.remove( this );
};

////////////////////////////////////////////////////////////////////////////////////
// MenuButton extends MenuItem
//
function MenuButton( sLabelText, oSubMenu ) {
	this.MenuItem = MenuItem;
	this.MenuItem( sLabelText, null, null, oSubMenu );

	// private
	this._hover = false;
	this._useInsets = false;	// should insets be taken into account when showing sub menu
}

MenuButton.prototype = new MenuItem;
MenuButton.prototype.subMenuDirection = "vertical";

MenuButton.prototype.scrollIntoView = function () {};
MenuButton.prototype.toHtml = function () {
	var cssClass = this.getCssClass();
	var toolTip = this.getToolTip();

	if ( this.subMenu && !this.subMenu._onclose )
		this.subMenu._onclose = new Function( "eventListeners.menuButton.onclose(\"" + this.id + "\")" );

	return	"<span unselectable=\"on\" " +
			(cssClass != "" ? " class=\"" + cssClass + "\"" : "") +
			(toolTip != "" ? " title=\"" + toolTip + "\"" : "") +
			(!this.visible ? " style=\"display: none\"" : "") +
			"><span unselectable=\"on\" class=\"left\"></span>" +
			"<span unselectable=\"on\" class=\"middle\">" +
				this.getTextHtml() +
			"</span>" +
			"<span unselectable=\"on\" class=\"right\"></span>" +
			"</span>";
};

MenuButton.prototype.getCssClass = function () {
	if ( this.disabled && this._selected )
		return "menu-button disabled-hover";
	else if ( this.disabled )
		return "menu-button disabled";
	else if ( this._selected ) {
		if ( this.parentMenu.getActiveState() == "open" ) {
			return "menu-button active";
		}
		else
			return "menu-button hover";
	}
	else if ( this._hover )
		return "menu-button hover";

	return "menu-button ";
};

MenuButton.prototype.subMenuClosed = function () {

	if ( this.subMenu._closeReason == "escape" )
		this.setSelected( true );
	else
		this.setSelected( false );

	if ( this.parentMenu.getActiveState() == "inactive" )
		this.parentMenu.restoreFocused();
};

MenuButton.prototype.setSelected = function ( bSelected ) {

	var oldSelected = this._selected;
	this._selected = Boolean( bSelected );

	var tr = this._htmlElement;
	if ( tr )
		tr.className = this.getCssClass();

	if ( this._selected == oldSelected )
		return;

	if ( !this._selected )
		this.closeSubMenu( true );

	if ( bSelected ) {
		this.parentMenu.setSelectedIndex( this.itemIndex );
		this.scrollIntoView();
	}
	else
		this.parentMenu.setSelectedIndex( -1 );
};





////////////////////////////////////////////////////////////////////////////////////
// event listener
//

var eventListeners = {
	menu: {
		onkeydown:	function ( id ) {
			var oThis = menuCache[id];
			var w = oThis.getDocument().parentWindow;
			oThis.handleKeyEvent( w.event );
		},
		onunload:	function ( id ) {
			if (id in menuCache) {
				menuCache[id].closeAllMenus();
				menuCache[id].destroy();
			}
			// else already destroyed
		},
		oncontextmenu:	function ( id ) {
			var oThis = menuCache[id];
			var w = oThis.getDocument().parentWindow;
			w.event.returnValue = false;
		},

		onscroll:	function ( id ) {
			menuCache[id].fixScrollEnabledState();
		},

		onmouseover:	function ( id ) {
			var oThis = menuCache[id];
			var w = oThis.getDocument().parentWindow;

			var fromEl	= getTrElement( w.event.fromElement );
			var toEl	= getTrElement( w.event.toElement );

			if ( toEl != null && toEl != fromEl ) {
				var mi = toEl._menuItem;
				if ( mi ) {
					if ( !mi.disabled || oThis.mouseHoverDisabled ) {
						mi.setSelected( true );
						mi.showSubMenu( true );
					}
				}
				else {	// scroll button
					if (toEl.className == "disabled" || toEl.className == "disabled-hover" )
						toEl.className = "disabled-hover";
					else
						toEl.className = "hover";
					oThis.selectedIndex = -1;
				}
			}
		},

		onmouseout:	function ( id ) {
			var oThis = menuCache[id];
			var w = oThis.getDocument().parentWindow;

			var fromEl	= getTrElement( w.event.fromElement );
			var toEl	= getTrElement( w.event.toElement );

			if ( fromEl != null && toEl != fromEl ) {

				var id = fromEl.parentNode.parentNode.id;
				var mi = fromEl._menuItem;

				if ( id == "scroll-up-item" || id == "scroll-down-item" ) {
					if (fromEl.className == "disabled-hover" || fromEl.className == "disabled" )
						fromEl.className = "disabled";
					else
						fromEl.className = "";
					oThis.selectedIndex = -1;
				}

				else if ( mi &&
					( toEl != null || mi.subMenu == null || mi.disabled ) ) {

					mi.setSelected( false );
				}
			}

		},

		onmouseup:	function ( id ) {
			var oThis = menuCache[id];
			var w = oThis.getDocument().parentWindow;

			var srcEl	= getMenuItemElement( w.event.srcElement );

			if ( srcEl != null ) {
				var id = srcEl.parentNode.parentNode.id;
				if ( id == "scroll-up-item" || id == "scroll-down-item" ) {
					return;
				}

				oThis.selectedIndex = srcEl.rowIndex;
				var menuItem = oThis.items[ oThis.selectedIndex ];
				menuItem.dispatchAction();
			}
		},

		onmousewheel:	function ( id ) {
			var oThis = menuCache[id];
			var d = oThis.getDocument();
			var w = d.parentWindow;
			var scrollContainer = d.getElementById("scroll-container");
			scrollContainer.scrollTop -= 3 * w.event.wheelDelta / 120 * ScrollButton.scrollAmount;
		},

		onreadystatechange:	function ( id ) {
			var oThis = menuCache[id];
			var d = oThis.getDocument();
			var linkEl = d.getElementsByTagName("LINK")[0];
			if ( linkEl.readyState == "complete" ) {
				oThis.resetSizeCache();	// reset sizes
				oThis.fixSize();
				oThis.fixScrollButtons();
			}
		},

		oncloseinterval:	function ( id ) {
			 menuCache[id]._checkCloseState();
		}
	},


	menuItem:	{
		onshowtimer:	function ( id ) {
			var oThis = menuCache[id];
			var sm = oThis.subMenu;
			var pm = oThis.parentMenu;
			var selectedIndex = sm.getSelectedIndex();

			pm.closeAllSubs( sm );
			window.setTimeout( "eventListeners.menuItem.onshowtimer2(\"" + id + "\")", 1);
		},

		onshowtimer2:	function ( id ) {
			var oThis = menuCache[id];
			var sm = oThis.subMenu;
			var selectedIndex = sm.getSelectedIndex();
			oThis.positionSubMenu();
			sm.setSelectedIndex( selectedIndex );
			oThis.setSelected( true );
		},

		onclosetimer:	function ( id ) {
			var oThis = menuCache[id];
			var sm = oThis.subMenu;
			sm.close();
		},

		onpositionsubmenutimer:	function ( id ) {
			var oThis = menuCache[id];
			var sm = oThis.subMenu;
			sm.resetSizeCache();	// reset sizes
			oThis.positionSubMenu();
			sm.setSelectedIndex( 0 );
		}
	},

	menuBar:	{
		onmouseover:	function ( id ) {
			var oThis = menuCache[id];

			var e = oThis.getDocument().parentWindow.event;
			var fromEl = getMenuItemElement( e.fromElement );
			var toEl = getMenuItemElement( e.toElement );

			if ( toEl != null && toEl != fromEl ) {

				var mb = toEl._menuItem;
				var m = mb.parentMenu;

				if ( m.getActiveState() == "open" ) {
					window.setTimeout( function () {
						mb.dispatchAction();
					}, 1);
				}
				else if ( m.getActiveState() == "active" ) {
					mb.setSelected( true );
				}
				else {
					mb._hover = true;
					toEl.className = mb.getCssClass();
				}
			}
		},

		onmouseout:	function ( id ) {
			var oThis = menuCache[id];

			var e = oThis.getDocument().parentWindow.event;
			var fromEl = getMenuItemElement( e.fromElement );
			var toEl = getMenuItemElement( e.toElement );

			if ( fromEl != null && toEl != fromEl ) {
				var mb = fromEl._menuItem;
				mb._hover = false;
				fromEl.className = mb.getCssClass();
			}
		},

		onmousedown:	function ( id ) {
			var oThis = menuCache[id];

			var e = oThis.getDocument().parentWindow.event;
			if ( e.button != MenuBar.leftMouseButton )
				return;

			var el = getMenuItemElement( e.srcElement );

			if ( el != null ) {
				var mb = el._menuItem;
				if ( mb.subMenu ) {
					mb.subMenu._checkCloseState();
					if ( new Date() - mb.subMenu._closedAt > 100 ) {	// longer than the time to
																		// do the hide
						mb.dispatchAction();
					}
					else {
						mb._hover = true;
						mb._htmlElement.className = mb.getCssClass();
					}
				}
			}
		},

		onkeydown:	function ( id ) {
			var oThis = menuCache[id];
			var e = oThis.getDocument().parentWindow.event;
			oThis.handleKeyEvent( e );
		},

		onunload:	function ( id ) {
			menuCache[id].destroy();
		},

		ongotonextmenuitem:	function ( id ) {
			var oThis = menuCache[id];
			var mi = oThis.items[ oThis.getSelectedIndex() ];
			mi.dispatchAction();
			if ( mi.subMenu )
				mi.subMenu.setSelectedIndex( 0 );
		},

		ongotopreviousmenuitem:	function ( id ) {
			var oThis = menuCache[id];
			var mi = oThis.items[ oThis.getSelectedIndex() ];
			mi.dispatchAction();
			if ( mi.subMenu )
				mi.subMenu.setSelectedIndex( 0 );
		}
	},

	menuButton: {
		onclose:	function ( id ) {
			menuCache[id].subMenuClosed();
		}
	}
};