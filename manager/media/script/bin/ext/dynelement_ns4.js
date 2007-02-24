

var p = DynElement.prototype;
p.setLocation = function(x,y) {
	var cx = (x!=null && x!=this.x);
	var cy = (y!=null && y!=this.y);
	if (cx) this.x = x||0;
	if (cy) this.y = y||0;
	if (this.style!=null) {
		if (cx && cy) this.moveTo(this.x, this.y);
	}
	return (cx||cy);
};
p.setVisible = function(b) {
	if (b!=this.visible) {
		this.visible = (b)? true:false;
		if (this.style) this.style.visibility = this.visible ? "inherit" : "hide";
	}
};
p.setSize = function(w,h) {
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
		if (this._hasAnchor) this.updateAnchor(); // update this anchor
		if (this._hasChildAnchors) this._updateAnchors(); // update child anchors
		if (this.style) {
			if (cw) this.style.clip.width = (this.w || 0)+this._fixBw;
			if (ch) this.style.clip.height = (this.h || 0)+this._fixBh;
		}
	}
	return (cw||ch);
};
p.setInnerHTML=function(html) {
	var ch = (html!=null && html!=this.html);
	if (ch) {
		this.html = html;
		if (this.style) {
			var i, doc = this.document;
			doc.open();	doc.write(html); doc.close();
		}
	}
};
p.setTextSelectable=function() {
	this.addEventListener('onmousemove',function(e) {
		e.preventDefault();
	});
};
p.getCursor = function() {return this._cursor};
p.setCursor = function(c) {
	if (!c) c = 'default';
	if (this._cursor!=c) this._cursor = c;	
};
p.setClip=function(clip) {
	var cc=this.getClip();
	for (var i=0;i<clip.length;i++) if (clip[i]==null) clip[i]=cc[i];
	this.clip=clip;
	if (this.style==null) return;
	var c=this.style.clip;
	c.top=clip[0], c.right=clip[1], c.bottom=clip[2], c.left=clip[3];
};
p.getClip=function() {
	if (this.style==null || !this.style.clip) return [0,0,0,0];
	var c = this.style.clip;
	if (c) {
		return [c.top,c.right,c.bottom,c.left];
	}
};
p.getContentWidth=function() {
	if (this==null) return 0;
	else {
		return this.document.width;
	};
};
p.getContentHeight=function() {
	if (this==null) return 0;
	else {
		return this.document.height;
	}
};
