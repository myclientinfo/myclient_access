<?php
$client_title_name = false;
$project_title_name = false;
if(isset($_GET['client_id'])){
	$client_id = @Site::urlIn($_GET['client_id']);
	if($client_id && isset($clients[$client_id])){
		$temp_cl_array[] = $clients[$client_id];
		$client_title_name = $clients[$client_id]['name'];
		$clients = $temp_cl_array;
	}
}

if(isset($_GET['project_id'])){
	$client_id = @Site::urlIn($_GET['project_id']);
	if($client_id && isset($clients[$client_id])){
		$temp_cl_array[] = $clients[$client_id];
		$client_title_name = $clients[$client_id]['name'];
		$clients = $temp_cl_array;
	}
}
//$GLOBALS['debug']->printr(@$_GET['project_id']);
//$GLOBALS['debug']->printr($projects);
?>


<script>
$(document).ready(function(){
	
	$(document).on('submit', 'form.add_data', function(e){
		e.preventDefault();
		$.post('/api/data.php', $(this).serialize(),  function(data) {
			$('#alert_box').html(data);
			newAlert('', 'stop');
			
		});
		
	});
	
	$(document).on('dblclick', 'table.data', function(e){
		e.preventDefault();
		
		$.post('/api/data.php', $('form', $(this)).serialize(),  function(data) {
			$('#alert_box').html(data);
			newAlert('', 'stop');
			
		});
	});
	
	$(document).on('submit', '#new_dataset', function(e){
		e.preventDefault();
		$.post('/api/data.php', $('#new_dataset').serialize(),  function(data) {
			endAlert();
			window.location.href=window.location.href;
		});
	});
	
	$(document).on('submit', '.add_project', function(e){
		e.preventDefault();
		$.post('/api/projects.php', $(this).serialize(),  function(data) {
			$('#alert_box').html(data);
			newAlert('', 'stop');
		});
	});
	
	$(document).on('click', '.edit_project', function(e){
		e.preventDefault();
		$.post('/api/projects.php', {id: $(this).parent().attr('id').replace('project_box_','')},  function(data) {
			$('#alert_box').html(data);
			newAlert('', 'stop');
		});
	});
	
	
	$(document).on('change', '.dataset_select', function(){
		if($('option:selected', $(this)).text()=='Add'){
			newAlert('datasets', 'stop');
		}
	});
	
	$(document).on('click', '#add_dataset_field', function(){
		$('#dataset_fields input').each(function(){
			number = $(this).attr('id').replace('fields[', '').replace(']','');
		});
		var n = number*1+1;
		$('#dataset_fields').append('<label for="fields['+n+']">Field</label><input type="text" name="fields['+n+']" id="fields['+n+']">')
		
		position_alert();
	});
	
	$(document).on('submit', '#save_dataset_a', function(e){
		e.preventDefault();
		$.post('/api/datasets.php', $('#save_dataset_a').serialize(),  function(data) {
			endAlert();
			window.location.href=window.location.href;
		});
	});
	
	
	
	$(document).on('click', '.project_permission', function(){
		window.location.href='/projects/permissions/?id='+$(this).attr('id').replace('project_permission_','');
	});
	
	$(document).on('click', '.client_header', function(){
		
		$('.client_header').not($(this)).each(function(){
			$('.client_box', $(this).parent()).hide();
			$(this).parent().removeClass('client_active');
			//$(this).parent().removeClass('active');
		});
		
		$(this).parent().addClass('client_active');
		$('.client_box', $(this).parent()).show();
		
	}); 
	
	
	$(document).on('click', '.project_header', function(){
		
		var clicked = $(this)[0];
		
		$('.project_header').each(function(){
			
			if(clicked != $(this)[0]){
				$('.project_box', $(this).parent()).hide();
			} 
		});
		
		var prj_id = $(this).attr('id').replace('project_header_','');
		$('.project_box', $(this).parent()).show();
		
		$.post('/api/last_project.php', {project_id: prj_id}, function(data){
			
		});
		
		
	}); 
	
	
	$(document).on('click', '.project_permission', function(){
		
		var clicked = $(this)[0];
		
		
	}); 
	
	<?php if(@$_SESSION['user']['last_project']){ ?>
	var last_project_box = $('#project_box_<?php echo $_SESSION['user']['last_project']?>');
	
	$('.project_box', last_project_box).show();
	$('div.client_header', last_project_box.parent().parent()).click();
	$('div.project_header', last_project_box.parent().parent()).click();
	//last_project_box.parent().show();
	
	<?php } ?>
	
	
	
	
	$('.client_header span.client_name, .project_header span.project_name').editable('/api/jedit.php', {event: 'dblclick'});
});

</script>

