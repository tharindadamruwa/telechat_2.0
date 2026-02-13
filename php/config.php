<?php
//$conn = mysqli_connect("localhost", "root", "", "telechat");

$conn = mysqli_connect("sql312.infinityfree.com", "if0_41143045", "jI7h89Id0H", "if0_41143045_telechat");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>