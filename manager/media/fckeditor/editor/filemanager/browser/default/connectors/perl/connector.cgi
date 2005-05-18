#!/usr/bin/env perl 

#/*
# * FCKeditor - The text editor for internet
# * Copyright (C) 2003-2004 Frederico Caldeira Knabben
# * 
# * Licensed under the terms of the GNU Lesser General Public License:
# * 		http://www.opensource.org/licenses/lgpl-license.php
# * 
# * For further information visit:
# * 		http://www.fckeditor.net/
# * 
# * File Name: connector.cgi
# * 	This is the File Manager Connector for Perl.
# * 
# * Version:  2.0 RC2
# * Modified: 2005-01-7 13:20:00
# * 
# * File Authors:
# * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
# * 		Takashi Yamaguchi (jack@omakase.net)
# */

require 'util.pl';
require 'io.pl';
require 'basexml.pl';
require 'commands.pl';
require 'upload_fck.pl';

	&read_input();

	if($FORM{'ServerPath'} ne "") {
		$GLOBALS{'UserFilesPath'} = $FORM{'ServerPath'};
		if(!($GLOBALS{'UserFilesPath'} =~ /\/$/)) {
			$GLOBALS{'UserFilesPath'} .= '/' ;
		}
	} else {
		$GLOBALS{'UserFilesPath'} = '/UserFiles/';
	}

	# Map the "UserFiles" path to a local directory.
	$rootpath = &GetRootPath();
	$GLOBALS{'UserFilesDirectory'} = $rootpath . $GLOBALS{'UserFilesPath'};

	&DoResponse();

sub DoResponse
{

	if($FORM{'Command'} eq "" || $FORM{'Type'} eq "" || $FORM{'CurrentFolder'} eq "") {
		return ;
	}
	# Get the main request informaiton.
	$sCommand		= $FORM{'Command'};
	$sResourceType	= $FORM{'Type'};
	$sCurrentFolder	= $FORM{'CurrentFolder'};

	# Check the current folder syntax (must begin and start with a slash).
	if(!($sCurrentFolder =~ /\/$/)) {
		$sCurrentFolder .= '/';
	}
	if(!($sCurrentFolder =~ /^\//)) {
		$sCurrentFolder = '/' . $sCurrentFolder;
	}

	# File Upload doesn't have to Return XML, so it must be intercepted before anything.
	if($sCommand eq 'FileUpload') {
		FileUpload($sResourceType,$sCurrentFolder);
		return ;
	}

	print << "_HTML_HEAD_";
Content-Type:text/xml; charset=utf-8
Pragma: no-cache
Cache-Control: no-cache
Expires: Thu, 01 Dec 1994 16:00:00 GMT

_HTML_HEAD_

	&CreateXmlHeader($sCommand,$sResourceType,$sCurrentFolder);
	# Execute the required command.
	if($sCommand eq 'GetFolders') {
		&GetFolders($sResourceType,$sCurrentFolder);
	} elsif($sCommand eq 'GetFoldersAndFiles') {
		&GetFoldersAndFiles($sResourceType,$sCurrentFolder);
	} elsif($sCommand eq 'CreateFolder') {
		&CreateFolder($sResourceType,$sCurrentFolder);
	}
	&CreateXmlFooter();
	exit ;
}

