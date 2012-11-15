<script>
var access_type = false;

var project_types = <?php echo json_encode($projects)?>;
var access = <?php echo json_encode($access)?>;




var project_id = false;
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
	
	
	
	/*
	
	$('#matching_projects div').click(function(){
	console.log($(this));
		project_id = $(this).attr('id').replace('project_','');
	});
	
	*/
	$('#matching_projects').on('click', 'div.project', function(){
	
		if(access_type=='project'){
			project_id = $(this).attr('id').replace('project_','');
			show_users();
		}
	
	});
	
	$('#access_user_list').on('click', 'div', function(){
		
		$(this).toggleClass('active');
		
		var user_id = $(this).attr('id').replace('user_', '');
		var this_array = access[access_type][project_id];
		
		
		if($(this).hasClass('active')){
			if(access[access_type][project_id].indexOf(user_id)==-1) access[access_type][project_id].push(user_id);
		} else {
			var new_array = [];
			$.each(access[access_type][project_id],function(key, value){
				if(value != user_id) new_array.push(value);
			})
			
			access[access_type][project_id] = new_array;
		}
		
	});
	
	
	$('#save_project_access').on('click', save_project_access);
	
	$('#access_private').trigger('click');
});

var save_project_access = function(){

	$.post('/api/save_project_access.php', access, function(data){
		console.log(data);
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
		$('#matching_projects_inner').append('<div id="project_'+value.id+'" class="project">'+value.name+'</div>');
	});
	
	
	//show_users();
}

var show_users = function(){
	
	$('#access_user_list').show();
	$('#access_user_list div').each(function(){
		var user_id = $(this).attr('id').replace('user_','');
		if($.inArray(user_id, access[access_type][project_id])>-1){
			$(this).addClass('active');
			
		}
	});
	
}





</script>

<style>
#user_access_type_select {
	width: 100%;
	
}

	
#user_access_type_select li {
	background-color: white;
	float: left;
	padding: 10px;
	cursor: pointer;
	border-radius: 5px 5px 0px 0px;
}

#matching_projects, #access_user_list {
	width: 100%;
	clear: both;
	padding: 10px;
	background-color: #ebebeb;
	margin-bottom: 15px;
}

#access_user_list {
	display: none;
}

#access_user_list div {
	padding: 5px;
	border-radius: 3px;
	border: 1px solid #cccccc;
	margin: 1px 5px;
	
}

div.active {
	background-color: #FFA07A;
}

</style>


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
//$GLOBALS['debug']->printr($access);



?>

<ul id="user_access_type_select">
	<li id="access_private">Private</li>
	<li id="access_agency">Agency</li>
	<li id="access_project">Project</li>
	<li id="access_group">Group</li>
</ul>

<div id="matching_projects">
<h3>Projects</h3>
	
	<div id="matching_projects_inner">Select an access type to view or edit applicable projects</div>
</div>

<div id="access_user_list">

<h3>Users</h3>



<?php if(!empty($content)){
	foreach($content as $c){ ?>
		<div class="user_item_name" id="user_<?php echo $c['id']?>"><?php echo $c['first_name']?> <?php echo $c['last_name']?></div>
<?php	
}
}?>
</div>

<button class="button" id="save_project_access">Save Settings </button>
