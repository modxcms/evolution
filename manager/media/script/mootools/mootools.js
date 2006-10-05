//	mootools.js: moo javascript tools
//	by Valerio Proietti (http://mad4milk.net) MIT-style license.
	
//  CREDITS:

//	Class is slightly based on Base.js : http://dean.edwards.name/weblog/2006/03/base/
//		(c) 2006 Dean Edwards, License: http://creativecommons.org/licenses/LGPL/2.1/

//	Some functions are based on those found in prototype.js : http://prototype.conio.net/
//		(c) 2005 Sam Stephenson <sam@conio.net>, MIT-style license


//moo.js : My Object Oriented javascript - has no dependancies

var Class = function(properties){
	var klass = function(){
		for (p in this) this[p]._proto_ = this;
		if (arguments[0] != 'noinit' && this.initialize) return this.initialize.apply(this, arguments);
	};
	klass.extend = this.extend;
	klass.implement = this.implement;
	klass.prototype = properties;
	return klass;
};

Class.empty = function(){};

Class.create = function(properties){
	return new Class(properties);
};

Class.prototype = {
	extend: function(properties){
		var prototype = new this('noinit');
		for (property in properties){
			var previous = prototype[property];
			var current = properties[property];
			if (previous && previous != current) current = previous.parentize(current) || current;
			prototype[property] = current;
		}
		return new Class(prototype);
	},
	
	implement: function(properties){
		for (property in properties) this.prototype[property] = properties[property];
	}
}

Object.extend = function(){
	var args = arguments;
	if (args[1]) args = [args[0], args[1]];
	else args = [this, args[0]];
	for (property in args[1]) args[0][property] = args[1][property];
	return args[0];
};

Object.Native = function(){
	for (var i = 0; i < arguments.length; i++) arguments[i].extend = Class.prototype.implement;
};

new Object.Native(Function, Array, String);

Function.extend({
	parentize: function(current){
		var previous = this;
		return function(){
			this.parent = previous;
			return current.apply(this, arguments);
		};
	}
});

//Function.js : Function extension - Depends on Moo.js

Function.extend({
	
	pass: function(args, bind){
		var fn = this;
		if ($type(args) != 'array') args = [args];
		return function(){
			fn.apply(bind || fn._proto_ || fn, args);
		};
	},

	bind: function(bind){
		var fn = this;
		return function(){
			return fn.apply(bind, arguments);
		};
	},

	bindAsEventListener: function(bind){
		var fn = this;
		return function(event){
			fn.call(bind, event || window.event);
			return false;
		};
	},

	delay: function(ms, bind){
		return setTimeout(this.bind(bind || this._proto_ || this), ms);
	},

	periodical: function(ms, bind){
		return setInterval(this.bind(bind || this._proto_ || this), ms);
	}

});

function $clear(timer){
	clearTimeout(timer);
	clearInterval(timer);
	return null;
};

function $type(obj, types){
	if (!obj) return false;
	var type = false;
	if (obj instanceof Function) type = 'function';
	else if (obj.nodeName){
		if (obj.nodeType == 3 && !/\S/.test(obj.nodeValue)) type = 'textnode';
		else if (obj.nodeType == 1) type = 'element';
	}
	else if (obj instanceof Array) type = 'array';
	else if (typeof obj == 'object') type = 'object';
	else if (typeof obj == 'string') type = 'string';
	else if (typeof obj == 'number' && isFinite(obj)) type = 'number';
	return type;
};

function $check(obj, objTrue, objFalse){
	if (obj) {
		if (objTrue && $type(objTrue) == 'function') return objTrue();
		else return objTrue || obj;
	} else {
		if (objFalse && $type(objFalse) == 'function') return objFalse();
		return objFalse || false;
	}
};

var Chain = new Class({

	chain: function(fn){
		this.chains = this.chains || [];
		this.chains.push(fn);
		return this;
	},

	callChain: function(){
		if (this.chains && this.chains.length) this.chains.splice(0, 1)[0].delay(10, this);
	}

});

