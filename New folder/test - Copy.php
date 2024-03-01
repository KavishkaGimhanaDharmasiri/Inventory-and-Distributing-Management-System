<?php
require_once('email_sms.php');

$name="kamal";

$body="dear".$name;
$subject="testing ".$name;
$user="prolinkpc2@gmail.com";

sendmail($subject,$body,$user);
echo "sucessfull";
?>