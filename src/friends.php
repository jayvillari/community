<?php
include("config.php");
include("session.php");

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if (isset($_POST['request']))
  {
      $requested_user_id = mysqli_real_escape_string($db,($_POST["sendRequest"]));
      $subQuery = "select id from users where username = '$requested_user_id'";
      $sql = "INSERT INTO relationships (user_id_1, user_id_2, is_mutual) VALUES ('$login_user_id', ($subQuery), '0')";
      mysqli_query($db, $sql);
  }
  elseif (isset($_POST['approve']))
  {
      $pending_user_name = mysqli_real_escape_string($db,($_POST["approvedRequest"]));
      $sql = mysqli_query($db,"SELECT id FROM users WHERE username = '$pending_user_name' ");
      $row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
      $pending_user_id= $row['id'];

      $sql = mysqli_query($db,"UPDATE relationships SET is_mutual = '1' WHERE user_id_1 = '$pending_user_id' AND user_id_2 = '$login_user_id' ");
  }
  elseif (isset($_POST['decline']))
  {
      $pending_user_name = mysqli_real_escape_string($db,($_POST["approvedRequest"]));
      $sql = mysqli_query($db,"SELECT id FROM users WHERE username = '$pending_user_name' ");
      $row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
      $pending_user_id= $row['id'];

      $sql = mysqli_query($db,"DELETE FROM relationships WHERE user_id_1 = '$pending_user_id' AND user_id_2 = '$login_user_id' ");

  }
}  

?>

<html>
<style type="text/css">
  body {
  font-family: Arial;
  padding: 20px;
  background: #f1f1f1;
}

/* Header/Blog Title */
.header {
  padding: 10px;
  font-size: 36px;
  text-align: center;
  background: #ddd;
}

/* Create two unequal columns that floats next to each other */
/* Left column */
.leftcolumn { 
  float: left;
  width: 75%;
}

/* Right column */
.rightcolumn {
  float: left;
  width: 25%;
  padding-left: 20px;
}

/* Fake image */
.fakeimg {
  background-color: #aaa;
  width: 50%;
  padding: 20px;
}

/* Add a card effect for articles */
.card {
  width: 95%;
  background-color: white;
  padding: 20px;
  margin-top: 20px;
}

/* Footer */
.footer {
  padding: 20px;
  text-align: left;
  background: #ddd;
  margin-top: 20px;
}

</style>
<div class="header">
  <h2>Manage Friends</h2>
</div>

<div class="row">
    <div class="card">
      <h2>Send Friend Request</h2>
      <form method="POST">
        <input type="text" name="sendRequest" placeholder="User Name">
        <input type = "submit" value = "Send Request" name='request'/>
      </form>
    </div>
    <div class="card">
      <h2>Pending Requests</h2>
      <p><?php 
        $sql = mysqli_query($db,"SELECT * FROM relationships WHERE user_id_2 ='$login_user_id' and is_mutual='0'");
        
        while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
        {
        $pendingReq = $row['user_id_1'];
        $sql = mysqli_query($db,"SELECT username FROM users WHERE id ='$pendingReq'");
        while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
        {
          $pendingReq2 = $row['username'];
          echo "$pendingReq2 <br/><br/>";
        }
        
        }

        ?>

      </p>
      <form method="POST">
        <input type="text" name="approvedRequest" placeholder="User Name">
        <input type = "submit" value = "Approve" name='approve'/>
        <input type = "submit" value = "Decline" name='decline'/>
      </form>
    </div>
    <div class="footer">
      <h3>Friends</h3>
      <p><?php 
        $sql = mysqli_query($db,"CALL get_friends('$login_user_id')");
        
        while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
        {
        $postedComments = $row['name'];
        echo "$postedComments <br/><br/>";
        }

        ?>

      </p>
    </div>
</div>
</html>