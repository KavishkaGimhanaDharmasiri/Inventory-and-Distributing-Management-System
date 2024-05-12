<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>Loutos</title>

    <link rel="stylesheet" href="css/navistyle.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <style type="text/css">

        .dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: white;
  min-width: 160px;
  overflow: auto;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: green;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown a:hover {background-color: #D2F7B7;}

.show {display: block;}
    </style>
</head>

<body>
    <section>
        <nav>
        <header class="index-body">
        <div class="logo"><img src="Images/Decoration/lotus.png" height="100px" >Lotus</div>

        <div class="bx bx-menu" id="menu-icon"></div>
        <ul class="navbar">
            
                <a href="Dashboard.php" class="home-active" id="Dashboard"><h2>Home</h2></a>
                <a href="http://localhost/Inventory-and-Distributing-Management-System/Dashboard.php#Products" class="home-active" id="Categories"><h2>Products</h2></a>
                <a href="http://localhost/Inventory-and-Distributing-Management-System/Dashboard.php#About" class="home-active" id="about"><h2>About</h2></a>
                <a href="http://localhost/Inventory-and-Distributing-Management-System/Dashboard.php#Review"><h2>Review</h2></a>
                <a href="http://localhost/Inventory-and-Distributing-Management-System/Dashboard.php#Servises"><h2>Servises</h2></a>
            
            </ul>
            <div class="cart-profile">
            
           <div class="dropdown">
    <img src="Images/Decoration/profile.png" onclick="myFunction()" class="dropbtn" style="width: 50px; height: 50px;">
    <div id="myDropdown" class="dropdown-content">
        <a href="profileupdate.php">Edit Profile</a>
        <a href="LogOut.php">Log Out</a>
    </div>
</div>
&nbsp&nbsp
            <a href="Cart.php" class="index-cart"><i class='bx bx-cart' ></i></a> 
            </div>

        </header>
        </nav>
    </section>
<script>

function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>

</body>

</html>