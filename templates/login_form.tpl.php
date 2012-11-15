<!-- 
<form id="login_form" method="post" action="">
<label for="login_email">Username</label><input type="text" name="login_email" id="login_email" value="<?php echo (isset($_POST['login_email'])?$_POST['login_email']:(isset($_GET['un'])?$_GET['un']:''))?>"><br>
<label for="login_password">Password</label><input type="password" name="login_password" id="login_password"><br>

<input type="image" src="/images/button_login.gif" name="submit" style="float: right; width: 55px; height: 14px; border-width: 0px;" value="Log In">
<div style="clear: both;"></div>
</form>
-->

<?php 
echo Site::drawForm('login_form');
echo Site::drawText('login_email', @$_POST['login_email'], 'Email');
echo Site::drawPassword('login_password', '', 'Password');
?>
<button class="styled">Log In <span class="lsf">&#xe087;</span></button>
<?php
//echo Site::drawSubmit('login', 'Log In');
echo Site::drawForm();

//$GLOBALS['debug']->printr($_SESSION['data']);
?>

