<?php
ob_start();
require_once "vistas/parte_superior.php"; 
?>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Ventas Realizadas</h1>
    <?php
    include '../conexion.php'; // Incluye el archivo de conexión

    // Crear la conexión
    $conexion = mysqli_connect($host, $username, $password, $dbname);

    // Verificar la conexión
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Ejecutar la consulta para obtener ventas
    $query_ventas = "SELECT v.id, v.fecha, v.total, v.condicion, c.nombre AS cliente 
                     FROM venta v 
                     INNER JOIN cliente c ON v.cliente_id = c.id";
    $result_ventas = mysqli_query($conexion, $query_ventas);

    // Verificar errores en la consulta
    if (!$result_ventas) {
        die("Error al obtener las ventas: " . mysqli_error($conexion));
    }

    // Obtener todas las ventas
    $ventas = mysqli_fetch_all($result_ventas, MYSQLI_ASSOC);
    ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Condición</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?php echo htmlspecialchars($venta['id']); ?></td>
                    <td><?php echo htmlspecialchars($venta['cliente']); ?></td>
                    <td><?php echo htmlspecialchars($venta['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($venta['total']); ?></td>
                    <td><?php echo htmlspecialchars($venta['condicion']); ?></td>
                    <td>
                        <a href="ver_detalle_venta.php?id=<?php echo $venta['id']; ?>" class="btn btn-info btn-sm">Ver Detalle</a>
                        <a href="editar_venta.php?id=<?php echo $venta['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="eliminar_venta.php?id=<?php echo $venta['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="agregar_venta.php" class="btn btn-success">Agregar Venta</a>
    <a href="index.php" class="btn btn-dark">Regresar al Menu</a>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
