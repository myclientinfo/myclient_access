<?php 
include_once '../../classes/config.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
	header('location: /');
	die();
} 
//print_r($_POST);
if(isset($_POST['ds'])){
	$query = 'UPDATE agencies SET ds_qs_seen = 1 WHERE id = '.$_SESSION['user']['agency_id'];
	$_SESSION['user']['ds_qs_seen'] = 1;
	@Site::runQuery($query);
	
	$query = 'SELECT * FROM default_datasets WHERE id IN('.$_POST['ds'].'0)';
	$data = Site::getData($query, false);
	
	$dataset = new Dataset(false, false);
	foreach($data as $d){
		$_POST['name'] = $d['name'];
		$_POST['fields'] = $d['fields'];
		$dataset->save();
	}
}
?>