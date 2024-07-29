<?php
ob_start();
require_once "vistas/parte_superior.php"; 
?>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800">Agregar Venta</h1>
    <?php
    include '../conexion.php'; // Incluye el archivo de conexión

    $conexion = mysqli_connect($host, $username, $password, $dbname);

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cliente_id = $_POST['cliente_id'];
        $fecha = date('Y-m-d'); // fecha actual
        $total = $_POST['total'];
        $condicion = $_POST['condicion'];

        // Iniciar transacción
        mysqli_begin_transaction($conexion);

        try {
            // Insertar venta
            $stmt = $conexion->prepare("INSERT INTO venta (cliente_id, fecha, total, condicion) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isis", $cliente_id, $fecha, $total, $condicion);
            $stmt->execute();
            $venta_id = $stmt->insert_id; // Obtener el ID de la venta recién insertada

            // Insertar detalles de la venta
            $productos = $_POST['productos']; // Se espera un array de productos
            foreach ($productos as $producto) {
                $producto_id = $producto['producto_id'];
                $cantidad = $producto['cantidad'];

                // Obtener el precio del producto
                $stmt_precio = $conexion->prepare("SELECT precio FROM producto WHERE id = ?");
                $stmt_precio->bind_param("i", $producto_id);
                $stmt_precio->execute();
                $result_precio = $stmt_precio->get_result();
                $row_precio = $result_precio->fetch_assoc();
                $precio_unitario = $row_precio['precio']; // Precio del producto
                $subtotal = $cantidad * $precio_unitario;

                // Inserción en detalle_venta sin precio_unitario
                $stmt_detalle = $conexion->prepare("INSERT INTO detalle_venta (venta_id, producto_id, cantidad, subtotal) VALUES (?, ?, ?, ?)");
                $stmt_detalle->bind_param("iids", $venta_id, $producto_id, $cantidad, $subtotal);
                $stmt_detalle->execute();
            }

            // Confirmar transacción
            mysqli_commit($conexion);
            header("Location: ver_ventas.php");
            exit;
        } catch (Exception $e) {
            // Deshacer transacción en caso de error
            mysqli_rollback($conexion);
            die("Error al agregar la venta: " . $e->getMessage());
        }

        // Cerrar la conexión
        $stmt->close();
        $stmt_precio->close();
        mysqli_close($conexion);
    }
    ?>

    <form method="post" id="venta-form">
        <div class="form-group">
            <label for="cliente_id">Cliente:</label>
            <select class="form-control" id="cliente_id" name="cliente_id" required>
                <?php
                // Obtener lista de clientes
                $result = mysqli_query($conexion, "SELECT id, nombre FROM cliente");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="condicion">Condición:</label>
            <select class="form-control" id="condicion" name="condicion" required>
                <option value="contado">Contado</option>
                <option value="credito">Crédito</option>
            </select>
        </div>
        <div class="form-group">
            <label for="total">Total:</label>
            <input type="number" step="0.01" class="form-control" id="total" name="total" required readonly>
        </div>
        <div class="form-group">
            <label for="productos">Productos:</label>
            <div id="productos">
                <!-- Sección dinámica para productos -->
                <div class="producto">
                    <select class="form-control producto-id" name="productos[0][producto_id]" required>
                        <?php
                        // Obtener lista de productos
                        $result = mysqli_query($conexion, "SELECT id, nombre, precio FROM producto");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . htmlspecialchars($row['id']) . '" data-precio="' . htmlspecialchars($row['precio']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                        }
                        ?>
                    </select>
                    <input type="number" class="form-control cantidad" name="productos[0][cantidad]" placeholder="Cantidad" required>
                </div>
            </div>
            <button type="button" id="agregar_producto" class="btn btn-info">Agregar otro producto</button>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
    <br>
    <a href="ver_ventas.php" class="btn btn-secondary">Volver a Ventas</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productoContainer = document.getElementById('productos');
    let productoIndex = 1;

    document.getElementById('agregar_producto').addEventListener('click', function() {
        const newProducto = document.createElement('div');
        newProducto.classList.add('producto');
        newProducto.innerHTML = `
            <select class="form-control producto-id" name="productos[${productoIndex}][producto_id]" required>
                <?php
                // Obtener lista de productos
                $result = mysqli_query($conexion, "SELECT id, nombre, precio FROM producto");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . htmlspecialchars($row['id']) . '" data-precio="' . htmlspecialchars($row['precio']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                }
                ?>
            </select>
            <input type="number" class="form-control cantidad" name="productos[${productoIndex}][cantidad]" placeholder="Cantidad" required>
        `;
        productoContainer.appendChild(newProducto);
        productoIndex++;
    });

    productoContainer.addEventListener('input', function() {
        let total = 0;
        document.querySelectorAll('.producto').forEach(producto => {
            const selectProducto = producto.querySelector('.producto-id');
            const cantidad = parseFloat(producto.querySelector('.cantidad').value) || 0;
            const precioUnitario = parseFloat(selectProducto.options[selectProducto.selectedIndex].dataset.precio) || 0;
            total += cantidad * precioUnitario;
        });
        document.getElementById('total').value = total.toFixed(2);
    });
});
</script>

<?php 
require_once "vistas/parte_inferior.php"; 
ob_end_flush();
?>
