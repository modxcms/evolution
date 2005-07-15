
var p = DynElement.prototype;
p.setLocation=function(x,y) {
	var cx = (x!=null && x!=this.x);
	var cy = (y!=null && y!=this.y);
	if (cx) this.x = x||0;
	if (cy) this.y = y||0;
	if (this.style!=null) {
		if (cx) this.style.left = this.x+"px";
		if (cy) this.style.top = this.y+"px";
	}
	return (cx||cy);
};
p.setInnerHTML = function(html) {
	if (html!=this.html) {
		this.html = html;
		if (this.style) {
			this.innerHTML = html;		
			var sTmp=(this.w==null)?'<NOBR>'+this.html+'</NOBR>':this.html;
			while (this.hasChildNodes()) this.removeChild(this.firstChild);
			var r=this.ownerDocument.createRange();
			r.selectNodeContents(this);
			r.collapse(true);
			var df=r.createContextualFragment(sTmp);
			this.appendChild(df);
		}
	}
};
p.getContentWidth=function() {
	var p = this.parent;		
	var tw = this.style.width;
	this.style.width = "auto";		
	var w = this.offsetWidth;
	this.style.width = tw;
	return w;
};
p.getContentHeight=function() {
	var th = this.style.height;
	this.style.height = "auto";
	var h = this.offsetHeight;
	this.style.height = th;
	return h;
};