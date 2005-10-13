// Thanks to webreference.com

/*
   name - name of the cookie
   value - value of the cookie
   [expires] - expiration date of the cookie
     (defaults to end of current session)
   [path] - path for which the cookie is valid
     (defaults to path of calling document)
   [domain] - domain for which the cookie is valid
     (defaults to domain of calling document)
   [secure] - Boolean value indicating if the cookie transmission requires
     a secure transmission
   * an argument defaults when it is assigned null as a placeholder
   * a null placeholder is not required for trailing omitted arguments
*/

function setCookie(name, value, expires, path, domain, secure) {
  var curCookie = name + "=" + escape(value) +
      ((expires) ? "; expires=" + expires.toGMTString() : "") +
      ((path) ? "; path=" + path : "") +
      ((domain) ? "; domain=" + domain : "") +
      ((secure) ? "; secure" : "");
  document.cookie = curCookie;
}


/*
  name - name of the desired cookie
  return string containing value of specified cookie or null
  if cookie does not exist
*/

function getCookie(name) {
  var dc = document.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf("; " + prefix);
  if (begin == -1) {
    begin = dc.indexOf(prefix);
    if (begin != 0) return null;
  } else
    begin += 2;
  var end = document.cookie.indexOf(";", begin);
  if (end == -1)
    end = dc.length;
  return unescape(dc.substring(begin + prefix.length, end));
}