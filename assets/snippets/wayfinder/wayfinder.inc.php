<?php

/*
 Snippet name: Wayfinder
 Short Desc: builds site navigation
 Version: 1.0.1
 Authors: Ryan Thrash (vertexworks.com)
          Kyle Jaebker (muddydogpaws.com)
*/

class Wayfinder {
    var $id = 0;
    var $level = 0;
    var $ph = FALSE;
    var $debug;
    var $ignoreHidden = FALSE;
    var $hideSubMenus = FALSE;
    var $textOfLinks = 'menutitle';
    var $titleOfLinks = 'pagetitle';
    var $templates = array();
    var $parentTree = array();
    var $css = array();
    var $cssTpl = FALSE;
    var $jsTpl = FALSE;
    var $rowIdPrefix = FALSE;
    var $useWeblinkUrl = TRUE;
	var $sortOrder = 'ASC';
	var $sortBy = 'menuindex';
    var $modxVersion = array();
	var $showSubDocCount = FALSE;
	var $docCount = array();
	var $limit = 0;
	var $randomLinks = 0;
    var $placeHolders = array('[+wf.wrapper+]','[+wf.classes+]','[+wf.classnames+]','[+wf.link+]','[+wf.title+]','[+wf.linktext+]','[+wf.id+]','[+wf.attributes+]','[+wf.docid+]','[+wf.introtext+]','[+wf.description+]','[+wf.subitemcount+]');
    var $ie = "\n";
    var $debugOutput = '<h2>WayFinder Debug Output:</h2>';
    
	//Run all of the wayfinder methods
	function run() {
		global $modx;
		//setup here checking array
		$this->parentTree = $modx->getParentIds($modx->documentIdentifier);
		$this->parentTree[] = $modx->documentIdentifier;
		//Get version info
		$this->modxVersion = $modx->getVersionData();
		
		if ($this->debug) {
		    $this->debugOutput .= '<p>Starting Menu from Docid: ' . $this->id . '<br/>';
		    $this->debugOutput .= 'Docids for \'Here\' class checking: ' . implode(', ',$this->parentTree) . '</p>';
		    $this->debugOutput .= '<h3>Chunk Checks</h3>';
		}

		$this->checkChunks();

		if ($this->cssTpl || $this->jsTpl) {
		    $this->regJsCss();
		}

		if ($this->debug) {
		    $this->debugOutput .= '<h3>Document Processing</h3>';
		}

		$output = $this->buildMenu($this->id);
		
		if ($this->debug) {
		    $output = $output . $this->debugOutput;
		}

		return $output;
	}
	
	//Get the menu items from the DB
    function getMenuChildren($id=0, $sort='menuindex', $dir='ASC') {
        global $modx;
        $fields = 'sc.id,sc.menutitle,sc.pagetitle,sc.introtext,sc.menuindex,sc.published,sc.hidemenu,sc.parent,sc.isfolder,sc.description,sc.alias,sc.longtitle,sc.type,if(sc.type=\'reference\',sc.content,\'\') as content, sc.template';
        
        $revision= substr($this->modxVersion['code_name'],-4);
        if ($revision >= 1392 && $revision != 1923) {
            $fields .= ',sc.link_attributes';
        }
        
        $tblsc = $modx->getFullTableName("site_content");
        $tbldg = $modx->getFullTableName("document_groups");
        
        if ($this->ignoreHidden) {
            $menuWhere = '';
        } else {
            $menuWhere = ' AND sc.hidemenu = 0';
        }
		
		if ($this->limit) {
			$sqlLimit = ' LIMIT 0, ' . $this->limit;
		} else {
			$sqlLimit = '';
		}

		if (strtolower($sort) == 'random') {
			$sort = 'rand()';
			$dir = '';
		} else {
			// modify field names to use sc. table reference
			$sort = 'sc.'.implode(',sc.',preg_replace("/^\s/i","",explode(',',$sort)));
		}
		
        // get document groups for current user
        if($docgrp = $modx->getUserDocGroups()) $docgrp = implode(",",$docgrp);
        // build query
        $access = ($modx->isFrontend() ? "sc.privateweb=0":"1='".$_SESSION['mgrRole']."' OR sc.privatemgr=0").
          (!$docgrp ? "":" OR dg.document_group IN ($docgrp)");
        $sql = 'SELECT DISTINCT '.$fields.' FROM '.$tblsc.' sc LEFT JOIN '.$tbldg.
            ' dg on dg.document = sc.id WHERE sc.parent = '.$id.
            ' AND sc.published=1 AND sc.deleted=0 AND ('.$access.')'.$menuWhere.
			' GROUP BY sc.id'.
            ' ORDER BY '.$sort.' '.$dir.
			$sqlLimit.';';
        $result = $modx->dbQuery($sql);
        $resourceArray = array();
        for($i=0;$i<@$modx->recordCount($result);$i++)  {
          array_push($resourceArray,@$modx->fetchRow($result));
        }
        return $resourceArray;
    }

