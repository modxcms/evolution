<?php
/**
 * Qm+ — QuickManager+
 *  
 * @author      Mikko Lammi, www.maagit.fi (based on QuickManager by Urique Dertlian, urique@unix.am)
 * @license     GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @version     1.3.4 updated 3/2/2010                
 */

if(!class_exists('Qm')) {

class Qm {
  var $modx;
  
    //_______________________________________________________
    function Qm(&$modx, $jqpath, $loadmanagerjq, $loadfrontendjq, $noconflictjq, $loadtb, $tbwidth, $tbheight, $hidefields, $hidetabs, $hidesections, $addbutton, $tpltype, $tplid, $custombutton, $managerbutton, $logout, $autohide) {
        $this->modx = $modx;
        
        // Get plugin parameters
        $this->jqpath = $jqpath;
        $this->loadmanagerjq = $loadmanagerjq;
        $this->loadfrontendjq = $loadfrontendjq;
        $this->noconflictjq = $noconflictjq;  
        $this->loadtb = $loadtb;
        $this->tbwidth = $tbwidth;
        $this->tbheight = $tbheight;
        $this->usemm = $usemm;
        $this->hidefields = $hidefields;  
        $this->hidetabs = $hidetabs;  
        $this->hidesections = $hidesections;     
        $this->addbutton = $addbutton;       
        $this->tpltype = $tpltype;       
        $this->tplid = $tplid;
        $this->custombutton = $custombutton;
        $this->managerbutton = $managerbutton;
        $this->logout = $logout;
        $this->autohide = $autohide;           
        
        // Includes
        include_once($this->modx->config['base_path'].'assets/plugins/qm/mcc.class.php');
        
        // Run plugin
        $this->Run();
    }
    
    //_______________________________________________________
    function Run() {
        
        // Include MODx manager language file
        global $_lang;
		
		// Get manager language
        $manager_language = $this->modx->config['manager_language'];
        	
		// Include_once the language file
        if(!isset($manager_language) || !file_exists(MODX_MANAGER_PATH."includes/lang/".$manager_language.".inc.php")) {
            $manager_language = "english"; // if not set, get the english language file.
        }
        // Include default language
        include_once MODX_MANAGER_PATH."includes/lang/english.inc.php";
        
        // Include user language
        if($manager_language!="english" && file_exists(MODX_MANAGER_PATH."includes/lang/".$manager_language.".inc.php")) {
            include_once MODX_MANAGER_PATH."includes/lang/".$manager_language.".inc.php";
        }
        
        // Get event
        $e = $this->modx->Event;
        
        // Run plugin based on event
        switch ($e->name) {
            
            // Save document
            case 'OnDocFormSave':
                
                // Saving process for Qm only
                if(intval($_REQUEST['quickmanager']) == 1) {
            
                    $id = $e->params['id'];
                    $key = $id;
                    
                    // Normal saving document procedure stops to redirect => Before redirecting secure documents and clear cache
                    
                    // Secure web documents - flag as private (code from: manager/processors/save_content.processor.php)
    		        include $this->modx->config['base_path']."manager/includes/secure_web_documents.inc.php";
    		        secureWebDocument($key);
    
            		// Secure manager documents - flag as private (code from: manager/processors/save_content.processor.php)
            		include $this->modx->config['base_path']."manager/includes/secure_mgr_documents.inc.php";
            		secureMgrDocument($key);
                    
                    // Clear cache
                    include_once $this->modx->config['base_path']."manager/processors/cache_sync.class.processor.php";
                    $sync = new synccache();
                    $sync->setCachepath($this->modx->config['base_path']."assets/cache/");
                    $sync->setReport(true);
                    $sync->emptyCache();
                    
                    // Redirect to clearer page which refreshes parent window and closes modal box frame
                    $this->modx->sendRedirect($this->modx->config['base_url'].'assets/plugins/qm/close.php?id='.$id, 0, 'REDIRECT_HEADER', 'HTTP/1.1 301 Moved Permanently');               
                }
                
                break;
            
            // Display page in front-end
            case 'OnWebPagePrerender':
            
                // If logged in manager but not in manager preview show control buttons
                if(isset($_SESSION['mgrValidated']) && $_REQUEST['z'] != 'manprev') {
                
                    $output = &$this->modx->documentOutput;
                    
                    // If logout break here
                    if(isset($_REQUEST['logout'])) {
                        $this->Logout();
                        break;
                    }
                    
                    $userID = $_SESSION['mgrInternalKey'];
                    $docID = $this->modx->documentIdentifier;
                    $doc = $this->modx->getDocument($docID);
                    
                    // Edit button
                    
                    $editButton = '
                    <li>
                    <a class="qmButton qmEdit colorbox" href="'.$this->modx->config['site_url'].'manager/index.php?a=27&amp;id='.$docID.'&amp;quickmanager=1"><span> '.$_lang['edit_resource'].'</span></a>
                    </li>
                    ';
                    
                    // Check if user has manager access to current document
                    $access = $this->checkAccess();
                    
                    // Does user have permissions to edit document   
                    if($access) $controls .= $editButton;
                    
                    if ($this->addbutton == 'true' && $access) {                    
                        // Add button
                        $addButton = '
                        <li>
                        <a class="qmButton colorbox" href="'.$this->modx->config['site_url'].'manager/index.php?a=4&amp;pid='.$docID.'&amp;quickmanager=1">'.$_lang['create_resource_here'].'</a>
                        </li>
                        ';
                        
                        // Does user have permissions to add document
                        if($this->modx->hasPermission('new_document')) $controls .= $addButton;        
                    }            
                    
                    // Custom add buttons if not empty and enough permissions
                    if ($this->custombutton != '') {  
                                                            
                        $buttons = explode("||", $this->custombutton); // Buttons are divided by "#"
                        
                        // Parse buttons
                        foreach($buttons as $key => $field) { 
                            $field = substr($field, 1, -1); // Trim "'" from beginning and from end
                            $buttonParams = explode("','", $field); // Button params are divided by "','"
                            
                            $buttonTitle = $buttonParams[0];
                            $buttonAction = $buttonParams[1]; // Contains URL if this is not add button
                            $buttonParentId = $buttonParams[2]; // Is empty is this is not add button
                            $buttonTplId = $buttonParams[3];
                            
                            // Button visible for all
                            if ($buttonParams[4] == '') {
                                $showButton = TRUE;
                            }
                            // Button is visible for specific user roles
                            else {
                                $showButton = FALSE;
                            
                                // Get user roles the button is visible for
                                $buttonRoles = explode(",", $buttonParams[4]); // Roles are divided by ','
                                                            
                                // Check if user role is found
                                foreach($buttonRoles as $key => $field) { 
                                    if ($field == $_SESSION['mgrRole']) {
                                        $showButton = TRUE;
                                    }
                                }
                            }
                            
                            // Show custom button
                            if ($showButton) {
                                switch ($buttonAction)
                                {
                                    case 'new':
                                        $customButton = '
                                        <li>
                                        <a class="qmButton colorbox" href="'.$this->modx->config['site_url'].'manager/index.php?a=4&amp;pid='.$buttonParentId.'&amp;quickmanager=1&amp;customaddtplid='.$buttonTplId.'">'.$buttonTitle.'</a>
                                        </li>
                                        ';
                                    break;
                                
                                    case 'link':
                                        $customButton  = '
                                        <li>
                                        <a class="qmButton" href="'.$buttonParentId.'" >'.$buttonTitle.'</a>
                                        </li>
                                        ';    
                                    break;
                                    
                                    case 'modal':
                                        $customButton  = '
                                        <li>
                                        <a class="qmButton colorbox" href="'.$buttonParentId.'" >'.$buttonTitle.'</a>
                                        </li>
                                        ';   
                                    break;
                                }
                                $controls .= $customButton;  
                            }                                             
                        }                                   
                    } 
                    
                    // Not implemented yet
                    //$delButton  = '<a class="button delete" title="'.$doc['pagetitle'].'&raquo; '.$_lang['delete_document'].'" href="#" onclick="if(confirm(\'`'.$doc['pagetitle'].'`\n\n'.$_lang['confirm_delete_document'].'\')==true) document.location.href=\''.$this->modx->config['site_url'].'manager/index.php?a=4&id='.$docID.'\';return false;">'.$_lang['delete_document'].'</a>';
                    //if($this->modx->hasPermission('delete_document')) $controls.=$delButton;
                    
                    // Go to Manager button
                    if ($this->managerbutton == 'true') {
                        $managerButton  = '
                        <li>
                        <a class="qmButton" title="'.$_lang['manager'].'" href="'.$this->modx->config['site_url'].'manager/" >'.$_lang['manager'].'</a>
                        </li>
                        ';
                        $controls .= $managerButton;
                    }
                    
                    // Logout button
                    $logout = $this->modx->config['site_url'].'manager/index.php?a=8&amp;quickmanager=logout&amp;logoutid='.$docID;     
                    $logoutButton  = '
                    <li>
                    <a id="qmLogout" class="qmButton" title="'.$_lang['logout'].'" href="'.$logout.'" >'.$_lang['logout'].'</a>
                    </li>
                    ';
                    $controls .= $logoutButton;
                    
                    // Add action buttons
                    $editor = '
                    <div id="qmEditorClosed"></div>
                    
					<div id="qmEditor">
					
                    <a id="qmClose" class="qmButton qmClose" href="#" onclick="javascript: return false;">X</a>
                    
                    <ul>
                    <li><a id="qmLogoClose" class="qmClose" href="#" onclick="javascript: return false;"></a></li>
                    '.$controls.'
                    </ul>
					</div>';
					
                    $css = '
                    <link rel="stylesheet" type="text/css" href="'.$this->modx->config['site_url'].'assets/plugins/qm/css/style.css" />
                    <!--[if IE]><link rel="stylesheet" type="text/css" href="'.$this->modx->config['site_url'].'assets/plugins/qm/css/ie.css" /><![endif]-->
                    <!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="'.$this->modx->config['site_url'].'assets/plugins/qm/css/ie7.css" /><![endif]-->
                    ';
        
                    // Autohide toolbar? Default: true
                    if ($this->autohide == 'false') {
                        $css .= '
                        <style type="text/css">
                        #qmEditor, #qmEditorClosed { top: 0px; }
                        </style>
                        ';
                    }
        
                    // Insert jQuery and ColorBox in head if needed
                    if ($this->loadfrontendjq == 'true') $head .= '<script src="'.$this->modx->config['site_url'].$this->jqpath.'" type="text/javascript"></script>';
                    if ($this->loadtb == 'true') {
                        $head .= '
                        <link type="text/css" media="screen" rel="stylesheet" href="'.$this->modx->config['site_url'].'assets/plugins/qm/css/colorbox.css" />
                        
                        <style type="text/css">
                        .cboxIE #cboxTopLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.$this->modx->config['site_url'].'assets/plugins/qm/css/images/internet_explorer/borderTopLeft.png, sizingMethod=\'scale\');}
                        .cboxIE #cboxTopCenter{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.$this->modx->config['site_url'].'assets/plugins/qm/css/images/internet_explorer/borderTopCenter.png, sizingMethod=\'scale\');}
                        .cboxIE #cboxTopRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.$this->modx->config['site_url'].'assets/plugins/qm/css/images/internet_explorer/borderTopRight.png, sizingMethod=\'scale\');}
                        .cboxIE #cboxBottomLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.$this->modx->config['site_url'].'assets/plugins/qm/css/images/internet_explorer/borderBottomLeft.png, sizingMethod=\'scale\');}
                        .cboxIE #cboxBottomCenter{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.$this->modx->config['site_url'].'assets/plugins/qm/css/images/internet_explorer/borderBottomCenter.png, sizingMethod=\'scale\');}
                        .cboxIE #cboxBottomRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.$this->modx->config['site_url'].'assets/plugins/qm/css/images/internet_explorer/borderBottomRight.png, sizingMethod=\'scale\');}
                        .cboxIE #cboxMiddleLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.$this->modx->config['site_url'].'assets/plugins/qm/css/images/internet_explorer/borderMiddleLeft.png, sizingMethod=\'scale\');}
                        .cboxIE #cboxMiddleRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.$this->modx->config['site_url'].'assets/plugins/qm/css/images/internet_explorer/borderMiddleRight.png, sizingMethod=\'scale\');}
                        </style>
                        
                        <script type="text/javascript" src="'.$this->modx->config['site_url'].'assets/plugins/qm/js/jquery.colorbox-min.js"></script>
                        ';
                    }
                    
                    // Insert ColorBox jQuery definitions for QuickManager+
                    $head .= '
                    <script type="text/javascript">
                    ';
                    
                    // jQuery in noConflict mode 
                    if ($this->noconflictjq == 'true')
                    {
                        $head .= '
                    	var $j = jQuery.noConflict();
                    	$j(document).ready(function($)
                    	';
                    	
                    	$jvar = 'j';
                    }
                    	
                    // jQuery in normal mode 
                    else { 	
                        $head .= '$(document).ready(function($)';
                        
                        $jvar = '';
                    }
                        
                    $head .= '    
                        {                      
                    		$("a.colorbox").colorbox({width:"'.$this->tbwidth.'", height:"'.$this->tbheight.'", iframe:true, overlayClose:false});
                    	
                        	// Bindings
                        	$().bind("cbox_open", function(){
                                $("body").css({"overflow":"hidden"});
                                $("html").css({"overflow":"hidden"});
                                $("#qmEditor").css({"display":"none"});
                            });
                        	$().bind("cbox_closed", function(){
                                $("body").css({"overflow":"auto"});
                                $("html").css({"overflow":"auto"});
                                $("#qmEditor").css({"display":"block"});
                                // Remove manager lock by going to home page
                                $'.$jvar.'.ajax({ type: "GET", url: "'.$this->modx->config['site_url'].'manager/index.php?a=2" });
                            });                  
                                                        						                            
                            // Hide QM+ if cookie found
                            if (getCookie("hideQM") == 1)
                            {
                                $("#qmEditor").css({"display":"none"});
                                $("#qmEditorClosed").css({"display":"block"});    
                            }
                            
                            // Hide QM+
                            $(".qmClose").click(function () {
                                $("#qmEditor").hide("normal");
                                $("#qmEditorClosed").show("normal");
                                document.cookie = "hideQM=1; path=/;";
                            });
                            
                            // Show QM+
                            $("#qmEditorClosed").click(function () {
                                {
                                    $("#qmEditorClosed").hide("normal");
                                    $("#qmEditor").show("normal");
                                    document.cookie = "hideQM=0; path=/;";
                                }
                            });

                        });
                        
                        function getCookie(cookieName)
                        {
                            var results = document.cookie.match ( "(^|;) ?" + cookieName + "=([^;]*)(;|$)" );
    
                            if (results) return (unescape(results[2]));
                            else return null;
                        }

                    </script>
                    ';
                    
                    // Insert QM+ css in head
                    $head .= $css;
        
                    // Place Qm head information in head, just before </head> tag
                    $output = preg_replace('~(</head>)~i', $head . '\1', $output);
        
                    // Insert editor toolbar right after <body> tag
                    $output = preg_replace('~(<body[^>]*>)~i', '\1' . $editor, $output);
                }
                
                break;
            
            // Edit document in ThickBox frame (MODx manager frame)
            case 'OnDocFormPrerender':
                                        
                // If there is Qm call, add control buttons and modify to edit document page
                if (intval($_REQUEST['quickmanager']) == 1) {
                
                    global $content;
                    
                    // Set template for new document, action = 4
                    if(intval($_GET['a']) == 4) {    
                        
                        // Custom add button
                        if (isset($_GET['customaddtplid'])) {
                            // Set template
                            $content['template'] = intval($_GET['customaddtplid']);   
                        }
                        
                        // Normal add button
                        else {                                     
                            switch ($this->tpltype) {
                                // Template type is parent
                                case 'parent':
                                    // Get parent document id
                                    $pid = $content['parent'] ? $content['parent'] : intval($_REQUEST['pid']);
            
                                    // Get parent document
                                    $parent = $this->modx->getDocument($pid);
                        
                                    // Set parent template
                                    $content['template'] = $parent['template'];
                                
                                    break;
                                
                                // Template is specific id
                                case 'id':
                                    $content['template'] = $this->tplid;
                                
                                    break;
                                
                                // Template is inherited by Inherit Selected Template plugin
                                case 'selected':
                                    // Get parent document id
                                    $pid = $content['parent'] ? $content['parent'] : intval($_REQUEST['pid']);
                                    
                                    // Get inheritTpl TV
                                    $tv = $this->modx->getTemplateVar("inheritTpl", "", $pid);
                                    
                                    // Set template to inherit
                                    if ($tv['value'] != '') $content['template'] = $tv['value'];
                                    else $content['template'] = $this->modx->config['default_template'];
                                
                                    break;
                            }
                        }
                    }

                    // Manager control class
                    $mc = new Mcc();
                
                    // Hide default manager action buttons
                    $mc->addLine('$("#actions").hide();');
    
                    // Get MODx theme
					$qm_theme = $this->modx->config['manager_theme'];
					
					// Get doc id
					$doc_id = intval($_REQUEST['id']);
					
					// Get jQuery conflict mode
					if ($this->noconflictjq == 'true') $jq_mode = '$j';
					else $jq_mode = '$';
					
					// Add action buttons
                    $mc->addLine('var controls = "<div style=\"padding:4px 0;position:fixed;top:10px;right:-10px;z-index:1000\" id=\"qmcontrols\" class=\"actionButtons\"><ul><li><a href=\"#\" onclick=\"setBaseUrl(\''.$this->modx->config['base_url'].'\'); documentDirty=false;document.mutate.save.click();return false;\"><img src=\"media/style/'.$qm_theme.'/images/icons/save.png\" />'.$_lang['save'].'</a></li><li><a href=\"#\" onclick=\"parent.'.$jq_mode.'.fn.colorbox.close(); return false;\"><img src=\"media/style/'.$qm_theme.'/images/icons/stop.png\"/>'.$_lang['cancel'].'</a></li></ul></div>";');
                    
                    // Modify head
                    $mc->head = '<script type="text/javascript">document.body.style.display="none";</script>';
                    if ($this->loadmanagerjq == 'true') $mc->head .= '<script src="'.$this->modx->config['site_url'].$this->jqpath.'" type="text/javascript"></script>';
    
                    // Add control button
                    $mc->addLine('$("body").prepend(controls);');

                    // Hide fields to from front-end editors
                    if ($this->hidefields != '') {
                        $hideFields = explode(",", $this->hidefields);
                        
                        foreach($hideFields as $key => $field) {
                            $mc->hideField($field); 
                        }
                    }
                              
                    // Hide tabs to from front-end editors
                    if ($this->hidetabs != '') {
                        $hideTabs = explode(",", $this->hidetabs);
                        
                        foreach($hideTabs as $key => $field) {
                            $mc->hideTab($field); 
                        }
                    }
                    
                    // Hide sections from front-end editors
                    if ($this->hidesections != '') {
                        $hideSections = explode(",", $this->hidesections);
                        
                        foreach($hideSections as $key => $field) {
                            $mc->hideSection($field); 
                        }
                    }
                    
                    // Set active doc script (needed if alias is changed)
                    $setActiveDoc = '
                    <script type="text/javascript">
                    function setBaseUrl(baseUrl)
                    {
                        // Set base url
                        document.cookie = "baseUrlQM=" + baseUrl + "; path=/;";
                    }
                    </script>
                    ';
                                              
                    // Hidden field to verify that QM+ call exists
                    $hiddenField = '<input type="hidden" name="quickmanager" value="1" />';
                    
                    // Output
                    $e->output($mc->Output().$setActiveDoc.$hiddenField);
                }
                
            break;
            
            // Where to logout
            case 'OnManagerLogout':
                // Only if cancel editing the document and QuickManager is in use
                if ($_REQUEST['quickmanager'] == 'logout') {
                    // Redirect to document id
                    if ($this->logout != 'manager') {
                        $this->modx->sendRedirect($this->modx->makeUrl($_REQUEST['logoutid']), 0, 'REDIRECT_HEADER', 'HTTP/1.1 301 Moved Permanently');
                    }
                }
            
            break;
        }
    }
    
    // Check if user has manager access permissions to current document 
    //_______________________________________________________
    function checkAccess() {
        $access = FALSE;

        // If user is admin (role = 1)
        if ($_SESSION['mgrRole'] == 1) $access = TRUE;
        
        else {
            $docID = $this->modx->documentIdentifier;
                   
            // Database table
            $table= $this->modx->getFullTableName("document_groups");
            
            // Check if current document is assigned to one or more doc groups
            $sql= "SELECT id FROM {$table} WHERE document={$docID}";
            $result= $this->modx->db->query($sql);
            $rowCount= $this->modx->recordCount($result);
            
            // If document is assigned to one or more doc groups, check access
            if ($rowCount >= 1) {
            
                // Get document groups for current user
                $mrgDocGroups = $_SESSION['mgrDocgroups'];
                if (!empty($mrgDocGroups))  {
                    $docGroup= implode(",", $mrgDocGroups); 
                    
                    // Check if user has access to current document 
                    $sql= "SELECT id FROM {$table} WHERE document = {$docID} AND document_group IN ({$docGroup})";
                    $result= $this->modx->db->query($sql);
                    $rowCount = $this->modx->recordCount($result);
                    
                    if ($rowCount >= 1) $access = TRUE;
                }
                
                else $access = FALSE;
            }
            
            else $access = TRUE;
        }
        
        return $access;
    }
}
}
?>