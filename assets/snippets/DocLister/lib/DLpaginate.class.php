<?php
class DLpaginate {
    /**
     * Script Name: *Digg Style Paginator Class
     * Script URI: http://www.mis-algoritmos.com/2007/05/27/digg-style-pagination-class/
     * Description: Class in PHP that allows to use a pagination like a digg or sabrosus style.
     * Script Version: 0.5
     * Author: Victor De la Rocha
     * Author: Agel Nash
     * Author URI: http://www.mis-algoritmos.com
     */

    /**Default values*/
    public $total_pages = -1; //items
    public $limit = null;
    public $target = "";
    public $page = 1;
    public $adjacents = 2;
    public $showCounter = false;
    public $className = "pagination";
    public $parameterName = "page";
    public $urlF = null; //urlFriendly

    /**Buttons next and previous*/
    public $nextT = ' <a href="[+link+]">Next</a> ';
    public $nextI = "&#187;"; //&#9658;
    public $prevT = ' <a href="[+link+]">Previous</a> ';
    public $prevI = "&#171;"; //&#9668;

    /**Buttons last and first*/
    public $lastT = ' <a href="[+link+]">Last</a> ';
    public $lastI = "&#187;&#187;"; //&#9658;
    public $firstT = ' <a href="[+link+]">First</a> ';
    public $firstI = "&#171;&#171;"; //&#9668;

    public $numberT = ' <a href="[+link+]">[+num+]</a> ';
    public $currentT = ' <b>[+num+]</b> ';

    public $mainTpl = '<div class="[+classname+]">[+wrap+]</div>';

    public $dotsT = ' ... ';

    /*****/
    protected $mode = null;
    protected $modeConfig = array();

    private $calculate = false;
    private $pagination;

    public function setMode($mode, array $config = array()) {
        $this->mode = $mode;
        $this->modeConfig = $config;
        return $this;
    }

    #Total items
    public function items($value) {
        $this->total_pages = (int)$value;
        return $this;
    }

    #how many items to show per page
    public function limit($value) {
        $this->limit = (int)$value;
        return $this;
    }

    #Page to sent the page value
    public function target($value) {
        $this->target = $value;
        return $this;
    }

    #Current page
    public function currentPage($value) {
        $this->page = (int)$value;
        return $this;
    }

    #How many adjacent pages should be shown on each side of the current page?
    public function adjacents($value) {
        $this->adjacents = (int)$value;
        return $this;
    }

    #show counter?
    public function showCounter($value = "") {
        $this->showCounter = ($value === true) ? true : false;
        return $this;
    }

    #to change the class name of the pagination div
    public function changeClass($value = "") {
        $this->className = $value;
        return $this;
    }

    public function mainTpl($value) {
        $this->mainTpl = $value;
        return $this;
    }

    public function nextLabel($value) {
        $this->nextT = $value;
        return $this;
    }

    public function nextIcon($value) {
        $this->nextI = $value;
        return $this;
    }

    public function prevLabel($value) {
        $this->prevT = $value;
        return $this;
    }

    public function prevIcon($value) {
        $this->prevI = $value;
        return $this;
    }

    #to change the class name of the pagination div
    public function parameterName($value = "") {
        $this->parameterName = $value;
        return $this;
    }

    #to change urlFriendly
    public function urlFriendly($value = "%") {
        if (eregi('^ *$', $value)) {
            $this->urlF = false;
        }
        $this->urlF = $value;
        return $this;
    }

    public function show() {
        echo $this->getOutput();
    }

    public function getOutput() {
        $out = '';
        if (!$this->calculate && $this->calculate() && !empty($this->pagination)){
            $out = str_replace(array("[+class+]", "[+wrap+]"), array($this->className, $this->pagination), $this->mainTpl) . "\n";
        }
        return $out;
    }
    protected function getPageQuery($page){
        switch($this->mode){
            case 'offset':{
                $display = isset($this->modeConfig['display']) ? $this->modeConfig['display'] : 0;
                $out = $display * ($page - 1);
                break;
            }
            case 'back':
            case 'pages':
            default:{
                $out = $page;
                break;
            }
        }
        return $out;
    }
    public function get_pagenum_link($id) {
        $flag = (strpos($this->target, '?') === false);
        $value = $this->getPageQuery($id);
        if ($flag && $this->urlF){
            $out = str_replace($this->urlF, $value, $this->target);
        } else {
            $out = $this->target;
            if ($id > 1) {
                $out .= ($flag ? "?" : "&") . $this->parameterName."=".$value;
            }
        }
        return $out;
    }

