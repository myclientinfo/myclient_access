<?php 
include_once 'classes/config.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
	header('location: /');
	die();
} 

if(!empty($_POST)){
	$data = new Project(false, false);
	$data->save();
}

$clients = new Client(false, true);
$projects = new Project(false, true);
$datasets = new Dataset(false, true);
$data = new Data(false, true);


$menu = new Template('menu');
$main = new Template('projects');
//$GLOBALS['debug']->printr($projects->data_listing);
$main->set('clients', $clients->data_listing);

$raw_projects = $projects->data_listing;
$clean_projects = User::enforcePermissions($raw_projects);

$main->set('projects', $clean_projects);
$main->set('datasets', Site::formatData($datasets->data_listing));
$main->set('data', $data->data_listing);

$template = new Template('index');
$template->set('content', $main->fetch());
$template->set('menu', $menu->fetch());
echo $template->fetch();
?>


