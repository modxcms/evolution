<?php
include_once(MODX_BASE_PATH . "assets/lib/Helpers/Collection.php");

class DLCollection extends \Helpers\Collection{
    protected $modx = null;

    public function __construct($modx, $data = array()){
        $this->modx = $modx;
		switch(true){
			case is_resource($data):
			case (is_object($data) && $data instanceof \mysqli_result):
				$this->fromQuery($data, false);
				break;
			case is_array($data):
				$this->data = $data;
				break;
			case (is_object($data) && $data instanceof \IteratorAggregate):
				foreach($data as $key => $item){
					$this->add($item, $key);
				}
				break;
			default:
				$this->add($data);
		}
    }

	public function fromQuery($q, $exec = true){
		$i = 0;
		if($exec){
			$q = $this->modx->db->query($q);
		}
		while($row = $this->modx->db->getRow($q)){
			$data = $this->create($row);
			$this->add($data);
			$i++;
		}
		return $i;
	}

    public function create(array $data = array()){
        return new static($this->modx, $data);
    }
}