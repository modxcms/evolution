/*
	DynElement - Web Application Framework
	Created 23-Jan-2005 By Raymond - Based on DynAPI 3
	
	This Framework is Distribution is distributed under the terms of the GNU LGPL license.
*/


// DynElement - Dynamic Element
function DynElement(id) {
	
	var b;
	var elm = WebElement(id); // get element
	var ua = document.ua;

	if (!elm) return null;

	b = elm.style.visibility;
	elm.z = 1;
	elm._saveAnchor = false;
	elm._textSelectable = true;
	elm.visible = (b=="inherit" || b=="show" || b=="visible" || b=="") ? true:false;

	if (ua.def) {
		if (ua.ie) {
			var css = elm.currentStyle;
			elm.x = parseInt(css.left)||0;
			elm.y = parseInt(css.top)||0;
			elm.w = document.ua.ie4? css.pixelWidth : elm.offsetWidth;
			elm.h = document.ua.ie4? css.pixelHeight : elm.offsetHeight;
			elm.bgImage = css.backgroundImage;
			elm.bgColor = css.backgroundColor;
			elm.html = elm.innerHTML;
		}	
		else if (ua.dom) {
			var css = elm.style;
			elm.x = parseInt(elm.offsetLeft)||0;
			elm.y = parseInt(elm.offsetTop)||0;
			elm.w = elm.offsetWidth||0;
			elm.h = elm.offsetHeight||0;
			elm.bgImage = css.backgroundImage;
			elm.bgColor = css.backgroundColor;
			elm.html = elm.innerHTML;
		}

	}
	else if (ua.ns4) {
		var css = elm;
		elm.x = parseInt(css.left)||0;
		elm.y = parseInt(css.top)||0;
		elm.w = css.clip.width||0;
		elm.h = css.clip.height||0;
		elm.clip = [css.clip.top,css.clip.right,css.clip.bottom,css.clip.left];
		elm.bgColor = elm.document.bgColor!=''? elm.document.bgColor : null;
		elm.bgImage = css.background.src!=''? css.background.src : null;
		elm.html = '';
	}
	elm.z = css.zIndex;
	
	// upgrade elm to dynelement
	for(i in this) elm[i] = this[i];
	
	return elm;
};
var p = DynElement.prototype;
p.setSize = function(w,h) { //! Overwritten by NS4
	if (this._useMinSize||this._useMaxSize){
		if (this._minW && w<this._minW) w=this._minW;
		if (this._minH && h<this._minH) h=this._minH;
		if (this._maxW && w>this._maxW) w=this._maxW;
		if (this._maxH && h>this._maxH) h=this._maxH;
	}
	var cw = (w!=null && w!=this.w);
	var ch = (h!=null && h!=this.h);
	if (cw) this.w = w<0? 0 : w;
	if (ch) this.h = h<0? 0 : h;
	if (cw||ch) {
		if (this.updateAnchor) this.updateAnchor(); // update this anchor
		if (this.style) {
			if (cw) this.style.width = this.w||0;
			if (ch) this.style.height = this.h||0;
			if (cw || ch) {				
				if(this._needBoxFix) BorderManager.FixBoxModel(this,true);
				else this.style.clip = 'rect(0px '+(this.w||0)+'px '+(this.h||0)+'px 0px)';
			}
			if (this.updateLayout) this.updateLayout(); // what's this?
		}
	}
	return (cw||ch);
};
p.setMaximumSize = function(w,h){
	this._maxW=w; this._maxH=h;
	this._useMaxSize=(w!=h!=null);
	w=(this.w>w)?w:this.w;
	h=(this.h>h)? h:this.h;
	this.setSize(this.w,this.h);
};
p.setMinimumSize = function(w,h){
	this._minW=w; this._minH=h;
	this._useMinSize=(w!=h!=null);
	this.setSize(this.w,this.h);
};
p.setX=function(x) {this.setLocation(x,null)};
p.setY=function(y) {this.setLocation(null,y)};
p.getX=function() {return this.x||0};
p.getY=function() {return this.y||0};
p.getVisible=function() {return this.visible};
p.getZIndex=function() {return this.z};
p.setZIndex=function(z) {
	if (typeof(z)=="object") {
		if (z.above) this.z = z.above.z + 1;
		else if (z.below) this.z = z.below.z - 1;
		else if (z.topmost) this.z = (DynLayer._z)? (DynLayer._z++):(DynLayer._z=1000);
	}
	else this.z = z;
	if (this.style) this.style.zIndex = this.z;
};
p.getInnerHTML = function() {return this.html};
p.setWidth=function(w) {this.setSize(w,null)};
p.setHeight=function(h) {this.setSize(null,h)};
p.getWidth=function() {return this.w||0};
p.getHeight=function() {return this.h||0};
p.getPageLocation = function(){
	var x=0,y=0;
	var elm = this;
	while (elm){
		x+=elm.offsetLeft;
		y+=elm.offsetTop;
		elm=elm.parent;
	}
	return {x:x,y:y};
}

