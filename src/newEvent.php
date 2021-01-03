<?php
include("config.php");
include("session.php");

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
	$name = mysqli_real_escape_string($db,$_POST['name']);
	$location = mysqli_real_escape_string($db,$_POST['location']); 
	$description = mysqli_real_escape_string($db,$_POST['description']);

	$datetimes = mysqli_real_escape_string($db,$_POST['datetimes']); 
	$pieces = explode(" - ", $datetimes);

	$visibility = mysqli_real_escape_string($db,$_POST['visibility']);
	//$image = mysqli_real_escape_string($db,$_POST['image']); 

	$userID = $login_user_id;


    //Validate name
	if (empty(trim($_POST["name"])))
	{
		$name_err = "Please enter a name";
	}
	else
	{
		$name = trim($_POST["name"]);
	}

    //Validate location
	if (empty(trim($_POST["location"])))
	{
		$location_err = "Please enter a location";
	}
	else
	{
		$location = trim($_POST["location"]);
	}

    //Validate description
	if (empty(trim($_POST["description"])))
	{
		$description_err = "Please enter a description";
	}
	else
	{
		$description = trim($_POST["description"]);
	}

    //Validate event time
	if (empty(trim($_POST["datetimes"])))
	{
		$datetimes_err = "Please enter a time";
	}
	else
	{
		$datetimes = trim($_POST["datetimes"]);
	}

	if (empty(trim($_POST["visibility"])))
	{
		$visibility_err = "Please enter a visibility";
	}
	else
	{
		$visibility = trim($_POST["visibility"]);
	}

	$address = $location; // Address

	// Get JSON results from this request
	$geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address));
	$geo = json_decode($geo, true); // Convert the JSON to an array

	if (isset($geo['status']) && ($geo['status'] == 'OK')) 
	{
  		$latitude = $geo['results'][0]['geometry']['location']['lat']; // Latitude
  		$longitude = $geo['results'][0]['geometry']['location']['lng']; // Longitude
	}

	$sql = "INSERT INTO locations (name, address, lat, lng, type) VALUES ('$name', '$location', '$latitude', '$longitude', '')";
	$result = mysqli_query($db,$sql);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$active = $row['active'];

	$sql = mysqli_query($db,"SELECT id FROM locations WHERE address = '$location' ");
    $row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
   	$id_location = $row['id'];

   	//SAVE IMAGE
   	//$image=$_FILES['imagefile']['tmp_name']; 
   	//$image = base64_encode(file_get_contents(addslashes($image))); //Get the content of the image and then add slashes to it 

	//Insert the image name and image content in image_table
	//$insert_image="INSERT INTO multimedia (post_id, content) VALUES('2', '$image')";

	//mysqli_query($db, $insert_image);
	if (empty($_FILES['userFile']['name']))
	{
		$err_no_file = 1;
	}
	else
	{
		$err_no_file = 0;
		$info = pathinfo($_FILES['userFile']['name']);
		$ext = $info['extension']; // get the extension of the file
		$newname = $userid.$id_location.".".$ext;

		$target = 'images/'.$newname;
		move_uploaded_file( $_FILES['userFile']['tmp_name'], $target);
	}

	$sql = "INSERT INTO posts (location_id, title, description, start_time, end_time, visible_to, created_by) VALUES ('$id_location', '$name', '$description', '$pieces[0]', '$pieces[1]', '$visibility', '$userID')";
	$result = mysqli_query($db,$sql);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$active = $row['active'];

	if ($err_no_file == 0)
	{

		$sql = mysqli_query($db,"SELECT id FROM posts WHERE location_id = '$id_location' AND title = '$name' ");
    	$row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
   		$id_posts = $row['id'];

		$insert_image="INSERT INTO multimedia (post_id, content_path) VALUES('$id_posts', '$newname')";
		mysqli_query($db, $insert_image);
	}

	if($result)
	{
		echo "Success";
		header("Location: home.php");

	}   
	else
	{
		echo "Error $pieces[0]";

	}
}

