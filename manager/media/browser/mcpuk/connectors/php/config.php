<?php 
/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 *
 * "Support Open Source software. What about a donation today?"
 * 
 * File Name: config.php
 * 	Configuration file
 * 
 * File Authors:
 * 		Grant French (grant@mcpuk.net)
 */

// ** START FOR MODx

require_once('../../../../../includes/protect.inc.php');

// load configuration file
// initialize the variables prior to grabbing the config file
$database_type = '';
$database_server = '';
$database_user = '';
$database_password = '';
$dbase = '';
$table_prefix = '';
$base_url = '';
$base_path = '';
include("../../../../../includes/config.inc.php");

/** 
 * Security check user MUST be logged into manager 
 * before being able to run this script
 */
startCMSSession(); 
if(!isset($_SESSION['mgrValidated'])) {
	if(!isset($_SESSION['webValidated'])){
		die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
	}
}

// connect to the database
if(@!$modxDBConn = mysql_connect($database_server, $database_user, $database_password)) {
	die("Failed to create the database connection!");
} else {
	mysql_select_db($dbase);
    @mysql_query("{$database_connection_method} {$database_connection_charset}");
}

// Override system settings with user settings
define('IN_MANAGER_MODE', 'true'); // set this so that user_settings will trust us.
include("../../../../../includes/settings.inc.php");
include("../../../../../includes/user_settings.inc.php");

if($settings['use_browser'] != 1){
	die("<b>PERMISSION DENIED</b><br /><br />You do not have permission to access this file!");
}

if(!isset($_SESSION['mgrValidated'])){
	if($_SESSION['webValidated'] && $settings['rb_webuser'] != 1 ){
		die("<b>PERMISSION DENIED</b><br /><br />You do not have permission to access this file!");
	}
}

// make arrays from the file upload settings
$upload_files = explode(',',$upload_files);
$upload_images = explode(',',$upload_images);
$upload_media = explode(',',$upload_media);
$upload_flash = explode(',',$upload_flash);

// avoid problems when passing strings into CHMOD
$fckphp_config['modx']['file_permissions'] = octdec($new_file_permissions);
$fckphp_config['modx']['folder_permissions'] = octdec($new_folder_permissions);
// ** END FOR MODx


/*------------------------------------------------------------------------------*/
/* HTTP over SSL Detection (shouldnt require changing)				*/
/*------------------------------------------------------------------------------*/
$fckphp_config['prot']="http";
$fckphp_config['prot'].=((isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=='on')?"s":"");
$fckphp_config['prot'].="://";
/*==============================================================================*/

$basedir = $rb_base_dir;
$baseurl = $rb_base_url;
$rb_base_url_parse = parse_url($rb_base_url);
if(empty($rb_base_url_parse['host'])){
    $base_url_parse = parse_url($base_url);
    $rb_base_url = str_replace($base_url_parse['path'], "", $rb_base_url);
    $rb_base_url = (substr($rb_base_url, 0, 1) == "/" ? substr($rb_base_url, 1) : $rb_base_url);  
    if($_GET['editor'] == 'fckeditor2' && $strip_image_paths == 1){
    	$baseurl = $base_url.$rb_base_url;     
    } else if(($_GET['editor'] == 'tinymce3' || $_GET['editor'] == 'tinymce') && $strip_image_paths != 1){
    	$baseurl = $site_url.$rb_base_url;        
    }
}
/*------------------------------------------------------------------------------*/
/* The physical path to the document root, Set manually if not using apache	*/
/*------------------------------------------------------------------------------*/
$fckphp_config['basedir'] = (substr($basedir,-1)=="/") ? substr($basedir,0,-1):$basedir;
//$fckphp_config['basedir']=substr($base_path, 0, strlen($base_path)-1);
//$fckphp_config['basedir'] = $_SERVER['DOCUMENT_ROOT'] ;
/*==============================================================================*/

