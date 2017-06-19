<?php
/**
 * mm_minimizablesections
 * @version 0.2 (2015-05-30)
 *
 * @desc A widget for ManagerManager plugin that allows one, few or all sections to be minimizable on the document edit page.
 *
 * @uses ManagerManager plugin 0.6.2.
 *
 * @param $sections {comma separated string} - The id(s) of the sections this should apply to. Use '*' for apply to all. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates). Default: ''.
 * @param $minimized {comma separated string} - The id(s) of the sections this should be minimized by default.
 *
 * @author Sergey Davydov <webmaster@sdcollection.com>
 *
 * @copyright 2015
 */

function prepareSection($section) {
 switch ($section) {
  case 'access':
   return "#sectionAccessHeader";
   break;
  case '*':
   return ".sectionHeader";
   break;
  default:
   $section = prepareSectionId($section);
   return "#{$section}_header";
   break;
 }
}

function mm_minimizablesections($sections, $roles = '', $templates = '',$minimized = '') {
 if (!useThisRule($roles, $templates)){return;}
 
 global $modx;
 $e = &$modx->Event;
 $site = $modx->config['site_url'];
 $widgetDir = $site.'assets/plugins/managermanager/widgets/mm_minimizablesections/';

 $output='';
 if ($e->name == 'OnDocFormPrerender') {
  $output .= includeJsCss($widgetDir.'minimizablesections.css', 'html');

  $e->output($output);
 } else if ($e->name == 'OnDocFormRender') {
  $sections = makeArray($sections);
  $minimized = makeArray($minimized);

  $sections = array_map("prepareSection",$sections);
  $minimized = array_map("prepareSection",$minimized);

  $output .= "//---------- mm_minimizablesections :: Begin -----\n";
  $output .= '$j("'.implode(",",$sections).'","#documentPane").addClass("minimizable").on("click",function(){
     var _t = $j(this);
     _t.next().slideToggle(400,function(){_t.toggleClass("minimized");})
    });
    $j(".minimizable").filter("'.implode(",",$minimized).'").addClass("minimized").next().hide();
  ';

  $output .= "//---------- mm_minimizablesections :: End -----\n";

  $e->output($output);
 }
}

?>