    //generate the menu
    function buildMenu($parentId,$curLevel = 1) {
        global $modx;
        $output = '';

        $resource = $this->getMenuChildren($parentId,$this->sortBy,$this->sortOrder);

        $firstItem = 1;

        if (is_array($resource) && !empty($resource)) {
            $numItems = count($resource);
			$this->docCount[$parentId] = $numItems;
            foreach ($resource as $n => $v) {
                if ($this->useWeblinkUrl !== 'FALSE' && $v['type'] == 'reference') {
					if (is_numeric($v['content'])) {
						$v['link'] = $modx->makeUrl(intval($v['content']));
					} else {
						$v['link'] = $v['content'];
					}
                } else {
                    $v['link'] = $modx->makeUrl($v['id']);
                }
                $v['level'] = $curLevel;
                $v['first'] = $firstItem;
                $firstItem = 0;
                if ($n == ($numItems-1) && $numItems > 1) {
                    $v['last'] = 1;
                } else {
                    $v['last'] = 0;
                }

                $useTextField = (empty($v[$this->textOfLinks])) ? 'pagetitle' : "$this->textOfLinks";
                $v['linktext'] = $v[$useTextField];
                $v['title'] = $v[$this->titleOfLinks];

                if ($v['isfolder'] && ($curLevel < $this->level || $this->level == 0) && (!$this->hideSubMenus || $this->isHere($v['id']))) {
                    $oldLevel = $curLevel;
                    $subMenu = $this->ie . $this->buildMenu($v['id'],++$curLevel) . $this->ie;
                    $curLevel = $oldLevel;
                } else {
                    $subMenu = '';
                }
				if ($this->showSubDocCount && $v['isfolder']) {
						$resource = $this->getMenuChildren($v['id'],$this->sortBy,$this->sortOrder);
						$this->docCount[$v['id']] = count($resource);
				}
				
                $output .= $this->renderRow($v,$subMenu);
            }

            if ($this->templates['innerTpl'] && $curLevel > 1) {
                $useChunk = $this->templates['innerTpl'];
                $usedTemplate = 'innerTpl';
            } else {
                $useChunk = $this->templates['outerTpl'];
                $usedTemplate = 'outerTpl';
            }

            if ($curLevel > 1) {
                $wrapperClass = 'innercls';
            } else {
                $wrapperClass = 'outercls';
            }

            $classNames = $this->setItemClass($wrapperClass);
            if ($classNames) $useClass = ' class="' . $classNames . '"';
            $phArray = array($output,$useClass,$classNames);

            $output = str_replace($this->placeHolders,$phArray,$useChunk);

            if ($this->debug) {
                $this->debugOutput .= '<strong>Nesting Complete:</strong> Previous ' . $this->docCount[$parentId] . ' level ' . $curLevel . ' items inserted into ' . $usedTemplate . ' with class ' . $classNames . '<br/>';
            }
        }

        return $output;
    }

