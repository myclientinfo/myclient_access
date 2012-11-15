<?php

class Dataset extends Site{
	
	var $content_type = 'Data Set';
	var $table_name = 'data_sets';
	var $table_fields = array('id', 'agency_id', 'name', 'fields');
	var $table_alias = 'ds';
	var $field_list = '*, AES_DECRYPT(name,"KEY_AES") as name, AES_DECRYPT(fields,"KEY_AES") as fields';
	var $crypt_array = array('name', 'fields');
	
	function save(){
		$_POST['name'] = $_SESSION['data']->quickEncrypt($_POST['name']);
		if(is_array($_POST['fields'])) $_POST['fields'] = implode('|', $_POST['fields']);
		$_POST['fields'] = $_SESSION['data']->quickEncrypt($_POST['fields']);
		$_POST['agency_id'] = $_SESSION['user']['agency_id'];
		return parent::save();
		$temp = new Dataset(false, true);
		$this->data_listing = $temp->data_listing;
		$_SESSION['datasets'] = $temp->getSorted();
	}
	
	function getOne($id){
		$data = parent::getOne($id);
		$data = $_SESSION['data']->decryptArray($data);
		return $data;
	}
	
	
	function getAll(){
		$data = parent::getAll();
		$data = $_SESSION['data']->decryptArray($data);
		return $data;
	}
	
	
	function getDefaultDatasets(){
		
		$query = 'SELECT par.name AS parent, cat.name AS category, d.name, d.fields, d.common, d.id FROM default_datasets AS d 
				LEFT JOIN default_dataset_categories AS cat ON cat.id = d.subcategory_id  
				LEFT JOIN default_dataset_categories AS par ON par.id = d.category_id
				ORDER BY parent, category, name';
		
		$datasets = Site::getData($query, false);
		//$GLOBALS['debug']->($datasets);
		
		$new_datasets = array();
		
		
		
		foreach($datasets as $v){
			$v['parent'] = $v['parent']==''?'empty':$v['parent'];
			$v['category'] = $v['category']==''?'empty':$v['category'];
			$new_datasets[$v['parent']][$v['category']][] = $v;
		}
		
		return $new_datasets;
		
	}
	
	function getSorted($array = array()){
		
		foreach($this->data_listing as $d){
			$array[$d['id']] = $d;
		}
		return $array;
	}
	
	
}	
?>