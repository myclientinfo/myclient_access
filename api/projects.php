<?php 
require_once '../../classes/config.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
	header("Status: 404 Not Found"); 
	die();
} 

if(!empty($_POST) && isset($_POST['access_type_id'])){
	$data = new Project(false, false);
	if(isset($_POST['delete_form']) && $_POST['delete_form'] == 1){
		$_POST['active'] = 0;
	}
	$id = $data->save();
	
	$query = 'UPDATE users SET last_project = '.$id.' WHERE id = '.$_SESSION['user']['id'];
	$_SESSION['user']['last_project'] = $id;
	@Site::runQuery($query);
}

$clients = new Client(false, true);

if(!empty($clients->data_listing)){
	foreach($clients->data_listing as $c){
		$client_array[$c['name']] = $c['id'];
	}
	
	ksort($client_array);
	$client_array = array_flip($client_array);
} else {
	$client_array = array();
}

if(isset($_POST['id'])){
	$project = new Project($_POST['id'], false);
	$data = $project->data;
} else {
	$data= $_POST;
	$data['access_type_id'] = 2;
}

$access_array = Site::getLookupTable('access_types', 'id', 'access_type', 'id');
/*
echo '<pre>';
print_r($data);
echo '</pre>';
*/
?>

<h3>Add Project</h3>
<?php


echo Site::drawForm('new_project');
echo Site::drawHidden('delete_form', 0);
echo Site::drawHidden('access_type_changed', 0);
echo Site::drawHidden('id', @(int)$_POST['id']);
echo Site::drawHidden('client_id', $_POST['client_id']);
echo Site::drawText('name', @$data['name'], 'Name');
echo Site::drawSelect('access_type_id', $access_array, $data['access_type_id'], 1, 'Access');
echo Site::drawSelect('client_id', $client_array, $data['client_id'], 1, 'Client');
?>
<div class="delete lsf">&#xe12c;</div>
<button class="styled">Save Project <span class="lsf">&#xe031;</span></button>
<?php
echo Site::drawForm();
?>