    //render each rows output
    function renderRow(&$resource,$subMenu) {
        global $modx;
        $output = '';

        if ($resource['id'] == $modx->documentObject['id'] && $resource['isfolder'] && $this->templates['parentRowHereTpl'] && ($resource['level'] < $this->level || $this->level == 0)) {
            $usedTemplate = 'parentRowHereTpl';
        } elseif ($resource['id'] == $modx->documentObject['id'] && $this->templates['innerHereTpl'] && $resource['level'] > 1) {
            $usedTemplate = 'innerHereTpl';
        } elseif ($resource['id'] == $modx->documentObject['id'] && $this->templates['hereTpl']) {
            $usedTemplate = 'hereTpl';
        } elseif ($resource['isfolder'] && $this->templates['activeParentRowTpl'] && ($resource['level'] < $this->level || $this->level == 0) && $this->isHere($resource['id'])) {
            $usedTemplate = 'activeParentRowTpl';
        } elseif ($resource['isfolder'] && ($resource['template']=="0" || is_numeric(strpos($resource['link_attributes'],'rel="category"'))) && $this->templates['categoryFoldersTpl'] && ($resource['level'] < $this->level || $this->level == 0)) {
            $usedTemplate = 'categoryFoldersTpl';
        } elseif ($resource['isfolder'] && $this->templates['parentRowTpl'] && ($resource['level'] < $this->level || $this->level == 0)) {
            $usedTemplate = 'parentRowTpl';
        } elseif ($resource['level'] > 1 && $this->templates['innerRowTpl']) {
            $usedTemplate = 'innerRowTpl';
        } else {
            $usedTemplate = 'rowTpl';
        }
        
        $useChunk = $this->templates[$usedTemplate];

        $useSub = $subMenu;
        $classNames = $this->setItemClass('rowcls',$resource['id'],$resource['first'],$resource['last'],$resource['level'],$resource['isfolder'],$resource['type']);
        if ($classNames) $useClass = ' class="' . $classNames . '"';
        
        if ($this->rowIdPrefix) {
            $useId = ' id="' . $this->rowIdPrefix . $resource['id'] . '"';
        } else {
            $useId = '';
        }

        $phArray = array($useSub,$useClass,$classNames,$resource['link'],$resource['title'],$resource['linktext'],$useId,$resource['link_attributes'],$resource['id'],$resource['introtext'],$resource['description'],$this->docCount[$resource['id']]);

        $output .= str_replace($this->placeHolders,$phArray,$useChunk);

        if ($this->debug) {
            $this->debugOutput .= '<strong>Item Processed: (' . $resource['id'] . ') ' . $resource['pagetitle'] . '</strong><br/>
            template: ' . $usedTemplate . ' | class: ' . $classNames . '<br/>
            level: ' . $resource['level'] . ' | First/Last: ' . $resource['first'] . '/' . $resource['last'] . '<br/>';
            $this->debugOutput .= $this->rowIdPrefix? 'Id applied: ' . $useId . '<br/>' : '';
        }

        return $output . $this->ie;
    }

    //debugging to check for valid chunks
    function checkChunks() {
        global $modx;

        foreach ($this->templates as $n => $v) {
            $chunkcheck = $modx->getChunk($v);
            if (empty($v) || !$chunkcheck) {
                if ($n === 'outerTpl') {
                    $this->templates[$n] = '<ul[+wf.classes+]>[+wf.wrapper+]</ul>';
                } elseif ($n === 'rowTpl') {
                    $this->templates[$n] = '<li[+wf.id+][+wf.classes+]><a href="[+wf.link+]" title="[+wf.title+]" [+wf.attributes+]>[+wf.linktext+]</a>[+wf.wrapper+]</li>';
                } else {
                    $this->templates[$n] = FALSE;
                }
                if ($this->debug) {
                    $this->debugOutput .= 'No chunk found for <strong>' . $n . '</strong> using default.<br/>';
                }
            } else {
                $this->templates[$n] = $chunkcheck;
                if ($this->debug) {
                    $this->debugOutput .= 'The chunk ('.$v.') for <strong>' . $n . '</strong> was used.<br/>';
                }
            }
        }
        if ($this->debug) {
            $this->debugOutput .= '<br/>';
        }
    }

