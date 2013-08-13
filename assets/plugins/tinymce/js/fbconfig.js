document.write('<script language="javascript" type="text/javascript" src="tiny_mce/tiny_mce_popup.js"></script>');

var FileBrowserDialogue = {
    init : function () {
        // Here goes your code for setting your custom things onLoad.
    },
    selectURL : function (url) {
        var win = tinyMCEPopup.getWindowArg("window");

        // insert information now
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = url;

		if (typeof(win.ImageDialog) != 'undefined') {
			// for image browsers: update image dimensions
			if (win.ImageDialog.getImageData) {
				win.ImageDialog.getImageData();
			}
			// show preview image
			if (win.ImageDialog.showPreviewImage) {
			win.ImageDialog.showPreviewImage(url);
            }
		}
		
        // close popup window
        tinyMCEPopup.close();			    
	}
}

tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);

function SetUrl(fileUrl){
	top.FileBrowserDialogue.selectURL(fileUrl);
}