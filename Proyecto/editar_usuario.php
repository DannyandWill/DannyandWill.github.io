<?php
ob_start();
require_once "vistas/parte_superior.php";
?>
<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Editar Usuario</h1>
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
        $email = $_POST['email'];
        $rol_id = $_POST['rol_id'];

        // Actualizar usuario
        $query = "UPDATE usuario SET nombre = ?, email = ?, rol_id = ? WHERE id = ?";
        if ($stmt = mysqli_prepare($conexion, $query)) {
            mysqli_stmt_bind_param($stmt, 'ssii', $nombre, $email, $rol_id, $id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ver_usuarios.php");
                exit;
            } else {
                echo "Error al actualizar el usuario: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($conexion);
        }
    } else {
        $id = $_GET['id'];

        // Obtener datos del usuario
        $query = "SELECT * FROM usuario WHERE id = ?";
        if ($stmt = mysqli_prepare($conexion, $query)) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                $usuario = mysqli_fetch_assoc($result);
            } else {
                echo "Error al obtener el usuario: " . mysqli_error($conexion);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($conexion);
        }

        // Obtener lista de roles
        $query_roles = "SELECT * FROM rol";
        $result_roles = mysqli_query($conexion, $query_roles);

        if (!$result_roles) {
            echo "Error al obtener los roles: " . mysqli_error($conexion);
        }
    }
    ?>

    <div class="container">
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['id']); ?>">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="rol_id">Rol:</label>
                <select class="form-control" id="rol_id" name="rol_id" required>
                    <?php while ($rol = mysqli_fetch_assoc($result_roles)): ?>
                        <option value="<?php echo $rol['id']; ?>" <?php echo $rol['id'] == $usuario['rol_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($rol['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
        <br>
        <a href="ver_usuarios.php" class="btn btn-secondary">Volver a Usuarios</a>
    </div>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