    //determine "you are here"
    function isHere($did) {
        return in_array($did,$this->parentTree);
    }

    //determine style class for current item being processed
    function setItemClass($classType, $docId = 0, $first = 0, $last = 0, $level = 0, $isFolder = 0, $type = 'document') {
        global $modx;
        $returnClass = '';
        $hasClass = 0;

        if ($classType === 'outercls' && !empty($this->css['outer'])) {
            //Set outer class if specified
            $returnClass .= $this->css['outer'];
            $hasClass = 1;
        } elseif ($classType === 'innercls' && !empty($this->css['inner'])) {
            //Set inner class if specified
            $returnClass .= $this->css['inner'];
            $hasClass = 1;
        } elseif ($classType === 'rowcls') {
            //Set row class if specified
            if (!empty($this->css['row'])) {
                $returnClass .= $this->css['row'];
                $hasClass = 1;
            }
            //Set first class if specified
            if ($first && !empty($this->css['first'])) {
                $returnClass .= $hasClass ? ' ' . $this->css['first'] : $this->css['first'];
                $hasClass = 1;
            }
            //Set last class if specified
            if ($last && !empty($this->css['last'])) {
                $returnClass .= $hasClass ? ' ' . $this->css['last'] : $this->css['last'];
                $hasClass = 1;
            }
            //Set level class if specified
            if (!empty($this->css['level'])) {
                $returnClass .= $hasClass ? ' ' . $this->css['level'] . $level : $this->css['level'] . $level;
                $hasClass = 1;
            }
            //Set parentFolder class if specified
            if ($isFolder && !empty($this->css['parent']) && ($level < $this->level || $this->level == 0)) {
                $returnClass .= $hasClass ? ' ' . $this->css['parent'] : $this->css['parent'];
                $hasClass = 1;
            }
            //Set here class if specified
            if (!empty($this->css['here']) && $this->isHere($docId)) {
                $returnClass .= $hasClass ? ' ' . $this->css['here'] : $this->css['here'];
                $hasClass = 1;
            }
            //Set self class if specified
            if (!empty($this->css['self']) && $docId == $modx->documentIdentifier) {
                $returnClass .= $hasClass ? ' ' . $this->css['self'] : $this->css['self'];
                $hasClass = 1;
            }
            //Set class for weblink
            if (!empty($this->css['weblink']) && $type == 'reference') {
                $returnClass .= $hasClass ? ' ' . $this->css['weblink'] : $this->css['weblink'];
                $hasClass = 1;
            }
        }

        return $returnClass;
    }
    
    //Add the specified css & javascript chunks to the page
    function regJsCss() {
        global $modx;
        
        if ($this->debug) {
            $this->debugOutput .= '<h3>Processing Css/Js Chunks</h3>';
        }
        
        if ($this->cssTpl) {
            $cssChunk = $modx->getChunk($this->cssTpl);
            if ($cssChunk) {
                $modx->regClientCSS($cssChunk);
                if ($this->debug) {$this->debugOutput .= '<p>The CSS chunk ' . $this->cssTpl . ' was added to the page.</p>';}
            } else {
                if ($this->debug) {$this->debugOutput .= '<p>The CSS chunk ' . $this->cssTpl . ' was not found.</p>';}
            }
        }
        
        if ($this->jsTpl) {
            $jsChunk = $modx->getChunk($this->jsTpl);
            if ($jsChunk) {
                $modx->regClientStartupScript($jsChunk);
                if ($this->debug) {$this->debugOutput .= '<p>The JS chunk ' . $this->jsTpl . ' was added to the page.</p>';}
            } else {
                if ($this->debug) {$this->debugOutput .= '<p>The JS chunk ' . $this->jsTpl . ' was not found.</p>';}
            }
        }
    }
}
?>
