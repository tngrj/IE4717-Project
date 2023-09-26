<?php
$host = "localhost";
$username = "user";
$pw = "password";
$db_name = "testDB";

$conn = new mysqli($host, $username, $pw, $db_name);
if (!$conn) {
    die('Database connection failed');
}
