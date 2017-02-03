<!-- Templates -->
<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

if(isset($resources->items['site_templates'])) { ?>
	<div class="tab-page" id="tabTemplates">
		<h2 class="tab"><i class="fa fa-newspaper-o"></i> <?php echo $_lang["manage_templates"] ?></h2>
		<script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabTemplates" ) );</script>
		<div id="template-info" class="msg-container" style="display:none">
			<p class="element-edit-message"><?php echo $_lang['template_management_msg']; ?></p>
            <p class="viewoptions-message"><?php echo $_lang['view_options_msg']; ?></p>
		</div>

		<ul class="actionButtons">
			<li>
				<form class="filterElements-form">
					<input class="form-control" type="text" placeholder="<?php echo $_lang['element_filter_msg']; ?>" id="site_templates_search">
				</form>
			</li>
			<li><a href="index.php?a=19"><?php echo $_lang['new_template']; ?></a></li>
			<li><a href="#" id="template-help"><?php echo $_lang['help']; ?></a></li>
			<?php echo renderViewSwitchButtons('site_templates'); ?>
		</ul>

		<?php echo createResourceList('site_templates', $resources); ?>

		<script>
            initQuicksearch('site_templates_search', 'site_templates');
            initViews('tmp', 'template', 'site_templates');
		</script>
	</div>
<?php } ?>