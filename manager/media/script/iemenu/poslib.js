//<script>
/*
 * Position functions
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
 * Version: 1.1
 * Created: 2002-05-28
 * Updated: 2002-06-06	Rewrote to use getBoundingClientRect(). This solved
 *						several bugs related to relative and absolute positened
 *						elements
 *
 *
 */

// This only works in IE5 and IE6+ with both CSS1 and Quirk mode

var posLib = {

	getIeBox:		function (el) {
		return this.ie && el.document.compatMode != "CSS1Compat";
	},
	
	// relative client viewport (outer borders of viewport)
	getClientLeft:	function (el) {
		var r = el.getBoundingClientRect();
		return r.left - this.getBorderLeftWidth(this.getCanvasElement(el));
	},

	getClientTop:	function (el) {
		var r = el.getBoundingClientRect();
		return r.top - this.getBorderTopWidth(this.getCanvasElement(el));
	},

	// relative canvas/document (outer borders of canvas/document,
	// outside borders of element)
	getLeft:	function (el) {
		return this.getClientLeft(el) + this.getCanvasElement(el).scrollLeft;
	},

	getTop:	function (el) {
		return this.getClientTop(el) + this.getCanvasElement(el).scrollTop;
	},

	// relative canvas/document (outer borders of canvas/document,
	// inside borders of element)
	getInnerLeft:	function (el) {
		return this.getLeft(el) + this.getBorderLeftWidth(el);
	},

	getInnerTop:	function (el) {
		return this.getTop(el) + this.getBorderTopWidth(el);
	},

	// width and height (outer, border-box)
	getWidth:	function (el) {
		return el.offsetWidth;
	},

	getHeight:	function (el) {
		return el.offsetHeight;
	},

	getCanvasElement:	function (el) {
		var doc = el.ownerDocument || el.document;	// IE55 bug
		if (doc.compatMode == "CSS1Compat")
			return doc.documentElement;
		else
			return doc.body;
	},

	getBorderLeftWidth:	function (el) {
		return el.clientLeft;
	},

	getBorderTopWidth:	function (el) {
		return el.clientTop;
	},

	getScreenLeft:	function (el) {
		var doc = el.ownerDocument || el.document;	// IE55 bug
		var w = doc.parentWindow;
		return w.screenLeft + this.getBorderLeftWidth(this.getCanvasElement(el)) +
			this.getClientLeft(el);
	},

	getScreenTop:	function (el) {
		var doc = el.ownerDocument || el.document;	// IE55 bug
		var w = doc.parentWindow;
		return w.screenTop + this.getBorderTopWidth(this.getCanvasElement(el)) +
			this.getClientTop(el);
	}
};

posLib.ua =		navigator.userAgent;
posLib.opera =	/opera [56789]|opera\/[56789]/i.test(posLib.ua);posLib.ie =		(!posLib.opera) && /MSIE/.test(posLib.ua);
posLib.ie6 =	posLib.ie && /MSIE [6789]/.test(posLib.ua);
posLib.moz =	!posLib.opera && /gecko/i.test(posLib.ua);