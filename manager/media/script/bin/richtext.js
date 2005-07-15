
// Template Variable Rich Text Box script initiator

var _editor_lang = "en";
var _editor_url = "manager/media/editor/";

function tvCreateRTB(id,tbarStyle){
	var tvRTB,tvRTBConfig;

	tvRTBConfig = new HTMLArea.Config();
	
	if (tbarStyle=='simple') {
		tvRTBConfig.statusBar = false;
		tvRTBConfig.toolbar = [
			[ "formatblock", "space",
			  "bold", "italic", "underline", "strikethrough", "separator",
			  "subscript", "superscript", "separator",
			  "copy", "cut", "paste", "space", "undo", "redo",
			  "orderedlist", "unorderedlist", "separator",
			  "inserthorizontalrule", "createlink", "insertimage", "inserttable", "forecolor"]
		];
	}
	
	tvRTB = new HTMLArea(id,tvRTBConfig);
	tvRTB.registerPlugin(ContextMenu);	
	tvRTB.registerPlugin(EnterParagraphs);
	tvRTB.generate();
}
