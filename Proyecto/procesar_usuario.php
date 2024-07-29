<?php
require '../conexion.php';

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
$rol_id = $_POST['rol_id'];

$sql = "INSERT INTO usuarios (nombre, email, contrasena, rol_id) VALUES ('$nombre', '$email', '$contrasena', '$rol_id')";
if (mysqli_query($conexion, $sql)) {
    echo "Nuevo usuario agregado exitosamente";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
}

mysqli_close($conexion);
?>
