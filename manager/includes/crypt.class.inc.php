<?php

class rc4crypt {
	function endecrypt ($pwd, $data, $case='') {
		if ($case == 'de') {
			$data = urldecode($data);
		}

		$key[] = "";
		$box[] = "";
		$temp_swap = "";
		$pwd_length = 0;

		$pwd_length = strlen($pwd);

		for ($i = 0; $i <= 255; $i++) {
			$key[$i] = ord(substr($pwd, ($i % $pwd_length), 1));
			$box[$i] = $i;
		}

		$x = 0;

		for ($i = 0; $i <= 255; $i++) {
			$x = ($x + $box[$i] + $key[$i]) % 256;
			$temp_swap = $box[$i];

			$box[$i] = $box[$x];
			$box[$x] = $temp_swap;
		}

		$temp = "";
		$k = "";

		$cipherby = "";
		$cipher = "";

		$a = 0;
		$j = 0;

		for ($i = 0; $i < strlen($data); $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;

			$temp = $box[$a];
			$box[$a] = $box[$j];

			$box[$j] = $temp;

			$k = $box[(($box[$a] + $box[$j]) % 256)];
			$cipherby = ord(substr($data, $i, 1)) ^ $k;

			$cipher .= chr($cipherby);
		}

		if ($case == 'de') {
			$cipher = urldecode(urlencode($cipher));
		} 
		else {
			$cipher = urlencode($cipher);
		}

		return $cipher;
	}
}

// to be removed : note bGljZW5zZU9L decodes to licenseOK
/*function checkSession() {
	return true;//!empty($_POST[base64_decode("bGljZW5zZU9L")]) && $_POST[base64_decode("bGljZW5zZU9L")]=="on" ? base64_encode(md5($_POST['username'])) : false; 
}*/

?>