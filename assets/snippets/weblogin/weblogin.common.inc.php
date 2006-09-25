<?php
/**
 * Commonly used login functions
 * Writen By Raymond Irving April, 2005
 *
 */

    // extract declarations
    function webLoginExtractDeclarations(&$html){
        $declare  = array();
        if(strpos($html,"<!-- #declare:")===false) return $declare;
        $matches= array();
        if (preg_match_all("/<\!-- \#declare\:(.*)[^-->]?-->/i",$html,$matches)) {    
            for($i=0;$i<count($matches[1]);$i++) {
                $tag = explode(" ",$matches[1][$i]);
                $tagname=trim($tag[0]);
                $tagvalue=trim($tag[1]);
                $declare[$tagname] = $tagvalue;
            }
            // remove declarations
            $html = str_replace($matches[0],"",$html);
        }
        return $declare;
    }

    // show javascript alert    
    function webLoginAlert($msg){
        return "<script>window.setTimeout(\"alert('".addslashes(mysql_escape_string($msg))."')\",10);</script>";
    }

    // generate new password
    function webLoginGeneratePassword($length = 10) {
        $allowable_characters = "abcdefghjkmnpqrstuvxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";
        $ps_len = strlen($allowable_characters);
        mt_srand((double)microtime()*1000000);
        $pass = "";
        for($i = 0; $i < $length; $i++) {
            $pass .= $allowable_characters[mt_rand(0,$ps_len-1)];
        }
        return $pass;
    }    

    // Send new password to the user
    function webLoginSendNewPassword($email,$uid,$pwd,$ufn){
        global $modx;
        $mailto = $modx->config['mailto'];
        $websignupemail_message = $modx->config['websignupemail_message'];    
        $emailsubject = $modx->config['emailsubject'];
        $emailsender = $modx->config['emailsender']; 
        $site_name = $modx->config['site_name'];
        $site_start = $modx->config['site_start'];
        $message = sprintf($websignupemail_message, $uid, $pwd); // use old method
        // replace placeholders
        $message = str_replace("[+uid+]",$uid,$message);
        $message = str_replace("[+pwd+]",$pwd,$message);
        $message = str_replace("[+ufn+]",$ufn,$message);
        $message = str_replace("[+sname+]",$site_name,$message);
        $message = str_replace("[+semail+]",$emailsender,$message);
        $message = str_replace("[+surl+]",dirname(PHP_SELF),$message);
        if(!mail($email, $emailsubject, $message, "From: ".$emailsender."\r\n"."X-Mailer: Content Manager - PHP/".phpversion())) {
            return webLoginAlert("Error while sending mail to $mailto",1);
        }        
        return true;
    }

?>