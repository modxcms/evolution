
function ResizeViewPort(n,mode){ 
	if (!n || !mode || document.layers) return null;
	var f=(document.getElementById)? document.getElementById(n): document.all[n]; 
	var doc = (f && f.contentDocument)? f.contentDocument:f.Document; 
	w = (doc.body.scrollWidth)? doc.body.scrollWidth:doc.body.offsetWidth;
	h = (doc.body.scrollHeight)? doc.body.scrollHeight:doc.body.offsetHeight;
	if(mode==1||mode==3) f.style.width = w;
	if(mode==2||mode==3) f.style.height = h;
}
