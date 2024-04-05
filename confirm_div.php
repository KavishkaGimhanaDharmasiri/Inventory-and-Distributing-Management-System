<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Button Click</title>
  <link rel="stylesheet" type="text/css" href="divs.css">
</head>
<body>

<div id="overlay"></div>

<div id="successModal" style="height: 170px;">
  <div class="gif"></div>
  <button onclick="redirectTopdf()" class="sucess">Print Recipt</button>
  <br>
  <button onclick="redirectToIndex()" class="sucess">Return Option</button>
</div>

<script >
  function showSuccess() {
    var overlay = document.getElementById('overlay');
    var successModal = document.getElementById('successModal');

    overlay.style.display = 'block';
    successModal.style.display = 'block';
  }

  function hideSuccess() {
    var overlay = document.getElementById('overlay');
    var successModal = document.getElementById('successModal');

    overlay.style.display = 'none';
    successModal.style.display = 'none';
  }

function redirectToIndex() {
    hideSuccess();
    // Redirect to index.php
    window.location.href = 'vbs1.php';
  }
/*function redirectTopdf() {
    hideSuccess();
    // Redirect to index.php
    window.location.href = 'vbs1.php';
  }*/
</script>

</body>
</html>
