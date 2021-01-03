<?php
include("config.php");
include("session.php");

$sql = mysqli_query($db,"select * from users where id = '$login_user_id' ");
$row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
$user_id = $row['id'];
$email = $row['email'];
$username = $row['username'];
$name = $row['name'];
$age = $row['age'];
$city = $row['city'];
$biography = $row['biography'];

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
  $new_email = mysqli_real_escape_string($db,$_POST['emailBox']);
  $new_name = mysqli_real_escape_string($db,$_POST['nameBox']); 
  $new_age = mysqli_real_escape_string($db,$_POST['ageBox']);
  $new_city = mysqli_real_escape_string($db,$_POST['cityBox']);
  $new_biography = mysqli_real_escape_string($db,$_POST['biographyBox']); 

  $sql = mysqli_query($db,"UPDATE users SET email = '$new_email', name = '$new_name', age = '$new_age', city = '$new_city', biography = '$new_biography' WHERE id = '$login_user_id'");
  $result = mysqli_query($db,$sql);
  $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
  $active = $row['active'];

  header("Location: home.php");
 
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
  <h2>Edit Profile</h2>
</div>

<div class="row">
  <div class="card">
    <form method="POST">
    <table width="100%">
      <tr>
        <td align="center"><h2>Email</h2>
          <input type="text" name="emailBox" value="<?php echo $email; ?>"></input></td>
          <td align="center"><h2>Username</h2>
            <input type="text" readonly="readonly" name="userNameBox" value="<?php echo $username; ?>"></input></td>
          </tr>
          <tr><td align="center">
            <h2>Name</h2>
            <input type="text" name="nameBox" value="<?php echo $name; ?>"></input></td>
            <td align="center"><h2>Age</h2>
              <input type="text" name="ageBox" value="<?php echo $age; ?>"></input></td>
            </tr>
            <tr><td align="center">
            <h2>City</h2>
            <input type="text" name="cityBox" value="<?php echo $city; ?>"></input></td>
            <td align="center"><h2>Biography</h2>
              <input type="text" name="biographyBox" value="<?php echo $biography; ?>"></input></td>
            </tr>
            <tr><td colspan="2" align="center">
                <input type = "submit" value = "Update"/>
              </td></tr>
            </table>
            </form>
          </div>
        </div>
        </html>