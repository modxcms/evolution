//<?php
/**
 * Reflect
 * 
 * Generates date-based archives using Ditto
 *
 * @category 	snippet
 * @version 	2.1.0
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties
 * @internal	@modx_category Content
 * @internal    @installset base, sample
 */


/*
 * Author: 
 *      Mark Kaplan for MODX CMF
 * 
 * Note: 
 *      If Reflect is not retrieving its own documents, make sure that the
 *          Ditto call feeding it has all of the fields in it that you plan on
 *       calling in your Reflect template. Furthermore, Reflect will ONLY
 *          show what is currently in the Ditto result set.
 *       Thus, if pagination is on it will ONLY show that page's items.
*/
 

// ---------------------------------------------------
//  Includes
// ---------------------------------------------------

$reflect_base = isset($reflect_base) ? $modx->config['base_path'].$reflect_base : $modx->config['base_path']."assets/snippets/reflect/";
/*
    Param: ditto_base
    
    Purpose:
    Location of Ditto files

    Options:
    Any valid folder location containing the Ditto source code with a trailing slash

    Default:
    [(base_path)]assets/snippets/ditto/
*/

$config = (isset($config)) ? $config : "default";
/*
    Param: config

    Purpose:
    Load a custom configuration

    Options:
    "default" - default blank config file
    CONFIG_NAME - Other configs installed in the configs folder or in any folder within the MODX base path via @FILE

    Default:
    "default"
    
    Related:
    - <extenders>
*/

require($reflect_base."configs/default.config.php");
require($reflect_base."default.templates.php");
if ($config != "default") {
    require((substr($config, 0, 5) != "@FILE") ? $reflect_base."configs/$config.config.php" : $modx->config['base_path'].trim(substr($config, 5)));
}

// ---------------------------------------------------
//  Parameters
// ---------------------------------------------------

$id = isset($id) ? $id."_" : false;
/*
    Param: id

    Purpose:
    Unique ID for this Ditto instance for connection with other scripts (like Reflect) and unique URL parameters

    Options:
    Any valid folder location containing the Ditto source code with a trailing slash

    Default:
    "" - blank
*/
$getDocuments = isset($getDocuments) ? $getDocuments : 0;
/*
    Param: getDocuments

    Purpose:
    Force Reflect to get documents

    Options:
    0 - off
    1 - on
    
    Default:
    0 - off
*/
$showItems = isset($showItems) ? $showItems : 1;
/*
    Param: showItems

    Purpose:
    Show individual items in the archive

    Options:
    0 - off
    1 - on
    
    Default:
    1 - on
*/
$groupByYears = isset($groupByYears)? $groupByYears : 1;
/*
    Param: groupByYears

    Purpose:
    Group the archive by years

    Options:
    0 - off
    1 - on
    
    Default:
    1 - on
*/
$targetID = isset($targetID) ? $targetID : $modx->documentObject['id'];
/*
    Param: targetID

    Purpose:
    ID for archive links to point to

    Options:
    Any MODX document with a Ditto call setup with extenders=`dateFilter`
    
    Default:
    Current MODX Document
*/
$dateSource = isset($dateSource) ? $dateSource : "createdon";
/*
    Param: dateSource

    Purpose:
    Date source to display for archive items

    Options:
    # - Any UNIX timestamp from MODX fields or TVs such as createdon, pub_date, or editedon
    
    Default:
    "createdon"
    
    Related:
    - <dateFormat>
*/
$dateFormat = isset($dateFormat) ? $dateFormat : "%d-%b-%y %H:%M";  
/*
    Param: dateFormat

    Purpose:
    Format the [+date+] placeholder in human readable form

    Options:
    Any PHP valid strftime option

    Default:
    "%d-%b-%y %H:%M"
    
    Related:
    - <dateSource>
*/
$yearSortDir = isset($yearSortDir) ? $yearSortDir : "DESC";
/*
    Param: yearSortDir

    Purpose:
    Direction to sort documents

    Options:
    ASC - ascending
    DESC - descending

    Default:
    "DESC"
    
    Related:
    - <monthSortDir>
*/
$monthSortDir = isset($monthSortDir) ? $monthSortDir : "ASC";
/*
    Param: monthSortDir

    Purpose:
    Direction to sort the months

    Options:
    ASC - ascending
    DESC - descending

    Default:
    "ASC"
    
    Related:
    - <yearSortDir>
*/
$start = isset($start)? intval($start) : 0;
/*
    Param: start

    Purpose:
    Number of documents to skip in the results
    
    Options:
    Any number

    Default:
    0
*/  
$phx = (isset($phx))? $phx : 1;
/*
    Param: phx

    Purpose:
    Use PHx formatting

    Options:
    0 - off
    1 - on
    
    Default:
    1 - on
*/