<style>
.client_name form, div.project_header span.project_name form {float: left;}
.client_name form input, div.project_header span.project_name form input  {height: 20px; position: relative; top: 7px; margin-right: 10px; font-size: 16px;border: 1px solid #cccccc;}
div.project_header span.project_name form input {top:-4px; font-size: 14px; }
</style>

<h2><span class="lsf">&#xe028;</span> <?php echo $client_title_name? $client_title_name : 'All'?> Projects</h2>


<?php

//$GLOBALS['debug']->printr($projects);



if(empty($projects)){
	//echo 'You currently have no projects listed.';
}

if(!empty($clients)){
	foreach($clients as $c){
		$client_array[$c['name']] = $c['id'];
	}
	
	ksort($client_array);
	$client_array = array_flip($client_array);
} else {
	$client_array = array();
	echo '<p>You have no clients listed. Please <a href="/clients/">add a client</a> to continue using the system.</p>';
}

$access_array = Site::getLookupTable('access_types', 'id', 'access_type', 'id');


$datasets[0] = 'Select Set';
$datasets[] = 'Add';


//if(!empty($projects)){
	if(!empty($projects)){
		foreach($projects as $p){
			$project_array[$p['client']][$p['name']] = $p;
		}
		foreach($project_array as &$p) ksort($p);
		ksort($project_array);
	} else {
		$project_array = array();
	}
	
	
	foreach($clients as $cl){
		$client = $cl['name'];
		
		if(isset($project_array[$client])){
			$ps = $project_array[$client];
		} else {
			$ps = array();
		}
		
		
	?>
	
	<div class="client<?php echo $client_title_name ? ' hide_client' : ''?>" id="client_box_<?php echo str_replace(' ','_', $client)?>">
		<div class="horizontal_bar client_header<?php echo $client_title_name?' display_none':''?>">
			<div class="lsf icon">&#xe023;</div>
			<div class="bar_text" id="clients_<?php echo $cl['id']?>"><?php echo $client?></div>
			<a href="/projects/<?php echo Site::urlOut($cl['id'])?>/" class="lsf next">&#xe112;</a>
		</div>
		<div class="client_box">
		<?php
			//$first = @reset($ps);
			echo Site::drawForm('form'.$client, '', '', '', false, array('class'=>'add_project'));
			echo Site::drawHidden('client_id', $cl['id']);
			?>
			<button class="styled">Add Project <span class="lsf">&#xe108;</span></button>
			<?php
			//echo Site::drawSubmit('save','Add Project');
			echo Site::drawHidden('mode', 'add');
			echo Site::drawForm();
			?>
			
			<!--<button id="buttony">Edit Project <span class="lsf">&#xe09f;</span></button>-->
			
			
		<?php 
		if(isset($ps) && is_array($ps)){
		foreach($ps as $q){ ?>
			<div class="project" id="project_box_<?php echo $q['id'] ?>">
			<div class="horizontal_bar project_header" id="project_header_<?php echo $q['id'] ?>">
				<div class="lsf icon">&#xe028;</div>
				<div class="bar_text project_name" id="projects_<?php echo $q['id'] ?>"><?php echo $q['name']?></div>
				<a href="/projects/<?php echo Site::urlOut($cl['id'])?>/<?php echo Site::urlOut($q['id'])?>" class="lsf next">&#xe112;</a>
			</div>
			<div id="project_box_<?php echo $q['id'] ?>" class="project_box">
			<button class="styled left edit_project"><span class="lsf">&#xe09f;</span></button>
			<button class="styled left project_permission" id="project_permission_<?php echo $q['id']?>"><span class="lsf">&#xe07c;</span></button>
			
			<?php
			echo Site::drawForm('form'.$q['id'], '', '', '', false, array('class'=>'add_data'));
			echo Site::drawHidden('project_id', $q['id']);
			?>
			<button class="styled add_data"><span class="lsf">&#xe108;</span></button>
			<?php
			//echo Site::drawSubmit('save','Add');
			echo Site::drawSelect('data_set_id', $datasets, '', '',false, array('class'=>'dataset_select'));
			echo Site::drawHidden('mode', 'add');
			echo Site::drawForm();
			
			if(isset($data[$client][$q['name']])){
				foreach($data[$client][$q['name']] as $ds => $data_groups){ ?>
					
					
					<?php	
					foreach($data_groups['groups'] as $dg_id => $vals){
						$rotate = rand(-2, 2);
					?>
					<table class="data" align="center">
					<tr><th colspan="2" align="left"><?php echo $ds?></th></tr>
					<?php 
						foreach($vals as $f){
							echo '<tr><td width="100">'.$f['field'].'</td><td>'.$f['value'].'</td></tr>';
						}
						echo '<tr><td colspan="2" align="right">';
						echo Site::drawForm('edit_fields_'.$ds, '', '', '', false,  array('class'=>'add_data'));
						echo Site::drawHidden('data_group_id', $dg_id);
						echo Site::drawHidden('mode', 'edit');
						echo Site::drawHidden('data_set_id', $data_groups['data_set_id']);
						echo Site::drawHidden('project_id', $q['id']);
						//echo Site::drawSubmit('edit_fields', 'Edit Data');
						?>
						<button class="styled">&nbsp<span class="lsf">&#xe041;</span></button>
						<?php
						echo Site::drawForm();
						echo '</td></tr>';
						echo '</table>';
					}	
					
					
				}
			}
			//($name='', $action = '', $method = 'POST', $enc = '', $block_submit = false, $attr = false)
			?>
			</div>
			<?php
		?>
		</div>
		
		<?php 
			}
		}
		?>
		</div>
		</div>
		<?php
	
	}

//}



?>




