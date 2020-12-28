<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
?>
<p></p>
<p>EVO 1.4.7 includes the update of several core snippets and internationalization languages, as well as various fixes and improvements for stability and backward compatibility.</p>
<p></p>
<p>Fixes and Updates in 1.4.7:</p>
<ul>
	<li>[U] phpThumb 1.3.3 (AgelxNash)</li>
  	<li>[U] ElementsInTree 1.5.10 (AgelxNash)</li>
  	<li>[U] DocLister 2.4.1 (AgelxNash)</li>
  	<li>[U] Formlister 1.8.1 (AgelxNash)</li>
  	<li>[U] DocInfo  0.4.1 (AgelxNash)</li>
  	<li>[U] FileSource 0.1 (Serg)
	<li>[U] Updated extras url from extras.evolution-cms.com to extras.evo.im (dmi3yy)</li>
	<li>[U] Updated Help Version Noticies 1.4.2 - 1.4.7 (Nicola)</li>
	<li>[U] Updated Languages files: English, Italian, German, Spanish and Polish</li>
	<li>[I] Colorpicker added to tinymce full theme (mnoskov)</li>
	<li>[I] New Enable Mootools Setting Option (Load Mootools.js in manager for backward compatibility) (Nicola)</li>
	<li>[I] #887 Elements in ElementsInTree plugin sorted by name (Nicola)</li>
	<li>[F] Fix #874 Remove fullstop at end of new password (Serg)</li>
	<li>[F] Fix #888 The FileSource 0.1 is dependent on the mootools (Serg)</li>
	<li>[F] Fix #892 Duplicated element name issue (Serg)</li>
	<li>[F] Fix #882 broken extras module link in RSS check (Nicola)</li>
	<li>[F] Fix for php7 in ddmultiplefields.php</li>
	<li>[R] Format code save_user_processor (Serg)</li>
</ul>
