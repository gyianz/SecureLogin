<?php

include_once 'config.php';
include_once 'login.php';

function checkSession($db)
{
    //check if cookie exists
    //

    if (isset($_COOKIE["id"],$_COOKIE['username'],$_COOKIE['expiration'])) {
        $cookieId = $_COOKIE["id"];
        $cookieUsername = $_COOKIE["username"];
		$cookieTime = $_COOKIE["expiration"];

		if($stmt = $db->prepare("SELECT session_id FROM session WHERE username = :username"))
		{
			$stmt->bindValue(":username",$cookieUsername);
			$query = $stmt->execute();
			$result = $query->fetchArray();

			if(equalString($result['session_id'],hashString($cookieUsername.$cookieTime)))
			{
				if (($_COOKIE["expiration"] - time()) > 0) {
					updateSession($_COOKIE["id"],$_COOKIE["username"],$db);
					return true;
				}
			}
		}
    } else {
        return false;
    }
}

function alertRedirect($message, $redirect)
{
    echo "<script type='text/javascript'>\n";
    echo "alert('$message');\n";
    echo "window.location = '$redirect'\n";
    echo '</script>';
}

// function to compare two strings and prevent timing attack.
function equalString($string1, $string2) {

	$output = 0;

	if (strlen($string1) != strlen($string2)) {
        $output = 1;
    }

    for ($i = 0; $i < $userLen; $i++) {
        $output |= (ord($safe[$i]) ^ ord($user[$i]));
    }

    // They are only identical strings if $result is exactly 0
    return $output === 0;
}

function hashString($inputString)
{
    $outputString = hash('sha512',$inputString);
    return $outputString;
}

function sanitize($url) {

    if ('' == $url) {
        return $url;
    }

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;

    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

	return $url;

}


function bruteForce($user_id, $db)
{
	// Get timestamp of current time
   $now = time();
	// Check for attempts in the past 5 minutes
   $valid_attempts = $now - (5 *60);

   if ($stmt = $db->prepare("SELECT COUNT(*) AS count FROM login_attempts WHERE id = :id AND time >= :valid_attempts"))
   {
	   $stmt->bindValue(':id', $user_id);
	   $stmt->bindValue(':valid_attempts', $valid_attempts);

	   $query = $stmt->execute();
	   $result = $query->fetchArray();

	   // If there have been more than 3
	   if ($result['count'] > 3) {
		   recordLoginAttempt($user_id,time(),$db);
		   return true;
	   } else {
		   return false;
	   }
   }
}

function loggedIn($inputId,$inputUsername,$db)
{
	$expiryTime = time()+60*5; // keeping time consistent across cookie

	setcookie("id",$inputId, $expiryTime,"/");
	setcookie("username",$inputUsername,$expiryTime,"/");
	setCookie("expiration", $expiryTime, $expiryTime,"/"); // used as expiry time can't be read

	if($stmt = $db->prepare("SELECT * from session WHERE username = :username"))
	{
		$stmt->bindValue(":username",$inputUsername);
		$query = $stmt->execute();
		if($result = $query->fetchArray())
		{
			$stmt->reset();
			if($stmt = $db->prepare("DELETE from session WHERE username = :username"))
			{
				$stmt->bindValue(":username",$inputUsername);
				$query = $stmt->execute();
				$stmt->reset();
			}
		}
	}
	if($stmt = $db->prepare("INSERT INTO session (username,expiry,logged_in,session_id) VALUES (:username,:expiry,:logged_in,:session_id)"))
	{
		$stmt->bindValue(":username",$inputUsername);
		$stmt->bindValue(":expiry",$expiryTime);
		$stmt->bindValue(":logged_in",true);
		$stmt->bindValue(":session_id",hashString($inputUsername.$expiryTime));

		$query = $stmt->execute();
	}
}

function recordLoginAttempt($user_id,$time,$db)
{
	if ($stmt = $db->prepare("INSERT INTO login_attempts (id,time) VALUES (:id,:time)"))
	{
		$stmt->bindValue(':id',$user_id);
		$stmt->bindValue(':time',$time);
		$query = $stmt->execute();
	}
}

function updateSession($inputId,$inputUsername,$db)
{
	$expiryTime = time()+60*5; // keeping time consistent across cookie
	setcookie("id",$inputId, $expiryTime,"/");
	setcookie("username",$inputUsername,$expiryTime,"/");
	setCookie("expiration", $expiryTime, $expiryTime,"/"); // used as expiry time can't be read

	if($stmt = $db->prepare("UPDATE session SET session_id = :session_id, expiry = :expiry WHERE username = :username"))
	{
		$stmt->bindValue(":expiry",$expiryTime);
		$stmt->bindValue(":session_id",hashString($inputUsername.$expiryTime));
		$stmt->bindValue(":username",$inputUsername);
		$result = $stmt->execute();
	}
}

function clearSession($db)
{
	if(isset($_COOKIE['username']))
	{
		if($stmt = $db->prepare("DELETE FROM session WHERE username = :username"))
		{
			$stmt->bindValue(":username",$_COOKIE['username'],SQLITE3_TEXT);
			$query = $stmt->execute();
		}
	}
}

function deleteCookies()
{
	foreach ($_COOKIE as $key=>$val)
  	{
		setcookie ($key, "", 1); // forcing cookie to expire in 1 second regardless of browser time
		setcookie ($key, false); // unset cookie
		unset($_COOKIE[$key]); // unset cookie again
  	}
}

function getRank($db)
{
	if($stmt = $db->prepare("SELECT rank FROM members WHERE username = :username"))
	{
		$stmt->bindValue(":username",$_COOKIE['username'],SQLITE3_TEXT);
		$query = $stmt->execute();
		$result = $query->fetchArray();

		if($result['rank'] === 0)
		{
			return 0;
		}
		else if($result['rank'] === 1)
		{
			return 1;
		}
	}
}
