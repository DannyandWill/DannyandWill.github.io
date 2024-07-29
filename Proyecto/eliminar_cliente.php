<?php
ob_start();
require_once "vistas/parte_superior.php";
?>
<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Eliminar Cliente</h1>
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

        // Eliminar cliente
        $query = "DELETE FROM cliente WHERE id = ?";
        if ($stmt = mysqli_prepare($conexion, $query)) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ver_clientes.php");
                exit;
            } else {
                echo "Error al eliminar el cliente: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($conexion);
        }
    } else {
        $id = $_GET['id'];

        // Obtener datos del cliente
        $query = "SELECT * FROM cliente WHERE id = ?";
        if ($stmt = mysqli_prepare($conexion, $query)) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                $cliente = mysqli_fetch_assoc($result);
            } else {
                echo "Error al obtener el cliente: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($conexion);
        }
    }
    ?>

    <div class="container">
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($cliente['id']); ?>">
            <p>¿Estás seguro de que deseas eliminar el cliente "<?php echo htmlspecialchars($cliente['nombre']); ?>"?</p>
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <a href="ver_clientes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
