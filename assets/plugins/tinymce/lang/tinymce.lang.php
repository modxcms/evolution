<?php
function getTinyMCELang($lang)
{
	switch($lang)
	{
		case 'bulgarian'             : $js_lang = 'bg'; break;
		case 'czech'                 : $js_lang = 'cs'; break;
		case 'danish'                : $js_lang = 'da'; break;
		case 'german'                : $js_lang = 'de'; break;
		case 'english'               :
		case 'english-british'       : $js_lang = 'en'; break;
		case 'spanish-utf8'          :
		case 'spanish'               : $js_lang = 'es'; break;
		case 'persian'               : $js_lang = 'fa'; break;
		case 'finnish'               : $js_lang = 'fi'; break;
		case 'francais'              :
		case 'francais-utf8'         : $js_lang = 'fr'; break;
		case 'hebrew'                : $js_lang = 'he'; break;
		case 'italian'               : $js_lang = 'it'; break;
		case 'japanese-utf8'         :
		case 'japanese-euc'          : $js_lang = 'ja'; break;
		case 'nederlands-utf8'       :
		case 'nederlands'            : $js_lang = 'nl'; break;
		case 'norsk'                 : $js_lang = 'nn'; break;
		case 'polish-utf8'           :
		case 'polish'                : $js_lang = 'pl'; break;
		case 'portuguese-br'         :
		case 'portuguese'            : $js_lang = 'pt'; break;
		case 'russian'               :
		case 'russian-UTF8'          : $js_lang = 'ru'; break;
		case 'svenska'               :
		case 'svenska-utf8'          : $js_lang = 'sv'; break;
		case 'chinese'               :
		case 'simple_chinese-gb2312' : $js_lang = 'zh'; break;
		default                      : $js_lang = 'en';
	}
	return $js_lang;
}
