<?php
   include('config.php');
   session_start();
   
   //set user name
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = mysqli_query($db,"select username from users where username = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['username'];

   
   //set user city
   $ses_sql = mysqli_query($db,"select city from users where username = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_city = $row['city'];


   //set user id
   $ses_sql = mysqli_query($db,"select id from users where username = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_user_id = $row['id'];

   
   if(!isset($_SESSION['login_user'])){
      header("location:index.php");
      die();
   }
?>