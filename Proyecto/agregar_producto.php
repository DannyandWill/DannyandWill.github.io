<?php
ob_start();
require_once "vistas/parte_superior.php"; 
?>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Agregar Producto</h1>
    <?php
    include '../conexion.php'; // Incluye el archivo de conexión

    // Conectar a la base de datos
    $conexion = mysqli_connect($host, $username, $password, $dbname);

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $categoria_id = $_POST['categoria_id'];
        $proveedor_id = $_POST['proveedor_id'];

        // Iniciar transacción
        mysqli_begin_transaction($conexion);

        try {
            // Insertar producto
            $stmt = $conexion->prepare("INSERT INTO producto (nombre, descripcion, precio, categoria_id, proveedor_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $categoria_id, $proveedor_id);
            $stmt->execute();
            $producto_id = $stmt->insert_id; // Obtener el ID del producto recién insertado

            // Insertar stock inicial
            $cantidad = $_POST['cantidad'];
            $stmt_stock = $conexion->prepare("INSERT INTO stock (producto_id, cantidad) VALUES (?, ?)");
            $stmt_stock->bind_param("ii", $producto_id, $cantidad);
            $stmt_stock->execute();

            // Confirmar transacción
            mysqli_commit($conexion);
            header("Location: ver_productos.php");
            exit;
        } catch (Exception $e) {
            // Deshacer transacción en caso de error
            mysqli_rollback($conexion);
            die("Error al agregar el producto: " . $e->getMessage());
        }

        // Cerrar la conexión
        $stmt->close();
        mysqli_close($conexion);
    }
    ?>

    <form method="post">
        <div class="form-group">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
        </div>
        <div class="form-group">
            <label for="categoria_id">Categoría:</label>
            <select class="form-control" id="categoria_id" name="categoria_id" required>
                <?php
                // Obtener lista de categorías
                $result_categoria = mysqli_query($conexion, "SELECT id, nombre FROM categoria");
                while ($row = mysqli_fetch_assoc($result_categoria)) {
                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="proveedor_id">Proveedor:</label>
            <select class="form-control" id="proveedor_id" name="proveedor_id" required>
                <?php
                // Obtener lista de proveedores
                $result_proveedor = mysqli_query($conexion, "SELECT id, nombre FROM proveedor");
                while ($row = mysqli_fetch_assoc($result_proveedor)) {
                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
    <br>
    <a href="ver_productos.php" class="btn btn-secondary">Volver a Productos</a>
</div>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
