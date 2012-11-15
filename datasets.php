<?php 
include_once '../classes/config.php';

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

$menu = new Template('menu');
$main = new Template('datasets');

$main->set('content', $dataset->data_listing);
$main->set('default_datasets', $dataset->getDefaultDatasets());


$template = new Template('index');
$template->set('content', $main->fetch());
$template->set('menu', $menu->fetch());
echo $template->fetch();
?>


