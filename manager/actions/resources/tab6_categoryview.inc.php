<?php if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly."); ?>

<!-- category view -->
<div class="tab-page" id="tabCategory">
	<h2 class="tab"><?php echo $_lang["element_categories"] ?></h2>
	<script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabCategory" ) );</script>

	<div id="category-info" class="msg-container" style="display:none">
		<p class="element-edit-message"><?php echo $_lang['category_msg']; ?></p>
        <p class="viewoptions-message"><?php echo $_lang['view_options_msg']; ?></p>
	</div>
	<ul class="actionButtons">
		<li>
			<form class="filterElements-form">
				<input class="form-control" type="text" placeholder="<?php echo $_lang['element_filter_msg']; ?>" id="categories_list_search">
			</form>
		</li>
        <li><a href="index.php?a=120"><i class="<?php echo $_style["actions_categories"] ?>" aria-hidden="true"></i><span><?php echo $_lang['manage_categories']; ?></span></a></li>
        <li><a href="#" id="category-help"><i class="<?php echo $_style["actions_help"] ?>" aria-hidden="true"></i><span><?php echo $_lang['help']; ?></span></a></li>
		<?php echo renderViewSwitchButtons('categories_list'); ?>
	</ul>

	<?php echo createCombinedView($resources); ?>
	
	<script>
        initQuicksearch('categories_list_search', 'categories_list');
        initViews('cat', 'category');
	</script>
</div>