//<?php
/**
 * Quick Manager+
 * 
 * Enables QuickManager+ front end content editing support
 *
 * @category 	plugin
 * @version 	clipper-1.5.6
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @properties &loadmanagerjq=Load jQuery in manager;list;true,false;false &loadtb=Load modal box in front-end;list;true,false;true &tbwidth=Modal box window width;text;80% &tbheight=Modal box window height;text;90% &hidefields=Hide document fields from front-end editors;text;parent &hidetabs=Hide document tabs from front-end editors;text; &hidesections=Hide document sections from front-end editors;text; &addbutton=Show add document here button;list;true,false;true &tpltype=New document template type;list;parent,id,selected;parent &tplid=New document template id;int;3 &custombutton=Custom buttons;textarea; &managerbutton=Show go to manager button;list;true,false;true &logout=Logout to;list;manager,front-end;manager &disabled=Plugin disabled on documents;text; &autohide=Autohide toolbar;list;true,false;true &editbuttons=Inline edit buttons;list;true,false;false &editbclass=Edit button CSS class;text;qm-edit &newbuttons=Inline new resource buttons;list;true,false;false &newbclass=New resource button CSS class;text;qm-new &tvbuttons=Inline template variable buttons;list;true,false;false &tvbclass=Template variable button CSS class;text;qm-tv
 * @internal	@events OnParseDocument,OnWebPageInit,OnWebPagePrerender,OnDocFormPrerender,OnDocFormSave,OnManagerLogout 
 * @internal	@modx_category Manager and Admin
 * @internal    @legacy_names QM+,QuickEdit
 * @internal    @installset base, sample
 */


// In manager
if (isset($_SESSION['mgrValidated'])) {

    $show = TRUE;

    if ($disabled  != '') {
        $arr = explode(",", $disabled );
        if (in_array($modx->documentIdentifier, $arr)) {
            $show = FALSE;
        }
    }

    if ($show) {
        // Replace [*#tv*] with QM+ edit TV button placeholders
        if ($tvbuttons == 'true') {
            $e = $modx->Event;
            if ($e->name == 'OnParseDocument') {
                 $output = &$modx->documentOutput;
                 $output = preg_replace('~\[\*#(.*?)\*\]~', '<!-- '.$tvbclass.' $1 -->[*$1*]', $output);
                 $modx->documentOutput = $output;
             }
         }
        // In manager
        if (isset($_SESSION['mgrValidated'])) {
            require_once($modx->config['base_path'].'assets/plugins/qm/qm.inc.php');
            $qm = new Qm($modx, $loadmanagerjq, $modx->config['jquery_noconflict'], $loadtb, $tbwidth, $tbheight, $hidefields, $hidetabs, $hidesections, $addbutton, $tpltype, $tplid, $custombutton, $managerbutton, $logout, $autohide, $editbuttons, $editbclass, $newbuttons, $newbclass, $tvbuttons, $tvbclass);
        }
    }
}
