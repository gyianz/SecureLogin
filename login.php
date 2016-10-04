<?php

include_once 'config.php';
include_once 'lib.php';


// TO DO HASH PASSWORD
if (isset($_POST['username'], $_POST['password'])) {

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $password = hashString($password);

    if (login($username, $password,$db) == true) {
        alertRedirect('Login Succesful',"main.php");
    } else {
        alertRedirect('Invalid username and password', 'index.php');
    }
}


function login($username,$password,$db)
{
    // Using prepared statements for SQL Injection
    if ($stmt = $db->prepare('SELECT id, username, password, rank FROM members WHERE username = :username')) {
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $query = $stmt->execute();    // Execute the prepared query.
        $result = $query->fetchArray();

        if ($result) {
			$user_id = $result['id'];
			$rank = $result['rank'];
            $db_password = $result['password'];

            if (bruteForce($user_id,$db) == true) {
                alertRedirect("Failed logging in too many times","index.php");
            } else {
                if (equalString($password,$db_password)) {
					loggedIn($user_id,$username,$db);
					return true;
                } else {
                    // Password is not correct
                    $now = time();
					recordLoginAttempt($user_id,$now,$db);
                }
            }
        } else {
            return false;
        }
    }
}
