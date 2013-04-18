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
        <script type="text/javascript" src="/assets/js/frankensleight.js"></script>
    <![endif]-->
</head>

<body<?php echo $modx_textdir ? ' id="rtl"':''?>>
<!-- start install screen-->
<div id="header">
    <div class="container_12">
        <span class="help"><a href="<?php echo $_lang["help_link"] ?>" target="_blank" title="<?php echo $_lang["help_title"] ?>"><?php echo $_lang["help"] ?></a></span>
		<span class="version"><?php echo $moduleName.' '.$moduleVersion.' ('.($modx_textdir?'&rlm;':'').$modx_release_date?>)</span>
        <div id="mainheader">
        	<h1 class="pngfix" id="logo"><span>MODX CMS</span></h1>
        </div>
    </div>
</div>
<!-- end header -->

<div id="contentarea">
    <div class="container_12">        
        <!-- start content -->
