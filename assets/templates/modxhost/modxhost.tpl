<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>  <title>[(site_name)] | [*pagetitle*]</title>  <meta http-equiv="Content-Type" content="text/html; charset=[(modx_charset)]" />  <base href="[(site_url)]"></base>  <link rel="stylesheet" href="assets/templates/modxhost/layout.css" type="text/css" media="screen" />  <link rel="stylesheet" href="assets/templates/modxhost/modxmenu.css" type="text/css" media="screen" />  <link rel="stylesheet" href="assets/templates/modxhost/form.css" type="text/css" media="screen" />  <link rel="stylesheet" href="assets/templates/modxhost/modx.css" type="text/css" media="screen" />  <link rel="stylesheet" href="assets/templates/modxhost/print.css" type="text/css" media="print" />  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="[(site_url)][~11~]" />  <script src="manager/media/script/mootools/mootools.js" type="text/javascript"></script>
  <script src="assets/templates/modxhost/drop_down_menu.js" type="text/javascript"></script>
</head><body><div id="wrapper">  <div id="minHeight"></div>  <div id="outer">    <div id="inner">      <div id="right">        <div id="right-inner">          <h1 style="text-indent: -5000px;padding: 0px; margin:0px; font-size: 1px;">[(site_name)]</h1>          <div id="sidebar">            <h2>News:</h2>            [[Ditto? &startID=`2` &summarize=`2` &total=`20` &commentschunk=`Comments` &tpl=`nl_sidebar` &showarch=`0` &truncLen=`100` &truncSplit=`0`]]            <div id="recentdocsctnr">              <h2>Most Recent:</h2>              <a name="recentdocs"></a>[[ListIndexer?LIn_root=0]] </div>            <h2>Login:</h2>            <div id="sidebarlogin">[!WebLogin? &tpl=`FormLogin` &loginhomeid=`[(site_start)]`!]</div>            <h2>Meta:</h2>            <p><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></p>            <p><a href="http://jigsaw.w3.org/css-validator/check/referer" title="This page uses valid Cascading Stylesheets" rel="external">Valid <abbr title="W3C Cascading Stylesheets">css</abbr></a></p>            <p><a href="http://modxcms.com" title="Ajax CMS and PHP Application Framework">MODx</a></p>          </div>          <!-- close #sidebar -->        </div>        <!-- end right inner-->      </div>      <!-- end right -->      <div id="left">        <div id="left-inner">          <div id="content">            <div class="post">              <h2>[*longtitle*]</h2>              [*#content*] </div>            <!-- close .post (main column content) -->
          </div>
          <!-- close #content -->
        </div>
        <!-- end left-inner -->
      </div>
      <!-- end left -->
    </div>
    <!-- end inner -->
    <div id="clearfooter"></div>
    <div id="header">
      <h1><a id="logo" href="[~[(site_start)]~]" title="[(site_name)]">[(site_name)]</a></h1>
      <div id="search"><!--search_terms--><span id="search-txt">SEARCH</span><a name="search"></a>[!AjaxSearch? &ajaxSearch=`1` &landingPage=`8` &moreResultsPage=`8` &addJscript=`0` &showIntro=`0` &ajaxMax=`5` &extract=`1`!]</div>
      <div id="ajaxmenu"> [[Wayfinder?startId=`0` &outerTpl=`mh.OuterTpl` &innerTpl=`mh.InnerTpl` &rowTpl=`mh.RowTpl` &innerRowTpl=`mh.InnerRowTpl` &firstClass=`first` &hereClass=``]] </div>
      <!-- end topmenu -->
    </div>
    <!-- end header -->
    <br style="clear:both;height:0;font-size: 1px" />
    <div id="footer">      <p> <a href="http://modxcms.com" title="Ajax CMS and PHP Application Framework">Powered          by MODx</a> &nbsp;<a href="http://www.modxhost.com/" title="Template Designed by modXhost.com">Template &copy; 2006          modXhost.com</a><br />        Memory: [^m^], MySQL: [^qt^], [^q^] request(s), PHP: [^p^], total: [^t^], document retrieved
        from [^s^]. </p>    </div>    <!-- end footer -->  </div>  <!-- end outer div -->
</div>
<!-- end wrapper -->
</body></html>