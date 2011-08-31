<?php
/*******************************************************
 *
 * MODxMailer Class extends PHPMailer
 * Created by ZeRo (http://www.petit-power.com/)
 * updated by yama (http://kyms.ne.jp/)
 *
 * -----------------------------------------------------
 * [History]
 * Ver1.4.4.7    2011/06/07
 * -----------------------------------------------------
 * Update $Date: 2011-06-07 23:28:18 +0900 $ 
 *        $Revision: 61 $
 *******************************************************
 */

include_once MODX_MANAGER_PATH . 'includes/controls/class.phpmailer.php';

/* ------------------------------------------------------------------
 *
 * MODxMailer - Extended PHPMailer
 *
 * -----------------------------------------------------------------
 */

class MODxMailer extends PHPMailer
{
	var $mb_language          = null;
	var $encode_header_method = null;
	
	function MODxMailer()
	{
		global $modx;
		
		$this->IsMail();
		$this->From     = $modx->config['emailsender'];
		$this->FromName = $modx->config['site_name'];
		
		switch(strtolower($modx->config['manager_language']))
		{
			case 'japanese-utf8':
			case 'japanese-euc':
				$this->CharSet     = 'ISO-2022-JP';
				$this->Encoding    = '7bit';
				$this->mb_language = 'Japanese';
				$this->encode_header_method = 'mb_encode_mimeheader';
				$this->IsHTML(false);
				break;
			case 'russian-utf8':
				$this->CharSet     = 'UTF-8';
				$this->Encoding    = 'base64';
				$this->mb_language = 'UNI';
				break;
			case 'english':
				$this->CharSet     = 'iso-8859-1';
				$this->Encoding    = 'quoted-printable';
				$this->mb_language = 'English';
				break;
			default:
				$this->CharSet     = 'UTF-8';
				$this->Encoding    = 'base64';
				$this->mb_language = 'UNI';
		}
	}
	
	function Send()
	{
		global $modx;
		
	    if(function_exists(mb_language))
		{
			mb_language($this->mb_language);
			mb_internal_encoding($modx->config['modx_charset']);
			$this->Body = mb_convert_encoding($this->Body, $this->CharSet, $modx->config['modx_charset']);
		}
		return parent::Send();
	}
	
	function EncodeHeader($str, $position = 'text')
	{
		if($this->encode_header_method=='mb_encode_mimeheader')
		{
			 return mb_encode_mimeheader($str, $this->CharSet, 'B');
		}
		else return parent::EncodeHeader($str, $position);
	}
}
