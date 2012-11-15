<?php

class Data extends Site{
	
	var $key = false;
	var $encrypt = false;
	var $data_listing;
	var $uid = true;
	
	function __construct($id = false, $list = false){
		if($id){
			$this->key = $this->loadKey($id);
			$this->encrypt = new Encrypt($this->key);
		}
		
		if($list){
			$this->data_listing = $this->getAll();
		}
	}
	
	function getGroup($group_id){
	
		$query = 'SELECT f.unique_id AS field_unique_id, v.unique_id AS data_unique_id, dg.unique_id AS group_unique_id, 
						AES_DECRYPT(dg.group_name, "'.KEY_AES.'") as group_name, AES_DECRYPT(value, "'.KEY_AES.'") as value, 
						AES_DECRYPT(f.name, "'.KEY_AES.'") as field, data_field_id, v.id AS value_id, 
					f.data_group_id, AES_DECRYPT(ds.name, "'.KEY_AES.'") as data_set, ds.id as data_set_id, dg.id AS data_group_id  
					FROM data_values AS v 
					LEFT JOIN data_fields AS f ON f.id = v.data_field_id
					LEFT JOIN data_groups AS dg ON dg.id = f.data_group_id
					LEFT JOIN data_sets AS ds ON ds.id = dg.data_set_id
					WHERE f.data_group_id = '.$group_id.'
					ORDER BY order_num';
		
		$data = Site::getData($query, false, 'data_field_id');
		$first = reset($data);
		$data['group_name'] = $first['group_name'];
		$data['data_group_id'] = $first['data_group_id'];
		$data['group_unique_id'] = $first['group_unique_id'];
		$data['data_set'] = $first['data_set'];
		$data = $_SESSION['data']->decryptArray($data, false, false, array('value_id'));
		
		return $data;
	}
	
	function saveEdit(){
		
		$data = $_SESSION['data']->encryptArray($_POST);
		$group_name = $_SESSION['data']->quickEncrypt($_POST['group_name']);
		
		$group_query = 'UPDATE data_groups SET group_name = AES_ENCRYPT("'.$group_name.'", "'.KEY_AES.'") WHERE id = '.$data['data_group_id'] . ' AND unique_id = "'.$data['group_unique_id'].'"';
		echo $group_query."<br>\n";
		parent::runQuery($group_query);
		
		foreach($data['fields'] as $k => $v){
			$field_query = 'UPDATE data_fields SET name = AES_ENCRYPT("'.$v['field'].'", "'.KEY_AES.'") WHERE id = '.$k . ' AND unique_id = "'.$v['field_unique_id'].'"';
			$GLOBALS['debug']->printr($field_query);
			$this->runQuery($field_query);
			$data_query = 'UPDATE data_values SET value = AES_ENCRYPT("'.$v['data'].'", "'.KEY_AES.'") WHERE data_field_id = '.$k. ' AND unique_id = "'.$v['data_unique_id'].'"';
			$GLOBALS['debug']->printr($data_query);
			$this->runQuery($data_query);
			
		}
		
		return true;
		
		
		
	}
	
