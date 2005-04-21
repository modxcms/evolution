<?php
#
# DataSetPager Class
# Created By Raymond Irving 2-Dec,2004
# Based on CLASP 2.0 (www.claspdev.com)
# -----------------------------------------
# Licensed under the GPL
# -----------------------------------------
#

$__DataSetPagerCnt=0;

class DataSetPager {

	var $ds; // datasource
	var $pageSize;
	var $pageNumber;
	var $rows;
	var $pager;
	var $id;
	
	var $cssStyle;
	var $cssClass;

	function DataSetPager($id,$ds,$pageSize=10,$pageNumber=1) {		
		
		global $__DataSetPagerCnt;
		
		// set id
		$__DataSetPagerCnt++;		
		$this->id = !empty($id) ? $id:"dsp".$__DataSetPagerCnt;
		
		// get pagenumber
		$pageNumber= (isset($_GET["dpgn".$this->id]))? $_GET["dpgn".$this->id]:1;
		if (!is_numeric($pageNumber)) $pageNumber = 1;	

		$this->ds = $ds; // datasource
		$this->pageSize = $pageSize;
		$this->pageNumber = $pageNumber;
		$this->rows = '';
		$this->pager = '';
	}

	function getRenderedPager() {
		return $this->pager;
	}

	function getRenderedRows() {
		return $this->rows;
	}

	function setDataSource($ds){
		$this->ds = $ds;
	}

	function setPageSize($ps){
		$this->pageSize = $ps;
	}

	function setRenderRowFnc($fncName){
		$this->renderRowFnc = $fncName;
	}

	function setRenderPagerFnc($fncName){
		$this->renderPagerFnc = $fncName;
	}

	function render(){

		$isDataset = is_resource($this->ds);
		
		// get total number of rows		
		$tnr = ($isDataset)? mysql_num_rows($this->ds):count($this->ds); 
		if($tnr<=0) {
			// no records found
			$fnc = $this->renderRowFnc;
			if (isset($fnc)) $this->rows .= $fnc(0,null);
			return;
		}

		// get total pages
		$tp = ceil($tnr/$this->pageSize);

		// get page number
		$p = $this->pageNumber;

		// render pager : renderPagerFnc($cuurentPage,$pagerNumber)
		if($tp>1) {	
			$fnc = $this->renderPagerFnc;
			if (!isset($fnc)){
				$url = $_SERVER['PHP_SELF']."?";
				$i=0;
				foreach($_GET as $n => $v) if($n!='dpgn'.$this->id) {$i++;$url.=(($i>1)? "&":"")."$n=$v";}
				if($i>=1)$url.="&";
			}
			for($i=1;$i<=$tp;$i++) {
				if (isset($fnc)) $this->pager .= $fnc($p,$i);
				else $this->pager .=($p==$i)? "$i":" <a href='".$url."dpgn".$this->id."=$i'>$i</a> ";
			}
		}

		$fnc = $this->renderRowFnc;

		if (isset($fnc)) {
			$i = 1;
			$fncObject = is_object($fnc);
			$minitems = (($p-1)*$this->pageSize)+1;
			$maxitems = (($p-1)*$this->pageSize)+$this->pageSize;
			while ($i<=$maxitems && ($row = ($isDataset)? mysql_fetch_assoc($this->ds):$this->ds[$i-1])) {
				if ($i>=$minitems && $i<=$maxitems){
					// render row : renderRowFnc($rowNumber,$row)
					if($fncObject) $this->rows .= $fnc->RenderRowFnc($i,$row);
					else $this->rows .= $fnc($i,$row);
				}
				$i++;
			}			
		}	
	}
}

?>