//<script>
/*
 * ScrollButton
 *
 * This script was designed for use with DHTML Menu 4
 *
 * This script was created by Erik Arvidsson
 * (http://webfx.eae.net/contact.html#erik)
 * for WebFX (http://webfx.eae.net)
 * Copyright 2002
 *
 * For usage see license at http://webfx.eae.net/license.html
 *
 * Version: 1.02
 * Created: 2002-05-28
 * Updated:	???			Memory management updates
 *			2003-09-23	Changed to using onunload instead onbeforeunload
 *
 */

////////////////////////////////////////////////////////////////////////////////////
// scroolButtonCache
//

var scrollButtonCache = {
	_count:		0,
	_idPrefix:	"-scroll-button-cache-",

	getId:	function () {
		return this._idPrefix + this._count++;
	},

	remove:	function ( o ) {
		delete this[ o.id ];
	}
};

function ScrollButton( oEl, oScrollContainer, nDir ) {
	this.htmlElement = oEl;
	this.scrollContainer = oScrollContainer;
	this.dir = nDir;

	this.id = scrollButtonCache.getId();
	scrollButtonCache[ this.id ] = this;

	this.makeEventListeners();
	this.attachEvents();
}

ScrollButton.scrollIntervalPause = 100;
ScrollButton.scrollAmount = 18;

ScrollButton.prototype.startScroll = function () {
	this._interval = window.setInterval(
		"ScrollButton.eventListeners.oninterval(\"" + this.id + "\")",
		ScrollButton.scrollIntervalPause );
};

ScrollButton.prototype.endScroll = function () {
	if ( this._interval != null ) {
		window.clearInterval( this._interval );
		delete this._interval;
	}
};

ScrollButton.prototype.makeEventListeners = function () {
	if ( this.eventListeners != null )
		return;

	this.eventListeners = {
		onmouseover:	new Function( "ScrollButton.eventListeners.onmouseover(\"" + this.id + "\")" ),
		onmouseout:		new Function( "ScrollButton.eventListeners.onmouseout(\"" + this.id + "\")" ),
		onunload:	new Function( "ScrollButton.eventListeners.onunload(\"" + this.id + "\")" )
	};
};

ScrollButton.prototype.attachEvents = function () {
	if ( this.eventListeners == null )
		return;

	this.htmlElement.attachEvent( "onmouseover", this.eventListeners.onmouseover );
	this.htmlElement.attachEvent( "onmouseout", this.eventListeners.onmouseout );
	window.attachEvent( "onunload", this.eventListeners.onunload );
};

ScrollButton.prototype.detachEvents = function () {
	if ( this.eventListeners == null )
		return;

	this.htmlElement.detachEvent( "onmouseover", this.eventListeners.onmouseover );
	this.htmlElement.detachEvent( "onmouseout", this.eventListeners.onmouseout );
	window.detachEvent( "onunload", this.eventListeners.onunload );
};

ScrollButton.prototype.destroy = function () {
	this.endScroll();
	this.detachEvents();

	this.htmlElement = null;
	this.scrollContainer = null;
	this.eventListeners = null;

	scrollButtonCache.remove( this );
};

ScrollButton.eventListeners = {
	onmouseover:	function ( id ) {
		scrollButtonCache[id].startScroll();
	},

	onmouseout:		function ( id ) {
		scrollButtonCache[id].endScroll();
	},

	oninterval:		function ( id ) {
		var oThis = scrollButtonCache[id];
		switch ( oThis.dir ) {
			case 8:
				oThis.scrollContainer.scrollTop -= ScrollButton.scrollAmount;
				break;

			case 2:
				oThis.scrollContainer.scrollTop += ScrollButton.scrollAmount;
				break;

			case 4:
				oThis.scrollContainer.scrollLeft -= ScrollButton.scrollAmount;
				break;

			case 6:
				oThis.scrollContainer.scrollLeft += ScrollButton.scrollAmount;
				break;
		}
	},

	onunload:	function ( id ) {
		scrollButtonCache[id].destroy();
	}
};