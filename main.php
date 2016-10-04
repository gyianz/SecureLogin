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

<!DOCTYPE html>
<html><head><title>PHP page output page</title></head>
<body>
	<?php
        echo 'Welcome back ';
        echo $_COOKIE['username'];
		echo "</br>";

        if (getRank($db) == 1) {
            echo "<a href='admin.php'> Admin Page </a> </br>";
        }
    ?>

	<a href="<?php echo sanitize('password.php'); ?>"> Update Password </a> </br>
	<a href="<?php echo sanitize('logout.php'); ?>"> Logout </a></br>
</body>
</html>
