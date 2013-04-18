<?php

if($modx->manager->action == 17)
{
	$css_selectors   = 'left=justifyleft;right=justifyright';
	$params['theme']       = (empty($params['theme']))          ? 'editor' : $params['theme'];
	$ph['custom_plugins']  = $params['custom_plugins'];
	$ph['custom_buttons1'] = $params['custom_buttons1'];
	$ph['custom_buttons2'] = $params['custom_buttons2'];
	$ph['custom_buttons3'] = $params['custom_buttons3'];
	$ph['custom_buttons4'] = $params['custom_buttons4'];
	$ph['mce_template_docs'] = $params['mce_template_docs'];
	$ph['mce_template_chunks'] = $params['mce_template_chunks'];
	$ph['css_selectors']   = (!isset($params['css_selectors']))  ? $css_selectors   : $params['css_selectors'];
	$ph['mce_entermode']   = (empty($params['mce_entermode'])) ? 'p' : $params['mce_entermode'];
	$ph['mce_schema']      = (empty($params['mce_schema'])) ? 'html4' : $params['mce_schema'];
	$ph['mce_element_format'] = (empty($params['mce_element_format'])) ? 'xhtml' : $params['mce_element_format'];
}
else
{
	$ph = $params;
}
