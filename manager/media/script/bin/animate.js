/*
	Animate functions
	Written By Raymond Irving - Based on DynAPI 3
	
	This Framework is Distribution is distributed under the terms of the GNU LGPL license.
*/


// splash screen;
function tvOpen(id,callFncName,w,h,_loop) {		
	var o = document.getElementById(id);
	if(!o) return;
	if(_loop && o._w<w) {
		o._stepW *=2.5;
		o._w = o._w+o._stepW;
		if(o._w>w) o._w = w;
		o.style.width= o._w+"px";
	}
	else if(_loop && o._h<h) {
		o._stepH *=2.5;
		o._h = o._h+o._stepH;;
		if(o._h>h) o._h = h;
		o.style.height= o._h+"px";
	}
	else if(!_loop) {
		if (!w) w = parseInt(o.offsetWidth);
		if (!h) h = parseInt(o.offsetHeight);
		o._stepW = o._stepH = 1;
		o.style.width= "0px";
		o.style.height= "2px";
		o.style.overflow="hidden";
		o._h = o._w = 0;
		o.style.visibility = "visible";
	}

	if(o._w<w || o._h<h) {
		setTimeout("tvOpen('"+id+"','"+callFncName+"',"+w+","+h+",true)",20);
	}
	else {
		o.style.overflow="";			
		if (callFncName) {
			var fnc = window[callFncName];
			if(fnc) fnc();
		}
	}
};
