<?php
/**
 * session_keepalive.php
 *
 * This page is requested once in awhile to keep the session alive and kicking.
 */

	// Keep it alive
	session_start();

	header('Location: ../media/images/_session.gif?rnd='. $_REQUEST['rnd']);
	die();
?>