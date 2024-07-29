<?php
ob_start();
require_once "vistas/parte_superior.php"; 
?>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Agregar Usuario</h1>
    <?php
    include '../conexion.php'; // Incluye el archivo de conexión

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Hash de la contraseña
        $rol_id = $_POST['rol_id'];

        // Conectar a la base de datos
        $conexion = mysqli_connect($host, $username, $password, $dbname);

        if (!$conexion) {
            die("Error de conexión: " . mysqli_connect_error());
        }

        try {
            $stmt = $conexion->prepare("INSERT INTO usuario (nombre, email, contrasena, rol_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $nombre, $email, $contrasena, $rol_id);
            $stmt->execute();

            header("Location: ver_usuarios.php");
            exit;
        } catch (Exception $e) {
            die("Error al agregar el usuario: " . $e->getMessage());
        }

        // Cerrar la conexión
        $stmt->close();
        mysqli_close($conexion);
    }
    ?>

    <form method="post">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="contrasena">Contraseña:</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
        </div>
        <div class="form-group">
            <label for="rol_id">Rol:</label>
            <select class="form-control" id="rol_id" name="rol_id" required>
                <?php
                // Obtener lista de roles
                $result = mysqli_query($conexion, "SELECT id, nombre FROM rol");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
    <br>
    <a href="ver_usuarios.php" class="btn btn-secondary">Volver a Usuarios</a>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
