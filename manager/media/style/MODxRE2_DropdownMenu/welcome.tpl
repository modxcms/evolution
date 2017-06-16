<!--link rel="stylesheet" href="media/script/gridster/jquery.gridster.css" />
<link rel="stylesheet" href="media/style/[(manager_theme)]/dashboard/css/dashboard.css" /-->
[[@OnManagerWelcomePrerender]]
<div class="container-fluid pt-2">
	<!-- logout reminder -->
	<div id="logout_reminder" style="display:[+show_logout_reminder+]">
		<div class="widget-wrapper alert alert-warning">
			[+logout_reminder_msg+]
		</div>
	</div>
	<!-- logout reminder -->
	<div id="multiple_sessions" style="display:[+show_multiple_sessions+]">
		<div class="widget-wrapper alert alert-warning">
			[+multiple_sessions_msg+]
		</div>
	</div>

	<!-- alert -->
	<div style="display:[+config_display+]">
		<div class="widget-wrapper alert alert-warning">
			[+config_check_results+]
		</div>
	</div>

<!-- end  title -->
	<div class="row mr-0">
		[+widgets+]
	</div>
	<!--a class="btn btn-secondary mb-1"><i class="fa fa-cogs"></i> Добавить виджет</a-->
</div>

[[@OnManagerWelcomeRender]]