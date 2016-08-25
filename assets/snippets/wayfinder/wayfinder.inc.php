<?php
/*
::::::::::::::::::::::::::::::::::::::::
 Snippet name: Wayfinder
 Short Desc: builds site navigation
 Version: 2.0.5
 Authors: 
    Kyle Jaebker (muddydogpaws.com)
    Ryan Thrash (vertexworks.com)
 Date: February 27, 2006
::::::::::::::::::::::::::::::::::::::::
*/

class Wayfinder {
    var $_config;
    var $_templates;
    var $_css;
    var $docs = array();
    var $parentTree = array();
    var $hasChildren = array();
    var $tvList = array();
    var $debugInfo = array();
    
    function __construct() {
    }
    
    function run() {
        global $modx;
        if ($this->_config['debug']) {
            $this->addDebugInfo('settings','Settings','Settings','Settings used to create this menu.',$this->_config);
            $this->addDebugInfo('settings','CSS','CSS Settings','Available CSS options.',$this->_css);
        }
        //setup here checking array
        $this->parentTree = $modx->getParentIds($this->_config['hereId']);
        $this->parentTree[] = $this->_config['hereId'];
        //Load the templates
        $this->checkTemplates();
        //Register any scripts
        if ($this->_config['cssTpl'] || $this->_config['jsTpl']) {
            $this->regJsCss();
        }
        //Get all of the documents
        $this->docs = $this->getData();
        
        if (!empty($this->docs)) {
            ksort($this->docs);                   //Sort documents by level for proper wrapper substitution
            return $this->buildMenu(); //build the menu
        } else {
            $noneReturn = $this->_config['debug'] ? '<p style="color:red">No documents found for menu.</p>' : '';
            return $noneReturn;
        }
    }
    
    function buildMenu() {
        global $modx;
        //Loop through all of the menu levels
        foreach ($this->docs as $level => $subParents) {
            //Loop through each document group (grouped by parent doc)
            foreach ($subParents as $parentId => $subDocs) {
                //only process document group, if starting at root, hidesubmenus is off, or is in current parenttree
                if ($this->_config['hideSubMenus'] && !$this->isHere($parentId) && 1<$level) continue;
                
                //Build the output for the group of documents
                $menuPart = $this->buildSubMenu($subDocs,$level);
                //If we are at the top of the menu start the output, otherwise replace the wrapper with the submenu
                if(($level==1 && (!$this->_config['displayStart'] || $this->_config['id']==0)) || ($level==0 && $this->_config['displayStart'])) {
                    $output = $menuPart;
                } else {
                    $output = str_replace("[+wf.wrapper.{$parentId}+]",$menuPart,$output);
                }
            }
        }
        //Return the final Menu
        return $output;
    }

    function buildSubMenu($subDocs,$level) {
        global $modx;
        
        $subMenuOutput = '';
        $counter = 1;
        $total = count($subDocs);
        
        //Loop through each document to render output
        foreach ($subDocs as $docId => $docInfo) {
            $docInfo['level'] = $level;
            $docInfo['first'] = $counter==1 ? 1 : 0;
            
            //Determine if last item in group
            if ($counter == $total && 0 < $total) $docInfo['last'] = 1;
            else                                  $docInfo['last'] = 0;
            
            //Determine if document has children
            if(in_array($docInfo['id'],$this->hasChildren)) {
                $docInfo['hasChildren'] = 1;
                $numChildren            = count($this->docs[$level+1][$docInfo['id']]);
            }
            else {
                $docInfo['hasChildren'] = 0;
                $numChildren            = 0;
            }
            
            //Render the row output
            $subMenuOutput .= $this->renderRow($docInfo,$numChildren,$counter);
            //Update counter for last check
            $counter++;
        }
        
        if ($level < 1) return $subMenuOutput;
        
        //Determine wrapper class
        if ($level==1) $wrapperClass = 'outercls';
        else           $wrapperClass = 'innercls'; // 1<$level
        
        //Get the class names for the wrapper
        $classNames = $this->setItemClass($wrapperClass, 0, 0, 0, $level);
        
        $ph = array();
        $ph['wf.wrapper']    = $subMenuOutput;
        $ph['wf.classes']    = $classNames ? sprintf(' class="%s"',$classNames) : '';
        $ph['wf.classnames'] = $classNames;
        $ph['wf.level']      = $level;
        
        //Determine which wrapper template to use
        if ($this->_templates['innerTpl'] && $wrapperClass=='innercls') $tpl = $this->_templates['innerTpl'];
        else                                                            $tpl = $this->_templates['outerTpl'];
        
        //Process the wrapper
        $subMenuOutput = $modx->parseText($tpl,$ph);
        //Debug
        if ($this->_config['debug']) {
            $info = array();
            $info['template'] = ($tpl==$this->_templates['innerTpl']) ? 'innerTpl':'outerTpl';
            foreach ($ph as $k=>$v) {
                if ($k !== 'wf.wrapper') $info[$k] = $v;
            }
            $groupkey = $docInfo['parent'];
            $header  = "Wrapper for items with parent {$groupkey}.";
            $message = 'These fields were used when processing the wrapper for the following documents.';
            $this->addDebugInfo('wrapper',$groupkey,$header,$message,$info);
        }
        //Return the submenu
        return $subMenuOutput;
    }
    
