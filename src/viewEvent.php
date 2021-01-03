<?php
include("config.php");
include("session.php");

$post_id = $_SERVER['QUERY_STRING'];
$userID = $login_user_id;

//get post info
$sql = mysqli_query($db,"select * from posts where id = '$post_id' ");
$row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
$title = $row['title'];
$description = $row['description'];
$start_time = $row['start_time'];
$end_time = $row['end_time'];
$privacy = $row['visible_to'];
$poster_id = $row['created_by'];

//check privileges
if ($privacy == "Private")
{
  $sql = mysqli_query($db,"CALL get_friends('$login_user_id')");
  $isFriend = 0;      
  while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
  {
      $friendID = $row['id'];
      if ($friendID == $poster_id)
      {
        $isFriend = 1;
      }
  }

  if ($isFriend == 0)
  {
    header("Location: restricted.php");
  }
}

if ($privacy == "FOF")
{
  $sql = mysqli_query($db,"CALL get_friends('$login_user_id')");
  $isFriend = 0;      
  while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
  {
      $friendID = $row['id'];
      if ($friendID == $poster_id)
      {
        $isFriend = 1;
      }
  }

  if ($isFriend == 0)
  {
    header("Location: restricted.php");
  }
}

//get multimedia info
$sql = mysqli_query($db,"select * from multimedia where post_id = '$post_id' ");
$row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
$file_name = $row['content_path'];
if ($file_name == "")
{
  $file_name = "default.png";
}

$img_path = "images/".$file_name;

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if (isset($_POST['like']))
  {
      $sql = "INSERT INTO likes (post_id, user_id) VALUES ('$post_id', '$userID')";
      mysqli_query($db, $sql);
  }
  elseif (isset($_POST['dislike']))
  {
    $sql = "DELETE FROM likes WHERE post_id ='$post_id' AND user_id = '$userID'";
      mysqli_query($db, $sql);
  }
  elseif (isset($_POST['comment']))
  {
    $comment = mysqli_real_escape_string($db,($_POST["commentbox"]));
    $sql = "INSERT INTO comments (post_id, user_id, comment) VALUES ('$post_id', '$userID', '$comment')";
    mysqli_query($db, $sql);
  }
}   

$sql = mysqli_query($db,"select count(*) as count from likes where post_id = '$post_id' ");
$row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
$num_likes = $row['count'];

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
  <h2><?php echo $title; ?></h2>
</div>

<div class="row">
    <div class="card">
      <h2><?php echo $description; ?></h2>
      <img src=<?php echo $img_path; ?> >
      <h4>Start Time: <?php echo $start_time; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; End Time: <?php echo $end_time; ?></h4>
      <form method="POST">
        <input type = "submit" value = "Like" name='like'/>
        <input type = "submit" value = "Dislike" name='dislike'/>
        <h4>Popularity: <?php echo $num_likes; ?></h4>
      </form>
    </div>
    <div class="footer">
      <h3>Comments</h3>
      <p><?php 
        $sql = mysqli_query($db,"SELECT * FROM comments WHERE post_id = '$post_id' ");
        
        while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
        {
        $postedComments = $row['comment'];
        echo "$postedComments <br/><br/>";
        }

        ?>

      </p>
      <form method="POST">
        <textarea name="commentbox" placeholder="New comment"></textarea>
        <input type = "submit" value = "Comment" name='comment'/>
      </form>
    </div>
</div>
</html>