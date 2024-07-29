<?php
ob_start();
require_once "vistas/parte_superior.php"; 
?>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Editar Venta</h1>
    <?php
    include '../conexion.php'; // Incluye el archivo de conexión

    // Crear la conexión
    $conexion = mysqli_connect($host, $username, $password, $dbname);

    // Verificar la conexión
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $producto = $_POST['producto'];
        $cantidad = $_POST['cantidad'];

        $query_update = "UPDATE detalle_venta SET producto_id = ?, cantidad = ? WHERE id = ?";
        
        if ($stmt = mysqli_prepare($conexion, $query_update)) {
            mysqli_stmt_bind_param($stmt, 'iii', $producto, $cantidad, $id);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: ver_ventas.php");
                exit;
            } else {
                die("Error al actualizar la venta: " . mysqli_error($conexion));
            }

            mysqli_stmt_close($stmt);
        } else {
            die("Error en la preparación de la consulta: " . mysqli_error($conexion));
        }
    } else {
        $id = $_GET['id'];

        $query_select = "SELECT d.*, v.fecha, v.total, v.condicion 
                         FROM detalle_venta d
                         JOIN venta v ON d.venta_id = v.id
                         WHERE d.id = ?";

        if ($stmt = mysqli_prepare($conexion, $query_select)) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($venta = mysqli_fetch_assoc($result)) {
                mysqli_stmt_close($stmt);
            } else {
                die("No se encontró el detalle de la venta.");
            }
        } else {
            die("Error en la preparación de la consulta: " . mysqli_error($conexion));
        }
    }
    ?>

    <form method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($venta['id']); ?>">
        <div class="form-group">
            <label for="producto">Producto:</label>
            <input type="text" class="form-control" id="producto" name="producto" value="<?php echo htmlspecialchars($venta['producto_id']); ?>" required>
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($venta['cantidad']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
    <br>
    <a href="ver_ventas.php" class="btn btn-secondary">Volver a Ventas</a>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
