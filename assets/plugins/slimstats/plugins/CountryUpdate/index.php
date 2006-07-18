<?php

/*
 * CountryUpdate: An ip to country table updater plugin for SlimStat.
 * Copyright (C) 2006 Timothy Lo
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

$ip_to_country_file = "ip-to-country.csv";		// name of ip-to-country file
$ip_to_country_zip = "ip-to-country.csv.zip";	// name of ip-to-country archive file
$size_bytes = "5000000";						// max file size for $ip_to_country_file;
$uploaddir = "countrydb/";						// ensure directory name ends with trailing slash
$ip_to_country_remotefile = "http://ip-to-country.webhosting.info/downloads/ip-to-country.csv.zip";	// Full URL to zip file on webhosting.info

/* NO MORE EDITING BELOW THIS LINE NECESSARY UNLESS YOU WISH TO TINKER */
$country_updateinfo = "CountryUpdate v1.0";
$current_url = $_SERVER['PHP_SELF'];
$option = $_GET['option'];

// CHECK FOR THE DIRECTORY
			if (!is_dir("$uploaddir")){ 
				mkdir("$uploaddir", 0755);
				echo '<p><strong>INITIAL SETUP</strong><br />'.$uploaddir.' <span class="positive">successfully created</span></p>';
			}
			
// CHECK CHMOD PROPERTIES
			if (!is_writeable("$uploaddir")){ 
				chmod("$uploaddir", 755);
				echo '<p>'.$uploaddir.' successfully modified permisions';
			}

// CHECK ip_to_country FILE DATE

		$ch = curl_init(); // create cURL handler (ch)
		if (!$ch) {
		   die("Couldn't initialize a cURL handler");
		}

		$filepointer = $uploaddir.$ip_to_countryzip;
		$ret = curl_setopt($ch, CURLOPT_URL, $ip_to_country_remotefile);
		$ret = curl_setopt($ch, CURLOPT_HEADER, 1);
		$ret = curl_setopt($ch, CURLOPT_FILETIME, 1);
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$ret = curl_exec($ch);


// Check for Timestamp
		if (empty($ret)) {
		   die(curl_error($ch));
		   curl_close($ch);
		} else {
		   $info = curl_getinfo($ch);
		   curl_close($ch);
		
		   if (empty($info['filetime'])) {
				   die("No File Timestamp Determined");
		   } else {
				$rawdate = $info['filetime'];
				$friendlydate = date("M d Y", $rawdate);
		   }
		}

if (file_exists( $uploaddir.$ip_to_country_file )) {
	$loc_rawdate = filemtime($uploaddir.$ip_to_country_file);
	$loc_friendlydate = date ("M d Y", $loc_rawdate);
} else {
	$loc_friendlydate = "No Local File To Date";
}

if ($rawdate > $loc_rawdate) {
	$freshness = '<span class="negative">STALE</span>';
} else {
	$freshness = '<span class="positive">FRESH</span>';
}



// display

echo '<p>The <a href="http://ip-to-country.webhosting.info/">IP-to-Country Database</a> from <a href="http://webhosting.info">webhosting.info</a> was last updated: <strong>'.$friendlydate.'</strong>';
echo '<br>The IP-to-Country Database being used by SlimStat is dated: <strong>'.$loc_friendlydate.'</strong>';
echo '<br>This means that your copy is:</p><p><strong style="font-size: 50px;">'.$freshness.'</strong></p>';
echo '<p>You only need to do the below steps if the installation is <span class="negative"><strong>STALE</strong></span></p>';

