<?php

class User extends Site{
	
	var $content_type = 'User';
	var $table_name = 'users';
	var $table_fields = array('id', 'agency_id', 'email', 'is_owner', 'first_name', 'last_name', 'status_id', 'password', 'salt', 'active');
	var $field_list = 'u.*, AES_DECRYPT(u.first_name,"KEY_AES") as first_name, AES_DECRYPT(u.last_name,"KEY_AES") as last_name,  AES_DECRYPT(u.email,"KEY_AES") as email ';
	var $table_alias = 'u';
	var $uid = true;
	
	var $crypt_array = array('email', 'first_name', 'last_name', 'password', 'salt');
	
	var $table_field_mapping = array(
		'id' => array('type' => 'hidden'),
		'company_id' => array('type' => 'select', 'link' => 'true', 'table' => 'agencies', 'as' => 'a', 'on' => 'a.id = u.agency_id'),
		'account_type_id' => array('type' => 'select', 'link' => 'true', 'table' => 'account_types', 'as' => 'at', 'on' => 'at.id = a.account_type_id')
	);
	
	function key(){
		return $_SESSION['user']['key'];
	}
	
	function addUser(){
		
		
		$GLOBALS['debug'] = new Debug(false);
		$GLOBALS['debug']->debugStatus(true);
		$encrypt = new Encrypt(KEY_INTERNAL);
		
		foreach(array('agency','first_name','last_name','email') as $f){
			$_POST[$f] = @$encrypt->encrypt($_POST[$f]);
		}
		
		$_POST['salt'] = hash('whirlpool', microtime.$_POST['company'].getmypid());
		$_POST['salt'] = hash('whirlpool', 'test');
		$_POST['password'] = hash('whirlpool', $_POST['salt'].$_POST['password']);
		
		$_POST['start_time'] = date('Y-m-d H:i:s');
		
		if(isset($_GET['debug_login'])){
			echo '<br>Post';
			$GLOBALS['debug']->printr($_POST);
		}
		
		$agency = new Agency(false, false);
		$agency_id = $agency->save();
		$agency->createAgencyKey($agency_id);
		
		$_POST['agency_id'] = $agency_id;
		
		$user = new User(false, false);
		$_POST['company_id'] = $company_id;
		return $user->save();
	
	}
	
	
	function addAgencyUser(){
		
		
		$encrypt = new Encrypt(KEY_INTERNAL);
		
		foreach(array('agency','first_name','last_name','email') as $f){
			$_POST[$f] = @$encrypt->encrypt($_POST[$f]);
		}
		
		//$GLOBALS['debug']->printr($_SESSION['user']);
		//$GLOBALS['debug']->printr($_SESSION['user'], true);
		
		
		$_POST['salt'] = hash('whirlpool', microtime.$_SESSION['user']['company'].getmypid());
		$_POST['salt'] = hash('whirlpool', 'test');
		$_POST['password'] = hash('whirlpool', $_POST['salt'].$_POST['password']);
		
		$_POST['start_time'] = date('Y-m-d H:i:s');
		
		if(isset($_GET['debug_login'])){
			echo '<br>Post';
			$GLOBALS['debug']->printr($_POST);
		}
		
		$_POST['agency_id'] = $_SESSION['user']['agency_id'];
		
		$user = new User(false, false);
		$_POST['company_id'] = $company_id;
		return $user->save();
	
	}
	
	function enforcePermissions($projects){
		
		$new_array = array();
		foreach($projects as $k => $v){
			//echo $v['access_type'];
			if ($v['access_type']=='Agency') {
				$new_array[] = $v;
			} else if($v['access_type']=='Private' && $v['owner_id'] == $_SESSION['user']['id']){
				$new_array[] = $v;
			} else if($v['access_type']=='Set') {
				if($v['owner_id'] == $_SESSION['user']['id']){
					$new_array[] = $v;
				} else {
					
					if(in_array($v['id'], $_SESSION['user']['permissions'])){
						$new_array[] = $v;
					}
					
				}
			}
		}
		
		return $new_array;
	}
	
	function getOne($id){
		$data = parent::getOne($id);
		
		$encrypt = new Encrypt(KEY_INTERNAL);
		$new_data = $encrypt->decryptArray($data);
		
		return $new_data;
	}
	
	function getAll(){
		$query = 'SELECT '.str_replace('KEY_AES', KEY_AES, $this->field_list).' FROM users AS u WHERE u.agency_id = '.$_SESSION['user']['agency_id'];
		//$GLOBALS['debug']->printr($query);
		$data = Site::getData($query, false);
		$encrypt = new Encrypt(KEY_INTERNAL);
		$data = $encrypt->decryptArray($data, false, array('start_time','is_owner', 'last_project', 'ds_qs_seen'));
		//$data = $_SESSION['data']->decryptArray($data);
		//$GLOBALS['debug']->printr($data);
		return $data;
	}
	
	function addProjectTos(){
	
	}
	
	
	function saveProjectAccess(){
	
		foreach($_POST['project'] as $project_id => $project){
		
			$query = 'DELETE FROM user_project_access WHERE project_id = '.$project_id;
			Site::runQuery($query);
			
			$query = 'INSERT INTO user_project_access(user_id, project_id) VALUES ';
			
			$i = 0;
			foreach($project as $user_id){
				$query .= ($i>0?',':'').'('.$user_id.', '.$project_id.')';
				$i++;
			}
			
			Site::runQuery($query);
			
			
		}
		
		
		/*
		foreach($_POST['group'] as $project_id => $project){
		
			$query = 'DELETE FROM user_project_access WHERE project_id = '.$project_id;
			Site::runQuery($query);
			
			$query = 'INSERT INTO user_project_access(user_id, project_id) VALUES ';
			
			$i = 0;
			foreach($project as $user_id){
				$query .= ($i>0?',':'').'('.$user_id.', '.$project_id.')';
				$i++;
			}
			
			Site::runQuery($query);
			
			
		}
		
		*/
		
		
	}
	
	
	
}	
?>