    //render each rows output
    function renderRow(&$resource,$numChildren,$curNum) {
        global $modx;
        $refid = $resource['id'];

        // Determine fields for use from referenced resource
        if ($this->_config['useReferenced'] && $resource['type'] == 'reference' && preg_match('@^[1-9][0-9]*$@',$resource['content'])) {
            if ($this->_config['useReferenced'] == 'id') {
                // if id only, do not need get referenced data
                $resource['id'] = $resource['content'];
            } elseif ($referenced = $modx->getDocument($resource['content'])) {
                if (in_array($this->_config['useReferenced'], explode(',', '1,*'))) {
                    $this->_config['useReferenced'] = array_keys($resource);
                }
                if (!is_array($this->_config['useReferenced'])) {
                    $this->_config['useReferenced'] = preg_split("/[\s,]+/", $this->_config['useReferenced']);
                }
                $this->_config['useReferenced'] = array_diff($this->_config['useReferenced'], explode(',', 'content,parent,isfolder'));
                
                foreach ($this->_config['useReferenced'] as $field) {
                    if (isset($referenced[$field])) $resource[$field] = $referenced[$field];
                    $linkTextField = empty($resource[$this->_config['textOfLinks']]) ? 'pagetitle' : $this->_config['textOfLinks'];
                    if (in_array($field,array('linktext',$linkTextField))) $resource['linktext'] = $referenced[$linkTextField];
                    if (in_array($field,array('title',$this->_config['titleOfLinks']))) $resource['title'] = $referenced[$this->_config['titleOfLinks']];
                }
            }
        }
        
        //Determine which template to use
        if ($this->_config['displayStart'] && $resource['level'] == 0) {
            $usedTemplate = 'startItemTpl';
        } elseif ($resource['id'] == $modx->documentIdentifier
            && $resource['isfolder']
            && $this->_templates['parentRowHereTpl']
            && ($resource['level'] < $this->_config['level'] || $this->_config['level'] == 0)
            && $numChildren) {
            $usedTemplate = 'parentRowHereTpl';
        } elseif ($resource['id'] == $modx->documentIdentifier && $this->_templates['innerHereTpl'] && $resource['level'] > 1) {
            $usedTemplate = 'innerHereTpl';
        } elseif ($resource['id'] == $modx->documentIdentifier && $this->_templates['hereTpl']) {
            $usedTemplate = 'hereTpl';
        } elseif ($resource['isfolder']
            && $this->_templates['activeParentRowTpl']
            && ($resource['level'] < $this->_config['level'] || $this->_config['level'] == 0)
            && $this->isHere($resource['id'])
            && $numChildren) {
            $usedTemplate = 'activeParentRowTpl';
        } elseif ($resource['isfolder']
            && ($resource['template']=='0' || is_numeric(strpos($resource['link_attributes'],'rel="category"')))
            && $this->_templates['categoryFoldersTpl']
            && ($resource['level'] < $this->_config['level'] || $this->_config['level'] == 0)) {
            $usedTemplate = 'categoryFoldersTpl';
        } elseif ($resource['isfolder']
            && $this->_templates['parentRowTpl']
            && ($resource['level'] < $this->_config['level'] || $this->_config['level'] == 0)
            && $numChildren) {
            $usedTemplate = 'parentRowTpl';
        } elseif ($resource['level'] > 1 && $this->_templates['innerRowTpl']) {
            $usedTemplate = 'innerRowTpl';
        } elseif ($resource['last'] && $this->_templates['lastRowTpl']) {
            $usedTemplate = 'lastRowTpl';
        } else {
            $usedTemplate = 'rowTpl';
        }
        //Setup the new wrapper name and get the class names
        $useSub = $resource['hasChildren'] ? "[+wf.wrapper.{$refid}+]" : '';
        $classNames = $this->setItemClass('rowcls',$resource['id'],$resource['first'],$resource['last'],$resource['level'],$resource['hasChildren'],$resource['type']);
        $useClass = ($classNames) ? $useClass = sprintf(' class="%s"',$classNames) : '';
        
        //Setup the row id if a prefix is specified
        if ($this->_config['rowIdPrefix']) $useId = sprintf(' id="%s%s"', $this->_config['rowIdPrefix'], $resource['id']);
        else                               $useId = '';
        
        //Load row values into placholder array
        $ph = array();
        $ph['wf.wrapper']      = $useSub;
        $ph['wf.classes']      = $useClass;
        $ph['wf.classnames']   = $classNames;
        $ph['wf.link']         = $resource['link'];
        $ph['wf.title']        = !$this->_config['entityEncode'] ? $resource['title']       : $this->hsc($resource['title']);
        $ph['wf.linktext']     = !$this->_config['entityEncode'] ? $resource['linktext']    : $this->hsc($resource['linktext']);
        $ph['wf.id']           = $useId;
        $ph['wf.alias']        = $resource['alias'];
        $ph['wf.attributes']   = $resource['link_attributes'];
        $ph['wf.docid']        = $resource['id'];
        $ph['wf.introtext']    = !$this->_config['entityEncode'] ? $resource['introtext']   : $this->hsc($resource['introtext']);
        $ph['wf.description']  = !$this->_config['entityEncode'] ? $resource['description'] : $this->hsc($resource['description']);
        $ph['wf.subitemcount'] = $numChildren;
        $ph['wf.refid']        = $refid;
        $ph['wf.menuindex']    = $resource['menuindex'];
        $ph['wf.iterator']     = $curNum;
        
        //Add document variables to the placeholder array
        foreach ($resource as $dvName => $dvVal) {
            $ph[$dvName] = $dvVal;
        }
        
        //If tvs are used add them to the placeholder array
        if (!empty($this->tvList)) {
            foreach ($this->tvList as $tvName) {
                $ph[$tvName] = $resource[$tvName];
            }
        }
        //Debug
        if ($this->_config['debug']) {
            $debugDocInfo = array();
            $debugDocInfo['template'] = $usedTemplate;
            foreach ($ph as $k=>$v) {
                $k = "[+{$k}+]";
                $debugDocInfo[$k] = $v;
            }
            $this->addDebugInfo('row',"{$resource['parent']}:{$resource['id']}","Doc: #{$resource['id']}",'The following fields were used when processing this document.',$debugDocInfo);
            $this->addDebugInfo('rowdata',"{$resource['parent']}:{$resource['id']}","Doc: #{$resource['id']}",'The following fields were retrieved from the database for this document.',$resource);
        }
        //Process the row
        
        $output = $modx->parseText($this->_templates[$usedTemplate],$ph);
        
        return $output . $this->_config['nl'];
    }
    
