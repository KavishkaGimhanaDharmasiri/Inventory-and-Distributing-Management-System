<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Button Click</title>
  <link rel="stylesheet" type="text/css" href="divs.css">
  <style>
    #successModel {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 20px;
      background-color: white;
      color:#4CAF50 ;
      z-index: 1;
      border-radius: 15px;
      border: 2px solid indianred;
      height: 150px;
      width: 210px;
    }
      .suces{
      width: 50px;
            padding: 10px;
            background-color: indianred;
            color: #fff;
            border: none;
            border-radius: 15px;
            cursor: pointer;
             width: calc(100% - 5px);
    }
    #overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
     // background-color: white;
      backdrop-filter: blur(9px);
      z-index: 1;
    }
  </style>
</head>
<body>

<div id="overlay"></div>

<div id="successModel">
  <div class="j">
    <p style="color: indianred; font-size: 13pt; font-weight: bold; font-family: Calibri; margin-top: 0px; text-align: center;">Customer is Already Availble under Entered Telephone Number or Email Address<br>Please Check Whether Entered Details are Correct.</p></div>
  <button onclick="redirectTonormal()" class="suces">OK</button>
</div>
<br>

<script src="divs.js">
</script>
<script type="text/javascript">
  function redirectTonormal() {
    hideSuccess();
    // Redirect to index.php
    window.location.href = 'error.php';
  }
  function showSuccess() {
    var overlay = document.getElementById('overlay');
    var successModal = document.getElementById('successModel');

    overlay.style.display = 'block';
    successModal.style.display = 'block';
  }

  function hideSuccess() {
    var overlay = document.getElementById('overlay');
    var successModal = document.getElementById('successModel');

    overlay.style.display = 'none';
    successModal.style.display = 'none';
  }
  
</script>

</body>
</html>
