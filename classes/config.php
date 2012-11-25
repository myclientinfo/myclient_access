<?php
$GLOBALS['project'] = 'access.myclientinfo';

if($_SERVER['HTTP_HOST'] == $GLOBALS['project'] || $_SERVER['HTTP_HOST'] == 'myclientinfo' || $_SERVER['HTTP_HOST'] == 'mci-access' ){
	$GLOBALS['db'] = new mysqli($_SERVER['DB1_HOST'], $_SERVER['DB1_USER'], $_SERVER['DB1_PASS'], $_SERVER['DB1_NAME']);
} else {
	$GLOBALS['db'] = new mysqli($_SERVER['DB1_HOST'], $_SERVER['DB1_USER'], $_SERVER['DB1_PASS'], $_SERVER['DB1_NAME'], ini_get("mysqli.default_port"), "/var/lib/mysql/mysql.sock");
	if ($GLOBALS['db']->connect_error) {
	    die('Connect Error: ' . $GLOBALS['db']->connect_error);
	}
}

function __autoload($class_name) {
	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/classes/class.' . strtolower($class_name) . '.php')){
		require_once $_SERVER['DOCUMENT_ROOT'].'/classes/class.' . strtolower($class_name) . '.php';
	}
}
session_start();
if(isset($_GET['log_out'])){
	Auth::logOut();
	header('location:/');
	die();
} 


define('KEY_INTERNAL',	'zeEOqeEZSfMaLfDZld003XpMlPPNx7ACuPxkiWQsDyZwyNiiCUWsKhCSbWQJmJb3LHmntbH24ORtIww2AfsfqF40mcsw9JuwtmOc');
define('KEY_URL',		'ZpNZ3eC7V9jbyTWm5zPmxEp1lhQe6gaLX96eddepRxClTt1zw2WmIljoA3zLUuPMqdmfs9q9uhEKhRnowl3HWVdh2sVrUSfb9KjJ');
define('KEY_SALT',		'EycmHfW4fxODTx67kzMGLTKlsq3YXI3Q7070wvhzl5KFkkK86jxx1qwHX8SWESAjqUfIA5hP93p8p6l7hHwLSQ3sHW3l7AvhfofV');
define('KEY_AES',		'UZZH9xqTseGU5');

/*
bP7BFTE5wHa3wHxtA0lgkWsOn5lke1ioX5DQo3k8qGLo1Pu0mqksaEKBUbZUiQSbwfb6tJvlRmygWQ1rg5kacEByguAOyZTl4tM9
*/
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/classes/class.debug.php')){
	$GLOBALS['debug'] = new Debug(false);
	$GLOBALS['debug']->debugStatus(true);	
}

if(!isset($_SESSION['data'])  && isset($_SESSION['user']['id'])){
	$_SESSION['data'] = new Data($_SESSION['user']['id']);
	
}

if(isset($_SESSION['user']['id']) && !isset($_SESSION['datasets'])){
	$data = new Dataset(false, true);
	$_SESSION['datasets'] = $data->getSorted();
}

?>