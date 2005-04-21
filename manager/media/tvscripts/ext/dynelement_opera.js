

var p = DynElement.prototype;
p.setLocation=function(x,y) {
	var cx = (x!=null && x!=this.x);
	var cy = (y!=null && y!=this.y);
	if (cx) this.x = x||0;
	if (cy) this.y = y||0;
	if (this.style!=null) {
		if (cx) this.style.pixelLeft = this.x;
		if (cy) this.style.pixelTop = this.y;
	}
	return (cx||cy);
};
p.setInnerHTML = function(html) {
	if (html!=this.html) {
		this.html = html;
		this.innerHTML = html;
	}
};
p.getContentWidth=function() {
	var tw=this.style.width;
	var w,to = this.style.overflow;
	this.style.width='auto';
	this.style.overflow='auto';
	w = parseInt(this.scrollWidth);
	this.style.width=tw;
	this.style.overflow=to;
	return w;
};
p.getContentHeight=function() {
	if (document.ua.mac) return this.offsetHeight;
	return parseInt(this.scrollHeight);
};