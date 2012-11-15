<?php

class Client extends Site{
	
	var $content_type = 'Client';
	var $table_name = 'clients';
	var $table_fields = array('id', 'agency_id', 'name', 'active');
	var $table_alias = 'c';
	var $field_list = '*, AES_DECRYPT(name,"KEY_AES") as name';
	var $crypt_array = array('name');
	var $uid = true;
	
	function save(){
		$_POST['name'] = $_SESSION['data']->quickEncrypt($_POST['name']);
		$_POST['agency_id'] = $_SESSION['user']['agency_id'];
		return parent::save();
	}
	
	
	function getOne($id){
		
		
		return $_SESSION['data']->decryptArray(parent::getOne($id));
	}
	
	function getAll(){
		$query = 'SELECT '.str_replace('KEY_AES', KEY_AES, $this->field_list).' FROM clients AS c WHERE c.agency_id = '.$_SESSION['user']['agency_id'];
		$data = Site::getData($query, false, 'id');
		$data = $_SESSION['data']->decryptArray($data);
		return $data;
	}
	
}	
?>