    //determine style class for current item being processed
    function setItemClass($classType, $docId = 0, $first = 0, $last = 0, $level = 0, $isFolder = 0, $type = 'document') {
        global $modx;
        
        $classNames = array();
        $class  = &$this->_css;
        $config = &$this->_config;
        
        switch($classType) {
            case 'outercls':
                if(!empty($class['outer']))                                   $classNames[] = $class['outer'];             //Set outer class if specified
                break;
            case 'innercls':
                if( !empty($class['inner']))                                  $classNames[] = $class['inner'];             //Set inner class if specified
                if(!empty($class['outerLevel']))                              $classNames[] = $class['outerLevel'].$level; //Set level class if specified
                break;
            case 'rowcls':
                if(!empty($class['row']))                                     $classNames[] = $class['row'];          //Set row class if specified
                if($first && !empty($class['first']))                         $classNames[] = $class['first'];        //Set first class if specified
                if($last  && !empty($class['last']))                          $classNames[] = $class['last'];         //Set last class if specified
                if(!empty($class['level']))                                   $classNames[] = $class['level'].$level; //Set level class if specified
                
                if(!empty($class['here'])    && $this->isHere($docId))        $classNames[] = $class['here'];    //Set here class if specified
                if(!empty($class['self'])    && $docId==$config['hereId'])    $classNames[] = $class['self'];    //Set self class if specified
                if(!empty($class['weblink']) && $type=='reference')           $classNames[] = $class['weblink']; //Set class for weblink
                
                if($isFolder && !empty($class['parent'])) {
                    if($level < $config['level'] || $config['level']==0) {
                        if($this->isHere($docId) || !$config['hideSubMenus']) $classNames[] = $class['parent'];  // Set parentFolder class if specified
                    }
                }
                break;
            default:
                return;
        }

        if($classNames) return join(' ', $classNames);
    }

