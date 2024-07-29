<?php
ob_start();
require_once "vistas/parte_superior.php";
?>
<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Editar Cliente</h1>
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
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];

        // Actualizar cliente
        $query = "UPDATE cliente SET nombre = ?, direccion = ?, telefono = ?, email = ? WHERE id = ?";
        if ($stmt = mysqli_prepare($conexion, $query)) {
            mysqli_stmt_bind_param($stmt, 'ssssi', $nombre, $direccion, $telefono, $email, $id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ver_clientes.php");
                exit;
            } else {
                echo "Error al actualizar el cliente: " . mysqli_error($conexion);
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
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($cliente['direccion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
        <br>
        <a href="ver_clientes.php" class="btn btn-secondary">Volver a Clientes</a>
    </div>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
