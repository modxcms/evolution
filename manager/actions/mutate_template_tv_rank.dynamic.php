<?php
if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('save_template')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$reset = isset($_POST['reset']) && $_POST['reset'] == 'true' ? 1 : 0;

$tbl_site_templates = $modx->getFullTableName('site_templates');
$tbl_site_tmplvar_templates = $modx->getFullTableName('site_tmplvar_templates');
$tbl_site_tmplvars = $modx->getFullTableName('site_tmplvars');

$siteURL = $modx->config['site_url'];

$updateMsg = '';

if(isset($_POST['listSubmitted'])) {
	$updateMsg .= '<div class="warning" id="updated">' . $_lang['sort_updated'] . '</div>';
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
			$tmplvar = ltrim($item, 'item_');
			$modx->db->update(array('rank' => $key), $tbl_site_tmplvar_templates, "tmplvarid='{$tmplvar}' AND templateid='{$id}'");
		}
	}
	// empty cache
	$modx->clearCache('full');
}

$rs = $modx->db->select("tv.name AS name, tv.caption AS caption, tv.id AS id, tr.templateid, tr.rank, tm.templatename", "{$tbl_site_tmplvar_templates} AS tr
		INNER JOIN {$tbl_site_tmplvars} AS tv ON tv.id = tr.tmplvarid
		INNER JOIN {$tbl_site_templates} AS tm ON tr.templateid = tm.id", "tr.templateid='{$id}'", "tr.rank DESC, tv.rank DESC, tv.id DESC"     // workaround for correct sort of none-existing ranks
);
$limit = $modx->db->getRecordCount($rs);

if($limit > 1) {
	$tvsArr = array();
	while($row = $modx->db->getRow($rs)) {
		$tvsArr[] = $row;
	}
	$tvsArr = array_reverse($tvsArr, true);  // reverse ORDERBY DESC

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

<style type="text/css">
	ul.sortableList {
		margin: 0;
		}
	ul.sortableList li, .sort {
		position: relative;
		z-index: 1;
		max-width: 100%;
		width: 30rem;
		list-style: none;
		font-weight: bold;
		cursor: move;
		color: #444444;
		padding: .5rem;
		margin: .2rem 0;
		border: 1px solid #CCCCCC;
		background-color: #fff;
		display: block;
		-webkit-transform: translateY(0);
		transform: translateY(0);
		}
	.sortableList .ghost {
		z-index: 2;
		opacity: .5;
		}
</style>

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
			window.location.href = 'index.php?a=16&amp;id=<?= $id ?>';
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

	var sortdir = 'asc';

	function sort() {
		var els = document.querySelectorAll('li.sort');
		var keyA, keyB;
		if(sortdir === 'asc') {
			els = [].slice.call(els).sort(function(a, b) {
				keyA = a.innerText.toLowerCase();
				keyB = b.innerText.toLowerCase();
				return keyA.localeCompare(keyB);
			});
			sortdir = 'desc'
		} else {
			els = [].slice.call(els).sort(function(b, a) {
				keyA = a.innerText.toLowerCase();
				keyB = b.innerText.toLowerCase();
				return keyA.localeCompare(keyB);
			});
			sortdir = 'asc'
		}
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

		<form action="" method="post" name="sortableListForm">
			<input type="hidden" name="listSubmitted" value="true" />
			<input type="hidden" id="list" name="list" value="" />
		</form>
	</div>
</div>

<script type="text/javascript">

	[].slice.call(document.querySelectorAll('.sort')).forEach(function(a) {
		a.onmousedown = function(e) {
			var b, c;
			a.classList.add('ghost');
			a.position = a.getBoundingClientRect();
			b = e.pageY - a.position.top;
			document.onselectstart = function() {
				return false
			};
			document.onmousemove = function(e) {
				c = e.pageY - a.position.top - b;
				if(c >= a.offsetHeight && a.nextSibling) {
					b += a.offsetHeight;
					a.parentNode.insertBefore(a, a.nextSibling.nextSibling);
					c = 0
				} else if(c < -a.offsetHeight && a.previousSibling) {
					b -= a.offsetHeight;
					a.parentNode.insertBefore(a, a.previousSibling);
					c = 0
				} else if(!a.previousSibling && c < 0 || !a.nextSibling && c > 0) {
					c = 0
				}
				a.style.webkitTransform = 'translateY(' + c + 'px)';
				a.style.transform = 'translateY(' + c + 'px)';
			};
			document.onmouseup = function() {
				a.style.webkitTransform = '';
				a.style.transform = '';
				a.classList.remove('ghost');
				document.onmousemove = null;
				renderList();
			}
		}
	});

</script>
