/**
 * mm_rules
 *
 * Default ManagerManager rules.
 *
 * @category	chunk
 * @version 	1.0.6
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal 	@modx_category Js
 * @internal    @overwrite false
 * @internal    @installset base, sample
 */

// more example rules are in assets/plugins/managermanager/example_mm_rules.inc.php
// example of how PHP is allowed - check that a TV named documentTags exists before creating rule

if ($modx->db->getValue($modx->db->select('count(id)', $modx->getFullTableName('site_tmplvars'), "name='documentTags'"))) {
	mm_widget_tags('documentTags', ' '); // Give blog tag editing capabilities to the 'documentTags (3)' TV
}
mm_widget_showimagetvs(); // Always give a preview of Image TVs
if(isset($modx->event->params)&&($modx->event->name=='OnDocFormPrerender'||$modx->event->name=='OnDocFormRender')){
	$tpl=$modx->event->params['template'];
	$q=$modx->db->query('select tv.name,tv.category as catId,c.category from '.$modx->db->config['table_prefix'].'site_tmplvars tv,'.$modx->db->config['table_prefix'].'site_tmplvar_templates tvt,'.$modx->db->config['table_prefix'].'categories c where c.id=tv.category and tvt.tmplvarid=tv.id and c.category<>\'Content\' and tvt.templateid='.$tpl.' order by tv.rank');
	$categories=array();
	$fields=array();
	while($r=$modx->db->getRow($q)){
		$fields[$r['name']]=$r['catId'];
		$categories[$r['catId']]=$r['category'];
	}
	if(count($categories)>0)foreach($categories as $k=>$v)mm_createTab($v,'cat'.$k, '','','','');
	if(count($fields)>0)foreach($fields as $k=>$v)mm_moveFieldsToTab($k,'cat'.$v,'','');
}
