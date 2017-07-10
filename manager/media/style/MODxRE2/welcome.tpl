<!--<link rel="stylesheet" type="text/css" href="media/style/common/bootstrap/css/bootstrap.min.css" />-->
<link rel="stylesheet" href="media/script/gridster/jquery.gridster.css" />
<link rel="stylesheet" href="media/style/[(manager_theme)]/dashboard/css/dashboard.css" />
<!--<link rel="stylesheet" href="media/style/common/font-awesome/css/font-awesome.min.css" />-->

<div class="container-fluid dashboard">

	<!-- title-->
	[[@OnManagerWelcomePrerender]]
	<!--div class="dashboard_header">
	  <div class="row">
		<div class="col-sm-12">
		  <div class="wm_logo">
			<img src="media/style/[(manager_theme)]/images/misc/logo-dashboard.png" alt="[%logo_slogan%]" />
		  </div>
		  <h1>[(site_name)]</h1>
		</div>
	  </div>
	</div-->

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

</div>

<!-- end  title -->
<div class="dashboard">
	<!-- GridSter widgets -->
	<div class="gridster">
		<ul>
			[[@OnManagerWelcomeHome]]
			<!---Welcome Logo and buttons--->
			<!--- panel -->
			<li id="modxwelcome_widget" data-row="1" data-col="1" data-sizex="2" data-sizey="6">
				<div class="panel panel-default widget-wrapper">
					<div class="panel-headingx widget-title sectionHeader clearfix">
						<span class="panel-handel pull-left"><i class="fa fa-home"></i> [%welcome_title%]</span>
						<div class="widget-controls pull-right">
							<div class="btn-group">
								<a href="javascript:;" class="btn btn-default btn-xs panel-hide hide-full fa fa-minus" data-id="modxwelcome_widget"></a>
							</div>
						</div>
					</div>
					<div class="panel-body widget-stage sectionBody">
						<div class="wm_buttons">
							<!--@IF:[[#hasPermission?key=new_user]] OR [[#hasPermission?key=edit_user]]-->
							<span class="wm_button" style="border:0">
								<a class="hometblink" href="index.php?a=75"><i class="[&icons_security_large&]" alt="[%user_management_title%]"> </i><br />
								[%security%]</a>
							</span>
							<!--@ENDIF-->
							<!--@IF:[[#hasPermission?key=new_web_user]] OR [[#hasPermission?key=edit_web_user]]-->
							<span class="wm_button" style="border:0">
								<a class="hometblink" href="index.php?a=99"><i class="[&icons_webusers_large&]" alt="[%web_user_management_title%]"> </i><br />
								[%web_users%]</a>
							</span>
							<!--@ENDIF-->
							<!--@IF:[[#hasPermission?key=new_module]] OR [[#hasPermission?key=edit_module]]-->
							<span class="wm_button" style="border:0">
								<a class="hometblink" href="index.php?a=106"><i class="[&icons_modules_large&]" alt="[%manage_modules%]"> </i><br />
								[%modules%]</a>
							</span>
							<!--@ENDIF-->
							<!--@IF:[[#hasAnyPermission:is(1)]] -->
							<span class="wm_button" style="border:0">
								<a class="hometblink" href="index.php?a=76"><i class="[&icons_resources_large&]" alt="[%element_management%]"> </i><br />
								[%elements%]</a>
							</span>
							<!--@ENDIF-->
							<!--@IF:[[#hasPermission?key=bk_manager]]-->
							<span class="wm_button" style="border:0">
								<a class="hometblink" href="index.php?a=93"><i class="[&icons_backup_large&]" alt="[%bk_manager%]"> </i><br />
								[%backup%]</a>
							</span>
							<!--@ENDIF-->
							<!--@IF:[[#hasPermission?key=help]] OR [[#hasPermission?key=edit_module]]-->
							<span class="wm_button" style="border:0">
								<a class="hometblink" href="index.php?a=9"><i class="[&icons_help_large&]" alt="[%help%]"> </i><br />
								[%help%]</a>
							</span>
							<!--@ENDIF-->
						</div>
						<div class="userprofiletable">
							<table class="table table-hover table-condensed">
								<tr>
									<td width="150">[%yourinfo_username%]</td>
									<td><b>[[#getLoginUserName]]</b></td>
								</tr>
								<tr>
									<td>[%yourinfo_role%]</td>
									<td><b>[[$_SESSION['mgrPermissions']['name'] ]]</b></td>
								</tr>
								<tr>
									<td>[%yourinfo_previous_login%]</td>
									<td><b>[[$_SESSION['mgrLastlogin']:math('%s+[(server_offset_time)]'):dateFormat]]</b></td>
								</tr>
								<tr>
									<td>[%yourinfo_total_logins%]</td>
									<td><b>[[$_SESSION['mgrLogincount']:math('%s+1')]]</b></td>
								</tr>
								<!--@IF:[[#hasPermission?key=messages]]-->
								<tr>
									<td>[%inbox%]</td>
									<td><a href="index.php?a=10"><b>[[#getMessageCount]]</b></a></td>
								</tr>
								<!--@ENDIF-->
							</table>
						</div>
					</div>
				</div>
			</li>
			<!--- /panel --->

			<!---User Info--->
			<!--- panel --->
			<li id="modxonline_widget" data-row="2" data-col="3" data-sizex="2" data-sizey="6">
				<div class="panel panel-default widget-wrapper">
					<div class="panel-headingx widget-title sectionHeader clearfix">
						<span class="panel-handel pull-left"><i class="fa fa-user"></i> [%onlineusers_title%]</span>
						<div class="widget-controls pull-right">
							<div class="btn-group">
								<a href="javascript:;" class="btn btn-default btn-xs panel-hide hide-full fa fa-minus" data-id="modxonline_widget"></a>
							</div>
						</div>
					</div>
					<div class="panel-body widget-stage sectionBody">
						<div class="userstable">
							[+OnlineInfo+]
						</div>
					</div>
				</div>
			</li>
			<!--- /panel --->

			<!---Recent Resources--->
			<!--- panel --->
			<li id="modxrecent_widget" data-row="3" data-col="1" data-sizex="4" data-sizey="7">
				<div class="panel panel-default widget-wrapper">
					<div class="panel-headingx widget-title sectionHeader clearfix">
						<span class="panel-handel pull-left"><i class="fa fa-pencil-square-o"></i> [%activity_title%]</span>
						<div class="widget-controls pull-right">
							<div class="btn-group">
								<a href="javascript:;" class="btn btn-default btn-xs panel-hide hide-full fa fa-minus" data-id="modxrecent_widget"></a>
							</div>
						</div>
					</div>
					<div class="panel-body widget-stage sectionBody">
						[+RecentInfo+]
					</div>
				</div>
			</li>
			<!--- /panel --->

			<!---MODX News--->
			<!--- panel --->
			<li id="modxnews_widget" data-row="4" data-col="1" data-sizex="2" data-sizey="5">
				<div class="panel panel-default widget-wrapper">
					<div class="panel-headingx widget-title sectionHeader clearfix">
						<span class="panel-handel pull-left"><i class="fa fa-rss"></i> [%modx_news_title%]</span>
						<div class="widget-controls pull-right">
							<div class="btn-group">
								<a href="javascript:;" class="btn btn-default btn-xs panel-hide hide-full fa fa-minus" data-id="modxnews_widget"></a>
							</div>
						</div>
					</div>
					<div class="panel-body widget-stage sectionBody">
						[+modx_news_content+]
					</div>
				</div>
			</li>
			<!--- /panel --->

			<!---Security News--->
			<!--- panel --->
			<li id="modxsecurity_widget" data-row="4" data-col="3" data-sizex="2" data-sizey="5">
				<div class="panel panel-default widget-wrapper">
					<div class="panel-headingx widget-title sectionHeader clearfix">
						<span class="panel-handel pull-left"><i class="fa fa-exclamation-triangle"></i> [%security_notices_title%]</span>
						<div class="widget-controls pull-right">
							<div class="btn-group">
								<a href="javascript:;" class="btn btn-default btn-xs panel-hide hide-full fa fa-minus" data-id="modxsecurity_widget"></a>
							</div>
						</div>
					</div>
					<div class="panel-body widget-stage sectionBody">
						[+modx_security_notices_content+]
					</div>
				</div>
			</li>
			<!--- /panel --->
		</ul>
	</div>
	<!-- / GridStack widgets -->

	[[@OnManagerWelcomeRender]]
	<div class="container-fluid">
		<p class="text-muted pull-right">
			<a class="btn btn-sm btn-default" onclick="cleanLocalStorage('[(site_name:encode_js)]-evodashboard.grid,[(site_name:encode_js)]-evodashboard.states')"><i class="fa fa-refresh"></i> [%reset%]</a>
		</p>
	</div>

</div>

<script src="media/script/jquery/jquery.min.js"></script>
<script src="media/script/gridster/jquery.gridster.min.js"></script>
<script src="media/style/[(manager_theme)]/dashboard/js/evodashboard.js"></script>
<script src="media/script/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
	//localStorage.clear();

	var localdata_position = JSON.parse(localStorage.getItem('[(site_name:encode_js)]-evodashboard.grid'));
	var localdata_states = JSON.parse(localStorage.getItem('[(site_name:encode_js)]-evodashboard.states'));

	fnCreateGridster('[(site_name:encode_js)]-evodashboard.grid', '[(site_name:encode_js)]-evodashboard.states');
</script>

<script type="text/javascript">
	function cleanLocalStorage(keys) {
		keys = keys.split(',');
		for(var i = 0; i < keys.length; i++) {
			delete localStorage[keys[i]];
		}
		location.reload();
	}
</script>