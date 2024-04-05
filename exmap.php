
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="google-site-verification" content="vZm0ZMT-vb8aUejnNpsfLSZLYrizCqzwdIISmcwN9Y4">

    <link rel="icon" type="image/png" href="/favicon.png">
    
<link rel="icon" sizes="192x192" href="https://www.latlong.net/icon.png">
<link rel="apple-touch-icon" href="https://www.latlong.net/apple-touch-icon-152x152.png" sizes="152x152">


    <link rel="preconnect" href="https://cdn.pubmax.co">
    <link rel="dns-prefetch" href="https://cdn.pubmax.co">
    <script  src="https://cdn.pubmax.co/llg.pm.js" async ></script>


    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-159581532-5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-159581532-5');
    </script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css"
  integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
  crossorigin=""/>
</head>
<body>
<header>

</header><main>
<div class="center margin10" style="min-height:100px">
    <div pm-adunit='/22815767462/LLG_728v_1' ></div>
    <div pm-adunit='/22815767462/LLG_Mob_300v_1' ></div>
</div>
<div class="whitebg">
<div id="latlongmap" style="width:75%;height:400px;" class="shadow"></div>
  <div class="margin10 center" style="min-height:250px">
    <div pm-adunit='/22815767462/LLG_728v_2' ></div>
    <div pm-adunit='/22815767462/LLG_Mob_300v_2' ></div>
</div>
<iframe width="360" height="400" frameborder="0" style="border:0;width:100%" src="https://www.google.com/maps/embed/v2/view?key=AIzaSyALrSTy6NpqdhIOUs3IQMfvjh71td2suzY&center=6.0630954,80.5415007&zoom=16&maptype=satellite" allowfullscreen></iframe>

</main>

<div class="stickyAd">
    <div pm-adunit='/22815767462/LLG_160v_Sticky' ></div>
</div>
<div class="stickyAdMobile" style="text-align:center;background-color:#fff">
    <div pm-adunit='/22815767462/LLG_Mob_300v_sticky' ></div>
</div>
 <div pm-adunit='/22815767462/LLG_Desktop_Anchor' ></div>

<script type="application/ld+json">{"@context":"http://schema.org","@type":"WebSite","name":"Lat Long Finder","alternateName":"Latitude and Longitude Finder","url":"https://www.latlong.net/","sameAs":["https://twitter.com/latlong_net"]}</script>
<script>var yid ='';
</script>
<script>

function dropMenu() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}

function moveAds(){let e=document.getElementsByTagName("main")[0].offsetLeft-160;e>0&&(document.getElementsByClassName("stickyAd")[0].style.left=e+"px")}window.onresize=moveAds,window.onload=moveAds,document.addEventListener("scrollend",e=>{moveAds()});
</script>
<script src="https://unpkg.com/leaflet@1.3.3/dist/leaflet.js"
  integrity="sha512-tAGcCfR4Sc5ZP5ZoVz0quoZDYX5aCtEm/eu1KhSLj2c9eFrylXZknQYmxUssFaVJKvvc0dJQixhGjG2yXWiV9Q=="
  crossorigin="">

  function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    //x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
 // x.innerHTML = "Latitude: " + position.coords.latitude + 
  //"<br>Longitude: " + position.coords.longitude;

var mymap = L.map('latlongmap');
var mmr = L.marker([position.coords.latitude,position.coords.longitude]);
mmr.bindPopup('position.coords.latitude,position.coords.longitude');
mmr.addTo(mymap);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {foo: 'bar',
attribution:'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(mymap);
mymap.setView([position.coords.latitude,position.coords.longitude],14);
}
</script>
</body>
</html>