<?php
include_once 'classes/config.php';
$new_user = false;
if(!empty($_POST)){
	User::addUser();
	$new_user = true;
	//header('location: ');

}
$show_menu = false;
$main = new Template('index_signup');

$menu = new Template('menu');
$menu->set('show_menu', $show_menu);
//$GLOBALS['debug']->printr();
$template = new Template('index_front');
$template->set('content', $main->fetch());
$template->set('menu', $menu->fetch());
echo $template->fetch();

?>