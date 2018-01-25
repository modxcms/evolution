<?php

if(!defined('MODX_BASE_PATH')) die('What are you doing? Get out of here!');

$tabLabel_template  = '<i class="fa fa-newspaper-o"></i>';
$tabLabel_tv        = '<i class="fa fa-list-alt"></i>';
$tabLabel_chunk     = '<i class="fa fa-th-large"></i>';
$tabLabel_snippet   = '<i class="fa fa-code"></i>';
$tabLabel_plugin    = '<i class="fa fa-plug"></i>';
$tabLabel_module    = '<i class="fa fa-cubes"></i>';
$tabLabel_create    = '<i class="fa fa-plus"></i>';
$tabLabel_refresh   = '<i class="fa fa-refresh"></i>';

$text_reload_title = 'Click here to reload elements list.';

$templates = createElementsList('site_templates',16,'templatename');
$tmplvars  = createElementsList('site_tmplvars',301);
$chunk     = createElementsList('site_htmlsnippets',78);
$snippet   = createElementsList('site_snippets',22);
$plugin    = createElementsList('site_plugins',102);
$module    = createModulesList(112);

$ph = compact('tabLabel_template','tabLabel_tv','tabLabel_chunk','tabLabel_snippet','tabLabel_plugin','tabLabel_module','tabLabel_create','tabLabel_refresh','text_reload_title','templates','tmplvars','chunk','snippet','plugin','module');

if ( hasAnyPermission() ) $output = '</div>';

$modx->addSnippet('hasPermission','return $modx->hasPermission($permission);');
$modx->addSnippet('hasAnyPermission','return (int)hasAnyPermission();');

$output .= file_get_contents($eit_base_path . 'assets/on_manager_tree_render.tpl');
    
$_ = $modx->config['enable_filter'];
$modx->config['enable_filter'] = 1;
$output = $modx->mergeConditionalTagsContent($output);
$output = $modx->parseText($output,$ph);
$output = $modx->parseText($output,$_lang,'[%','%]');
$modx->config['enable_filter'] = $_;
$e->output($output);
