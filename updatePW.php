<?php

include_once 'config.php';
include_once 'lib.php';

// TO DO HASH PASSWORD
if (isset($_POST['oldpw'], $_POST['pw1'], $_POST['pw2'])) {
    $oldpw = hashString($_POST['oldpw']);
    $pw1 = hashString($_POST['pw1']);
    $pw2 = hashString($_POST['pw2']);

    if (updatePassword($oldpw, $pw1, $pw2,$db) == true) {
        alertRedirect('Update password Succesful',"main.php");
    } else {
        alertRedirect('Invalid password', 'main.php');
    }
} else {
    alertRedirect('Invalid Request', 'main.php');
}

function updatePassword($oldPassword,$newPassword1,$newPassword2,$db)
{
	if(equalString($newPassword1,$newPassword2))
	{
		if($stmt = $db->prepare("SELECT password from members where username = :username"))
		{
			$stmt->bindValue(":username",$_COOKIE['username']);
			$query = $stmt->execute();
			$result = $query->fetchArray();

			if(equalString($result['password'],$oldPassword))
			{
				$stmt->reset();
				if($stmt = $db->prepare("UPDATE members SET password = :password WHERE username = :username"))
				{
					$stmt->bindValue(":password",$newPassword1);
					$stmt->bindValue(":username",$_COOKIE['username']);
					$query = $stmt->execute();
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}

}
