<?php
	include_once 'lib.php';
	include_once 'config.php';
 ?>
<HTML>
	<head>
		<title>Register</title>
	</head>

	<body>
    	<div id="register">
	<div id="content">
		<form method="post" action="<?php echo sanitize('regverify.php'); ?>">
			<label >Username</label>
			<input name="user" type="text" /> <br>
			<label >Password</label>
			<input name="pw1" type="password" /><br>
           	<label >Re-Type Password</label>
			<input name="pw2" type="password" /><br>
			<input name="Submit" type="submit" value="Register" />

		<p><a href="<?php echo sanitize('index.php'); ?>">Back to Login page</a></p>
		</form>
        </div>
        </div>
	</body>
</HTML>
