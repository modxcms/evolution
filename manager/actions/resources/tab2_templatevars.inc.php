<!-- Template variables -->
<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

if(isset($resources->items['site_tmplvars'])) { ?>
	<div class="tab-page" id="tabVariables">
		<h2 class="tab"><i class="fa fa-list-alt"></i> <?php echo $_lang["tmplvars"] ?></h2>
		<script type="text/javascript">tpResources.addTabPage( document.getElementById( "tabVariables" ) );</script>
		<!--//
			Modified By Raymond for Template Variables
			Added by Apodigm 09-06-2004- DocVars - web@apodigm.com
		-->
		<div id="tv-info" class="msg-container" style="display:none">
			<p class="element-edit-message"><?php echo $_lang['tmplvars_management_msg']; ?></p>
            <p class="viewoptions-message"><?php echo $_lang['view_options_msg']; ?></p>
		</div>

		<ul class="actionButtons">
			<li>
				<form class="filterElements-form">
					<input class="form-control" type="text" placeholder="<?php echo $_lang['element_filter_msg']; ?>" id="site_tmplvars_search">
				</form>
			</li>
            <li><a href="index.php?a=300"><i class="<?php echo $_style["actions_new"] ?>" aria-hidden="true"></i><span><?php echo $_lang['new_tmplvars']; ?></span></a></li>
            <li><a href="index.php?a=305"><i class="<?php echo $_style["actions_sort"] ?>" aria-hidden="true"></i><span><?php echo $_lang['template_tv_edit']; ?></span></a></li>
            <li><a href="#" id="tv-help"><i class="<?php echo $_style["actions_help"] ?>" aria-hidden="true"></i><span><?php echo $_lang['help']; ?></span></a></li>
			<?php echo renderViewSwitchButtons('site_tmplvars'); ?>
		</ul>

		<?php echo createResourceList('site_tmplvars', $resources); ?>

		<script>
            initQuicksearch('site_tmplvars_search', 'site_tmplvars');
            initViews('tv', 'tv', 'site_tmplvars');
		</script>
	</div>
<?php } ?>