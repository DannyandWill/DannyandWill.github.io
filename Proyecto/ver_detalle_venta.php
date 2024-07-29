<?php
ob_start();
require_once "vistas/parte_superior.php"; 
?>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Detalle de Venta</h1>
    <?php
    include '../conexion.php'; // Incluye el archivo de conexión

    // Obtener el ID de la venta desde el parámetro de la URL
    $venta_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Crear la conexión
    $conexion = mysqli_connect($host, $username, $password, $dbname);

    // Verificar la conexión
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Consultar detalles de la venta
    $query_venta = "SELECT v.id, v.fecha, v.total, v.condicion, c.nombre AS cliente 
                    FROM venta v 
                    INNER JOIN cliente c ON v.cliente_id = c.id 
                    WHERE v.id = ?";
    $stmt_venta = $conexion->prepare($query_venta);
    $stmt_venta->bind_param("i", $venta_id);
    $stmt_venta->execute();
    $venta_result = $stmt_venta->get_result();
    $venta = $venta_result->fetch_assoc();

    // Consultar productos de la venta
    $query_detalles = "SELECT p.nombre AS producto, dv.cantidad, dv.subtotal 
                       FROM detalle_venta dv 
                       INNER JOIN producto p ON dv.producto_id = p.id 
                       WHERE dv.venta_id = ?";
    $stmt_detalles = $conexion->prepare($query_detalles);
    $stmt_detalles->bind_param("i", $venta_id);
    $stmt_detalles->execute();
    $detalles_result = $stmt_detalles->get_result();

    // Verificar errores en las consultas
    if (!$venta || !$detalles_result) {
        die("Error al obtener los detalles de la venta: " . mysqli_error($conexion));
    }
    ?>

    <!-- Información de la venta -->
    <div class="mb-4">
        <h3 class="h4">Información de la Venta</h3>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($venta['id']); ?></p>
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($venta['cliente']); ?></p>
        <p><strong>Fecha:</strong> <?php echo htmlspecialchars($venta['fecha']); ?></p>
        <p><strong>Total:</strong> <?php echo htmlspecialchars($venta['total']); ?></p>
        <p><strong>Condición:</strong> <?php echo htmlspecialchars($venta['condicion']); ?></p>
    </div>

    <!-- Tabla de detalles de la venta -->
    <h3 class="h4">Productos Comprados</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($detalle = $detalles_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($detalle['producto']); ?></td>
                    <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars($detalle['subtotal']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>
    <a href="ver_ventas.php" class="btn btn-secondary">Volver a Ventas</a>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
