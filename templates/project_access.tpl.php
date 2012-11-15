<script>
var access_type = false;

var project_types = <?php echo json_encode($projects)?>;
var access = <?php echo json_encode($access)?>;




var project_id = false;
var project_name = false;
var group_id = false;
var self_id = <?php echo $_SESSION['user']['id'] ?>;

$(document).ready(function(){
	
	$('#user_access_type_select').on('click', 'li', function(){
		var type = $(this).attr('id').replace('access_', '');
		access_type = type;
		
		
		$('#user_access_type_select li').css('backgroundColor','#FFFFFF');
		$(this).css('backgroundColor','#ebebeb');
		show_projects();
		$('#access_user_list').hide();
		
	});
	
	
	$(document).on('click', 'div.horizontal_bar div.edit', function(e){
		e.preventDefault();
		
		$.post('/api/projects.php', {id: $(this).attr('id').replace('project_edit_','')},  function(data) {
			$('#alert_box').html(data);
			newAlert('', 'stop');
		});
		
		//window.location.href='/projects/permissions/?id='+$(this).attr('id').replace('project_edit_','');
		
	});
	
	
	/*
	
	$('#matching_projects div').click(function(){
	console.log($(this));
		project_id = $(this).attr('id').replace('project_','');
	});
	
	*/
	$('#matching_projects').on('click', 'div.bar_text', function(){
		
		$('#matching_projects div.horizontal_bar').not($(this).parent()).removeClass('active');
		if(access_type=='project'){
			project_name = $(this).text();
			project_id = $(this).attr('id').replace('project_','');
			show_users();
		}
	
	});
	
	$('#access_user_list').on('click', 'div.horizontal_bar', function(){
		
		var user_id = $(this).attr('id').replace('user_', '');
		var this_array = access[access_type][project_id];
		console.log(!$(this).hasClass('active'));
		if(!$(this).hasClass('active')){
			if(access[access_type][project_id].indexOf(user_id)==-1){
				access[access_type][project_id].push(user_id);
			}
		} else {
			var new_array = [];
			$.each(access[access_type][project_id],function(key, value){
				if(value != user_id) new_array.push(value);
			})
			
			access[access_type][project_id] = new_array;
		}
		
	});
	
	$('#access_private').trigger('click');
	<?php if(isset($_GET['id'])){
		foreach($projects as $key => $val){
			foreach($val as $proj){
				if($_GET['id']==$proj['id']){?>
				$('#access_<?php echo $key?>').trigger('click');
				<?php
				}
				
			}
			
		}
	} ?>
	
	$('#save_project_access').on('click', save_project_access);
	
	
});

var save_project_access = function(){

	$.post('/api/save_project_access.php', access, function(data){
		//console.log(data);
	});

}

var show_projects = function(){
	
	$('#matching_projects_inner').html('');
	
	if(typeof(project_types[access_type])=='undefined'){
		$('#matching_projects_inner').append('<div>There are no matching projects of this type.</div>');
		$('#user_list').hide();
		return false;
	}
	
	
	
	$.each(project_types[access_type], function(index, value) { 
		$('#matching_projects_inner').append('<div class="horizontal_bar"><div id="project_'+value.id+'" class="bar_text">'+value.name+'</div><div id="project_edit_'+value.id+'" class="lsf edit">&#xe041;</div></div>');
	});
	
	
	//show_users();
}

var show_users = function(){
	$('#for_project').text(' for '+project_name);
	$('#access_user_list').show();
	$('#access_user_list div.horizontal_bar').each(function(){
		
		var user_id = $(this).attr('id').replace('user_','');
		
		if(access!=null){
			if($.inArray(user_id, access[access_type][project_id])>-1){
				$(this).addClass('active');
			} else {
				$(this).removeClass('active');
			}
		} else {
			$(this).removeClass('active');
		}
	});
	
}





</script>



<?php
if(empty($content)){
	echo 'You currently have no clients listed.';
}





if(!empty($content)){
	foreach($content as $c){
		//echo $c['first_name'].' '.$c['last_name'].'<br>';
	}
}
//$GLOBALS['debug']->printr($_SESSION);
//$GLOBALS['debug']->printr($projects);



?>

<h2><span class="lsf">&#xe07c;</span> Project Access</h2>

<ul id="user_access_type_select">
	<li id="access_private">Private</li>
	<li id="access_agency">Agency</li>
	<li id="access_project">Project</li>
	<li id="access_group" style="display:none;">Group</li>
</ul>



<div id="matching_projects">
<h3>Projects</h3>
	
	<div id="matching_projects_inner">Select an access type to view or edit applicable projects</div>
</div>

<div id="access_user_list">

<h3>Users <span id="for_project"></span></h3>



<?php if(!empty($content)){
	foreach($content as $c){ ?>
		<div class="horizontal_bar" id="user_<?php echo $c['id']?>">
			<div class="bar_text"><?php echo $c['first_name']?> <?php echo $c['last_name']?></div></div>
<?php	
}
}?>
</div>

<button class="styled" id="save_project_access">Save Access <span class="lsf">&#xe031;</span></button>
