<?php
$GLOBALS['project'] = 'access.myclientinfo';

if($_SERVER['HTTP_HOST'] == $GLOBALS['project'] || $_SERVER['HTTP_HOST'] == 'myclientinfo' ){
	$dbuser = "root";
	$dbpass = "";
	$db = 'myclient_cli';
	define('KEY_LOC', '/Applications/XAMPP/keys/');
	$GLOBALS['db'] = new mysqli('localhost', $dbuser, $dbpass, $db);
} else {
	$dbuser = "myclient_cli";
	$dbpass = "mc1v4ul77";
	$db = 'myclient_cli';
	define('KEY_LOC', '/home/myclient/keys/');
	$GLOBALS['db'] = new mysqli("localhost", $dbuser, $dbpass, $db, ini_get("mysqli.default_port"), "/var/lib/mysql/mysql.sock");
	if ($GLOBALS['db']->connect_error) {
	    die('Connect Error: ' . $GLOBALS['db']->connect_error);
	}
}

function __autoload($class_name) {
	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/../classes/class.' . strtolower($class_name) . '.php')){
		require_once $_SERVER['DOCUMENT_ROOT'].'/../classes/class.' . strtolower($class_name) . '.php';
	}
}

define('KEY_INTERNAL',	'zeEOqeEZSfMaLfDZld003XpMlPPNx7ACuPxkiWQsDyZwyNiiCUWsKhCSbWQJmJb3LHmntbH24ORtIww2AfsfqF40mcsw9JuwtmOc');
define('KEY_URL',		'ZpNZ3eC7V9jbyTWm5zPmxEp1lhQe6gaLX96eddepRxClTt1zw2WmIljoA3zLUuPMqdmfs9q9uhEKhRnowl3HWVdh2sVrUSfb9KjJ');
define('KEY_SALT',		'EycmHfW4fxODTx67kzMGLTKlsq3YXI3Q7070wvhzl5KFkkK86jxx1qwHX8SWESAjqUfIA5hP93p8p6l7hHwLSQ3sHW3l7AvhfofV');
define('KEY_AES',		'UZZH9xqTseGU5');

/*
bP7BFTE5wHa3wHxtA0lgkWsOn5lke1ioX5DQo3k8qGLo1Pu0mqksaEKBUbZUiQSbwfb6tJvlRmygWQ1rg5kacEByguAOyZTl4tM9
*/
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/../classes/class.debug.php')){
	$GLOBALS['debug'] = new Debug(false);
	$GLOBALS['debug']->debugStatus(true);	
}

session_start();


//print_r($_SESSION);

if(!$_SESSION['data']->td && isset($_SESSION['user']['id'])){
	$_SESSION['data'] = new Data($_SESSION['user']['id']);
	
}

if(isset($_SESSION['user']['id']) && !isset($_SESSION['datasets'])){
	$data = new Dataset(false, true);
	$_SESSION['datasets'] = $data->getSorted();
}
?>