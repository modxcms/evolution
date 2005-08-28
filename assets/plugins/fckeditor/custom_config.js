/*
 * Custom Configuration file 
 * 
 */

/* *
 * Language settings
 *
 */
FCKConfig.AutoDetectLanguage	= parent.FCKAutoLanguage;
FCKConfig.DefaultLanguage		= 'en' ;

/* *
 * setup toolbar sets 
 *
 */
// basic			
FCKConfig.ToolbarSets["basic"] = [
	['Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','Image']
];
// standard
FCKConfig.ToolbarSets["standard"] = [
	['Source','-','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord'],
	['Undo','Redo','-','Find','Replace','-','RemoveFormat'],
	['Bold','Italic','Underline'],
	['OrderedList','UnorderedList','-','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Anchor'],
	['Image','Flash','Table','Rule','SpecialChar'],
	['Style'],['FontFormat'],['FontName'],['FontSize'],
	['TextColor','BGColor']
];
// advanced
FCKConfig.ToolbarSets["advanced"] = [
	['Source','DocProps','-','NewPage','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Unlink','Anchor'],
	['Image','Flash','Table','Rule','Smiley','SpecialChar','UniversalKey'],
	['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
	'/',
	['Style'],['FontFormat'],['FontName'],['FontSize'],
	['TextColor','BGColor']
];
// custom
FCKConfig.ToolbarSets["custom"] = parent.FCKCustomToolbarSet;

