/*
 *	PoweredBy 
 *	A little link to MODx
 *
 */
 
$version = $modx->getVersionData();
return "Powered by MODx<b> ".$version['version']."</b> ".( $version['code_name']!="" ? "<i>(".$version['code_name'].")</i>.":"");