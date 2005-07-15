/*
	Web Application Framework functions
	Created 23-Jan-2005 By Raymond Irving - Based on DynAPI 3
	
	This Framework is Distribution is distributed under the terms of the GNU LGPL license.
*/


// WebElement - Basic 
function WebElement(elm) {
	var b,dt;
	if(!elm) return;
	dt = typeof(elm);
	if (dt=='string') elm = document.getElementById(elm);
	else if(dt!='object') return;
	if(!elm) return;
	if(!elm.id) elm.id = "WebElm" + document._cn++;
	// assign functions
	var o = WebElement;
	if (!elm.style) elm.style = elm; // ns4
	if (!elm._created) { // check if already created
		elm._created				= true;
		elm.enabled 				= (elm.disabled)? true:false;
		elm.setId 					= o.setId;
		elm.addEventListener		= o.addEventListener;
		elm.removeEventListener		= o.removeEventListener;
		elm.removeAllEventListeners	= o.removeAllEventListeners;
		elm.invokeEvent				= o.invokeEvent;
	}
	return elm;
};
var w = WebElement;
w._evtCnt = 0;
w.Zero = function(){return 0};
w.setId = function(id) {
	var d = document;
	if (this.id) delete d.__objcache[this.id];
	this.id = id; d.__objcache[this.id] = this;
};
w.addEventListener = function(evt,fn) {
	var oldevtfn;
	var el,evtid;
	if(!evt) return null;
	else evt = (evt+'').toLowerCase();
	if (fn) {
		if (this[evt] && this[evt]!=WebElement._eventHandler) {
			oldevtfn = this[evt];
			this[evt] = "";
			if (typeof oldevtfn == "string")
				oldevtfn = new Function ("event", oldevtfn);
		}
		if(!this._events) this._events={};
		if(!(el=this._events[evt])) this._events[evt]=el=[];
		evtId = evt + (WebElement._evtCnt++);
		el[el.length]=evtId;
		this[evtId]=fn;
	}
	if(evt!="onload" && evt!="onunload" && evt!="onresize" && evt!="onscroll") {
		this[evt] = WebElement._eventHandler;
	}
	if(oldevtfn) this.addEventListener(evt,oldevtfn);
	if (evt=='oninit' && document.init) this[evtId]();
	else if (evt=='onload' && document.loaded) this[evtId]();
};
w.removeEventListener = function(evt) {
	var i,el;
	if(!evt) return null;
	else evt = (evt+'').toLowerCase();
	if (!this._events) return null;
	if(el = this._events[evt]) delete this._events[evt];
	if (el) for(i=0;i<el.length;i++){
		delete this[el[i]];
	}	
};
w.removeAllEventListeners = function() {
	var evt;
	for(evt in this._events) this.removeEventListener(evt);
	delete this._events;
	this._events = [];
};
w.invokeEvent = function(evt,e,args) {
	var i,id,fn,rt,el; 
	evt = (evt+'').toLowerCase();
	if (!e) e = new WebEvent(evt,this);
	e.src = this;
	e.type = evt;
	
	if(this._events) {;
		if((el = this._events[evt])) for (var i=0;i<el.length;i++) {
			fn = this[el[i]];
			if(fn) fn(e,args);
			if (!e.propagate) break;
		}
	}
	id = this.id;
	if (this == document) id = 'document';
	fn = document.winframe[id+'_'+evt];
	if (fn) rt = fn(e,args);
	if (evt=='onload' && id=='document') document.loaded = true;
	return rt;
};
w._eventHandler = function(e){
	if(!e) e = document.winframe.event;
	evt = ('on'+e.type).toLowerCase();
	e1 = new WebEvent(evt,this,e);
	this.invokeEvent(evt,e1);
};

// WebEvent
function WebEvent(type,src,e) {
	this.type = type;
	this.src = src;
	this.origin = src;
	this.propagate = true;
	this.bubble = false;
	this.bubbleChild = null;
	this.defaultValue = true;
	this.button = (e) ? e.button:0;
};
w = WebEvent.prototype;
w.getType = function() {return this.type};
w.getSource = function() {return this.src};
w.getOrigin=function() {return this.origin};
w.stopPropagation = function() {this.propagate = false};
w.preventBubble = function() {this.bubble = false};
w.preventDefault = function() {this.defaultValue = false};
w.getBubbleChild = function() {return this.bubbleChild};


