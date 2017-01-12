/**
 * MODX startup - Bootstrap
 *
 * Sample template in Bootstrap
 *
 * @category	template
 * @version 	1.0
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@lock_template 0
 * @internal 	@modx_category Demo Content
 * @internal    @installset sample
 * @internal	@save_sql_id_as BOOTSTRAP_SQL_ID
 */
<!DOCTYPE html>
<html lang="[(lang_code)]">
<head>	
	<base href="[(site_url)]" />
	<meta charset="[(modx_charset)]" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>[*pagetitle*] / [(site_name)]</title>
	<@IF:[*description:isntEmpty*]><meta name="description" content="[*description*]"><@ENDIF>
	
	<link href="[(site_url)]<@IF:[*id:isnt(1)*]>[~[*id*]~]<@ENDIF>" rel="canonical">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css">	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	
	<style> 
	html, body{background:#eee; font-family:'Open Sans',sans-serif; line-height:1.8; font-size:14px;}
	a:focus{outline:none; outline-offset:0;}
	h1{margin-top:15px;}

	.logo{float:left;}
	.logo img{max-width:200px; margin:10px 0; display:block; height:50px; width:auto;}
	.dropdown-menu{border-radius:0; border:0;}
	.dropdown-menu > li > a{padding-top:5px; padding-bottom:5px;}

	.navbar-collapse.collapse.in{border-bottom:10px solid #eee;}
	.navbar{min-height:0; background:#fff; margin-bottom:0;}
	.navbar.nav{margin-left:0;}
	.navbar.nav ul{padding-left:0;}
	.navbar-nav{margin:0;}
	.navbar-toggle{background:#fff; border:2px solid #eee; border-radius:0; position:fixed; z-index:99; right:0; top:7px; padding:12px 10px; margin-right:10px;}
	.navbar .navbar-toggle .icon-bar{background-color:#333;}

	.nav li a{text-transform:uppercase; color:#333; font-weight:500; font-size:110%;}
	.nav li li a{text-transform:none; font-weight:normal; font-size:100%;}

	.navbar{border:none; border-radius:0;}
	#navbar{padding:0;}
	ul.nav > li > a:hover{background-color:#f5f5f5;}

	.affix{top:0px; width:100%; z-index:1000; background-color:#eee;}
	.affix + .affspacer{display:block; height:50px;}

	.box-shadow{-webkit-box-shadow:0 6px 12px rgba(0,0,0,.175); box-shadow:0 6px 12px rgba(0,0,0,.175);}

	.container {max-width:970px; margin:0 12px;}
	.top .col-sm-12{padding-left:0; padding-right:0;}

	#ajaxSearch_input,
	#username,
	#password{width:100%!important;}
	#forgotpsswd{clear:both;}
	input.button[type="submit"]{display:block;}
	label.checkbox{display:inline-block; margin-left:10px;}
	label, legend{font-weight:400;}
	#ajaxSearch_form { position:relative; }
	#searchClose { display:none !important; }
    #indicator { position:absolute; top:9px; right:12px; z-index:10; opacity:.75; }

	h2{font-size:22px;}
	.bread{padding:1em 0 0 0;}
	.mem{color:#aaa; text-align:center; padding:1em 0 2em;}

	section.main .container{background-color:#fff; padding-bottom:20px;}
	footer.footer .container{background-color:#000; color:#fff; line-height:40px;}

	section.main ul{list-style:none; margin:0 0 1em 0; padding:0;}
	section.main ul li{padding-left:1em;}
	section.main ul li:before{content:'\2022'; position:absolute; line-height:1.85em; margin-left:-1em;}

	.footer{text-align:center;}
	.footer .text-right{text-align:center;}

	/* JOT */
	.jot-comment{padding:5px 10px;}
	.jot-row-author{background-color:#dddddd;}
	.jot-row-alt{background-color:#f9f9f9;}
	.jot-row{background-color:#eeeeee;}
	.jot-row-up{border:1px solid #333!important;}
	.jot-row-up.panel-primary > .panel-heading{background-color:#333!important; border-color:#333!important;}
	.jot-extra{font-size:75%;}
	.jot-poster{font-size:inherit!important;}

	.ditto_summaryPost img{max-width:100%; height:auto; margin:10px 0 5px; display:block;}
	.ditto_summaryPost{padding-top:10px; padding-bottom:15px; border-bottom:1px solid #eee;}

	/* Larger than mobile */
	@media (min-width:320px) {

	}

	/* Larger than phablet */
	@media (min-width:480px) {

	}

	/* Larger than tablet */
	@media (min-width:768px) {

		.container{margin:0 auto;}
		.logo{padding-left:15px;}
		.logo img{ max-width:240px; margin:0; display:block; height:100px;}

		.navbar{background:transparent;}
		.navbar.affix{background:#eee;}
		.navbar-collapse.collapse.in{border-bottom:0;}

		.footer{text-align:left;}
		.footer .text-right{text-align:right;}

		.col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {padding-left:35px; padding-right:35px;}

	}
	</style>
	
	<script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
</head>
<body>
	<section class="top">
		<div class="container">
			<div class="row">
				<div class="col-sm-12" itemscope itemtype="http://schema.org/Organization">

					<a class="logo" href="[~[(site_start)]~]" title="[(site_name)]" itemprop="url">
						<img src="[(site_url)]assets/images/modx-logo.png" itemprop="logo" width="240" height="100" alt="[(site_name)]" />
					</a>

					<div class="clearfix"></div>

					<nav class="navbar" role="navigation" data-spy="affix" data-offset-top="100">

						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div id="navbar" class="navbar-collapse collapse">
							[[Wayfinder? 
							&startId=`0` 
							&level=`2` 
							&removeNewLines=`1`
							&outerTpl=`@CODE:<ul class="nav navbar-nav">[+wf.wrapper+]</ul>`
							&rowTpl=`@CODE:<li[+wf.classes+]><a href="[+wf.link+]" [+wf.attributes+]>[+wf.linktext+]</a>[+wf.wrapper+]</li>`
							&innerTpl=`@CODE:<ul class="dropdown-menu">[+wf.wrapper+]</ul>`
							&innerRowTpl=`@CODE:<li[+wf.classes+]><a href="[+wf.link+]">[+wf.linktext+]</a></li>`
							&parentRowTpl=`@CODE:<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" title="[+wf.title+]">[+wf.linktext+] <b class="caret"></b></a>[+wf.wrapper+]</li>`
							&activeParentRowTpl=`@CODE:<li class="dropdown active"><a class="dropdown-toggle" data-toggle="dropdown" href="#" title="[+wf.title+]">[+wf.linktext+] <b class="caret"></b></a>[+wf.wrapper+]</li>`
							]]
						</div>

					</nav>
					<div class="affspacer"></div>

				</div>
			</div>
		</div>
		</section>

		<section class="main">
			<div class="container">

				<div class="row">
					<div class="col-sm-12">
						<div class="bread">
							[[Breadcrumbs]]
						</div>
					</div>
				</div>

				<div class="row">

					<div class="col-sm-8">
						<h1>[*#longtitle*]</h1>
						[*#content*]
					</div>

					<aside class="col-sm-4">
						<div class="search">
							<h2>Search</h2>
							[!AjaxSearch? 
							&ajaxSearch=`1` 
							&addJscript=`0` 
							&showIntro=`0` 
							&ajaxMax=`5` 
							&extract=`1`
							&jscript=`jquery`
							&tplInput=`AjaxSearch_tplInput`
							&tplAjaxGrpResult=`AjaxSearch_tplAjaxGrpResult`
							&tplAjaxResults=`AjaxSearch_tplAjaxResults`
							&tplAjaxResult=`AjaxSearch_tplAjaxResult`
							&showResults=`1`
							&liveSearch=`0`
							!]
						</div>

						<h2>News:</h2>
						[[DocLister? 
						&parents=`2` 
						&display=`2`
						&total=`20` 
						&removeChunk=`Comments` 
						&tpl=`nl_sidebar`
						]]

						<div>
							<h2>Most Recent:</h2>

							<ul>
								[[DocLister? 
								&showInMenuOnly=`1` 
								&parents=`0`
								&display=`5`
								&tpl=`@CODE:<li><a href="[+url+]" title="[+pagetitle+]">[+pagetitle+]</a> <span class="date">[+date+]</span></li>`
								]]
							</ul>

						</div>
						<br/>
						<h2>Login:</h2>
						<div>
							[!WebLogin? 
							&tpl=`WebLogin_tplForm` 
							&loginhomeid=`[(site_start)]`
							&focusInput=`0`
							!]
						</div>

					</aside>
				</div>
			</div>

		</section>

		<footer class="footer">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<a href="https://modx.com" title="Learn more about MODX">MODX</a> Starter-Template &copy;2006-[[$_SERVER['REQUEST_TIME']:dateFormat=`Y` ]]
					</div>
					<div class="col-sm-6 text-right">
						Built with <a href="http://www.getbootstrap.com" target="_blank">Bootstrap</a> framework.
					</div>
				</div>
			</div>
		</footer>

		<div class="container mem">
			<small>Memory: [^m^], MySQL: [^qt^], [^q^] request(s), PHP: [^p^], total: [^t^], document retrieved from [^s^]. </small>
	</div>

	<!-- Scripts
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->	

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	
</body>
</html>