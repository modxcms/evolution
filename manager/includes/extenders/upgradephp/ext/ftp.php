<?php
/*
   Provides the usual ftp_() functions (or at least work-alikes)
   for PHP versions compiled without.

   Hint: If you want to use it with PHP versions with compiled-in FTP
   support, then simply let your text editor replace all occourences
   of "ftp_" into "xftp_" or something similar.

   Public Domain, 2004, milky*users·sf·net
*/

if (!function_exists("ftp_connect")) {

 #-- config
 define("FTP_DEBUG", 0);
 define("FTP_PASV", 0);   // default data transfer mode

 #-- constants
 define("FTP_ASCII", "A");
 define("FTP_BINARY", "I");


 #-- establish socket connection,
 #   returns status+data array
 function ftp_connect($server, $port=21, $proxy_server=NULL, $proxy_port=21) {
    $fc = array();

    #-- proxy or direct connection
    if ($proxy_server) {
       $f = fsockopen($proxy_server, $proxy_port, $errno, $errstr, 15);
       $fc["proxy"] = 1;
    }
    else {
       if (strpos($server, ":")) {
          $server = strtok($server, ":");
          $port = strtok(":");
       }
       $f = fsockopen($server, (int)$port, $errno, $errstr, 15);
    }

    #-- socket connection established
    if ($f) {
       #-- socket connection settings
       socket_set_blocking($f, 1);

       #-- mk connection data hash
       $fc["host"] = $server;
       $fc["port"] = $port;
       $fc["f"] = $f;
       $fc["d"] = false;
       $fc["dport"] = false;
       $fc["pasv"] = FTP_PASV;

       #-- read greeting
       ftp_send($fc, "", 1);
       if (!$fc["err"] == 220) {
          ftp_err("server rejected further communication (after successful TCP/IP connection)");
          return(false);
       }

       #-- ret handle
       return($fc);
    }
    else {
       ftp_err("connecting to $server:$port$add_err failed [err$errno: \"$errstr\"]");
       return(false);
    }
 }


 #-- internal use
 function ftp_send(&$fc, $command, $wait100=0, $break100=0) {
    if ($fc) {

       #-- send command
       fwrite($fc["f"], $command);

       #-- read reply, line by line
       $r = "";
       while (!feof($fc["f"]) && ($line = fgets($fc["f"], 768))) {
          $r .= $line;

          #-- error/status number separated by space
          if ($line{3} === " ") {
             $err = (int) substr($line, 0, 3);
             if ($err >= 100) {
                $fc["err"] = $err;
                $fc["msg"] = rtrim(substr($line, 4));
                if (($err >= 200) || $break100) {
                   break;
                }
                elseif ($wait100) {
                   // nop, loop on until real status reply
                }
                elseif (FTP_DEBUG) {
                   trigger_error("ftp delay ($err): $fc[msg]", E_USER_NOTICE);
                }
          }  }
       }

       #-- general faults, closed connection
       switch ($err) {

          case 421:
            ftp_err("server closed connection - $fc[msg]");
            ftp_close($fc);
            $fc = false;
            break;

          case 332:
            $r = ftp_send("ACCT $fc[user]");
            break;

          default:
            if ($err >= 400) {
               trigger_error("ftp ($err): $fc[msg]", E_USER_NOTICE);
            }
       }

       #-- done
       if (FTP_DEBUG) {
          echo "»»» $command";
          echo "««« $r";
       }
       return($r);
    }
    else {
       ftp_err("invalid connection handle passed to ftp_send() function");
    }
 }


 #-- simply check for successful result
 function ftp_result(&$fc) {
    if ( ($fc["err"] <= 399) && ($fc["err"] >= 200) ) {
       return(true);
    }
 }


 #-- internal use
 function ftp_err($str) {
    if (is_array($str)) {
       $str = $str["err"] . " " . $str["msg"];
    }
    trigger_error("ftp: $str", E_USER_WARNING);
 }


 #-- authentification
 function ftp_login(&$fc, $user, $pw="") {

    #-- send user name
    $fc["user"] = $user;
    if ($user) {
       $user .= ($fc["proxy"] ? "@".$fc["host"] : "");
       ftp_send($fc, "USER $user\n");

       #-- password if required
       if ($pw || ($fc["err"] == 331)) {
          ftp_send($fc, "PASS $pw\n");
       }
       $r = ftp_result($fc);

       if ($fc["err"] != 230) {
          ftp_err("unsuccessful login");
       }
    }

    #-- check capabilities
    @ftp_send($fc, "MODE B\n");     // block mode supported?
    if ($fc["err"] == 200) {
       $fc["mode"] = "B";
    }
    else {
       $fc["mode"] = "S";
       ftp_send($fc, "MODE S\n");   // else stream mode (unreliable)
    }

    #-- pre-fetch system type
    ftp_send($fc, "SYST\n");
    $fc["sys"] = strtok($fc["msg"], " \r\n\t\f");

    #-- set default options
    @ftp_send($fc, "SITE UMASK 0022\n");
    ftp_send($fc, "TYPE A\n");
    ftp_send($fc, "PWD\n");
    if ($fc["err"] != 257)  {
       ftp_err("incompatible connection");
    }

    return($r);
 }


 #-- set data connection method/mode (will later be negotiated with server)
 function ftp_pasv(&$fc, $bool=1) {
    $fc["pasv"] = $bool ?1:0;
    // ftp_err("this ftp:// access module always uses the PASV server data connection mode");
 }


 #-- terminate ftp session
 function ftp_quit(&$fc) {
    ftp_send($fc, "QUIT\n");   // the server closes the tcp/ip connection
    ftp_close($fc);            // so this is not necessary
    $fc = false;
 }


 #-- close connections
 function ftp_close(&$fc) {
    if ($fc["d"] && !feof($fc["d"])) {
       @fclose($fc["d"]);
    }
    if (!feof($fc["f"])) {
       @fclose($fc["f"]);
    }
 }


 #-- simple functions -----------------------------------------------------

 function ftp_cdup(&$fc) {
    ftp_send($fc, "CDUP\n");
    return ftp_result($fc);
 }
 function ftp_chdir(&$fc, $path) {
    ftp_send($fc, "CWD $path\n");
    return ftp_result($fc);
 }
 function ftp_mkdir(&$fc, $path) {
    ftp_send($fc, "MKD $path\n");
    return ftp_result($fc);
 }
 function ftp_rmdir(&$fc, $path) {
    ftp_send($fc, "RMD $path\n");
    return ftp_result($fc);
 }
 function ftp_pwd(&$fc) {
    ftp_send($fc, "PWD $path\n");
    $d = $fc["msg"];
    $l = strpos($d, '"');
    $d = substr($d, $l+1, strrpos($d, '"')-$l-1);
    return($d);
 }

 function ftp_delete(&$fc, $file) {
    ftp_send($fc, "DELE $path\n");
    return ftp_result($fc);
 }
 function ftp_mv(&$fc, $from, $to) {
    ftp_send($fc, "RNFR $from\n");
    ftp_send($fc, "RNTO $to\n");
    return ftp_result($fc);
 }
 function ftp_chmod(&$fc, $perm, $path) {
    $perm = "0" . base_convert($perm, 10, 8);
    ftp_send($fc, "SITE CHMOD $perm $path\n");
    return ftp_result($fc);
 }

 function ftp_site(&$fc, $cmd) {
    $cmd = rtrim($cmd);
    ftp_send($fc, "SITE $cmd\n");
    return ftp_result($fc);
 }
 function ftp_exec(&$fc, $cmd) {
    ftp_site($fc, "EXEC $cmd\n");
    return ftp_result($fc);
 }
 function ftp_raw(&$fc, $str) {
    $str = rtrim($str) . "\n";
    $r = ftp_send($fc, $str);
    return(explode("\n", $r));
 }

 function ftp_systype(&$fc) {
    return $fc["sys"];
 }


 #-- file transfer calls --------------------------------------------------


 #-- file upload
 function ftp_fput(&$fc, $to, $fh, $mode=FTP_BINARY, $chunksize=65536) {
    if (!$fh) { return; }

    #-- data connection
    ftp_data_connection($fc);
    if ($chunksize >= 65536) {
       $chunksize = 65535;
    }

    #-- initiate file transfer
    ftp_send($fc, "TYPE $mode\n");
    set_time_limit(240);
    ftp_send($fc, "STOR $to\n", 0, 1);

    #-- server waiting for transfer?
    if (($fc["err"] == 150) || ($fc["err"] == 125)) {

       #-- connection mode
       if (!$fc["pasv"]) {
          $d = socket_accept($fc["s"]);
          $data_write = "socket_write";
          $data_close = "socket_close";
       }
       else {
          $d = & $fc["d"];
          $data_write = "fwrite";
          $data_close = "fclose";
       }

       #-- stream mode, simple
       if ($fc["mode"] == "S") {
          while ($fh && !feof($fh)) {
             $dat = fread($fh, $chunksize);
             $data_write($d, $dat);
          }
       }
       else {
          while ($fh && !feof($fh)) {
             $dat = fread($fh, $chunksize);
             $n = strlen($dat);
             $data_write($d, pack("cn", 0, $n));   // block header
             $data_write($d, $dat);
          }
          $data_write($fc["d"], pack("ccc", 0x40, 0, 0));   // EOF
       }

       #-- close server socket
       if (!$fc["pasv"]) {
          socket_close($d);
       }

       #-- transmission ok
       ftp_data_end($fc);
       ftp_send($fc, "", 1);
       $r = ($fc["err"] == 226) || ($fc["err"] == 250);

       #-- reset options
       if ($mode != FTP_ASCII) {
          ftp_send($fc, "TYPE A\n");
       }

    }
    else {
       ftp_err("$fc[err] $fc[msg]");
       $r = 0;
    }

    return($r);
 }


 function ftp_put(&$fc, $to, $fn, $mode=FTP_BINARY) {
    if (!is_resource($fn)) {
       $fn = fopen($fn, "rb");
    }
    $r = ftp_fput($fc, $to, $fn, $mode);
    fclose($fn);
    return($r);
 }




 #-- download
 function ftp_fget(&$fc, $to, $fh, $mode=FTP_BINARY, $chunksize=65536) {
    if (!$fh) { return; }

    #-- open connection
    ftp_data_connection($fc);

    #-- initiate file transfer
    ftp_send($fc, "TYPE $mode\n");
    set_time_limit(240);
    ftp_send($fc, "RETR $to\n", 0, 1);

    #-- server waiting for transfer?
    if (($fc["err"] == 150) || ($fc["err"] == 125)) {

       #-- connection mode
       if (!$fc["pasv"]) {
          $d = socket_accept($fc["s"]);
          $data_read = "socket_read";
          $data_close = "socket_close";
       }
       else {
          $d = & $fc["d"];
          $data_read = "fread";
          $data_close = "fclose";
       }

       #-- stream mode, simple
       if ($fc["mode"] == "S") {
          $dat = "+";
          while (strlen($dat)) {
             $dat = $data_read($d, $chunksize);
             fwrite($fh, $dat);
          }
       }
       else {
          $eof = 0;
          while (!$eof) {
             list($flags, $len) = unpack("cn", $data_read($d, 3));
             $eof = $flags & (0x40|0x80);

             if ($len) {
                $dat = $data_read($d, $len);
                fwrite($fh, $dat);
             }
          }
       }

       #-- close server socket
       if (!$fc["pasv"]) {
          $data_close($d);
       }

       #-- transmission ok
       ftp_data_end($fc);
       ftp_send($fc, "", 1);
       $r = ($fc["err"] == 226) || ($fc["err"] == 250);

       #-- reset options
       if ($mode != FTP_ASCII) {
          ftp_send($fc, "TYPE A\n");
       }

    }
    else {
       ftp_err("$fc[err] $fc[msg]");
       $r = 0;
    }

    return($r);
 }


 #-- directly into file
 function ftp_get(&$fc, $to, $fn, $mode=FTP_BINARY) {
    if (!is_resource($fn)) {
       $fn = fopen($fn, "wb");
    }
    $r = ftp_fget($fc, $to, $fn, $mode);
    fclose($fn);
    return($r);
 }



 #-- establishes a data connection ---------------------------------------
 function ftp_data_connection(&$fc) {
    if (!$fc["d"] || feof($fc["d"])) {

       #-- make client establish connection (PASsiVe server)
       if ($fc["pasv"]) {
          ftp_send($fc, "PASV\n");

          #-- reply ok?
          if ($fc["err"] == 227) {
             $l = strpos($fc["msg"], "(");
             $r = strpos($fc["msg"], ")", $l);
             $uu = explode(",", substr($fc["msg"], $l + 1, $r - $l - 1));
             $ip = "$uu[0].$uu[1].$uu[2].$uu[3]";
             $port = ((int)$uu[4]<<8) + ((int)$uu[5]);

             #-- all done
             if ($port) {
                if (!$fc["d"] = @fsockopen($ip, $port, $errno, $errstr, 25)) {
                   ftp_err("data socket connection could not be established [$errno - $errstr]");
                }
             }
             else {
                ftp_err("data connection negotiation problem (server uses wrong syntax)");
             }
          }

          #-- fall back
          else {
             $fc["pasv"] = 0;
             ftp_err("falling back to standard (ACTiVe server) connection mode");
             ftp_data_connection($fc);
          }
       }

       #-- else choose port we wish the server to contact us
       elseif (!$fc["s"]) {
          if (!function_exists("socket_listen")) {
             ftp_err("could not establish data connection, because PHP socket I/O functions are absent");
          }
          else {
             $ip = strtr(gethostbyname("localhost"), ".", ",");

             #-- loop, test randomly choosen ports
             $retry = 20;
             $s = 0;
             while ((!$s) && ($retry-- >= 0)) {
                $port = rand(3072, 65535);
                $p1 = ($port >> 8);
                $p2 = ($port & 0xFF);
                ftp_send($fc, "PORT $ip,$p1,$p2\n");
                if ($fc["err"] == 200) {
                   $s = socket_create_listen($port);
                }
             }
             if ($s) {
                $fc["s"] = $s;
             }
             else {
                ftp_err("could not create listening socket for (ACTiVe server) data connection");
             }
       }  }
    }
 }


 #-- closes data connection, (in stream mode)
 function ftp_data_end(&$fc) {
    if (($fc["d"]) && ($fc["mode"] == "S")) {
       if ($fc["pasv"]) {
          fclose($fc["d"]);
       }
       else {
          socket_close($fc["d"]);
       }
       $fc["d"] = false;
    }
 }


}



