<?php
    include_once 'lib.php';
	include_once 'config.php';

	if(!checkSession($db))
	{
		alertRedirect("","index.php");
	}
	else
	{
		updateSession($_COOKIE["id"],$_COOKIE["username"],$db);
	}

	if(getRank($db) != 1)
	{
		alertRedirect("Not admin","main.php");
	}


	$stmt = $db->prepare("SELECT * from members");
    $query = $stmt->execute();    // Execute the prepared query.

	echo " <html>
		   <body>
		   <form action='";
	echo sanitize('changeAdmin.php');
	echo "' method='POST'>
		   <table style='width:80%' border='1'>
  <tr>
    <td>Role</td>
    <td>Username</td>
    <td>New Password</td>
  </tr>

";
	while($result = $query->fetchArray())
	{
		if(equalString($result['username'],$_COOKIE['username']))
		{
			continue;
		}
		echo "<tr>";
		echo "<td>";
		echo "<select name='role,".$result['username']."'>";
		echo "<option value='chooseone'>Choose One..</option>";
		echo "<option value='user'>User</option>";
		echo "<option value='admin'>Admin</option>";
		echo "</select> ";
		echo "</td>";

		echo "<td>";
		echo $result['username']." ";
		echo "</td>";

		echo "<td>";
		echo " <input name='password,".$result['username']."'";
		echo "type='password' /><br>";
		echo " ";
		echo "</br>";
		echo "</td>";
		echo "</tr>";
	}

	echo "  </table>
			<p>
			<input type='submit' value='Submit'>
			</p>

			</form>

				</body>
				</html>"
?>
