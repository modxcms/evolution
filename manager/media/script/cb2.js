/*
 * This script was created by Erik Arvidsson (erik@eae.net)
 * for WebFX (http://webfx.eae.net
 * Copyright 2001
 * 
 * For usage see license at http://webfx.eae.net/license.html	
 *
 * Created: 2001-03-17
 */

/*
 * This file depends on emulateAttachEvent and extendEventObject
 * found in ieemu.js to get Mozilla to work
 * 
 * Styling is currently done in a separate css files
 * cb2.css
 *
 */

// Modified By Raymond Irving 19-April-2005 to work with fFirefox 1.0.3

// modified by Raymond
/* Set up IE Emualtion for Mozilla */
//if (window.moz == true && (typeof window.emulateAttachEvent != "function" || typeof window.extendEventObject != "function"))
//	alert("Error! IE Emulation file not included.");

//if (window.moz) {
//	emulateAttachEvent();
//	extendEventObject();
//}
/* end Mozilla specific emulation initiation */
// end:modified by Raymond

function createButton(el) {
	
	// mod by Raymond
	if(!el) return;
	if(!el._created) el = new DynElement(el);
	
	el.addEventListener("onmouseover",	createButton.overCoolButton);
	el.addEventListener("onmouseout",	createButton.outCoolButton);
	el.addEventListener("onmousedown",	createButton.downCoolButton);
	el.addEventListener("onmouseup",		createButton.upCoolButton);
	el.addEventListener("onclick",		createButton.clickCoolButton);	el.addEventListener("ondblclick",	createButton.clickCoolButton);
	el.addEventListener("onkeypress",	createButton.keypressCoolButton);
	el.addEventListener("onkeyup",		createButton.keyupCoolButton);
	el.addEventListener("onkeydown",		createButton.keydownCoolButton);
	el.addEventListener("onfocus",		createButton.focusCoolButton);
	el.addEventListener("onblur",		createButton.blurCoolButton);
	
	el.className = "coolButton";
	
	el.setEnabled	= createButton.setEnabled;
	el.getEnabled	= createButton.getEnabled;
	el.setValue		= createButton.setValue;
	el.getValue		= createButton.getValue;
	el.setToggle	= createButton.setToggle;
	el.getToggle	= createButton.getToggle;
	el.setAlwaysUp	= createButton.setAlwaysUp;
	el.getAlwaysUp	= createButton.getAlwaysUp;
	
	el._enabled		= true;
	el._toggle		= false;
	el._value		= false;
	el._alwaysUp	= false;
	
	return el;
}

createButton.LEFT = window.moz ? 0 : 1;

/* event listeners */

createButton.overCoolButton = function (e) {
	toEl = e.src;
	if (toEl == null) return;
	
	toEl._over = true;
	
	if (!toEl._enabled) return;
	
	createButton.setClassName(toEl);
};

createButton.outCoolButton = function (e) {
	toEl = e.src;
	if (toEl == null) return;

	toEl._over = false;
		
	createButton.setClassName(toEl);
};

createButton.downCoolButton = function (e) {
	if (e.button != createButton.LEFT) return;	

	el = e.src;
	if (el == null) return;
	
	el._down = true;
	
	if (!el._enabled) return;

	createButton.setClassName(el);
};

createButton.upCoolButton = function (e) {
	if (e.button != createButton.LEFT) return;
	
	el = e.src;
	if (el == null) return;
	
	el._down = false;
	
	if (!el._enabled) return;
	
	if (el._toggle)
		el.setValue(!el._value);
	else
		createButton.setClassName(el);
};

createButton.clickCoolButton = function (e) {
	el = e.src;
	if (el == null) return;
	el.onaction = el.getAttribute("onaction");
	if (el == null || !el._enabled || el.onaction == "" || el.onaction == null) return;
};

createButton.keypressCoolButton = function (e) {
	el = e.src;
	if (el == null) return;
	if (el == null || !el._enabled || window.event.keyCode != 13) return;
	
	el.setValue(!el._value);
	
	if (el.onaction == null) return;
	
	if (typeof el.onaction == "string")
		el.onaction = new Function ("event", el.onaction);
	
	el.onaction(window.event);
};

createButton.keydownCoolButton = function (e) {
	el = e.src;
	if (el == null) return;
	if (el == null || !el._enabled || window.event.keyCode != 32) return;
	createButton.downCoolButton();
};

createButton.keyupCoolButton = function (e) {
	el = e.src;
	if (el == null) return;
	if (el == null || !el._enabled || window.event.keyCode != 32) return;
	createButton.upCoolButton();
	
	//el.setValue(!el._value);	// is handled in upCoolButton()
	
	if (el.onaction == null) return;
	
	if (typeof el.onaction == "string")
		el.onaction = new Function ("event", el.onaction);
	
	el.onaction(window.event);
};

createButton.focusCoolButton = function (e) {
	el = e.src;
	if (el == null) return;
	if (el == null || !el._enabled) return;
	createButton.setClassName(el);
};

createButton.blurCoolButton = function (e) {
	el = e.src;
	if (el == null) return;
	
	createButton.setClassName(el)
};


/* end event listeners */

createButton.setClassName = function (el) {
	var over = el._over;
	var down = el._down;
	var focused;
	try {
		focused = (el == document.activeElement && el.tabIndex > 0);
	}
	catch (exc) {
		focused = false;
	}
	
	if (!el._enabled) {
		if (el._value)
			el.className = "coolButtonActiveDisabled";
		else
			el.className = el._alwaysUp ? "coolButtonUpDisabled" : "coolButtonDisabled";
	}
	else {
		if (el._value) {
			if (over || down || focused)
				el.className = "coolButtonActiveHover";
			else
				el.className = "coolButtonActive";
		}
		else {
			if (down)
				el.className = "coolButtonActiveHover";
			else if (over || el._alwaysUp || focused)
				el.className = "coolButtonHover";
			else
				el.className = "coolButton";
		}
	}
};

createButton.setEnabled = function (b) {
	if (this._enabled != b) {
		this._enabled = b;
		createButton.setClassName(this, false, false);
		if (!window.moz) {
			if (b)
				this.setInnerHTML(this.firstChild.firstChild.innerHTML);
			else
				this.setInnerHTML("<span class='coolButtonDisabledContainer'><span class='coolButtonDisabledContainer'>" + this.innerHTML + "</span></span>");
		}
	}
};

createButton.getEnabled = function () {
	return this._enabled;
};

createButton.setValue = function (v, bDontTriggerOnChange) {
	if (this._toggle && this._value != v) {
		this._value = v;
		createButton.setClassName(this, false, false);
		
		this.onchange = this.getAttribute("onchange");
		
		if (this.onchange == null || this.onchange == "" || bDontTriggerOnChange) return;
		
		if (typeof this.onchange == "string")
			this.onchange = new Function("", this.onchange);

		this.onchange();
	}
};

createButton.getValue = function () {
	return this._value;
};

createButton.setToggle = function (t) {
	if (this._toggle != t) {
		this._toggle = t;
		if (!t) this.setValue(false);
	}
};

createButton.getToggle = function () {
	return this._toggle;
};

createButton.setAlwaysUp = function (up) {
	if (this._alwaysUp != up) {
		this._alwaysUp = up;
		createButton.setClassName(this, false, false);
	}
};

createButton.getAlwaysUp = function () {
	return this._alwaysUp;
};