?>
<html>
<style type="text/css">
	.form-style-5{
		max-width: 500px;
		padding: 10px 20px;
		background: #f4f7f8;
		margin: 10px auto;
		padding: 20px;
		background: #f4f7f8;
		border-radius: 8px;
		font-family: Georgia, "Times New Roman", Times, serif;
	}
	.form-style-5 fieldset{
		border: none;
	}
	.form-style-5 legend {
		font-size: 1.4em;
		margin-bottom: 10px;
	}
	.form-style-5 label {
		display: block;
		margin-bottom: 8px;
	}
	.form-style-5 input[type="text"],
	.form-style-5 input[type="date"],
	.form-style-5 input[type="datetime"],
	.form-style-5 input[type="email"],
	.form-style-5 input[type="number"],
	.form-style-5 input[type="search"],
	.form-style-5 input[type="time"],
	.form-style-5 input[type="url"],
	.form-style-5 textarea,
	.form-style-5 select {
		font-family: Georgia, "Times New Roman", Times, serif;
		background: rgba(255,255,255,.1);
		border: none;
		border-radius: 4px;
		font-size: 15px;
		margin: 0;
		outline: 0;
		padding: 10px;
		width: 100%;
		box-sizing: border-box; 
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box; 
		background-color: #e8eeef;
		color:#8a97a0;
		-webkit-box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
		box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
		margin-bottom: 12px;
	}
	.form-style-5 input[type="text"]:focus,
	.form-style-5 input[type="date"]:focus,
	.form-style-5 input[type="datetime"]:focus,
	.form-style-5 input[type="email"]:focus,
	.form-style-5 input[type="number"]:focus,
	.form-style-5 input[type="search"]:focus,
	.form-style-5 input[type="time"]:focus,
	.form-style-5 input[type="url"]:focus,
	.form-style-5 textarea:focus,
	.form-style-5 select:focus{
		background: #d2d9dd;
	}
	.form-style-5 select{
		-webkit-appearance: menulist-button;
		height:35px;
	}
	.form-style-5 .number {
		background: #1abc9c;
		color: #fff;
		height: 30px;
		width: 30px;
		display: inline-block;
		font-size: 0.8em;
		margin-right: 4px;
		line-height: 30px;
		text-align: center;
		text-shadow: 0 1px 0 rgba(255,255,255,0.2);
		border-radius: 15px 15px 15px 0px;
	}

	.form-style-5 input[type="submit"],
	.form-style-5 input[type="button"]
	{
		position: relative;
		display: block;
		padding: 19px 39px 18px 39px;
		color: #FFF;
		margin: 0 auto;
		background: #1abc9c;
		font-size: 18px;
		text-align: center;
		font-style: normal;
		width: 100%;
		border: 1px solid #16a085;
		border-width: 1px 1px 3px;
		margin-bottom: 10px;
	}
	.form-style-5 input[type="submit"]:hover,
	.form-style-5 input[type="button"]:hover
	{
		background: #109177;
	}
</style>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<div class="form-style-5">
	<form method="post" enctype="multipart/form-data">
		<fieldset>
			<legend><span class="number">1</span> Event Info</legend>
			<input type="text" name="name" placeholder="Event Name">
			<input type="text" name="location" placeholder="Event Location">
			<textarea name="description" placeholder="Event Description"></textarea>
			<input type="text" name="datetimes" />

			<script>
				$(function() {
					$('input[name="datetimes"]').daterangepicker({
						timePicker: true,
						startDate: moment().startOf('hour'),
						endDate: moment().startOf('hour').add(32, 'hour'),
						locale: {
							format: 'YYYY-MM-DD hh:mm:ss'
						}
					});
				});
			</script>

			<label for="job">Visibility:</label>
			<select id="job" name="visibility">
				<option value="Public">Public</option>
				<option value="Private">Private</option>
				<option value="FOF">Friends-of-Friends</option>
			</select>      
		</fieldset>
		<fieldset>
			<legend><span class="number">2</span> Upload Photo</legend>
			<input type="file" name="userFile">
		</fieldset>
		<input type="submit" value="Create" />
	</form>
</div>
</html>