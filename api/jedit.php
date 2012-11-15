<?php 
include_once '../../classes/config.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
	header('location: /');
	die();
} 

if(!isset($_POST['value']) || !isset($_POST['id']) || $_POST['value'] =='' || $_POST['id'] == ''){
	header('location: /');
	die();
}

list($target['type'], $target['id']) = explode('_', $_POST['id']);

// SERIOUSLY NEED TO ADD SOME PERMISSION CHECKING IN HERE

$encrypted_value = $_SESSION['data']->quickEncrypt($_POST['value']);


$query = 'UPDATE '.$target['type'] . ' SET name = AES_ENCRYPT("' . $encrypted_value . '","'.KEY_AES.'") WHERE id = '.$target['id'];
Site::runQuery($query);
echo $_POST['value'];
?>