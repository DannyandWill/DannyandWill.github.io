<?php
ob_start();
require_once "vistas/parte_superior.php"; 
?>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Lista de Productos</h1>
    <?php
    include '../conexion.php'; // Incluye el archivo de conexión

    // Conectar a la base de datos
    $conexion = mysqli_connect($host, $username, $password, $dbname);

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Consultar productos
    $query = "
        SELECT p.id, p.nombre, p.descripcion, p.precio, c.nombre AS categoria, s.cantidad, s.fecha_actualizacion
        FROM producto p
        LEFT JOIN categoria c ON p.categoria_id = c.id
        LEFT JOIN stock s ON p.id = s.producto_id
    ";
    $result = mysqli_query($conexion, $query);

    if (!$result) {
        die("Error al obtener los productos: " . mysqli_error($conexion));
    }
    ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Fecha de Actualización</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($producto = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['id']); ?></td>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                    <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                    <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars($producto['fecha_actualizacion']); ?></td>
                    <td>
                        <a href="editar_productos.php?id=<?php echo $producto['id']; ?>" class="btn btn-primary btn-sm">Editar</a> <br><br>
                        <a href="eliminar_productos.php?id=<?php echo $producto['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br>
    <a href="agregar_producto.php" class="btn btn-success">Agregar Producto</a>
    <a href="index.php" class="btn btn-dark">Regresar al Menú</a>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