    //determine "you are here"
    function isHere($did) {
        return in_array($did,$this->parentTree);
    }

    //Add the specified css & javascript chunks to the page
    function regJsCss() {
        global $modx;
        //Debug
        if ($this->_config['debug']) {
            $jsCssDebug = array('js' => 'None Specified.', 'css' => 'None Specified.');
        }
        //Check and load the CSS
        if ($this->_config['cssTpl']) {
            $cssChunk = $this->fetch($this->_config['cssTpl']);
            if ($cssChunk) {
                $modx->regClientCSS($cssChunk);
                if ($this->_config['debug']) $jsCssDebug['css'] = sprintf('The CSS in %s was registered.', $this->_config['cssTpl']);
            }
            elseif ($this->_config['debug']) $jsCssDebug['css'] = sprintf('The CSS in %s was not found.',  $this->_config['cssTpl']);
        }
        //Check and load the Javascript
        if ($this->_config['jsTpl']) {
            $jsChunk = $this->fetch($this->_config['jsTpl']);
            if ($jsChunk) {
                $modx->regClientStartupScript($jsChunk);
                if ($this->_config['debug']) $jsCssDebug['js'] = sprintf('The Javascript in %s was registered.', $this->_config['jsTpl']);
            }
            elseif ($this->_config['debug']) $jsCssDebug['js'] = sprintf('The Javascript in %s was not found.',  $this->_config['jsTpl']);
        }
        //Debug
        if ($this->_config['debug']) $this->addDebugInfo('settings','JSCSS','JS/CSS Includes','Results of CSS & Javascript includes.',$jsCssDebug);
    }

