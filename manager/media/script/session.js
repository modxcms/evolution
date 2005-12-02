/*
 * Small script to keep session alive in MODx
 */
function keepSessionAlive() {
	var img = new Image();
	img.src = "../../includes/session_keepalive.php?rnd=" + new Date().getTime();
	window.setTimeout('keepSessionAlive();', 1000 * 60);
}

keepSessionAlive();