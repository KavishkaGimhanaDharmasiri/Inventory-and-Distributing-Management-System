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
    window.location.href = "<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/common/option.php";
  }
  