    //Get all of the documents from the database
    function getData() {
        global $modx;
        $depth = !empty($this->_config['level']) ? $this->_config['level'] : 10;
        $ids = array();
        
        if(strtolower(substr($this->_config['id'],0,1))==='p')
            $this->_config['id'] = $this->getParentID($modx->documentIdentifier);
        
        if (!$this->_config['hideSubMenus']) {
            $ids = $modx->getChildIds($this->_config['id'],$depth);
        } else { // then hideSubMenus is checked, we don`t need all children
            // first we always included the chilren of startId document
            // this fix problem with site root chidrens,
            // because site root not included in $modx->getParentIds
            $ids = $modx->getChildIds($this->_config['id'], 1, $ids);

            $parents = array($this->_config['hereId']);
            $parents += $modx->getParentIds($this->_config['hereId']);

            // if startId not in parents, only show children of startId
            if ($this->_config['id'] == 0 || in_array($this->_config['id'], $parents)){

                //remove parents higher than startId(including startId)
                $startId_parents = array($this->_config['id']);
                $startId_parents += $modx->getParentIds($this->_config['id']);
                $parents = array_diff($parents, $startId_parents);

                //remove parents lower than level of startId + level depth
                $parents = array_slice(array_reverse($parents), 0, $depth-1);

                foreach($parents as $p)
                    $ids = $modx->getChildIds($p, 1, $ids);
            }
        }
        //Get all of the ids for processing
        if ($this->_config['displayStart'] && $this->_config['id'] !== 0) {
            $ids[] = $this->_config['id'];
        }
        if (!empty($ids)) {
            //Setup the fields for the query
            $fields = explode(',','id,menutitle,pagetitle,introtext,menuindex,published,hidemenu,parent,isfolder,description,alias,longtitle,type,content,template,link_attributes');
            foreach($fields as $i=>$v) {
                if    ($v=='alias')   $fields[$i] = "IF(sc.alias='', sc.id, sc.alias) AS alias";
                elseif($v=='content') $fields[$i] = "IF(sc.type='reference',sc.content,'') AS content";
                else                  $fields[$i] = 'sc.'.$v;
            }
            $fields = join(',', $fields);
            
            //Determine sorting
            if (strtolower($this->_config['sortBy'])=='random')
                $sort = 'rand()';
            else {
                // modify field names to use sc. table reference
                $_ = explode(',', $this->_config['sortBy']);
                foreach($_ as $i=>$v) {
                    $_[$i] = 'sc.' . trim($v);
                }
                $sort = implode(',', $_);
            }

            // get document groups for current user
            if($docgrp = $modx->getUserDocGroups()) $docgrp = implode(',',$docgrp);
            // build query
            if($modx->isFrontend())
                $access = 'sc.privateweb=0';
            else {
                $access = sprintf("1='%s' OR sc.privatemgr=0", $_SESSION['mgrRole']);
                if($docgrp) $access .= sprintf(' OR dg.document_group IN (%s)', $docgrp);
            }
            if($access) $access = "AND({$access})";
            
            //Add the ignore hidden option to the where clause
            if ($this->_config['ignoreHidden'])  $menuWhere = '';
            else                                 $menuWhere = ' AND sc.hidemenu=0';
            
            //add the include docs to the where clause
            if ($this->_config['includeDocs'])   $menuWhere .= sprintf(' AND sc.id IN (%s)', $this->_config['includeDocs']);
            
            //add the exclude docs to the where clause
            if ($this->_config['excludeDocs'])   $menuWhere .= sprintf(' AND (sc.id NOT IN (%s))', $this->_config['excludeDocs']);
            
            //add custom where conditions
            if (!empty($this->_config['where'])) $menuWhere .= sprintf(' AND (%s)', $this->_config['where']);
            
            //add the limit to the query
            if ($this->_config['limit']) $limit = sprintf('0, %s', $this->_config['limit']);
            else                         $limit = '';
            
            $fields = "DISTINCT {$fields}";
            $from   = '[+prefix+]site_content sc LEFT JOIN [+prefix+]document_groups dg ON dg.document=sc.id';
            $where  = sprintf('sc.published=1 AND sc.deleted=0 %s %s AND sc.id IN (%s) GROUP BY sc.id', $access, $menuWhere, implode(',',$ids));
            $sort   = "{$sort} {$this->_config['sortOrder']}";
            
            //run the query
            $result = $modx->db->select($fields,$from,$where,$sort,$limit);
            
            $resourceArray = array();
            $level = 1;
            $prevParent = -1;
            //Setup startlevel for determining each items level
            if ($this->_config['id'] == 0) {
                $startLevel = 0;
            } else {
                $startLevel = count($modx->getParentIds($this->_config['id']));
                $startLevel = $startLevel ? $startLevel+1 : 1;
            }
            $resultIds = array();
            //loop through the results
            while($row = $modx->db->getRow($result)) {
                $resultIds[] = $row['id'];
                //Create the link
                $linkScheme = $this->_config['fullLink'] ? 'full' : '';
                if ($this->_config['useWeblinkUrl'] && $row['type'] == 'reference') {
                    if (preg_match('@^[1-9][0-9]*$@',$row['content'])) $row['link'] = $modx->makeUrl(intval($row['content']),'','',$linkScheme);
                    else                                               $row['link'] = $row['content'];
                }
                elseif ($row['id'] == $modx->config['site_start'])     $row['link'] = $modx->config['site_url'];
                else                                                   $row['link'] = $modx->makeUrl($row['id'],'','',$linkScheme);
                
                //determine the level, if parent has changed
                if ($prevParent !== $row['parent']) {
                    $level = count($modx->getParentIds($row['id'])) + 1 - $startLevel;
                }
                //add parent to hasChildren array for later processing
                if (($level > 1 || $this->_config['displayStart']) && !in_array($row['parent'],$this->hasChildren)) {
                    $this->hasChildren[] = $row['parent'];
                }
                //set the level
                $row['level'] = $level;
                $prevParent = $row['parent'];
                //determine other output options
                $useTextField = (empty($row[$this->_config['textOfLinks']])) ? 'pagetitle' : $this->_config['textOfLinks'];
                $row['linktext'] = $row[$useTextField];
                $row['title'] = $row[$this->_config['titleOfLinks']];
                //If tvs were specified keep array flat otherwise array becomes level->parent->doc
                if (!empty($this->tvList)) {
                    $tempResults[] = $row;
                } else {
                    $resourceArray[$row['level']][$row['parent']][] = $row;
                }
            }
            //Process the tvs
            if (!empty($this->tvList) && !empty($resultIds)) {
                $tvValues = array();
                //loop through all tvs and get their values for each document
                foreach ($this->tvList as $tvName) {
                    $tvValues = array_merge_recursive($this->appendTV($tvName,$resultIds),$tvValues);
                }
                //loop through the document array and add the tvar values to each document
                foreach ($tempResults as $tempDocInfo) {
                    if (isset($tvValues["#{$tempDocInfo['id']}"])) {
                        foreach ($tvValues["#{$tempDocInfo['id']}"] as $tvName => $tvValue) {
                            $tempDocInfo[$tvName] = $tvValue;
                        }
                    }
                    $resourceArray[$tempDocInfo['level']][$tempDocInfo['parent']][] = $tempDocInfo;
                }
            }
        }
        //return final docs
        return $resourceArray;
    }

