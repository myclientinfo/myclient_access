<h3>Edit <?php echo @$content['data_set']? @$content['data_set'] : @$_SESSION['datasets'][$_POST['data_set_id']]['name'] ?></h3>

<?php

//$GLOBALS['debug']->printr($_SESSION['datasets']);



echo Site::drawForm('new_dataset');
echo Site::drawHidden('save_data', 1);
echo Site::drawHidden('type', @$_POST['mode']);
echo Site::drawHidden('project_id', @$_POST['project_id']);
echo Site::drawHidden('data_set_id', @$_POST['data_set_id']);
echo Site::drawHidden('data_group_id', @$_POST['data_group_id']);
echo Site::drawHidden('group_unique_id', @$content['group_unique_id']);
echo Site::drawText('group_name', @$content['group_name']?$content['group_name']:@$_SESSION['datasets'][$_POST['data_set_id']]['name'], 'Title', array('class'=>'field_field'));

unset($content['group_unique_id'], $content['data_group_id'], $content['group_name'], $content['data_set']);

foreach($content as $i => $f){
	echo Site::drawHidden('fields['.$i.'][field_unique_id]', @$f['field_unique_id']);	
	echo Site::drawText('fields['.$i.'][field]', $f['field'], false, array('class'=>'field_field'));
	echo Site::drawHidden('fields['.$i.'][data_unique_id]', @$f['data_unique_id']);
	echo Site::drawText('fields['.$i.'][data]', @$f['value']);
}
?>
<button class="styled">Save Data <span class="lsf">&#xe031;</span></button>

<?php
//echo Site::drawSubmit('save', 'submit');
echo Site::drawForm();
?>