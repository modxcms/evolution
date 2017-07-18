<?php
if(IN_MANAGER_MODE != "true") {
	die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('edit_document')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : NULL;
$reset = isset($_POST['reset']) && $_POST['reset'] == 'true' ? 1 : 0;
$items = isset($_POST['list']) ? $_POST['list'] : '';
$ressourcelist = '';
$updateMsg = '';

// check permissions on the document
include_once MODX_MANAGER_PATH . "processors/user_documents_permissions.class.php";
$udperms = new udperms();
$udperms->user = $modx->getLoginUserID();
$udperms->document = $id;
$udperms->role = $_SESSION['mgrRole'];

if(!$udperms->checkPermissions()) {
	$modx->webAlertAndQuit($_lang["access_permission_denied"]);
}

if(isset($_POST['listSubmitted'])) {
	$updateMsg .= '<div class="warning" id="updated">' . $_lang['sort_updated'] . '</div>';
	if(strlen($items) > 0) {
		$items = explode(';', $items);
		foreach($items as $key => $value) {
			$docid = ltrim($value, 'item_');
			$key = $reset ? 0 : $key;
			if(is_numeric($docid)) {
				$modx->db->update(array('menuindex' => $key), $modx->getFullTableName('site_content'), "id='{$docid}'");
			}
		}
	}
}

$limit = 0;
$disabled = 'true';
$pagetitle = '';
if($id !== NULL) {
	$rs = $modx->db->select('pagetitle', $modx->getFullTableName('site_content'), "id='{$id}'");
	$pagetitle = $modx->db->getValue($rs);

	$rs = $modx->db->select('id, pagetitle, parent, menuindex, published, hidemenu, deleted', $modx->getFullTableName('site_content'), "parent='{$id}'", 'menuindex ASC');
	$resource = $modx->db->makeArray($rs);
	$limit = count($resource);
	if($limit < 1) {
		$updateMsg = $_lang['sort_nochildren'];
	} else {
		$disabled = 0;
		foreach($resource as $item) {
			// Add classes to determine whether it's published, deleted, not in the menu
			// or has children.
			// Use class names which match the classes in the document tree
			$classes = '';
			$classes .= ($item['hidemenu']) ? ' notInMenuNode ' : ' inMenuNode';
			$classes .= ($item['published']) ? ' publishedNode ' : ' unpublishedNode ';
			$classes = ($item['deleted']) ? ' deletedNode ' : $classes;
			$hasChildren = (count($modx->getChildIds($item['id'], 1)) > 0) ? '<i class="' . $_style['files_folder'] . '"></i> ' : ' <i class="' . $_style['files_page_html'] . '"></i> ';
			$ressourcelist .= '<li id="item_' . $item['id'] . '" class="sort ' . $classes . '" title="">' . $hasChildren . $item['pagetitle'] . ' <small>(' . $item['id'] . ')</small></li>';
		}
	}
}

$pagetitle = $id == 0 ? $site_name : $pagetitle;
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

	parent.tree.updateTree();

	var actions = {
		save: function() {
			var el = document.getElementById('updated');
			if(el) el.style.display = 'none';
			el = document.getElementById('updating');
			if(el) el.style.display = 'block';
			setTimeout("document.sortableListForm.submit()", 1000);
		},
		cancel: function() {
			document.location.href = 'index.php?a=2';
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

<h1>
	<i class="fa fa-sort-numeric-asc"></i><?= $_lang["sort_menuindex"] ?>
</h1>

<?= $_style['actionbuttons']['dynamic']['save'] ?>

<div class="tab-page">
	<div class="container container-body">
		<b><?= $pagetitle ?> (<?= $id ?>)</b>
		<p><?= $_lang["sort_elements_msg"] ?></p>
		<p>
			<a class="btn btn-secondary" href="javascript:;" onclick="sort();return false;"><i class="<?= $_style['actions_sort'] ?>"></i> <?= $_lang['sort_alphabetically'] ?></a>
			<a class="btn btn-secondary" href="javascript:;" onclick="resetSortOrder();return false;"><i class="<?= $_style['actions_refresh'] ?>"></i> <?= $_lang['reset_sort_order'] ?></a>
		</p>
		<?= $updateMsg ?>
		<span class="warning" style="display:none;" id="updating"><?= $_lang['sort_updating'] ?></span>
		<ul id="sortlist" class="sortableList">
			<?= $ressourcelist ?>
		</ul>

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
