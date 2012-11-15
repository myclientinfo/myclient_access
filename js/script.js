$(document).ready(function(){

	$('#blocker').click(function(){
		$('#alert_box').fadeOut();
		$('#blocker').fadeOut();
		
	});
	
	
	$(document).on('click', 'div.horizontal_bar', function(){
		$(this).toggleClass('active');
	});
	
	$("select").select2({minimumResultsForSearch: 1000, width: 'element'});
	
	$(document).on('submit', 'form#new_project', function(e){
		e.preventDefault();
		$.post('/api/projects.php', $('#new_project').serialize(),  function(data) {
			endAlert();
			if($('#access_type_changed').val()==0){
				window.location.href=window.location.href;
			} else {
				window.location.href='/projects/permissions/?id='+$('#id').val();
			} 
		});
		
	});
	
	$(document).on('change', 'form#new_project #access_type_id', function(){
		if($(this).val()==3 || $(this).val()==4){
			$('#access_type_changed').val(1);
			//window.location.href=window.location.href;
		}
	});
	
	$(document).on('click', 'form .delete', function(e){
		e.preventDefault();
		if(confirm('Are you sure you want to delete this?')){
			$('#delete_form').val(1);
			$.post('/api/projects.php', $('#new_project').serialize(),  function(data) {
				endAlert();
				window.location.href=window.location.href;
			});
		}
		
	});
	
});


var newAlert = function(file, type){
	
	$('#blocker').css({width: $(document).width(), height:$(document).height() } );
	$('#blocker').css('opacity', '0.7');
	$('#blocker').fadeIn();
	
	if(file != '') $('#alert_box').load('/api/'+file+'.php');
	//console
	
	$('#alert_box').show();
	
	$('#alert_box').css({top: '-'+$('#alert_box').height()+'px', left:  ($(window).width() / 2) - ($('#alert_box').width() / 2)+'px'});
	
	position_alert();
	
	if(type == 'alert'){
		setTimeout('endAlert()', 4000);
	}
	//position_alert();
	if(type == 'stop') return false;
}

var position_alert = function(){
	
	vpos = $(document).scrollTop() + (($(window).height() / 2) - ($('#alert_box').height() / 2));
	hpos = ($(window).width() / 2) - ($('#alert_box').width() / 2);
	
	$('#alert_box').animate({top: vpos, left: hpos});
	
}




var submit_project_form = function(){
	
}


var endAlert = function(){
	$('#alert_box').animate({top: '-'+($('#alert_box').height()+50)+'px'});
	$('#blocker').fadeOut();
}