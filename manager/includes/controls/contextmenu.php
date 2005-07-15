<?php

global $ContextMenuCnt; 
$ContextMenuCnt = 0;

class ContextMenu {

	function ContextMenu($id='',$width=120,$visible=false) {
		$ContextMenuCnt++;
		$this->html = "";
		$this->visible = $visible ? $visible:false; 
		$this->width = is_numeric($width) ? intval($width):120;
		$this->id = id ? $id:"cntxMnu".$ContextMenuCnt;	// set id
	}
	
	function addItem($text,$action="",$img="",$disabled=0){
		global $base_url;
		if(!$img) $img = $base_url."manager/media/images/_tx_.gif";
		if(substr($action,0,3)=="js:") $action = substr($action,3);
		else if(substr($action,0,3)=="hl:") $action = "window.location.href='".substr($action,3)."'";
		else $action = "window.location.href='".$action."'";
		$action=" onmouseover=\"this.className='cntxMnuItemOver';\" onmouseout=\"this.className='cntxMnuItem';\" onclick=\"$action; hideCntxMenu('".$this->id."');\"";
		if ($disabled) $action="";
		$this->html .= "
			<div class='".($disabled ? "cntxMnuItemDisabled":"cntxMnuItem")."' $action>
				<img src='$img' width='16' height='16' align='absmiddle' />&nbsp;$text
			</div>
		";
	}
	
	function addSeparator(){
		$this->html .= "
			<div class='cntxMnuSeparator'></div>
		";	
	}
	
	function render() {
		global $modx;
		global $ContextMenuScript;
	
		$html = $ContextMenuScript.
				"<div id='".$this->id."' class='contextMenu' style='width:".$this->width."px; visibility:".($this->visible ?'visible':'hidden')."'>".$this->html."</div>";
		$ContextMenuScript = ""; // reset css
		return $html;
	}
	
	function getClientScriptObject(){
		return "getCntxMenu('".$this->id."')";
	}
}

$ContextMenuScript = <<<BLOCK
<script>
	function getCntxMenu(id) {
		if(self.DynElement) return new DynElement(id);
		else return document.getElementById(id);
	}
	function hideCntxMenu(id){
		var cm = getCntxMenu(id);
		cm.style.visibility = 'hidden';
	}
</script>
<style>
.contextMenu {
	background-image: 			url("media/images/bg/context.gif");
	background-color: 			#fff;
	background-position: 		top left;
	background-repeat: 			repeat-y;
	margin:						0px;
	padding:					0px;
	border: 					1px solid #003399;
	border-left-color:			#eaeaea;
	border-top-color:			#eaeaea;
	border-right-color:			#909090;
	border-bottom-color:		#707070;
	position:					absolute;
	z-index:					10000; }

.cntxMnuItem {
	background-image:			url('media/images/_tx_.gif');
	cursor:						pointer;
	font:						menu;
	color:						MenuText;
	padding: 					3px 16px 3px 2px; }

.cntxMnuItemOver {
	cursor:						pointer;
	color: 						#000000;
	background-color: 			#FFCC00;	
	background-position: 		bottom left;
	background-repeat: 			repeat-x;	
	background-image: 			url("media/images/misc/buttonbar_gs.gif");
	font:						menu;
	padding: 					2px 15px 2px 1px;
	border: 					1px solid  #FFAA00; /*#003399;*/
}

.cntxMnuItemDisabled {
	cursor:						default;
	font: 						menu;
	padding: 					3px 16px 3px 2px;
	color:						graytext;
}
.cntxMnuItem IMG, .cntxMnuItemOver IMG, .cntxMnuItemDisabled IMG {
	margin-right:				8px;
}

.cntxMnuItem IMG, .cntxMnuItemDisabled IMG {
	filter:						gray();
}

.cntxMnuSeparator {
	font-size:      			0pt;
	height:         			1px;
	background-color: 			#6A8CCB;
	overflow:       			hidden;
	margin:						3px 1px 3px 28px; }	

</style>
BLOCK;

?>
