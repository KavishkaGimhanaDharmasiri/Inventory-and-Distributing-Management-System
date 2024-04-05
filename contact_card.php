<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Card</title>
    <style>
        #locationDetails {
            display: none;
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }
        #map {
            height: 300px;
            width: 100%;
        }
    </style>
</head>
<body>

<?php
// Contact information
$contactNumbers = [
    'Danushka' => '077-22124569',
    'Main Branch' => '043-22579853',
];

$location = 'Your Current Location, Country';

// Display contact card
echo '<h2>Contact Information</h2>';
echo '<ul>';
foreach ($contactNumbers as $name => $number) {
    echo '<li>' . $name . ': <a href="javascript:void(0);" onclick="openCallApp(\'' . str_replace('-', '', $number) . '\')">' . $number . '</a></li>';
}
echo '</ul>';

echo '<p>Location: <a href="https://www.latlong.net/c/?lat=6.0630954&long=80.5415007">Show Location</a></p>';
?>

<div id="locationDetails">
    <div id="map"></div>
</div>

<script async defer
        src="https://www.latlong.net/c/?lat=6.0630954&long=80.5415007">
</script>

<script>
    function openCallApp(number) {
        window.location.href = 'tel:' + number;
    }

    function loadMap(location) {
        document.getElementById('locationDetails').style.display = 'block';
        document.getElementById('map').innerHTML = ''; // Clear existing map

        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 0, lng: 0}, // Set initial center to avoid errors
            zoom: 15
        });

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': decodeURIComponent(location)}, function (results, status) {
            if (status === 'OK') {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });
            } else {
                console.error('Geocode was not successful for the following reason: ' + status);
            }
        });
    }
</script>

</body>
</html>
