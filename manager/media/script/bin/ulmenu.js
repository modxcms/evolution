/**
 *	ULMenu
 *	Created by Raymond Irving
 *
 */
 

/**
 *	@name:ULMenu
 *	@id: id/name of menu
 *	@width: width of menu
 *	@orient: orientation:  horz, vert
 *	@css: class name
 *	@style: style sheet
 *	@return - returns an instance of the menu			
 */
function ULMenu(id,width,orient,css,style) {
	this.id = id ? id:"ulMnu"+ULMenu.cnt++;
	this.orient = orient=="vert" ? "vert":"horz";
	this.blocks = [];
	if (this.orient=="vert") css = "ulMenuVert " + css;
	else css = "ulMenuHorz " + css ;
	this.createMenuBlock(this.id,width,css,style);
	this.items = this.blocks[this.id].items;
	this.addItem = ULMenu._addItem;
	
};
ULMenu.cnt = 0;
ULMenu.prototype._buildBlock = function(b,_lvl){
	var i,id,itm,litms = "";
	var t, cssStyle,cssClass;
	if(!b) return "";
	if(!_lvl) _lvl = 0;_lvl++;
	id= b.id ? " id='"+b.id+"'":"";
	width = b.width ? " width:"+b.width+"px;":"";
	cssStyle = (width||b.style) ? " style='"+b.style+";"+width+"'":"";
	cssClass = (b.css) ? " class='"+b.css+"'":"";
	for(i=0;i<b.items.length;i++){
		itm = b.items[i];
		litms += this._buildBlockItem(itm,_lvl);
	}	
	t = "<ul"+ id + cssClass + cssStyle +">"+litms+"</ul>";
	return t;
};
ULMenu.prototype._buildBlockItem = function(itm,_lvl){
	var arrow='';
	var id, ahref='', block='';
	var t, cssStyle,cssClass;
	if(!itm) return "";
	id = itm.id ? " id='"+itm.id+"'":"";
	cssStyle = (itm.style) ? " style='"+itm.style+";'":"";
	cssClass = (itm.css) ? " class='"+itm.css+"'":"";
	ahref = "<a href='"+(itm.href ? itm.href:"javascript:;")+"' onclick=\""+(itm.onclick ? itm.onclick+";return false;":"")+"\">"+itm.text+"</a>";
	if(itm.blockid) block = this._buildBlock(this.blocks[itm.blockid],_lvl);
	//if (_lvl>1 && block) arrow = "<span style='position:absolute; right:10px;top:0px'>&nbsp;&rArr;</span>";
	t = "<li"+ id + cssClass + cssStyle +">" + ahref + arrow + block +"</li>";
	return t;
};

/**
 *	@name:		createMenuBlock  
 *	@descr:		Create a new menu block
 *	@id: id/name of menu
 *	@width: width of menu
 *	@css: class name
 *	@style: style sheet
 *	@return - returns an instance of the menu item
 */
ULMenu.prototype.createMenuBlock = function(id,width,css,style) {
	return this.blocks[id] = {
		'id'		: id,
		'width'		: width,
		'css'		: css,
		'style'		: style,
		'items'		: [],
		'addItem'	: ULMenu._addItem
	}
};

ULMenu.prototype.render = function(){
	var mnu = this._buildBlock(this.blocks[this.id]);
	setTimeout("ULMenu._ConvertMenu('"+this.id+"')",10);
	return mnu;
};

/**
 *	@id: id/name of menu item
 *	@text: menu item text
 *	@orient: orientation:  horz, vert
 *	@params: Object  
 *		@css: class name
 *		@style: style sheet
 *		@onclick: onclick
 *		@href: href
 *		@title: title
 *		@blockid: menu block id to popup
 *	@return - returns an instance of the menu			
 */
ULMenu._addItem = function(id,text,xparams) {
	id = id ? id:"ulMnu"+ULMenu.cnt++;
	if (!xparams) xparams = {};
	this.items[this.items.length] = {
		'id'		: id,
		'text'		: text,
		'css'		: xparams.css,
		'style'		: xparams.style,
		'onclick'	: xparams.onclick,
		'href' 		: xparams.href,
		'title'		: xparams.title,
		"blockid"	: xparams.blockid
	}
};

ULMenu._AddClass = function(obj,cName){ 
	if (!obj) return; if (obj.className==null) obj.className=''; return obj.className+=(obj.className.length>0?' ':'')+cName; 
};
ULMenu._KillClass = function(obj,cName){ 
	if (!obj) return; return obj.className=obj.className.replace(RegExp("^"+cName+"\\b\\s*|\\s*\\b"+cName+"\\b",'g'),''); 
};
ULMenu._ConvertMenu = function(id){
	var menu=document.getElementById(id);
	if (!menu) return;
	var menuIsHorizontal= menu.className.indexOf('ulMenuHorz')!==-1 ? true:false;
	var lis = menu.getElementsByTagName('li');
	for (var i=0,len=lis.length;i<len;i++){
		var li=lis[i];
		var uls = li.getElementsByTagName('ul');
		li.onmouseover=ULMenu._ShowHead;
		li.onmouseout=ULMenu._HideHead;
		if (!uls || uls.length==0) continue;
		var ul=uls[0];
		li.sub=ul;
		li.isTop = li.parentNode==menu;
		li.isHorizontal = (menuIsHorizontal && li.isTop);
	}
};
ULMenu._ShowHead = function(){
	var li=this;
	var xy = ULMenu._FindXYWH(li);
	ULMenu._AddClass(li,'active');
	if(!li.sub) return;
	if (li.isTop){
		li.sub.style.left=(xy.x+(!li.isHorizontal?xy.w:0))+'px';
		li.sub.style.top=(xy.y+(li.isHorizontal?xy.h:0)-(li.isTop?0:1))+'px';
	} else {
		li.sub.style.left=li.offsetWidth+'px';
		li.sub.style.top=li.offsetTop+'px';
	}
	li.sub.style.visibility='visible';
};
ULMenu._HideHead = function(){
	var li=this;
	ULMenu._KillClass(li,'active');
	if(!li.sub) return;
	li.sub.style.visibility='hidden';
};
ULMenu._FindXY = function(obj){
	var x=0,y=0;
	while (obj){
		x+=obj.offsetLeft - (obj.scrollLeft || 0);
		y+=obj.offsetTop - (obj.scrollTop || 0);
		obj=null;
	}
	return {x:x,y:y};
};
ULMenu._FindXYWH = function(obj){
	if (!obj) return { x:0, y:0, w:0, h:0 };
	var objXY = ULMenu._FindXY(obj);
	return { x:objXY.x, y:objXY.y, w:obj.offsetWidth||0, h:obj.offsetHeight||0 };
};


