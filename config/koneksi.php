<?php
$hostname = 'localhost';
$username = 'craftcoi_bamboo';
$password = "QS6OODCrD6;J5PR0IC";
$database = "craftcoi_peminjaman_motor";

// Establish connection
$connect = mysqli_connect($hostname, $username, $password, $database);

// Check connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