//Array.js : Array extension - depends on Moo.js

if (!Array.prototype.forEach){
	Array.prototype.forEach = function(fn, bind){
		for(var i = 0; i < this.length ; i++) fn.call(bind, this[i], i);
	};
}

Array.extend({
	
	each: Array.prototype.forEach,
	
	copy: function(){
		var nArray = [];
		for (var i = 0; i < this.length; i++) nArray.push(this[i]);
		return nArray;
	},
	
	remove: function(item){
		for (var i = 0; i < this.length; i++){
			if (this[i] == item) this.splice(i, 1);
		}
		return this;
	},
	
	test: function(item){
		for (var i = 0; i < this.length; i++){
			if (this[i] == item) return true;
		};
		return false;
	},
	
	extend: function(nArray){
		for (var i = 0; i < nArray.length; i++) this.push(nArray[i]);
		return this;
	}
	
});

function $A(array){
	return Array.prototype.copy.call(array);
};

//String.js : String extension - depends on Moo.js

String.extend({

	test: function(value, params){
		return this.match(new RegExp(value, params));
	},

	camelCase: function(){
		return this.replace(/-\D/gi, function(match){
			return match.charAt(match.length - 1).toUpperCase();
		});
	},

	capitalize: function(){
		return this.toLowerCase().replace(/\b[a-z]/g, function(match){
			return match.toUpperCase();
		});
	},

	trim: function(){
		return this.replace(/^\s*|\s*$/g,'');
	},

	clean: function(){
		return this.replace(/\s\s/g, ' ').trim();
	},

	rgbToHex: function(array){
		var rgb = this.test('^[rgba]{3,4}\\(([\\d]{0,3}),[\\s]*([\\d]{0,3}),[\\s]*([\\d]{0,3})\\)$');
		var hex = [];
		for (var i = 1; i < rgb.length; i++) hex.push((rgb[i]-0).toString(16));
		var hexText = '#'+hex.join('');
		if (array) return hex;
		else return hexText;
	},

	hexToRgb: function(array){
		var hex = this.test('^[#]{0,1}([\\w]{1,2})([\\w]{1,2})([\\w]{1,2})$');
		var rgb = [];
		for (var i = 1; i < hex.length; i++){
			if (hex[i].length == 1) hex[i] += hex[i];
			rgb.push(parseInt(hex[i], 16));
		}
		var rgbText = 'rgb('+rgb.join(',')+')';
		if (array) return rgb;
		else return rgbText;
	}

});

//Element.js : Element methods - depends on Moo.js + Native Scripts

