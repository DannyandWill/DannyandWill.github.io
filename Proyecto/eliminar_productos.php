<?php
ob_start();
require_once "vistas/parte_superior.php";
?>
<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Eliminar Producto</h1>
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

        // Eliminar registros relacionados en stock
        $query_stock = "DELETE FROM stock WHERE producto_id = ?";
        if ($stmt = mysqli_prepare($conexion, $query_stock)) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Eliminar el producto
        $query_producto = "DELETE FROM producto WHERE id = ?";
        if ($stmt = mysqli_prepare($conexion, $query_producto)) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ver_productos.php");
                exit;
            } else {
                echo "Error al eliminar el producto: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($conexion);
        }
    } else {
        $id = $_GET['id'];

        $query = "SELECT * FROM producto WHERE id = ?";
        if ($stmt = mysqli_prepare($conexion, $query)) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                $producto = mysqli_fetch_assoc($result);
            } else {
                echo "Error al obtener el producto: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($conexion);
        }
    }
    ?>

    <div class="container">
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>">
            <p>¿Estás seguro de que deseas eliminar el producto "<?php echo htmlspecialchars($producto['nombre']); ?>"?</p>
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <a href="ver_productos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