    // ---------------------------------------------------
    // Function: appendTV taken from Ditto (thanks Mark)
    // Apeend a TV to the documents array
    // ---------------------------------------------------

    function appendTV($tvname,$docIDs){
        global $modx;
        
        $resourceArray = array();
        foreach($docIDs as $id) {
            $tv = $modx->getTemplateVarOutput($tvname, $id);
            $resourceArray["#{$id}"][$tvname] = $tv[$tvname];
        }
        return $resourceArray;
    }
    
    // ---------------------------------------------------
    // Get a list of all available TVs
    // ---------------------------------------------------

    function getTVList() {
        global $modx;
        $tvs = $modx->db->select('name', $modx->getFullTableName('site_tmplvars'));
            // TODO: make it so that it only pulls those that apply to the current template
        $dbfields = $modx->db->getColumn('name', $tvs); 
        return $dbfields;
    }

    //debugging to check for valid chunks
    function checkTemplates() {
        global $modx;
        $nonWayfinderFields = array();

        foreach ($this->_templates as $n => $v) {
            $templateCheck = $this->fetch($v);
            if (empty($v) || !$templateCheck) {
                switch($n) {
                    case 'outerTpl'    : $_ = '<ul[+wf.classes+]>[+wf.wrapper+]</ul>';break;
                    case 'rowTpl'      : $_ = '<li[+wf.id+][+wf.classes+]><a href="[+wf.link+]" title="[+wf.title+]" [+wf.attributes+]>[+wf.linktext+]</a>[+wf.wrapper+]</li>';break;
                    case 'startItemTpl': $_ = '<h2[+wf.id+][+wf.classes+]>[+wf.linktext+]</h2>[+wf.wrapper+]';break;
                    default:$_ = FALSE;
                }
                $this->_templates[$n] = $_;
                if ($this->_config['debug']) { $this->addDebugInfo('template',$n,$n,'No template found, using default.',array($n => $this->_templates[$n])); }
            } else {
                $this->_templates[$n] = $templateCheck;
                $check = $this->findTemplateVars($templateCheck);
                if (is_array($check)) {
                    $nonWayfinderFields = array_merge($check, $nonWayfinderFields);
                }
                if ($this->_config['debug']) { $this->addDebugInfo('template',$n,$n,'Template Found.',array($n => $this->_templates[$n])); }
            }
        }

        if (!empty($nonWayfinderFields)) {
            $nonWayfinderFields = array_unique($nonWayfinderFields);
            $allTvars = $this->getTVList();

            foreach ($nonWayfinderFields as $field) {
                if (in_array($field, $allTvars)) {
                    $this->tvList[] = $field;
                }
            }
            if ($this->_config['debug']) { $this->addDebugInfo('tvars','tvs','Template Variables','The following template variables were found in your templates.',$this->tvList); }
        }
    }

    function fetch($tpl){
        // based on version by Doze at http://forums.modx.com/thread/41066/support-comments-for-ditto?page=2#dis-post-237942
        global $modx;
        $template = '';
        if(substr($tpl, 0, 5) == '@FILE') {
            $template = file_get_contents(substr($tpl, 6));
        } elseif(substr($tpl, 0, 5) == '@CODE') {
            $template = substr($tpl, 6);
        } elseif($modx->getChunk($tpl) != '') {
            $template = $modx->getChunk($tpl);
        } else {
            $template = FALSE;
        }
        return $template;
    }


