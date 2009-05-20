<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $_lang['language_code']?>" lang="<?php echo $_lang['language_code']?>">
<head>
	<title><?php echo $_lang['modx_install']?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_lang['encoding']?>" />
    <style type="text/css">
         @import url("style.css");
    </style>
    <style type="text/css">
        body {
            background: #F4F4F4 none repeat scroll 0 0;
        }
        #content ul li { background: none; list-style: none; }
        #header {
            margin-bottom:25px;
            padding-top:16px;
        }
        #mainheader {
            height:116px;
            margin-top:25px;
        }
        #downloadbar {
            height:93px;
        }
        #downloadbar ul {
            margin:0 0 1.5em 15px;
        }
        #downloadbar li {
            list-style-type:none;
            margin:0;
        }
        #downloadinfo {
            background:none;
            padding-top:0px;
        }
        tbody td {
            text-align:left;
        }
        .buttonlinks {
            float:right;
        }
        .labelHolder label {
            width:200px;
            float:left;
        }
        p.actions a {
            margin: 0 3px;
            padding: 1px 4px 2px;
            border: 1px solid #777;
            border-top-color: #bbb;
            border-left-color: #bbb;
            background: #e4e4e4;
            display: block;
            float: left;
            color: #222;
        }
        p.actions a:hover {
            margin: 1px 2px -1px 4px;
            border: 1px solid #bbb;
            border-top-color: #777;
            border-left-color: #777;
            text-decoration: none;
        }
        p.actions {
            line-height: 1;
            margin-top: 3px;
        }
        p.actions a:active {
            background-color: #ccc;
        }
        img.options {
            float:left;
            margin-right:5px;
        }
        span.ok {
            font-weight:bold;
            color:green;
        }
        span.notok {
            font-weight:bold;
            color:red;
        }
        #footer-inner {
            padding:15px 0 15px;
        }
    </style>
    <!--[if lt IE 7]>
    <script src="DD_belatedPNG-min.js"></script>
    <script>
        DD_belatedPNG.fix('.pngfix');
    </script>
    <![endif]-->
</head>

<body>
<!-- start install screen-->
<div id="header">
    <div class="container_12">
        <span style="color:#fff;"><?php echo $moduleName; ?> <?php echo $moduleVersion; ?> (<?php echo $moduleRelease;?>)</span>
        <div id="mainheader">
            <div id="downloadbar" class="pngfix installbar">
                <span id="downloadarrow" title="<?php echo $_lang["install_update"]; ?>"></span>
                <ul style="margin-left:287px;">
                    <li id="downloadinfo"> <?php echo $_lang["install_update"]; ?></li>
                    <li id="downloaddate"> </li>
                </ul>
            </div>

        </div>
    </div>
</div>
<!-- end header -->

<div id="contentarea">
    <div class="container_12">        
        <!-- start content -->
