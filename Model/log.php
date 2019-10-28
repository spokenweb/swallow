<?php
class logger{
	public $updates = array();

	function add($type,$message){
		$record = array();
		$record[$type] = $message;
		$this->updates[] = $record;
	}

	

	static function write($mgs){
			$now =  new DateTime(date('Y-m-d H:i:0'));
		$name = "../log/".$now->format("Y-m").".log";
		$fp = fopen($name,'a');
		$line = $now->format('Y-m-d H:i:00')."|".$mgs.PHP_EOL;
		
		fwrite($fp,$line);
		fclose($fp);
	}

	static function console_log($mgs){
		echo("<script>console.log('".$mgs."')</script>");
	}
}
?>