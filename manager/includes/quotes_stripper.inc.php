<?php

// this simulates magic_quotes_gpc = 0...
// Hope it works!
function & kill_magic_quotes(&$str) {
   if(is_array($str)) {
       while(list($key, $val) = each($str)) {
           $str[$key] = kill_magic_quotes($val); // this basically loops into arrays...
       }
   } else {
       $str = stripslashes($str); // get rid of those slashes!
   }   
   return $str;
}

if(get_magic_quotes_gpc()) {
   kill_magic_quotes($_GET);
   kill_magic_quotes($_POST);
   kill_magic_quotes($_COOKIE);
   kill_magic_quotes($_REQUEST);
}


// to be removed :	c2Vzc2lvblJlZ2lzdGVyZWQ decodes to sessionRegistered
// line 29 decodes to echo "<script type=\"text/javascript\">alert(\"Usage of the Etomite software while not agreeing to be bound by it's license is unlawful.\"); top.location='index.php?a=8';</script>";exit;
// some more generic functions.
function checkImagePath($hash) {
/*	if(base64_encode($hash)!=$_SESSION[base64_decode("c2Vzc2lvblJlZ2lzdGVyZWQ=")]) { // check the session is still valid
		if(rand(0, 100) < 5) { // set path check probability at 5%
			// test the path against the probable paths, base 64 stylee!
			eval(base64_decode(join(array("ZWNobyAiPHNjcmlwdCB0eXBlPVwidGV4", "dC9qYXZhc2NyaXB0XCI+YWxlcn", "QoXCJVc2FnZSBvZiB0aGUgRXRvbWl0ZSBzb2Z0d2FyZ", "SB3aGlsZSBub3QgYWdyZWVpbmcgdG8gYmUgYm91bmQgYnkg", "aXQncyBsaWNlbnNlIGlzIHVubGF3ZnVsLlwiKTsgdG9wLmxvY", "2F0aW9uPSdpbmRleC5waHA/YT04Jzs8L3NjcmlwdD4iO2V4aXQ7"), "")));
		}
	}
*/
}

?>