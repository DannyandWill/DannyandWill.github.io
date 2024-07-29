<?php
ob_start();
require_once "vistas/parte_superior.php"; 
?>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Agregar Categoría</h1>
    <?php
    include '../conexion.php'; // Incluye el archivo de conexión

    // Conectar a la base de datos
    $conexion = mysqli_connect($host, $username, $password, $dbname);

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];

        try {
            $stmt = $conexion->prepare("INSERT INTO categoria (nombre) VALUES (?)");
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            header("Location: ver_categorias.php");
            exit;
        } catch (Exception $e) {
            die("Error al agregar la categoría: " . $e->getMessage());
        }

        $stmt->close();
    }

    mysqli_close($conexion);
    ?>

    <form method="post">
        <div class="form-group">
            <label for="nombre">Nombre de la Categoría:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
    <br>
    <a href="ver_categorias.php" class="btn btn-secondary">Volver a Categorías</a>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
