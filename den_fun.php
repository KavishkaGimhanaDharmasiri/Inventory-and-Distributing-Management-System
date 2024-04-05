<?php

function acess_denie()
{
echo'<div id="overlay"></div>

<div  style ="position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 20px;
      background-color: white;
      color:#4CAF50 ;
      z-index: 1;
      border-radius: 15px;
      border: 2px solid indianred;
      height: 70px;
      width: 150px;">
  <p style="color: indianred; font-size: 13pt; font-weight: bold; font-family: Calibri; margin-top: 0px; text-align: center;">Acess Denied</p>
  <a href="index.php" style="font-weight: bold; text-decoration: none;color: white;"><button  style="width: 50px;
            padding: 8px;
            background-color: indianred;
            color: #fff;
            border: none;
            border-radius: 15px;
            cursor: pointer;
             width: calc(100% - 5px);">OK</button></a>
</div> ';
}

?>