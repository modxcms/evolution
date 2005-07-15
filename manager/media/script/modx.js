/*
 * Commonly used MODx Javascript API
 * Written By Raymond Irving - Mar 2005
 * 
 */

var SCRIPT_INCLUDE_PATH = MODX_MEDIA_PATH+'/script/bin';

if(!document.initWebElm) {
	document.write('<script type="text/javascript" language="JavaScript" src="'+SCRIPT_INCLUDE_PATH+'/webelm.js"></script>');
}