var Element = new Class({

	//creation

	initialize: function(el){
		if ($type(el) == 'string') el = document.createElement(el);
		return $(el);
	},

	//injecters

	inject: function(el, where){
		var el = $check($(el), $(el), new Element(el));
		switch(where){
			case "before": $(el.parentNode).insertBefore(this, el); break;
			case "after": {
					if (!el.getNext()) $(el.parentNode).appendChild(this);
					else $(el.parentNode).insertBefore(this, el.getNext());
			} break;
			case "inside": el.appendChild(this); break;
		}
		return this;
	},

	injectBefore: function(el){
		return this.inject(el, 'before');
	},

	injectAfter: function(el){
		return this.inject(el, 'after');
	},

	injectInside: function(el){
		return this.inject(el, 'inside');
	},

	adopt: function(el){
		var el = $check($(el), $(el), new Element(el));
		this.appendChild(el);
		return this;
	},

	//actions
	
	remove: function(){
		this.parentNode.removeChild(this);
	},

	clone: function(){
		return $(this.cloneNode(true));
	},

	replaceWith: function(el){
		var el = $check($(el), $(el), new Element(el));
		this.parentNode.replaceChild(el, this);
		return el;
	},
	
	appendText: function(text){
		if (this.getTag() == 'style' && window.ActiveXObject) this.styleSheet.cssText = text;
		else this.appendChild(document.createTextNode(text));
		return this;
	},

	//classnames

	hasClassName: function(className){
		return $check(this.className.test("\\b"+className+"\\b"), true);
	},

	addClassName: function(className){
		if (!this.hasClassName(className)) this.className = (this.className+' '+className.trim()).clean();
		return this;
	},

	removeClassName: function(className){
		if (this.hasClassName(className)) this.className = this.className.replace(className.trim(), '').clean();
		return this;
	},

	toggleClassName: function(className){
		if (this.hasClassName(className)) return this.removeClassName(className);
		else return this.addClassName(className);
	},

	//styles

	setStyle: function(property, value){
		if (property == 'opacity') this.setOpacity(value);
		else this.style[property.camelCase()] = value;
		return this;
	},

	setStyles: function(source){
		if ($type(source) == 'object') {
			for (property in source) this.setStyle(property, source[property]);
		} else if ($type(source) == 'string') this.setAttribute('style', source);
		return this;
	},

	setOpacity: function(opacity){
		if (opacity == 0 && this.style.visibility != "hidden") this.style.visibility = "hidden";
		else if (this.style.visibility != "visible") this.style.visibility = "visible";
		if (window.ActiveXObject) this.style.filter = "alpha(opacity=" + opacity*100 + ")";
		this.style.opacity = opacity;
		return this;
	},

	getStyle: function(property, num){
		var proPerty = property.camelCase();
		var style = $check(this.style[proPerty]);
		if (!style) {
			if (document.defaultView) style = document.defaultView.getComputedStyle(this,null).getPropertyValue(property);
			else if (this.currentStyle) style = this.currentStyle[proPerty];
		}
		if (style && ['color', 'backgroundColor', 'borderColor'].test(proPerty) && style.test('rgb')) style = style.rgbToHex();
		if (['auto', 'transparent'].test(style)) style = 0;
		if (num) return parseInt(style);
		else return style;
	},

	removeStyles: function(){
		$A(arguments).each(function(property){
			this.style[property.camelCase()] = '';
		}, this);
		return this;
	},

	//events

	addEvent: function(action, fn){
		this[action+fn] = fn.bind(this);
		if (this.addEventListener) this.addEventListener(action, fn, false);
		else this.attachEvent('on'+action, this[action+fn]);
		var el = this;
		if (this != window) Unload.functions.push(function(){
			el.removeEvent(action, fn);
			el[action+fn] = null;
		});
		return this;
	},

	removeEvent: function(action, fn){
		if (this.removeEventListener) this.removeEventListener(action, fn, false);
		else this.detachEvent('on'+action, this[action+fn]);
		return this;
	},

	//get non-text elements

	getBrother: function(what){
		var el = this[what+'Sibling'];
		while ($type(el) == 'textnode') el = el[what+'Sibling'];
		return $(el);
	},

	getPrevious: function(){
		return this.getBrother('previous');
	},

	getNext: function(){
		return this.getBrother('next');
	},

	getFirst: function(){
		var el = this.firstChild;
		while ($type(el) == 'textnode') el = el.nextSibling;
		return $(el);
	},

	//properties

	setProperty: function(property, value){
		var el = false;
		switch(property){
			case 'class': this.className = value; break;
			case 'style': this.setStyles(value); break;
			case 'name': if (window.ActiveXObject && this.getTag() == 'input'){
				el = $(document.createElement('<input name="'+value+'" />'));
				$A(this.attributes).each(function(attribute){
					if (attribute.name != 'name') el.setProperty(attribute.name, attribute.value);
					
				});
				if (this.parentNode) this.replaceWith(el);
			};
			default: this.setAttribute(property, value);
		}
		return el || this;
	},

	setProperties: function(source){
		for (property in source) this.setProperty(property, source[property]);
		return this;
	},

	setHTML: function(html){
		this.innerHTML = html;
		return this;
	},

	getProperty: function(property){
		return this.getAttribute(property);
	},

	getTag: function(){
		return this.tagName.toLowerCase();
	},

	//position

	getOffset: function(what){
		what = what.capitalize();
		var el = this;
		var offset = 0;
		do {
			offset += el['offset'+what] || 0;
			el = el.offsetParent;
		} while (el);
		return offset;
	},

	getTop: function(){
		return this.getOffset('top');
	},

	getLeft: function(){
		return this.getOffset('left');
	}

});

