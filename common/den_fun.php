<?php

function acess_denie()
{


  echo '<div id="body"><div  class="dencon">
  <p style="color: indianred; font-size: 13pt; font-weight: bold; font-family: Calibri; margin-top: 0px; text-align: center;">Acess Denied</p><br>
  <a href="/index.php" style="font-weight: bold; text-decoration: none;color: white;"><button  style="width: 50px;
            padding: 8px;
            background-color: indianred;
            color: #fff;
            border: none;
            border-radius: 15px;
            cursor: pointer;
             width: calc(100% - 5px);">OK</button></a>
</div> </diV>';
}
?>
<style>
  #body {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-size: cover;
  }

  .dencon {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 100%;
    max-width: 400px;
    border: 1px solid indianred;
  }
</style>