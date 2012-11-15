<script>
$(document).ready(function(){

	$(document).on('submit', 'form.add_client', function(e){
		e.preventDefault();
		$.post('/api/clients.php', $(this).serialize(),  function(data) {
			endAlert();
		});
	});
		
	
	$(document).on('click', 'button#add_client', function(e){
		e.preventDefault();
		$.post('/api/clients.php', $(this).serialize(),  function(data) {
			$('#alert_box').html(data);
			newAlert('', 'stop');
		});
	});
	
	
	$(document).on('click', '.edit', function(e){
		e.preventDefault();
		$.post('/api/clients.php', {id:$(this).attr('id').replace('client_edit_','')},  function(data) {
			$('#alert_box').html(data);
			newAlert('', 'stop');
		});
	});
	
});
</script>

<style>
.edit_client .styled_min {
	bottom: 8px;
	right: 5px;
}
	
</style>

<h2><span class="lsf">&#xe023;</span> Clients</h2>


<div class="thin_form">
<button class="styled" id="add_client">Add Client <span class="lsf">&#xe108;</span></button>
</div>

<div id="client_list">
<?php



if(!empty($content)){
	foreach($content as $cl){ 
	$client = $cl['name'];
	?>
	<div class="horizontal_bar" id="client_box_<?php echo $client?>">
		<div class="bar_text"><?php echo $client?><span style="font-size: 14px; font-weight: normal"> - Client</span></div>
		<div id="client_edit_<?php echo $cl['id']?>" class="lsf edit">&#xe041;</div>
	</div>
	<?php
	}
} else {
	echo 'You currently have no clients listed. Please add one above.';
	
}

?>

</div>