function $Element(el, method, args){
	if ($type(args) != 'array') args = [args];
	return Element.prototype[method].apply(el, args);
};

new Object.Native(Element);

function $(el){
	if ($type(el) == 'string') el = document.getElementById(el);
	if ($type(el) == 'element'){
		if (!el.extend){
			Unload.elements.push(el);
			el.extend = Object.extend;
			el.extend(Element.prototype);
		}
		return el;
	} else return false;
};

//garbage collector

window.addEvent = Element.prototype.addEvent;
window.removeEvent = Element.prototype.removeEvent;

var Unload = {

	elements: [], functions: [], vars: [],
	
	unload: function(){
		Unload.functions.each(function(fn){
			fn();
		});
		
		window.removeEvent('unload', window.removeFunction);
		
		Unload.elements.each(function(el){
			for(p in Element.prototype){
				window[p] = null;
				document[p] = null;
				el[p] = null;
			}
			el.extend = null;
		});
	}
	
};
window.removeFunction = Unload.unload;
window.addEvent('unload', window.removeFunction);

//Fx.js - depends on Moo.js + Native Scripts

var Fx = fx = {};

Fx.Base = new Class({

	setOptions: function(options){
		this.options = Object.extend({
			duration: 500,
			onComplete: Class.empty,
			onStart: Class.empty,
			unit: 'px',
			wait: true,
			transition: Fx.sinoidal,
			fps: 30
		}, options || {});
	},

	step: function(){
		var currentTime  = (new Date).getTime();
		if (currentTime >= this.options.duration+this.startTime){
			this.clearTimer();
			this.now = this.to;
			this.options.onComplete.pass(this.el, this).delay(10);
			this.callChain();
		} else {
			this.tPos = (currentTime - this.startTime) / this.options.duration;
			this.setNow();
		}
		this.increase();
	},

	setNow: function(){
		this.now = this.compute(this.from, this.to);
	},

	compute: function(from, to){
		return this.options.transition(this.tPos) * (to-from) + from;
	},

	custom: function(from, to){
		if(!this.options.wait) this.clearTimer();
		if (this.timer) return;
		this.options.onStart.pass(this.el, this).delay(10);
		this.from = from;
		this.to = to;
		this.startTime = (new Date).getTime();
		this.timer = this.step.periodical(Math.round(1000/this.options.fps), this);
		return this;
	},

	set: function(to){
		this.now = to;
		this.increase();
		return this;
	},

	clearTimer: function(){
		this.timer = $clear(this.timer);
		return this;
	},

	setStyle: function(el, property, value){
		if (property == 'opacity'){
			if (value == 1 && navigator.userAgent.test('Firefox')) value = 0.9999;
			el.setOpacity(value);
		} else el.setStyle(property, value+this.options.unit);
	}

});

Fx.Base.implement(new Chain);

Fx.Style = Fx.Base.extend({

	initialize: function(el, property, options){
		this.el = $(el);
		this.setOptions(options);
		this.property = property.camelCase();
	},
	
	hide: function(){
		return this.set(0);
	},
	
	goTo: function(val){
		return this.custom(this.now || 0, val);
	},
	
	increase: function(){
		this.setStyle(this.el, this.property, this.now);
	}

});

Fx.Layout = Fx.Style.extend({
	
	initialize: function(el, layout, options){
		this.parent(el, layout, options);
		this.layout = layout.capitalize();
		this.el.setStyle('overflow', 'hidden');
	},
	
	toggle: function(){
		if (this.el['offset'+this.layout] > 0) return this.custom(this.el['offset'+this.layout], 0);
		else return this.custom(0, this.el['scroll'+this.layout]);
	},

	show: function(){
		return this.set(this.el['scroll'+this.layout]);
	}
	
});

