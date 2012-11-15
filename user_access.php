<?php 
include_once '../classes/config.php';

$auth = new Auth();

if(!$auth->isLoggedIn()) {
	header('location: /');
	die();
} 


if(!empty($_POST)){
	
	$user = new User(false, false);
	$user_id = $user->addAgencyUser();
	header('location: /users/permissions/?id='.$user_id);
	
}


$_GET['ob'] = 'first_name';

$project = new Project(false, true);
$projects = $project->typeSortedListing();
$access = $project->getAllAccess();
//echo 'test';


$data = new User(false, true);

$main = new Template('user_access');
$main->set('content', $data->data_listing);
$main->set('access', $access);
$main->set('projects', $projects);
$menu = new Template('menu');

$template = new Template('index');
$template->set('content', $main->fetch());
$template->set('menu', $menu->fetch());
echo $template->fetch();
?>


