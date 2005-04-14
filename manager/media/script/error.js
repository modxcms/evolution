document._error_messages = new Array();
var w;

function doError(msg,url,ln) {
	url = url.substr(8);
	slashpos = url.indexOf("/");
	url = url.substr(slashpos);
	
	var _error_obj = {msg : msg, url : url, ln : ln};
	
	document._error_messages[document._error_messages.length] = _error_obj;
	
	str = ""
	str += "<title>Scripting Error</title>"
	str += "<!-- script from http://www.webfx.eae.net -->"
	str += "<script>window.onload=new Function('showError()');"
	str += 'var nr=0;'
	str += 'function next() {'
	str += '   nr=Math.min(window.opener.document._error_messages.length-1,nr+1);'
	str += '   showError();'
	str += '}'
	str += 'function previous() {'
	str += '   nr=Math.max(0,nr-1);'
	str += '   showError();'
	str += '}'
	str += 'function showError() {'
	str += '   errorArray = window.opener.document._error_messages;'
	str += '   if (errorArray.length != 0 && nr >= 0 && nr < errorArray.length) {'
	str += '      url.innerText = errorArray[nr].url;'
	str += '      msg.innerText = errorArray[nr].msg;'
	str += '      ln.innerText = errorArray[nr].ln;'
	str += '   }'
	str += '}</script>'
	str += "<style>"
	str += "body {background: #fefefe; color: black; border: 10 solid #990033; font-family: tahoma, arial, helvitica; font-size: 12px; margin: 0;}"
	str += "p {font-family: tahoma, arial, helvitica; font-size: 12px; margin-left: 5px; margin-right: 5px;}"
	str += "h1	{font-family: arial black; font-style: italic; margin-bottom: -15; margin-left: 5; color:#990033}"
	str += "button {width: 100; height:26}"
	str += "a {color: #990033;}"
	str += "a:hover {color: #990033;}"
	str += "</style>"
	str += '<body scroll="no">'
	str += "<h1>Scripting error:</h1>"
	str += '<p>One or more errors ocurred during the execution of this file.<br>This might prevent the page from working correctly.</p>'
	str += '<div id="info" style="background: #cccccc; margin: 5; margin-bottom: 0; border: 1 solid #999999;">'
	str += '<table>'
	str += '<tr><td><p>URL:</p></td><td><p id="url"></p></td></tr>'
	str += '<tr><td><p>Message:</p></td><td><p id="msg"></p></td></tr>'
	str += '<tr><td><p>Line:</p></td><td><p id="ln"></p></td></tr>'
	str += '</table>'
	str += '</div>'
	str += '<table style="width: 100%;" cellspacing=0 cellpadding=5><tr><td>'
	str += '<button onclick="previous()"><img src="media/images/icons/previous.gif" align="absmiddle" width=16 height=16>&nbsp;Previous</button>'
	str += '</td><td align=right><button onclick="next()"><img src="media/images/icons/next.gif" align="absmiddle" width=16 height=16>&nbsp;Next</button>'
	str += '</td></tr>'
	str += '<tr><td></td><td align="RIGHT"><button onclick="window.close()"><img src="media/images/icons/delete.gif" align="absmiddle" width=16 height=16>&nbsp;Close</button>'
	str += '</td></tr></table>'
	str += '</body>'

	//get screen stats
	screenWidth = screen.width;
	screenHeight = screen.height;
	errorWidth = (screenWidth/2)-(500/2);
	errorHeight = (screenHeight/2)-(280/2);


	if (!w || w.closed) {
		w = window.open("","_webxf_error_win","width=500,height=280,left=" + errorWidth + ",top=" + errorHeight);
		var d = w.document;
		d.open();
		d.write(str);
		d.close();
		w.focus();
	}
	return true;
}

window.onerror = doError