// ---------------------------------------------------
//  Initialize Ditto
// ---------------------------------------------------
$placeholder = ($id != false && $getDocuments == 0) ? true : false;
if ($placeholder === false) {
    $rID = "reflect_".rand(1,1000);
    $itemTemplate = isset($tplItem) ? $tplItem: "@CODE:".$defaultTemplates['item'];
    $dParams = array(
        "id" => "$rID",
        "save" => "3",  
        "summarize" => "all",
        "tpl" => $itemTemplate,
    );
    
    $source = $dittoSnippetName;
    $params = $dittoSnippetParameters;
        // TODO: Remove after 3.0
        
    if (isset($params)) {
        $givenParams = explode("|",$params);
        foreach ($givenParams as $parameter) {
            $p = explode(":",$parameter);
            $dParams[$p[0]] = $p[1];
        }
    }
    /*
        Param: params

        Purpose:
        Pass parameters to the Ditto instance used to retreive the documents

        Options:
        Any valid ditto parameters in the format name:value 
        with multiple parameters separated by a pipe (|)
        
        Note:
        This parameter is only needed for config, start, and phx as you can
        now simply use the parameter as if Reflect was Ditto

        Default:
        [NULL]
    */
    
    $reflectParameters = array('reflect_base','config','id','getDocuments','showItems','groupByYears','targetID','yearSortDir','monthSortDir','start','phx','tplContainer','tplYear','tplMonth','tplMonthInner','tplItem','save');
    $params =& $modx->event->params;
    if(is_array($params)) {
        foreach ($params as $param=>$value) {
            if (!in_array($param,$reflectParameters) && substr($param,-3) != 'tpl') {
                $dParams[$param] = $value;
            }
        }
    }

    $source = isset($source) ? $source : "Ditto";
    /*
        Param: source

        Purpose:
        Name of the Ditto snippet to use

        Options:
        Any valid snippet name

        Default:
        "Ditto"
    */
    $snippetOutput = $modx->runSnippet($source,$dParams);
    $ditto = $modx->getPlaceholder($rID."_ditto_object");
    $resource = $modx->getPlaceholder($rID."_ditto_resource");
} else {
    $ditto = $modx->getPlaceholder($id."ditto_object");
    $resource = $modx->getPlaceholder($id."ditto_resource");
}
if (!is_object($ditto) || !isset($ditto) || !isset($resource)) {
    return !empty($snippetOutput) ? $snippetOutput : "The Ditto object is invalid. Please check it.";
}

// ---------------------------------------------------
//  Templates
// ---------------------------------------------------

$templates['tpl'] = isset($tplContainer) ? $ditto->template->fetch($tplContainer): $defaultTemplates['tpl'];
/*
    Param: tplContainer

    Purpose:
    Container template for the archive

    Options:
    - Any valid chunk name
    - Code via @CODE:
    - File via @FILE:

    Default:
    See default.tempates.php
*/
$templates['year'] = isset($tplYear) ? $ditto->template->fetch($tplYear): $defaultTemplates['year'];
/*
    Param: tplYear

    Purpose:
    Template for the year item

    Options:
    - Any valid chunk name
    - Code via @CODE:
    - File via @FILE:

    Default:
    See default.tempates.php
*/
$templates['year_inner'] = isset($tplYearInner) ? $ditto->template->fetch($tplYearInner): $defaultTemplates['year_inner'];
/*
    Param: tplYearInner

    Purpose:
    Template for the year item (the ul to hold the year template)

    Options:
    - Any valid chunk name
    - Code via @CODE:
    - File via @FILE:

    Default:
    See default.tempates.php
*/
$templates['month'] = isset($tplMonth) ? $ditto->template->fetch($tplMonth): $defaultTemplates['month'];
/*
    Param: tplMonth

    Purpose:
    Template for the month item

    Options:
    - Any valid chunk name
    - Code via @CODE:
    - File via @FILE:

    Default:
    See default.tempates.php
*/
$templates['month_inner'] = isset($tplMonthInner) ? $ditto->template->fetch($tplMonthInner): $defaultTemplates['month_inner'];
/*
    Param: tplMonthInner

    Purpose:
    Template for the month item  (the ul to hold the month template)

    Options:
    - Any valid chunk name
    - Code via @CODE:
    - File via @FILE:

    Default:
    See default.tempates.php
*/
$templates['item'] = isset($tplItem) ? $ditto->template->fetch($tplItem): $defaultTemplates['item'];
/*
    Param: tplItem

    Purpose:
    Template for the individual item

    Options:
    - Any valid chunk name
    - Code via @CODE:
    - File via @FILE:

    Default:
    See default.tempates.php
*/

