<?php
if(empty($content)){
	echo 'You currently have no datasets listed.';
}

echo Site::drawForm('new_dataset');
echo Site::drawText('name', '', 'Name').BR2;
echo Site::drawText('fields', '', 'Fields').BR2;
echo Site::drawSubmit('save', 'submit');
echo Site::drawForm();

if(!empty($content)){
	foreach($content as $c){
		echo $c['name'].' - '.str_replace('|',', ',$c['fields']).'<br>';
	}
}



?>