if ( empty($option)){
		echo '
		<div class="module smallmodule">
		<h3>Automatic Transmission</h3>
		<div>
			<form name="retrievalbutton" action="'.$current_url.'?show=CountryUpdate&option=automatic" method="post">
				<input type="submit" value="AUTOMATIC" style="width: 225px; height: 100px; font-size: 30px;">
			</form>
			Please be patient it can take a few minutes.
		</div>	
		</div>
		
		
		<div class="module smallmodule">
		<h3>Manual Transmission</h3>
		<div>
			<span class="countryupdate">
			<br />
			Upload the ZIP file.
			<form name="uploadform" action="'.$current_url.'?show=CountryUpdate&option=upload" method="post" enctype="multipart/form-data">
				<input type="file" name="countryfile" /><br />
				<input type="submit" value="upload">
			</form>
			</span></div>
		</div>
		
		';
	} elseif ( $option=="automatic" ) {
				
	// GET FILE FROM WEBHOSTING.INFO
	
		    $store_name = $uploaddir.$ip_to_country_zip;
	        $f = fopen($store_name, "wb");
	        $ch = curl_init($ip_to_country_remotefile);
	        curl_setopt($ch, CURLOPT_HEADER, 0);
	        curl_setopt($ch, CURLOPT_FILE, $f);
	        curl_exec($ch);
	        curl_close($ch);
	        fclose($f);
			
	
	// EXTRACT FROM ZIP
			require_once('lib/pclzip.lib.php');
			$archive = new PclZip($uploaddir.$ip_to_country_zip);

			if ($archive->extract(PCLZIP_OPT_PATH, $uploaddir) == 0) {
				die("Error : ".$archive->errorInfo(true));
			} else {
				echo "<p>Extracted IP-to-Country Database successfully</p>";
			}
			flush();

	// INSERT INTO DB
	
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


	
	
	} elseif ( $option=="upload" ) {
		

			
			// CHECK FOR THE DIRECTORY
			if (!is_dir("$uploaddir")){ 
				mkdir("$uploaddir", 0755);
				echo $uploaddir." successfully created";
			}
			
			// CHECK CHMOD PROPERTIES
			if (!is_writeable("$uploaddir")){ 
				chmod("$uploaddir", 755);
			}
			
			
			// DELETE OLD FILES
			
			if ( file_exists ( $uploaddir.$ip_to_country_file ) ) {
				unlink($uploaddir.$ip_to_country_file); // delete file if existing.
				echo "<p>Removed <code>$ip_to_country_file</code> left behind from previous update</p>";
			}
			
			if ( file_exists ( $uploaddir.$ip_to_country_zip) ) {
				unlink($uploaddir.$ip_to_country_zip); // delete file if existing
				echo "<p>Removed <code>$ip_to_country_zip</code> left behind from previous update</p>";
			}
			
			
			// HANDLE FILE
				if (is_uploaded_file($_FILES['countryfile']['tmp_name'])) 
				{
					$size = $_FILES['countryfile']['size']; 
					if ($size > $size_bytes){
							echo "<p>The file was too big.</p>";
						}else{
						$filename = $_FILES['countryfile']['name'];
						move_uploaded_file($_FILES['countryfile']['tmp_name'],$uploaddir.$filename);
						echo "<p><strong>$filename</strong> uploaded!</p>"; 
			
						// CHECK FILE TYPE
						 $array = explode(".", $filename);
						 $nr    = count($array);
						 $ext  = $array[$nr-1];

						 if ($ext=="zip") {
							  require_once('lib/pclzip.lib.php');
							  $archive = new PclZip($uploaddir.$filename);
								// EXTRACT FROM ZIP
							  if ($archive->extract(PCLZIP_OPT_PATH, $uploaddir) == 0) {
								die("Error : ".$archive->errorInfo(true));
							  } else {
							  	echo "<p>Extracted IP-to-Country Database successfully</p>";
							  }
						 } else {
						 	echo "<p>$ip_to_country_file uploaded successfully</p>";
						 }
						 echo '
								<p>
									<form name="uploadform" action="'.$current_url.'?show=CountryUpdate&option=insert" method="post">
										<input type="submit" value="next">
									</form>
								</p>
								';

						}
				 }
				
	}elseif ($option=="insert"){


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
		

echo '<div style="clear: both;"><p>'.$country_updateinfo.'</p></div>';


?>
