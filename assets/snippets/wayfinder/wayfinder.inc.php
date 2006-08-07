<?php

/*
 Snippet name: Wayfinder
 Short Desc: builds site navigation
 Version: beta
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
    var $wfDocs = array();
    var $css = array();
    var $cssTpl = FALSE;
    var $jsTpl = FALSE;
    var $rowIdPrefix = FALSE;
    var $placeHolders = array('[+wf.wrapper+]','[+wf.classes+]','[+wf.link+]','[+wf.title+]','[+wf.linktext+]','[+wf.id+]');
    var $ie = "\n";
    var $debugOutput = '<h2>WayFinder Debug Output:</h2>';

    //remove any docs specified with hidemenu
    function filterHidden($var) {
		return (!$var['hidemenu']==1);
	}
	
	function endKey(&$array){
        end($array);
        return key($array);
    }

    //generate the menu
    function buildMenu($parentId,$curLevel = 1) {
        global $modx;
        $output = '';

        $getFields = 'id,menutitle,pagetitle,menuindex,published,hidemenu,parent,isfolder,description,alias,longtitle';
        $resource = $modx->getActiveChildren($parentId,'menuindex','ASC',$getFields);

        if (!$this->ignoreHidden) {
            $resource = array_filter($resource, array($this, "filterHidden"));
        }

        $firstItem = 1;

        if (is_array($resource) && !empty($resource)) {
            $numItems = $this->endKey($resource);
            foreach ($resource as $n => $v) {
                $v['link'] = $modx->makeUrl($v['id']);
                $v['level'] = $curLevel;
                $v['first'] = $firstItem;
                $firstItem = 0;
                if ($n == $numItems) {
                    $v['last'] = 1;
                } else {
                    $v['last'] = 0;
                }

                $useTextField = (empty($v[$this->textOfLinks])) ? 'pagetitle' : "$this->textOfLinks";
                $v['linktext'] = $v[$useTextField];
                $v['title'] = $v[$this->titleOfLinks];

                if ($v['isfolder'] && ($curLevel < $this->level || $this->level == 0) && (!$this->hideSubMenus || in_array($v['id'],$this->parentTree))) {
                    $oldLevel = $curLevel;
                    $subMenu = $this->ie . $this->buildMenu($v['id'],++$curLevel) . $this->ie;
                    $curLevel = $oldLevel;
                } else {
                    $subMenu = '';
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

            $useClass = $this->setItemClass($wrapperClass);
            $phArray = array($output,$useClass);

            $output = str_replace($this->placeHolders,$phArray,$useChunk);

            if ($this->debug) {
                $numDocs = $numItems + 1;
                $this->debugOutput .= '<strong>Nesting Complete</strong> - Previous ' . $numDocs . ' level ' . $curLevel . ' items inserted into ' . $usedTemplate . ' with class ' . $useClass . '<br/>';
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
        } elseif ($resource['isfolder'] && $this->templates['parentRowTpl'] && ($resource['level'] < $this->level || $this->level == 0)) {
            $usedTemplate = 'parentRowTpl';
        } elseif ($resource['level'] > 1 && $this->templates['innerRowTpl']) {
            $usedTemplate = 'innerRowTpl';
        } else {
            $usedTemplate = 'rowTpl';
        }
        
        $useChunk = $this->templates[$usedTemplate];

        $useSub = $subMenu;
        $useClass = $this->setItemClass('rowcls',$resource['id'],$resource['first'],$resource['last'],$resource['level'],$resource['isfolder']);
        
        if ($this->rowIdPrefix) {
            $useId = ' id="' . $this->rowIdPrefix . $resource['id'] . '"';
        } else {
            $useId = '';
        }

        $phArray = array($useSub,$useClass,$resource['link'],$resource['title'],$resource['linktext'],$useId);

        $output .= str_replace($this->placeHolders,$phArray,$useChunk);

        if ($this->debug) {
            $this->debugOutput .= '<strong>Item Processed: (' . $resource['id'] . ') ' . $resource['pagetitle'] . '</strong><br/>
            template: ' . $usedTemplate . ' | class: ' . $useClass . '<br/>
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
                    $this->templates[$n] = '<li[+wf.id+][+wf.classes+]><a href="[+wf.link+]" title="[+wf.title+]">[+wf.linktext+]</a>[+wf.wrapper+]</li>';
                } else {
                    $this->templates[$n] = FALSE;
                }
                if ($this->debug) {
                    $this->debugOutput .= '<p>No chunk found for <strong>' . $n . '</strong> using default.</p>';
                }
            } else {
                $this->templates[$n] = $chunkcheck;
            }
        }
    }

    //determine "you are here"
    function isHere($did) {
        return in_array($did,$this->parentTree);
    }

    //determine style class for current item being processed
    function setItemClass($classType, $docId = 0, $first = 0, $last = 0, $level = 0, $isFolder = 0) {
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
        }

        if ($hasClass) {
            $returnClass = ' class="' . $returnClass . '"';
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
