<?php

global $ContextMenuCnt;
$ContextMenuCnt = 0;

class ContextMenu {
	public $id;
    /**
     * @var string
     */
	public $html = '';
    /**
     * @var bool
     */
	public $visible = false;
    /**
     * @var int
     */
	public $width = 120;

    public function __construct($id = '', $width = 120, $visible = false) {
		global $ContextMenuCnt;
		$ContextMenuCnt++;
		$this->html = "";
		$this->visible = $visible ? $visible : false;
		$this->width = is_numeric($width) ? (int)$width : 120;
		$this->id = $id ? $id : "cntxMnu" . $ContextMenuCnt;    // set id
	}

    public function addItem($text, $action = "", $img = "", $disabled = 0) {
		global $base_url, $_style;
        if($disabled) {
            return;
        }
		if(!$img) {
			$img = $base_url . $_style['tx'];
		}
		if(substr($action, 0, 3) == "js:") {
			$action = substr($action, 3);
		} else if(substr($action, 0, 3) == "hl:") {
			$action = "window.location.href='" . substr($action, 3) . "'";
		} else {
			$action = "window.location.href='" . $action . "'";
		}
		$action = " onmouseover=\"this.className='cntxMnuItemOver';\" onmouseout=\"this.className='cntxMnuItem';\" onclick=\"$action; hideCntxMenu('" . $this->id . "');\"";
		$this->html .= "<div class='" . ($disabled ? "cntxMnuItemDisabled" : "cntxMnuItem") . "' $action>";
        if(substr($img, 0, 5) == 'fa fa') {
            $img = '<i class="' . $img . '"></i>';
        } else if(substr($img, 0, 1) != '<') {
            $img = '<img src="' . $img . '" />';
        }
		$this->html .= $img . '&nbsp;' . $text . '</div>';
	}

    public function addSeparator() {
		$this->html .= "
			<div class='cntxMnuSeparator'></div>
		";
	}

    public function render() {
		global $ContextMenuScript;

		$html = $ContextMenuScript . "<div id='" . $this->id . "' class='contextMenu' style='width:" . $this->width . "px; visibility:" . ($this->visible ? 'visible' : 'hidden') . "'>" . $this->html . "</div>";
		$ContextMenuScript = ""; // reset css
		return $html;
	}

    public function getClientScriptObject() {
		return "getCntxMenu('" . $this->id . "')";
	}
}

$ContextMenuScript = <<<BLOCK
<script>
	function getCntxMenu(id) {
		return document.getElementById(id);
	}
	function hideCntxMenu(id){
		var cm = getCntxMenu(id);
		cm.style.visibility = 'hidden';
	}
</script>
BLOCK;
