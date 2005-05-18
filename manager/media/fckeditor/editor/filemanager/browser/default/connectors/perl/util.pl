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
#  File Name: util.pl
#  	This is the File Manager Connector for Perl.
#  
#  File Authors:
#  		Takashi Yamaguchi (jack@omakase.net)
#####

sub RemoveFromStart
{
	local($sourceString, $charToRemove) = @_;
	$sPattern = '^' . $charToRemove . '+' ;
	$sourceString =~ s/^$charToRemove+//g;
	return $sourceString;
}

sub RemoveFromEnd
{
	local($sourceString, $charToRemove) = @_;
	$sPattern = $charToRemove . '+$' ;
	$sourceString =~ s/$charToRemove+$//g;
	return $sourceString;
}

sub ConvertToXmlAttribute
{
	local($value) = @_;
	return $value;
#	return utf8_encode(htmlspecialchars($value));

}

1;