    public function calculate() {
        $this->pagination = "";
        $this->calculate = true;
        $error = false;

        if ($this->urlF && $this->urlF != '%' && strpos($this->target, $this->urlF) === false) {
            //Es necesario especificar el comodin para sustituir
            //echo "Especificaste un wildcard para sustituir, pero no existe en el target<br />";
            $error = true;
        } elseif ($this->urlF && $this->urlF == '%' && strpos($this->target, $this->urlF) === false) {
            //echo "Es necesario especificar en el target el comodin % para sustituir el n�mero de p�gina<br />";
            $error = true;
        }

        if ($this->total_pages < 0) {
            //echo "It is necessary to specify the <strong>number of pages</strong> (\$class->items(1000))<br />";
            $error = true;
        }
        if ($this->limit == null) {
            //echo "It is necessary to specify the <strong>limit of items</strong> to show per page (\$class->limit(10))<br />";
            $error = true;
        }
        if ($error) return false;

        /* Setup vars for query. */
        if ($this->page)
            $start = ($this->page - 1) * $this->limit; //first item to display on this page
        else
            $start = 0; //if no page var is given, set start to 0

        /* Setup page vars for display. */
        $prev = ($this->page <= 1) ? 0 : $this->page - 1; //previous page is page - 1
        $next = (($this->page == $this->total_pages) ? 0 : ($this->page + 1)); //next page is page + 1
        $lastpage = ceil($this->total_pages / $this->limit); //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1; //last page minus 1

        /*
                Now we apply our rules and draw the pagination object.
                We're actually saving the code to a variable in case we want to draw it more than once.
        */
        if ($lastpage > 1) {
            if ($this->page) {
                if ($this->page > 1) {
                    $this->pagination .= $this->firstT ? $this->renderItemTPL($this->firstT, 0) : '';
                    $this->pagination .=  $this->prevT ? $this->renderItemTPL( $this->prevT, $prev) : '';
                } else {
                    $this->pagination .= $this->firstI ? $this->renderItemTPL($this->firstI, 0) : '';
                    $this->pagination .=  $this->prevI ? $this->renderItemTPL( $this->prevI, $prev) : '';
                }
            }
            //pages
            if ($lastpage < 7 + ($this->adjacents * 2)) { //not enough pages to bother breaking it up
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    $tpl = ($counter == $this->page) ? $this->currentT : $this->numberT;
                    $this->pagination .= $this->renderItemTPL($tpl, $counter);
                }
            } elseif ($lastpage > 5 + ($this->adjacents * 2)) { //enough pages to hide some
                //close to beginning; only hide later pages
                if ($this->page < 1 + ($this->adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($this->adjacents * 2); $counter++) {
                        $tpl = ($counter == $this->page) ? $this->currentT : $this->numberT;
                        $this->pagination .= $this->renderItemTPL($tpl, $counter);
                    }
                    $this->pagination .= $this->renderItemTPL($this->dotsT, $counter);
                    $this->pagination .= $this->renderItemTPL($this->numberT, $lpm1);
                    $this->pagination .= $this->renderItemTPL($this->numberT, $lastpage);
                } //in middle; hide some front and some back
                elseif ($lastpage - ($this->adjacents * 2) > $this->page && $this->page > ($this->adjacents * 2)) {
                    $this->pagination .= $this->renderItemTPL($this->numberT, 1);
                    $this->pagination .= $this->renderItemTPL($this->numberT, 2);
                    $this->pagination .= $this->renderItemTPL($this->dotsT, 3);

                    for ($counter = $this->page - $this->adjacents; $counter <= $this->page + $this->adjacents; $counter++){
                        $tpl = ($counter == $this->page) ? $this->currentT : $this->numberT;
                        $this->pagination .= $this->renderItemTPL($tpl, $counter);
                    }
                    $this->pagination .= $this->renderItemTPL($this->dotsT, $counter);
                    $this->pagination .= $this->renderItemTPL($this->numberT, $lpm1);
                    $this->pagination .= $this->renderItemTPL($this->numberT, $lastpage);
                } //close to end; only hide early pages
                else {
                    $this->pagination .= $this->renderItemTPL($this->numberT, 1);
                    $this->pagination .= $this->renderItemTPL($this->numberT, 2);
                    $this->pagination .= $this->renderItemTPL($this->dotsT, 3);

                    for ($counter = $lastpage - (2 + ($this->adjacents * 2)); $counter <= $lastpage; $counter++){
                        $tpl = ($counter == $this->page) ? $this->currentT : $this->numberT;
                        $this->pagination .= $this->renderItemTPL($tpl, $counter);
                    }
                }
            }
            if ($this->page) {
                if ($this->page < $counter - 1){
                    $this->pagination .=  $this->nextT ? $this->renderItemTPL( $this->nextT, $next) : '';
                    $this->pagination .=  $this->lastT ? $this->renderItemTPL( $this->lastT, $lastpage) : '';
                } else {
                    $this->pagination .=  $this->nextI ? $this->renderItemTPL( $this->nextI, $next) : '';
                    $this->pagination .=  $this->lastI ? $this->renderItemTPL( $this->lastI, $lastpage) : '';
                }

                if ($this->showCounter) {
                    $this->pagination .= "<div class=\"pagination_data\">($this->total_pages Pages)</div>";
                }
            }
        }

        return true;
    }

    protected function renderItemTPL($tpl, $num){
        return str_replace(array('[+num+]', '[+link+]'), array($num, $this->get_pagenum_link($num)), $tpl);
    }
}
