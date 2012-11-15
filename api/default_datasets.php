<?php 
include_once '../../classes/config.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
	header('location: /');
	die();
} 

if(!empty($_POST)){
	$data = new Dataset(false, false);
	$data->save();
}

$dataset = new Dataset(false, true);

$main = new Template('datasets_default');

$main->set('default_datasets', $dataset->getDefaultDatasets());
echo $main->fetch();
?>


