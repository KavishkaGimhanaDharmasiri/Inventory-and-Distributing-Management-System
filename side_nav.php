<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.sidenav {
  display: none;
  height: 100%;
  width: 250px;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #4caf50;
  overflow-x: hidden;
  padding-top: 60px;
}

.sidenav a {
  padding: 0px 0px 8px 0px;
  text-decoration: none;
  font-size: 25px;
  color: black;
  text-align: center;
  display: block;
}
.sidenav a:hover {
  color: #f1f1f1;
}

.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
</style>
</head>
<body>

<div id="mySidenav" class="sidenav">
  <img src="lotus.png" style="height: 50px; width: 50px; margin-left: 30%;">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="#">About</a>
  <a href="#">Visit Website</a>
  <a href="#">Contact</a>
</div>

<span style="font-size:30px;cursor:pointer; position: absolute;top: 8px;left: 16px;" onclick="openNav()">&#9776;</span>

<script>
function openNav() {
  document.getElementById("mySidenav").style.display = "block";
}

function closeNav() {
  document.getElementById("mySidenav").style.display = "none";
}
</script>
  
</body>
</html> 