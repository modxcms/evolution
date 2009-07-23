<?php
/**
 * Qm+ — QuickManager+
 *
 * @author      Urique Dertlian, urique@unix.am & Mikko Lammi, www.maagit.fi
 * @license     GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @version     1.1.1 updated 21/04/2009
 */

if(!class_exists('Qm')) {

class Qm {
  var $modx;

    //_______________________________________________________
    function Qm(&$modx, $jqpath, $loadmanagerjq, $loadfrontendjq, $loadtb, $usemm, $tbwidth, $tbheight, $hidefields, $addbutton, $tpltype, $tplid) {
        $this->modx = $modx;

        // Get plugin parameters
        $this->jqpath = $jqpath;
        $this->loadmanagerjq = $loadmanagerjq;
        $this->loadfrontendjq = $loadfrontendjq;
        $this->loadtb = $loadtb;
        $this->usemm = $usemm;
        $this->tbwidth = $tbwidth;
        $this->tbheight = $tbheight;
        $this->hidefields = $hidefields;
        $this->addbutton = $addbutton;
        $this->tpltype = $tpltype;
        $this->tplid = $tplid;

        // Includes
        include_once($this->modx->config['base_path'].'assets/plugins/qm/mcc.class.php');

        // Run plugin
        $this->Run();
    }

    //_______________________________________________________
    function Run() {

        // Include MODx manager language file
        global $_lang;
        include_once($this->modx->config['base_path'].'manager/includes/lang/'.$this->modx->config['manager_language'].'.inc.php');

        // Get event
        $e = $this->modx->Event;

        // Run plugin based on event
        switch ($e->name) {

            // Save document
            case 'OnDocFormSave':

                // Saving process for Qm only, confirm HTTP_REFERER
                if(!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],'quickmanager=true') !== false) {

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

                    // Redirect to clearer page which refreshes parent window and closes ColorBox frame
                    $this->modx->sendRedirect($this->modx->config['base_url'].'assets/plugins/qm/close.php?id='.$id.'&baseurl='.$this->modx->config['base_url'], 0, 'REDIRECT_HEADER', 'HTTP/1.1 301 Moved Permanently');
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
							<a class="qmButton qmEdit colorbox" title="'.$doc['pagetitle'].' &raquo; '.$_lang['edit_document'].'" href="'.$this->modx->config['site_url'].'manager/index.php?a=27&amp;id='.$docID.'&amp;quickmanager=true">'.$_lang['edit_document'].'</a>
						</li>';

                    // Does user have permissions to edit document
                    if($this->modx->hasPermission('edit_document')) $controls.=$editButton;

                    if ($this->addbutton == 'true') {
                        // Add button
                        $addButton  = '
							<li>
								<a class="qmButton qmAdd colorbox" title="'.$doc['pagetitle'].' &raquo; '.$_lang['create_document_here'].'" href="'.$this->modx->config['site_url'].'manager/index.php?a=4&amp;pid='.$docID.'&amp;quickmanager=true">'.$_lang['create_document_here'].'</a>
							</li>';

                        // Does user have permissions to add document
                        if($this->modx->hasPermission('new_document')) $controls.=$addButton;
                    }

                    // Not implemented yet
                    //$delButton  = '<a class="button delete" title="'.$doc['pagetitle'].'&raquo; '.$_lang['delete_document'].'" href="#" onclick="if(confirm(\'`'.$doc['pagetitle'].'`\n\n'.$_lang['confirm_delete_document'].'\')==true) document.location.href=\''.$this->modx->config['site_url'].'manager/index.php?a=4&id='.$docID.'\';return false;">'.$_lang['delete_document'].'</a>';
                    //if($this->modx->hasPermission('delete_document')) $controls.=$delButton;

                    // Logout button
                    $logout = $this->modx->config['site_url'].'manager/index.php?a=8';
                    $logoutButton  = '
						<li>
							<a class="qmButton qmLogout" title="'.$_lang['logout'].'" href="'.$logout.'" ><!--img src="' . MODX_BASE_URL. 'assets/plugins/qm/res/exit.png" alt="Edit" /-->'.$_lang['logout'].'</a>
						</li>';

                    $controls .= $logoutButton;

                    $editor = '
						<div id="qmEditor" class="actionButtons">
							<ul>'.$controls.'
							</ul>
						</div>';
                    $css = '<link rel="stylesheet" type="text/css" href="assets/plugins/qm/res/style.css" />';

                    // Insert jQuery and ColorBox in head if needed
                    if ($this->loadfrontendjq == 'true') $head .= '<script src="'.$this->modx->config['site_url'].$this->jqpath.'" type="text/javascript"></script>';
                    if ($this->loadtb == 'true') {
                        $head .= '
                        <script type="text/javascript" src="'.$this->modx->config['site_url'].'assets/js/jquery.colorbox-min.js"></script>
                        <link type="text/css" media="screen" rel="stylesheet" href="'.$this->modx->config['site_url'].'assets/js/colorbox.css" />
                        <!--[if IE]>
                        <link type="text/css" media="screen" rel="stylesheet" href="'.$this->modx->config['site_url'].'assets/js/colorbox-ie.css" title="example" />
                        <![endif]-->

                    	<script type="text/javascript">
	                    	var $j = jQuery.noConflict();
	                    	$j(document).ready(function($){
	                    		$("a.colorbox").colorbox({width:"'.$this->tbwidth.'", height:"'.$this->tbheight.'", iframe:true});
	                    	});
	                		function cb_remove(){
	                            $j.fn.colorbox.close();
	                		}
                        </script>
                        ';
                    }

                    // Insert Qm css in head
                    $head .= $css;

                    // Place Qm head information in head, just before </head> tag
                    $output = preg_replace('~(</head>)~i', $head . '\1', $output);

                    // Insert editor toolbar right after <body> tag
                    $output = preg_replace('~(<body[^>]*>)~i', '\1' . $editor, $output);

                }

                break;

            // Edit document in ColorBox frame (MODx manager frame)
            case 'OnDocFormPrerender':

                // If there is Qm call, add control buttons and modify to edit document page
                if(!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],'manager') === false) {

                    global $content;

                    // Set template for new document, action = 4
                    if($_GET['a'] == 4) {

                        switch ($this->tpltype) {
                            // Template type is parent
                            case 'parent':
                                // Get parent document id
                                $pid = $content['parent'] ? $content['parent'] : $_REQUEST['pid'];

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
                                $pid = $content['parent'] ? $content['parent'] : $_REQUEST['pid'];

                                // Get inheritTpl TV
                                $tv = $this->modx->getTemplateVar("inheritTpl", "", $pid);

                                // Set template to inherit
                                if ($tv['value'] != '') $content['template'] = $tv['value'];
                                else $content['template'] = $this->modx->config['default_template'];

                                break;
                        }
                    }

                    // Manager control class
                    $mc = new Mcc($this->jqpath);

                    // Hide subtitle
                    $mc->addLine('$(".subTitle").hide();');

                    // Use with ManagerManager => remove sectionBody
					$qm_theme = $this->modx->config['manager_theme'];
                    if ($this->usemm == 'true') {
                        $mc->addLine('var controls = "<div style=\"position:fixed;top:10px;right:-10px;z-index:1000\" id=\"qmcontrols\" class=\"actionButtons\"><ul><li><a href=\"#\" onclick=\"documentDirty=false;document.mutate.save.click();return false;\"><img src=\"media/style/'.$qm_theme.'/images/icons/save.png\"/>'.$_lang['save'].'</a></li><li><a href=\"#\" onclick=\"documentDirty=false;document.location.href=\'index.php?a=3&amp;id='.$_REQUEST['id'].'&amp;quickmanager=cancel\';return false;\"><img src=\"media/style/'.$qm_theme.'/images/icons/stop.png\"/>'.$_lang['cancel'].'</a></li></ul></div>";');
                    }
                    else {
                        $mc->addLine('var controls = "<div id=\"qmcontrols\" class=\"sectionBody actionButtons\"><ul><li><a href=\"#\" onclick=\"documentDirty=false;document.mutate.save.click();return false;\"><img src=\"media/style/'.$qm_theme.'/images/icons/save.png\"/>'.$_lang['save'].'</a></li><li><a href=\"#\" onclick=\"documentDirty=false;document.location.href=\'index.php?a=3&amp;id='.$_REQUEST['id'].'&amp;quickmanager=cancel\';return false;\"><img src=\"media/style/'.$qm_theme.'/images/icons/stop.png\"/>'.$_lang['cancel'].'</a></li></ul></div>";');
                    }

                    // Modify head
                    $mc->head = '<script type="text/javascript">document.body.style.display="none";</script>';
                    if ($this->loadmanagerjq == 'true') $mc->head .= '<script src="'.$modx->config['site_url'].$jqpath = $this->jqpath.'" type="text/javascript"></script>';

                    // Add control button
                    $mc->addLine('$("body").prepend(controls);');
                    //$mc->addLine('$("body").append(controls);');

                    // Hide fields to from front-end editors, especially template and parent are problematic
                    $hideFields = explode(",", $this->hidefields);

                    foreach($hideFields as $key => $field) {
                        $mc->hideField($field);
                    }

                    // Hide templates but not active template => Changing template is not possible with Qm+
                    $sql = "SELECT id FROM ".$this->modx->getFullTableName('site_templates');
	                $rs = $this->modx->db->query($sql);
	                while($row = $this->modx->db->getRow($rs)) {
	                   if ($content['template'] != $row['id']) $hideTpls[] = $row['id'];
	                }
	                $mc->hideTemplates($hideTpls);

                    // Output
                    $e->output($mc->Output());
                }

            break;

            // Remove edit document locks
            case 'OnManagerPageInit':
                // Only if cancel editing the document and QuickManager is in use
                if ($_REQUEST['quickmanager'] == 'cancel') {
                    // Redirect to clearer page which closes ColorBox frame
                    $this->modx->sendRedirect($this->modx->config['base_url'].'assets/plugins/qm/close.php?action=cancel', 0, 'REDIRECT_HEADER', 'HTTP/1.1 301 Moved Permanently');
                }

            break;
        }
    }
}
}
?>