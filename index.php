<?php
	include_once 'lib.php';
	include_once 'config.php';
	clearSession($db);
	deleteCookies();
?>
<html lang = "en">
   <head>
      <title>Login</title>
   </head>

   <body>

      <h2>Login Page</h2>

      <div class = "container">

		  <form action="<?php echo sanitize('login.php'); ?>" method="POST" name="login_form">
               Username: <input type="text" name="username" />
               Password: <input type="password" name="password" id="password"/>
              <input type="submit" value="Login" />
           </form>

		 Don't have an account? Register <a href="register.php"> here </a>

      </div>

   </body>
</html>
