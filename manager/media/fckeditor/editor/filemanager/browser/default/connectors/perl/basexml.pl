#####
#  FCKeditor - The text editor for internet
#  Copyright (C) 2003-2005 Frederico Caldeira Knabben
#  
#  Licensed under the terms of the GNU Lesser General Public License:
#  		http://www.opensource.org/licenses/lgpl-license.php
#  
#  For further information visit:
#  		http://www.fckeditor.net/
#  
#  File Name: basexml.pl
#  	This is the File Manager Connector for Perl.
#  
#  File Authors:
#  		Takashi Yamaguchi (jack@omakase.net)
#####

sub CreateXmlHeader
{

	local($command,$resourceType,$currentFolder) = @_;

	# Create the XML document header.
	print '<?xml version="1.0" encoding="utf-8" ?>';

	# Create the main "Connector" node.
	print '<Connector command="' . $command . '" resourceType="' . $resourceType . '">';

	# Add the current folder node.
	print '<CurrentFolder path="' . ConvertToXmlAttribute($currentFolder) . '" url="' . ConvertToXmlAttribute(GetUrlFromPath($resourceType,$currentFolder)) . '" />';
}

sub CreateXmlFooter
{
	print '</Connector>';
}

1;
