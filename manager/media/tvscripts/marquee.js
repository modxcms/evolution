/*
	Marquee Control 
	Wriiten By Raymond Jan, 2005
	Based on Cross browser Marquee II- © Dynamic Drive (www.dynamicdrive.com)

	This Framework is Distribution is distributed under the terms of the GNU LGPL license.
*/

function Marquee(id,html,mspeed,mpause,transfx) {
	var elm;
	
	// get element
	this.DynElement = DynElement;
	elm = this.DynElement(id);
	
	elm.mousepause = (mpause=='1') ? 1:0;	// Pause marquee onMousever (0=no. 1=yes)?
	elm.marqueespeed = parseInt(mspeed);	// Specify the marquee's marquee speed (larger is faster 1-10)
	if(elm.marqueespeed>20) elm.marqueespeed = 20;
	else if(elm.marqueespeed<0) elm.marqueespeed = 1;

	elm.marqueespeed=(!document.ua.ns4)? elm.marqueespeed : Math.max(1, elm.marqueespeed-1); //slow speed down by 1 for NS
	elm.copyspeed=elm.marqueespeed;
	elm.pausespeed=(elm.mousepause==0)? elm.copyspeed: 0;
	elm.actualheight='';
	elm._transfx = parseInt(transfx); // 1 - vert, 2- horz

	html = (elm._transfx==2) ? "<nobr>"+html+"</nobr>":html;

	elm.content = new DynElement(id+"_con");
	elm.content.setInnerHTML(html);	
	if (elm._transfx==2) elm.actualwidth = elm.content.getContentWidth();
	else elm.actualheight = elm.content.getContentHeight();
	elm.content.setLocation(elm.getWidth()+8,elm.getHeight()+8);
	
	return elm;
}
Marquee.prototype = new DynElement;
Marquee.prototype.scrollMarquee = function(){
	var x,y;
	if (this._transfx==2) {
		x = this.content.getX();
		if (x>((this.actualwidth+8)*(-1))) x = x - (this.copyspeed*0.2);
		else x = this.getWidth()+8;
		this.content.setLocation(x,0);
	}
	else {
		y = this.content.getY();
		if (y>((this.actualheight+8)*(-1))) y = y - (this.copyspeed*0.2);
		else y = this.getHeight()+8;
		this.content.setLocation(0,y);
	}
	this.lefttime = setTimeout("document.getElementById('"+this.id+"').scrollMarquee()",30);
};
Marquee.prototype.start = function(){
	this.lefttime = setTimeout("document.getElementById('"+this.id+"').scrollMarquee()",30);
};
Marquee.prototype.stop = function(){
	this.copyspeed = this.pausespeed;
};
Marquee.prototype.setInnerHTML = function(s){
	this.content.setInnerHTML(s);
};

Marquee.Render = function(id,w,h,css,style){
	if(!w) w='100%';
	if(!h) h='100px';
	if(!style) style = '';
	css = (css) ? 'class="'+css+'"':'';
	style='style="position:relative;width:'+w+';height:'+h+';overflow:hidden;'+style+'"';
	if (!document.ua.ns4){
		write('<div '+css+' '+style+' id="'+id+'" onMouseover="var o = document.getElementById(\''+id+'\'); o.copyspeed=o.pausespeed; " onMouseout="var o = document.getElementById(\''+id+'\'); o.copyspeed=o.marqueespeed">');
		write('<div id="'+id+'_con" style="position:absolute;left:0px;top:0px;width:100%;">');
		write('</div></div>');
	}
	else {
		write('<ilayer class="'+css+'" name="'+id+'" width='+w+' height='+h+'>');
		write('<layer name="'+id+'_con" width='+w+' height='+h+' left="0" top="0" onMouseover="var o = window.document.getElementById(\''+id+'\'); o.copyspeed=o.pausespeed" onMouseout="var o = window.document.getElementById(\''+id+'\'); o.copyspeed=o.marqueespeed"></layer>');
		write('</ilayer>');
	}
};
