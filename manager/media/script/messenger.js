

<script type="text/javascript" language="JavaScript" src="media/tvscripts/webelm.js"></script>
<script type="text/javascript" language="JavaScript">
	var evtMessenger;
	src =  
	document.addEventListener('oninit',function(){ 
		document.include('dynelement');  
		document.include('floater');  
	}); 

	document.addEventListener('onload',function(){ 
		evtMessenger = new Floater('evtMessenger','hello'); 
	});
</script>
<script>Floater.Render("fl",150,30);</script>