<?php

/*
 * CountryUpdate: an ip-to-country table updater plugin for SlimStat.
 * Copyright (C) 2006 Tim Lo
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/* ####################################################
# These are the 3 variabls you can change if you want #
#################################################### */

$ip_to_country_file = "ip-to-country.csv";	// name of ip-to-country file
$size_bytes = "5000000";					// max file size for $ip_to_country_file in bytes;
$uploaddir = "countrydb/";					// ensure directory name ends with trailing slash

/* Only tweak below this line if you know what you're doing */

$current_url = $_SERVER['PHP_SELF'];

echo '<p>Use this form to update to the latest version of the <a href="http://ip-to-country.webhosting.info/">IP-to-Country Database</a></p>';

if ( file_exists ( $uploaddir.$ip_to_country_file ) ) {
	unlink($uploaddir.$ip_to_country_file); // delete file if existing.
	echo "<p>Removed <code>$ip_to_country_file</code> left behind from previous update</p>";
}

if ( empty($option)){
		echo '
		<div class="module smallmodule">
		<h3>Upload IP-to-Country Database</h3>
		<div>
			<span class="countryupdate">
			<br />
			This file is normally quite large, please be patient while it uploads
			<form name="uploadform" action="'.$current_url.'?a=68&show=CountryUpdate&option=upload" method="post" enctype="multipart/form-data">
				<input type="file" name="countryfile" /><br />
				<input type="submit" value="upload">
			</form>
			</span></div>
		</div>
		
		</p>
		
		
		';
	} else {
		

			
			// CHECK FOR THE DIRECTORY
			if (!is_dir("$uploaddir")){ 
				mkdir("$uploaddir", 0777);
				echo $uploaddir." successfully created";
			}
			
			// CHECK CHMOD PROPERTIES
			if (!is_writeable("$uploaddir")){ 
				chmod("$uploaddir", 777);
			}
			
			// HANDLE FILE
				if (is_uploaded_file($_FILES['countryfile']['tmp_name'])) 
				{
					$size = $_FILES['countryfile']['size']; 
					
					if ($size > $size_bytes){
							echo "<p>The file was too big.</p>";
						}elseif(file_exists($uploaddir.$ip_to_country_file)){
							echo "<p>The file named <code>$ip_to_country_file</code> already exists</p>"; 
							}elseif(move_uploaded_file($_FILES['countryfile']['tmp_name'],$uploaddir.$ip_to_country_file)){
								$filelocation = $uploaddir.$ip_to_country_file;
								echo "<p><code>$ip_to_country_file</code> uploaded!</p>";
							}
				}
		
			// put it into the table.
			// clears out table
			$query = "TRUNCATE `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->countries )."`";
			mysql_query( $query );
			
			// start process of importing
			print "<p>Importing countries ";
			$country_data_str = "";
			$fd = fopen( $uploaddir.$ip_to_country_file, "r" );
			while ( !feof( $fd ) ) {
				$country_data_str .= fread( $fd, 4096 );
				$string_to_split = strrev( strstr( strrev( $country_data_str ), "\n" ) );
				$country_data_str = substr( $country_data_str, strlen( $string_to_split ) );
				$string_to_split = trim( $string_to_split );
				$country_data = explode( "\n", $string_to_split );
				foreach ( $country_data as $country_datum ) {
					$fields = explode( ",", $country_datum, 5 );
					if ( sizeof( $fields ) == 5 ) {
						$escaped_fields = array();
						foreach ( $fields as $field ) {
							$escaped_fields[] = SlimStat::my_esc( preg_replace( "/[^A-Za-z0-9\ ]*/", "", trim( $field ) ) );
						}
						$query = "INSERT INTO `".SlimStat::my_esc( $config->database )."`.`".SlimStat::my_esc( $config->countries )."` ( `ip_from`, `ip_to`, `country_code2`, `country_code3`, `country_name` ) VALUES ( \"".implode( "\", \"", $escaped_fields )."\" )";
						mysql_query( $query );
					}
				}
				print ". ";
				flush();
			}
			fclose( $fd );
			
			echo "<p>If there were no error messages above, then the ip-to-country table has been updated successfully</p>";
		
}
		




?>
