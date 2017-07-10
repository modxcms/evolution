<?php
if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('save_plugin')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$siteURL = $modx->config['site_url'];

$updateMsg = '';

if(isset($_POST['listSubmitted'])) {
	$updateMsg .= "<span class=\"warning\" id=\"updated\">Updated!<br /><br /> </span>";
	$tbl = $modx->getFullTableName('site_plugin_events');

	foreach($_POST as $listName => $listValue) {
		if($listName == 'listSubmitted') {
			continue;
		}
		$orderArray = explode(',', $listValue);
		$listName = ltrim($listName, 'list_');
		if(count($orderArray) > 0) {
			foreach($orderArray as $key => $item) {
				if($item == '') {
					continue;
				}
				$pluginId = ltrim($item, 'item_');
				$modx->db->update(array('priority' => $key), $tbl, "pluginid='{$pluginId}' AND evtid='{$listName}'");
			}
		}
	}
	// empty cache
	$modx->clearCache('full');
}

$rs = $modx->db->select("sysevt.name as evtname, sysevt.id as evtid, pe.pluginid, plugs.name, pe.priority, plugs.disabled", $modx->getFullTableName('system_eventnames') . " sysevt
		INNER JOIN " . $modx->getFullTableName('site_plugin_events') . " pe ON pe.evtid = sysevt.id
		INNER JOIN " . $modx->getFullTableName('site_plugins') . " plugs ON plugs.id = pe.pluginid", '', 'sysevt.name,pe.priority');

$insideUl = 0;
$preEvt = '';
$evtLists = '';
$sortables = array();

while($plugins = $modx->db->getRow($rs)) {
	if($preEvt !== $plugins['evtid']) {
		$sortables[] = $plugins['evtid'];
		$evtLists .= $insideUl ? '</ul><br />' : '';
		$evtLists .= '<strong>' . $plugins['evtname'] . '</strong><br /><ul id="' . $plugins['evtid'] . '" class="sortableList">';
		$insideUl = 1;
	}
	$evtLists .= '<li id="item_' . $plugins['pluginid'] . '"' . ($plugins['disabled'] ? ' style="color:#AAA"' : '') . '>' . $plugins['name'] . ($plugins['disabled'] ? ' (hide)' : '') . '</li>';
	$preEvt = $plugins['evtid'];
}
if($insideUl) {
	$evtLists .= '</ul>';
}

require_once(MODX_MANAGER_PATH . 'includes/header.inc.php');
?>

<style type="text/css">
	.topdiv {
		border: 0;
		}
	.subdiv {
		border: 0;
		}
	li { list-style: none; }
	.tplbutton {
		text-align: right;
		}
	ul.sortableList {
		margin: 0px;
		width: 300px;
		font-family: Arial, sans-serif;
		}
	ul.sortableList li {
		font-weight: bold;
		cursor: move;
		color: #444444;
		padding: 3px 5px;
		margin: 4px 0px;
		border: 1px solid #CCCCCC;
		background: #f2f2f2;
		}
	#sortableListForm { display: none; }
</style>
<script type="text/javascript" src="media/script/mootools/mootools.js"></script>
<script type="text/javascript">

	function save() {
		setTimeout("document.sortableListForm.submit()", 1000);
	}

	window.addEvent('domready', function() {
		<?php
		foreach($sortables as $list) {
		?>

		new Sortables($('<?php echo $list ?>'), {
			onComplete: function() {
				var id = null;
				var list = this.serialize(function(el) {
					id = el.getParent().id;
					return el.id;
				});
				$('list_' + id).value = list;
			}
		});
		<?php
		}
		?>

	});
</script>

<h1><?php echo $_lang['plugin_priority_title'] ?></h1>

<div id="actions"
	<ul class="actionButtons">
		<li class="transition"><a href="javascript:;" onclick="save();"><i class="<?php echo $_style["actions_save"] ?>"></i> <?php echo $_lang['save'] ?></a></li>
		<li class="transition"><a href="javascript:;" onclick="window.location.href='index.php?a=76';"><i class="<?php echo $_style["actions_cancel"] ?>"></i> <?php $_lang['cancel'] ?></a></li>
	</ul>
</div>

<div class="section">
	<div class="sectionHeader"><?php echo $_lang['plugin_priority'] ?></div>
	<div class="sectionBody">
		<p><?php echo $_lang['plugin_priority_instructions'] ?></p>

		<?php echo $updateMsg ?><span class="warning" style="display:none;" id="updating">Updating...<br /><br /> </span>

		<?php echo $evtLists ?>

		<form action="" method="post" name="sortableListForm" style="display: none;">
			<input type="hidden" name="listSubmitted" value="true" />
			<?php
			foreach($sortables as $list) {
				?>
				<input type="text" id="list_<?php echo $list ?>" name="list_<?php echo $list ?>" value="" />
				<?php
			}
			?>
		</form>
	</div>
</div>
