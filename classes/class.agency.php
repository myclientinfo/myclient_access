<?php

class Agency extends Site{
	
	var $content_type = 'Agency';
	var $table_name = 'agencies';
	var $table_fields = array('id', 'name', 'size', 'location', 'agency', 'country_id', 'start_time', 'last_payment', 'payment_due', 'account_type_id', 'status_id', 'active');
	var $table_alias = 'co';
	var $crypt_array = array('agency');
	var $uid = true;
	
	var $table_field_mapping = array(
		'id' => array('type' => 'hidden'),
		'country_id' => array('type' => 'select', 'link' => 'true', 'table' => 'countries', 'as' => 'cn', 'on' => 'cn.id = co.country_id'),
		'account_type_id' => array('type' => 'select', 'link' => 'true', 'table' => 'account_types', 'as' => 'at', 'on' => 'ac.id = co.account_type_id')
	);
	
	
	function connectUtils(){
		if($_SERVER['HTTP_HOST'] == $GLOBALS['project'] || $_SERVER['HTTP_HOST'] == 'myclientinfo' || $_SERVER['HTTP_HOST'] == 'mci-access' ){
			$db = new mysqli($_SERVER['DB2_HOST'], $_SERVER['DB2_USER'], $_SERVER['DB2_PASS'], $_SERVER['DB2_NAME']) or die('is fucked');
		} else {
			$db = new mysqli($_SERVER['DB2_HOST'], $_SERVER['DB2_USER'], $_SERVER['DB2_PASS'], $_SERVER['DB2_NAME'], ini_get("mysqli.default_port"), "/var/lib/mysql/mysql.sock");
		}
		return $db;
	}
	
	function closeUtils($db){
		$db->close();
	}
	
	function createAgencyKey($id){
		
		$db = Agency::connectUtils();
		
		$unique = $this->getUniqueCode(32);
		
		$db->query('INSERT INTO user_keys(agency_id, user_key) VALUES('.$id.', "'.$unique.'")');
		
		return $unique;
		
		
		/*
		if(!file_exists(KEY_LOC.$id.'.key')){
			$text = $this->getUniqueCode(32);
			if(!file_put_contents(KEY_LOC.$id.'.key', $text)){
				//echo 'file creation failed because:<br>';
				//echo KEY_LOC.$id.'.key<br>';
				
			} else {
				//echo KEY_LOC.$id.'.key<br>';
				
			}
		}
		*/
		
	}
	
	function getUniqueCode($length = ""){	
		$code = md5(uniqid(rand(), true));
		if ($length != "") return substr($code, 0, $length);
		else return $code;
	}
	
	function save(){
		return parent::save();
	}
	
	
	function setSeenDefaults(){
		$query = 'UPDATE agencies SET ds_qs_seen = 1 WHERE id = ' . $_SESSION['user']['agency_id'] . ' AND unique_id = "' . $_SESSION['user']['agency_unique_id'] .'"';
		parent::runQuery($query);
		$_SESSION['user']['ds_qs_seen'] = 1;
		
	}
	
}	