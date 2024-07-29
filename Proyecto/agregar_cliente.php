<?php
ob_start();
require_once "vistas/parte_superior.php"; 
?>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Agregar Cliente</h1>
    <?php
    include '../conexion.php'; // Incluye el archivo de conexión

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];

        // Conectar a la base de datos
        $conexion = mysqli_connect($host, $username, $password, $dbname);

        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }

        try {
            $stmt = $conexion->prepare("INSERT INTO cliente (nombre, direccion, telefono, email) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $direccion, $telefono, $email);
            $stmt->execute();

            header("Location: ver_clientes.php");
            exit;
        } catch (Exception $e) {
            die("Error al agregar el cliente: " . $e->getMessage());
        }

        // Cerrar la conexión
        $stmt->close();
        mysqli_close($conexion);
    }
    ?>

    <form method="post">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <input type="text" class="form-control" id="direccion" name="direccion">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" class="form-control" id="telefono" name="telefono">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
    <br>
    <a href="ver_clientes.php" class="btn btn-secondary">Volver a Clientes</a>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
