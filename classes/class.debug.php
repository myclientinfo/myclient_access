<?php

class Debug{

	var $debug_on = false;
	
	function Debug($debug_on){
		if($debug_on) print "Debug Mode On";
		
	}
	
	function debugStatus($debug_on){
		$this->debug_on = $debug_on;
	}
	
	function printr($value, $die = false){
		if(!$this->debug_on)return false;
		print "<pre style='background-color: white; border: 1px solid black; text-align: left; color: black;position:relative; z-index: 100000;'>";
		print_r($value);
		print "</pre>";
		if($die) die('halted by debug');
	}
	
	function query_fail($query, $die = false){
		$this->printr($GLOBALS['db']->error);
		$this->printr($query);
		if($die) die();
	}
}

?>