#------------------------------------------------------------- add-ons ---
# require a connection in $GLOBALS['fc'];


#-- transfers a file tree from source dir to destination on connected server
function ftp_xcopy($from, $to=NULL) {
   global $fc, $DEBUG;
   #-- dir
   if ($to) {
      ftp_xmkdir($to);
   }
   else {
      ftp_xmkdir($from);
      $to = $from;
   }
   if (!$from) {
      return;
   }
   #-- store files
   if (is_dir($from)) {
      if ($dh = opendir($from)) {
         $from = trim($from, "/");
         while ($fn = readdir($dh)) {
            if ($fn[0] != ".") {
               ftp_xcopy("$from/$fn");
            }
         }
         closedir($dh);
      }
      else {
         echo "error reading directory '$from'<br>\n";
      }
   }
   else {
      if ($f = fopen($from, "rb")) {
         if (!$DEBUG) {
#$cwd=getcwd(); echo "PUT $cwd/$from to $to  <br>\n";
            ftp_put($fc, $to, $from, FTP_BINARY);
            ftp_site($fc, "CHMOD 0644 $to");
         }
         else {
            echo "upload '$from' to 'ftp://.../$to'<br>\n";
         }
         fclose($f);
      }
      else {
         echo "error reading file '$from'<br>\n";
      }
   }
}


#-- creates directory trees for given filename strings and keeps track
#   of what it created, so you can call it without thought
function ftp_xmkdir($file) {
   global $fc, $ftp_dirs, $DEBUG;
   $p = strrpos($file, "/");
   if (!$p) {
      return;
   }
   $file = substr($file, 0, $p);
   if (in_array($file, $ftp_dirs)) {
      return;
   }
   $p = 0;
   $file .= "/";
   while ($p = strpos($file, "/", $p+1)) {
      $dir = substr($file, 0, $p);
      if (!in_array($dir, $ftp_dirs)) {
         if (!$DEBUG) {
            @ftp_mkdir($fc, $dir);
            @ftp_site($fc, "CHMOD 0755 $dir");
         }
         $ftp_dirs[] = $dir;
      }
   }
}


?>