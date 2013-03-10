<script language="javascript" type="text/javascript">
function mceOpenServerBrowser(field_name, url, type, win) {
    if (type == "media") {type = win.document.getElementById("media_type").value;}
	var cmsURL = "[+cmsurl+]";
	switch (type) {
		case "image":
			type = "images";
			break;
		case "media":
		case "qt":
		case "wmp":
		case "rmp":
		case "video":
		case "quicktime":
		case "windowsmedia":
		case "realmedia":
		case "iframe":
			type = "media";
			break;
		case "shockwave":
		case "flash":
			type = "flash";
			break;
		case "file":
			type = "files";
			break;
		default:
			return false;
	}
	if (cmsURL.indexOf("?") < 0) {
	    //add the type as the only query parameter
	    cmsURL = cmsURL + "?type=" + type;
	}
	else {
	    //add the type as an additional query parameter
	    // (PHP session ID is now included if there is one at all)
	    cmsURL = cmsURL + "&type=" + type;
	}

	var wm = tinyMCE.activeEditor.windowManager;
	    wm.open({
	    file : cmsURL,
	    width : screen.width * 0.7,  // Your dimensions may differ - toy around with them!
	    height : screen.height * 0.7,
	    resizable : "yes",
	    inline : 0,  // This parameter only has an effect if you use the inlinepopups plugin!
	    close_previous : "no"
	}, {
	    window : win,
	    input : field_name
	});
	if (window.focus) {wm.focus();}
	return false;
}
</script>
