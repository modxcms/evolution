/*
	Ticker Control 
	Written By Raymond Jan, 2005
	Based on Memory Ticker script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)

	This Framework is Distribution is distributed under the terms of the GNU LGPL license.

*/

function Ticker(id,tick,transfx){
	var elm;

	// get element	
	this.DynElement = DynElement;
	elm = this.DynElement(id);

	elm.cmsg=0;			//current message
	elm.messages = [];
	elm.tickdelay=(tick)? parseInt(tick):3000; //delay between ticking of messages (in miliseconds):
	elm._transfx = parseInt(transfx); // 1 - norm, 2 - fader
	elm._dir = 0;
	elm._opac = -5;

	window.setTimeout("document.getElementById('"+id+"').changeMessage()",10);
	
	return elm;

};
Ticker.prototype = new DynElement();
Ticker.prototype._faderTrans = function(){
	var change = 0;	
	if(this._opac>=90) {
		this._opac=90;
		this._dir=1;
		change = 1;
	}
	else if(this._opac<=-5) {
		this._opac=0;
		this._dir=0;
		this.setInnerHTML(this.messages[this.cmsg]);
		this.cmsg=(this.cmsg==this.messages.length-1)? this.cmsg=0 : this.cmsg+1;
	}
	if(this._dir) this._opac -= 5; else this._opac+= 5;
	if(document.ua.ie) this.style.filter="alpha(opacity="+ this._opac +")";
	else this.style.MozOpacity=this._opac/100;
	if(!change) setTimeout("document.getElementById('"+this.id+"')._faderTrans()",80);
	else setTimeout("document.getElementById('"+this.id+"').changeMessage()",this.tickdelay);
};
Ticker.prototype._normalTrans = function(){
	if (this.filters && this.filters.length>0)
		this.filters[0].Apply();
	this.setInnerHTML(this.messages[this.cmsg]);
	if (this.filters && this.filters.length>0)
		this.filters[0].Play();
	var filterduration=(this.filters && this.filters.length>0)? this.filters[0].duration * 1000 : 0;
	this.cmsg=(this.cmsg==this.messages.length-1)? this.cmsg=0 : this.cmsg+1;
	setTimeout("document.getElementById('"+this.id+"').changeMessage()",this.tickdelay + filterduration);
};
Ticker.prototype.addMessage = function(s){
	this.messages[this.messages.length]=s;
};
Ticker.prototype.changeMessage = function(){
	if (this._transfx == 2) this._faderTrans();
	else this._normalTrans();
};
Ticker.prototype.setDelay = function(n){
	this.tickdelay = n;
};

Ticker.Render = function(id,w,h,css,style){
	var ln
	if(!style) style = '';
	css = (css) ? 'class="'+css+'"':'';
	if (!document.ua.ns4) {
		style='style = "position:relative;'+'width:'+w+';height:'+h+';'+style+';"';
		write('<div '+css+' '+style+' id="'+id+'"></div>');
	}
	else {
		w = (w ? ' width="'+w+'" ':'width="100%"');
		h = (h ? ' height="'+h+'" ':'');
		write('<ilayer '+css+' id="'+id+'_ns4" '+w+h+'><layer id="'+id+'" '+w+h+'></layer></ilayer>');
	}
};


