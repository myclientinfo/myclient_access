<?php 
include_once 'classes/config.php';

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

//__construct($id, $list, $admin = false, $table_name = '', $content_type = '', $where = false
$where = ' agency_id = '.$_SESSION['user']['agency_id'];
$data = new User(false, true, false, 'users', 'Users', $where);

$main = new Template('users');
$main->set('content', $data->data_listing);
$menu = new Template('menu');

$template = new Template('index');
$template->set('content', $main->fetch());
$template->set('menu', $menu->fetch());
echo $template->fetch();
?>


