<?php
/**
 * QuickManager+
 *  
 * @author      Mikko Lammi, www.maagit.fi, updated by Dmi3yy and Nicola1971 
 * @license     GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @version     1.5.9 updated 28/09/2017
 */

if(!class_exists('Qm')) {

class Qm {
  var $modx;
  
    //_______________________________________________________
    function __construct(&$modx, $jqpath='', $loadmanagerjq='', $loadfrontendjq='', $noconflictjq='', $loadfa='', $loadtb='', $tbwidth='', $tbheight='', $hidefields='', $hidetabs='', $hidesections='', $addbutton='', $tpltype='', $tplid='', $custombutton='', $managerbutton='', $logout='', $autohide='', $position='', $editbuttons='', $editbclass='', $newbuttons='', $newbclass='', $tvbuttons='', $tvbclass='', $buttonStyle='', $removeBg='') {
        $this->modx = $modx;
        
        // Get plugin parameters
        $this->jqpath = $jqpath;
        $this->loadmanagerjq = $loadmanagerjq;
        $this->loadfrontendjq = $loadfrontendjq;
        $this->noconflictjq = $noconflictjq;
        $this->loadfa = $loadfa;
        $this->loadtb = $loadtb;
        $this->tbwidth = $tbwidth;
        $this->tbheight = $tbheight;
        $this->usemm = null;
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
        $this->position = $position;
        $this->editbuttons = $editbuttons;
        $this->editbclass = $editbclass;
        $this->newbuttons = $newbuttons;
        $this->newbclass = $newbclass;
        $this->tvbuttons = $tvbuttons;
        $this->tvbclass = $tvbclass;
        $this->buttonStyle = $buttonStyle;
        $this->removeBg = $removeBg;
        
        // Includes
        include_once($this->modx->config['base_path'].'assets/plugins/qm/mcc.class.php');
        
        // Run plugin
        $this->Run();
    }
    
    //_______________________________________________________
    function Run() {
        
        // Include MODX manager language file
        global $_lang;
		
		// Get manager language
        $manager_language = $this->modx->config['manager_language'];
        
        // Individual user language setting (if set)
        if (isset($_SESSION['mgrUsrConfigSet']['manager_language'])) $manager_language = $_SESSION['mgrUsrConfigSet']['manager_language'];
	
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
        $e = &$this->modx->Event;
        
        // Run plugin based on event
        switch ($e->name) {
            
            // Save document
            case 'OnDocFormSave':
                
                // Saving process for Qm only
                if(intval($_REQUEST['quickmanager']) == 1) {
            
                    $id = $e->params['id'];
                    $key = $id;
                    
                    // Normal saving document procedure stops to redirect => Before redirecting secure documents and clear cache
                    
                    // Secure web documents - flag as private (code from: processors/save_content.processor.php)
    		        include $this->modx->config['site_manager_path']."includes/secure_web_documents.inc.php";
    		        secureWebDocument($key);
    
            		// Secure manager documents - flag as private (code from: processors/save_content.processor.php)
            		include $this->modx->config['site_manager_path']."includes/secure_mgr_documents.inc.php";
            		secureMgrDocument($key);
                    
                    // Clear cache
                    $this->modx->clearCache('full');
                    
                    // Different doc to be refreshed than the one we are editing?
                    if (isset($_POST['qmrefresh'])) {
                        $id = intval($_POST['qmrefresh']);
                    }
                    
                    // Redirect to clearer page which refreshes parent window and closes modal box frame
                    if ($this->modx->config['friendly_urls'] == 1){
                        $this->modx->sendRedirect($this->modx->makeUrl($id).'?quickmanagerclose=1', 0, 'REDIRECT_HEADER', 'HTTP/1.1 301 Moved Permanently'); 
                    }else{
                        $this->modx->sendRedirect($this->modx->makeUrl($id).'&quickmanagerclose=1', 0, 'REDIRECT_HEADER', 'HTTP/1.1 301 Moved Permanently');    
                    }
                    
                }
                
                break;
            
            // Display page in front-end
            case 'OnWebPagePrerender':
    
                // Get document id
                $docID = $this->modx->documentIdentifier;
                
                // Get page output
                $output = &$this->modx->documentOutput;
                
                // Close modal box after saving (previously close.php)
                if (isset($_GET['quickmanagerclose'])) {
                    
                    // Set url to refresh
                    $url = $this->modx->makeUrl($docID, '', '', 'full');
                    
                    $output = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body onload="javascript: parent.location.href = \''.$url.'\';">
</body>
</html>
';
                    break;
                }
                
                // QM+ TV edit
                if(intval($_GET['quickmanagertv'] == 1) && $_GET['tvname'] != '' && $this->tvbuttons == 'true') {
                    
                    $tvName = '';
                    $locked = FALSE;
                    $access = FALSE;
                    $save = 0;
                    $imagePreview = '';
                    
                    // Includes
                    include_once(MODX_MANAGER_PATH.'includes/tmplvars.inc.php');
                    include_once(MODX_MANAGER_PATH.'includes/tmplvars.commands.inc.php');
                    include_once(MODX_MANAGER_PATH.'includes/tmplvars.format.inc.php');
                    
                    // Get save status
                    if (isset($_POST['save'])) $save = intval($_POST['save']); 
                    
                    // Get TV name
                    if (preg_match('/^([^\\"\'\(\)<>!?]+)/i', $_GET['tvname'])) $tvName = $_GET['tvname'];
                    
                    // Get TV array
                    $tv = $this->modx->getTemplateVar($tvName, '*', $docID);
                    
                    // Handle default TVs
                    switch ($tvName) {
                        case 'pagetitle'    : $tv['type'] = 'text';     $tv['caption'] = $this->getDefaultTvCaption($tvName); $access = TRUE; break;     
                        case 'longtitle'    : $tv['type'] = 'text';     $tv['caption'] = $this->getDefaultTvCaption($tvName); $access = TRUE; break;
                        case 'description'  : $tv['type'] = 'text';     $tv['caption'] = $this->getDefaultTvCaption($tvName); $access = TRUE; break;
                        case 'content'      : $tv['type'] = 'richtext'; $tv['caption'] = $this->getDefaultTvCaption($tvName); $access = TRUE; break;
                        case 'menutitle'    : $tv['type'] = 'text';     $tv['caption'] = $this->getDefaultTvCaption($tvName); $access = TRUE; break;
                        case 'introtext'    : $tv['type'] = 'textarea'; $tv['caption'] = $this->getDefaultTvCaption($tvName); $access = TRUE; break;
                    }
                    
                    // Check TV access
                    if (!$access) { $access = $this->checkTvAccess($tv['id']); }
                    
                    // User can access TV
                    if ($access) {
                        
                        // Show TV form
                        if ($save == 0) {
                        
                            // Check is document locked? Someone else is editing the document...  //$_lang['lock_msg']
                            if ($this->checkLocked()) $locked = TRUE;
                                
                            // Set document locked
                            else $this->setLocked(1);                       
                            
                            // Handle RTE
                            if($tv['type'] == 'richtext') {                   
                                // Invoke OnRichTextEditorInit event
                                $eventOutput = $this->modx->invokeEvent("OnRichTextEditorInit", array('editor'=>$this->modx->config['which_editor'], 'elements'=>array('tv'.$tvName)));
                                
                                if(is_array($eventOutput)) {
                                    $editorHtml = implode("",$eventOutput);
                                }
                            }
                            
                            // Render TV html
                            $tvHtml = renderFormElement($tv['type'], $tv['name'], $tv['default_text'], $tv['elements'], $tv['value']);
                            
                            // Get jQuery conflict mode
    					    if ($this->noconflictjq == 'true') $jq_mode = '$j';
    					    else $jq_mode = '$';
					    }
                        
                        // Save TV
                        else {
                            // Remove document locked
                            $this->setLocked(0); 
                            
                            // Save TV
                            $this->saveTv($tvName);
                        }
                        
                        // Page output: header
                        $output = '
                        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
                        <title></title>
                        <link rel="stylesheet" type="text/css" href="'.$this->modx->config['site_url'].'assets/plugins/qm/css/style.css" />
                        <!--[if IE]><link rel="stylesheet" type="text/css" href="'.$this->modx->config['site_url'].'assets/plugins/qm/css/ie.css" /><![endif]-->
                        <script src="'.$this->modx->config['site_url'].$this->jqpath.'" type="text/javascript"></script>
                        </head>
                        ';
                        
                        // Page output: TV form
                        if ($save == 0) {
                            $output .= ' 
                            <body id="qm-tv-body">
                            ';
                            
                            // Document is locked message
                            if ($locked) {
                                $output .= '
                                <h1>'.$_lang['locked'].'</h1>
                                <div id="qm-tv-description">'.$_lang['lock_msg'].'</div> 
                                ';   
                            }
                            
                            // Normal form
                            else {
                                // Image preview
                                if ($tv['type'] == 'image') {
                                    $imagePreview = '
                                    <div id="qm-tv-image-preview"><img class="qm-tv-image-preview-drskip qm-tv-image-preview-skip" src="'.$this->modx->config['site_url'].$tv['value'].'" alt="" /></div>
                                    <script type="text/javascript" charset="UTF-8">
                                    
                                    $(document).ready(function() {
                                        
                                        var previewImage = "#tv'.$tvName.'";
                                        var siteUrl = "'.$this->modx->config['site_url'].'";
                                        
                                        OriginalSetUrl = SetUrl; // Copy the existing Image browser SetUrl function						
                            			SetUrl = function(url, width, height, alt) {	// Redefine it to also tell the preview to update
                            				OriginalSetUrl(url, width, height, alt);
                            				$(previewImage).trigger("change");
                            			}
                                        
                                        $(previewImage).change(function() {
                                            $("#qm-tv-image-preview").empty();
                                             if ($(previewImage).val()!="" ){
                                                 $("#qm-tv-image-preview").append("<img class=\"qm-tv-image-preview-drskip qm-tv-image-preview-skip\" src=\"" + siteUrl + $(previewImage).val()  + "\" alt=\"\" />");
                                             }
                                             else{
                                                 $("#qm-tv-image-preview").append("");
                                             }
                                         });
                                    });
 
                                    </script>
                                    ';
                                }
                                $amp = ($this->modx->config['friendly_urls'] == 1) ? '?' : '&';
                                $output .= ' 
                                <form id="qm-tv-form" name="mutate" method="post" enctype="multipart/form-data" action="'.$this->modx->makeUrl($docID).$amp.'quickmanagertv=1&amp;tvname='.$tvName.'">
                                <input type="hidden" name="tvid" value="'.$tv['id'].'" />
                                <input id="save" type="hidden" name="save" value="1" />
                                    
                                <div id="qm-tv-actions">
                                <div class="qm-cancel"><a href="#" onclick="parent.'.$jq_mode.'.fn.colorbox.close(); return false;"><span>'.$_lang['cancel'].'</span></a></div>
                                <div class="qm-save"><a href="#" onclick="document.forms[\'mutate\'].submit(); return false;"><span>'.$_lang['save'].'</span></a></div>
                                </div>
                                
                                <h1>'.$tv['caption'].'</h1>
                                
                                <div id="qm-tv-description">'.$tv['description'].'</div>
                                
                                <div id="qm-tv-tv" class="qm-tv-'.$tv['type'].'">
                                '.$tvHtml.'
                                </div>
                                
                                '.$imagePreview.'
                                
                                </form>
                                '.$editorHtml.'
                                ';
                            }
                        }
                        
                        // Page output: close modal box and refresh parent frame
                        else $output .= '<body onload="parent.location.reload();">'; 
                        
                        // Page output: footer
                        $output .= '
                        </body>
                        </html>
                        ';  
                    }
                    
                    else {
                        $output = 'Error: Access denied.'; 
                    }
                }
                
                // QM+ with toolbar
                else {

                    if(isset($_SESSION['mgrValidated']) && $_REQUEST['z'] != 'manprev') {
                        
                        // If logout break here
                        if(isset($_REQUEST['logout'])) {
                            $this->Logout();
                            break;
                        }
                        
                        $userID = $_SESSION['mgrInternalKey'];
                        
                        // Add ID
                        $controls .= '<li class="qmId"><span class="textid">ID: </span>'.$docID.'</li>';
                        // Go to Manager button
                        if ($this->managerbutton == 'true') {
                            $managerButton  = '
                            <li class="qmManager">
                            <a class="qmButton qmManager" title="'.$_lang['manager'].'" href="'.$this->modx->config['site_manager_url'].'" ><i class="fa fa-2x fa-fw fa-home butticon"></i><span class="butttext">'.$_lang['manager'].'</span></a>
                            </li>
                            ';
                            $controls .= $managerButton;
                        }
                        // Edit button
                        
                        $editButton = '
                        <li class="qmEdit">
                        <a class="qmButton qmEdit colorbox primary" href="'.$this->modx->config['site_manager_url'].'index.php?a=27&amp;id='.$docID.'&amp;quickmanager=1"><i class="fa fa-2x fa-fw fa-pencil-square-o butticon"></i><span class="butttext"> '.$_lang['edit_resource'].'</span></a>
                        </li>
                        ';
                        
                        // Check if user has manager access to current document
                        $access = $this->checkAccess();
                        
                        // Does user have permissions to edit document   
                        if($access) $controls .= $editButton;
                        
                        if ($this->addbutton == 'true' && $access) {                    
                            // Add button
                            $addButton = '
                            <li class="qmAdd">
                            <a class="qmButton qmAdd colorbox" href="'.$this->modx->config['site_manager_url'].'index.php?a=4&amp;pid='.$docID.'&amp;quickmanager=1"><i class="fa fa-2x fa-fw fa-file butticon"></i><span class="butttext">'.$_lang['create_resource_here'].'</span></a>
                            </li>
                            ';
                            
                            // Does user have permissions to add document
                            if($this->modx->hasPermission('new_document')) $controls .= $addButton;        
                        }            
                        
                        // Custom add buttons if not empty and enough permissions
                        if ($this->custombutton != '') {  
                            
                            // Replace [*id*] with current doc id
                            $this->custombutton = str_replace("[*id*]", $docID, $this->custombutton); 
                            
                            // Handle [~id~] links
                            $this->custombutton = $this->modx->rewriteUrls($this->custombutton);
                                                                
                            $buttons = explode("||", $this->custombutton); // Buttons are divided by "||"
                            
                            // Custom buttons class index
                            $i = 0;
                            
                            // Parse buttons
                            foreach($buttons as $key => $field) {
                                $i++;
                             
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
                                $buttonIcon = $buttonParams[5];
                                // Show custom button
                                if ($showButton) {
                                    switch ($buttonAction)
                                    {
                                        case 'new':
                                            $customButton = '
                                            <li class="qm-custom-'.$i.' qmCustom">
                                            <a class="qmButton qmCustom colorbox" href="'.$this->modx->config['site_manager_url'].'index.php?a=4&amp;pid='.$buttonParentId.'&amp;quickmanager=1&amp;customaddtplid='.$buttonTplId.'"><i class="fa fa-2x fa-fw fa-'.$buttonIcon.' butticon"></i><span class="butttext">'.$buttonTitle.'</span></a>
                                            </li>
                                            ';
                                        break;
                                    
                                        case 'link':
                                            $customButton  = '
                                            <li class="qm-custom-'.$i.' qmCustom">
                                            <a class="qmButton qmCustom" href="'.$buttonParentId.'" ><i class="fa fa-2x fa-fw fa-'.$buttonIcon.' butticon"></i><span class="butttext">'.$buttonTitle.'</span></a>
                                            </li>
                                            ';    
                                        break;
                                        
                                        case 'modal':
                                            $customButton  = '
                                            <li class="qm-custom-'.$i.' qmCustom">
                                            <a class="qmButton qmCustom colorbox" href="'.$buttonParentId.'" ><i class="fa fa-2x fa-fw fa-'.$buttonIcon.' butticon"></i><span class="butttext">'.$buttonTitle.'</span></a>
                                            </li>
                                            ';   
                                        break;
                                    }
                                    $controls .= $customButton;  
                                }                                             
                            }                                   
                        } 
                          

                        
                        // Logout button
                        $logout = $this->modx->config['site_manager_url'].'index.php?a=8&amp;quickmanager=logout&amp;logoutid='.$docID;
                        $logoutButton  = '
                        <li class="qmLogout">
                        <a id="qmLogout" class="qmButton qmLogout" title="'.$_lang['logout'].'" href="'.$logout.'" ><i class="fa fa-2x fa-fw fa-sign-out butticon"></i><span class="butttext">'.$_lang['logout'].'</span></a>
                        </li>
                        ';
                        
                        $controls .= $logoutButton;
                        
                        $username = $_SESSION['mgrShortname'];
                        // Add action buttons
                        $editor = '
                        <div id="qmEditorClosed"></div>
                        
    					<div id="qmEditor">
    					
                        <ul>
                        <li id="qmUser">
                        <span class="qmUser"> <span class="butttext">'.$username.'</span> <i class="fa fa-2x fa-fw fa-user-circle usericon"></i></span>
                        </li>
                        
                        <li><a id="qmLogoClose" class="qmClose" href="#" onclick="javascript: return false;"></a></li>
                        '.$controls.'
                        </ul>
    					</div>';
    					
                        $MGR_DIR = $this->modx->getManagerPath( );
                        $css = '
                        <link rel="stylesheet" type="text/css" href="'.$this->modx->config['site_url'].'assets/plugins/qm/css/style.css" />';
                    
                        $css .= '
                        <!--[if IE]><link rel="stylesheet" type="text/css" href="'.$this->modx->config['site_url'].'assets/plugins/qm/css/ie.css" /><![endif]-->
                        ';
                                                
                        // font-awesome
                        if ($this->loadfa == 'true') {
                        $css .= '
                        <link rel="stylesheet" type="text/css" href="'.$MGR_DIR.'media/style/common/font-awesome/css/font-awesome.min.css" />
                            ';
                        }
                        // Buttons Styles
                        if ($this->buttonStyle == 'actionButtons') {
                            $css .= '
                            <link rel="stylesheet" type="text/css" href="'.$this->modx->config['site_url'].'assets/plugins/qm/css/actionButtons.css" />
                            ';
                        }
                        else
                        if ($this->buttonStyle == 'navButtons') {
                            $css .= '
                            <link rel="stylesheet" type="text/css" href="'.$this->modx->config['site_url'].'assets/plugins/qm/css/navButtons.css" />
                            ';
                        }
                        // Top toolbar Autohide false
                        if (($this->autohide == 'false') && ($this->position == 'top')) {
                            $css .= '
                            <style type="text/css">
                            #qmEditor, #qmEditorClosed { left:0; top: 0px; width: 100%;}
                            #qmEditor:hover, #qmEditorClosed:hover {top: 0px;}
                            .butticon {display:inline}
                            @media only screen and (max-width: 728px) {
                            .butttext {display:none}
                            #qmUser {display:none}
                            }
                            @media only screen 
                            and (min-device-width: 768px) 
                            and (max-device-width: 1024px) 
                            and (orientation: portrait) 
                            and (-webkit-min-device-pixel-ratio: 1) {
                            .butttext {display:none}
                            .butticon {display:inline!important}
                            }
                            </style>
                            ';
                        }
                        else 
                        // Top toolbar Autohide true
                        if (($this->autohide == 'true') && ($this->position == 'top')) {
                            $css .= '
                            <style type="text/css">
                            #qmEditor, #qmEditorClosed { left:0; top: -30px; width: 100%;}
                            #qmEditor:hover, #qmEditorClosed:hover {top: 0px;}
                            .butticon {display:none}
                            @media only screen and (max-width: 728px) {
                            .butttext {display:none}
                            #qmUser {display:none}
                            }
                            @media only screen 
                            and (min-device-width: 768px) 
                            and (max-device-width: 1024px) 
                            and (orientation: portrait) 
                            and (-webkit-min-device-pixel-ratio: 1) {
                            .butttext {display:none}
                            .butticon {display:inline!important}
                            }
                            </style>
                            ';
                        }
                        else 
                        // Top toolbar Relative
                        if ($this->position == 'before') {
                            $css .= '
                            <style type="text/css">
                            #qmEditor, #qmEditorClosed {position: relative; left:0; top: 0; width: 100%; height:35px; opacity:1; -ms-filter:"" !important; filter:none !important;}
                            #qmEditor:hover, #qmEditorClosed:hover {top: 0}
                            .butticon {display:none}
                            @media only screen and (max-width: 728px) {
                            .butttext {display:none}
                            #qmUser {display:none}
                            }
                            @media only screen 
                            and (min-device-width: 768px) 
                            and (max-device-width: 1024px) 
                            and (orientation: portrait) 
                            and (-webkit-min-device-pixel-ratio: 1) {
                            .butttext {display:none}
                            .butticon {display:inline!important}
                            }
                            </style>
                            ';
                        }
                        else 
                        // Bottom toolbar Autohide true
                        if (($this->autohide == 'true') && ($this->position == 'bottom')) {
                            $css .= '
                            <style type="text/css">
                            #qmEditor, #qmEditorClosed { left:0; bottom: -30px; width: 100%;}
                            #qmEditor:hover, #qmEditorClosed:hover {bottom: 0;}
                            .butticon {display:none}
                            @media only screen and (max-width: 728px) {
                            .butttext {display:none}
                            #qmUser {display:none}
                            }
                            @media only screen 
                            and (min-device-width: 768px) 
                            and (max-device-width: 1024px) 
                            and (orientation: portrait) 
                            and (-webkit-min-device-pixel-ratio: 1) {
                            .butttext {display:none}
                            .butticon {display:inline!important}
                            }
                            </style>
                            ';
                        }
                        else
                        // Bottom toolbar Autohide false
                        if (($this->autohide == 'false') && ($this->position == 'bottom')) {
                            $css .= '
                            <style type="text/css">
                            #qmEditor, #qmEditorClosed { left:0; bottom: 0; width: 100%;}
                            #qmEditor:hover, #qmEditorClosed:hover {bottom: 0;}
                            .butticon {display:none}                            
                            @media only screen and (max-width: 728px) {
                            .butttext {display:none}
                            #qmUser {display:none}
                            }
                            @media only screen 
                            and (min-device-width: 768px) 
                            and (max-device-width: 1024px) 
                            and (orientation: portrait) 
                            and (-webkit-min-device-pixel-ratio: 1) {
                            .butttext {display:none}
                            .butticon {display:inline!important}
                            }
                            </style>
                            ';
                        }
                        // Left toolbar Autohide true
                        if (($this->autohide == 'true') && ($this->position == 'left')) {
                            $css .= '
                            <style type="text/css">
                            #qmEditor, #qmEditorClosed {left:-33px; top: 0; width: 40px; height:100%}
                            #qmEditor:hover, #qmEditorClosed:hover {left: 0}
                            #qmEditor .qmButton, .qm-edit, .qm-new {padding: 6px 7px; margin-bottom:3px;}
                            #qmUser {display:none;}
                            .butttext, span.textid {display:none}
                            #qmEditor .qmId {width:40px; font-size:12px; text-align:center;}
                            #qmEditorClosed { width:40px !important;}
                            #qmEditorClosed:after {margin-left:18px!important;}
                            #qmLogoClose:after {margin-left:11px!important;}
                            </style>
                            ';
                        }
                        else
                        // Left toolbar Autohide false
                        if (($this->autohide == 'false') && ($this->position == 'left')) {
                            $css .= '
                            <style type="text/css">
                            #qmEditor, #qmEditorClosed {left:0; top: 0; width: 40px; height:100%}
                            #qmEditor .qmButton, .qm-edit, .qm-new {padding: 6px 7px; margin-bottom:3px;}
                            #qmUser {display:none;}
                            .butttext, span.textid {display:none}
                            #qmEditor .qmId {width:40px; font-size:12px; text-align:center;}
                            #qmEditorClosed { width:40px !important;}
                            #qmEditorClosed:after {margin-left:18px!important;}
                            #qmLogoClose:after {margin-left:11px!important;}
                            </style>
                            ';
                        }
                        // Right toolbar Autohide true
                        if (($this->autohide == 'true') && ($this->position == 'right')) {
                            $css .= '
                            <style type="text/css">
                            #qmEditor, #qmEditorClosed {right:-33px; top: 0; width: 40px; height:100%}
                            #qmEditor:hover, #qmEditorClosed:hover {right: 0;}
                            #qmEditor .qmButton, .qm-edit, .qm-new {padding: 6px 7px; margin-bottom:3px;}
                            #qmUser {display:none;}
                            .butttext, span.textid {display:none}
                            #qmEditor .qmId {width:40px; font-size:12px; text-align:center;}
                            #qmEditorClosed { width:40px !important;}
                            #qmEditorClosed:after {margin-left:18px!important;}
                            #qmLogoClose:after {margin-left:11px!important;}
                            </style>
                            ';
                        }
                        else
                        // Right toolbar Autohide false
                        if (($this->autohide == 'false') && ($this->position == 'right')) {
                            $css .= '
                            <style type="text/css">
                            #qmEditor, #qmEditorClosed {right:0; top: 0; width: 40px; height:100%}
                            #qmEditor .qmButton, .qm-edit, .qm-new {padding: 6px 7px; margin-bottom:3px;}
                            #qmUser {display:none;}
                            .butttext, span.textid {display:none}
                            #qmEditor .qmId {width:40px; font-size:12px; text-align:center;}
                            #qmEditorClosed { width:40px !important;}
                            #qmEditorClosed:after {margin-left:18px!important;}
                            #qmLogoClose:after {margin-left:11px!important;}
                            </style>
                            ';
                        }
                        // Remove background
                        if (($this->removeBg == 'yes') && ($this->buttonStyle == 'actionButtons')) {
                            $css .= '
                            <style type="text/css">
                             #qmEditor, #qmEditorClosed {background: none; background-color: transparent!important;}
                            #qmEditor .qmId {background: rgba(255, 255, 255, 0.3); font-size:13px; text-align:center;  color: #666; padding:2px;} 
                            </style>
                            ';
                        }
                        else
                        if (($this->removeBg == 'yes') && ($this->buttonStyle == 'navButtons')) {
                            $css .= '
                            <style type="text/css">
                             #qmEditor, #qmEditorClosed {background: none; background-color: transparent!important;}
                            #qmEditor .qmId {font-size:13px; text-align:center;  color: #666; padding:1px; margin-right:2px!important;}
                            #qmEditor .qmButton, .qm-edit, .qm-new, .qm-tv, .qm-save, .qm-cancel {transition: all; background-color:rgba(57, 81, 93, 0.8);border-color: rgba(255, 255, 255, 0.8)}
                            #qmEditor .qmButton:hover, .qm-edit:hover, .qm-new:hover, .qm-tv:hover, .qm-save:hover, .qm-cancel:hover { color:#FFF!important; background-color:rgba(57, 81, 93,.4); border-color: rgba(255, 255, 255, 1)}
                            </style>
                            ';
                        }
                        // Insert jQuery and ColorBox in head if needed
                        $head = '';
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
                        		$'.$jvar.'("a.colorbox").colorbox({width:"'.$this->tbwidth.'", height:"'.$this->tbheight.'", iframe:true, overlayClose:false, opacity:0.5, transition:"fade", speed:150});
                        	
                            	// Bindings
                            	$'.$jvar.'(document).bind("cbox_open", function(){
                                    $'.$jvar.'("body").css({"overflow":"hidden"});
                                    $'.$jvar.'("html").css({"overflow":"hidden"});
                                    $'.$jvar.'("#qmEditor").css({"display":"none"});
                                });
				
                                $'.$jvar.'(document).bind("cbox_cleanup", function(){ 
                                    //window is closing and cannot be stopped so clear dirty settings for all fields and tinyMCE
				    var foundit;
				    
                                    //loop through the iframes, checking their scr
                                    iframearray = document.getElementsByTagName(\'iframe\');
				    
                                    for(var i=0; i < iframearray.length; i++) {
                                        //if the matching colorbox src, we have found the correct iframe
                                        haystack=iframearray[i].src;
                                        needle="'.$this->modx->config["site_url"]."manager/index.php?a=27".'";
                                        if(haystack.substr(0, needle.length) == needle){ 
                                            foundit=iframearray[i]; //assign it to the foundit variable created earlier
                                            break; //no need to keep looking
                                        }
                                    }
                                    if (foundit) {
                                        foundit.contentWindow.window.documentDirty=false; //clear document dirty for fields
                                        //loop through tinyMCE editors and clear any dirty flags
                                        if (typeof(foundit.contentWindow.window.tinyMCE)!==\'undefined\') {   
                                            var i, t = foundit.contentWindow.window.tinyMCE.editors;for (i in t){    
                                            if (t.hasOwnProperty(i)){    t[i].isNotDirty=true }}  
                                        }
                                    }
                                });
                                
                            	$'.$jvar.'(document).bind("cbox_closed", function(){      
                                    $'.$jvar.'("body").css({"overflow":"auto"});
                                    $'.$jvar.'("html").css({"overflow":"auto"});
                                    $'.$jvar.'("#qmEditor").css({"display":"block"});
                                    // Remove manager lock by going to home page
                                    $'.$jvar.'.ajax({ type: "GET", url: "'.$this->modx->config['site_manager_url'].'index.php?a=2" });
                                });                  
                                                            						                            
                                // Hide QM+ if cookie found
                                if (getCookie("hideQM") == 1)
                                {
                                    $'.$jvar.'("#qmEditor").css({"display":"none"});
                                    $'.$jvar.'("#qmEditorClosed").css({"display":"block"});    
                                }
                                
                                // Hide QM+
                                $'.$jvar.'(".qmClose").click(function () {
                                    $'.$jvar.'("#qmEditor").hide("normal");
                                    $'.$jvar.'("#qmEditorClosed").show("normal");
                                    document.cookie = "hideQM=1; path=/;";
                                });
                                
                                // Show QM+
                                $'.$jvar.'("#qmEditorClosed").click(function () {
                                    {
                                        $'.$jvar.'("#qmEditorClosed").hide("normal");
                                        $'.$jvar.'("#qmEditor").show("normal");
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

                        $head .= "
                        <script>
                        // Assure keeping session and related locks alive
                        $".$jvar."(document).ready(function($".$jvar.") {
                            function updateMODXsession() {
                              $".$jvar.".ajax({ url: '".MODX_MANAGER_URL."index.php', method:'post', data:{'updateMsgCount':true},
                                success: function(data) {},
                                complete: function() {
                                  setTimeout(updateMODXsession, ". ($this->modx->config['mail_check_timeperiod'] * 1000) .");
                                }
                              });
                            }
                            setTimeout(updateMODXsession, ". ($this->modx->config['mail_check_timeperiod'] * 1000) .");
                        });
                        </script>
                        ";
                        
                        // Insert QM+ css in head
                        $head .= $css;
            
                        // Place QM+ head information in head, just before </head> tag
                        $output = preg_replace('~(</head>)~i', $head . '\1', $output);
            
                        // Insert editor toolbar right after <body> tag
                        $output = preg_replace('~(<body[^>]*>)~i', '\1' . $editor, $output);
                        
                        // Search and create edit buttons in to the content
                        if ($this->editbuttons == 'true' && $access) {
                            $output = preg_replace('/<!-- '.$this->editbclass.' ([0-9]+) \'([^\\"\'\(\)<>!?]+)\' -->/', '<span class="'.$this->editbclass.'"><a class="colorbox" href="'.$this->modx->config['site_manager_url'].'index.php?a=27&amp;id=$1&amp;quickmanager=1&amp;qmrefresh='.$docID.'"><span>$2</span></a></span>', $output);
                        }
                        
                        // Search and create new document buttons in to the content
                        if ($this->newbuttons == 'true' && $access) {
                            $output = preg_replace('/<!-- '.$this->newbclass.' ([0-9]+) ([0-9]+) \'([^\\"\'\(\)<>!?]+)\' -->/', '<span class="'.$this->newbclass.'"><a class="colorbox" href="'.$this->modx->config['site_manager_url'].'index.php?a=4&amp;pid=$1&amp;quickmanager=1&amp;customaddtplid=$2"><span>$3</span></a></span>', $output);
                        }
                        
                        // Search and create new document buttons in to the content
                        if ($this->tvbuttons == 'true' && $access) {
                            // Set and get user doc groups for TV permissions
                            $this->docGroup = '';
                            $mrgDocGroups = $_SESSION['mgrDocgroups'];
                            if (!empty($mrgDocGroups)) $this->docGroup = implode(",", $mrgDocGroups); 

                            // Create TV buttons and check TV permissions
                            $output = preg_replace_callback('/<!-- '.$this->tvbclass.' ([^\\"\'\(\)<>!?]+) -->/', array(&$this, 'createTvButtons'), $output);
                        } 
                    }
                }
                
                break;
            
            // Edit document in ThickBox frame (MODX manager frame)
            case 'OnDocFormPrerender':
                                        
                // If there is Qm call, add control buttons and modify to edit document page
                if (intval($_REQUEST['quickmanager']) == 1) {
                
                    global $content, $_style;
                    
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
                                case 'selected': // Template is inherited by Inherit Selected Template plugin
                                case 'sibling':
                                    // Get parent document id
                                    $pid = $content['parent'] ? $content['parent'] : intval($_REQUEST['pid']);
                                    
                                    if ($this->modx->config['auto_template_logic'] === 'sibling') {
                                        // Eoler: template_autologic in Evolution 1.0.5+
                                        // http://tracker.modx.com/issues/9586
                                        $tv = array();
                                        $sibl = $this->modx->getDocumentChildren($pid, 1, 0, 'template', '', 'menuindex', 'ASC', 1);
                                        if(empty($sibl)) {
                                            $sibl = $this->modx->getDocumentChildren($pid, 0, 0, 'template', '', 'menuindex', 'ASC', 1);
                                        }
                                        if(!empty($sibl)) {
                                            $tv['value'] = $sibl[0]['template'];
                                        }
                                        else $tv['value'] = ''; // Added by yama
                                    }
                                    else
                                    {
                                        // Get "inheritTpl" TV
                                        $tv = $this->modx->getTemplateVar('inheritTpl', '', $pid);
                                    }
                                    
                                    // Set template to inherit
                                    if ($tv['value'] != '') $content['template'] = $tv['value'];
                                    else                    $content['template'] = $this->modx->config['default_template'];
                                    break;
                            }
                        }
                    }

                    // Manager control class
                    $mc = new Mcc();
                
                    // Hide default manager action buttons
                    $mc->addLine('$("#actions").hide();');
    
					
					// Get doc id
					$doc_id = intval($_REQUEST['id']);
					
					// Get jQuery conflict mode
					if ($this->noconflictjq == 'true') $jq_mode = '$j';
					else $jq_mode = '$';
					
					// Add action buttons
                    $url = $this->modx->makeUrl($doc_id,'','','full');
                    $mc->addLine('var controls = "<div style=\"padding:4px 0;position:fixed;top:10px;right:10px;z-index:1000\" id=\"qmcontrols\" class=\"actions\"><a id=\"Button1\" class=\"btn btn-success\" href=\"#\" onclick=\"documentDirty=false;document.mutate.save.click();return false;\"><span> '.$_lang['save'].'</span></a><a id=\"Button5\" class=\"btn btn-secondary\" href=\"#\" onclick=\"parent.location.href=\''.$url.'\'; return false;\"><span> '.$_lang['cancel'].'</span></a></div>";');
                    
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
                                             
                    // Hidden field to verify that QM+ call exists
                    $hiddenFields = '<input type="hidden" name="quickmanager" value="1" />';
                    
                    // Different doc to be refreshed?
                    if (isset($_REQUEST['qmrefresh'])) {
                        $hiddenFields .= '<input type="hidden" name="qmrefresh" value="'.intval($_REQUEST['qmrefresh']).'" />';
                    }
                    
                    // Output
                    $e->output($mc->Output().$hiddenFields);
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
            $result = $this->modx->db->select('count(id)', $table, "document='{$docID}'");
            $rowCount= $this->modx->db->getValue($result);
            
            // If document is assigned to one or more doc groups, check access
            if ($rowCount >= 1) {
            
                // Get document groups for current user
                $mrgDocGroups = $_SESSION['mgrDocgroups'];
                if (!empty($mrgDocGroups))  {
                    $docGroup = implode(",", $mrgDocGroups); 
                    
                    // Check if user has access to current document 
                    $result = $this->modx->db->select('count(id)', $table, "document = '{$docID}' AND document_group IN ({$docGroup})");
                    $rowCount = $this->modx->db->getValue($result);
                    
                    if ($rowCount >= 1) $access = TRUE;
                }
                
                else $access = FALSE;
            }
            
            else $access = TRUE;
        }
        
        return $access;
    }
    
    // Function from: processors/cache_sync.class.processor.php 
    //_____________________________________________________
    function getParents($id, $path = '') { // modx:returns child's parent
		if(empty($this->aliases)) {
			$qh = $this->modx->db->select("id, IF(alias='', id, alias) AS alias, parent", $this->modx->getFullTableName('site_content'));
				while ($row = $this->modx->db->getRow($qh)) {
					$this->aliases[$row['id']] = $row['alias'];
					$this->parents[$row['id']] = $row['parent'];
				}
		}
		if (isset($this->aliases[$id])) {
			$path = $this->aliases[$id] . ($path != '' ? '/' : '') . $path;
			return $this->getParents($this->parents[$id], $path);
		}
		return $path;
	} 
	
	// Create TV buttons if user has permissions to TV
	//_____________________________________________________
	function createTvButtons($matches) {
	    
        $access = FALSE;
        $table = $this->modx->getFullTableName('site_tmplvar_access');
        $docID = $this->modx->documentIdentifier;
        
        // Get TV caption for button title
	    $tv = $this->modx->getTemplateVar($matches[1]);
	    $caption = $tv['caption'];
	
	    // If caption is empty this must be a "build-in-tv-field" like pagetitle etc.
	    if ($caption == '') {
	        
            // Allowed for all
            $access = TRUE;    
            
            // Resolve caption
            $caption = $this->getDefaultTvCaption($matches[1]);
	    }
	    
	    // Check TV access
	    else {
	       $access = $this->checkTvAccess($tv['id']);
	    }
	    
	    // Return TV button link if access
	    if ($access && $caption != '') {
            $amp = ($this->modx->config['friendly_urls'] == 1) ? '?' : '&';
	        return '<span class="'.$this->tvbclass.'"><a class="colorbox" href="'.$this->modx->makeUrl($docID).$amp.'quickmanagertv=1&amp;tvname='.$matches[1].'"><span>'.$caption.'</span></a></span>';
        } 
    }
    
    // Check user access to TV
	//_____________________________________________________
	function checkTvAccess($tvId) {
	    $access = FALSE;
	    $table = $this->modx->getFullTableName('site_tmplvar_access');
	    
	    // If user is admin (role = 1)
        if ($_SESSION['mgrRole'] == 1 && !$access) { $access = TRUE; }
	    
	    // Check permission to TV, is TV in document group?  
	    if (!$access) {
	        $result = $this->modx->db->select('count(id)', $table, "tmplvarid = '{$tvId}'");
            $rowCount = $this->modx->db->getValue($result);
            // TV is not in any document group
            if ($rowCount == 0) { $access = TRUE; }    
	    }
	    
	    // Check permission to TV, TV is in document group 
	    if (!$access && $this->docGroup != '') {
            $result = $this->modx->db->select('count(id)', $table, "tmplvarid = '{$tvId}' AND documentgroup IN ({$this->docGroup})");
            $rowCount = $this->modx->db->getValue($result);
            if ($rowCount >= 1) { $access = TRUE; }
        }    
        
        return $access;
	}
    
	// Get default TV ("build-in" TVs) captions
	//_____________________________________________________
	function getDefaultTvCaption($name) {
	
	    global $_lang;
	    $caption = '';
	    
	    switch ($name) {
            case 'pagetitle'    : $caption = $_lang['resource_title']; break;     
            case 'longtitle'    : $caption = $_lang['long_title']; break;
            case 'description'  : $caption = $_lang['resource_description']; break;
            case 'content'      : $caption = $_lang['resource_content']; break;
            case 'menutitle'    : $caption = $_lang['resource_opt_menu_title']; break;
            case 'introtext'    : $caption = $_lang['resource_summary']; break;
        }
        
        return $caption;
	}
	
	// Check that a document isn't locked for editing
	//_____________________________________________________
	function checkLocked() {

		$pageId = $this->modx->documentIdentifier;
		$locked = TRUE;

		if ($this->modx->elementIsLocked(7, $pageId) === NULL) {
			$locked = FALSE;
		}

		return $locked;
	}
	
	// Set document locked on/off
	//_____________________________________________________
	function setLocked($locked) {

		$pageId = $this->modx->documentIdentifier;
		
		// Set document locked
		if ($locked == 1) {
    		$this->modx->lockElement(7, $pageId);
        }
        
        // Set document unlocked
        else {
            $this->modx->unlockElement(7, $pageId);    
        }
	}
	
	// Save TV
	//_____________________________________________________
	function saveTv($tvName) {
	
        $tmplvarContentValuesTable = $this->modx->getFullTableName('site_tmplvar_contentvalues');
        $siteContentTable = $this->modx->getFullTableName('site_content');
        $pageId = $this->modx->documentIdentifier;
        $result = null;
        $time = time();
        $user = $_SESSION['mgrInternalKey'];      
        $tvId = isset($_POST['tvid']) ? intval($_POST['tvid']) : '';
        $tvContent = isset($_POST['tv'.$tvName]) ? $_POST['tv'.$tvName] : '';
        $tvContentTemp = '';
        
        // Escape TV content
        $tvContent = $this->modx->db->escape($tvContent);
        
        // Invoke OnBeforeDocFormSave event
        $this->modx->invokeEvent('OnBeforeDocFormSave', array('mode'=>'upd', 'id'=>$pageId));
        
        // Handle checkboxes and other arrays, TV to be saved must be e.g. value1||value2||value3
        if (is_array($tvContent)) {
            $tvContent = implode("||", $tvContent);
        }
        
        // Save TV
        if ($tvId != '') {
            $fields = array(
                'tmplvarid' => $tvId,
                'contentid' => $pageId,
                'value'     => $tvContent,
                );
            $result = $this->modx->db->select('count(id)', $tmplvarContentValuesTable, "tmplvarid = '{$fields['tmplvarid']}' AND contentid = '{$fields['contentid']}'");
            
            // TV exists, update TV   
            if($this->modx->db->getValue($result)) {
                $this->modx->db->update($fields, $tmplvarContentValuesTable, "tmplvarid = '{$fields['tmplvarid']}' AND contentid = '{$fields['contentid']}'");
            } 
        
            // TV does not exist, create new TV   
            else {
                $this->modx->db->insert($fields, $tmplvarContentValuesTable);
            }
            
            // Page edited by
            $this->modx->db->update(
                array(
                    'editedon' => $time,
                    'editedby' => $user
                    ), $siteContentTable, "id = '{$pageId}'");
        } 
        
        // Save default field, e.g. pagetitle
        else {                
            $this->modx->db->update(
                array(
                    $tvName    => $tvContent,
                    'editedon' => $time,
                    'editedby' => $user
                    ), $siteContentTable, "id = '{$pageId}'");
        }
        
            // Invoke OnDocFormSave event
            $this->modx->invokeEvent('OnDocFormSave', array('mode'=>'upd', 'id'=>$pageId));
            
            // Clear cache
            $this->modx->clearCache('full');
    }
	
}
}
