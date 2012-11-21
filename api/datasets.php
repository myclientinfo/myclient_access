<?php 
include_once '../classes/config.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
	header("Status: 404 Not Found"); 
	die();
} 

if(isset($_POST['save'])){
	$data = new Dataset(false, false);
	$data->save();
}
?>
<h3>Create Data Template</h3>

<?php

echo Site::drawForm('save_dataset_a');
echo Site::drawHidden('id', 0);
echo Site::drawHidden('save', 1);
echo Site::drawText('name', '', 'Name');
echo Site::drawDiv('dataset_fields');
echo Site::drawText('fields[1]', '', 'Field');
echo Site::drawDiv();?>
<img src="/images/add.png" id="add_dataset_field">
<button class="styled">Save Template <span class="lsf">&#xe031;</span></button>
<?php
//echo Site::drawSubmit('save', 'Save');
echo Site::drawForm();
?>