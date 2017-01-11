//<?php
/**
 * ElementsInTree
 *
 * Get access to all Elements and Modules inside Manager sidebar
 *
 * @category    plugin
 * @version     1.5.7
 * @license     http://creativecommons.org/licenses/GPL/2.0/ GNU Public License (GPL v2)
 * @internal    @properties &tabTreeTitle=Tree Tab Title;text;Site Tree;;Custom title of Site Tree tab. &useIcons=Use icons in tabs;list;yes,no;yes;;Icons available in MODX version 1.2 or newer. &treeButtonsInTab=Tree Buttons in tab;list;yes,no;yes;;Move Tree Buttons into Site Tree tab. &unifyFrames=Unify Frames;list;yes,no;yes;;Unify Tree and Main frame style. Right now supports MODxRE2 theme only.
 * @internal    @events OnManagerTreePrerender,OnManagerTreeRender,OnManagerMainFrameHeaderHTMLBlock,OnTempFormSave,OnTVFormSave,OnChunkFormSave,OnSnipFormSave,OnPluginFormSave,OnModFormSave,OnTempFormDelete,OnTVFormDelete,OnChunkFormDelete,OnSnipFormDelete,OnPluginFormDelete,OnModFormDelete
 * @internal    @modx_category Manager and Admin
 * @internal    @installset base
 * @documentation Requirements: This plugin requires MODX Evolution 1.2 or later
 * @reportissues https://github.com/modxcms/evolution/issues
 * @link        Original Github thread https://github.com/modxcms/evolution/issues/783
 * @author      Dmi3yy https://github.com/dmi3yy
 * @author      pmfx https://github.com/pmfx
 * @author      Nicola1971 https://github.com/Nicola1971
 * @author      Deesen https://github.com/Deesen
 * @author      yama https://github.com/yama
 * @lastupdate  27/12/2016
 */

require MODX_BASE_PATH.'assets/plugins/elementsintree/plugin.elementsintree.php';
