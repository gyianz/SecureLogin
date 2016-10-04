<?php
    include_once 'lib.php';
	include_once 'config.php';

	if(!checkSession($db))
	{
		alertRedirect("Not logged in","index.php");
	}
	else
	{
		updateSession($_COOKIE["id"],$_COOKIE["username"],$db);
	}
?>


<HTML>
	<head>
		<title>Password Management</title>
	</head>

	<body>
    	<div id="register">
	<div id="content">
		<form method="post" action="<?php echo sanitize('updatePW.php'); ?>">
			<label >Old password</label>
			<input name="oldpw" type="password" /> <br>
			<label >New Password</label>
			<input name="pw1" type="password" /><br>
           	<label >Re-Type New Password</label>
			<input name="pw2" type="password" /><br>
			<input name="Submit" type="submit" value="Update" />

		<p><a href="<?php echo sanitize('main.php'); ?>">Back to Main page</a></p>
		</form>
        </div>
        </div>
	</body>
</HTML>
