<?php 
include_once '../../classes/config.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
	header('location: /');
	die();
} 
//print_r($_POST);
if(isset($_POST['project_id'])){
	$query = 'UPDATE users SET last_project = '.$_POST['project_id'].' WHERE id = '.$_SESSION['user']['id'];
	$_SESSION['user']['last_project'] = $_POST['project_id'];
	@Site::runQuery($query);
}
?>