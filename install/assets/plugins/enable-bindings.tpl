//<?php
/**
 * Bindings enable-disable
 * 
 * Added system config Bindings commands enable-disable
 *
 * @category 	plugin
 * @version 	0.1
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@events OnInterfaceSettingsRender 
 * @internal	@modx_category Manager and Admin
 * @internal    @installset base, sample
  *
 * @author yama  / created: 2010/10/03
 */

$e = &$modx->Event; 
global $settings;
$action = $modx->manager->action;
if($action!==17) return;
$enable_bindings = (is_null($settings['enable_bindings'])) ? '1' : $settings['enable_bindings'];
$html = render_html($enable_bindings);
$e->output($html);

function render_html($enable_bindings)
{
	global $_lang;
	$str = '<table id="enable_bindings" style="width:inherit;" border="0" cellspacing="0" cellpadding="3">' . PHP_EOL;
	$str .= '  <tr class="row1">' . PHP_EOL;
	$str .= '    <td colspan="2" class="warning" style="color:#707070; background-color:#eeeeee"><h4 style="margin:3px;">@Bindingsの設定</h4></td>' . PHP_EOL;
	$str .= '  </tr>' . PHP_EOL;
	$str .= '  <tr>' . PHP_EOL;
	$str .= '    <td nowrap class="warning"><b>@Bindingsを有効にする</b></td>' . PHP_EOL;
	$str .= '    <td><input onchange="documentDirty=true;" type="radio" name="enable_bindings" value="1" ' . ($enable_bindings=='1' ? 'checked="checked"' : "") . ' />' . PHP_EOL;
	$str .=       $_lang["yes"] . '<br />' . PHP_EOL;
	$str .= '      <input onchange="documentDirty=true;" type="radio" name="enable_bindings" value="0" ' . (($enable_bindings=='0' || !isset($enable_bindings)) ? 'checked="checked"' : "" ) . ' />' . PHP_EOL;
	$str .=       $_lang["no"] . '</td>' . PHP_EOL;
	$str .= '  </tr>' . PHP_EOL;
	$str .= '  <tr class="row1">' . PHP_EOL;
	$str .= '	<td width="200">&nbsp;</td>' . PHP_EOL;
	$str .= '	<td class="comment"><a href="http://www.google.com/cse?cx=007286147079563201032%3Aigbcdgg0jyo&q=Bindings" target="_blank">@Bindings commands</a> enable</td>' . PHP_EOL;
	$str .= '  </tr>' . PHP_EOL;
	$str .= '</table>' . PHP_EOL;
	return $str;
}
