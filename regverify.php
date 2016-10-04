<?php

    include 'config.php';
    include 'lib.php';

    $validate = false;
    $errors = false;
    $errormessage = 'Contains errors:\n';

    $username = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $password1 = $_POST['pw1'];
    $password2 = $_POST['pw2'];
    $rank = 0;

    //check if username is empty
    if (!empty($username)) {
        //validate username input
        if (!preg_match('/^[a-zA-Z0-9]/', $username)) {
            $errors = true;
            $errormessage = $errormessage.'Username not valid.\n';
        } else {
            //check if username exists

            if ($stmt = $db->prepare('SELECT username FROM members WHERE username = :username')) {
                $stmt->bindValue(':username', $username, SQLITE3_TEXT);
                $query = $stmt->execute();    // Execute the prepared query.
                $result = $query->fetchArray();
                $rows = count($result);

                if ($result && $rows > 0) {
                    $errors = true;
                    alertRedirect('Cannot create account, username already exists', '../register.php');
                }
            }
        }
    } else {
        $errors = true;
        $errormessage = $errormessage.'Username is required.\n';
    }

    //validate password input
    if (!empty($password1) && !empty($password2)) {
        if ($password1 != $password2) {
            $errormessage = $errormessage.'Passwords are not matched.\n';
            $errors = true;
        }
    } else {
        $errormessage = $errormessage.'Password must not be empty.\n';
        $errors = true;
    }

    if ($errors == false) {
        $password = hashString($password1);

        //Prepared statement to prevent SQL Injection
        if ($stmt = $db->prepare('INSERT INTO members (`username`,`password`,`rank`) VALUES (:username,:password,:rank)')) {
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            $stmt->bindValue(':password', $password, SQLITE3_TEXT);
            $stmt->bindValue(':rank', $rank, SQLITE3_INTEGER);
            $query = $stmt->execute();    // Execute the prepared query.
            alertRedirect('DONE. LOGIN WITH YOUR NEW ACCOUNT NOW', '../index.php');
        }
    } else {
        //if errors found, display error list and return to form
        alertRedirect($errormessage, 'register.php');
    }
