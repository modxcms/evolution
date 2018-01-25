<?php
class WFBC
{
	function __construct()
	{
	}
	
	function fetch($tpl)
	{
		global $modx;
		$template = '';
		if(substr($tpl, 0, 5) == "@FILE")
		{
			$template = file_get_contents(ltrim(substr($tpl, 6)));
		}
		elseif(substr($tpl, 0, 5) == "@CODE")
		{
			$template = substr($tpl, 6);
		}
		elseif ($modx->getChunk($tpl) != "")
		{
			$template = $modx->getChunk($tpl);
		}
		else
		{
			$template = $tpl;
		}
		return $template;
	}
}