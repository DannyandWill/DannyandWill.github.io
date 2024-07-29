<?php
$host = "localhost";
$dbname = "sistema";
$username = "root";
$password = "";

$conexion = mysqli_connect($host, $username, $password, $dbname);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
