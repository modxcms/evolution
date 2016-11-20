<link rel="stylesheet" type="text/css" href="media/style/common/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="media/script/gridster/jquery.gridster.css" />
<link rel="stylesheet" href="media/style/[+theme+]/dashboard/css/dashboard.css" />
<link rel="stylesheet" href="media/style/common/font-awesome/css/font-awesome.min.css" />

<div class="container-fluid dashboard">

  <!-- title-->
  [+OnManagerWelcomePrerender+]
  <!--div class="dashboard_header">
    <div class="row">
      <div class="col-sm-12">
        <div class="wm_logo">
          <img src='media/style/[+theme+]/images/misc/logo-dashboard.png' alt='[+logo_slogan+]' />
        </div>
        <h1>[+site_name+]</h1>
      </div>
    </div>
  </div-->
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
      [+OnManagerWelcomeHome+]
      <!---Welcome Logo and buttons---> 
      <!--- panel -->
      <li id="modxwelcome_widget" data-row="1" data-col="1" data-sizex="2" data-sizey="6">
        <div class="panel panel-default widget-wrapper">
          <div class="panel-headingx widget-title sectionHeader clearfix">
            <span class="panel-handel pull-left"><i class="fa fa-home"></i> [+welcome_title+]</span>
            <div class="widget-controls pull-right">
              <div class="btn-group">
                <a href="#" class="btn btn-default btn-xs panel-hide hide-full fa fa-minus" data-id="modxwelcome_widget"></a>
              </div>    
            </div>
          </div>
          <div class="panel-body widget-stage sectionBody">
            <div class="wm_buttons">
              [+SecurityIcon+]
              [+WebUserIcon+]
              [+ModulesIcon+]
              [+ResourcesIcon+]
              [+BackupIcon+]
              [+HelpIcon+]
            </div>
            <div class="userprofiletable">
              [+UserInfo+]
            </div>
          </div>
        </div>      
      </li>
      <!--- /panel --->

      <!---User Info--->
      <!--- panel --->
      <li id="modxonline_widget" data-row="2" data-col="2" data-sizex="2" data-sizey="6">
        <div class="panel panel-default widget-wrapper">
          <div class="panel-headingx widget-title sectionHeader clearfix">
            <span class="panel-handel pull-left"><i class="fa fa-user"></i> [+onlineusers_title+]</span>
            <div class="widget-controls pull-right">
              <div class="btn-group">
                <a href="#" class="btn btn-default btn-xs panel-hide hide-full fa fa-minus" data-id="modxonline_widget"></a>
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
            <span class="panel-handel pull-left"><i class="fa fa-pencil-square-o"></i> [+activity_title+]</span>
            <div class="widget-controls pull-right">
              <div class="btn-group">
                <a href="#" class="btn btn-default btn-xs panel-hide hide-full fa fa-minus" data-id="modxrecent_widget"></a>
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
            <span class="panel-handel pull-left"><i class="fa fa-rss"></i> [+modx_news_title+]</span>
            <div class="widget-controls pull-right">
              <div class="btn-group">
                <a href="#" class="btn btn-default btn-xs panel-hide hide-full fa fa-minus" data-id="modxnews_widget"></a>
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
      <li id="modxsecurity_widget" data-row="4" data-col="2" data-sizex="2" data-sizey="5">
        <div class="panel panel-default widget-wrapper">
          <div class="panel-headingx widget-title sectionHeader clearfix">
            <span class="panel-handel pull-left"><i class="fa fa-exclamation-triangle"></i> [+modx_security_notices_title+]</span>
            <div class="widget-controls pull-right">
              <div class="btn-group">
                <a href="#" class="btn btn-default btn-xs panel-hide hide-full fa fa-minus" data-id="modxsecurity_widget"></a>
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

  [+OnManagerWelcomeRender+] 
  <div class="container-fluid">
    <p class="text-muted pull-right">
      <a class="btn btn-sm btn-default" onclick="cleanLocalStorage('[+site_name+]-evodashboard.grid,[+site_name+]-evodashboard.states')"><i class="fa fa-refresh" aria-hidden="true"></i> [+resetgrid+]</a>
    </p>
  </div>

</div>

<script src="media/script/jquery/jquery.min.js"></script>
<script src='media/script/gridster/jquery.gridster.min.js'></script>
<script src='media/style/[+theme+]/dashboard/js/evodashboard.js'></script>
<script src="media/script/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
  //localStorage.clear();

  var localdata_position = JSON.parse(localStorage.getItem('[+site_name+]-evodashboard.grid'));
  var localdata_states = JSON.parse(localStorage.getItem('[+site_name+]-evodashboard.states'));


  fnCreateGridster('[+site_name+]-evodashboard.grid', '[+site_name+]-evodashboard.states');
</script>

<script type="text/javascript">        
  function cleanLocalStorage() {
    for(key in localStorage) {
      delete localStorage[key];
    }
    location.reload();
  }
</script>