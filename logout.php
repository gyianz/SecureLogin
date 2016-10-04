<?php
include_once 'config.php';
include_once 'lib.php';

clearSession($db);
deleteCookies();

alertRedirect("Logout successful","index.php");
 ?>
