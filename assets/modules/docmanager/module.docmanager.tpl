include_once(MODX_BASE_PATH.'assets/modules/docmanager/classes/docmanager.class.php');
include_once(MODX_BASE_PATH.'assets/modules/docmanager/classes/dm_frontend.class.php');
include_once(MODX_BASE_PATH.'assets/modules/docmanager/classes/dm_backend.class.php');

$dm = new DocManager($modx);
$dmf = new DocManagerFrontend($dm, $modx);
$dmb = new DocManagerBackend($dm, $modx);

$dm->ph = $dm->getLang();
$dm->ph['theme'] = $dm->getTheme();
$dm->ph['ajax.endpoint'] = MODX_SITE_URL.'assets/modules/docmanager/tv.ajax.php';
$dm->ph['datepicker.offset'] = $modx->config['datepicker_offset'];
$dm->ph['datetime.format'] = $modx->config['datetime_format'];

if (isset($_POST['tabAction'])) {
	$dmb->handlePostback();
} else {
	$dmf->getViews();
	echo $dm->parseTemplate('main.tpl', $dm->ph);
}
