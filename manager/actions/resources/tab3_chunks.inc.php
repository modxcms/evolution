<!-- chunks -->
<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

if(isset($resources->items['site_htmlsnippets'])) { ?>
	<div class="tab-page" id="tabChunks">
		<h2 class="tab"><i class="fa fa-th-large"></i> <?php echo $_lang["manage_htmlsnippets"] ?></h2>
		<script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabChunks" ) );</script>
		<div id="chunks-info" class="msg-container" style="display:none">
			<p class="element-edit-message"><?php echo $_lang['htmlsnippet_management_msg']; ?></p>
            <p class="viewoptions-message"><?php echo $_lang['view_options_msg']; ?></p>
		</div>

		<ul class="actionButtons">
			<li>
				<form class="filterElements-form">
					<input class="form-control" type="text" placeholder="<?php echo $_lang['element_filter_msg']; ?>" id="site_htmlsnippets_search">
				</form>
			</li>
			<li><a href="index.php?a=77"><?php echo $_lang['new_htmlsnippet']; ?></a></li>
			<li><a href="#" id="chunks-help"><?php echo $_lang['help']; ?></a></li>
			<?php echo renderViewSwitchButtons('site_htmlsnippets'); ?>
		</ul>

		<?php echo createResourceList('site_htmlsnippets', $resources); ?>

		<script>
            initQuicksearch('site_htmlsnippets_search', 'site_htmlsnippets');
            initViews('ch', 'chunks', 'site_htmlsnippets');
		</script>
	</div>
<?php } ?>