// Document - the main object
var d= document;
d._cn = 0; 							//counter
d.initWebElm = true;
d.version = '1.0 Beta 1';
d.init = false;
d.loaded = false;
d.loadedjs = [];
d.__objcache = {};
d = new WebElement(document);	// wrap webelement around document;
d.ua = new UserAgent();
d.api = {
	True : function() {return true},
	False : function() {return false},
	Null : function() {},
	Zero : function() {return 0;},
	Allow : function() {
		event.cancelBubble = true;
		return true;
	},
	Deny : function() {
		event.cancelBubble = false;
		return false;
	},
	getURLArguments : function(o) {  // pass a string or frame/layer object
		var url,l={};
		var ua = document.ua;
		if (typeof(o)=="string") url = o;
		else if (ua.ns4 && o.src) url = o.src;
		else if (o.document) url = o.document.location.href;
		else return l;
		var s = url.substring(url.indexOf('?')+1);
		var a = s.split('&');
		for (var i=0;i<a.length;i++) {
			var b = a[i].split('=');
			l[b[0]] = unescape(b[1]);
		}
		return l;
	}
};

// setup winframe
d.winframe = window;
winFrameOnload = function() {
	var rt,d = document;
	d.loaded = true;
	if(d.winframe.document_onclick) d.addEventListener("onclick",d.winframe.document_onclick);
	if (!d.ua.supported) rt = d.invokeEvent("onunsupported");
	if(rt!=false) setTimeout("document.invokeEvent('onload')",1);
};
winFrameOnunload = function() {
	var d = document;
	d.invokeEvent('onunload');
};
winFrameOnresize = function() {
	var d = document;
	d.__onResize();
	setTimeout("document.invokeEvent('onresize')",1);
};
winFrameOnscroll = function() {
	var d = document;
	setTimeout("document.invokeEvent('onscroll')",1);
};

if(window.attachEvent) {
	d.winframe.attachEvent("onload",winFrameOnload);
	d.winframe.attachEvent("onunload",winFrameOnunload);
	d.winframe.attachEvent("onresize",winFrameOnresize);
	d.winframe.attachEvent("onscroll",winFrameOnscroll);
}
else {
	if(d.winframe.onload) d.addEventListener("onload",d.winframe.onload);
	if(d.winframe.onunload) d.addEventListener("onunload",d.winframe.onunload);
	if(d.winframe.onresize) d.addEventListener("onresize",d.winframe.onresize);
	if(d.winframe.onscroll) d.addEventListener("onscroll",d.winframe.onscroll);
	d.winframe.onload = winFrameOnload;
	d.winframe.onunload = winFrameOnunload;
	d.winframe.onresize = winFrameOnresize;
	d.winframe.onscroll = winFrameOnscroll;
	if(d.winframe.captureEvents) {
		d.winframe.captureEvents(Event.LOAD);
		d.winframe.captureEvents(Event.UNLOAD);
		d.winframe.captureEvents(Event.RESIZE);
		d.winframe.captureEvents(Event.SCROLL);
	}
}

// set doc path and include path
url = d.location.href;
url = url.substring(0,url.lastIndexOf('/')+1);
d.documentPath = url;
d.incpath = (self.SCRIPT_INCLUDE_PATH) ? self.SCRIPT_INCLUDE_PATH:"";

// set include path
d.setIncludePath = function(p) {
	if (p==this.incpath) return null;
	this.incpath = p;
	document.invokeEvent("oninit");
	document.init = true;
};
// writes the <script> tag for the object
d.include = function(src) { 
	var id = src;
	if (!src ||	this.loadedjs[src]==true) return null;	
	else src = src+'';
	src = src.toLowerCase();
	if (src.indexOf('.')<0) src += ".js";
	if (src.indexOf('/')<0) src = this.incpath+src;
	src = src.replace(/\\/g,'/');
	this.loadedjs[src]=true;
	if (!this.loaded) this.winframe.document.write('<script type="text/javascript" language="JavaScript" src="'+src+'"><\/script>');
	else {
		// dynamic loading
		//this.load(src);
	}	
	debug ('loading: '+src); //debug out
	if (id=='dynelement') {
		if (this.ua.ie) this.include("ext\\dynelement_ie");
		else if (this.ua.ns4) document.include("ext\\dynelement_ns4");
		else if (this.ua.opera) document.include("ext\\dynelement_opera");
		else this.include("ext\\dynelement_dom");
	}
};

d.getWidth = function() {
	this.findDimensions();
	return this.w;
};
d.getHeight = function() {
	this.findDimensions();
	return this.h;
};
d.findDimensions = function() {
	var d=this, ua=d.ua;
	var docElm = (document.documentElement && document.documentElement.scrollTop>0) ? document.documentElement:document.body;	
	d.w=(ua.ie)? docElm.clientWidth : d.winframe.innerWidth;
	d.h=(ua.ie)? docElm.clientHeight : d.winframe.innerHeight;
};
d.getScrollTop = function() {
  var scrOfY = 0;
  if(typeof(window.pageYOffset)=='number') {
	//Netscape compliant
	scrOfY = window.pageYOffset;
  }
  else {
	//DOM and IE compliant
	var docElm = (document.documentElement && document.documentElement.scrollTop>0) ? document.documentElement:document.body;	
	scrOfY = docElm.scrollTop;
  }
  return scrOfY;
};