$ditto->addField("date","display","custom");
    // force add the date field if receiving data from a Ditto instance

// ---------------------------------------------------
//  Reflect
// ---------------------------------------------------

if (function_exists("reflect") === FALSE) {
function reflect($templatesDocumentID, $showItems, $groupByYears, $resource, $templatesDateSource, $dateFormat, $ditto, $templates,$id,$start,$yearSortDir,$monthSortDir) {
    global $modx;
    $cal = array();
    $output = '';
    $ph = array('year'=>'','month'=>'','item'=>'','out'=>'');
    $build = array();
    $stop = count($resource);

    // loop and fetch all the results
    for ($i = $start; $i < $stop; $i++) {
        $date = getdate($resource[$i][$templatesDateSource]);
        $year = $date["year"];
        $month = $date["mon"];
        $cal[$year][$month][] = $resource[$i];
    }
    if ($yearSortDir == "DESC") {
        krsort($cal);
    } else {
        ksort($cal);
    }
    foreach ($cal as $year=>$months) {
        if ($monthSortDir == "ASC") {
            ksort($months);
        } else {
            krsort($months);
        }
        $build[$year] = $months;
    }
    
    foreach ($build as $year=>$months) {
        $r_year = '';
        $r_month = '';
        $r_month_2 = '';
        $year_count = 0;
        $items = array();
        
        foreach ($months as $mon=>$month) {
            $month_text = strftime("%B", mktime(10, 10, 10, $mon, 10, $year));
            $month_url = $ditto->buildURL("month=".$mon."&year=".$year."&day=false&start=0",$templatesDocumentID,$id);
            $month_count = count($month);
            $year_count += $month_count;
            $r_month = $ditto->template->replace(array("year"=>$year,"month"=>$month_text,"url"=>$month_url,"count"=>$month_count),$templates['month']);
            if ($showItems) {
                foreach ($month as $item) {
                    $items[$year][$mon]['items'][] = $ditto->render($item, $templates['item'], false, $templatesDateSource, $dateFormat, array(),$phx);
                }
                $r_month_2 = $ditto->template->replace(array('wrapper' => implode('',$items[$year][$mon]['items'])),$templates['month_inner']);
                $items[$year][$mon] = $ditto->template->replace(array('wrapper' => $r_month_2),$r_month);
            } else {
                $items[$year][$mon] = $r_month;
            }
        }
        if ($groupByYears) {
            $year_url = $ditto->buildURL("year=".$year."&month=false&day=false&start=0",$templatesDocumentID,$id);
            $r_year =  $ditto->template->replace(array("year"=>$year,"url"=>$year_url,"count"=>$year_count),$templates['year']);
            $var = $ditto->template->replace(array('wrapper'=>implode('',$items[$year])),$templates['year_inner']);
            $output .= $ditto->template->replace(array('wrapper'=>$var),$r_year);
        } else {
            $output .= implode('',$items[$year]);
        }
    }

    $output = $ditto->template->replace(array('wrapper'=>$output),$templates['tpl']);
    $modx->setPlaceholder($id.'reset',$ditto->buildURL('year=false&month=false&day=false',$templatesDocumentID,$id));

return $output;
    
}
}

return reflect($targetID, $showItems, $groupByYears, $resource, $dateSource, $dateFormat, $ditto, $templates,$id,$start,$yearSortDir,$monthSortDir);