/*------------------------------------------------------------------------------*/
/* Prefix added to image path before sending back to editor			*/
/*------------------------------------------------------------------------------*/
//$fckphp_config['urlprefix']=$fckphp_config['prot'].$_SERVER['SERVER_NAME'];
if ($strip_image_paths == 1) {
	$fckphp_config['urlprefix'] = (substr($baseurl,-1)=="/") ? str_replace($site_url,'',substr($baseurl,0,-1)):$baseurl;
} else {
	$fckphp_config['urlprefix'] = (substr($baseurl,-1)=="/") ? substr($baseurl,0,-1):$baseurl;
}
//$fckphp_config['urlprefix']=substr($site_url, 0, strlen($site_url)-1);

/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Path to user files relative to the document root (no trailing slash)		*/
/*------------------------------------------------------------------------------*/
$fckphp_config['UserFilesPath'] = "";//(subst($rb_base_url,-1)=="/") ? subst($rb_base_url,0,-1):$rb_base_url;
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Progressbar handler (script that monitors upload progress) (''=none)
/*------------------------------------------------------------------------------*/
$fckphp_config['uploadProgressHandler']=''; //No upload progress handler
//$fckphp_config['uploadProgressHandler']=$fckphp_config['prot'].$_SERVER['SERVER_NAME']."/cgi-bin/progress.cgi"; //Perl upload progress handler
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Authentication (auth) :-								*/
/*  - Req		:: Boolean, whether authentication is required		*/
/*  - HandlerClass	:: Name of class to handle authentication in connector	*/
/*------------------------------------------------------------------------------*/
$fckphp_config['auth']['Req']=false;
$fckphp_config['auth']['HandlerClass']='Default';
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Settings for authentication handler :-					*/
/*  - SharedKey :: Shared encryption key (as set in test.php in example)	*/
/*------------------------------------------------------------------------------*/
$fckphp_config['auth']['Handler']['SharedKey']="->Shared_K3y-F0R*5enD1NG^auth3nt1caT10n'Info/To\FILE,Brow5er--!";
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Per resource area settings:-							*/
/* - AllowedExtensions	:: Array, allowed file extensions (in lowercase)	*/
/* - AllowedMIME	:: Array, allowed mime types (in lowercase)		*/
/* - MaxSize		:: Number, Maximum size of file uploads in KBytes	*/
/* - DiskQuota		:: Number, Maximum size allowed for the resource area	*/
/* - HideFolders	:: Array, RegExp, matching folder names will be hidden	*/
/* - HideFiles		:: Array, RegExp, matching file names will be hidden	*/
/* - AllowImageEditing	:: Boolean, whether images in this area may be edited	*/
/*------------------------------------------------------------------------------*/
//First area options are commented

//File Area
$fckphp_config['ResourceAreas']['files'] =array(
	
	//Files(identified by extension) that may be uploaded to this area
	'AllowedExtensions'	=>	$upload_files,
	
	//Not implemented yet
	'AllowedMIME'		=>	array(),
	
	//Set the maximum single upload to this area to 2MB (2048Kb)
	'MaxSize'		=>	$upload_maxsize,
	
	//Set disk quota for this resource area to 20MB
	'DiskQuota'		=>	-1,
	
	//By Default hide all folders starting with a . (Unix standard)
	'HideFolders'		=>	array("^\."), 
	
	//By Default hide all files starting with a . (Unix standard)
	'HideFiles'		=>	array("^\."), 
	
	//Do not allow images to be edited in this resource area
	'AllowImageEditing'	=>	false
	);

//Image area
$fckphp_config['ResourceAreas']['images'] =array(
	'AllowedExtensions'	=> $upload_images,
	'AllowedMIME'		=>	array(),
	'MaxSize'		=>	$upload_maxsize,
	'DiskQuota'		=>	-1,
	'HideFolders'		=>	array("^\."),
	'HideFiles'		=>	array("^\."),
	'AllowImageEditing'	=>	true //Not yet complete, but you can take a look and see
	);