// Resize handling
d.__onResize = function() {
	var d = document;
	var ua = d.ua;
	var w = d.w;
	var h = d.h;
	d.findDimensions();
	if (d.w!=w || d.h!=h) {
		if (ua.ns4) d.location.href = d.location.href;
		else {
			if(d._updateAnchors) d._updateAnchors();
		}
	}
};

d.__getElementById = d.getElementById;
d.getElementById = function(id, doc) {
	var f,p,i,o;  
	var ua = this.ua;
	
	oid = id;
	
	// check cache first
	o = document.__objcache[oid];
	if(o) return o;
	if(!doc) doc = this; 
	if((p = id.indexOf("@"))>0) {  		
		fn = id.substring(p+1);
		f = this.getFrame(fn);
		if (f) {
			doc=f.document; 
			doc.__getElementById = doc.getElementById;
		}
		id=id.substring(0,p);
	}
		
	if(ua.ie && !(o=doc[id])) o=doc.all[id];
	else if (ua.dom) o=doc.__getElementById(id);

	// look in forms
	for (i=0;!o && i<doc.forms.length;i++) {
		o = doc.forms[i].elements[id];		
	}
	// loook in ns4 layers
	if (!o && doc.layers) o = doc.layers[id];
	for(i=0;!o && ua.ns4 && i<doc.layers.length;i++) {
		o = document.getElementById(id,doc.layers[i].document); 		
	}	
	// look in images
	if (!o && doc.images) o = doc.images[id];
	for (i=0;!o && i<doc.images.length;i++) {
		o = doc.images[id];		
	}	
	// loook in ns4 links
	for(i=0;!o && ua.ns4 && i<document.links.length;i++) {
		if(document.links[i].href.indexOf("'" + id + "'")>0) {
			o = document.links[i];
			break;
		}	
	}
	// save object  to cache
	document.__objcache[oid] = o;
						
	return o;
};

d.getFrame = function (fn){
	var f;
	if (this.frames) f=this.frames[fn];
	if (!f) f= parent.frames[fn];
	if (!f) f= window.frames[fn];
	if (!f && window.frames['top']) f= window.frames['top'].frames[fn];
	return f;
};

// Debug
d.debug = {};
d._debugBuffer = '';
debug = function(s){document.debug.print(s)};
d.debug.print = function(s) {
	//@IF:DEBUG[
		if(s==null) s='';
		document._debugBuffer += s + '\n';
	//]:DEBUG
};

// write functions
write = function(s){document.write(s)};
writeln = function(s){document.writeln(s)};

// UserAgent
function UserAgent() {
	var ua = navigator.userAgent
	this.ns4 = (document.layers)? true:false;
	this.ie = (document.all&&(!window.opera))? true:false;
	this.dom = (document.getElementById)? true:false;
	this.ns6 = (window.sidebar)? true:false;
	this.moz = (window.sidebar||ua.indexOf('Gecko')!=-1)? true:false;
	this.opera = (window.opera)? true:false;
	this.mac = (ua.indexOf('Mac')!=-1)? true:false;
	this.win32 =(ua.indexOf("win")>-1)? true:false;
	this.ns = this.ns6 || this.ns4;
	this.def = (this.ie||this.dom)?true:false; //dom and ie are the default browsers
	this.supported = (this.def||this.ns4||this.ns6||this.opera)? true:false;
	// setup UA version
	var v = navigator.appVersion;
	this.ver = parseInt(v);
	if (this.ie){
		if (v.indexOf('MSIE 7')>0) 			{this.ie7=true; this.ver = 7;}
		else if (v.indexOf('MSIE 6')>0) 	{this.ie6=true; this.ver = 6;}
		else if (v.indexOf('MSIE 5.5')>0)	{this.ie55=true; this.ver = 5.5;}
		else if (v.indexOf('MSIE 5')>0) 	{this.ie5=true; this.ver = 5;}
		else if (v.indexOf('MSIE 4')>0) 	{this.ie4=true; this.ver = 4;}
	}else if (this.opera) {
		this.ver=parseInt(ua.substr(ua.indexOf("opera")+6,1)); // set opera version
		this.opera6=(this.v>=6);
		this.opera7=(this.v>=7);
	}	
}


