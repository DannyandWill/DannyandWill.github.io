<?php
ob_start();
require_once "vistas/parte_superior.php"; 
?>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Proveedores</h1>
    <?php
    include '../conexion.php'; // Incluye el archivo de conexión

    $conexion = mysqli_connect($host, $username, $password, $dbname);

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Obtener lista de proveedores
    $result = mysqli_query($conexion, "SELECT * FROM proveedor");
    ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                    <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <a href="editar_proveedor.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Editar</a> <br><br>
                        <a href="eliminar_proveedor.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a><br>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br>
    <a href="agregar_proveedor.php" class="btn btn-success">Agregar Proveedor</a> 
    <a href="index.php" class="btn btn-dark">Regresar al Menu</a>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
