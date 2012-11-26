<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<meta charset="UTF-8">
<head>
	<title>My Client Info</title>
	<meta name="description" content="Securely store and quickly find information for your clients' sites - CPanel, FTP, Wordpress, Hosting and more." />
	<meta name="keywords" content="store client information data vault passwords ftp login cms manage" />
	<link rel="stylesheet" href="/styles/reset.css" />
	<link rel="stylesheet" href="/styles/style.css" />
	<link rel="stylesheet" href="/styles/fonts.css" />
	<link rel="stylesheet" href="/js/select2/select2.css" />
	
	<script src="/js/jquery.min.js" language="JavaScript" type="text/javascript"></script>
	<script src="/js/select2/select2.js" language="JavaScript" type="text/javascript"></script>
	<script src="/js/jquery.jeditable.min.js" language="JavaScript" type="text/javascript"></script>
	<script src="/js/script.js" language="JavaScript" type="text/javascript"></script>
	
	<script type='text/javascript'>
	(function (d, t) {
	  var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
	  bh.type = 'text/javascript';
	  bh.src = '//www.bugherd.com/sidebarv2.js?apikey=111d494c-9b23-4417-a8eb-9e14de9a19ae';
	  s.parentNode.insertBefore(bh, s);
	  })(document, 'script');
	</script>
	

</head>


<body>
	
	
	
	
	<div id="content_outer" class="index">
	
	
	<h1></h1>
	
	


<?php echo $content?>
	
	
<?php
	//$GLOBALS['debug']->printr($_SESSION);
	
?>
	
	</div>

<div id="alert_box" class="new_box" style="display: none;"></div>

<div id="blocker"></div>


</body>
</html>
