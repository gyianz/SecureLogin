<?php
	include_once 'config.php';
	include_once 'lib.php';

	foreach ($_POST as $key => $value)
	{
	 	$keyArray = explode(',', $key);
		if(equalString($keyArray[0],"role"))
		{
			if(!updateRole($keyArray[1],$value,$db))
			{
				alertRedirect("Update not valid","main.php");
			}
		}
		else if(equalString($keyArray[0],"password"))
		{
			if(!updatePassword($keyArray[1],$value,$db))
			{
				alertRedirect("Update not valid","main.php");
			}
		}
	}

	alertRedirect("Info updated","main.php");

function updatePassword($owner,$value,$db)
{
	if(equalString($value,""))
	{
		return true;
	}
	else
	{
		if($stmt = $db->prepare("UPDATE members SET password = :password WHERE username = :username"))
		{
			$value = hashString($value);
			$stmt->bindValue(':username',$owner);
			$stmt->bindValue(':password',$value);
			$query = $stmt->execute();
			return true;
		}
	}
}

//Change the role of the user
function updateRole($owner,$value,$db)
{
	if(equalString($value,"chooseone"))
	{
		return true;
	}
	else
	{
		$role = 0;
		if(equalString($value,"admin"))
		{
			$role = 1;
		}
		else if(equalString($value,"user"))
		{
			$role = 0;
		}

		if($stmt = $db->prepare("UPDATE members SET rank = :rank WHERE username = :username"))
		{
			$stmt->bindValue(':username',$owner);
			$stmt->bindValue(':rank',$role);
			$query = $stmt->execute();
			return true;
		}
	}
}

 ?>
