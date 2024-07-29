<?php
ob_start();
require_once "vistas/parte_superior.php";
?>
<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Eliminar Venta</h1>
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

        // Eliminar venta
        $query = "DELETE FROM venta WHERE id = ?";
        if ($stmt = mysqli_prepare($conexion, $query)) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            if (mysqli_stmt_execute($stmt)) {
                // Eliminar detalles de venta si es necesario
                $query_detalles = "DELETE FROM detalle_venta WHERE venta_id = ?";
                if ($stmt_detalle = mysqli_prepare($conexion, $query_detalles)) {
                    mysqli_stmt_bind_param($stmt_detalle, 'i', $id);
                    mysqli_stmt_execute($stmt_detalle);
                    mysqli_stmt_close($stmt_detalle);
                }
                header("Location: ver_ventas.php");
                exit;
            } else {
                echo "Error al eliminar la venta: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($conexion);
        }
    } else {
        $id = $_GET['id'];

        // Obtener datos de la venta
        $query = "SELECT v.id, v.fecha, v.total, v.condicion, c.nombre AS cliente 
                  FROM venta v 
                  INNER JOIN cliente c ON v.cliente_id = c.id 
                  WHERE v.id = ?";
        if ($stmt = mysqli_prepare($conexion, $query)) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                $venta = mysqli_fetch_assoc($result);
            } else {
                echo "Error al obtener la venta: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($conexion);
        }
    }
    ?>

    <div class="container">
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($venta['id']); ?>">
            <p>¿Estás seguro de que deseas eliminar la venta con ID <?php echo htmlspecialchars($venta['id']); ?>?</p>
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <a href="ver_ventas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
