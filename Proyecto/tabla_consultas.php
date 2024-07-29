<?php
require_once "vistas/parte_superior.php";
include '../conexion.php'; // Incluir el archivo de conexión
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SB Admin 2 - Consultas de Ventas</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">
        <!-- Sidebar -->
        <!-- Tu código de barra lateral aquí -->
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <!-- Tu código de barra superior aquí -->
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Consultas de Ventas</h1>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Consulta de Ventas</h6>
                        </div>
                        <div class="card-body">
                            <!-- Formulario de consulta -->
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label for="cliente_id">Consultar ventas por cliente:</label>
                                    <input type="text" id="cliente_id" name="cliente_id" class="form-control" placeholder="Ingrese ID del cliente">
                                </div>
                                <button type="submit" name="consultar_cliente" class="btn btn-primary">Consultar</button>
                            </form>

                            <form method="POST" action="" class="mt-3">
                                <div class="form-group">
                                    <label for="producto_id">Consultar ventas por producto:</label>
                                    <input type="text" id="producto_id" name="producto_id" class="form-control" placeholder="Ingrese ID del producto">
                                </div>
                                <button type="submit" name="consultar_producto" class="btn btn-primary">Consultar</button>
                            </form>

                            <form method="POST" action="" class="mt-3">
                                <button type="submit" name="ranking_ventas" class="btn btn-primary">Consultar Ranking de Ventas</button>
                            </form>

                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Venta ID</th>
                                            <th>Producto ID</th>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                            <th>Cliente ID</th>
                                            <th>Fecha</th>
                                            <th>Total</th>
                                            <th>Condición</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_POST['consultar_cliente']) && !empty($_POST['cliente_id'])) {
                                            $cliente_id = mysqli_real_escape_string($conexion, $_POST['cliente_id']);

                                            $query = "
                                                SELECT d.*, v.cliente_id, v.fecha, v.total, v.condicion, p.nombre 
                                                FROM detalle_venta d
                                                JOIN venta v ON d.venta_id = v.id
                                                JOIN producto p ON d.producto_id = p.id
                                                WHERE v.cliente_id = ?
                                            ";

                                            if ($stmt = mysqli_prepare($conexion, $query)) {
                                                mysqli_stmt_bind_param($stmt, 'i', $cliente_id);
                                                mysqli_stmt_execute($stmt);
                                                $result = mysqli_stmt_get_result($stmt);

                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row["id"] . "</td>";
                                                        echo "<td>" . $row["venta_id"] . "</td>";
                                                        echo "<td>" . $row["producto_id"] . "</td>";
                                                        echo "<td>" . $row["nombre"] . "</td>";
                                                        echo "<td>" . $row["cantidad"] . "</td>";
                                                        echo "<td>" . $row["subtotal"] . "</td>";
                                                        echo "<td>" . $row["cliente_id"] . "</td>";
                                                        echo "<td>" . $row["fecha"] . "</td>";
                                                        echo "<td>" . $row["total"] . "</td>";
                                                        echo "<td>" . $row["condicion"] . "</td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='10'>No hay ventas para el cliente especificado.</td></tr>";
                                                }

                                                mysqli_stmt_close($stmt);
                                            }
                                        }

                                        if (isset($_POST['consultar_producto']) && !empty($_POST['producto_id'])) {
                                            $producto_id = mysqli_real_escape_string($conexion, $_POST['producto_id']);

                                            $query = "
                                                SELECT d.*, v.cliente_id, v.fecha, v.total, v.condicion, p.nombre 
                                                FROM detalle_venta d
                                                JOIN venta v ON d.venta_id = v.id
                                                JOIN producto p ON d.producto_id = p.id
                                                WHERE d.producto_id = ?
                                            ";

                                            if ($stmt = mysqli_prepare($conexion, $query)) {
                                                mysqli_stmt_bind_param($stmt, 'i', $producto_id);
                                                mysqli_stmt_execute($stmt);
                                                $result = mysqli_stmt_get_result($stmt);

                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row["id"] . "</td>";
                                                        echo "<td>" . $row["venta_id"] . "</td>";
                                                        echo "<td>" . $row["producto_id"] . "</td>";
                                                        echo "<td>" . $row["nombre"] . "</td>";
                                                        echo "<td>" . $row["cantidad"] . "</td>";
                                                        echo "<td>" . $row["subtotal"] . "</td>";
                                                        echo "<td>" . $row["cliente_id"] . "</td>";
                                                        echo "<td>" . $row["fecha"] . "</td>";
                                                        echo "<td>" . $row["total"] . "</td>";
                                                        echo "<td>" . $row["condicion"] . "</td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='10'>No hay ventas para el producto especificado.</td></tr>";
                                                }

                                                mysqli_stmt_close($stmt);
                                            }
                                        }

                                        if (isset($_POST['ranking_ventas'])) {
                                            $query = "
                                                SELECT id, cliente_id, fecha, total 
                                                FROM venta 
                                                ORDER BY total DESC 
                                                LIMIT 1
                                            ";

                                            if ($stmt = mysqli_prepare($conexion, $query)) {
                                                mysqli_stmt_execute($stmt);
                                                $result = mysqli_stmt_get_result($stmt);

                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<tr>";
                                                        echo "<td>" . $row["id"] . "</td>";
                                                        echo "<td colspan='9'>Venta con mayor importe: ID " . $row["id"] . " - Total " . $row["total"] . "</td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='10'>No hay ventas registradas.</td></tr>";
                                                }

                                                mysqli_stmt_close($stmt);
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>

<?php
require_once "vistas/parte_inferior.php";
?>
