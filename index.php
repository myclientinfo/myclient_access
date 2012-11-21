<?php 

include_once 'classes/config.php';

$auth = new Auth();



if(!$auth->isLoggedIn()) {

	if(isset($_POST['login_email'])){
		$result = $auth->verifyUser();
		
		if($result){
			$auth->setLocalCookie($result['id']);
			$auth->loadUser($result['id']);
			//$GLOBALS['debug']->printr($_SESSION['user']);
		}
		$msg = (!$result?'?un='.$_POST['login_email']:'');
		header('location:'.$_SERVER['PHP_SELF'].$msg);
	}

    $main = new Template('login_form');
    $show_menu = false;

} else {
	//$GLOBALS['debug']->printr($_SESSION['user']);
	$main = new Template('dashboard');
	$show_menu = true;
} 

$menu = new Template('menu');
$menu->set('show_menu', $show_menu);
//$GLOBALS['debug']->printr();
$template = new Template('index');
$template->set('content', $main->fetch());
$template->set('menu', $menu->fetch());
echo $template->fetch();

?>


