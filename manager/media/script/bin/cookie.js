/*
	Cookie functions
	Written By Raymond Irving - Based on DynAPI 3
	
	This Framework is Distribution is distributed under the terms of the GNU LGPL license.
*/

// set cookie 
document.setCookie = function (sName, sValue, nDays) {
	var expires = "";
	if ( nDays ) {
		var d = new Date();
		d.setTime( d.getTime() + nDays * 24 * 60 * 60 * 1000 );
		expires = "; expires=" + d.toGMTString();
	}

	document.cookie = sName + "=" + sValue + expires + "; path=/";
};
// get cookie
document.getCookie = function (sName) {
	var re = new RegExp( "(\;|^)[^;]*(" + sName + ")\=([^;]*)(;|$)" );
	var res = re.exec( document.cookie );
	return res != null ? res[3] : null;
};
