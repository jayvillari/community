<?php
   include("config.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") 
   {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      
      $sql = "SELECT id FROM users WHERE username = '$myusername' and password = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];

      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
    
      if($count == 1) 
      {
         //session_register("myusername");
         $_SESSION['login_user'] = $myusername;
         $_SESSION['Error'] = "Welcome" . $myusername;
        //echo "You have successfully logged in!";
         header("Location: home.php");
      }
      else 
      {
         $_SESSION['Error'] = "Invalid Username/Password Combination";
      }
   }
?>

<html>

<head>
  <title>Community</title>
  <link rel="stylesheet" type="txt/css" href="style.css">
</head>
  
  <body>
      
      <div class="loginbox">

        <img src="avatar.png" class="avatar">
        <h1>Community</h1>

        <form action = "" method = "post">
          <label>
          <p>Username</p>
          <input type="text" name="username" placeholder="Enter Username">
        </label>
        <label>
          <p>Password</p>
          <input type="password" name="password" placeholder="Enter Password">
          </label>
          <label>
          <input type="submit" name="" value="Login">
          </label>
          <?php if( isset($_SESSION['Error']) )
          {
              echo $_SESSION['Error'];
              unset($_SESSION['Error']);
          } ?>
          <a href="register.php"> Don't have an account?</a>
        </form>

      </div>

  </body>

  </html>