p.setClip=function(clip) {	//! Overwritten by NS4
	var cc=this.getClip();
	for (var i=0;i<clip.length;i++) if (clip[i]==null) clip[i]=cc[i];
	this.clip=clip;
	if (this.style==null) return;
	var c=this.style.clip;
	this.style.clip="rect("+clip[0]+"px "+clip[1]+"px "+clip[2]+"px "+clip[3]+"px)";
};
p.getClip=function() {	//! Overwritten by NS4
	if (this.style==null || !this.style.clip) return [0,0,0,0];
	var c = this.style.clip;
	if (c) {
		if (c.indexOf("rect(")>-1) {
			c=c.split("rect(")[1].split(")")[0];
			c=c.replace(/(\D+)/g,',').split(",");
			for (var i=0;i<c.length;i++) c[i]=parseInt(c[i]);
			return [c[0],c[1],c[2],c[3]];
		}
		else return [0,this.w,this.h,0];
	}
};
p.getBoxWidth = function(){
	return this.w+this._fixBw;
};
p.getBoxHeight = function(){
	return this.h+this._fixBh;
};
p.getVisible = function(){ return this.visible};
p.setVisible = function(b){
	this.visible = (b)? true:false;
	this.style.visibility = (b)? 'visible':'hidden';
};
p.getCursor = function() {return (this._cursor=='pointer')? 'hand':this._cursor};
p.setCursor = function(c) {
	if (!c) c = 'default';
	else c=(c+'').toLowerCase();
	if (!document.ua.ie && c=='hand') c='pointer';
	if (this._cursor!=c) {
		this._cursor = c;
		this.style.cursor = c;
	}
};
p.setTextSelectable = function(b){
	this._textSelectable = b;	
	if(document.ua.ns4) this.captureMouseEvents();
	else this.onselectstart = b ? document.api.Allow : document.api.Deny;
	if (!b) this.setCursor('default');
};

p.slideTo = function(endx,endy,inc,speed) {
	if (!this._slideActive) {
		var x = this.x||0;
		var y = this.y||0;
		if (endx==null) endx = x;
		if (endy==null) endy = y;
		var distx = endx-x;
		var disty = endy-y;
		if (x==endx && y==endy) return;
		var num = Math.sqrt(Math.pow(distx,2) + Math.pow(disty,2))/(inc||10)-1;
		var dx = distx/num;
		var dy = disty/num;
		this._slideActive = true;
		this._slide(dx,dy,endx,endy,num,this.x,this.y,1,(speed||20));
	}
};
p.slideStop = function() {
	this._slideActive = false;
	//this.invokeEvent('pathcancel');
};
p._slide = function(dx,dy,endx,endy,num,x,y,i,speed) {
	if (!this._slideActive) this.slideStop();
	else if (i++ < num) {
		//this.invokeEvent('pathrun');
		if (this._slideActive) {
			x += dx;
			y += dy;
			this.setLocation(Math.round(x),Math.round(y));
			setTimeout('document.getElementById("'+this.id+'")._slide('+dx+','+dy+','+endx+','+endy+','+num+','+x+','+y+','+i+','+speed+')',speed);
		}
		//else this.slideStop();
	}
	else {
		this._slideActive = false;
		this.invokeEvent('pathrun');
		this.setLocation(endx,endy);
		this.invokeEvent('pathfinish');
	}
};

