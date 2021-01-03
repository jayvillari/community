<?php
include("config.php");
include("session.php");

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
  $strSearch = mysqli_real_escape_string($db,($_POST["strSearch"]));
  $sql = mysqli_query($db,"CALL search_posts('$strSearch')");
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
  <h2>Search</h2>
</div>

<div class="row">
    <div class="card">
      <h3>Search by keyword</h3>
      <form method="POST">
        <input type="text" name="strSearch" placeholder="Search...">
        <input type = "submit" value = "Search" name='request'/>
      </form>
      <p><?php 
        
        while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
        {
        $results = $row['title'];
        $post_id = $row['id'];
        echo "<a href='viewEvent.php?$post_id'>$results <br/><br/></a>";
        }

        ?>

      </p>
    </div>
</div>
</html>