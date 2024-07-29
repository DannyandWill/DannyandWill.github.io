<?php 
require_once "vistas/parte_superior.php";
require_once "../conexion.php"; // Asegúrate de que este archivo contiene tu conexión a la base de datos

// Inicializa las variables con valores predeterminados
$usuarios = 0;
$clientes = 0;
$proveedores = 0;
$productos = 0;

// Consulta para obtener el número de usuarios
$sqlUsuarios = "SELECT COUNT(*) as total FROM usuario";
$resultUsuarios = mysqli_query($conexion, $sqlUsuarios);
if ($resultUsuarios) {
    $row = mysqli_fetch_assoc($resultUsuarios);
    $usuarios = $row['total'];
}

// Consulta para obtener el número de clientes
$sqlClientes = "SELECT COUNT(*) as total FROM cliente";
$resultClientes = mysqli_query($conexion, $sqlClientes);
if ($resultClientes) {
    $row = mysqli_fetch_assoc($resultClientes);
    $clientes = $row['total'];
}

// Consulta para obtener el número de proveedores
$sqlProveedores = "SELECT COUNT(*) as total FROM proveedor";
$resultProveedores = mysqli_query($conexion, $sqlProveedores);
if ($resultProveedores) {
    $row = mysqli_fetch_assoc($resultProveedores);
    $proveedores = $row['total'];
}

// Consulta para obtener el número de productos
$sqlProductos = "SELECT COUNT(*) as total FROM producto";
$resultProductos = mysqli_query($conexion, $sqlProductos);
if ($resultProductos) {
    $row = mysqli_fetch_assoc($resultProductos);
    $productos = $row['total'];
}

// Consulta para obtener el número de Ventas
$sqlVentas = "SELECT COUNT(*) as total FROM venta";
$resultVentas = mysqli_query($conexion, $sqlVentas);
if ($resultVentas) {
    $row = mysqli_fetch_assoc($resultVentas);
    $Ventas = $row['total'];
}

?>


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Panel de Administración</h1>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Usuarios Card -->
    <a class="col-xl-3 col-md-6 mb-4" href="ver_usuarios.php">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Usuarios</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($usuarios); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <!-- Clientes Card -->
    <a class="col-xl-3 col-md-6 mb-4" href="ver_clientes.php">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Clientes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($clientes); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <!-- Proveedores Card -->
    <a class="col-xl-3 col-md-6 mb-4" href="ver_proveedores.php">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Proveedores</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($proveedores); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <!-- Productos Card -->
    <a class="col-xl-3 col-md-6 mb-4" href="ver_productos.php">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Productos</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo htmlspecialchars($productos); ?></div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <!-- Ventas Card -->
    <a class="col-xl-3 col-md-6 mb-4" href="ver_ventas.php">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Ventas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($Ventas); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<?php require_once "vistas/parte_inferior.php"; ?>
