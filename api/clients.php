<?php 
include_once '../classes/config.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
	header("Status: 404 Not Found"); 
	die();
} 

//print_r($_POST);

if(!empty($_POST) && !isset($_POST['no_save']) && isset($_POST['unique_id'])){
	$data = new Client(false, false);
	$data->save();
}

if(isset($_POST['id'])){
	$content = new Client($_POST['id'], false);
	$data = $content->data;
	
}

?>

<h3>Edit Client</h3>

<?php

echo Site::drawForm('add_client');
echo Site::drawHidden('id', @$data['id']);
echo Site::drawHidden('unique_id', @$data['unique_id']);
echo Site::drawText('name', @$data['name'], 'Name');
?>
<button class="styled">Save Client <span class="lsf">&#xe031;</span></button>
<?php
echo Site::drawForm();

?>