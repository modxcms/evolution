<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $_lang['language_code']?>" lang="<?php echo $_lang['language_code']?>">
<head>
	<title><?php echo $_lang['modx_install']?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_lang['encoding']?>" />
    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <!--[if lt IE 7]>
        <style type="text/css">
            body { behavior: url(/assets/js/csshover3.htc) }
        </style>
        <script type="text/javascript" src="/assets/js/frankensleight.js"></sript>
    <![endif]-->
</head>

<body<?php echo $modx_textdir ? ' id="rtl"':''?>>
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
