<?php 
include_once '../classes/config.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
	header("Status: 404 Not Found"); 
	die();
} 

//print_r($_POST);

if(!empty($_POST) && !isset($_POST['no_save']) && isset($_POST['unique_id'])){
	$data = new User(false, false);
	$data->save();
}

if(isset($_POST['id'])){
	$content = new User($_POST['id'], false);
	$data = $content->data;
	
}

?>

<h3>Edit User</h3>

<?php

echo Site::drawForm('new_user');
echo Site::drawText('first_name', $data['first_name'], 'First Name');
echo Site::drawText('last_name', $data['last_name'],'Last Name').BR2;
echo Site::drawEmail('email', $data['email'], 'Email').BR2;
//echo Site::drawPassword('password', '', false, array('placeholder'=>'Password')).BR2;
//echo Site::drawPassword('conf_password', '', false, array('placeholder'=>'Confirm Password')).BR2;
echo Site::drawCheckbox('is_admin', 1, false, 'Grant admin access?');
?>
<button class="styled">Save User <span class="lsf">&#xe031;</span></button>
<?php
echo Site::drawForm();
?>