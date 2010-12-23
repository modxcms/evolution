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
 * File Name: iconlookup.php
 * 	(!)
 * 
 * File Authors:
 * 		Grant French (grant@mcpuk.net)
 */
function iconLookup($mime,$ext) {

	$mimeIcons=array(
			"image"=>"image.jpg",
			"audio"=>"sound.png",
			"video"=>"video.png",
			"text"=>"document2.png",
			"text/html"=>"html.png",
			"application"=>"binary.jpg",
			"application/pdf"=>"pdf.png",
			"application/msword"=>"document2.png",
			"application/postscript"=>"postscript.jpg",
			"application/rtf"=>"document2.png",
			"application/vnd.ms-excel"=>"spreadsheet.png",
			"application/vnd.ms-powerpoint"=>"document2.png",
			"application/x-tar"=>"tar.jpg",
			"application/zip"=>"tar.jpg",
			"application/x-shockwave-flash"=>"flash.png",
			"message"=>"email.jpg",
			"message/html"=>"html.png",
			"model"=>"kmplot.jpg",
			"multipart"=>"kmultiple.jpg"
			);
	
	$extIcons=array(
			"swf"=>"flash.png",
			"fla"=>"flash.png",
			"flv"=>"flash.png",
			"pdf"=>"pdf.png",
			"ps"=>"postscript.jpg",
			"eps"=>"postscript.jpg",
			"ai"=>"postscript.jpg",
			"ra"=>"real_doc.jpg",
			"rm"=>"real_doc.jpg",
			"ram"=>"real_doc.jpg",
			"wav"=>"sound.png",
			"mp3"=>"sound.png",
			"ogg"=>"sound.png",
			"eml"=>"email.jpg",
			"tar"=>"tar.jpg",
			"zip"=>"tar.jpg",
			"bz2"=>"tar.jpg",
			"tgz"=>"tar.jpg",
			"gz"=>"tar.jpg",
			"rar"=>"tar.jpg",
			"avi"=>"video.png",
			"mpg"=>"video.png",
			"mpeg"=>"video.png",
			"jpg"=>"image.jpg",
			"gif"=>"image.jpg",
			"png"=>"image.jpg",
			"jpeg"=>"image.jpg",
			"nfo"=>"info.jpg",
			"xls"=>"spreadsheet.png",
			"csv"=>"spreadsheet.png",
			"html"=>"html.png",
			"doc"=>"document2.png",
			"rtf"=>"document2.png",
			"txt"=>"document2.png",
			"xla"=>"document2.png",
			"xlc"=>"document2.png",
			"xlt"=>"document2.png",
			"xlw"=>"document2.png",
			"txt"=>"document2.png"
			);


	$icon_basedir = MODX_BASE_PATH.'manager/media/browser/mcpuk/connectors/php/images/';

	if ($mime!="text/plain") {	
		//Check specific cases
		$mimes=array_keys($mimeIcons);
		if (in_array($mime,$mimes)) {
			return $icon_basedir.$mimeIcons[$mime];
		} else {
			//Check for the generic mime type
			$mimePrefix="text";
			$firstSlash=strpos($mime,"/"); 
			if ($firstSlash!==false) $mimePrefix=substr($mime,0,$firstSlash);
			
			if (in_array($mimePrefix,$mimes)) {
				return $icon_basedir.$mimeIcons[$mimePrefix];
			} else {
				return $icon_basedir."empty.png";	
			}
		}
	} else {
		$extensions=array_keys($extIcons);
		if (in_array($ext,$extensions)) {
			return $icon_basedir.$extIcons[$ext];
		} else {
			return $icon_basedir."empty.png";
		}
	}

	return $icon_basedir."empty.png";
}

?>