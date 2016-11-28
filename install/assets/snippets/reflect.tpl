//<?php
/**
 * Reflect
 * 
 * Generates date-based archives using Ditto
 *
 * @category 	snippet
 * @version 	2.2
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties
 * @internal	@modx_category Content
 * @internal    @installset base, sample
 * @documentation Cheatsheet https://de.scribd.com/doc/55919355/MODx-Ditto-and-Reflect-Cheatsheet-v1-2
 * @documentation Inside snippet-code
 * @reportissues https://github.com/modxcms/evolution
 * @author      Mark Kaplan
 * @author      Ryan Thrash http://thrash.me
 * @author      netProphET, Dmi3yy, bossloper, yamamoto
 * @lastupdate  2016-11-21
 */

/*
 *  Note: 
 *  If Reflect is not retrieving its own documents, make sure that the
 *  Ditto call feeding it has all of the fields in it that you plan on
 *  calling in your Reflect template. Furthermore, Reflect will ONLY
 *  show what is currently in the Ditto result set.
 *  Thus, if pagination is on it will ONLY show that page's items.
*/

return require MODX_BASE_PATH.'assets/snippets/reflect/snippet.reflect.php';
