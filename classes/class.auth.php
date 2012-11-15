<?php

class Auth{
	
	var $key = false;
	var $id = false;
	
    function __construct(){
    
        
    }

    function isLoggedIn(){
    	
        if(Auth::checkSession() && Auth::checkLocalCookie()){
        	//$GLOBALS['debug']->printr('sessionandcookie');
            Auth::setLocalCookie($_SESSION['user_id']);
            return true;
        } elseif(Auth::checkSession() && !Auth::checkLocalCookie()) {
        	//$GLOBALS['debug']->printr('sessionbutnocookie');
	        Auth::setLocalCookie($_SESSION['user_id']);
            return true;
        } elseif(!Auth::checkSession() && Auth::checkLocalCookie()) {
        	//$GLOBALS['debug']->printr('nosessionbutcookie');
        	$user_id = $_COOKIE['user_id'];
            Auth::setLocalCookie($user_id);
            $auth_array = Auth::loadUser($user_id);
            Auth::setSession($auth_array);
            return true;
        } else {
        	//$GLOBALS['debug']->printr('notadamnthing');
        	return false;
        }
    }
	
	function checkLocalCookie(){
        if(isset($_COOKIE['user_id'])) return true;
        else return false;
    }

    function checkSession(){
        if(isset($_SESSION['user'])) return true;
        else return false;
    }

    function setSession($auth_array){
        $_SESSION['user_id'] = $auth_array['user_id'];
        $_SESSION['user'] = $auth_array;
    }

    function loadUser($id){
    	$this->id = $id;
    	
    	$query = 'SELECT co.name AS country, a.*, a.unique_id AS agency_unique_id, u.unique_id AS user_unique_id, 
    			AES_DECRYPT(a.agency,"'.KEY_AES.'") as agency, 
        		u.*, AES_DECRYPT(first_name,"'.KEY_AES.'") as first_name , AES_DECRYPT(last_name,"'.KEY_AES.'") as last_name, 
        		AES_DECRYPT(email,"'.KEY_AES.'") as email, last_project FROM users AS u 
        	LEFT JOIN agencies AS a ON a.id = u.agency_id	
        	LEFT JOIN countries AS co ON co.id = a.country_id	
        	WHERE u.id = '.$id.' LIMIT 1';
        $data = Site::getData($query, true);
        $encrypt = new Encrypt(KEY_INTERNAL);
        
        $data = $encrypt->decryptArray($data, false, array('start_time','is_owner', 'last_project', 'ds_qs_seen'));
        $_SESSION['user'] = $data;
        
        if(!isset($_SESSION['data'])) $_SESSION['data'] = new Data($id);
        
        $query = 'SELECT id, project_id FROM user_project_access WHERE user_id = '.$id;
        $_SESSION['user']['permissions'] = Site::getData($query, false, 'id', 'project_id');
        
        return $_SESSION['user'];
        
    }
    
    
    
    function logOut(){
    	setcookie ( 'user_id', '', time()-13600, '/');
    	$_COOKIE = array();
    	$_SESSION = array();
    }

    function verifyUser(){
        
        $un = $GLOBALS['db']->real_escape_string($_POST['login_email']);
        $pw = $GLOBALS['db']->real_escape_string($_POST['login_password']);
    
        if($un==''||$pw=='') return false;
        
		$encrypt = new Encrypt(KEY_INTERNAL);
		
		$query = 'SELECT id, AES_DECRYPT(salt,"'.KEY_AES.'") as salt, AES_DECRYPT(password,"'.KEY_AES.'") as password FROM users WHERE AES_DECRYPT(email,"'.KEY_AES.'")  = "'.$encrypt->encrypt($un).'" LIMIT 1';
		//
		$result = Site::getData($query, true);
		
		if(isset($_GET['debug_login'])){
			
			
			if(empty($result)){
				
				$query = 'SELECT id, AES_DECRYPT(salt,"'.KEY_AES.'") as salt, AES_DECRYPT(password,"'.KEY_AES.'") as password, AES_DECRYPT(email,"'.KEY_AES.'") as email FROM users LIMIT 1';
				$result = Site::getData($query, true);
				
			}
		}
		if(!$result){
			return false;
		} else {
		
			if($result['password'] == hash('whirlpool', $result['salt'].$pw)){
				return $result['id'];
			} else {
				return false;
			}
		
		}
		
    }

    function setLocalCookie($user_id){
        $expire_time = time()+60*60*24*30;
        setcookie('user_id', $user_id, $expire_time);
    }
    
    
    

    function refreshLocalCookie($user_id){
        $expire_time = time()+60*60*24*30;
        setcookie('user_id', $user_id, $expire_time, '/');
    }
}
?>