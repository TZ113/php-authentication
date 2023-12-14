<?php
$serverName = 'localhost';
$userName = 'root';
$password = '20Butterfly26';
$dbName = "test";

$conn = mysqli_connect($serverName, $userName, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed" . $conn->connect_error);
}

function testInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);

    return $input;
}
