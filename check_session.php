<?php
session_start();

if (isset($_SESSION['sales_receipt_download'])) {
    echo 'true';
} else {
    echo 'false';
}