//Flash area
$fckphp_config['ResourceAreas']['flash'] =array(
	'AllowedExtensions'	=>	$upload_flash,
	'AllowedMIME'		=>	array(),
	'MaxSize'		=>	$upload_maxsize,
	'DiskQuota'		=>	-1,
	'HideFolders'		=>	array("^\."),
	'HideFiles'		=>	array("^\."),
	'AllowImageEditing'	=>	false
	);
	
//Media area
$fckphp_config['ResourceAreas']['media'] =array(
	'AllowedExtensions'	=>	$upload_media,
	'AllowedMIME'		=>	array(),
	'MaxSize'		=>	$upload_maxsize,
	'DiskQuota'		=>	-1,
	'HideFolders'		=>	array("^\."),
	'HideFiles'		=>	array("^\."),
	'AllowImageEditing'	=>	false
	);
	
/*==============================================================================*/		


/*------------------------------------------------------------------------------*/
/* Global Disk Quota - Max size of all resource areas				*/
/*------------------------------------------------------------------------------*/
$fckphp_config['DiskQuota']['Global']= -1; //no diskquota  //($rb_diskquota) ? $rb_diskquota:50; //In MBytes (default: 50mb)
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Directory and File Naming :-							*/
/*  -MaxDirNameLength	:: Maximum allowed length of a directory name		*/
/*  -DirNameAllowedChars :: Array of characters allowed in a directory name	*/
/*  -FileNameAllowedChars :: Array of characters allowed in a file name		*/
/*------------------------------------------------------------------------------*/

$fckphp_config['MaxDirNameLength']=25;

$fckphp_config['DirNameAllowedChars']=array();

	//Allow numbers
	for($i=48;$i<58;$i++) array_push($fckphp_config['DirNameAllowedChars'],chr($i));
	
	//Allow lowercase letters
	for($i=97;$i<123;$i++) array_push($fckphp_config['DirNameAllowedChars'],chr($i));
	
	//Allow uppercase letters
	for($i=65;$i<91;$i++) array_push($fckphp_config['DirNameAllowedChars'],chr($i));
	
	//Allow space,dash,underscore,dot
	array_push($fckphp_config['DirNameAllowedChars']," ","-","_",".");
	
$fckphp_config['FileNameAllowedChars']=$fckphp_config['DirNameAllowedChars'];
array_push($fckphp_config['FileNameAllowedChars'],')','(','[',']','~');
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Debugging :-									*/
/*  - Debug	:: Boolean, if set to true a copy of the connector output is 	*/
/*			sent to a file as well as to the client.		*/
/*  - DebugOutput :: File to send debug output to (absolute path)		*/
/*------------------------------------------------------------------------------*/

$fckphp_config['Debug']=false;
$fckphp_config['DebugOutput']="fck_conn_dbg";

#Log PHP errors
$fckphp_config['Debug_Errors']=false;
$fckphp_config['Debug_Trace']=false;

#Log Connector output
$fckphp_config['Debug_Output']=false;

#With each logged event display contents of
/* $_GET */ $fckphp_config['Debug_GET']=false;
/* $_POST */ $fckphp_config['Debug_POST']=false;
/* $_SERVER */ $fckphp_config['Debug_SERVER']=false;
/* $_SESSIONS */ $fckphp_config['Debug_SESSIONS']=false;

/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Internals :-									*/
/*	ResourceTypes :: Array of valid resource areas				*/
/*	Commands :: Array of valid commands accepted by the connector		*/
/*------------------------------------------------------------------------------*/
$fckphp_config['ResourceTypes'] = array('files','images','flash','media');
$fckphp_config['Commands'] = array(
				"CreateFolder",
				"GetFolders",
				"GetFoldersAndFiles",
				"FileUpload",
				"Thumbnail",
				"DeleteFile",
				"DeleteFolder",
				"GetUploadProgress",
				"RenameFile",
				"RenameFolder"
				);
/*==============================================================================*/

?>