    function findTemplateVars($tpl) {
        preg_match_all('~\[\+([^:]*?)(:|\+\])~', $tpl, $matches);
        $tvnames = array();
        foreach($matches[1] as $tv) {
            if (strpos(strtolower($tv), 'phx')===0) continue;
            if (strpos(strtolower($tv), 'wf.')===0) continue;
            $tvnames[] = $tv;
        }

        if (count($tvnames) >= 1) {
            return array_unique($tvnames);
        } else {
            return false;
        }
    }

    function addDebugInfo($group,$groupkey,$header,$message,$info) {
        $infoString = '<table border="1" cellpadding="3px">';
        $numInfo = count($info);
        $count = 0;

        foreach ($info as $key => $value) {
            $key = $this->modxPrep($key);
            if ($value === TRUE || $value === FALSE) {
                $value = $value ? 'TRUE' : 'FALSE';
            } else {
                $value = $this->modxPrep($value);
            }
            if ($count == 2) { $infoString .= '</tr>'; $count = 0; }
            if ($count == 0) { $infoString .= '<tr>'; }
            $value = empty($value) ? '&nbsp;' : $value;
            $infoString .= "<td><strong>{$key}</strong></td><td>{$value}</td>";
            $count++;
        }
        $infoString .= '</tr></table>';

        $this->debugInfo[$group][$groupkey] = array(
            'header' => $this->modxPrep($header),
            'message' => $this->modxPrep($message),
            'info' => $infoString,
        );
    }

    function renderDebugOutput() {
        $output = '<table border="1" cellpadding="3px" width="100%">';
        foreach ($this->debugInfo as $group => $item) {
            switch ($group) {
                case 'template':
                    $output .= '<tr><th style="background:#C3D9FF;font-size:200%;">Template Processing</th></tr>';
                    foreach ($item as $parentId => $info) {
                        $output .= sprintf('
                            <tr style="background:#336699;color:#fff;"><th>%s - <span style="font-weight:normal;">%s</span></th></tr>
                            <tr><td>%s</td></tr>', $info['header'], $info['message'], $info['info']);
                    }
                    break;
                case 'wrapper':
                    $output .= '<tr><th style="background:#C3D9FF;font-size:200%;">Document Processing</th></tr>';

                    foreach ($item as $parentId => $info) {
                        $output .= sprintf('<tr><table border="1" cellpadding="3px" style="margin-bottom: 10px;width:100%">
                                    <tr style="background:#336699;color:#fff;"><th>%s - <span style="font-weight:normal;">%s</span></th></tr>
                                    <tr><td>%s</td></tr>
                                    <tr style="background:#336699;color:#fff;"><th>Documents included in this wrapper:</th></tr>',$info['header'],$info['message'],$info['info']);

                        foreach ($this->debugInfo['row'] as $key => $value) {
                            $keyParts = explode(':',$key);
                            if ($parentId == $keyParts[0]) {
                                $param = array($value['header'],$value['message'],$value['info'],$this->debugInfo['rowdata'][$key]['message'],$this->debugInfo['rowdata'][$key]['info']);
                                $output .= vsprintf('<tr style="background:#eee;"><th>%s</th></tr>
                                    <tr><td><div style="float:left;margin-right:1%;">%s<br />%s</div><div style="float:left;">%s<br />%s</div></td></tr>',$param);
                            }
                        }

                        $output .= '</table></tr>';
                    }

                    break;
                case 'settings':
                    $output .= '<tr><th style="background:#C3D9FF;font-size:200%;">Settings</th></tr>';
                    foreach ($item as $parentId => $info) {
                        $output .= sprintf('
                            <tr style="background:#336699;color:#fff;"><th>%s - <span style="font-weight:normal;">%s</span></th></tr>
                            <tr><td>%s</td></tr>',$info['header'],$info['message'],$info['info']);
                    }
                    break;
                default:

                    break;
            }
        }
        $output .= '</table>';
        return $output;
    }

    function modxPrep($value) {
        global $modx;
        $value = (strpos($value,'<') !== FALSE) ? htmlentities($value,ENT_NOQUOTES,$modx->config['modx_charset']) : $value;
        $s = array('[', ']', '{', '}');
        $r = array('&#091;', '&#093;', '&#123;', '&#125;');
        $value = str_replace($s, $r, $value);
        return $value;
    }
    function hsc($string) {
        global $modx;
        return htmlspecialchars($string, ENT_COMPAT, $modx->config['modx_charset']);
    }
    function getParentID($id)
    {
        global $modx;
        if($modx->documentObject['parent']==0)   return $id;
        return $modx->documentObject['parent'];
    }
}
