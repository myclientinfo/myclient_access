<?php

class Project extends Site{
	
	var $content_type = 'Project';
	var $table_name = 'projects';
	var $table_fields = array('id', 'client_id', 'name', 'active', 'access_type_id', 'owner_id');
	var $table_alias = 'p';
	var $field_list = 'p.*, AES_DECRYPT(p.name,"KEY_AES") as name, a.access_type, AES_DECRYPT(c.name,"KEY_AES") AS client';
	var $crypt_array = array('name');
	var $uid = true;
	
	var $table_field_mapping = array(
		'client_id' => array('type' => 'select', 'link' => 'true', 'table' => 'client', 'as' => 'c', 'on' => 'c.id = p.client_id'),
		'access_type_id' => array('type' => 'select', 'link' => 'true', 'table' => 'access_types', 'as' => 'a', 'on' => 'a.id = p.access_type_id')
	);
	
	function getOne($id){
		
		$query = 'SELECT '.str_replace('KEY_AES', KEY_AES, $this->field_list).' FROM projects AS p 
				LEFT JOIN clients AS c ON c.id = p.client_id 
				LEFT JOIN access_types AS a ON a.id = p.access_type_id WHERE c.agency_id = '.$_SESSION['user']['agency_id'] . ' AND p.id = '.$id;
		
		$data = Site::getData($query, true);
		
		$data = $_SESSION['data']->decryptArray($data, false, false, array('access_type'));
		return $data;
	}
	
	function getAll(){
		
		$query = 'SELECT '.str_replace('KEY_AES', KEY_AES, $this->field_list).' FROM projects AS p 
				LEFT JOIN clients AS c ON c.id = p.client_id 
				LEFT JOIN access_types AS a ON a.id = p.access_type_id WHERE p.active = 1 AND c.agency_id = '.$_SESSION['user']['agency_id'];
		
		$data = Site::getData($query, false);
		
		$data = $_SESSION['data']->decryptArray($data, false, false, array('access_type'));
		return $data;
	}
	
	function save(){
		echo 'saving';
		$_POST['name'] = $_SESSION['data']->quickEncrypt($_POST['name']);
		$_POST['owner_id'] = $_SESSION['user']['id'];
		return parent::save();
	}
	
	
	
	function typeSortedListing(){
		
		foreach($this->data_listing as $d){
		
			$array[strtolower($d['access_type'])][] = $d;
			
		}
		return $array;
		
	}
	
	function getEmptyAccess(){
		
	}
	
	function getAllAccess(){
		$query = 'SELECT p.id as project_id, user_id FROM projects AS p 
			JOIN clients AS c ON c.id = p.client_id
			LEFT JOIN user_project_access AS pa ON pa.project_id = p.id AND p.access_type_id = 3
			WHERE c.agency_id = '.$_SESSION['user']['agency_id'];
		$data = Site::getData($query, false);
		
		
		foreach($data as $perm){
			$access_data['project'][$perm['project_id']][] = $perm['user_id'];
		}
		
		$query = 'SELECT project_id, data_group_id, user_id FROM user_datagroup_access AS da 
			JOIN data_groups AS dg ON da.data_group_id = dg.id
			JOIN projects AS p ON dg.project_id = p.id AND p.access_type_id = 4
			JOIN clients AS c ON c.id = p.client_id
			WHERE c.agency_id = '.$_SESSION['user']['agency_id'];
		
		$data = Site::getData($query, false);
		
		foreach($data as $perm){
			$access_data['group'][$perm['project_id']][$perm['data_group_id']][] = $perm['user_id'];
		}
		
		return $access_data;
		
	}
	
	
}	
?>