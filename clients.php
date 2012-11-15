<?php 
include_once '../classes/config.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
	header('location: /');
	die();
} 

if(!empty($_POST)){
	$data = new Client(false, false);
	$data->save();
}

$data = new Client(false, true);
$main = new Template('clients');
$main->set('content', $data->data_listing);
$menu = new Template('menu');

$template = new Template('index');
$template->set('content', $main->fetch());
$template->set('menu', $menu->fetch());
echo $template->fetch();
?>


