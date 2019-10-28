<?php
abstract class db{

	public $conn;
	public $list;
	public $index;
	public $total;
	
	function __construct($in_conn){
		$this->conn = $in_conn;
	}
	
	function __destruct() {
   }
   
	public function go($in_index){
		if ($in_index < $this->total and $in_index >= 0){
			$this->index = $in_index;
		}
		$this->update();
	}

	abstract public function update();
   
	abstract public function select($in_id);
	
	abstract public function selectAll();
	
	abstract public function save();
	
	abstract public function delete($in_id);
}

?>