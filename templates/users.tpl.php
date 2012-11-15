
<script>
$(document).ready(function(){

	$(document).on('submit', 'form.add_user', function(e){
		e.preventDefault();
		$.post('/api/users.php', $(this).serialize(),  function(data) {
			endAlert();
		});
	});
		
	
	$(document).on('click', 'button#add_user', function(e){
		e.preventDefault();
		$.post('/api/users.php', $(this).serialize(),  function(data) {
			$('#alert_box').html(data);
			newAlert('', 'stop');
		});
	});
	
	
	$(document).on('click', '.edit', function(e){
		e.preventDefault();
		$.post('/api/users.php', {id:$(this).attr('id').replace('user_edit_','')},  function(data) {
			$('#alert_box').html(data);
			newAlert('', 'stop');
		});
	});
	
});
</script>

<h2><span class="lsf">&#xe051;</span> Users</h2>

<div class="thin_form">
<button class="styled" id="add_user">Add User <span class="lsf">&#xe108;</span></button>
</div>

<?php

if(!empty($content)){
	echo '<div id="list_users">';
	foreach($content as $c){
	?>
		<div class="horizontal_bar"><div class="bar_text">
		<?php echo $c['first_name'] ?> <?php echo $c['last_name'] ?></div>
		<!--<div class="edit lsf">&#xe07c;</div>-->
		<div id="user_perms_<?php echo $c['id']?>" class="lsf perms">&#xe07c;</div>
		<div id="user_edit_<?php echo $c['id']?>" class="lsf edit">&#xe041;</div>
		</div>
	<?php
	}
	echo '</div>';
}

?>




