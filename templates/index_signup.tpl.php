	<?php 
	
	
	//$GLOBALS['debug']->printr($_POST);
	if(empty($_POST)){?>
	
	<h2>One Login – It’s All There</h2>

<p>As a developer the amount of information that must be tracked and maintained can sometimes be a challenge. FTP connection details, database access, control panels, Wordpress admins, Analytics, domain registrars, CMS access, Twitter and Facebook access. Losing any one of these could be a catastrophe. Having them all in one place and close at hand will save you money.</p>

	<h2>Move Beyond ClientLogins.xls</h2>

<p>Alarming numbers of studios store critical client information in insecure and overly accessible file formats.  These files risk being stolen, duplicated, or inadvertently deleted. The best case scenario is an out-of-date record with continual access conflicts. The worst case is unthinkable disasters for your business.</p>

<div class="box_out">

<h2>Move Into Security</h2>

<p>Unlike repurposed project management software, or wiki software, MyClient.Info is designed from the ground up for security first and foremost. All aspects of the application and server are built to withstand hacking or intrusion attempts. All client data is encrypted multiple times, using multiple encryption methods, and unique per-account keys.</p>
</div>

<h2>Share The Information</h2>

<p>Having up to date information is only half the battle. Information has to be shared to be useful, and MyClient.Info facilitates access within your company, allowing you to set access permissions individually on every aspect of a project.</p>

<p>Securing the process of sharing and enabling access to information is nearly as important as securing the data. Don’t send client to new staff by email – there’s a better way.</p>



<h2>Access Anywhere</h2>

<p>Working from home, at the office, or on the way to a meeting, you will always have access to critical logins and connections.  Don’t be caught without the tools you need to get it done, and don’t spend hours looking for the details that would let you bill for a 10 minute job.</p>


<h2>All Kinds of information</h2>

<p>MyClient.Info is ideal for storing a range of information, not limited to FTP details, control panels and administration services, content management logins, and social media access. However it can also be useful for storing personal information that is only available to an individual. Social security numbers, bank details, and other critical information can be stored in a conveniently accessible and secure vault.</p>


<h2>Options for Everyone</h2>

<p>MyClient.Info offers account types for all kinds of developers and companies. There are options for everyone, from individual freelancers working on a small number of projects alone to large development studios comprising multiple teams on numerous projects in varied environments. </p>


		
		<h2>Sign Up</h2>
		
		<form action="" method="post" id="signup_form">
		<label for="agency">Agency</label><input type="text" name="agency" id="agency" class="standard_field">
		<label for="first_name">First Name</label><input type="text" name="first_name" id="first_name" class="standard_field">
		<label for="last_name">Last Name</label><input type="text" name="last_name" id="last_name" class="standard_field">
		<label for="email">Email</label><input type="text" name="email" id="email" class="standard_field">
		<label for="password">Password</label><input type="password" name="password" id="password" class="standard_field">
		<button class="styled">Try MyClient.Info <span class="lsf">&#xe029;</span></button>
		</form>
		
	
	
	<p>To help us fine-tune the system we are looking for volunteers to use the system and report any bugs, inconsistencies, or disappointments you encounter. Be one of the first to gain access by filling in the form at the right, and entering in a few simple details.</p>
	
	<p>Once your beta access is approved (generally within 24 hours) you will be able to log in using the details you provide here.</p>
	
	<?php } else { ?>
	<h2>Thank you for your interest</h2>
	<p>An email will be sent to you shortly, confirming your acceptance to the Beta for MyClient.Info, and explaining how to gain access to the site.</p>
	
	<br /><br /><br />
	<?php } ?>