	function saveNew(){
		$data = $_SESSION['data']->encryptArray($_POST['fields']);
		$group_name = $_SESSION['data']->quickEncrypt($_POST['group_name']);
		$pid = (int)$_POST['project_id'];
		$dsid = (int)$_POST['data_set_id'];
		
		$group_query = 'INSERT INTO data_groups(data_set_id, project_id, group_name, unique_id) VALUES('.$dsid.', '.$pid.', AES_ENCRYPT("'.$group_name.'", "'.KEY_AES.'"), UUID())';
		$GLOBALS['debug']->printr($group_query);
		$group_id = $this->runQuery($group_query);
		foreach($data as $k => $f){
			
			$field_query = 'INSERT INTO data_fields(order_num, name, data_group_id, unique_id) VALUES('.$k.', AES_ENCRYPT("'.$f['field'].'", "'.KEY_AES.'"), '.$group_id.', UUID())';
			$GLOBALS['debug']->printr($field_query);
			$field_id = $this->runQuery($field_query);
			$data_query = 'INSERT INTO data_values(data_field_id, value, unique_id) VALUES('.$field_id.', AES_ENCRYPT("'.$f['data'].'", "'.KEY_AES.'"), UUID())';
			$GLOBALS['debug']->printr($data_query);
			$data_id = $this->runQuery($data_query);
		}
		
	}
	
	
	function getAll(){
		$query = 'SELECT AES_DECRYPT(group_name, "'.KEY_AES.'") as group_name, AES_DECRYPT(value, "'.KEY_AES.'") as value, 
					AES_DECRYPT(f.name, "'.KEY_AES.'") as field, data_field_id, v.id AS value_id, f.data_group_id, 
					AES_DECRYPT(ds.name, "'.KEY_AES.'") as data_set, ds.id as data_set_id, AES_DECRYPT(p.name, "'.KEY_AES.'") AS project, 
					AES_DECRYPT(c.name, "'.KEY_AES.'") AS client_name FROM data_values AS v 
					LEFT JOIN data_fields AS f ON f.id = v.data_field_id
					LEFT JOIN data_groups AS dg ON dg.id = f.data_group_id
					LEFT JOIN data_sets AS ds ON ds.id = dg.data_set_id
					JOIN projects AS p ON p.id = dg.project_id AND p.active = 1
					LEFT JOIN clients AS c ON c.id = p.client_id
					WHERE c.agency_id = '.$_SESSION['user']['agency_id'].'
					ORDER BY client_name, project, data_set, order_num';
		//$GLOBALS['debug']->printr($query);
		$data = Site::getData($query, false);
		
		$data = $_SESSION['data']->decryptArray($data, false, false, array('value_id'));
		foreach($data as $d){
			$new_array[$d['client_name']][$d['project']][$d['group_name']]['groups'][$d['data_group_id']][] = array('field'=>$d['field'], 'value' => $d['value']);
			$new_array[$d['client_name']][$d['project']][$d['group_name']]['data_set_id'] = $d['data_set_id'];
		}
		
		return $new_array;
	}
	
	function quickDecrypt($string){
    	return $this->encrypt->decrypt($string);
    }
    
    function quickEncrypt($string){
    	return $this->encrypt->encrypt($string);
    }
    
    function decryptArray($data, $decrypt_keys = false, $key = false, $skip = array()){
    	
    	if(!$key && $this->key==''){
    		$key = $this->loadKey($this->id);
    	} else if($this->key != '') {
    		$key = $this->key;
    	}
    	
    	$array = array();
    	
    	$encrypt = new Encrypt($key);
    	
    	foreach($data as $k => $val){
    		if(is_array($val)) $array[$k] = $this->decryptArray($val, $decrypt_keys, $key, $skip);
    		else {
    			if(substr($k, -2) =='id' || in_array($k, $skip) || $k == 'active' ){ 
    				//$GLOBALS['debug']->printr($skip);
    				$array[$k] = $val;
    			} else {
    				$array[$k] = @$encrypt->decrypt($val);
    			}
    		}
    	}
    	
    	return $array;
    }
    
    function encryptArray($data, $decrypt_keys = false, $skip = array()){
    	$array = array();
    	
    	foreach($data as $key => $val){
    		if(is_array($val)) $array[$key] = $this->encryptArray($val, $decrypt_keys, $skip);
    		else {
    			if(substr($key, -2) == 'id' || in_array($key, $skip) || $key == 'active' || $val==''  || $key == 'ds_qs_seen') $array[$key] = $val;
    			else {
    				$array[$key] = $this->quickEncrypt($val);
    			}
    		}
    	}
    	
    	return $array;
    }
	
	function loadKey($id){
		$key = file_get_contents(KEY_LOC.$id.'.key');
		$this->key = $key;
		return $key;
	}
	
	

	
}	
?>