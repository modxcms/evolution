<?php
// For a global parameter initialisation use the following syntax $__param = 'value';
// To overwrite parameter snippet call use $param = 'value';

    function travel(){
    
      $config = array();
      
      // travel search definition
      
      $config['documents'] = '104';  
      $config['whereSearch'] = 'content:alias|jot|maxigallery';
      $config['extract'] = '0';
      $config['breadcrumbs'] = 'Breadcrumbs,showHomeCrumb:0,showCrumbsAtHome:1';
          
      return $config;
    }

    function portraits(){
    
      $config = array();
      
      // portraits search definition
      
      $config['documents'] = '155';
      $config['whereSearch'] = 'content:alias|jot|maxigallery';
      $config['extract'] = '0';
      $config['breadcrumbs'] = 'Breadcrumbs,showHomeCrumb:0,showCrumbsAtHome:1';
    
      return $config;
    }
    
    function travelbooks(){
    
      $config = array();
      
      // travelbook search definition
      
      $config['parents'] = '158';  
      $config['whereSearch'] = 'content:pagetitle,content|tv|jot';
      $config['extract'] = '99:content,jot_content';
      $config['ajaxMax'] = '3';
      $config['breadcrumbs'] = 'Breadcrumbs,showHomeCrumb:0,showCrumbsAtHome:1';
          
      return $config;
    }

    function unesco(){
    
      $config = array();
      
      // unesco search definition
      
      $config['parents'] = '61';
      $config['whereSearch'] = 'content:pagetitle,content|tv';
      $config['withTvs'] = '+:asTag1';
      $config['breadcrumbs'] = 'Breadcrumbs,showHomeCrumb:0,showCrumbsAtHome:1';
    
      return $config;
    }

?>