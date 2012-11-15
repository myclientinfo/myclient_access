<?php 
//$GLOBALS['debug']->printr($_SESSION['user']['account_type_id']);
if(!isset($show_menu)||$show_menu){?>
	<ul id="main_menu">
		<li><a href="/">HOME</a></li>
		<li><a href="/clients/">CLIENTS</a></li>
		<li><a href="/projects/">PROJECTS</a></li>
		<?php if($_SESSION['user']['account_type_id'] != 1){ ?><li><a href="/users/">USERS</a></li><?php } ?>
		<li><a href="/?log_out">LOG OUT</a></li>
	</ul>
<?php } ?>