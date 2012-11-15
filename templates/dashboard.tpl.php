
<?php
//$GLOBALS['debug']->printr($_SESSION['user']);
?>


<script>
$(document).ready(function(){
	
	<?php if(!@$_SESSION['user']['ds_qs_seen']){
		Agency::setSeenDefaults();
	?>
	 $.post('/api/default_datasets.php', function(data) {
		$('#alert_box').html(data);
		newAlert('', 'stop');
		
	});
		
	$(document).on('click', '#default_box ul li', function(e){
		
		if($(this).attr('class')=='selected'){
			$(this).removeClass('selected');
		} else {
			$(this).addClass('selected');
		}
		
	});
	
	$(document).on('click', 'div.default_header', function(e){
	
		$('div.default_box').hide();
		$('div.default_box', $(this).parent()).show();
		
	});
	
	$(document).on('click', '#quickstart_save', function(e){
		var id_string = '';
		$('#default_box ul li.selected').each(function(){
			id_string += $(this).attr('id').replace('default_','')+',';
			
		});
		
		$.post('/api/save_defaults.php', {ds: id_string}, function(data){
			endAlert();
		});
	});
	<?php } ?>
//	}
	
});
</script>

<?php
//$GLOBALS['debug']->printr($_SESSION['user']);
?>
<h2><span class="lsf">&#xe03c;</span> Dashboard</h2>

<div id="dashboard">

<p>Welcome to MyClient.Info. This site is still throughly in Beta stage.</p>

<p>Please note that this is an early build of the site and database changes may be required. There is every chance that these database changes will delete your stored client information or otherwise reset your account. These changes will not be able to be repaired. Do not use for mission critical data that is not stored elsewhere.</p>

<p>You can take the following steps to use the system.</p>

<ol class="dashlist">
	<li>Add at least one client on the <a href="/clients/">Clients</a> page</li>
	<li>Add a project on the <a href="/projects/">Projects</a> page. Most actions can be done on this page, and it is the central interface for data entry and viewing. Click the <em>Add Project</em> button to create the new project. Further options (such as permission and access) will be added shortly.</li>
	<li>Add a data template. These templates consist of the fields required to store your data. Datasets are created by selecting <em>Add</em> on the <em>Select Set</em> dropdown. Give the dataset a name and add the fields as required.</li>
	<li>To add data, select a template from the dropdown and click <em>Add Data</em> to use that template. You can freely edit either the values or the field at any time. The templates are merely a convenient starter, they do not force your behaviour.</li>
</ol>


</div>