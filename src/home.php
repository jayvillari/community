<?php
include("session.php");

$address = $login_city; // Address

// Get JSON results from this request
$geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key');
$geo = json_decode($geo, true); // Convert the JSON to an array

if (isset($geo['status']) && ($geo['status'] == 'OK')) {
  $latitude = $geo['results'][0]['geometry']['location']['lat']; // Latitude
  $longitude = $geo['results'][0]['geometry']['location']['lng']; // Longitude
}
?>
<html >
<head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <title>Welcome</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <style>
      /* Always set the map height explicitly to define the size of the div
      * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

  <html>
  <body>
    <style>
     #wrapper { position: relative; }
     #over_map { position: absolute; top: 10px; left: 950px; z-index: 99; }
     #create_post {position: absolute; top: 400px; left: 25px; z-index: 40; background-color: #ffffff;
     border-radius: 20px; border-style: double; padding-left: 12px; padding-right: 12px;/*add this if you want a bit of space around the text*/}
     #add_friends {position: absolute; top: 450; left: 25px; z-index: 40; background-color: #ffffff;
     border-radius: 20px; border-style: double; padding-left: 12px; padding-right: 12px;/*add this if you want a bit of space around the text*/}
     #search {position: absolute; top: 500px; left: 25px; z-index: 40; background-color: #ffffff;
     border-radius: 20px; border-style: double; padding-left: 12px; padding-right: 12px;/*add this if you want a bit of space around the text*/}
     #profile {position: absolute; top: 160px; left: 950px; z-index: 99; }
   </style>

   <div id="wrapper">
    <div id="map"></div> 
    <div id="over_map">
      <h1>Welcome <?php echo $login_session; ?></h1> 
      <h2><a href = "logout.php">Sign Out </a></h2>
    </div>
    <div id="create_post">
      <h4><a href = "newEvent.php">New Post</a></h4>
    </div>
    <div id="add_friends">
      <h4><a href = "friends.php">Friends</a></h4>
    </div>
    <div id="search">
      <h4><a href = "search.php">Search</a></h4>
    </div>
    <div id="profile">
      <h2><a href = "profile.php">Edit Profile</a></h2>
    </div>
  </div>

  <script>
    var customLabel = {
      restaurant: {
        label: 'R'
      },
      bar: {
        label: 'B'
      }
    };

    function initMap() { 
      var lat = "<?php echo $latitude ?>"; 
      var long = "<?php echo $longitude ?>"; 
      var map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(lat, long),
        zoom: 12
      });
      var infoWindow = new google.maps.InfoWindow;

          // Change this depending on the name of your PHP or XML file
          downloadUrl('createXML.php', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var id = markerElem.getAttribute('id');
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                parseFloat(markerElem.getAttribute('lat')),
                parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('a');
              text.href = "viewEvent.php?" + (id - 1);
               text.textContent = address
               infowincontent.appendChild(text);

              var icon = customLabel[type] || {};
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
        }



        function downloadUrl(url, callback) {
          var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

          request.onreadystatechange = function() {
            if (request.readyState == 4) {
              request.onreadystatechange = doNothing;
              callback(request, request.status);
            }
          };

          request.open('GET', url, true);
          request.send(null);
        }

        function doNothing() {}
      </script>
      <script async defer
      src="https://maps.googleapis.com/maps/api/js">
    </script>
  </body>
  </html>