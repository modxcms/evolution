/*
	Ticker Control 
	Written By Raymond Feb, 2005
	Based on Floater script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)

	This Framework is Distribution is distributed under the terms of the GNU LGPL license.

*/

function Floater(id,html,x,y,pos,gs){
	var elm;

	if (pos && (!pos.indexOf("-right") && !pos.indexOf("-left"))) pos="";
	
	// get element	
	this.DynElement = DynElement;
	elm = this.DynElement(id);

	elm.moving = false;
	elm.gs = (!gs || isNaN(gs)) ? 6:parseInt(gs); //glide speed
	elm.pos = (!pos) ? "top-right":pos;
	elm.posx = elm.startX = (!x || isNaN(x))? 10: parseInt(x);
	elm.posy = elm.startY = (!y || isNaN(y))? 10: parseInt(y);
	elm.setInnerHTML(html||'');

	window.setTimeout("document.getElementById('"+id+"').startFloat()",10);	
	document.addEventListener("onscroll",function(){if (!elm.moving) elm.startFloat()});
	document.addEventListener("onresize",function(){if (!elm.moving) elm.startFloat()});
	return elm;
};
Floater.prototype = new DynElement();
Floater.prototype.startFloat=function()	{
	var pW,pY;
	var docElm = (document.documentElement && document.documentElement.scrollTop>0) ? document.documentElement:document.body;
	if (this.pos.indexOf("right")!=-1) {
		pW = document.ua.ns ? pageXOffset + innerWidth : docElm.scrollLeft + docElm.clientWidth;
		this.posx = ((pW - this.startX) - parseInt(this.offsetWidth ? this.offsetWidth:this.getWidth()));
	}
	if (this.pos.substr(0,3)=="top"){
		pY = document.ua.ns ? pageYOffset : docElm.scrollTop;
		this.posy += (pY + this.startY - this.posy)/this.gs;
	}
	else if (this.pos.substr(0,6)=="bottom") {
		pY = document.ua.ns ? pageYOffset + innerHeight : docElm.scrollTop + docElm.clientHeight;
		this.posy += (pY - this.startY - this.posy - parseInt(this.offsetHeight ? this.offsetHeight:this.getHeight()))/this.gs;
	}	
	if (this.posy==this.y) this.moving = false;
	else {
		this.moving = true;
		setTimeout("document.getElementById('"+this.id+"').startFloat()", 10);
	}
	this.setLocation(this.posx,this.posy);
};

Floater.Render = function(id,w,h,css,style){
	var ln
	if(!style) style = '';
	css = (css) ? 'class="'+css+'"':'';
	if (!document.ua.ns4) {
		style='style = "position:absolute;'+'width:'+w+';height:'+h+';'+style+';"';
		write('<div '+css+' '+style+' id="'+id+'"></div>');
	}
	else {
		w = (w ? ' width="'+w+'" ':'width="100%"');
		h = (h ? ' height="'+h+'" ':'');
		write('<layer '+css+' id="'+id+'" '+w+h+'></layer>');
	}
};


