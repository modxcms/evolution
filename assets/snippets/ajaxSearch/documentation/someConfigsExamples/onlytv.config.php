<?php

function saveHtml($results){ 

  // replace line Breaking by space
  $results = stripLineBreaking($results);
  // strip javascript tags
  $results = stripJscripts($results);
  
  return $results;
}

function owntablesWordList(){ 

  $list = "GPS,phone,Nokia,HTC,i-mate,Palm,Iphone,Blackberry";
  return $list;
}

function products(& $main,& $joined, $listIDs){

  $main = array(
      'tb_name' => "ext_products",
      'tb_alias' => 'pdt',
      'id' => 'id',
      'searcheable' => array('name','image','description'),
      'date' =>array(),
      'filters' => array(),
      'jfilters'  => array()
    );
    
  $main['filters'][]= array(
      'field' => 'available',
      'oper' => '=',
      'value' => '1' 
  );
  
  $main['filters'][]= array(
      'field' => 'id',
      'oper' => 'in',
      'value' => $listIDs
  );
   
    $joined = NULL;
}

?>