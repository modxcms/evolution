<!-- snippets -->
<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

if(isset($resources->items['site_snippets'])) { ?>
	<div class="tab-page" id="tabSnippets">
		<h2 class="tab"><i class="fa fa-code"></i> <?php echo $_lang["manage_snippets"] ?></h2>
		<script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabSnippets" ) );</script>
		<div id="snippets-info" style="display:none">
			<p class="element-edit-message"><?php echo $_lang['snippet_management_msg']; ?></p>
		</div>

		<ul class="actionButtons">
			<li>
				<form class="filterElements-form">
					<input class="form-control" type="text" placeholder="<?php echo $_lang['element_filter_msg']; ?>" id="site_snippets_search">
				</form>
			</li>
			<li><a href="index.php?a=23"><?php echo $_lang['new_snippet']; ?></a></li>
			<li><a href="#" id="snippets-help"><?php echo $_lang['help']; ?></a></li>
			<?php echo renderViewSwitchButtons('site_snippets'); ?>
		</ul>

		<?php echo $resources->createResourceList('site_snippets'); ?>

		<script>
            initQuicksearch('site_snippets_search', 'site_snippets');
            initViews('sn', 'snippets', 'site_snippets');
		</script>
	</div>
<?php } ?>