<?php
if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('save_template')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$reset = isset($_POST['reset']) && $_POST['reset'] == 'true' ? 1 : 0;

$tbl_site_tmplvars = $modx->getFullTableName('site_tmplvars');

$siteURL = $modx->config['site_url'];

$updateMsg = '';

if(isset($_POST['listSubmitted'])) {
	$updateMsg .= '<span class="warning" id="updated">' . $_lang['sort_updated'] . '</span>';
	foreach($_POST as $listName => $listValue) {
		if($listName == 'listSubmitted' || $listName == 'reset') {
			continue;
		}
		$orderArray = explode(';', rtrim($listValue, ';'));
		foreach($orderArray as $key => $item) {
			if(strlen($item) == 0) {
				continue;
			}
			$key = $reset ? 0 : $key;
			$id = ltrim($item, 'item_');
			$modx->db->update(array('rank' => $key), $tbl_site_tmplvars, "id='{$id}'");
		}
	}
	// empty cache
	$modx->clearCache('full');
}

$rs = $modx->db->select("name, caption, id, rank", $tbl_site_tmplvars, "", "rank ASC, id ASC");
$limit = $modx->db->getRecordCount($rs);

if($limit > 1) {
	$tvsArr = array();
	while($row = $modx->db->getRow($rs)) {
		$tvsArr[] = $row;
	}

	$i = 0;
	foreach($tvsArr as $row) {
		if($i++ == 0) {
			$evtLists .= '<strong>' . $row['templatename'] . '</strong><ul id="sortlist" class="sortableList">';
		}
		$caption = $row['caption'] != '' ? $row['caption'] : $row['name'];
		$evtLists .= '<li id="item_' . $row['id'] . '" class="sort">' . $caption . ' <small class="protectedNode" style="float:right">[*' . $row['name'] . '*]</small></li>';
	}
	$evtLists .= '</ul>';
}

?>
<script type="text/javascript">
	var actions = {
		save: function() {
			var el = document.getElementById('updated');
			if(el) el.style.display = 'none';
			el = document.getElementById('updating');
			if(el) el.style.display = 'block';
			setTimeout("document.sortableListForm.submit()", 1000);
		},
		cancel: function() {
			document.location.href = 'index.php?a=76';
		}
	};

	function renderList() {
		var list = '';
		var els = document.querySelectorAll('li.sort');
		for(var i = 0; i < els.length; i++) {
			list += els[i].id + ';';
		}
		document.getElementById('list').value = list
	}

	function sort() {
		var els = document.querySelectorAll('li.sort');
		els = [].slice.call(els).sort(function(a, b) {
			var keyA = a.innerText.toLowerCase();
			var keyB = b.innerText.toLowerCase();
			return keyA.localeCompare(keyB);
		});
		var ul = document.getElementById('sortlist');
		var list = '';
		for(var i = 0; i < els.length; i++) {
			ul.appendChild(els[i]);
			list += els[i].id + ';';
		}
		document.getElementById('list').value = list
	}

	function resetSortOrder() {
		if(confirm("<?= $_lang["confirm_reset_sort_order"] ?>") === true) {
			documentDirty = false;
			var input = document.createElement("input");
			input.type = "hidden";
			input.name = "reset";
			input.value = "true";
			document.sortableListForm.appendChild(input);
			actions.save();
		}
	}
</script>

<style type="text/css">
	li { list-style: none; }
	ul.sortableList {
		margin: 0px;
		}
	ul.sortableList li, .sort {
		width: 30rem;
		font-weight: bold;
		cursor: move;
		color: #444444;
		padding: .5rem;
		margin: .2rem 0;
		border: 1px solid #CCCCCC;
		background-color: #fff;
		display: block;
		}
	.ghost {
		opacity: .5;
		}
</style>

<h1><?= $_lang["template_tv_edit_title"] ?></h1>

<?= $_style['actionbuttons']['dynamic']['save'] ?>

<div class="tab-page">
	<div class="container container-body">
		<b><?= $_lang['template_tv_edit'] ?></b>
		<p><?= $_lang["tmplvars_rank_edit_message"] ?></p>
		<p>
			<a class="btn btn-secondary" href="javascript:;" onclick="sort();return false;"><i class="<?= $_style['actions_sort'] ?>"></i> <?= $_lang['sort_alphabetically'] ?></a>
			<a class="btn btn-secondary" href="javascript:;" onclick="resetSortOrder();return false;"><i class="<?= $_style['actions_refresh'] ?>"></i> <?= $_lang['reset_sort_order'] ?></a>
		</p>
		<?= $updateMsg ?>
		<span class="warning" style="display:none;" id="updating"><?= $_lang['sort_updating'] ?></span>
		<?= $evtLists ?>
	</div>
</div>
<form action="" method="post" name="sortableListForm">
	<input type="hidden" name="listSubmitted" value="true" />
	<input type="hidden" id="list" name="list" value="" />
</form>

<script type="text/javascript">
	//
	//	var sortable = {
	//		dragEl: null,
	//		nextEl: null,
	//		create: function(a) {
	//			[].slice.call(a.childNodes).forEach(function(a) {
	//				a.draggable = true;
	//				a.ondragstart = sortable.ondragstart;
	//				a.ondragover = sortable.ondragover;
	//				a.ondragend = sortable.ondragend;
	//			});
	//		},
	//		ondragstart: function(e) {
	//			sortable.dragEl = e.target;
	//			sortable.nextEl = sortable.dragEl.nextSibling;
	//			sortable.dragEl.classList.add('ghost');
	//			e.dataTransfer.effectAllowed = 'move';
	//		},
	//		ondragover: function(e) {
	//			e.dataTransfer.dropEffect = 'move';
	//			var target = e.target;
	//			if(target && target !== sortable.dragEl && target.nodeName === 'LI') {
	//				var pos = target.getBoundingClientRect();
	//				var next = (e.clientY - pos.top) / (pos.bottom - pos.top) > .5;
	//				target.parentNode.insertBefore(sortable.dragEl, next && target.nextSibling || target);
	//			}
	//			e.preventDefault();
	//		},
	//		ondragend: function(e) {
	//			e.preventDefault();
	//			sortable.dragEl.classList.remove('ghost');
	//			if(sortable.nextEl !== sortable.dragEl.nextSibling) {
	//				renderList();
	//				e.preventDefault();
	//			}
	//		}
	//	};
	//
	//	sortable.create(document.getElementById('sortlist'));

	new Sortables($('sortlist'), {
			initialize: function() {
				renderList();
			},
			onComplete: function() {
				renderList();
			}
		}
	);
</script>