Fx.Height = Fx.Layout.extend({

	initialize: function(el, options){
		this.parent(el, 'height', options);
	}

});

Fx.Width = Fx.Layout.extend({

	initialize: function(el, options){
		this.parent(el, 'width', options);
	}

});

Fx.Opacity = Fx.Style.extend({

	initialize: function(el, options){
		this.parent(el, 'opacity', options);
		this.now = 1;
	},

	toggle: function(){
		if (this.now > 0) return this.custom(1, 0);
		else return this.custom(0, 1);
	},

	show: function(){
		this.set(1);
	}

});

Element.extend({

	effect: function(property, options){
		return new Fx.Style(this, property, options);
	}

});

Fx.sinoidal = function(pos){return ((-Math.cos(pos*Math.PI)/2) + 0.5);}; //this transition is from script.aculo.us

Fx.linear = function(pos){return pos;};

Fx.cubic = function(pos){return Math.pow(pos, 3);};

Fx.circ = function(pos){return Math.sqrt(pos);};

//Tips.js : Display a tip on any element with a title and/or href - depends on Moo.js + Native Scripts +  Fx.js
//Credits : Tips.js is based on Bubble Tooltips (http://web-graphics.com/mtarchive/001717.php) by Alessandro Fulcitiniti (http://web-graphics.com)

var Tips = new Class({

	setOptions: function(options){
		this.options = {
			transitionStart: fx.sinoidal,
			transitionEnd: fx.sinoidal,
			maxTitleChars: 30,
			fxDuration: 150,
			maxOpacity: 1,
			timeOut: 100,
			className: 'tooltip'
		}
		Object.extend(this.options, options || {});
	},

	initialize: function(elements, options){
		this.elements = elements;
		this.setOptions(options);
		this.toolTip = new Element('div').addClassName(this.options.className).setStyle('position', 'absolute').injectInside(document.body);
		this.toolTitle = new Element('H4').injectInside(this.toolTip);
		this.toolText = new Element('p').injectInside(this.toolTip);
		this.fx = new fx.Style(this.toolTip, 'opacity', {duration: this.options.fxDuration, wait: false}).hide();
		$A(elements).each(function(el){
			$(el).myText = $check(el.title);
			if (el.myText) el.removeAttribute('title');
			if (el.href){
				if (el.href.test('http://')) el.myTitle = el.href.replace('http://', '');
				if (el.href.length > this.options.maxTitleChars) el.myTitle = el.href.substr(0,this.options.maxTitleChars-3)+"...";
			}
			if (el.myText && el.myText.test('::')){
				var dual = el.myText.split('::');
				el.myTitle = dual[0].trim();
				el.myText = dual[1].trim();
			} 
			el.onmouseover = function(){
				this.show(el);
				return false;
			}.bind(this);
			el.onmousemove = this.locate.bindAsEventListener(this);
			el.onmouseout = function(){
				this.timer = $clear(this.timer);
				this.disappear();
			}.bind(this);
		}, this);
	},

	show: function(el){
		this.toolTitle.innerHTML = el.myTitle;
		this.toolText.innerHTML = el.myText;
		this.timer = $clear(this.timer);
		this.fx.options.transition = this.options.transitionStart;
		this.timer = this.appear.delay(this.options.timeOut, this);
	},

	appear: function(){
		this.fx.custom(this.fx.now, this.options.maxOpacity);
	},

	locate: function(evt){
		var doc = document.documentElement;
		this.toolTip.setStyles({'top': evt.clientY + doc.scrollTop + 15 + 'px', 'left': evt.clientX + doc.scrollLeft - 30 + 'px'});
	},

	disappear: function(){
		this.fx.options.transition = this.options.transitionEnd;
		this.fx.custom(this.fx.now, 0);
	}

});