<?php
echo'<script>alert("hello");</script>';
   ob_end_clean();
  require_once('vbs.php');
  ob_end_clean();
?>