<?php 

function sequence(){
  echo'<ul class="breadcrumb">';
if(isset($_SESSION['index_visit'])){
    echo'<li><a href="index.php"><b>HOME</a></li>';
}
if(isset($_SESSION['option_visit'])){
  
  echo'<li><a href="option.php">OPTION</a></li>';
}
if(isset($_SESSION['new_sale_order_visit'])){
  
  echo'<li><a href="new_order.php">CREATE ORDER</a></li>';
}
if(isset($_SESSION['payment_visit'])){
  
  echo'<li><a href="payment.php">CREATE ORDER</a></li>';
}
if(isset($_SESSION['add_customer_visit'])){
  
  echo'<li><a href="add_wholesalecustomer.php">ADD CUSTOMER</a></li>';
}
if(isset($_SESSION['view_order_visit'])){
  
  echo'<li><a href="view_order.php">VIEW ORDERS</a></li>';
}
if(isset($_SESSION['generate_repport_visit'])){
  
  echo'<li><a href=".php">OPTION</a></li>';
}
echo'</ul>';
}
?>