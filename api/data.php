<?php 
include_once '../classes/config.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
	header('location: /');
	die();
} 


if(!empty($_POST)&&$_POST['save_data']==1){
	if($_POST['type']=='add'){
		$data = new Data();
		$data->saveNew();
	} else {
		$data = new Data();
		$data->saveEdit();
	}
	
}

$id = (int)@$_POST['data_set_id'];
$group_id = (int)@$_POST['data_group_id'];

$dataset = new Dataset($id, false);

if($_POST['mode']=='edit'){
	$content = Data::getGroup($group_id);
} else {
	$temp = $dataset->data;
	
	$i = 1;
	foreach(explode('|', $temp['fields']) as $f){
		$content[$i] = array('field'=>$f);
		$i++;
	}
}

$main = new Template('data');

$main->set('content', $content);



echo $main->fetch();
?>


