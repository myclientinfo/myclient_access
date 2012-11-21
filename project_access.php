<?php 
include_once 'classes/config.php';

$auth = new Auth();

if(!$auth->isLoggedIn()) {
	header('location: /');
	die();
} 


$_GET['ob'] = 'first_name';

$project = new Project(false, true);

$projects = $project->typeSortedListing();
//$GLOBALS['debug']->printr($project->data_listing);


$access = $project->getAllAccess();



$data = new User(false, true);

$main = new Template('project_access');
$main->set('content', $data->data_listing);
$main->set('access', $access);
$main->set('projects', $projects);
$menu = new Template('menu');

$template = new Template('index');
$template->set('content', $main->fetch());
$template->set('menu', $menu->fetch());
echo $template->fetch();
?>


