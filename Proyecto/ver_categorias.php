<?php
ob_start();
require_once "vistas/parte_superior.php"; 
?>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Lista de Categorías</h1>
    <?php
    include '../conexion.php'; // Incluye el archivo de conexión

    // Conectar a la base de datos
    $conexion = mysqli_connect($host, $username, $password, $dbname);

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    try {
        $result = mysqli_query($conexion, "SELECT * FROM categoria");
        if (!$result) {
            throw new Exception("Error al obtener las categorías: " . mysqli_error($conexion));
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }

    ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td>
                        <a href="editar_categoria.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="eliminar_categoria.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br>
    <a href="agregar_categoria.php" class="btn btn-success">Agregar Categoría</a>
    <a href="index.php" class="btn btn-dark">Regresar al Menu</a>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
